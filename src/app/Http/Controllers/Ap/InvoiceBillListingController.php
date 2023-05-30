<?php
/**
 *Created by PhpStorm
 *Created at ২২/৯/২১ ১০:৪৩ AM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApContract;
use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApInvoice;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Ap\FasApVendors;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\Ap\HoldFlag;
use App\Enums\Common\LGlInteModules;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ap\ApManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceBillListingController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    /** @var ApManager */
    private $apManager;

    protected $invoice;
    private $currency;
    private $attachment;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager, ApContract $apManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->apManager = $apManager;
        $this->invoice = new FasApInvoice();
        $this->currency = new LCurrency();
        $this->attachment = new FasApInvoiceDoc();
    }

    public function index($filter = null)
    {

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['billSecs'] = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['vendors'] = $this->apLookupManager->getVendors();
        $data['invoiceStatus'] = $this->apLookupManager->getInvoiceStatus();
        $filterData = isset($filter) ? explode('#', Crypt::decryptString($filter)) : $filter;

        return view('ap.invoice-bill-listing.index', compact('data', 'fiscalYear', 'filterData'));
    }

    public function update(Request $request)
    {
        $oldFlag = $request->post('oldFlag');
        $reason = $request->post('holdUnHoldReason');
        $invoice = $request->post('invoiceId');

        if ($oldFlag == HoldFlag::HOLD) {
            $newFlag = HoldFlag::UN_HOLD;
        } else {
            $newFlag = HoldFlag::HOLD;
        }
        $status_code = sprintf("%4000s", "");
        $status_message = sprintf("%4000s", "");

        $param = [
            "p_action_type" => 'U',
            "p_invoice_id" => $invoice,
            "p_payment_hold_flag" => $newFlag,
            "p_payment_hold_reason" => $reason,
            //"p_trans_date" => '',
            "p_user_id" => auth()->id(),
            "o_status_code" => &$status_code,
            "o_status_message" => &$status_message
        ];

        DB::beginTransaction();
        try {
            DB::executeProcedure("cpaacc.fas_ap_trans.trans_ap_invoice_hold_unlhold", $param);
            DB::commit();
            return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => $status_code, "response_msg" => $e->getMessage()]);
        }
    }

    public function dataList(Request $request)
    {
        $filteredData = Crypt::encryptString($request->post('fiscalYear') . '#' . $request->post('period') . '#' . $request->post('bill_section') . '#' . $request->post('bill_reg_id') . '#' . $request->post('approval_status'));
        #dd($filteredData, Crypt::decryptString($filteredData));
        /*$partySubLedger = $request->post('ap_party_sub_ledger');
        $invoiceType = $request->post('ap_invoice_type');
        $vendor = $request->post('ap_vendor');*/
        $fiscalYear = $request->post('fiscalYear');
        $period = $request->post('period');
        /*$postingDate = $request->post('posting_date');
        $batchId = $request->post('posting_batch_id');
        $documentNo = $request->post('ap_document_no');
        $department = $request->post('department');*/
        $billSection = $request->post('bill_section');
        $billReg = $request->post('bill_reg_id');
        $docNo =  $request->post('document_no');
        $approvalStatus = $request->post('approval_status');

        //$data = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_entry_list(:p_trans_period_id, :p_trans_date, :p_trans_batch_id, :p_document_no, :p_gl_subsidiary_id, :p_invoice_type_id, :p_vendor_id, :p_department_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status) from dual",["p_trans_period_id"=>$period,"p_trans_date"=>$postingDate,"p_trans_batch_id"=>$batchId,"p_document_no"=>$documentNo,"p_gl_subsidiary_id"=>$partySubLedger,"p_invoice_type_id"=>$invoiceType,"p_vendor_id"=>$vendor,"p_department_id"=>$department,"p_bill_sec_id"=>$billSection,"p_bill_reg_id"=>$billReg,"p_workflow_approval_status"=>$approvalStatus]);
        $data = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_entry_list(:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_document_no,:p_user_id) from dual", ["p_fiscal_year_id"=>$fiscalYear,"p_trans_period_id" => $period, "p_bill_sec_id" => $billSection, "p_bill_reg_id" => $billReg, "p_workflow_approval_status" => $approvalStatus, "p_document_no" => $docNo,'p_user_id'=>Auth::user()->user_id]);

        return datatables()->of($data)
            /*->addIndexColumn()
            ->editColumn('batch_id', function ($data) {
                return $data->batch_id;
            })*/
            ->editColumn('document_date', function ($data) {
                return HelperClass::dateConvert($data->document_date);
            })
            ->editColumn('invoice_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->invoice_amount);
            })
            ->editColumn('tax_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->tax_amount);
            })
            ->editColumn('vat_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->vat_amount);
            })
            ->editColumn('security_deposit', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->security_deposit);
            })
            ->editColumn('other_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->other_amount);
            })
            ->editColumn('payable_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->payable_amount);
            })
            ->editColumn('invoice_status', function ($data) {
                return $data->approval_status;
            })
            /*->editColumn('hold_unhold', function ($data) {
                if ($data->payment_hold_flag == '1') {
                    return "<button type='button' class='btn btn-sm btn-light-warning hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Unhold</button>";
                } else {
                    return "<button type='button' class='btn btn-sm btn-light-success hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Hold</button>";
                }
            })*/
            ->editColumn('action', function ($data) use ($filteredData) {
                $action = "";
                if ($data->payment_hold_flag == '1') {
                    $action .= "<button type='button' class='btn btn-sm btn-light-warning hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Unhold</button>";
                } else {
                    $action .= "<button type='button' class='btn btn-sm btn-light-success hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Hold</button>";
                }
                return $action . " || <a style='text-decoration:underline' href='" . route('invoice-bill-listing.view', ['id' => $data->invoice_id, 'filter' => $filteredData]) .
                    "' class='' data-target='' ><i class='bx bx-show'></i></a>
                ||  <a  target='_blank' href='" . request()->root() . "/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=" . $data->trans_period_id . "&p_trans_batch_id=" . $data->batch_id . "&type=pdf&filename=transaction_list_batch_wise'><i class='bx bx-printer cursor-pointer font-small-4'></i></a>";
            })
            ->rawColumns(['hold_unhold', 'action'])
            ->make(true);
    }

    public function view($id, $filter = null)
    {
        $user_id = auth()->id();
        //$inserted_data = $this->invoice->where('invoice_id','=',$id)->with('vendor.vendor_category','bill_section','bill_reg','invoice_type','invoice_line.gl_acc_detail','invoice_file')->first();
        $inserted_data = DB::selectOne("select cpaacc.fas_ap_trans.get_ap_invoice_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        if ($inserted_data) {
            $inserted_data->invoice_budget = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_budget_list(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
            $inserted_data->invoice_line = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_trans_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
            $inserted_data->invoice_file = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_docs_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        }

        //$fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();

        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        //$billRegs = $this->lookupManager->getBillRegisterOnFunction(ApFunType::AP_INVOICE_BILL_ENTRY);
        //dd($inserted_data);
        $vendorType = $this->apLookupManager->getVendorTypes();
        $vendorCategory = $this->apLookupManager->getVendorCategory();
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['invoice_type'] = $this->apLookupManager->findInvoiceType();
        $data['currency'] = $this->currency->get();
        $coaParams = $this->lookupManager->getSpecifiedGlCoaParams([\App\Enums\Common\GlCoaParams::ASSET, \App\Enums\Common\GlCoaParams::EXPENSE]);
        $paymentTerms = $this->lookupManager->getPaymentTerms();
        $paymentMethod = $this->lookupManager->getPaymentMethods();
        $budgetEntry = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_trans_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        //$roleWiseUser = $this->apManager->findRoleWiseUser(WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL, WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE, $user_id);  //Add & call this function  :pavel-29-05-2022

        return view('ap.invoice-bill-listing.view', compact('paymentMethod', 'paymentTerms', 'fiscalYear', 'department', 'billSecs', 'data', 'coaParams', 'vendorType', 'vendorCategory', 'inserted_data', 'filter'));
    }

    public function download($id)
    {
        $attachment = $this->attachment->where('doc_file_id', '=', $id)->first();
        $content = base64_decode($attachment->doc_file_content);

        return response()->make($content, 200, [
            'Content-Type' => $attachment->doc_file_type,
            'Content-Disposition' => 'attachment;filename="' . $attachment->doc_file_name . '"'
        ]);
    }

    public function updateInvoiceEntry(Request $request)
    {
        //dd($request->all());
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");

        /*$params = [
            'p_invoice_id' => $request->post('invoiceId'),
            'p_trans_period_id' => $request->post('postingPeriod'),
            'p_trans_date' => HelperClass::dateFormatForDB($request->post('postingDate')),
            'p_document_date' => HelperClass::dateFormatForDB($request->post('documentDate')),
            'p_document_no' => $request->post('documentNumber'),
            'p_document_ref' => $request->post('documentRef'),
            'p_department_id' => $request->post('department'),
            'p_budget_dept_id' => $request->post('budgetDepartment'),
            'p_bill_reg_id' => $request->post('billRegister'),
            'p_bill_sec_id' => $request->post('billSection'),
            'p_narration' => $request->post('documentNarration'),
            'p_budget_head_id' => $request->post('budgetHead'),
            'p_user_id' => Auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];*/
        $ap_without_budget_info = $request->post('ap_without_budget_info') ? $request->post('ap_without_budget_info') : null;
        $params = [
            'p_invoice_id' => $request->post('invoice_id'),
            'p_trans_period_id' => $request->post('edt_period'),
            'p_trans_date' => HelperClass::dateFormatForDB($request->post('edt_posting_date')),
            'p_document_date' => HelperClass::dateFormatForDB($request->post('edt_document_date')),
            'p_document_no' => $request->post('edt_document_number'),
            'p_document_ref' => $request->post('edt_document_reference'),
            'p_department_id' => $request->post('edt_department'),
            //'p_budget_dept_id' => $request->post('edt_budget_department'),
            'p_bill_reg_id' => $request->post('edt_bill_register'),
            'p_bill_sec_id' => $request->post('edt_bill_section'),
            'p_narration' => $request->post('edt_narration'),
            'p_without_budget_yn' => ($ap_without_budget_info != null) ? $ap_without_budget_info : YesNoFlag::NO,
            //'p_budget_head_id' => $request->post('b_head_id'),
            'p_employee_type_id' => $request->post('edt_emp_type_id') ? $request->post('edt_emp_type_id') : null,
            'p_user_id' => Auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        DB::beginTransaction();
        try {
            DB::executeProcedure("CPAACC.fas_ap_trans.trans_ap_invoice_ref_update", $params);//dd($params);
            //Log::info($params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_message" => $status_message]);
            } else {
                $addLineBudget = $request->get('addLineBudget');
                if ($addLineBudget && $ap_without_budget_info != 'Y') {
                    $bud_status_code = sprintf("%4000d", "");
                    $bud_status_message = sprintf("%4000s", "");//dd($addLineBudget);
                    foreach ($addLineBudget as $key => $line) {
                        //if($line['bzt_add_action_type']!=''){
                        $budgetParams = [
                            'p_action_type' => $line['bzt_add_action_type'] == 'A' ? 'I' : $line['bzt_add_action_type'],
                            'p_budget_trans_id' => $line['budget_trans_id'],
                            'p_invoice_id' => $request->post('invoice_id'),
                            'p_budget_dept_id' => $line['budget_dept_id'],
                            'p_budget_head_id' => $line['b_head_id'],
                            'p_budget_utilize_amt' => $line['budget_amt'],
                            'p_user_id' => auth()->id(),
                            'o_status_code' => &$bud_status_code,
                            'o_status_message' => &$status_message
                        ];
                        DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_ref_budget_update', $budgetParams);
                        //Log::info($budgetParams);

                        if ($budgetParams['o_status_code'] != "1") {
                            DB::rollBack();
                            return response()->json(["response_code" => $bud_status_code, "response_message" => $bud_status_message]);
                        }
                    }

                    /*** Call to validate function start ***/

                    $validate_ap_invoice_status_code = sprintf("%4000s", "");
                    $validate_ap_invoice_status_message = sprintf("%4000s", "");

                    $validateApInvoiceParams = [
                        'p_invoice_id' => $request->post('invoice_id'),
                        'o_status_code' => &$validate_ap_invoice_status_code,
                        'o_status_message' => &$validate_ap_invoice_status_message,
                    ];

                    DB::executeProcedure('CPAACC.fas_ap_trans.validate_ap_invoice_entry', $validateApInvoiceParams);
                    //Log::info($validateApInvoiceParams);

                    if ($validateApInvoiceParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $validate_ap_invoice_status_code, "response_message" => $validate_ap_invoice_status_message]);
                    }
                    /*** Call to validate function end ***/
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            return ['response_code' => 99, 'response_message' => $e->getMessage()];
            DB::rollBack();
        }

        return ['response_code' => $status_code, 'response_message' => $status_message];
    }

    public function getData(Request $request)
    {
        $b = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_budget_list(:p_invoice_id) from dual", ["p_invoice_id" => $request->get('invoice_id')]);

        return response(
            [
                'result' => $b,
            ]
        );
    }
}

<?php
/**
 *Created by PhpStorm
 *Created at ২২/৯/২১ ১০:৪৩ AM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Ar\ArContract;
use App\Entities\Ap\FasApInvoice;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Ap\FasApVendors;
use App\Entities\Ar\FasArInvoiceDocs;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\HoldFlag;
use App\Enums\Ar\ArFunType;
use App\Enums\Common\LGlInteModules;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\Ar\ArManager;
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
    private $arLookupManager;

    /** @var ArManager */
    private $arManager;

    protected $invoice;
    private $currency;
    private $attachment;

    public function __construct(LookupManager $lookupManager, ArLookupManager $arLookupManager, FlashMessageManager $flashMessageManager, ArContract $arManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->arLookupManager = $arLookupManager;
        $this->arManager = $arManager;
        $this->invoice = new FasApInvoice();
        $this->currency = new LCurrency();
        $this->attachment = new FasArInvoiceDocs();
    }

    public function index($filter = null)
    {
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['billSecs'] = $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_ENTRY);
        $data['subsidiary_type'] = $this->lookupManager->findArPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $data['customerCategory'] = $this->arLookupManager->getCustomerCategory();
        $data['customers'] = $this->arLookupManager->getCustomers();
        $data['invoiceStatus'] = $this->arLookupManager->getInvoiceStatus();
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;
        return view('ar.invoice-bill-listing.index', compact('data','fiscalYear','filterData'));
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
        $fiscalYear = $request->post('fiscalYear');
        $period = $request->post('period');
        /*$postingDate = $request->post('posting_date');
        $batchId = $request->post('posting_batch_id');
        $documentNo = $request->post('ar_document_no');
        $documentReference = $request->post('ar_document_reference');
        $customer = $request->post('ar_customer');
        $approvalStatus = $request->post('approval_status');*/

        $billSection = $request->post('bill_section');
        $billReg = $request->post('bill_reg_id');
        $approvalStatus = $request->post('approval_status');

        //dd($period,$postingDate);

        //$partySubLedger = $request->post('ap_party_sub_ledger');
        /*$invoiceType = $request->post('ap_invoice_type');
        $department = $request->post('department');
        $billSection = $request->post('bill_section');
        $billReg = $request->post('bill_reg_id');*/

        /*$data = FasApInvoice::where('trans_period_id', '=', DB::raw("NVL('" . $period . "',trans_period_id)"))
            ->where('gl_subsidiary_id', '=', DB::raw("NVL('" . $partySubLedger . "',gl_subsidiary_id)"))
            ->where('invoice_type_id', '=', DB::raw("NVL('" . $invoiceType . "',invoice_type_id)"))
            ->where('vendor_id', '=', DB::raw("NVL('" . $vendor . "',vendor_id)"))
            ->where('trans_date', '=', DB::raw("NVL('" . $postingDate . "',trans_date)"))
            ->where('gl_trans_batch_id', '=', DB::raw("NVL('" . $batchId . "',gl_trans_batch_id)"))
            ->where('document_no', '=', DB::raw("NVL('" . $documentNo . "',document_no)"))
            ->where('department_id', '=', DB::raw("NVL('" . $department . "',department_id)"))
            ->where('bill_sec_id', '=', DB::raw("NVL('" . $billSection . "',bill_sec_id)"))
            ->where('bill_reg_id', '=', DB::raw("NVL('" . $billReg . "',bill_reg_id)"))
            ->where('invoice_status_id', '=', DB::raw("NVL('" . $invoiceStatus . "',invoice_status_id)"))
            ->with('invoice_status', 'vendor')
            ->get();*/
        $data = DB::select("select cpaacc.fas_ar_trans.get_ar_invoice_entry_list(:p_fiscal_year_id,:p_trans_period_id, :p_bill_sec_id, :p_bill_reg_id, :p_workflow_approval_status,:p_user_id) from dual",
            [ "p_fiscal_year_id"=>$fiscalYear,"p_trans_period_id" => $period, "p_bill_sec_id" => $billSection, "p_bill_reg_id" => $billReg,"p_workflow_approval_status" => $approvalStatus,'p_user_id'=>Auth::user()->user_id
                /*"p_gl_subsidiary_id" => $partySubLedger,"p_department_id" => $department,"p_bill_sec_id" => $billSection,"p_bill_reg_id" => $billReg,*/
            ]);
        $filteredData = Crypt::encryptString($request->post('fiscalYear') .'#'.$request->post('period') .'#'. $request->post('bill_section') .'#'. $request->post('bill_reg_id') .'#'. $request->post('approval_status'));

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('document_no', function ($data) {
                return $data->document_no;
            })
            ->editColumn('document_date', function ($data) {
                return HelperClass::dateConvert($data->document_date);
            })->editColumn('invoice_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->invoice_amount);
            })->editColumn('vat_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->vat_amount);
            })->editColumn('receivable_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->receivable_amount);
            })
            ->editColumn('document_reference', function ($data) {
                return $data->document_ref;
            })
            ->editColumn('approval_status', function ($data) {
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
                return "<a style='text-decoration:underline' href='" . route('ar-invoice-bill-listing.view', ['id' => $data->invoice_id,'filter'=>$filteredData]) . "' class='' data-target='' ><i class='bx bx-show'></i></a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function view($id, $filter=null)
    {
        $user_id = auth()->id();
        //$inserted_data = $this->invoice->where('invoice_id','=',$id)->with('vendor.vendor_category','bill_section','bill_reg','invoice_type','invoice_line.gl_acc_detail','invoice_file')->first();
        $inserted_data = DB::selectOne("select cpaacc.fas_ar_trans.get_ar_invoice_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        if (isset ($inserted_data)){
            $inserted_data->invoice_line = DB::select("select cpaacc.fas_ar_trans.get_ar_invoice_trans_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
            $inserted_data->invoice_file = DB::select("select cpaacc.fas_ar_trans.get_ar_invoice_docs_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        }


        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_ENTRY);
        //dd($inserted_data, $department);

        $vendorCategory = $this->arLookupManager->getCustomerCategory();
        $data['subsidiary_type'] = $this->lookupManager->findArPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $data['invoice_type'] = $this->arLookupManager->getTransactionType();
        $data['currency'] = $this->currency->get();
        $coaParams = $this->lookupManager->getSpecifiedGlCoaParams([\App\Enums\Common\GlCoaParams::ASSET, \App\Enums\Common\GlCoaParams::EXPENSE]);
        $receiptTerms = $this->lookupManager->getArPaymentTerms();
        $receiptMethods = $this->lookupManager->getArPaymentMethods();
        $transactionType = $this->arLookupManager->getTransactionType();
        //$roleWiseUser = $this->arManager->findRoleWiseUser(WorkFlowMaster::AR_INVOICE_BILL_ENTRY_APPROVAL, WorkFlowRoleKey::AR_INVOICE_BILL_ENTRY_MAKE, $user_id);  //Add & call this function  :pavel-29-05-2022
        return view('ar.invoice-bill-listing.view', compact('transactionType','fiscalYear', 'receiptTerms', 'receiptMethods', 'department', 'billSecs', 'data', 'coaParams', 'vendorCategory', 'inserted_data','filter'));
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
        $status_code = sprintf("%4000d","");
        $status_message = sprintf("%4000s","");

        $params = [
            'p_invoice_id' => $request->post('invoiceId'),
            'p_trans_period_id' => $request->post('period'),
            'p_trans_date' => HelperClass::dateFormatForDB($request->post('postingDate')),
            'p_document_date' => HelperClass::dateFormatForDB($request->post('documentDate')),
            'p_document_no' => $request->post('documentNumber'),
            'p_document_ref' => $request->post('documentRef'),
            'p_department_id' => $request->post('department'),
            'p_bill_reg_id' => $request->post('billRegister'),
            'p_bill_sec_id' => $request->post('billSection'),
            'p_narration' => $request->post('documentNarration'),
            'p_user_id' => Auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        DB::beginTransaction();
        try {
            DB::executeProcedure("CPAACC.fas_ar_trans.trans_ar_invoice_ref_update",$params);
            DB::commit();
        }catch (\Exception $e){
            return ['response_code'=>99, 'response_message'=>$e->getMessage()];
            DB::rollBack();
        }
        return ['response_code'=>$status_code, 'response_message'=>$status_message];
    }
}

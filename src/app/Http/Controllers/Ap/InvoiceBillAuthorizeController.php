<?php
/**
 *Created by PhpStorm
 *Created at ২২/৯/২১ ১০:৪৩ AM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\LookupContract;
use App\Entities\Ap\FasApInvoice;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\ApprovalStatus;
use App\Enums\ModuleInfo;
use App\Enums\RolePermissionsKey;
use App\Enums\WorkFlowRoleKey;
use App\Helpers\HelperClass;
use App\Entities\Security\User;
use App\Entities\WorkFlowMapping;
use App\Enums\ApprovalStatusView;
use App\Enums\Common\LGlInteModules;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InvoiceBillAuthorizeController extends Controller
{
    use HasPermission;

    protected $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    protected $glCoaParam;
    //protected $lookupManager;
    protected $flashMessageManager;
    protected $invoice;
    private $currency;
    private $attachment;

    public function __construct(LookupContract $lookupManager, ApLookupContract $apLookupManager, CommonManager $commonManager, FlashMessageManager $flashMessageManager)
    {
        $this->lookupManager = $lookupManager;
        $this->apLookupManager = $apLookupManager;
        $this->commonManager = $commonManager;
        $this->flashMessageManager = $flashMessageManager;

        $this->glCoaParam = new GlCoaParams();
        $this->invoice = new FasApInvoice();
        $this->currency = new LCurrency();
        $this->attachment = new FasApInvoiceDoc();
    }

    public function index($filter = null)
    {
        //$fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        //$data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['billSecs'] = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['vendors'] = $this->apLookupManager->getVendors();
        $data['invoiceStatus'] = $this->apLookupManager->getInvoiceStatus();
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;
        return view('ap.invoice-bill-authorize.index', compact('data','fiscalYear','filterData'));
    }

    public function searchInvoiceBillAuthorize(Request $request)
    {
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
        $approvalStatus = $request->post('approval_status');
        $user_id = auth()->id();
        $filteredData = Crypt::encryptString($request->post('fiscalYear') .'#'.$request->post('period') .'#'. $request->post('bill_section') .'#'. $request->post('bill_reg_id') .'#'. $request->post('approval_status'));
        //$data = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_auth_list (:p_trans_period_id, :p_trans_date, :p_trans_batch_id, :p_document_no, :p_gl_subsidiary_id,:p_invoice_type_id, :p_vendor_id,:p_department_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual",["p_trans_period_id" => $period,"p_trans_date" => $postingDate,"p_trans_batch_id" => $batchId,"p_document_no" => $documentNo,"p_gl_subsidiary_id" => $partySubLedger,"p_invoice_type_id" => $invoiceType,"p_vendor_id" => $vendor,"p_department_id" => $department,"p_bill_sec_id" => $billSection,"p_bill_reg_id" => $billReg,"p_workflow_approval_status" => $approvalStatus,"p_user_id" => $user_id]);
        $data = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_auth_list (:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual",["p_fiscal_year_id"=>$fiscalYear,"p_trans_period_id" => $period,"p_bill_sec_id" => $billSection,"p_bill_reg_id" => $billReg,"p_workflow_approval_status" => $approvalStatus,"p_user_id" => $user_id]);

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('batch_id', function ($data) {
                return $data->batch_id;
            })
            ->editColumn('document_date', function ($data) {
                return HelperClass::dateConvert($data->document_date);
            }) ->editColumn('invoice_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->invoice_amount);
            }) ->editColumn('tax_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->tax_amount);
            }) ->editColumn('vat_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->vat_amount);
            }) ->editColumn('security_deposit', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->security_deposit);
            }) ->editColumn('other_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->other_amount);
            }) ->editColumn('payable_amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->payable_amount);
            })
            /* ->editColumn('invoice_status', function ($data) {
                 return $data->approval_status;
             })*/
            ->editColumn('invoice_status', function ($query) {
                if ($query->approval_status == ApprovalStatusView::PENDING) {
                    return '<span class="badge badge-primary badge-pill">' . ApprovalStatusView::PENDING . '</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">' . ApprovalStatusView::APPROVED . '</span>';
                } else if ($query->approval_status == ApprovalStatusView::REJECTED) {
                    return '<span class="badge badge-danger badge-pill">' . ApprovalStatusView::REJECTED . '</span>';
                } else {
                    return '<span class="badge badge-warning badge-pill">' . ApprovalStatusView::FORWARDED . '</span>';
                }
            })
            /*->editColumn('hold_unhold', function ($data) {
                if ($data->payment_hold_flag == '1') {
                    return "<button type='button' class='btn btn-sm btn-light-warning hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Unhold</button>";
                } else {
                    return "<button type='button' class='btn btn-sm btn-light-success hold_un_hold_invoice' data-currentflag='" . $data->payment_hold_flag . "' data-invoiceid='" . $data->invoice_id . "'>Hold</button>";
                }
            })*/
//            ->editColumn('action', function ($data) use ($filteredData) {
//                return "<a style='text-decoration:underline' href='" . route('invoice-bill-authorize.approval-view', [$data->invoice_id, 'wk_map_id' => $data->workflow_mapping_id, 'user_id' => $data->login_user_id, 'wk_ref_status' => $data->workflow_reference_status,'filter'=>$filteredData]) . "' class='' data-target='' ><i class='bx bx-show'></i></a> ||
//                        <a  target='_blank' href='".request()->root()."/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=".$data->trans_period_id."&p_trans_batch_id=".$data->batch_id."&type=pdf&filename=transaction_list_batch_wise'><i class='bx bx-printer cursor-pointer font-small-4'></i></a>";
//
//            })

            ->editColumn('action', function ($data) use ($filteredData) {

                $approveBtn = '';
                if ($data->workflow_approval_status == ApprovalStatus::PENDING) {
                    if (HelperClass::findUserPermissionKey(ModuleInfo::AP_MODULE_ID, RolePermissionsKey::CAN_QUICK_AUTHORIZE_AP_INVOICE_MAKE)) {

                        $approveBtn = '
                            <a href="#" class="approve-reject-btn cursor-pointer" data-map="' . $data->workflow_mapping_id . '"  name="authorize"
                                    value="' . ApprovalStatus::APPROVED . '"><i class="bx bx-check-double bg-success rounded"></i>
                            </a>';
                    }
                }
                return $approveBtn . "<a style='text-decoration:underline' href='" . route('invoice-bill-authorize.approval-view', [$data->invoice_id, 'wk_map_id' => $data->workflow_mapping_id, 'user_id' => $data->login_user_id, 'wk_ref_status' => $data->workflow_reference_status,'filter'=>$filteredData]) . "' class='' data-target='' ><i class='bx bx-show'></i></a>
                        <a  target='_blank' href='".request()->root()."/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=".$data->trans_period_id."&p_trans_batch_id=".$data->batch_id."&type=pdf&filename=transaction_list_batch_wise'><i class='bx bx-printer cursor-pointer font-small-4'></i></a>";
            })


            ->rawColumns(['action', 'invoice_status'])
            ->make(true);
    }

    public function approvalView(Request $request, $id, $filter=null)
    {
        $wkMapId = $request->get('wk_map_id');
        $userId = $request->get('user_id');
        $wkRefStatus = $request->get('wk_ref_status');

        $empInfo = User::with(['employee'])->where('user_id', $userId)->first();

        $wkMapInfo = WorkFlowMapping::where('workflow_mapping_id', $wkMapId)->first();

        //$inserted_data = $this->invoice->where('invoice_id','=',$id)->with('vendor.vendor_category','bill_section','bill_reg','invoice_type','invoice_line.gl_acc_detail','invoice_file')->first();
        $inserted_data = DB::selectOne("select cpaacc.fas_ap_trans.get_ap_invoice_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        $inserted_data->invoice_line = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_trans_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        $inserted_data->invoice_file = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_docs_view(:p_invoice_id) from dual", ["p_invoice_id" => $id]);
        $inserted_data->invoice_budget = DB::select("select cpaacc.fas_ap_trans.get_ap_invoice_budget_list(:p_invoice_id) from dual",["p_invoice_id"=>$id]);

        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);

        $vendorType = $this->apLookupManager->getVendorTypes();
        $vendorCategory = $this->apLookupManager->getVendorCategory();
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['invoice_type'] = $this->apLookupManager->findInvoiceType();
        $data['currency'] = $this->currency->get();
        $coaParams = $this->lookupManager->getSpecifiedGlCoaParams([\App\Enums\Common\GlCoaParams::ASSET, \App\Enums\Common\GlCoaParams::EXPENSE]);
        $paymentTerms = $this->lookupManager->getPaymentTerms();
        $paymentMethod = $this->lookupManager->getPaymentMethods();

        /**
         * 0002684: TRANSACTION EDIT/CANCEL LOG (FOR GL, AP, AR, Budget MODULE)
         * Check users cancel permission status for authorize invoice view: Start*
         */
        $cancelPermission = 'hidden';
        if ($wkMapInfo->reference_status == ApprovalStatus::APPROVED) {
            $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_AP_ENTRY_VOUCHER);
            $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
        }
        /**Check users cancel permission status for authorize invoice view: END**/

        //$filter = $request->get('filter');
        return view('ap.invoice-bill-authorize.view', compact('cancelPermission','paymentMethod', 'paymentTerms', 'postingDate', 'department', 'billSecs', 'data', 'coaParams', 'vendorType', 'vendorCategory', 'inserted_data', 'wkMapId', 'wkRefStatus', 'empInfo', 'wkMapInfo','filter'));
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

    public function approveRejectCancel(Request $request, $wkMapId = null, $filter = null)
    {
        if ($request->get('approve_reject_value') == ApprovalStatus::CANCEL)
        {
            try {
                DB::beginTransaction();
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_invoice_id' => $request->get('invoice_id'),
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];

                DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_cancel', $params);
                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    return redirect()->back()->with(['filter'=>$filter,'error' => $status_message]);
                }else{
                    DB::commit();
                    return redirect()->route('invoice-bill-authorize.index',['filter'=>$filter])->with('success', $status_message);
                }
            }catch (\Exception $e){
                DB::rollBack();
                return redirect()->back()->with(['filter'=>$filter,'error' => $e->getMessage()]);

            }
        }

        $response = $this->inv_bill_api_approved_rejected($request, $wkMapId);

        /*$message = $response['o_status_message'];*/
        $flashMessageContent = $this->flashMessageManager->getMessage($response);

        if ($response['o_status_code'] != 1) {
            return redirect()->back()->with($flashMessageContent['class'], $flashMessageContent['message'])->withInput();

            /* session()->flash('m-class', 'alert-danger');
             return redirect()->back()->with('message', 'error|'.$message)->withInput();*/
        }

        /*session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('invoice-bill-authorize.index');*/
        return redirect()->route('invoice-bill-authorize.index',['filter'=>$filter])->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    private function inv_bill_api_approved_rejected($request, $wkMapId)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL,
                'i_reference_table' => WkReferenceTable::FAS_AP_INVOICE,
                'i_reference_key' => WkReferenceColumn::INVOICE_ID,
                'i_reference_status' => $postData['approve_reject_value'],
                'i_reference_comment' => isset($postData['comment_on_decline'])?$postData['comment_on_decline'] :'',
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        DB::commit();
        return $params;
    }


    public function approveByList(Request $request)
    {

        $wkMapId=$request->wk_map_id;
        $data['response'] = $this->inv_bill_api_approved_rejected($request, $wkMapId);

        $data['response_code'] = $data['response']['o_status_code'];

        return $data;
    }
}

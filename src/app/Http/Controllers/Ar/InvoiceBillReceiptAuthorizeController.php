<?php


namespace App\Http\Controllers\Ar;

use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Ar\ArLookupContract;
use App\Contracts\LookupContract;
use App\Entities\Ap\FasApPaymentDocs;
use App\Entities\Ar\FasArReceiptDocs;
use App\Entities\Security\User;
use App\Entities\WorkFlowMapping;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\Ar\ArFunType;
use App\Enums\ModuleInfo;
use App\Enums\RolePermissionsKey;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InvoiceBillReceiptAuthorizeController extends Controller
{
    use HasPermission;
    protected  $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ArLookupManager */
    private $arLookupManager;

    public function __construct(LookupContract $lookupManager, ArLookupManager $arLookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->arLookupManager = $arLookupManager;
        $this->commonManager = $commonManager;
    }

    public function index($filter = null)
    {
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $data['billSecs'] = $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_RECEIPT);
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;
        return view('ar.invoice-bill-receipt-authorize.index', compact('data','fiscalYear','filterData'));
    }

    public function approvalView(Request $request, $id, $filter=null)
    {
        $invBillPayInfo = '';
        $invReferenceList = [];

        $wkMapId = $request->get('wk_map_id');
        $userId = $request->get('user_id');
        $wkRefStatus = $request->get('wk_ref_status');

        $invBillReceiptInfo = DB::selectOne("select cpaacc.fas_ar_trans.get_ar_receipt_view (:p_receipt_id) from dual",['p_receipt_id' => $id]);

        if ($invBillReceiptInfo) {
            //$invReferenceList = DB::select("select CPAACC.fas_ar_trans.get_receipt_invoice_referance (:p_gl_subsidiary_id, :p_receipt_id) from dual",['p_gl_subsidiary_id'=> $invBillReceiptInfo->gl_subsidiary_id, 'p_receipt_id'=> $id]);
            $invBillReceiptInfo->invoice_line = DB::select("select cpaacc.fas_ar_trans.get_ar_receipt_trans_view(:p_receipt_id) from dual", ["p_receipt_id" => $id]);
            $invReferenceList = DB::select("select CPAACC.fas_ar_trans.get_ar_receipt_ref_vw (:p_receipt_id) from dual",['p_receipt_id'=> $id]);
        }

        $invReceiptDocsList = FasArReceiptDocs::where('receipt_id', $id)->get();

        $empInfo = User::with(['employee'])->where('user_id',$userId)->first();

        $wkMapInfo = WorkFlowMapping::where('workflow_mapping_id',$wkMapId)->first();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        /**
         * 0002684: TRANSACTION EDIT/CANCEL LOG (FOR GL, AP, AR, Budget MODULE)
         * Check users cancel permission status for authorize invoice view: Start*
         */
        $cancelPermission = 'hidden';
        if ($wkMapInfo->reference_status == ApprovalStatus::APPROVED) {
            $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_AR_RECEIPT_VOUCHER);
            $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
        }
        /**Check users cancel permission status for authorize invoice view: END**/

        return view('ar.invoice-bill-receipt-authorize.approve_reject', [
            'cancelPermission' => $cancelPermission,
            'invBillReceiptInfo' => $invBillReceiptInfo,
            'invReferenceList' => $invReferenceList,
            'invReceiptDocsList' => $invReceiptDocsList,
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            'empInfo' => $empInfo,
            'wkMapInfo' => $wkMapInfo,
            'fiscalYear' => $fiscalYear,
            'department' => $this->lookupManager->getDeptCostCenter(),
            'billSecs' => $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_RECEIPT),
        ],compact('filter'));
    }

    public function searchInvoiceReceiptAuthorize(Request $request)
    {
        $user_id = auth()->id();
        $queryResult = [];

        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id'=>$request->post('fiscalYear',null),
            'p_trans_period_id' =>  $request->post('period',null),
            'p_bill_sec_id' =>  $request->post('bill_sec_id',null),
            'p_bill_reg_id' =>  $request->post('bill_reg_id',null),
            'p_workflow_approval_status' => $request->post('authorization_status',null),
            'p_user_id' => $user_id,
        ];

        $filteredData =Crypt::encryptString($request->post('fiscalYear') .'#'.$request->post('period') .'#'. $request->post('bill_sec_id') .'#'. $request->post('bill_reg_id') .'#'. $request->post('authorization_status'));

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_ar_trans.get_ar_receipt_auth_list (:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        return datatables()->of($queryResult)
            ->editColumn('status', function($query) {
                if($query->approval_status == ApprovalStatusView::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::REJECTED) {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                } else {
                    return '<span class="badge badge-warning badge-pill">'.ApprovalStatusView::FORWARDED.'</span>';
                }
            })
            ->editColumn('receipt_amount', function ($data){
                return HelperClass::getCommaSeparatedValue($data->receipt_amount);
            })
            ->addColumn('action', function ($query) use ($filteredData) {
//                /*return '<button class="btn btn-primary btn-sm trans-mst"  id="'.$query->payment_id.'">Detail View</button>';
//                <a class="btn btn-sm btn-info"  href="' . route('invoice-bill-payment.view', [$query->payment_id]) . '"><i class="bx bx-show cursor-pointer"></i> View</a>*/
//                return "<a href='" . route('invoice-bill-receipt-authorize.approval-view', [$query->receipt_id,'wk_map_id'=>$query->workflow_mapping_id,'user_id'=>$query->login_user_id, 'wk_ref_status'=>$query->workflow_reference_status,'filter'=>$filteredData]) ."'><i class='bx bx-show cursor-pointer'></i></a>";
//            })
//                {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AR_MODULE_ID, \App\Enums\WorkFlowRoleKey::AR_INVOICE_BILL_RECEIPT_AUTHORIZE,\App\Enums\RolePermissionsKey::CAN_QUICK_AUTHORIZE_AR_RECEIPT_MAKE )) ) ? 'disabled' : '' }}>

                $approveBtn = '';

                if ($query->workflow_approval_status == ApprovalStatus::PENDING) {
                    if (HelperClass::findUserPermissionKey(ModuleInfo::AR_MODULE_ID,RolePermissionsKey::CAN_QUICK_AUTHORIZE_AR_RECEIPT_MAKE)){

                        $approveBtn = '
                            <a href="#" class="approve-reject-btn cursor-pointer" data-map="' . $query->workflow_mapping_id . '"  name="authorize"
                                    value="' . ApprovalStatus::APPROVED . '"><i class="bx bx-check-double bg-success rounded"></i>
                            </a>';
                    }

                }
                return $approveBtn .' '. "<a href='" . route('invoice-bill-receipt-authorize.approval-view', [$query->receipt_id,'wk_map_id'=>$query->workflow_mapping_id,'user_id'=>$query->login_user_id, 'wk_ref_status'=>$query->workflow_reference_status,'filter'=>$filteredData]) ."'><i class='bx bx-show cursor-pointer'></i></a>";
            })

            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function approveRejectCancel(Request $request, $wkMapId=null,$filter=null) {

        if ($request->get('approve_reject_value') == ApprovalStatus::CANCEL)
        {
            try {
                DB::beginTransaction();
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_receipt_id' => $request->get('receipt_id'),
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];

                DB::executeProcedure('CPAACC.fas_ar_trans.trans_ar_receipt_cancel', $params);
                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    return redirect()->back()->with(['filter'=>$filter,'error' => $status_message]);

                }else{
                    DB::commit();
                    return redirect()->route('invoice-bill-receipt-authorize.index',['filter'=>$filter])->with('success', $status_message);
                }
            }catch (\Exception $e){
                DB::rollBack();
                return redirect()->back()->with(['filter'=>$filter,'error' => $e->getMessage()]);
            }
        }

        $response = $this->inv_bill_pay_api_approved_rejected($request, $wkMapId);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('invoice-bill-receipt-authorize.index',['filter'=>$filter]);
    }

    private function inv_bill_pay_api_approved_rejected($request, $wkMapId)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::AR_INVOICE_BILL_RECEIPT_APPROVAL,
                'i_reference_table' => WkReferenceTable::FAS_AR_RECEIPT,
                'i_reference_key' =>  WkReferenceColumn::AR_RECEIPT_ID,
                'i_reference_status' => $postData['approve_reject_value'],
                'i_reference_comment' => (isset($postData['comment_on_decline']) ? $postData['comment_on_decline'] : ''),
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            //dd($params);

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);
            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        DB::commit();
        return $params;
    }

    public function approveByList(Request $request)
    {
        $wkMapId=$request->wk_map_id;
        $data['response'] = $this->inv_bill_pay_api_approved_rejected($request, $wkMapId);

        $data['response_code'] = $data['response']['o_status_code'];

        return $data;
    }

}

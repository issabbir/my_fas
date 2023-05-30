<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\Gl\GlTransDetail;
use App\Entities\Gl\GlTransDocs;
use App\Entities\Gl\GlTransMaster;
use App\Entities\Security\SecUserRoles;
use App\Entities\WorkFlowMapping;
use App\Entities\WorkFlowTemplate;
use App\Enums\Ap\Role;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\Common\DebitCredit;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LTransAmtType;
use App\Enums\ModuleInfo;
use App\Enums\RolePermissionsKey;
use App\Enums\WkReferenceColumn;
use App\Http\Controllers\Controller;
use App\Enums\ProActionType;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\Gl\GlManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TransactionAuthorizeController extends Controller
{
    use HasPermission;

    protected  $commonManager;
    protected $attachment;

    /** @var LookupManager */
    private $lookupManager;

    /** @var GlManager */
    private $glManager;


    public function __construct(LookupContract $lookupManager, GlContract $glManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glManager = $glManager;

        $this->commonManager = $commonManager;
        $this->attachment = new GlTransDocs();
    }

    public function index($filter = null)
    {
        //dd(HelperClass::userHasPermission(\App\Enums\RolePermissionsKey::CAN_CANCEL_GL_JOURNAL_VOUCHER, \Illuminate\Support\Facades\Auth::id()));
        $moduleId = LGlInteModules::FIN_ACC_GENE_LEDGER;
        //$fiscalYear = $this->lookupManager->getACurrentFinancialYear(); //Block-Pavel: 14-07-22
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear(); //Add-Pavel: 14-07-22

        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        return view('gl.transaction-authorize.index', [
            'dptList' => $this->lookupManager->getDeptCostCenter(),
            'lBillSecList' => $this->lookupManager->getBillSections(null),
            //'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            'fiscalYear' => $fiscalYear,
            'cashTranFunTypeList' => $this->lookupManager->getIntegrationFunListOnAuth(),
        ],compact('filterData'));
    }

    public function searchTransactionsAuthorizeMst(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];
        $user_id = auth()->id();

        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id'=>$terms['fiscalYear'],
            'p_user_id' =>  $user_id,
            'p_workflow_approval_status' => $terms['trans_mst_approval_status'] ? $terms['trans_mst_approval_status'] : null,
            /*'p_function_id' =>   $terms['fun_type_id'] ?   $terms['fun_type_id'] : null,*/
            //'p_fiscalYear' =>   $terms['fiscalYear'] ?   $terms['fiscalYear'] : null,
            'p_trans_period_id' =>   $terms['period'] ?   $terms['period'] : null,
            'p_bill_sec_id' =>   $terms['bill_sec_id'] ?   $terms['bill_sec_id'] : null,
            'p_bill_reg_id' =>   $terms['bill_reg_id'] ?   $terms['bill_reg_id'] : null,
            /*'p_department_id' =>   $terms['dpt_id'] ?   $terms['dpt_id'] : null,
            'p_trans_date' =>   $terms['posting_date_field'] ?   HelperClass::dateFormatForDB($terms['posting_date_field']) : null,
            'p_trans_batch_id' =>   $terms['posting_batch_id'] ?   $terms['posting_batch_id'] : null,*/

        ];

        $filteredData = Crypt::encryptString($terms['fiscalYear'] .'#'.$terms['period'] .'#'. $terms['bill_sec_id'] .'#'. $terms['bill_reg_id'] .'#'. $terms['trans_mst_approval_status']);

        /** Execute Oracle Function With Params **/
        //$sql ="select CPAACC.fas_gl_trans.get_transaction_authorize_list (:p_user_id,:p_workflow_approval_status,:p_function_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_department_id,:p_trans_date,:p_trans_batch_id) from dual";
        $sql ="select CPAACC.fas_gl_trans.get_transaction_authorize_list (:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        return datatables()->of($queryResult)
            ->editColumn('document_date',function ($d){
                return HelperClass::dateConvert($d->document_date);
            })
            ->editColumn('debit_sum',function ($d){
                return HelperClass::getCommaSeparatedValue($d->debit_sum);
            })
            ->editColumn('credit_sum',function ($d){
                return HelperClass::getCommaSeparatedValue($d->credit_sum);
            })
            /*->editColumn('mst-view', function($query) {
                return '<span class="badge badge-danger badge-pill">'.$query->workflow_approval_status.'</span>';
            })
            ->editColumn('view', function($query) {
                return '<span class="badge badge-danger badge-pill">'.$query->workflow_reference_status.'</span>';
            })*/
            ->editColumn('status', function($query) {
                if($query->approval_status == ApprovalStatusView::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::FORWARDED) {
                    return '<span class="badge badge-warning badge-pill">'.ApprovalStatusView::FORWARDED.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                }
            })
            ->addColumn('action', function ($query) use ($filteredData) {
             $approveBtn = '';

                if ($query->workflow_approval_status == ApprovalStatus::PENDING) {
                    if (HelperClass::findUserPermissionKey(ModuleInfo::GL_MODULE_ID, RolePermissionsKey::CAN_QUICK_AUTHORIZE_GL_TRANSACTION)) {

                        $approveBtn = '
                            <a href="#" class="approve-reject-btn cursor-pointer" data-map="' . $query->workflow_mapping_id ."##".$query->workflow_master_id."##".$query->workflow_reference_table. '"  name="authorize"
                                    value="' . ApprovalStatus::APPROVED . '"><i class="bx bx-check-double bg-success rounded"></i>
                            </a>';
                    }
                }
                $dataString = $query->workflow_mapping_id."##".$query->workflow_master_id."##".$query->workflow_reference_table."##".$query->workflow_reference_status."##".$filteredData;
                return $approveBtn . '<a data-transaction-data="'.$dataString.'" class="trans-mst"   id="'.$query->trans_master_id.'"><i class="bx bx-show cursor-pointer"></i></a>';
            })
//            ->addColumn('action', function ($query) use ($filteredData) {
//                $dataString = $query->workflow_mapping_id."##".$query->workflow_master_id."##".$query->workflow_reference_table."##".$query->workflow_reference_status."##".$filteredData;
//                return '<a data-transaction-data="'.$dataString.'" class="trans-mst btn btn-primary btn-sm cursor-pointer" style="color:white"  id="'.$query->trans_master_id.'">Select</a>';
//            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function searchTransactionsAuthorizeDtl(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        if(empty($terms['trans_mst_id'])) {
            $queryResult = [];
        } else {
            //$queryResult = GlTransDetail::with(['gl_coa'])->where('trans_master_id', $terms['trans_mst_id'])->get();
            $sql ="select CPAACC.fas_gl_trans.get_transaction_detail_view (:p_trans_master_id) from dual";
            $queryResult = DB::select($sql,['p_trans_master_id' => $terms['trans_mst_id']] );
        }


        return datatables()->of($queryResult)
            /*->editColumn('cheque_date', function ($query) {
                return HelperClass::dateConvert($query->cheque_date);
            })
            ->editColumn('challan_date', function ($query) {
                return HelperClass::dateConvert($query->challan_date);
            })
            ->rawColumns(['cheque_date','challan_date'])*/
            ->addIndexColumn()
            ->make(true);
    }

    public function approveRejectCancel(Request $request, $wkMapId=null) {

        $filter = $request->get('filter');

        if ($request->get('ref_status') == ApprovalStatus::CANCEL)
        {
            try {
                DB::beginTransaction();
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_trans_master_id' => $request->get('trans_mst_id'),
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];

                DB::executeProcedure('CPAACC.FAS_GL_TRANS.trans_gl_cancel', $params);

                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    return redirect()->back()->with(['filter'=>$filter,'error' => $status_message]);

                }else{
                    DB::commit();
                    return redirect()->route('transaction-authorize.index',['filter'=>$filter])->with('success', $status_message);
                }
            }catch (\Exception $e){
                DB::rollBack();
                return redirect()->back()->with(['filter'=>$filter,'error' => $e->getMessage()]);

            }
        }

        $response = $this->transaction_authorize_api_approved_rejected($request, $wkMapId);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('transaction-authorize.index',['filter'=>$filter]);
    }

    private function transaction_authorize_api_approved_rejected($request, $wkMapId)
    {
       ;
        $wkMstId = $request->get('wk_mst_id');
        $refTable = $request->get('ref_tbl');
        if ($request->get('ref_status') == ApprovalStatus::APPROVED)
        {
            $refStatus = ApprovalStatus::APPROVED;
        }elseif ($request->get('ref_status') == ApprovalStatus::CANCEL)
        {
            $refStatus = ApprovalStatus::CANCEL;
        }else{
            $refStatus = ApprovalStatus::REJECT;
        }
        //$refStatus = $request->get('ref_status') == ApprovalStatus::APPROVED ? ApprovalStatus::APPROVED : ApprovalStatus::REJECT;
        $remarks = $request->get('rem') == 'true' ? 'N/A' : $request->get('rem');

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'I_WORKFLOW_MAPPING_ID' => $wkMapId,
                'I_WORKFLOW_MASTER_ID' => $wkMstId,
                'I_REFERENCE_TABLE' => $refTable,
                'I_REFERENCE_KEY' => WkReferenceColumn::TRANS_MASTER_ID,
                'I_REFERENCE_STATUS' => $refStatus,
                'I_REFERENCE_COMMENT' => $remarks,
                'I_USER_ID' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            //DB::executeProcedure('CPAACC.WORKFLOW_ROLEWISE_MAP_SAVE', $params);
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

    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->attachment->where('trans_doc_file_id','=',$attachmentId)->first();
        $content =  base64_decode($attachment->trans_doc_file_content);

        return response()->make($content, 200, [
            'Content-Type' => $attachment->trans_doc_file_type,
            'Content-Disposition' => 'attachment;filename="'.$attachment->trans_doc_file_name.'"'
        ]);
    }

    /*public function BAKsearchTransactionsAuthorizeMst(Request $request)
    {
        $terms = $request->post();
        /*$where = [];
        $debit = DebitCredit::DEBIT;
        $credit = DebitCredit::CREDIT;*/
        /*$whereClause = [];
        $whereQueryPart = '';
        //dd($terms);
        $user_id = auth()->id();*/
        //dd($user_id);

        /** Function Type Filter */
        /*if($terms['fun_type_id']) {
            //$where[] = ['function_id', '=', $terms['fun_type_id']];
            $whereClause['function_id'] = $terms['fun_type_id'];
            $whereQueryPart .= " AND gtm.function_id = :function_id";
        }*/
        /** Period Filter */
        /*if($terms['period']) {
            //$where[] = ['trans_period_id', '=', $terms['period']];
            $whereClause['trans_period_id'] = $terms['period'];
            $whereQueryPart .= " AND gtm.trans_period_id = :trans_period_id";
        }*/
        /** Bill Section Filter */
        /*if($terms['bill_sec_id']) {
            // $where[] = ['bill_sec_id', '=', $terms['bill_sec_id']];
            $whereClause['bill_sec_id'] = $terms['bill_sec_id'];
            $whereQueryPart .= " AND gtm.bill_sec_id = :bill_sec_id";
        }*/
        /** Bill Register Filter */
       /* if($terms['bill_reg_id']) {
            // $where[] = ['bill_reg_id', '=', $terms['bill_reg_id']];
            $whereClause['bill_reg_id'] = $terms['bill_reg_id'];
            $whereQueryPart .= " AND gtm.bill_reg_id = :bill_reg_id";
        }*/
        /** Department Filter */
        /*if($terms['dpt_id']) {
            //$where[] = ['department_id', '=', $terms['dpt_id']];
            $whereClause['department_id'] = $terms['dpt_id'];
            $whereQueryPart .= " AND gtm.department_id  = :department_id";
        }*/
        /** Posting Date Field Filter */
        /*if($terms['posting_date_field']) {
            //$where[] = ['trans_date', '=', HelperClass::dateFormatForDB($terms['posting_date_field'])];
            $whereClause['trans_date'] = $terms['posting_date_field'];
            $whereQueryPart .= " AND trunc(gtm.trans_date) = TO_DATE (:trans_date, 'dd-mm-yyyy')";
        }*/
        /** Posting Batch Filter */
        /*if($terms['posting_batch_id']) {
            //$where[] = ['trans_batch_id', '=', $terms['posting_batch_id']];
            $whereClause['trans_batch_id'] = $terms['posting_batch_id'];
            $whereQueryPart .= " AND gtm.trans_batch_id = :trans_batch_id";
        }*/
        /** Approval Status */
        /*if($terms['trans_mst_approval_status']) {
            //$where[] = ['trans_batch_id', '=', $terms['posting_batch_id']];
            $whereClause['workflow_approval_status'] = $terms['trans_mst_approval_status'];
            $whereQueryPart .= " AND gtm.workflow_approval_status = :workflow_approval_status";
        }*/

        /*$query = <<<QUERY
SELECT gtm.trans_master_id,
       gif.function_name,
       to_char(gtm.trans_date, 'DD-MM-YYYY') trans_date,
       gtm.trans_batch_id,
       gtm.document_no,
       lbs.bill_sec_name,
       lbr.bill_reg_name,
       (select sum(amount_lcy) from cpaacc.fas_gl_trans_detail	where TRANS_MASTER_ID = gtm.trans_master_id and DR_CR =	'D') debit_sum,
       (select sum(amount_lcy) from cpaacc.fas_gl_trans_detail	where TRANS_MASTER_ID = gtm.trans_master_id and DR_CR =	'C') credit_sum,
       gtm.workflow_approval_status,
       --DECODE (gtm.workflow_approval_status, 'P', 'PENDING','A', 'APPROVED','REJECT') app_status,
       wt.workflow_template_id,
       wt.workflow_template_id,
       wt.workflow_master_id,
       wt.role_key,
       wm.workflow_mapping_id,
       wm.seq,
       wm.user_id     as userid,
       wm.reference_id,
       wm.reference_table,
       wm.reference_status,
       --DECODE (wm.reference_status, 'A', 'PENDING','Y', 'APPROVED','REJECT') app_status,
       sur.user_id,
       sur.role_id
  FROM cpaacc.fas_gl_trans_master      gtm,
       cpaacc.l_gl_integration_functions gif,
       cpaacc.l_bill_section	lbs,
       cpaacc.l_bill_register	lbr,
       cpaacc.workflow_template        wt,
       cpaacc.workflow_mapping         wm,
       cpa_security.sec_user_roles  sur,
       cpa_security.sec_role        sr
 WHERE gtm.trans_master_id = wm.reference_id
       and gif.function_id = gtm.function_id
       and lbs.bill_sec_id = gtm.bill_sec_id
       and lbr.bill_reg_id = gtm.bill_reg_id
       and wt.workflow_template_id = wm.workflow_template_id
       and wt.workflow_master_id = wm.workflow_master_id
       --and wt.workflow_master_id = 3
       --and wm.user_id is null
       and sur.user_id = $user_id --2011160343 --:p_user_id
       and sr.role_id = sur.role_id
       and sr.role_key = wt.role_key
       $whereQueryPart
--       and gtm.workflow_approval_status = :workflow_approval_status
--       and gtm.function_id = :p_function_id
--       and gtm.trans_period_id = :p_trans_period_id
--       and gtm.bill_sec_id = :p_bill_sec_id
--       and gtm.bill_reg_id = :p_bill_reg_id
--       and gtm.department_id = :p_department_id
--       and gtm.trans_date = :p_trans_date
--       and gtm.trans_batch_id = :p_trans_batch_id
QUERY;*/

        /*$queryResult = DB::select($query, $whereClause);*/
        //dd($queryResult);

        /*return datatables()->of($queryResult)*/
            /*->addColumn('action', function($query) {
                return '<a href="' . route('coa.coa-setup-edit', [$query->trans_master_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            <button class="btn btn-primary btn-sm trans-mst"  id="'.$query->trans_master_id.'">Select</button>
            })*/
            /*->editColumn('trans_date', function ($query) {
                return HelperClass::dateConvert($query->trans_date);
            })*/
           /* ->editColumn('status', function($query) {
                if($query->workflow_approval_status == ApprovalStatus::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->workflow_approval_status == ApprovalStatus::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECT.'</span>';
                }
            })
            ->addColumn('action', function ($query) {

                $dataString = $query->workflow_mapping_id."##".$query->workflow_master_id."##".$query->reference_table."##".ApprovalStatus::APPROVED;
                return '<a data-transaction-data="'.$dataString.'" class="trans-mst btn btn-primary btn-sm cursor-pointer" style="color:white"  id="'.$query->trans_master_id.'">Select</a>';*/

                /*$dataString = $query->workflow_mapping_id."##".$query->workflow_master_id."##".$query->reference_table."##".ApprovalStatus::APPROVED;
                if($query->workflow_approval_status == ApprovalStatus::PENDING){
                    return '<a id="'.ApprovalStatus::APPROVED.'" class="trans-approval" href="' . route('transaction-authorize.approve-reject', [$query->workflow_mapping_id,'wk_mst_id' => $query->workflow_master_id,'ref_tbl' => $query->reference_table, 'ref_status'=>ApprovalStatus::APPROVED]) . '"><i class="bx bx-check-double cursor-pointer"></i></a>
                        <a id="'.ApprovalStatus::REJECT.'" class="trans-approval" href="' . route('transaction-authorize.approve-reject', [$query->workflow_mapping_id,'wk_mst_id' => $query->workflow_master_id,'ref_tbl' => $query->reference_table, 'ref_status'=>ApprovalStatus::REJECT]) . '"><i class="bx bx-x cursor-pointer"></i></a>
                        <a data-transaction-data="'.$dataString.'" class="trans-mst"  id="'.$query->trans_master_id.'"><i class="bx bx-show cursor-pointer"></i></a>';
                } else {
                    return '<a data-transaction-data="'.$dataString.'" class="trans-mst"  id="'.$query->trans_master_id.'"><i class="bx bx-show cursor-pointer"></i></a>';
                }*/

            /*})
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }*/

    public function approveByList(Request $request){

        $wkMapId= $request->wk_map_id;

        $data['response'] = $this->transaction_authorize_api_approved_rejected($request,$wkMapId);

        $data['response_code'] = $data['response']['o_status_code'];

        return $data;
    }

}

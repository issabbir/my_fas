<?php


namespace App\Http\Controllers\Gl;


use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\Gl\GlTransDocs;
use App\Enums\ApprovalStatusView;
use App\Enums\Common\LGlInteModules;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Common\CommonManager;
use App\Managers\Gl\GlManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReverseJournalController extends Controller
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

    public function index()
    {
        $moduleId = LGlInteModules::FIN_ACC_GENE_LEDGER;
        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        return view('gl.reverse-journal.index', [
            'dptList' => $this->lookupManager->getDeptCostCenter(),
            'lBillSecList' => $this->lookupManager->findLBillSec(),
            'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            'cashTranFunTypeList' => $this->lookupManager->getIntegrationFunList($moduleId),
        ]);
    }

    public function searchTransactionsMst(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        /** All Parameter Filter **/
        $params = [
            'p_posting_period_id' =>  $terms['period'] ?  $terms['period'] : null,
            'p_function_type' => $terms['fun_type_id'] ? $terms['fun_type_id'] : null,
            'p_posting_date' =>   $terms['posting_date_field'] ?   HelperClass::dateFormatForDB($terms['posting_date_field']) : null,
            'p_posting_batch_id' =>   $terms['posting_batch_id'] ?   $terms['posting_batch_id'] : null,
            'p_department' =>   $terms['dpt_id'] ?   $terms['dpt_id'] : null,
            'p_bill_section' =>   $terms['bill_sec_id'] ?   $terms['bill_sec_id'] : null,
            'p_bill_register' =>   $terms['bill_reg_id'] ?   $terms['bill_reg_id'] : null,
        ];

        /** Execute Oracle Function With Params **/
        $sql ="select CPAACC.fas_gl_trans.get_transaction_reverse_list (:p_posting_period_id,:p_function_type,:p_posting_date,:p_posting_batch_id,:p_department,:p_bill_section,:p_bill_register) from dual ";
        $queryResult = DB::select($sql, $params);

        return datatables()->of($queryResult)
            ->editColumn('status', function($query) {
                if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                }
            })
            ->addColumn('action', function ($query) {
                return '<button class="btn btn-primary btn-sm trans-mst"  id="'.$query->trans_master_id.'">Detail View</button>';
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function searchTransactionsDtl(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        if (empty($terms['trans_mst_id'])) {
            $queryResult = [];
        } else {
            $sql ="select CPAACC.fas_gl_trans.get_transaction_detail_view (:p_trans_master_id) from dual";
            $queryResult = DB::select($sql,['p_trans_master_id' => $terms['trans_mst_id']] );
        }

        return datatables()->of($queryResult)
            ->addIndexColumn()
            ->make(true);
    }

    public function reverseJournal(Request $request)
    {
        $o_status_code = sprintf("%4000s",'');
        $o_status_msg = sprintf("%4000s",'');

        $params = [
            'p_trans_master_id' => $request->post('trans_master_id'),
            'p_trans_period_id' => $request->post('trans_period_id'),
            'p_user_id' => auth()->id(),
            'o_status_code' => &$o_status_code,
            'o_status_message' => &$o_status_msg
        ];
        try {
            DB::executeProcedure('cpaacc.fas_gl_trans.trans_gl_reverse',$params);
        }catch (\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }

        return redirect()->back()->with("success", $o_status_msg);
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
}

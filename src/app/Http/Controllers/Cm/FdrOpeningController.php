<?php


namespace App\Http\Controllers\Cm;


use App\Contracts\LookupContract;
use App\Entities\Cm\FasCmFdrInvestmentTrans;
use App\Enums\Ap\ApFunType;
use App\Enums\Common\LCostCenterDept;
use App\Enums\ModuleInfo;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FdrOpeningController extends Controller
{
    private $lookupManager;
    private $openingInfo;
    private $transactionView;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;

    }

    public function index($id = null)
    {
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();


        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $department = $this->lookupManager->getSpecifiedDeptCostCenter([LCostCenterDept::FINANCE_ACCOUNTS_DEPARTMENT]);
        //$billSecs = $this->lookupManager->getBillSections(ApFunType::CM_FDR_OPENING_TRANSACTION);
        //$invBillSec = $this->lookupManager->getBillSectionOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));
        //$invBillReg = $this->lookupManager->getBillRegisterOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));
        $invBillSec = $this->lookupManager->getBillSectionOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));
        $invBillReg = $this->lookupManager->getBillRegisterOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()),"billRegisterForOpening");

        if (isset($id)) {
            $this->openingInfo = DB::selectOne("SELECT FAS_CM_TRANS.get_fdr_opening_trans_view (:p_investment_trans_id ) FROM DUAL", ['p_investment_trans_id' => $id]);
            $this->transactionView = DB::selectOne("select fas_cm_trans.get_gl_transaction_view(:p_gl_trans_master_id) from dual", ['p_gl_trans_master_id' => $this->openingInfo->investment_contra_gl_id])?? [];
        }
        return view('cm.fdr-opening.index', ['openingInfo' => $this->openingInfo, 'transactionContents' => $this->transactionView], compact('investmentStatus', 'periodTypes', 'investmentTypes', 'fiscalYear', 'department', 'invBillSec','invBillReg'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $transId = sprintf("%4000d", "");
        $batchId = sprintf("%4000d", "");
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");
        $params = [];

        try {
            $params = [
                'p_fiscal_year_id' => $request->post('fiscal_year'),
                'p_trans_period_id' => $request->post('period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => null,//$request->post('document_number'), //0003621: FDR OPENING TRANSACTION- MAKE UI
                'p_narration' => $request->post('narration'),
                'p_department_id' => $request->post('department'),
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_investment_type_id' => $request->post('investment_type'),
                'p_investment_id' => $request->post('investment_id'),
                'p_investment_amount' => HelperClass::removeCommaFromValue($request->post('amount')),
                'p_investment_contra_gl_id' => $request->post('cr_account_name'),
                'p_user_id' => Auth::id(),
                'o_investment_trans_id' => &$transId,
                'o_trans_batch_id' => &$batchId,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];
            DB::executeProcedure('CPAACC.fas_cm_trans.fas_fdr_opening_trans_make', $params);

            if ($status_code != "1") {
                DB::rollBack();
                if (isset($id)) {
                    return ["response_code" => $status_code, "response_msg" => $status_message];
                } else {
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                }
            } else {
                $wk_mapping_status_code = sprintf("%4000d", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::CM_FDR_OPENING_TRANSACTION,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_CM_FDR_INVESTMENT_TRANS,
                    'P_REFERANCE_KEY' => WkReferenceColumn::INVESTMENT_TRANS_ID,
                    'P_REFERANCE_ID' => $transId,//$log_id,
                    'P_TRANS_PERIOD_ID' => $params['p_trans_period_id'],
                    'P_INSERT_BY' => auth()->id(),
                    'o_status_code' => &$wk_mapping_status_code,
                    'o_status_message' => &$wk_mapping_status_message,
                ];

                DB::executeProcedure('CPAACC.fas_workflow.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

                if ($wkMappingParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                }

            }
            DB::commit();
            if (isset($id)) {
                return ["response_code" => $status_code, "response_msg" => $status_message];
            } else {
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($id)) {
                return ["response_code" => 99, "response_msg" => $e->getMessage()];
            } else {
                return response()->json(["response_code" => 99, "response_msg" => $e->getMessage()]);
            }
        }
    }

    public function dataList(Request $request)
    {
        $inv_type = $request->post('investmentType', null);
        $fiscal_year = $request->post('fiscalYear', null);
        $period = $request->post('period', null);
        $status = $request->post('approvalStatus', null);
        $opening_list = DB::select('select cpaacc.fas_cm_trans.get_fdr_opening_trans_make_list(:p_investment_type_id,:p_fiscal_year_id,:p_posting_period_id,:p_workflow_approval_status) from dual',
            ['p_investment_type_id' => $inv_type, 'p_fiscal_year_id' => $fiscal_year, 'p_posting_period_id' => $period, 'p_workflow_approval_status' => $status]);

        return datatables()->of($opening_list)
            ->editColumn('transaction_date', function ($d) {
                return HelperClass::dateFormatForDB($d->posting_date);
            })->editColumn('bank', function ($d) {
                return $d->bank_name;
            })->editColumn('branch', function ($d) {
                return $d->branch_name;
            })->editColumn('fdr_no', function ($d) {
                return $d->fdr_no;
            })->editColumn('amount', function ($d) {
                return HelperClass::getCommaSeparatedValue($d->investment_amount);
            })->editColumn('auth_status', function ($d) {
                $status = '';
                switch ($d->workflow_approval_status){
                    case 'A':
                        $status .= '<span class="badge-pill rounded-pill badge-success">'.$d->approval_status.'</span>';
                        break;
                    case 'P':
                        $status .= '<span class="badge-pill rounded-pill badge-warning">'.$d->approval_status.'</span>';
                        break;
                }
                return $status;
            })
            ->editColumn('action', function ($d) {
                return "<a href='" . route('fdr-opening.index', ['id' => $d->investment_trans_id]) . "' class='cursor-pointer'><i class='bx bx-show-alt'></i></a>";
            })
            ->rawColumns(['auth_status', 'action'])
            ->make(true);
    }

    public function preview(Request $request)
    {
        try {
            $transactionContents = DB::select("SELECT * FROM TABLE (fas_cm_trans.get_fdr_opening_trans_preview (:p_fdr_investment_id, :p_fdr_contra_gl_id))", ['p_fdr_investment_id' => $request->post('investmentId', null), 'p_fdr_contra_gl_id' => $request->post('contraGl', null)]);
            $previewTable = view("cm.cm-common.opening_preview_table")->with(compact('transactionContents'))->render();
            return response()->json(['status_code' => 1, 'content' => $previewTable]);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 99, 'content' => $e->getMessage()]);
        }
    }
}

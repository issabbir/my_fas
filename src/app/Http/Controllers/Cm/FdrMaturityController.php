<?php


namespace App\Http\Controllers\Cm;


use App\Contracts\LookupContract;
use App\Entities\Cm\FasCmFdrInvestmentSplit;
use App\Enums\Common\LCostCenterDept;
use App\Enums\Common\LFdrMaturityTransType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FdrMaturityController extends Controller
{
    private $lookupManager;
    private $maturityInfo;
    private $transactionView;

    public function __construct(LookupContract $lookupManager)
    {

        $this->lookupManager = $lookupManager;

    }

    public function index($id = null)
    {
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $transactionTypes = $this->lookupManager->getMaturityTransTypes();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $department = $this->lookupManager->getSpecifiedDeptCostCenter([LCostCenterDept::FINANCE_ACCOUNTS_DEPARTMENT]);
        //$billSecs = $this->lookupManager->getBillSections(ApFunType::CM_FDR_OPENING_TRANSACTION);
        $invBillSec = $this->lookupManager->getBillSectionOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));
        $invBillReg = $this->lookupManager->getBillRegisterOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()),"billRegisterForMaturity");

        if (isset($id)) {
            $this->maturityInfo = DB::selectOne("SELECT FAS_CM_TRANS.get_fdr_maturity_trans_view (:p_maturity_trans_id ) FROM DUAL", ['p_maturity_trans_id' => $id]);
            $this->maturityInfo->splitInfo = FasCmFdrInvestmentSplit::where('maturity_trans_id', '=', $this->maturityInfo->maturity_trans_id)->get();
            $this->transactionView = DB::selectOne("select fas_cm_trans.get_gl_transaction_view(:p_gl_trans_master_id) from dual", ['p_gl_trans_master_id' => $this->maturityInfo->gl_trans_master_id]) ?? [];
        }
        return view('cm.fdr-maturity.index', ['maturityInfo' => $this->maturityInfo, 'transactionContents' => $this->transactionView], compact('transactionTypes', 'investmentStatus', 'periodTypes', 'investmentTypes', 'fiscalYear', 'department', 'invBillSec', 'invBillReg'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $transBatchId = sprintf("%4000d", "");
        $maturityId = sprintf("%4000d", "");
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");
        $user_id = auth()->id();
        $params = [];
        try {
            $params = [
                'p_investment_type_id' => $request->post('investment_type'),
                'p_transaction_type' => $request->post('transaction_type'),
                'p_fiscal_year_id' => $request->post('fiscal_year'),
                'p_trans_period_id' => $request->post('period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => $request->post('document_number'),
                'p_narration' => $request->post('narration'),
                'p_department_id' => $request->post('department'),
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_investment_id' => $request->post('investment_id'),
                'p_pay_order_no' => $request->post('po_number'),
                'p_pay_oder_date' => HelperClass::dateFormatForDB($request->post('po_date')),
                'p_gross_interest_amount' => $request->post('maturity_gross_interest'),
	            'p_source_tax' =>$request->post('maturity_source_tax'),
	            'p_excise_duty' =>$request->post('maturity_excise_duty'),
                'p_principal_amt' => $request->post('po_principal_amount'),
                'p_interest_amt' => $request->post('po_interest_amount'),
                'p_renewal_date' => HelperClass::dateFormatForDB($request->post('current_renewal_date')),
                'p_renewal_amount'=>$request->post("current_renewal_amount"),
                'p_renewal_maturity_date' => HelperClass::dateFormatForDB($request->post('current_maturity_date')),
                'p_renewal_interest_rate' => $request->post('current_interest_rate'),
                'p_investment_contra_gl_id' => $request->post('cr_account_name'),
                'p_user_id' => Auth::id(),
                'o_maturity_trans_id' => &$maturityId,
                'o_trans_batch_id' => &$transBatchId,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];
            DB::executeProcedure('CPAACC.fas_cm_trans.fas_fdr_maturity_trans_make', $params);

            if ($status_code != "1") {
                DB::rollBack();
                if (isset($id)) {
                    return ["response_code" => $status_code, "response_msg" => $status_message];
                } else {
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                }
            } else {
                if ($request->post('transaction_type') == LFdrMaturityTransType::RENEWAL_AND_SPLIT) {
                    foreach ($request->post('line') as $data) {
                        try {
                            $status_code = sprintf("%4000d", "");
                            $status_message = sprintf("%4000s", "");
                            $params2 = [
                                'p_maturity_trans_id' => $maturityId,
                                /*'p_old_investment_id'=>$request->post('investment_id'),*/
                                'p_investment_type_id' => $request->post('investment_type'),
                                'p_bank_code' => $request->post('bank_id'),
                                'p_branch_code' => $request->post('branch_id'),
                                'p_fdr_no' => $data['split_fdr_no'],
                                'p_investment_date' => HelperClass::dateFormatForDB($data['split_renewal_date']),
                                'p_investment_amount' => $data['split_fdr_amount'],
                                /*'p_term_period_no'=>$request->post(''),
                                'p_term_period_code'=>$request->post(''),
                                'p_term_period_days'=>$request->post(''),*/
                                'p_maturity_date' => HelperClass::dateFormatForDB($data['split_expiry_date']),
                                'p_interest_rate' => $data['split_interest_rate'],
                                'p_user_id' => Auth::id(),
                                /*                                'o_maturity_detail_id' => &$maturityId,*/
                                'o_status_code' => &$status_code,
                                'o_status_message' => &$status_message
                            ];
                            //dd(HelperClass::dateFormatForDB($data['split_renewal_date']));

                            DB::executeProcedure('CPAACC.fas_cm_trans.fas_fdr_investment_split_save', $params2);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            if (isset($id)) {
                                return ["response_code" => 99, "response_msg" => $e->getMessage()];
                            } else {
                                return response()->json(["response_code" => 99, "response_msg" => $e->getMessage()]);
                            }
                        }
                    }
                }

                $wk_mapping_status_code = sprintf("%4000d", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::CM_FDR_MATURITY_TRANSACTION,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_CM_FDR_MATURITY_TRANS,
                    'P_REFERANCE_KEY' => WkReferenceColumn::MATURITY_TRANS_ID,
                    'P_REFERANCE_ID' => $maturityId,
                    'P_TRANS_PERIOD_ID' => $request->post('period'),
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
                return ["response_code" => $status_code, "response_msg" => $status_message,"fiscal_year_id"=>$params['p_fiscal_year_id'],"document_no"=>$params['p_document_no'],"user_id"=>$user_id];
            } else {
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message,"fiscal_year_id"=>$params['p_fiscal_year_id'],"document_no"=>$params['p_document_no'],"user_id"=>$user_id]);
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
        $maturity_list = DB::select('select cpaacc.fas_cm_trans.get_fdr_maturity_trans_make_list(:p_investment_type_id,:p_fiscal_year_id,:p_posting_period_id,:p_workflow_approval_status) from dual',
            ['p_investment_type_id' => $inv_type, 'p_fiscal_year_id' => $fiscal_year, 'p_posting_period_id' => $period, 'p_workflow_approval_status' => $status]);

        return datatables()->of($maturity_list)
            ->editColumn('transaction_date', function ($d) {
                return HelperClass::dateFormatForDB($d->posting_date);
            })->editColumn('bank', function ($d) {
                return $d->bank_name;
            })->editColumn('branch', function ($d) {
                return $d->branch_name;
            })->editColumn('fdr_no', function ($d) {
                return $d->fdr_no;
            })->editColumn('amount', function ($d) {
                return HelperClass::getCommaSeparatedValue($d->total_amount);
            })->editColumn('auth_status', function ($d) {
                $status = '';
                switch ($d->workflow_approval_status) {
                    case 'A':
                        $status .= '<span class="badge-pill rounded-pill badge-success">' . $d->approval_status . '</span>';
                        break;
                    case 'P':
                        $status .= '<span class="badge-pill rounded-pill badge-warning">' . $d->approval_status . '</span>';
                        break;
                }
                return $status;
            })
            ->editColumn('action', function ($d) {
                return "<a href='" . route('fdr-maturity.view', ['id' => $d->maturity_trans_id]) . "' class='cursor-pointer'><i class='bx bx-show-alt'></i></a>";
            })
            ->rawColumns(['auth_status', 'action'])
            ->make(true);
    }

    public function preview(Request $request)
    {
        try {
            $transactionContents = DB::select("
SELECT * FROM TABLE (fas_cm_trans.get_fdr_maturity_trans_preview (
    :p_investment_type_id,:p_investment_id,:p_transaction_type,:p_principal_amt,:p_interest_amt,:p_investment_contra_gl_id))", [
        'p_investment_type_id' => $request->post('investmentType', null),
        'p_investment_id' => $request->post('investmentId', null),
        'p_transaction_type' => $request->post('transactionType', null),
        'p_principal_amt' => $request->post('principalAmt', null),
        'p_interest_amt' => $request->post('interestAmt', null),
        'p_investment_contra_gl_id' => $request->post('contraGl', null)
        ]);
            $previewTable = view("cm.cm-common.opening_preview_table")->with(compact('transactionContents'))->render();
            return response()->json(['status_code' => 1, 'content' => $previewTable]);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 99, 'content' => $e->getMessage()]);
        }
    }

    public function view(Request $request, $id)
    {
        /*
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();
        $transactionTypes = $this->lookupManager->getMaturityTransTypes();
        $department = $this->lookupManager->getSpecifiedDeptCostCenter([LCostCenterDept::FINANCE_ACCOUNTS_DEPARTMENT]);
        $invBillSec = $this->lookupManager->getBillSectionOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));
        $invBillReg = $this->lookupManager->getBillRegisterOnInvType(HelperClass::getUserCurrentInvestType(Auth::id()));

        $this->maturityInfo = DB::selectOne("SELECT FAS_CM_TRANS.get_fdr_maturity_trans_view (:p_maturity_trans_id ) FROM DUAL", ['p_maturity_trans_id' => $id]);
        $this->maturityInfo->splitInfo = FasCmFdrInvestmentSplit::where('maturity_trans_id', '=', $this->maturityInfo->maturity_trans_id)->get();
        $this->transactionView = DB::select("select fas_cm_trans.get_gl_transaction_view(:p_gl_trans_master_id) from dual", ['p_gl_trans_master_id' => $this->maturityInfo->gl_trans_master_id]) ?? [];*/

        $user_id = auth()->id();
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $transaction = DB::selectOne("select fas_cm_trans.get_fdr_maturity_trans_view(:p_trans_id) from dual",["p_trans_id"=>$id]);
        $splitInfo = FasCmFdrInvestmentSplit::where('maturity_trans_id', '=', $id)->get();
        $transactionContents = DB::select("select fas_cm_trans.get_gl_transaction_view(:p_gl_trans_master_id) from dual", ['p_gl_trans_master_id' => $transaction->gl_trans_master_id]) ?? [];
        $contraAcc = DB::select("select CPAACC.fas_cm_trans.get_fdr_investment_contra_ac (:p_investment_type_id) from dual",
            ['p_investment_type_id' => $transaction->investment_type_id]);
        //return view('cm.fdr-maturity.view', ['maturityInfo' => $this->maturityInfo, 'transactionContents' => $this->transactionView], compact('transactionTypes', 'investmentStatus', 'periodTypes', 'investmentTypes', 'fiscalYear', 'department', 'invBillSec', 'invBillReg'));
        return view('cm.fdr-maturity.view',compact(  'transactionContents','contraAcc','periodTypes', 'investmentTypes', 'fiscalYear','transaction','splitInfo','user_id' ));
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();

        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");
        $params = [];
        try {
            $params = [
                'p_maturity_trans_id' => $request->post('edit_maturity_id'),
                'p_fiscal_year_id' => $request->post('edit_fiscal_year'),
                'p_trans_period_id' => $request->post('edit_period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('edit_posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('edit_document_date')),
                'p_document_no' => $request->post('edit_document_number'),
                'p_narration' => $request->post('edit_narration'),
                'p_pay_order_no' => $request->post('edit_po_number'),
                'p_pay_oder_date' => HelperClass::dateFormatForDB($request->post('edit_po_date')),
                'p_user_id' => Auth::id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_cm_trans.fdr_maturity_trans_ref_update', $params);

            if ($status_code != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }

            DB::commit();
            return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["response_code" => 99, "response_msg" => $e->getMessage()]);
        }
    }
}

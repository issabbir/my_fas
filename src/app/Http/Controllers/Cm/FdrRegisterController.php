<?php


namespace App\Http\Controllers\Cm;


use App\Contracts\LookupContract;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Common\LFdrInvestmentStatus;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FdrRegisterController extends Controller
{
    private $lookupManager;
    private $glCoaParam;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
    }

    public function index($param = null)
    {
        //$fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //Viewing or editing FDR information inserted from dump data can't select posting period
        //For this reason FAS_CM_CONFIG.get_financial_years and FAS_CM_CONFIG.get_posting_periods provided.
        $fiscalYear = DB::select("select cpaacc.FAS_CM_CONFIG.get_financial_years from dual");

        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();

        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();
        $coaParams = $this->glCoaParam->get();
        $mode = isset($param) ? explode('#',Crypt::decryptString($param)) : $param;
        $investmentInfo = isset($param) ? (DB::selectOne('select cpaacc.fas_cm_config.get_fdr_investment_make_view (:p_investment_id) from dual',['p_investment_id'=>$mode[0]])) : null;

        return view('cm.fdr-investment-register.index', compact('mode','investmentInfo','coaParams', 'investmentStatus', 'periodTypes', 'fiscalYear', 'investmentTypes'));
    }

    public function store(Request $request, $id = null)
    {
        DB::beginTransaction();
        $fdrStatus = $request->post('fdr_status');
        $investment_id = isset($id) ? $id : null;
        $actionType = (isset($id) || ($fdrStatus == 'D') )? ProActionType::UPDATE : ProActionType::INSERT;
        $log_id = sprintf("%4000s", "");
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");
        $params = [];

        try {
            $params = [
                'p_investment_id' => &$investment_id,
                'p_action_type' => $actionType,
                'p_investment_type_id' => $request->post('investment_type'),
                'p_fiscal_year_id' => $request->post('fiscal_year'),
                'p_posting_period_id' => $request->post('posting_period'),
                'p_bank_code' => $request->post('bank_id'),
                'p_branch_code' => $request->post('branch_id'),
                'p_fdr_no' => $request->post('fdr_number'),
                'p_investment_date' => HelperClass::dateFormatForDB($request->post('investment_date')),
                'p_investment_amount' => HelperClass::removeCommaFromValue($request->post('amount')),
                'p_term_period_no' => $request->post('term_period'),
                'p_term_period_code' => $request->post('term_period_type'),
                'p_term_period_type' => $request->post('term_period_days'),
                'p_maturity_date' => HelperClass::dateFormatForDB($request->post('maturity_date')),
                'p_interest_rate' => $request->post('interest_rate'),
                'p_renewal_date' => HelperClass::dateFormatForDB($request->post('renewal_date')),
                'p_renewal_amount' => $request->post('renewal_amount'),
                /*'p_renewal_term_period_no' => $request->post('renewal_term_period'),
                'p_renewal_term_period_code' => $request->post('renewal_term_period_type'),
                'p_renewal_term_period_days' => $request->post('renewal_term_period_days'),
                */
                'p_renewal_maturity_date' => HelperClass::dateFormatForDB($request->post('renewal_maturity_date')),
                'p_renewal_interest_rate' => $request->post('renewal_interest_rate'),
                'p_investment_gl_acc_id' => $request->post('account_id'),
                'p_investment_status_id' => $request->post('investment_status'),
                'p_user_id' => Auth::id(),
                'o_investment_auth_log_id' => &$log_id,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_cm_config.fas_fdr_register_save', $params);

            if ($status_code != "1") {
                DB::rollBack();

                if (isset($id)){
                    return ["response_code" => $status_code, "response_msg" => $status_message];
                }else{
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                }
            }else{
                if (($id == null) || ($request->post('fdr_status') == "D")){   //Only call for registration purpose not allowed for edit
                    $wk_mapping_status_code = sprintf("%4000d", "");
                    $wk_mapping_status_message = sprintf("%4000s", "");

                    $wkMappingParams = [
                        'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::CM_FDR_INVESTMENT_REGISTER,
                        'P_REFERENCE_TABLE' => WkReferenceTable::FAS_CM_FDR_INVESTMENT_AUTH_LOG,
                        'P_REFERANCE_KEY' => WkReferenceColumn::INVESTMENT_AUTH_LOG_ID,
                        'P_REFERANCE_ID' => $log_id,//$log_id,
                        'P_TRANS_PERIOD_ID' => $params['p_posting_period_id'],
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
                if (isset($id)){
                    return ["response_code" => $status_code, "response_msg" => $status_message];
                }else{
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($id)){
                return ["response_code" => 99, "response_msg" => $e->getMessage()];
            }else{
                return response()->json(["response_code" => 99, "response_msg" => $e->getMessage()]);
            }
        }
    }

    public function dataTableList(Request $request)
    {

        $inv_type = $request->post('investment_type');
        $bank_id = $request->post('bank_id');
        $branch_id = $request->post('branch_id');
        $status = $request->post('approval_status');
        $investment_list = DB::select('select cpaacc.fas_cm_config.get_fdr_investment_make_list(:p_investment_type,:p_bank_code,:p_branch_code,:p_approval_status) from dual',
            ['p_investment_type'=>$inv_type,'p_bank_code'=>$bank_id,'p_branch_code'=>$branch_id,'p_approval_status'=>$status]);

        return datatables()->of($investment_list)
            ->editColumn('investment_date',function ($d){
                return HelperClass::dateFormatForDB($d->investment_date);
            })->editColumn('investment_type',function ($d){
                return $d->investment_type_name;
            })->editColumn('fdr_no',function ($d){
                return $d->fdr_no;
            })->editColumn('amount',function ($d){
                return HelperClass::getCommaSeparatedValue($d->investment_amount);
            })->editColumn('interest_rate',function ($d){
                return $d->interest_rate;
            })->editColumn('maturity_date',function ($d){
                return HelperClass::dateFormatForDB($d->maturity_date);
            })->editColumn('auth_status',function ($d){
                $status = '';
                switch ($d->workflow_approval_status){
                    case 'A':
                        $status .= '<span class="badge-pill rounded-pill badge-success">'.$d->approval_status.'</span>';
                        break;
                    case 'P':
                        $status .= '<span class="badge-pill rounded-pill badge-warning">'.$d->approval_status.'</span>';
                        break;
                    default:
                        $status .= '<span class="badge-pill rounded-pill badge-info">'.$d->approval_status.'</span>';
                        break;
                }
                return $status;
            })->editColumn('action',function ($d){
                $edit = '';
                if ($d->investment_status_id == LFdrInvestmentStatus::NEW_INVESTMENT || $d->investment_status_id == LFdrInvestmentStatus::NEW_SPLIT && $d->workflow_approval_status !='P'){
                    $edit .= "|<a href='".route('fdr-register.index',['param'=>Crypt::encryptString($d->investment_id.'#e')])."' ><i class='bx bx-edit-alt'></i></a>";
                }
                return "<a class='' href='".route('fdr-register.index',['param'=>Crypt::encryptString($d->investment_id.'#v')])."' ><i class='bx bx-show-alt'></i></a>".$edit;
            })
            ->rawColumns(['auth_status','action'])
            ->make(true);
    }

    public function update(Request $request,$id)
    {
        $response = $this->store($request,$id);
        return response()->json(["response_code" => $response["response_code"], "response_msg" => $response["response_msg"]]);
    }

    public function fdtMaturityDate(Request $request){

        $invst_date = HelperClass::dateFormatForDB($request->investMentDate);
        $termPeriodDays = $request->termPeriodDays;

        $data['investment_list'] = DB::selectOne('select cpaacc.fas_cm_config.get_maturity_date(:p_investment_date,:p_term_period_type) as maturity_date from dual',
            ['p_investment_date'=>$invst_date,'p_term_period_type'=>$termPeriodDays]);

        $data['maturity_date'] = date('d-m-Y',strtotime($data['investment_list']->maturity_date));

            return response()->json($data);
    }

}

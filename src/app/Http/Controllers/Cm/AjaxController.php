<?php
/**
 *Created by PhpStorm
 *Created at ৯/৯/২১ ৩:২৯ PM
 */

namespace App\Http\Controllers\Cm;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApInvoiceParams;
use App\Entities\Ap\FasApVendors;
use App\Entities\Cims\PurchaseRcvMst;
use App\Entities\Cm\CmBankDistrict;
use App\Entities\Cm\CmBankInfo;
use App\Entities\Common\FasCmBankBranchInfo;
use App\Entities\Common\LBillRegister;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Security\User;
use App\Entities\WorkFlowMapping;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LPeriodType;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AjaxController extends Controller
{
    protected $lookupManager;
    protected $purchaseRcvMst;
    protected $apLookUpManager;

    public function __construct(LookupManager $lookupManager, FlashMessageManager $flashMessageManager, ApLookupManager $apLookUpManager)
    {
        $this->lookupManager = $lookupManager;
        $this->purchaseRcvMst = new PurchaseRcvMst();
        $this->apLookUpManager = $apLookUpManager;
    }

    public function cmBanks(Request $request)
    {
        $cmBanks = [];
        $searchTerm = $request->get('term');

        $cmBanks = CmBankInfo::select('*')
            ->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(bank_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhere('bank_code', 'like', '' . trim($searchTerm) . '%');
            })->orderBy('bank_name', 'ASC')->limit(10)->get();

        //$cmBanks = $cmBanks->prepend( (object)['rn'=> '0','bank_code'=>" ",'bank_name'=>'<select>']);
        return $cmBanks;
    }

    public function cmBank(Request $request, $bankCode)
    {
        $cmBank = '';
        $cmBank = CmBankInfo::where('bank_code', '=', $bankCode)->first();
        return $cmBank;
    }

    public function cmBranches($bankCode, Request $request)
    {
        $searchTerm = $request->get('term');
        $cmBranches = [];
        $cmBranches = FasCmBankBranchInfo::with(['bank_district'])
            ->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(fas_cm_bank_branch.branch_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhereHas('bank_district', function($query) use ($searchTerm) {
                        $query->where(DB::raw('LOWER(district_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
                    });
            })
            ->where('bank_code', '=', $bankCode)
            ->limit(10)->get();

        return $cmBranches;
    }

    public function cmBranch(Request $request, $branchCode)
    {
        $cmBranch = '';
        $cmBranch = FasCmBankBranchInfo::with(['bank_district'])->where('branch_code', '=', $branchCode)->first();
        return $cmBranch;
    }

    public function cmBankDistricts(Request $request)
    {
        $cmBankDistricts = [];
        $searchTerm = $request->get('term');

        $cmBankDistricts = CmBankDistrict::select('*')
            ->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(district_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhere('district_code', 'like', '' . trim($searchTerm) . '%');
            })->orderBy('district_name', 'ASC')->limit(10)->get();

        return $cmBankDistricts;
    }

    public function cmBankDistrict(Request $request, $districtCode)
    {
        $cmBank = '';
        $cmBank = CmBankDistrict::where('district_code', '=', $districtCode)->first();
        return $cmBank;
    }

    public function getBranchesOnBank(Request $request)
    {
        $branches = $this->lookupManager->getBranchOnBank($request->get('id'));

        $option = "<option value=''>&lt;Select&gt;</option>";
        foreach ($branches as $param) {
            $option .= "<option value='$param->branch_code' data-routing='" . $param->routing_no . "'";
            if ($param->branch_code == $request->get('branch')) {
                $option .= 'Selected';
            }
            $option .= ">" . $param->bank_district->district_name . "-" . $param->branch_name . "</option>";
        }

        return response()->json($option);
    }

    public function glTypeAccWiseCoa(Request $request)
    {
        $glCoaInfo = '';
        $glTypeId = $request->get('gl_type_id');
        $glAccId = $request->get('gl_acc_id');

        $glCoaInfo = GlCoa::with('acc_type')->where(
            [
                ['gl_acc_id', '=', $glAccId],
                ['gl_type_id', '=', $glTypeId],
            ]
        )->first();

        return response()->json($glCoaInfo);
    }

    public function glTypeWiseCoaList(Request $request)
    {
        $postData = $request->post();
        $queryResult = [];

        if (empty($postData['gl_type_id'])) {
            $queryResult = [];
        } else {
            $queryResult = GlCoa::where('gl_type_id', $postData['gl_type_id'])->orderBy('gl_acc_id','asc')->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm gl-coa" data-gl-type="' . $query->gl_type_id . '"   id="' . $query->gl_acc_id . '">Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getClearingDetail(Request $request, $id, $funcType)
    {
        $response_code = '99';
        $response_data = [];
        $response = DB::selectOne('select cpaacc.fas_cm_trans.get_clearing_recon_view(:p_function_id,:p_clearing_id) from dual', ['p_function_id'=>$funcType,'p_clearing_id' => $id]);
        $authorize_view = "";
        if (isset($response)) {
            $response_code = '1';
            $response_data = $response;
            $response->clearing_date = HelperClass::dateConvert($response->clearing_date);
            $response->instrument_date = HelperClass::dateConvert($response->instrument_date);
            $response->trans_date = HelperClass::dateConvert($response->trans_date);
        }

        if (($request->get('wkf_map_id') != '') && ($request->get('wkf_user_id') != '') && ($request->get('wk_ref_status') != '')){
            $wkMapId = $request->get('wk_map_id');
            $userId = $request->get('wkf_user_id');
            $wkRefStatus = $request->get('wk_ref_status');

            $empInfo = User::with(['employee'])->where('user_id',$userId)->first();

            $wkMapInfo = WorkFlowMapping::where('workflow_mapping_id',$wkMapId)->first();
            $authorize_view = view("cm.cm-common.common_authorizer",compact('wkMapInfo', 'wkRefStatus','empInfo'))->render();

            $response_data->authorize_view = $authorize_view;
        }

        return response()->json(['response_code' => $response_code, 'response_data' => $response_data]);
    }

    public function interestProvisionList(Request $request)
    {
        $postData = $request->post();
        $statusCode = '';
        $statusMessage= '';
        $intProvList = [];
        /*dd($postData);*/

        $invTypId = isset($postData['inv_type_id']) ? $postData['inv_type_id'] : '';
        $fiscalYearId = isset($postData['fiscal_year']) ? $postData['fiscal_year'] : '';
        $intProvSearchBtnVal = isset($postData['int_prov_search_btn_val']) ? $postData['int_prov_search_btn_val'] : '';

        if ($invTypId && $fiscalYearId && ($intProvSearchBtnVal == YesNoFlag::YES) ) {

            $response = $this->checkFdrProvisionProcess($request);
            //Sujon-CR
            $statusCode = $response['o_status_code'];
            $statusMessage = $response['o_status_message'];
            if ($response['o_status_code'] == 1 ){
                $intProvList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_process_list(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$invTypId, 'p_fiscal_year_id' => $fiscalYearId ]);
            }
            /*if ($response['o_status_code'] != 1 ){
                $statusCode = $response['o_status_code'];
                $statusMessage = $response['o_status_message'];
            } else {
                $intProvList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_process_list(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$invTypId, 'p_fiscal_year_id' => $fiscalYearId ]);
            }*/
        } else if ($intProvSearchBtnVal == YesNoFlag::NO) {
            $intProvList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_process_list(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$invTypId, 'p_fiscal_year_id' => $fiscalYearId ]);
        }

        $html = view('cm.ajax.interest_provision_list')->with('intProvList', $intProvList)->render();
        //$html = view('cm.ajax.interest_provision_list')->with(compact('intProvList', 'statusMessage'))->render();

        $jsonArray = [
            'html' => $html,
            'statusCode' => $statusCode,
            'statusMessage' => $statusMessage
        ];

        return response()->json($jsonArray);
    }

    public function interestProvisionTransViewList(Request $request)
    {
        $postData = $request->post();
        $intProvTransViewList = [];

        $invTypId = isset($postData['inv_type_id']) ? $postData['inv_type_id'] : '';
        $fiscalYearId = isset($postData['fiscal_year']) ? $postData['fiscal_year'] : '';
        $paramVal = isset($postData['param_val']) ? $postData['param_val'] : '';

        if ($invTypId && $fiscalYearId && ($paramVal == YesNoFlag::YES) ) {

            $intProvTransViewList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_trans_preview(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$invTypId, 'p_fiscal_year_id' => $fiscalYearId ]);

         }

        $html = view('cm.ajax.interest_provision_trans_view_list')->with('intProvTransViewList', $intProvTransViewList)->render();
        //$html = view('cm.ajax.interest_provision_list')->with(compact('intProvList', 'statusMessage'))->render();
        //dd($intProvTransViewList);
        $jsonArray = [
            'html' => $html,
            'intProvTransViewList' => $intProvTransViewList
        ];

        return response()->json($jsonArray);
    }

    public function checkFdrProvisionProcess (Request $request)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_investment_type_id' => $postData['inv_type_id'],
                'p_fiscal_year_id' => $postData['fiscal_year'],
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.fas_cm_trans.check_fdr_provision_proccess', $params);

            if ($params['o_status_code'] != 1) {
                //DB::rollBack();
                return $params;
            }
        }
        catch (\Exception $e) {
            //DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        //DB::commit();
        return $params;
    }

    public function fdrInvestmentList(Request $request)
    {
        $inv_type = $request->post('investmentType');
        $fiscal_year = $request->post('fiscalYear');
        $period = $request->post('period');
        $bank_id = $request->post('bankId');
        $branch_id = $request->post('branchId');
        $investment_list = DB::select('select cpaacc.fas_cm_trans.get_fdr_investment_list(:p_investment_type_id,:p_fiscal_year_id,:p_posting_period_id,:p_bank_code,:p_branch_code) from dual',
            ['p_investment_type_id'=>$inv_type,'p_fiscal_year_id'=>$fiscal_year,'p_posting_period_id'=>$period,'p_bank_code'=>$bank_id,'p_branch_code'=>$branch_id]);

        return datatables()->of($investment_list)
            ->editColumn('investment_date',function ($d){
                return HelperClass::dateConvert($d->investment_date);
            })->editColumn('investment_type',function ($d){
                return $d->investment_type_name;
            })->editColumn('fdr_no',function ($d){
                return $d->fdr_no;
            })->editColumn('amount',function ($d){
                return HelperClass::getCommaSeparatedValue($d->investment_amount);
            })->editColumn('interest_rate',function ($d){
                return $d->interest_rate;
            })->editColumn('expiry_date',function ($d){
                return '';
            })
            ->editColumn('action',function ($d){
                return "<button type='button' class='fdr_select btn btn-sm btn-dark' data-fdr='".$d->investment_id."' >Select</button>";
            })
            ->rawColumns(['auth_status','action'])
            ->make(true);
    }

    public function fdrMaturityList(Request $request)
    {
        $inv_type = $request->post('investmentType');
        $transType = $request->post('transactionType');
        $documentDate = HelperClass::dateFormatForDB($request->post('documentDate'));
        $bank_id = $request->post('bankId');
        $branch_id = $request->post('branchId');
        $investment_list = DB::select('select cpaacc.fas_cm_trans.get_fdr_investment_maturity_list(:p_investment_type_id,:p_transaction_type,:p_document_date,:p_bank_code,:p_branch_code) from dual',
            ['p_investment_type_id'=>$inv_type,'p_transaction_type'=>$transType,'p_document_date'=>$documentDate,'p_bank_code'=>$bank_id,'p_branch_code'=>$branch_id]);

        return datatables()->of($investment_list)
            ->editColumn('investment_date',function ($d){
                return HelperClass::dateConvert($d->investment_date);
            })->editColumn('investment_type',function ($d){
                return $d->investment_type_name;
            })->editColumn('fdr_no',function ($d){
                return $d->fdr_no;
            })->editColumn('amount',function ($d){
                return HelperClass::getCommaSeparatedValue($d->investment_amount);
            })->editColumn('interest_rate',function ($d){
                return $d->interest_rate;
            })->editColumn('expiry_date',function ($d){
                return HelperClass::dateConvert($d->maturity_date);
            })
            ->editColumn('action',function ($d){
                return "<button type='button' class='fdr_select btn btn-sm btn-dark' data-fdr='".$d->investment_id."' >Select</button>";
            })
            ->rawColumns(['auth_status','action'])
            ->make(true);
    }

    public function fdrInvestmentDetails(Request $request)
    {
        $fdr_id = $request->post('fdr_id');
        $investment_type = $request->post('investment_type');
        $preContraGL = $request->post('contraGl');
        $fdr_info = DB::selectOne("select cpaacc.fas_cm_trans.get_fdr_investment_view(:p_investment_id) from dual",["p_investment_id"=>$fdr_id]);

        try {
            $fdr_info->investment_date = HelperClass::dateConvert($fdr_info->investment_date);
            $fdr_info->maturity_date = HelperClass::dateConvert($fdr_info->maturity_date);
            $fdr_info->investment_amount = HelperClass::getCommaSeparatedValue($fdr_info->investment_amount);

            $accountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual",
                ['p_gl_acc_id' => $fdr_info->investment_gl_acc_id]);
            $accountInfo->account_balance = HelperClass::getCommaSeparatedValue($accountInfo->account_balance);
            $accountInfo->authorize_balance = HelperClass::getCommaSeparatedValue($accountInfo->authorize_balance);

            $contraAccs = '';
            $accounts = DB::select("select CPAACC.fas_cm_trans.get_fdr_investment_contra_ac (:p_investment_type_id) from dual",
                ['p_investment_type_id' => $investment_type]);

            foreach ($accounts as $account){
                if ($preContraGL==$account->gl_acc_id)
                {
                    $contraAccs .= '<option selected value="'.$account->gl_acc_id.'">'.$account->gl_acc_name.'</option>';
                }else{
                    $contraAccs .= '<option value="'.$account->gl_acc_id.'">'.$account->gl_acc_name.'</option>';
                }
            }
            return response()->json(['status_code'=>'1',"fdr_info"=>$fdr_info,"acc_info"=>$accountInfo,'contra_acc_list'=>$contraAccs]);
        }catch (\Exception $e){
            return response()->json(['status_code'=>'99',"fdr_info"=>[],"acc_info"=>[],'contra_acc_list'=>[]]);
        }

    }

    public function fdrMaturityDetails(Request $request)
    {

        $fdr_id = $request->post('fdr_id');
        $investment_type = $request->post('investment_type');
        $preContraGL = $request->post('contraGl');
        $transaction_type = $request->post('transaction_type');

        $maturity_info = DB::selectOne("select cpaacc.fas_cm_trans.get_fdr_investment_maturity_view(:p_investment_id,:p_transaction_type) from dual",["p_investment_id"=>$fdr_id,'p_transaction_type'=>$transaction_type]);

        try {
            $maturity_info->investment_date = HelperClass::dateConvert($maturity_info->investment_date);
            $maturity_info->maturity_date = HelperClass::dateConvert($maturity_info->maturity_date);
            //$maturity_info->investment_amount = $maturity_info->investment_amount;

            $maturity_info->last_renewal_date = HelperClass::dateConvert($maturity_info->last_renewal_date);
            $maturity_info->last_renewal_maturity_date = HelperClass::dateConvert($maturity_info->last_renewal_maturity_date);
            //$maturity_info->last_renewal_amount =$maturity_info->last_renewal_amount;


            $maturity_info->curr_renewal_date = HelperClass::dateConvert($maturity_info->curr_renewal_date);
            $maturity_info->curr_renewal_maturity_date = HelperClass::dateConvert($maturity_info->curr_renewal_maturity_date);
            //$maturity_info->curr_renewal_amount =$maturity_info->curr_renewal_amount;
            //$maturity_info->curr_renewal_interest_rate =$maturity_info->curr_renewal_interest_rate;

            if ($maturity_info->term_period_type == 'A'){
                $term_period = LPeriodType::Actual;
            }else{
                $term_period = LPeriodType::Flat;
            }



            $accountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual",
                ['p_gl_acc_id' => $maturity_info->investment_gl_acc_id]);
            $accountInfo->account_balance = $accountInfo->account_balance;
            $accountInfo->authorize_balance = $accountInfo->authorize_balance;

            $contraAccs = '';
            $accounts = DB::select("select CPAACC.fas_cm_trans.get_fdr_investment_contra_ac (:p_investment_type_id) from dual",
                ['p_investment_type_id' => $investment_type]);

            foreach ($accounts as $account){
                if ($preContraGL==$account->gl_acc_id)
                {
                    $contraAccs .= '<option selected value="'.$account->gl_acc_id.'">'.$account->gl_acc_name.'</option>';
                }else{
                    $contraAccs .= '<option value="'.$account->gl_acc_id.'">'.$account->gl_acc_name.'</option>';
                }
            }
            return response()->json(['status_code'=>'1',"fdr_info"=>$maturity_info,"acc_info"=>$accountInfo,'contra_acc_list'=>$contraAccs,'term_period'=>$term_period]);
        }catch (\Exception $e){
            return response()->json(['status_code'=>'99','status_message'=>$e->getMessage(),"fdr_info"=>[],"acc_info"=>[],'contra_acc_list'=>[]]);
        }

    }

    public function getPostingPeriod(Request $request)
    {
        //Viewing or editing FDR information inserted from dump data can't select posting period.
        //For this reason FAS_CM_CONFIG.get_financial_years and FAS_CM_CONFIG.get_posting_periods provided.

        $periods = DB::select("select CPAACC.fas_cm_config.get_posting_periods(:p_fiscal_year_id) from dual", ["p_fiscal_year_id" => $request->get("calenderId")]);
        //dd($periods);
        $preSelected = $request->post('preselected',null);
        $periodHtml = '<option value="">&lt;Select&gt;</option>';

        if (isset($periods)) {
            foreach ($periods as $period) {
                $periodHtml .= "<option " . ( isset($preSelected) ? (($period->posting_period_id == $preSelected) ? 'selected': '') : '' /*(($period->posting_period_status == 'O') ? 'selected' : '')*/ ). "
                                        data-currentdate='false'
                                        data-postingname='" . $period->posting_period_name . "'
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";

            }
        } /*else {
            $periodHtml = "<option value=''></option>";
        }*/

        return response()->json(['period' => $periodHtml]);
    }

    public function sectionByRegisterList(Request $request, $sectionId)
    {
        $searchTerm = $request->get('term');
        return $this->lookupManager->getBillRegistersOnSection($sectionId, $searchTerm);
    }

    public function reportGlPostingPeriods(Request $request, $isNullOption = false)
    {

        $fromPeriods = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_posting_periods(:p_fiscal_year_id) from dual', ['p_fiscal_year_id' => $request->get("fiscal_year_id")]);
        $fromOptions = ($isNullOption == "true") ? '<option value="">Select Period</option>' : '';

        foreach ($fromPeriods as $period) {
            $selected = "";
            /*if ($period->posting_period_status == 'O') {
                $selected = "selected";
            }*/
            $fromOptions .= '<option ' . $selected . ' data-mindate="' . HelperClass::dateConvert($period->posting_period_beg_date) . '"
                                        data-maxdate="' . HelperClass::dateConvert($period->posting_period_end_date) . '"
                                         value="' . $period->posting_period_id . '">' . $period->posting_period_name . ' </option>';
        }

        $toPeriods = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_posting_periods(:p_fiscal_year_id) from dual', ['p_fiscal_year_id' => $request->get("fiscal_year_id")]);
        $toOptions = '';
        foreach ($toPeriods as $period) {

            $toOptions .= '<option data-mindate="' . HelperClass::dateConvert($period->posting_period_beg_date) . '"
                                        data-maxdate="' . HelperClass::dateConvert($period->posting_period_end_date) . '"
                                         value="' . $period->posting_period_id . '">' . $period->posting_period_name . ' </option>';
        }

        return response()->json(['fromOptions' => $fromOptions, 'toOptions' => $toOptions]);
    }

}

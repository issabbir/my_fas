<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 4/28/20
 * Time: 3:45 PM
 */

namespace App\Managers;


//use Apiz\Http\Request;
use App\Entities\Common\LBillSectionFuncMap;
use App\Entities\Common\LBudgetCategory;
use App\Entities\Common\LBudgetSubCategory;
use App\Entities\Common\LBudgetType;
use App\Entities\Common\LCmFdrInvestmentStatus;
use App\Entities\Common\LCostCenterClusterMaster;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Common\LFdrInvestmentStatus;
use App\Entities\Common\LFdrInvestmentType;
use App\Entities\Common\LFdrMaturityTransType;
use App\Entities\Common\LInvestmentType;
use App\Entities\Gl\LPeriodType;
use App\Enums\Common\GlAccountID;
use Illuminate\Http\Request;
use App\Contracts\LookupContract;
use App\Entities\Admin\LBranch;
use App\Entities\Admin\LGeoCountry;
use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoDivision;
use App\Entities\Admin\LGeoThana;
use App\Entities\Ar\LArReceiptMethods;
use App\Entities\Ar\LArReceiptTerms;
use App\Entities\Budget\BudgetHeadMaster;
use App\Entities\Common\FasCmBankBranchInfo;
use App\Entities\Common\FasCmBankDistrictInfo;
use App\Entities\Common\FasCmBankInfo;
use App\Entities\Common\LAddressType;
use App\Entities\Common\LApPaymentMethod;
use App\Entities\Common\LApPaymentTerm;
use App\Entities\Common\LBankAccountType;
use App\Entities\Common\LBillSection;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Common\LGlIntegrationModules;
use App\Entities\Common\LGlSubsidiaryType;
use App\Entities\Common\VwDepartment;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Common\LCurrency;
use App\Entities\Security\SecUserRoles;
use App\Entities\WorkFlowTemplate;
use App\Enums\Common\LGlInteModules;
use App\Enums\Gl\CalendarStatus;
use App\Enums\YesNoFlag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Lcm;

class LookupManager implements LookupContract
{
    /**
     * @return LGeoDivision[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findDivisions()
    {
        return LGeoDivision::all();
    }

    /**
     * @param null $divisionId
     * @return LGeoDistrict[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findDistrictsByDivision($divisionId = null)
    {
        if ($divisionId) {
            return LGeoDistrict::where('geo_division_id', $divisionId)->get();
        }

        return LgeoDistrict::all();
    }

    /**
     * @param $districtId
     * @return LGeoThana[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findThanasByDistrict($districtId)
    {
        if ($districtId) {
            return LGeoThana::where('geo_district_id', $districtId)->get();
        }

        return LGeoThana::all();
    }

    /**
     * @param $bankId
     * @return LBranch[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findBranchesByBank($bankId)
    {
        if ($bankId) {
            return LBranch::where('bank_id', $bankId)->get();
        }

        return LBranch::all();
    }

    public function findGlCoaParams()
    {
        return GlCoaParams::all();
    }

    public function getSpecifiedGlCoaParams($glType)
    {
        return GlCoaParams::select('*')->whereIn('gl_type_id', $glType)->get();
    }

    public function findLCurrency()
    {
        return LCurrency::all();
    }

    public function findCurDate()
    {
        return DB::selectOne("SELECT (TO_CHAR(SYSDATE, 'DD-MM-YYYY')) AS cur_date FROM dual");
    }

    public function findBudgetHead()
    {
        return BudgetHeadMaster::all();
    }

    public function findVwDepartment()
    {
        return VwDepartment::all();
    }

    public function findLBillSec()
    {
        return LBillSection::orderby('bill_sec_name','ASC')->get();
    }

    public function findPostingPeriod($year_id)
    {
        //return CalendarDetail::where('posting_period_status', CalendarStatus::OPENED)->orWhere('posting_period_status', CalendarStatus::OPENED_SPECIAL)->get();
        return DB::select("select CPAACC.FAS_CONFIG.get_current_posting_period(:p_calendar_id) from dual", ["p_calendar_id" => $year_id]);
    }

    public function findYearEndPostingPeriod($year_id)
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_year_end_period (:p_fiscal_year_id) from dual", ['p_fiscal_year_id' => $year_id]);
    }

    public function getACurrentFinancialYear()
    {
        return DB::selectOne("select CPAACC.FAS_CONFIG.get_current_financial_year from dual");
    }

    public function getOldFiscalYear()
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_previous_financial_year from dual");
    }

    public function getOldPeriods($yearId, $sortOrder)
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_previous_posting_period(:p_fiscal_year_id,:p_sort_order) from dual", ["p_fiscal_year_id" => $yearId, "p_sort_order" => $sortOrder]);
    }

    public function getIntegrationFunType($parentId, $moduleId)
    {
        return LGlIntegrationFunctions::where(
            [
                ['function_parent_id', '=', $parentId],
                ['module_id', '=', $moduleId],
                ['active_yn', '=', 'Y']
            ]
        )
            ->orderBy('function_id', 'asc')
            ->get();
    }

    public function getIntegrationFunList($moduleId)
    {
        return LGlIntegrationFunctions::where(['module_id' => $moduleId, 'active_yn' => 'Y'])
            ->whereNotNull('function_parent_id')
            ->orderBy('function_id', 'ASC')->get();
    }

    public function getIntegrationFunListOnAuth()
    {
        $secUserRole = new SecUserRoles();
        $workFlowTemplate = new WorkFlowTemplate();

        $userTemplates = $secUserRole->select('user_id', 'role_id')
            ->with('role:role_id,role_key', 'role.workflow_template:step_role_key,integration_function_id')
            ->whereHas('role', function ($q) use ($workFlowTemplate) {
                $q->whereIn('role_key', $workFlowTemplate->select('step_role_key')->get());
            })->where('user_id', '=', Auth::id())->get();

        $functionIds = [];
        foreach ($userTemplates as $t) {
            $functionIds[]['function_parent_id'] = $t->role->workflow_template->integration_function_id;
        }

        return LGlIntegrationFunctions::whereIn('function_parent_id', $functionIds)
            ->where(['active_yn' => 'Y'])
            ->orderBy('function_id', 'asc')
            ->get();
    }

    public function findLGlSubsidiaryType()
    {
        return LGlSubsidiaryType::where('active_yn', '=', 'Y')->get();
    }

    public function findLGlIntegrationModules()
    {
        return LGlIntegrationModules::all();
    }


    public function findPartySubLedger($moduleId = null, $subsidiaryType = null)
    {
        //$id = is_null($moduleId) ? 3 : $moduleId;
        //'" . $id . "' '".$subsidiaryType."'
        return DB::select("SELECT t.* ,ROWIDTOCHAR(t.rowid)
FROM   fas_gl_sub_params t --fas_gl_subsidiary_params t
WHERE  t.module_id = :module_id AND (t.GL_SUBSIDIARY_TYPE_ID = NVL(:gl_subsidiary_type_id,t.GL_SUBSIDIARY_TYPE_ID))
ORDER  BY t.order_sl
        ,t.gl_subsidiary_name", ['module_id' => $moduleId, 'gl_subsidiary_type_id' => $subsidiaryType]);
    }


    public function findExcVatTaxPartySubLedger($moduleId = null)
    {
        $p1 = \App\Enums\Common\LGlSubsidiaryType::TAX_PAYABLE;
        $p2 = \App\Enums\Common\LGlSubsidiaryType::VAT_PAYABLE;

        $query = <<<QUERY
SELECT t.* ,ROWIDTOCHAR(t.rowid)
FROM   fas_gl_sub_params t --fas_gl_subsidiary_params t
WHERE  t.module_id = :module_id
AND    t.active_yn = :active_yn
AND t.GL_SUBSIDIARY_TYPE_ID NOT IN ('$p1','$p2')
ORDER  BY t.order_sl
        ,t.gl_subsidiary_name
QUERY;
        return DB::select($query, ['module_id' => $moduleId, 'active_yn' => YesNoFlag::YES]);
    }

    public function findIncVatTaxAcPayPartySubLedger($moduleId = null)
    {
        $p1 = \App\Enums\Common\LGlSubsidiaryType::ACCOUNTS_PAYABLE;
        $p2 = \App\Enums\Common\LGlSubsidiaryType::TAX_PAYABLE;
        $p3 = \App\Enums\Common\LGlSubsidiaryType::VAT_PAYABLE;

        return DB::select("SELECT t.* ,ROWIDTOCHAR(t.rowid)
FROM   fas_gl_sub_params t --fas_gl_subsidiary_params t
WHERE  t.module_id = :module_id
--AND ( t.GL_SUBSIDIARY_TYPE_ID = NVL(:gl_subsidiary_type_id, t.GL_SUBSIDIARY_TYPE_ID) )
AND    t.active_yn = :active_yn
AND t.GL_SUBSIDIARY_TYPE_ID IN ('$p1','$p2','$p3') -- [21=accounts payable, 22=tax payable, 23=vat payable]
ORDER  BY t.order_sl
        ,t.gl_subsidiary_name", ['module_id' => $moduleId, 'active_yn' => YesNoFlag::YES]);
    }

    public function getContraPartySubLedger($moduleId = null, $selectedId)
    {
        $id = is_null($moduleId) ? LGlInteModules::ACC_PAY_VENDOR : $moduleId;

        return DB::select("SELECT t.* ,ROWIDTOCHAR(t.rowid)
FROM   fas_gl_sub_params t --fas_gl_subsidiary_params t
WHERE  t.module_id = '" . $id . "' AND t.gl_subsidiary_id != '" . $selectedId . "'
ORDER  BY t.gl_subsidiary_type_id DESC
        ,t.gl_subsidiary_name");
    }

    function findCashAccParams(){
        $p1 = GlAccountID::SONALI_BANK_PORT_BR_CD;
        $p2 = GlAccountID::SONALI_BANK_PORT_BR_STD;

        return DB::select("SELECT a.cash_acc_param_id,
       a.gl_acc_id,
       b.gl_acc_id || '-( coa-' || b.gl_acc_code || ') ' || b.gl_acc_name
           as gl_acc_name
  FROM cpaacc.fas_gl_acc_params_cash a, cpaacc.fas_gl_coa b
 WHERE a.gl_acc_id = b.gl_acc_id AND  b.gl_acc_id IN ( '$p1', '$p2')");

    }

    public function getAddressType()
    {
        return LAddressType::all();
    }

    public function getCountry()
    {
        return LGeoCountry::all();
    }

    public function getBankInfo()
    {
        return FasCmBankInfo::orderBy('bank_name')->get();
    }

    public function getBankDistrict()
    {
        return FasCmBankDistrictInfo::orderBy('district_name')->get();
    }

    public function getBankBranch()
    {
        return FasCmBankBranchInfo::orderBy('branch_name')->get();
    }

    public function getBankAccountType()
    {
        return LBankAccountType::all();
    }

    public function getBranchOnBank($id)
    {
        return FasCmBankBranchInfo::with('bank_district')->where('bank_code', '=', $id)->get();
    }

    public function getPaymentTerms()
    {
        return LApPaymentTerm::all();
    }

    public function getPaymentMethods()
    {
        return LApPaymentMethod::all();
    }

    public function getArPaymentTerms()
    {
        return LArReceiptTerms::all();
    }

    public function getArPaymentMethods()
    {
        return LArReceiptMethods::all();
    }

    public function findLastPostingBatchId($moduleId, $childFunctionId, $userID)
    {
        $sql = "select cpaacc.FAS_GL_TRANS.get_trans_last_batch_id (:p_module_id,:p_function_id,:p_user_id) AS last_posting_batch_id from dual";
        return DB::selectOne($sql, ['p_module_id' => $moduleId, 'p_function_id' => $childFunctionId, 'p_user_id' => $userID]);
    }

    public function findArPartySubLedger($moduleId = null)
    {
        return DB::select("SELECT t.gl_subsidiary_id, t.gl_subsidiary_name, t.gl_acc_id FROM fas_gl_sub_params t /*fas_gl_subsidiary_params t*/  WHERE t.module_id = :module_id AND t.active_yn = :active_yn", ['module_id' => $moduleId, 'active_yn' => YesNoFlag::YES]);
    }

    public function getGLModuleList()
    {
        return DB::select("SELECT cpaacc.FAS_REPORT_CONTROL.get_gl_integration_modules FROM dual");
    }

    public function getARModuleList()
    {
        return DB::select("SELECT cpaacc.FAS_REPORT_CONTROL.get_ar_integration_modules FROM dual");
    }

    public function getAPModuleList()
    {
        return DB::select("SELECT cpaacc.FAS_REPORT_CONTROL.get_ap_integration_modules FROM dual");
    }

    public function getCurrentFinancialYear()
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_current_financial_year from dual");
    }

    public function getDeptCostCenter()
    {
        //return DB::select("select CPAACC.fas_budget.get_initial_dept_cost_centers(:p_fiscal_calendar_id) from dual",["p_fiscal_calendar_id"=>(int)$calendarId]);
        return DB::select("select CPAACC.FAS_CONFIG.get_cost_center_dept from dual");
    }

    public function getSpecifiedDeptCostCenter($deptArray)
    {
        //return DB::select("select CPAACC.fas_budget.get_initial_dept_cost_centers(:p_fiscal_calendar_id) from dual",["p_fiscal_calendar_id"=>(int)$calendarId]);
        return LCostCenterDept::select('cost_center_dept_id', 'cost_center_dept_name')
            ->whereIn('cost_center_dept_id', $deptArray)
            ->get();
    }

    public function getBillSections($funcId)
    {

        $user = Auth::user()->user_id ;
        $data = DB::select("select CPAACC.FAS_CONFIG.get_bill_section(:p_function_id,:p_user_id) from dual", ["p_function_id" => $funcId,'p_user_id'=>$user]);
//        $data = DB::select("select CPAACC.FAS_CONFIG.get_bill_section(:p_function_id) from dual", ["p_function_id" => $funcId]);
//dd($funcId,$data);

        return $data;
    }

    public function getBillSectionOnInvType($typeId)
    {
        return LFdrInvestmentType::with('billSection')
            ->where('investment_type_id','=',$typeId)
            ->first();
    }

    public function getBillRegisterOnInvType($typeId, $join="billRegister")
    {
        return LFdrInvestmentType::with($join)
            ->where('investment_type_id','=',$typeId)
            ->first();
    }

    public function getBillRegistersOnSection($secId, $searchTerm)
    {
        $filteredSearchTerm = strtolower('%' . trim($searchTerm) . '%');
        return DB::select("select CPAACC.FAS_CONFIG.get_bill_register(:p_bill_sec_id,:p_bill_reg_name) from dual", ["p_bill_sec_id" => $secId, "p_bill_reg_name" => $filteredSearchTerm]);
    }

    public function getBillRegisterOnFunction($functionId)
    {
        return DB::select("select CPAACC.fas_gl_config.get_bill_register_list(:p_func_id) from dual", ["p_func_id" => $functionId]);
    }

    public function getPmisBills()
    {
        return DB::select('select cpaacc.fas_ap_config.get_ap_pmis_bill_names() from dual');
    }

    public function getBudgetTypes()
    {
        return LBudgetType::all();
    }

    public function getCategoriesOnBudgetType($id)
    {
        return LBudgetCategory::where('budget_type_id', $id)->get();
    }

    public function getSubCategoriesOnCategory($id)
    {
        return LBudgetSubCategory::where('budget_category_id', $id)->get();
    }

    public function getDeptClusters()
    {
        return LCostCenterClusterMaster::orderBy('cost_center_cluster_name', 'asc')->get();
    }

    public function getLFdrInvestmentType()
    {
        return LFdrInvestmentType::all();
    }

    public function getInvestmentPeriodTypes()
    {
        return LPeriodType::all();
    }

    public function getFdrInvestmentStatus()
    {
        return LFdrInvestmentStatus::all();
    }

    public function getMaturityTransTypes()
    {
        return LFdrMaturityTransType::all();
    }
}

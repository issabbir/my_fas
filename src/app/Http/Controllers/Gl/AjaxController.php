<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Common\LBillRegister;
use App\Entities\Gl\GlTransMaster;
use App\Entities\Gl\GlCashAccParams;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\LGlCashAccType;
use App\Entities\Gl\LGlRevenueAccType;
use App\Enums\ApprovalStatus;
use App\Enums\Common\DebitCredit;
use App\Enums\Common\GlAccountID;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Gl\FunctionTypes;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\LookupManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{

    /** @var EmployeeManager */
    private $employeeManager;
    protected $slaJournalMasterVu;
    protected $coa;
    protected $cashAccParams;
    protected $cashAccount;
    protected $revenueAccount;
    protected $lookupManager;


    public function __construct(EmployeeContract $employeeManager, LookupManager $lookupManager)
    {
        $this->employeeManager = $employeeManager;
        $this->coa = new GlCoa();
        $this->cashAccParams = new GlCashAccParams();
        $this->cashAccount = new LGlCashAccType();
        $this->revenueAccount = new LGlRevenueAccType();
        $this->lookupManager = $lookupManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);

        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->employeeManager->findEmployeeInformation($empId);
    }

    public function glAccountsList(Request $request)
    {

        $searchTerm = $request->get('term');
        $glAccounts = [];
        $glAccounts = GlCoa::select('*')
            ->where(DB::raw('LOWER(gl_acc_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
            ->orderBy('gl_acc_id', 'ASC')->limit(10)->get();
        return $glAccounts;
    }

    public function sectionByRegisterList(Request $request, $sectionId)
    {
        $searchTerm = $request->get('term');
        $billRegisters = [];
        $responses = LBillRegister::select('*')
            ->where(DB::raw('LOWER(bill_reg_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
            ->where('bill_sec_id', '=', $sectionId)
            ->orderBy('bill_reg_id', 'ASC')->limit(10)->get();

        foreach ($responses as $response) {
            $current_account = DB::selectOne("select CPAACC.fas_gl_config.get_current_account (:p_bill_sec_id,:p_bil_reg_id) from dual", ['p_bill_sec_id' => $sectionId, 'p_bil_reg_id' => $response->bill_reg_id]);
            $response['current_account'] = $current_account->gl_acc_id;
        }

        return $responses;
    }

    public function funTypeByDebitBankAccList(Request $request, $funTypeId)
    {
        $searchTerm = $request->get('term');
        $filteredSearchTerm = strtolower('%' . trim($searchTerm) . '%');
        $debitBankAccounts = [];

        $debitBankAccounts = DB::select("select CPAACC.fas_gl_trans.get_cash_account_heads (:p_gl_acc_name,:p_function_id,:p_dr_cr) from dual",
            ['p_gl_acc_name' => $filteredSearchTerm, 'p_function_id' => $funTypeId, 'p_dr_cr' => DebitCredit::DEBIT]);
        return $debitBankAccounts;
    }


    public function funTypeByCreditBankAccList(Request $request, $funTypeId)
    {

        $searchTerm = $request->get('term');
        $filteredSearchTerm = strtolower('%' . trim($searchTerm) . '%');
        $creditBankAccounts = [];

        $creditBankAccounts = DB::select("select CPAACC.fas_gl_trans.get_cash_account_heads (:p_gl_acc_name,:p_function_id,:p_dr_cr) from dual",
            ['p_gl_acc_name' => $filteredSearchTerm, 'p_function_id' => $funTypeId, 'p_dr_cr' => DebitCredit::CREDIT]);

        return $creditBankAccounts;
    }


    public function bankAccountDetails(Request $request, $accountId)
    {
        $bankAccountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual", ['p_gl_acc_id' => $accountId]);
        $bankAccountInfo->account_balance = HelperClass::getCommaSeparatedValue($bankAccountInfo->account_balance);
        $bankAccountInfo->authorize_balance = HelperClass::getCommaSeparatedValue($bankAccountInfo->authorize_balance);
        return response()->json($bankAccountInfo);
    }

    public function bankAccountDetailsByFuncId(Request $request)
    {
        $accountId = $request->post('accId');
        $bankAccountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual", ['p_gl_acc_id' => $accountId]);
        return response()->json($bankAccountInfo);
    }


    public function getAccountInfo(Request $request)
    {
        $accountId = $request->post('accId');
        $accountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual",
            ['p_gl_acc_id' => $accountId]);

        $partySubLedgers = '';
        $partyInfo = null;

        if (isset($accountInfo)){
            $ledgers = DB::select("select CPAACC.fas_gl_trans.get_gl_subsidiary_ledger (:p_module_id,:p_gl_acc_id) from dual", ['p_module_id' => $accountInfo->module_id, 'p_gl_acc_id' => $accountId]);

            foreach ($ledgers as $ledger) {

                $partySubLedgers .= '<option value="' . $ledger->gl_subsidiary_id . '" data-partyparams="' . $ledger->vendor_type_id . '#' . $ledger->vendor_category_id . '#' . $ledger->gl_subsidiary_type_id.'">' . $ledger->gl_subsidiary_name . '</option>';
                if ($accountId == GlAccountID::TDS_Tax_Deduction_At_Source_Payable || $accountId == GlAccountID::VDS_Vat_Deduction_At_Source_Payable) {
                    $partyInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_party_account_info (:p_gl_subsidiary_id,:p_vendor_id,:p_customer_id) from dual",
                        ['p_gl_subsidiary_id' => $ledger->gl_subsidiary_id, 'p_vendor_id' => $ledger->vendor_id, 'p_customer_id' => $ledger->customer_id]);
                }
            }
        }

        return response()->json(['account_info'=>$accountInfo,'sub_ledgers'=>$partySubLedgers,'party_info'=>$partyInfo]);
    }

    public function getPartyAccountInfo(Request $request)
    {
        $gl_subsidiary_id = $request->post('glSubsidiaryId');
        $vendor_id = $request->post('vendorId',NULL);
        $customer_id = $request->post('customerId',NULL);

        $partyInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_party_account_info (:p_gl_subsidiary_id,:p_vendor_id,:p_customer_id) from dual",
            ['p_gl_subsidiary_id' => $gl_subsidiary_id, 'p_vendor_id' => $vendor_id, 'p_customer_id' => $customer_id]);

        return response()->json(['party_info'=>$partyInfo]);
    }

    public function glTransactionMstDetails(Request $request)
    {
        $glTransMstInfo = '';
        $glTransMstId = $request->get('trans_mst_id');
        $glTransMstInfo = GlTransMaster::with(['fun_type', 'bill_sec', 'bill_reg', 'dept', 'approval_status', 'approval_status.emp_info', 'attachments:trans_master_id,trans_doc_file_id,trans_doc_file_name,trans_doc_file_desc'])
            ->where('trans_master_id', $glTransMstId)
            ->first();
        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $billSecs = $this->lookupManager->findLBillSec();

        $glTransDtl = DB::selectOne('select cpaacc.fas_gl_trans.get_transaction_detail_view(:p_trans_master_id) from dual', ['p_trans_master_id' => $glTransMstId]);
        //$glTransMstInfo['edit_ref_ui'] = view('gl.common_edit_reference',compact('glTransDtl','postingDate','billSecs'))->render();
        /**
         * 0002684: TRANSACTION EDIT/CANCEL LOG (FOR GL, AP, AR, Budget MODULE)
         * Check users cancel permission status for authorize invoice view: Start*
         */
        $cancelPermission = 'hidden';
        if($glTransMstInfo->workflow_approval_status == ApprovalStatus::APPROVED){
            switch($glTransMstInfo->fun_type->function_parent_id){
                case LGlInteFun::CASH_REC_VOUCHER:
                    $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_GL_RECEIVE_VOUCHER);
                    $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
                    break;
                case LGlInteFun::CASH_PAY_VOUCHER:
                    $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_GL_PAYMENT_VOUCHER);
                    $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
                    break;
                case LGlInteFun::CASH_TRANS_VOUCHER:
                    $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_GL_TRANSFER_VOUCHER);
                    $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
                    break;
                default:    //For Journal Voucher
                    $response = HelperClass::checkRoleStatus(WorkFlowRoleKey::CAN_CANCEL_GL_JOURNAL_VOUCHER);
                    $cancelPermission = isset($response) ? '' : 'hidden'; //To make cancel field hidden/show
                    break;
            }
        }
        $glTransMstInfo['cancel_permission'] = $cancelPermission;
        /**Check users cancel permission status for authorize invoice view: END**/
        $wkMasterId = '';
        $funcId = $glTransMstInfo->function_id;
        switch (true) {
            case (FunctionTypes::BANK_RECEIVE == $funcId || FunctionTypes::CASH_RECEIVE == $funcId):
                $wkMasterId = WorkFlowMaster::GL_RECEIVE_VOUCHER_APPROVAL;
                break;
            case (FunctionTypes::BANK_PAYMENT == $funcId || FunctionTypes::CASH_PAYMENT == $funcId):
                $wkMasterId = WorkFlowMaster::GL_PAYMENT_VOUCHER_APPROVAL;
                break;
            case FunctionTypes::BANK_TRANSFER == $funcId:
                $wkMasterId = WorkFlowMaster::GL_TRANSFER_VOUCHER_APPROVAL;
                break;
            default:
                $wkMasterId = WorkFlowMaster::GL_JOURNAL_VOUCHER_APPROVAL;
                break;
        }
        //Fetching Authorize steps
        $glTransMstInfo['authStep'] = HelperClass::workflow(WkReferenceTable::FAS_GL_TRANS_MASTER, WkReferenceColumn::TRANS_MASTER_ID, $glTransMstId, $wkMasterId);
        return $glTransMstInfo;
    }

    public function cashAccountDetails(Request $request)
    {
        $glTypeId = $request->post('glTypeId');
        $accountId = $request->post('accountId');
        $account = $this->cashAccount->with(['coa' => function ($q) use ($accountId) {
            $q->where('gl_acc_id', '=', $accountId);
        }])->where('gl_type_id', '=', $glTypeId)->first();
        return response()->json($account);
    }

    public function revenueAccountDetails(Request $request)
    {
        $glTypeId = $request->post('glTypeId');
        $accountId = $request->post('accountId');
        $account = $this->revenueAccount->with(['coa' => function ($q) use ($accountId) {
            $q->where('gl_acc_id', '=', $accountId);
        }])->where('gl_type_id', '=', $glTypeId)->first();
        return response()->json($account);
    }

    public function coaAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');
        $sql = $this->coa->where('gl_type_id', '=', $glType);

        if (isset($accNameCode)) {
            $sql->where(function ($q) use ($accNameCode) {
                $q->Where(DB::raw('upper(gl_acc_name)'), 'like', '%' . strtoupper($accNameCode) . '%')
                    //->orWhere('gl_acc_id', 'like', '%' . $accNameCode . '%');
                    ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                    ->orWhere(DB::raw('to_char(fas_gl_coa.old_coa_code)'), '=', trim($accNameCode))      //Add two condition Part :pavel-10-04-2022
                    ->orWhere(DB::raw('to_char(fas_gl_coa.old_sub_code)'), '=', trim($accNameCode));
            });
        }
        $bankAccounts = $sql->orderBy('gl_acc_id','asc')->get();

        return datatables()->of($bankAccounts)
            ->addIndexColumn()
            ->editColumn('gl_acc_code', function ($data) {
                return $data->gl_acc_code;
            })
            ->editColumn('gl_acc_id', function ($data) {
                return $data->gl_acc_id;
            })
            ->editColumn('gl_acc_name', function ($data) {
                return $data->gl_acc_name;
            })
            ->editColumn('action', function ($data) {
                return "<button class='btn btn-sm btn-primary' onclick='getAccountDetail($data->gl_type_id,$data->gl_acc_id)' >Select</button>";
            })
            ->make(true);

    }

    public function coaDetails(Request $request)
    {
        $glTypeId = $request->post('glTypeId');
        $accountId = $request->post('accountId');
        $coaDetails = '';
        $coaDetails = GlCoa::select('*')->where([
            ['gl_acc_id', '=', $accountId],
            ['gl_type_id', '=', $glTypeId],
        ])->first();
        return response()->json($coaDetails);
    }

    public function coaInfoDetails(Request $request, $accountId, $accType)
    {
        $coaDetails = DB::selectOne('select cpaacc.fas_gl_config.get_gl_coa_info(:p_gl_acc_id,:p_gl_acc_type_id) from dual', ['p_gl_acc_id' => $accountId, 'p_gl_acc_type_id' => $accType]);
        return response()->json($coaDetails);
    }

    public function getCurrentPostingPeriod(Request $request)
    {
        $periods = $this->lookupManager->findPostingPeriod($request->get("calenderId"));
        $preSelected = $request->post('preselected',null);
        $periodHtml = '<option value="">&lt;Select&gt;</option>';

        if (isset($periods)) {
            foreach ($periods as $period) {
                $periodHtml .= "<option " . ( isset($preSelected) ? (($period->posting_period_id == $preSelected) ? 'selected': '') : '' /*(($period->posting_period_status == 'O') ? 'selected' : '')*/ ). "
                                        data-currentdate=" . HelperClass::dateConvert($period->current_posting_date) . "
                                        data-postingname=" . $period->posting_period_name . "
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";

            }
        } /*else {
            $periodHtml = "<option value=''></option>";
        }*/

        return response()->json(['period' => $periodHtml]);
    }

    public function getYearEndPostingPeriod(Request $request)
    {
        $periods = $this->lookupManager->findYearEndPostingPeriod($request->get("calenderId"));
        $preSelected = $request->post('preselected',null);
        $periodHtml = '<option value="">&lt;Select&gt;</option>';

        if (isset($periods)) {
            foreach ($periods as $period) {
                $periodHtml .= "<option ". ( isset($preSelected) ? (($period->posting_period_id == $preSelected) ? 'selected': '') : '' )."
                        value='" . $period->posting_period_id . "'>" . $period->period_name .
                    "</option>";
            }
        }

        return response()->json(['period' => $periodHtml]);
    }

    public function getRegisterDetail($id)
    {
        $response = LBillRegister::where('bill_reg_id', '=', $id)->first();
        $current_account = DB::selectOne("select CPAACC.fas_gl_config.get_current_account (:p_bill_sec_id,:p_bil_reg_id) from dual", ['p_bill_sec_id' => $response->bill_sec_id, 'p_bil_reg_id' => $response->bill_reg_id]);
        $response['current_account'] = $current_account->gl_acc_id;
        return $response;
    }

    /************************** -Report methods- ******************************************/
    public function reportGlAccounts(Request $request)
    {
        return DB::select('select CPAACC.FAS_REPORT_CONTROL.get_gl_account_name(:p_search_value) from dual', ['p_search_value' => $request->get('term')]);
    }

    public function reportGlFiscalYears(Request $request)
    {
        return DB::select('select CPAACC.FAS_REPORT_CONTROL.get_financial_years() from dual');
    }

    public function reportGlPostingPeriods(Request $request)
    {
        $fromPeriods = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_posting_periods(:p_fiscal_year_id) from dual', ['p_fiscal_year_id' => $request->get("fiscal_year_id")]);
        $fromOptions = '';
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

    public function getCurrentBankAccount(Request $request)
    {
        $response = DB::selectOne('select CPAACC.FAS_GL_CONFIG.get_current_account(:p_sec_id, :p_reg_id) from dual', ['p_sec_id' => $request->get('secId'), 'p_reg_id' => $request->get('regId')]);
        $predefined = false;
        if (isset($response)) {
            $predefined = true;
        }
        return response()->json(['selected' => $response, 'predefined' => $predefined]);
    }
}

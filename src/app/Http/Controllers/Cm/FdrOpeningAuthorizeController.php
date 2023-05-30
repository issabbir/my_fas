<?php


namespace App\Http\Controllers\Cm;


use App\Contracts\LookupContract;
use App\Enums\Ap\ApFunType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FdrOpeningAuthorizeController
{
    private $lookupManager;
    private $openingInfo;
    private $transactionView;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;

    }

    public function index($filter = null)
    {
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        return view('cm.fdr-opening-authorize.list', compact('filterData','investmentStatus', 'periodTypes', 'investmentTypes', 'fiscalYear', 'department', 'billSecs'));
    }

    public function view($id,$filter,$wkmId)
    {
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);

        $this->openingInfo = DB::selectOne("SELECT FAS_CM_TRANS.get_fdr_opening_trans_view (:p_investment_trans_id ) FROM DUAL", ['p_investment_trans_id' => $id]);
        $this->transactionView = DB::selectOne("select fas_cm_trans.get_gl_transaction_view(:p_gl_trans_master_id) from dual", ['p_gl_trans_master_id' => $id]) ?? [];

        return view('cm.fdr-opening-authorize.index', ['workflowMapId'=>$wkmId,'openingInfo' => $this->openingInfo, 'transactionContents' => $this->transactionView], compact('filter','investmentStatus', 'periodTypes', 'investmentTypes', 'fiscalYear', 'department', 'billSecs'));
    }

    public function perform(Request $request)
    {
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");

        DB::beginTransaction();
        try {
            $params = [
                'i_workflow_mapping_id' => $request->post('wkMapId'),
                'i_workflow_master_id' => WorkFlowMaster::CM_FDR_OPENING_TRANSACTION,
                'i_reference_table' => WkReferenceTable::FAS_CM_FDR_INVESTMENT_TRANS,
                'i_reference_key' => WkReferenceColumn::INVESTMENT_TRANS_ID,
                'i_reference_status' => strtoupper($request->post('approveStatus')),
                'i_reference_comment' => $request->post('comment'),
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);
            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return response()->json(["status_code" => $status_code, "status_msg" => $status_message]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["status_code" => 99, "status_msg" => $e->getMessage()]);
        }

        DB::commit();
        return response()->json(["status_code" => $status_code, "status_msg" => $status_message]);
    }

    public function dataList(Request $request)
    {
        $inv_type = $request->post('investmentType', null);
        $fiscal_year = $request->post('fiscalYear', null);
        $period = $request->post('period', null);
        $status = $request->post('approvalStatus', null);
        $filteredData = Crypt::encryptString($inv_type .'#'.$fiscal_year .'#'. $period .'#'. $status);

        $opening_list = DB::select('select cpaacc.fas_cm_trans.get_fdr_opening_trans_auth_list(:p_investment_type_id,:p_fiscal_year_id,:p_posting_period_id,:p_workflow_approval_status, :p_user_id) from dual',
            ['p_investment_type_id' => $inv_type, 'p_fiscal_year_id' => $fiscal_year, 'p_posting_period_id' => $period, 'p_workflow_approval_status' => $status,'p_user_id'=>Auth::id()]);

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
            ->editColumn('action', function ($d) use($filteredData){
                return "<a href='".route('fdr-opening-authorize.view', ['id' => $d->investment_trans_id,'filter'=>$filteredData,'wkmId'=>$d->workflow_mapping_id])."' class='cursor-pointer'><i class='bx bx-show-alt'></i></a>";
            })
            ->rawColumns(['auth_status', 'action'])
            ->make(true);
    }
}

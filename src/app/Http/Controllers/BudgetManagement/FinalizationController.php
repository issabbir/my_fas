<?php
/**
 *Created by PhpStorm
 *Created at ২২/১১/২১ ৪:৫১ PM
 */

namespace App\Http\Controllers\BudgetManagement;


use App\Enums\BudgetManagement\SubmissionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinalizationController extends Controller
{
    private $lookupManager;

    public function __construct()
    {
        $this->lookupManager = new BudgetMgtLookupManager();
    }

    public function index()
    {
        $data['financialYear'] = $this->lookupManager->getCurrentFinancialYear();
        return view('budget-management.budget-finalization.index', compact('data'));
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000d", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_fiscal_year_id' => $request->post('fiscal_year'),
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            //DB::executeProcedure('CPAACC.fas_budget.fas_budget_mgt_finalize', $params);
            DB::executeProcedure('CPAACC.fas_budget.fas_budget_est_finalize', $params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
            }

            DB::commit();
            return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => "99", 'response_msg' => $e->getMessage()]);
        }
    }
}

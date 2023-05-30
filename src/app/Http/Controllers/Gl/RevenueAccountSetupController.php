<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ১:১৪ PM
 */

namespace App\Http\Controllers\Gl;


use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Gl\GlRevenueAccParams;
use App\Entities\Gl\LGlRevenueAccType;
use App\Enums\ProActionType;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Illuminate\Support\Facades\DB;

class RevenueAccountSetupController extends Controller
{
    private $revenueAccount;
    private $glCoa;
    private $glCoaParam;
    private $revenueAcc;
    private $flashMessage;

    public function __construct()
    {
        $this->revenueAccount = new LGlRevenueAccType();
        $this->glCoa = new GlCoa();
        $this->glCoaParam = new GlCoaParams();
        $this->revenueAcc = new GlRevenueAccParams();
        $this->flashMessage = new FlashMessageManager();
    }

    public function index()
    {
        $accountTypes = $this->revenueAccount->get();
        $coaParams = $this->glCoaParam->get();
        $revenueAcc = $this->revenueAcc->with('revenue_type', 'gl_acc')->get();
        return view("gl.revenue-account-setup.index", compact('accountTypes', 'coaParams', 'revenueAcc'));
    }

    public function store(Request $request, $id = null, $actionType = null)
    {
        DB::beginTransaction();

        $key = !is_null($id) ? $id : '';
        $actionType = is_null($actionType) ? ProActionType::INSERT : $actionType;
        $status_code = sprintf("%4000s", "");
        $status_message = sprintf("%4000s", "");

        $params = [
            'p_action_type' => $actionType,
            /*'p_revenue_acc_param_id' => [
                'value' => &$key,
                'type' => \PDO::PARAM_INPUT_OUTPUT,
                'length' => 255
            ],*/
            'p_revenue_acc_type_id' => $request->post('account_type'),
            'p_gl_acc_id' => $request->post('account_id'),
            'p_user_id' => auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        try {
            DB::executeProcedure('fas_gl_config.create_reveue_acc_param', $params);

            $response = $this->flashMessage->getMessage($params);

            if ($status_code == "99") {
                DB::rollBack();
                if (is_null($id)) {
                    return redirect()->back()->with($response['class'], $status_message)->withInput();
                } else {
                    return $params;
                }
            } else {
                DB::commit();
                if (is_null($id)) {
                    return redirect()->back()->with($response['class'], $status_message);
                } else {
                    return $params;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (is_null($id)) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            } else {
                return $params;
            }
        }
    }

    public function edit($id)
    {
        $data['insertedData'] = $this->revenueAcc->where('revenue_acc_type_id', $id)->first();
        //dd($data);
        $accountTypes = $this->revenueAccount->get();
        $coaParams = $this->glCoaParam->get();
        $revenueAcc = $this->revenueAcc->with('revenue_type', 'gl_acc')->get();
        return view("gl.revenue-account-setup.index", compact('data', 'accountTypes', 'coaParams', 'revenueAcc'));
    }

    public function update(Request $request, $id)
    {
        $response = $this->store($request, $id, ProActionType::UPDATE);

        if ($response['o_status_code'] == '1') {
            return redirect(route('revenue-account-setup.index'))->with('success', $response['o_status_message']);
        } else {
            return redirect()->back()->with('error', $response['o_status_message']);
        }
    }

    public function delete($id = null)
    {
        DB::beginTransaction();

        $status_code = sprintf("%4000s", "");
        $status_message = sprintf("%4000s", "");

        $params = [
            'p_action_type' => ProActionType::DELETE,
            'p_revenue_acc_type_id' => $id,
            'p_gl_acc_id' => '',
            'p_user_id' => auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        try {
            DB::executeProcedure('fas_gl_config.create_reveue_acc_param', $params);
            $response = $this->flashMessage->getMessage($params);

            if ($status_code == "99") {
                DB::rollBack();
                return redirect()->back()->with($response['class'], $status_message)->withInput();
            } else {
                DB::commit();
                return redirect()->back()->with($response['class'], $status_message);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}

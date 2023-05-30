<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ১১:০৩ AM
 */

namespace App\Http\Controllers\Gl;


use App\Entities\Gl\GlCashAccParams;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Gl\LGlCashAccType;
use App\Enums\ProActionType;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class CashAccountSetupController extends Controller
{
    private $cashAccount;
    private $glCoaParam;
    private $glCoa;
    private $cashAcc;
    private $flashMessage;


    public function __construct()
    {
        $this->cashAccount = new LGlCashAccType();
        $this->glCoaParam = new GlCoaParams();
        $this->glCoa = new GlCoa();
        $this->cashAcc = new GlCashAccParams();
        $this->flashMessage = new FlashMessageManager();
    }

    public function index()
    {
        $accountTypes = $this->cashAccount->get();
        $coaParams = $this->glCoaParam->get();
        $cashAcc = $this->cashAcc->with('cash_type', 'gl_acc')->get();
        return view("gl.cash-account-setup.index", compact('accountTypes', 'coaParams', 'cashAcc'));
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
            'p_cash_acc_param_id' => [
                'value' => &$key,
                'type' => \PDO::PARAM_INPUT_OUTPUT,
                'length' => 255
            ],
            'p_cash_acc_type_id' => $request->post('account_type'),
            'p_gl_acc_id' => $request->post('account_id'),
            'p_user_id' => auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];
        try {
            DB::executeProcedure('fas_gl_config.create_cash_acc_param', $params);
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
        $data['insertedData'] = $this->cashAcc->where('cash_acc_param_id', $id)->first();
        //dd($data);
        $accountTypes = $this->cashAccount->get();
        $coaParams = $this->glCoaParam->get();
        $cashAcc = $this->cashAcc->with('cash_type', 'gl_acc')->get();
        return view("gl.cash-account-setup.index", compact('data', 'accountTypes', 'coaParams', 'cashAcc'));
    }

    public function update(Request $request, $id)
    {
        $response = $this->store($request, $id, ProActionType::UPDATE);

        if ($response['o_status_code'] == '1') {
            return redirect(route('cash-account-setup.index'))->with('success', $response['o_status_message']);
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
            'p_cash_acc_param_id' => [
                'value' => &$id,
                'type' => \PDO::PARAM_INPUT_OUTPUT,
                'length' => 255
            ],
            'p_cash_acc_type_id' => '',
            'p_gl_acc_id' => '',
            'p_user_id' => auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        try {
            DB::executeProcedure('fas_gl_config.create_cash_acc_param', $params);
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

    /*public function bankAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');
        $sql = $this->glCoa->where('gl_type_id', '=', $glType);

        if (isset($accNameCode)) {
            $sql->where(function ($q) use ($accNameCode){
                $q->Where(DB::raw('upper(gl_acc_name)'), 'like', '%' . strtoupper($accNameCode) . '%')
                    ->orWhere('gl_acc_id', 'like', '%' . $accNameCode . '%');
            });
        }
        $bankAccounts = $sql->get();

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
                return "<button class='btn btn-dark' onclick='getAccountDetail($data->gl_type_id,$data->gl_acc_id)' >Select</button>";
            })
            ->make(true);

    }*/
}

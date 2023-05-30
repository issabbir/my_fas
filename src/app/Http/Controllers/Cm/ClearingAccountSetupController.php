<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\Cm\CmLookupContract;
use App\Contracts\LookupContract;
use App\Entities\Cm\CmClearingParams;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Cm\CmLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClearingAccountSetupController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var CmLookupManager */
    private $cmLookupManager;

    public function __construct(LookupContract $lookupManager, CmLookupContract $cmLookupManager) {
        $this->lookupManager = $lookupManager;
        $this->cmLookupManager = $cmLookupManager;
    }

    public function index()
    {
        return view('cm.clearing-account-setup.index', [
            'bankAccList' => $this->cmLookupManager->getBankAcc(),
        ]);
    }

    public function dataTableList()
    {
        $queryResult = CmClearingParams::with(['coa_info'])->get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-sm btn-info"  href="' . route('clearing-account-setup.edit', [$query->bank_account_id]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('clearing-account-setup.delete', [$query->bank_account_id]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $clgAccInfo = CmClearingParams::with(['clg_outward', 'clg_inward'])->where('bank_account_id', $id)->first();

        return view('cm.clearing-account-setup.index', [
            'clgAccInfo' => $clgAccInfo,
            'bankAccList' => $this->cmLookupManager->getBankAcc(),
        ]);
    }

    public function store(Request $request) {
        $response = $this->clearing_account_setup_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('clearing-account-setup.index');
    }

    public function update(Request $request, $id) {
        $response = $this->clearing_account_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('clearing-account-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->clearing_account_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('clearing-account-setup.index');
    }

    private function clearing_account_setup_api_ins(Request $request)
    {
        $postData = $request->post();
        //dd($postData);

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_bank_account_id' => $postData['bank_acc_id'],
                'p_outward_clearing_acc_id' => $postData['clearing_outward_gl_id'],
                'p_inward_clearing_acc_id' => $postData['clearing_inward_gl_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_clearing_params', $params);
        }
        catch (\Exception $e) {
            //dd($e);
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function clearing_account_setup_api_upd($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_bank_account_id' => $id,
                'p_outward_clearing_acc_id' => $postData['clearing_outward_gl_id'],
                'p_inward_clearing_acc_id' => $postData['clearing_inward_gl_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_clearing_params', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function clearing_account_setup_api_del($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_bank_account_id' => $id,
                'p_outward_clearing_acc_id' => null,
                'p_inward_clearing_acc_id' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_clearing_params', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

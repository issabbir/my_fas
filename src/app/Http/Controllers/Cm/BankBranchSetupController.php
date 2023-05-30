<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\LookupContract;
use App\Entities\Cm\CmBankBranch;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankBranchSetupController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;


    public function __construct(LookupContract $lookupManager) {
        $this->lookupManager = $lookupManager;
    }

    public function index()
    {
        return view('cm.bank-branch-setup.index');
    }

    public function dataTableList()
    {
        $queryResult = CmBankBranch::with(['cm_bank_info', 'cm_bank_district'])->get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-sm btn-info"  href="' . route('bank-branch-setup.edit', [$query->branch_code]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('bank-branch-setup.delete', [$query->branch_code]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $bankBranchInfo = CmBankBranch::where('branch_code', $id)->first();

        return view('cm.bank-branch-setup.index', [
            'bankBranchInfo' => $bankBranchInfo,
        ]);
    }

    public function store(Request $request) {
        $response = $this->bank_branch_setup_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-branch-setup.index');
    }

    public function update(Request $request, $id) {
        $response = $this->bank_branch_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-branch-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->bank_branch_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-branch-setup.index');
    }

    private function bank_branch_setup_api_ins(Request $request)
    {
        $postData = $request->post();
        //dd($postData);

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $branch_code = null;

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_branch_code' => $branch_code,
                'p_branch_name' => $postData['branch_name'],
                'p_bank_code' => $postData['bank_id'],
                'p_district_code' => $postData['bank_district_id'],
                //'p_branch_sl' => $postData['branch_sl_code'],
                'p_routing_no' => $postData['routing_number'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_branch', $params);
        }
        catch (\Exception $e) {
            //dd($e);
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function bank_branch_setup_api_upd($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_branch_code' => $id,
                'p_branch_name' => $postData['branch_name'],
                'p_bank_code' => $postData['bank_id'],
                'p_district_code' => $postData['bank_district_id'],
                //'p_branch_sl' => $postData['branch_sl_code'],
                'p_routing_no' => $postData['routing_number'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_branch', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function bank_branch_setup_api_del($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_branch_code' => $id,
                'p_branch_name' => null,
                'p_bank_code' => null,
                'p_district_code' => null,
                //'p_branch_sl' => null,
                'p_routing_no' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_branch', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

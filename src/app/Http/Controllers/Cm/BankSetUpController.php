<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\LookupContract;
use App\Entities\Cm\CmBankInfo;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankSetUpController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    public function __construct(LookupContract $lookupManager) {
        $this->lookupManager = $lookupManager;
    }

    public function index()
    {
        return view('cm.bank-setup.index');
    }

    public function dataTableList()
    {
        $queryResult = CmBankInfo::get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-sm btn-info"  href="' . route('bank-setup.edit', [$query->bank_code]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('bank-setup.delete', [$query->bank_code]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $cmBankInfo = CmBankInfo::where('bank_code', $id)->first();

        return view('cm.bank-setup.index', [
            'cmBankInfo' => $cmBankInfo,
        ]);
    }

    public function store(Request $request) {
        $response = $this->bank_setup_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-setup.index');
    }

    public function update(Request $request, $id) {
        $response = $this->bank_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->bank_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('bank-setup.index');
    }

    private function bank_setup_api_ins(Request $request)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_bank_id' => $postData['bank_code'],
                'p_bank_name' => $postData['bank_name'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_info', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function bank_setup_api_upd($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_bank_id' => $postData['bank_code'],
                'p_bank_name' => $postData['bank_name'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_info', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function bank_setup_api_del($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_bank_id' => $id,
                'p_bank_name' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_cm_config.create_bank_info', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

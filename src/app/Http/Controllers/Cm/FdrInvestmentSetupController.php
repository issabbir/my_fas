<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\Cm\CmLookupContract;
use App\Contracts\Common\CommonContract;
use App\Contracts\LookupContract;
use App\Entities\Common\LFdrInvestmentUserMap;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Cm\CmLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FdrInvestmentSetupController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var CommonManager */
    private $commonManager;

    /** @var CmLookupManager */
    private $cmLookupManager;

    public function __construct(LookupContract $lookupManager, CommonContract $commonManager, CmLookupContract $cmLookupManager) {
        $this->lookupManager = $lookupManager;
        $this->commonManager = $commonManager;
        $this->cmLookupManager = $cmLookupManager;
    }

    public function index()
    {
        return view('cm.fdr-investment-setup.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'invUserList' => $this->commonManager->findWorkFlowUser(null)
        ]);
    }

    public function dataTableList()
    {
        $queryResult = DB::select("select cpaacc.fas_cm_config.get_fdr_investment_user_list from dual");

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-sm btn-info"  href="' . route('fdr-investment-setup.edit', [$query->investment_user_map_id]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('fdr-investment-setup.delete', [$query->investment_user_map_id]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $fdrInvSetupInfo = LFdrInvestmentUserMap::where('investment_user_map_id', $id)->first();

        return view('cm.fdr-investment-setup.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'invUserList' => $this->commonManager->findWorkFlowUser(null),
            'fdrInvSetupInfo' => $fdrInvSetupInfo,
        ]);
    }

    public function store(Request $request) {
        $response = $this->fdr_investment_setup_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-investment-setup.index');
    }

    public function update(Request $request, $id) {
        $response = $this->fdr_investment_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-investment-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->fdr_investment_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-investment-setup.index');
    }

    private function fdr_investment_setup_api_ins(Request $request)
    {
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $inv_user_map_id = null;

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_investment_user_map_id' => $inv_user_map_id,
                'p_investment_user_id' => $postData['inv_user_id'],
                'p_investment_type_id' => $postData['inv_type_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_fdr_investment_user', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function fdr_investment_setup_api_upd($request, $id)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_investment_user_map_id' => $id,
                'p_investment_user_id' => $postData['inv_user_id'],
                'p_investment_type_id' => $postData['inv_type_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_fdr_investment_user', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function fdr_investment_setup_api_del($request, $id)
    {
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_investment_user_map_id' => $id,
                'p_investment_user_id' => null,
                'p_investment_type_id' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_fdr_investment_user', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

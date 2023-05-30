<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\Cm\CmLookupContract;
use App\Contracts\Common\CommonContract;
use App\Contracts\LookupContract;
use App\Entities\Common\LFdrInvestmentUserMap;
use App\Entities\Gl\SectionUserMap;
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

class SectionSetupController extends Controller
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
        return view('gl.user-section-setup.index', [
            'billSection' => $this->lookupManager->findLBillSec(),
            'secUserList' => $this->commonManager->findWorkFlowUser(null)
        ]);
    }

    public function dataTableList()
    {

        $queryResult = DB::select("select cpaacc.fas_config.get_bill_section_user_list from dual");

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a class="btn btn-sm btn-info"  href="' . route('user-section-setup.edit', [$query->section_user_map_id]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('user-section-setup.delete', [$query->section_user_map_id]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $userSectionSetupInfo = SectionUserMap::where('section_user_map_id', $id)->first();

        return view('gl.user-section-setup.index', [
            'billSection' => $this->lookupManager->findLBillSec(),
            'secUserList' => $this->commonManager->findWorkFlowUser(null),
            'sectionSetupInfo' => $userSectionSetupInfo,
        ]);
    }

    public function store(Request $request) {

        $response = $this->user_section_setup_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('user-section-setup.index');
    }

    public function update(Request $request, $id) {

        $response = $this->user_section_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('user-section-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->user_section_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('user-section-setup.index');
    }

    private function user_section_setup_api_ins(Request $request)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $inv_user_map_id = null;

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_section_user_map_id' => $inv_user_map_id,
                'p_section_user_id' => $postData['sec_user_id'],
                'p_bill_sec_id' => $postData['bill_sec_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_config.create_bill_section_user_map', $params);

        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function user_section_setup_api_upd($request, $id)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_investment_user_map_id' => $id,
                'p_section_user_id' => $postData['sec_user_id'],
                'p_bill_sec_id' => $postData['bill_sec_id'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('cpaacc.fas_config.create_bill_section_user_map', $params);
// dd($params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function user_section_setup_api_del($request, $id)
    {
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_investment_user_map_id' => $id,
                'p_section_user_id' => null,
                'p_bill_sec_id' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('cpaacc.fas_config.create_bill_section_user_map', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

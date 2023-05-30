<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\LookupContract;
use App\Entities\Gl\GlSubsidiaryParams;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartySubLedgerController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    public function __construct(LookupContract $lookupManager) {
        $this->lookupManager = $lookupManager;
    }

    public function index()
    {
        $subsidiaryParamsList = GlSubsidiaryParams::all();

        return view('gl.party-sub-ledger-setup.index', [
            'coaParams' => $this->lookupManager->findGlCoaParams(),
            'subLedgerType' => $this->lookupManager->findLGlSubsidiaryType(),
            'subModuleType' => $this->lookupManager->findLGlIntegrationModules(),
            'subsidiaryParamsList' => $subsidiaryParamsList,
        ]);
    }


    public function edit(Request $request, $id)
    {
        $viewModeYN = $request->get('view', NULL); //Add Part Pavel:06-04-22
        $subsidiaryParam = GlSubsidiaryParams::with(['coa_info'])->where('gl_subsidiary_id', $id)->first();
        $subsidiaryParamsList = GlSubsidiaryParams::all();

        return view('gl.party-sub-ledger-setup.index', [
            'coaParams' => $this->lookupManager->findGlCoaParams(),
            'subLedgerType' => $this->lookupManager->findLGlSubsidiaryType(),
            'subModuleType' => $this->lookupManager->findLGlIntegrationModules(),
            'subsidiaryParamsList' => $subsidiaryParamsList,
            'subsidiaryParam' => $subsidiaryParam,
            'viewModeYN' => $viewModeYN,
        ]);
    }


    public function store(Request $request) {
        $response = $this->party_sub_ledger_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('party-sub-ledger-setup.index');
    }

    public function update(Request $request, $id) {
        $response = $this->party_sub_ledger_api_upd($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('party-sub-ledger-setup.index');
    }

    public function delete(Request $request, $id) {
        $response = $this->party_sub_ledger_api_del($request, $id);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('party-sub-ledger-setup.index');
    }

    private function party_sub_ledger_api_ins(Request $request)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $gl_subsidiary_id = null;

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_gl_subsidiary_id' => [
                    'value' => &$gl_subsidiary_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_gl_subsidiary_name' => $postData['party_sub_ledger_name'],
                'p_gl_subsidiary_type_id' => $postData['party_sub_ledger_type'],
                'p_gl_subsidiary_acc_id' => $postData['account_id'],
                'p_module_id' => $postData['sub_module_type'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('CPAACC.fas_gl_config.create_gl_subsidiary_params', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function party_sub_ledger_api_upd($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $gl_subsidiary_id = $id;

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_gl_subsidiary_id' => [
                    'value' => &$gl_subsidiary_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_gl_subsidiary_name' => $postData['party_sub_ledger_name'],
                'p_gl_subsidiary_type_id' => $postData['party_sub_ledger_type'],
                'p_gl_subsidiary_acc_id' => $postData['account_id'],
                'p_module_id' => $postData['sub_module_type'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('CPAACC.fas_gl_config.create_gl_subsidiary_params', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function party_sub_ledger_api_del($request, $id)
    {
        $postData = $request->post();
        //dd($postData);
        try {
            $status_code = sprintf("%4000s","");
            $status_message = sprintf("%4000s","");
            $gl_subsidiary_id = $id;

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_gl_subsidiary_id' => [
                    'value' => &$gl_subsidiary_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_gl_subsidiary_name' => null,
                'p_gl_subsidiary_type_id' => null,
                'p_gl_subsidiary_acc_id' => null,
                'p_module_id' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('CPAACC.fas_gl_config.create_gl_subsidiary_params', $params);
        }
        catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }



}

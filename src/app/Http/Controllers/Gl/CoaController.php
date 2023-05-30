<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\LookupContract;
use App\Entities\Budget\BudgetHeadLines;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlTransDetail;
use App\Enums\ProActionType;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoaController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;
    }

    public function index()
    {
        $gl_chart_list = DB::select("select CPAACC.fas_gl_config.get_gl_tree_chart() from dual");

        return view('gl.chart-of-account.index', [
            'gl_chart_list' => $gl_chart_list,
            //'glTransDtl' => $glTransDtl,
        ]);
    }

    public function coaSetup()
    {
        //dd($this->lookupManager->getDeptCostCenter());
        $gl_chart_list = DB::select("select CPAACC.fas_gl_config.get_gl_tree_chart() from dual");

        return view('gl.chart-of-account.setup', [
            'accTypeList' => $this->lookupManager->findGlCoaParams(),
            'lCurList' => $this->lookupManager->findLCurrency(),
            'date' => $this->lookupManager->findCurDate(),
            //'budgetHeadList' => $this->lookupManager->findBudgetHead(),
            'dptCostCenterList' => $this->lookupManager->getDeptCostCenter(),
            'gl_chart_list' => $gl_chart_list
        ]);
    }

    public function edit(Request $request, $id)
    {
        $coaInfo = GlCoa::with(['acc_type', 'l_curr', 'budget_head', 'coa_parent_info'])->where('gl_acc_id', $id)->first();

        return view('gl.chart-of-account.setup', [
            'accTypeList' => $this->lookupManager->findGlCoaParams(),
            'lCurList' => $this->lookupManager->findLCurrency(),
            'date' => $this->lookupManager->findCurDate(),
            'coaInfo' => $coaInfo,
            //'budgetHeadList' => $this->lookupManager->findBudgetHead(),
            'dptCostCenterList' => $this->lookupManager->getDeptCostCenter(),
        ]);
    }

    public function view(Request $request, $id)
    {
        $coaInfo = GlCoa::with(['budget_head', 'coa_parent_info','cost_center_dep'])->where('gl_acc_id', $id)->first();
        return view('gl.chart-of-account.view', [
            'accTypeList' => $this->lookupManager->findGlCoaParams(),
            'lCurList' => $this->lookupManager->findLCurrency(),
            'date' => $this->lookupManager->findCurDate(),
            'coaInfo' => $coaInfo,
            //'budgetHeadList' => $this->lookupManager->findBudgetHead(),
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->coa_entry_api_ins($request);

        $message = $response['o_status_message'];

        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('coa.coa-setup-index');
    }

    public function update(Request $request, $id)
    {
        $response = $this->coa_entry_api_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('coa.coa-setup-index');
    }

    private function coa_entry_api_ins(Request $request)
    {
        $postData = $request->post();
        $opening_date = isset($postData['opening_date']) ? HelperClass::dateFormatForDB($postData['opening_date']) : '';
        $acc_posting = isset($postData['acc_posting']) && ($postData['acc_posting'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $budget_head_control = isset($postData['budget_head_control']) && ($postData['budget_head_control'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $budget_head_line_id = isset($postData['budget_head_id']) ? $postData['budget_head_id'] : '';
        $allow_dept_cost_center_yn = isset($postData['allow_dept_cost_center_cot']) && ($postData['allow_dept_cost_center_cot'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $dept_cost_center_id = isset($postData['dept_cost_center_id']) ? $postData['dept_cost_center_id'] : '';

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_gl_acc_id' => NULL,
                'p_gl_acc_name' => $postData['acc_name'],
                'p_gl_acc_code' => isset ($postData['acc_code_legacy']) ? $postData['acc_code_legacy'] : NULL,
                'p_gl_type_id' => $postData['acc_type'],
                'p_gl_parent_id' => $postData['parent_acc_code'],
                'p_currency_code' => $postData['currency'],
                'p_postable_yn' => $acc_posting,
                'p_cost_center_dept_control_yn' => $allow_dept_cost_center_yn,
                'p_cost_center_dept_id' => $dept_cost_center_id,
                'p_budget_control_yn' => $budget_head_control,
                //'p_budget_head_line_id' => $budget_head_line_id,
                'p_budget_head_line_id' => $budget_head_line_id,
                'p_opening_date' => $opening_date,
                'p_inactive_yn' => YesNoFlag::NO,
                'p_inactive_date' => NULL,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.fas_gl_config.create_update_coa', $params);
        } catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function coa_entry_api_upd($request, $id)
    {
        $postData = $request->post();
        $opening_date = isset($postData['opening_date']) ? HelperClass::dateFormatForDB($postData['opening_date']) : '';
        $acc_posting = isset($postData['acc_posting']) && ($postData['acc_posting'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $budget_head_control = isset($postData['budget_head_control']) && ($postData['budget_head_control'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $budget_head_line_id = isset($postData['budget_head_id']) ? $postData['budget_head_id'] : '';
        $allow_dept_cost_center_yn = isset($postData['allow_dept_cost_center_cot']) && ($postData['allow_dept_cost_center_cot'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $dept_cost_center_id = isset($postData['dept_cost_center_id']) ? $postData['dept_cost_center_id'] : '';
        $acc_inactive_date = isset($postData['acc_inactive_date']) ? HelperClass::dateFormatForDB($postData['acc_inactive_date']) : '';
        $acc_inactive = isset($postData['acc_inactive']) && ($postData['acc_inactive'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_gl_acc_id' => $id,
                'p_gl_acc_name' => $postData['acc_name'],
                'p_gl_acc_code' => isset ($postData['acc_code_legacy']) ? $postData['acc_code_legacy'] : NULL,
                'p_gl_type_id' => $postData['acc_type'],
                'p_gl_parent_id' => $postData['parent_acc_code'],
                'p_currency_code' => $postData['currency'],
                'p_postable_yn' => $acc_posting,
                'p_cost_center_dept_control_yn' => $allow_dept_cost_center_yn,
                'p_cost_center_dept_id' => $dept_cost_center_id,
                'p_budget_control_yn' => $budget_head_control,
                'p_budget_head_line_id' => $budget_head_line_id,
                'p_opening_date' => $opening_date,
                'p_inactive_yn' => $acc_inactive,
                'p_inactive_date' => $acc_inactive_date,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];


            DB::executeProcedure('CPAACC.fas_gl_config.create_update_coa', $params);
        } catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function accTypeWiseCoa(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        if (empty($terms['acc_type_id'])) {
            $queryResult = [];
        } else {
            /**
             * COA ADD (problem: parent code must be non-postable). REF# email
             * Logic added:04-04-2022
             * **/
            //$queryResult = GlCoa::where(['gl_type_id'=> $terms['acc_type_id'], 'postable_yn' => 'N'])->get();

            /**
             * Remove table and add tree view. REF# Yousuf Imam
             * Logic added:04-04-2022
             * **/
            $queryResult = DB::select("select CPAACC.fas_gl_config.get_gl_parent_accounts(:p_gl_type_id) from dual", ['p_gl_type_id' => $terms['acc_type_id']]);
            //$queryResult = DB::select("select CPAACC.fas_gl_config.get_gl_tree_chart() from dual");
        }
       /* $html = '';
        foreach ($queryResult as $option) {
            $html .= '<li data-tree-branch="'. $option->node_path .'" class="text-dark lirow">
                        <span data-tree-click="'. $option->node_path .'" class="text-primary">
                           <small>&nbsp;'. $option->gl_acc_name .'</small>
                         </span>
                        <a target="_blank" href="{{route(\'coa.coa-setup-edit\', [$option->gl_acc_id])}}" style="float: right"><i class="bx bx-edit cursor-pointer"></i></a>
                        <a target="_blank" href="{{route(\'coa.coa-setup-view\', [$option->gl_acc_id])}}" style="float: right"><i class="bx bx-show cursor-pointer"></i></a>
                    </li>';
        }*/

        /**
         * Remove table and add tree view. REF# Yousuf Imam
         * Logic added:04-04-2022
         * **/
        /*return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm gl-coa"  id="' . $query->gl_acc_id . '">Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);*/
        $html = view('gl.chart-of-account.coa_tree',['gl_chart_list'=>$queryResult])->render();
        return response()->json($html);
    }

    public function budgetHeadWiseList(Request $request)
    {
        //$terms = $request->post();
        $queryResult = [];

        /*if (empty($terms['budget_grp_id'])) {
            $queryResult = [];
        } else {
            $queryResult = BudgetHeadLines::where('budget_group_id', $terms['budget_grp_id'])->get();
        }*/
        /** Execute Oracle Function **/
        $queryResult = DB::select("select CPAACC.fas_gl_config.get_budget_gl_head_list from dual");
        //dd($queryResult);

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                if ($query->postable_yn == YesNoFlag::NO) {
                    return 'N/A';
                } else {
                    return '<button class="btn btn-primary btn-sm budget-heads-data"  id="' . $query->budget_head_id . '">Select</button>';
                }
            })
            /*->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm budget-head-line"  id="' . $query->budget_head_line_id . '">Select</button>';
            })*/
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

    /*** Previous Data populated old budget schema ***/
    /*public function budgetHeadWiseLine(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        if (empty($terms['budget_grp_id'])) {
            $queryResult = [];
        } else {
            $queryResult = BudgetHeadLines::where('budget_group_id', $terms['budget_grp_id'])->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm budget-head-line"  id="' . $query->budget_head_line_id . '">Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }*/
    /*** Previous Data populated old budget schema ***/

    public function searchAccNamesCodes(Request $request)
    {
        $terms = $request->post();
        $searchTerm = ($terms['acc_name_code']);
        $queryResult = [];

        if (empty($terms['acc_name_code'])) {
            $queryResult = [];
        } else {
            $queryResult = GlCoa::with(['acc_type'])
                ->where(function ($query) use ($searchTerm) {
                    $query->where(DB::raw('LOWER(gl_acc_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                        ->orWhere('gl_acc_code', 'like', '' . trim($searchTerm) . '%');
                })
                /**
                 * COA SEARCH (problem: Search Result to be ordered by Account ID, No back button needed). REF# email
                 * Logic added:04-04-2022
                 * **/
                ->orderBy('gl_acc_id', 'asc')
                ->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                /*//TODO:: 01/08/2022 Yusuf Imam remove this logic. open the edit and view for all case.
                $glTransDtl = GlTransDetail::where('gl_acc_id', $query->gl_acc_id)->get();
                if ($glTransDtl->isEmpty()) {
                    return '<a href="' . route('coa.coa-setup-edit', [$query->gl_acc_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                } else {
                    return '<a href="' . route('coa.coa-setup-view', [$query->gl_acc_id]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
                }*/
                return '<a href="' . route('coa.coa-setup-view', [$query->gl_acc_id]) . '"><i class="bx bx-show cursor-pointer"></i></a>|<a href="' . route('coa.coa-setup-edit', [$query->gl_acc_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';

            })

            //->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

}

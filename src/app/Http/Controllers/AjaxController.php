<?php


namespace App\Http\Controllers;

use App\Contracts\LookupContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\LGlRevenueAccType;
use App\Entities\Security\SecUserRoles;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Matrix\Exception;

class AjaxController extends Controller
{

    /** @var EmployeeManager */
    private $employeeManager;
    protected $coa;
    protected $revenueAccount;
    protected $lookupManager;

    public function __construct(EmployeeContract $employeeManager, LookupContract $lookupManager)
    {
        $this->employeeManager = $employeeManager;
        $this->coa = new GlCoa();
        $this->revenueAccount = new LGlRevenueAccType();
        $this->lookupManager = $lookupManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);
        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->employeeManager->findEmployeeInformation($empId);
    }

    public function coaAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');
        $jsSelector = $request->post('selector');

        /*$sql = $this->coa->where('gl_type_id', '=', $glType);

        if (isset($accNameCode)) {
            $sql->where(function ($q) use ($accNameCode) {
                $q->Where(DB::raw('upper(gl_acc_name)'), 'like', '%' . strtoupper($accNameCode) . '%')
                    ->orWhere('gl_acc_id', 'like', '%' . $accNameCode . '%');
            });
        }*/

        $glCoaInfo = $this->coa->where(
            [
                ['gl_type_id', '=', $glType],
                ['postable_yn', '=', YesNoFlag::YES]
            ]
        )->where(function ($query) use ($accNameCode) {
            $query->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%' . trim($accNameCode) . '%'))
                ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                ->orWhere('fas_gl_coa.gl_acc_code', '=', trim($accNameCode));
        })->orderBy('gl_acc_id','asc')->get();

        //$bankAccounts = $sql->where('postable_yn', '=', 'Y')->get();

        return datatables()->of($glCoaInfo)
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
            ->editColumn('action', function ($data) use ($jsSelector) {
                return "<button class='btn btn-sm btn-primary' onclick='getAccountDetail($data->gl_type_id,$data->gl_acc_id, \"$jsSelector\")'>Select</button>";
            })
            ->make(true);
    }

    public function coaDetail(Request $request)
    {
        $glTypeId = $request->post('accountTypeId');
        $accountId = $request->post('accountId');

        $account = $this->revenueAccount->with(['coa' => function ($q) use ($accountId) {
            $q->where('gl_acc_id', '=', $accountId);
        }])->where('gl_type_id', '=', $glTypeId)->first();
        return response()->json($account);
    }

    public function functionTypesOfAmodule(Request $request)
    {
        $moduleId = $request->get('moduleId');

        $functions = LGlIntegrationFunctions::where('module_id', '=', $moduleId)
            ->where("function_parent_id", "!=", null)
            ->where('active_yn','=','Y')
            ->orderBy('function_id', 'asc')
            ->get();

        $html = "<option value=''>&lt;Select&gt;</option>";
        foreach ($functions as $function) {
            $html .= "<option value='" . $function->function_id . "'>$function->function_name</option>";
        }

        return response()->json($html);
    }

    public function billSectionsOnAFunction(Request $request)
    {
        $functionId = $request->get('functionId');

        $sections = $this->lookupManager->getBillSections($functionId);

        $html = "<option value=''>&lt;Select&gt;</option>";
        foreach ($sections as $section) {
            $html .= "<option value='" . $section->bill_sec_id . "'>$section->bill_sec_name</option>";
        }

        return response()->json($html);
    }

    public function oldPeriodFromTo(Request $request)
    {
        $periods_asc = $this->lookupManager->getOldPeriods($request->yearId, "ASC");
        $periods_desc = $this->lookupManager->getOldPeriods($request->yearId, "DESC");

        $options_asc = "<option value=''>&lt;Select&gt;</option>";
        $options_desc = "<option value=''>&lt;Select&gt;</option>";
        foreach ($periods_asc as $period) {
            $options_asc .= "<option data-periodfrom='".$period->posting_period_id."' value='".$period->posting_period_beg_date."'>".$period->posting_period_name."</option>";
        }

        foreach ($periods_desc as $period) {
            $options_desc .= "<option data-periodto='".$period->posting_period_id."' value='".$period->posting_period_end_date."'>".$period->posting_period_name."</option>";
        }

        return response()->json(['period_from'=>$options_asc,'period_to'=>$options_desc]);
    }
}

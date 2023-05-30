<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১০:৫০ AM
 */

namespace App\Http\Controllers\BudgetMonitoring;


use App\Entities\Ap\FasApVendors;
use App\Entities\BudgetMonitoring\FasBudgetBookingMaster;
use App\Entities\Common\LBillRegister;
use App\Enums\ApprovalStatus;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetMonitoring\BudgetMonitoringManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

class AjaxController extends Controller
{
    private $budgetMonitoringManager;
    protected $lookupManager;
    protected $budgetMonitoringLookupManager;

    public function __construct(LookupManager $lookupManager, FlashMessageManager $flashMessageManager, BudgetMonitoringManager $budgetMonitoringManager, BudgetMonitoringLookupManager $budgetMonitoringLookupManager)
    {
        //$this->budgetMonitoringManager = new BudgetMonitoringManager();
        $this->lookupManager = $lookupManager;
        $this->budgetMonitoringLookupManager = $budgetMonitoringLookupManager;
        $this->budgetMonitoringManager = $budgetMonitoringManager;
    }

    public function budgetDetailInfo(Request $request)
    {
        $budget_id = $request->get('budget_id');
        $department = $request->post('department');
        $calendar = $request->post('calendar');
        $data = $this->budgetMonitoringManager->getBudgetHeadDetailInfo($calendar, $department, $budget_id);
        /*$data = [
            "budget_head_id"=>"123456789",
            "budget_head_name"=>"Test",
            "budget_sub_category"=>"Test",
            "budget_category"=>"Test",
            "budget_type"=>"Test",
            "ministry_approved"=>"10000",
            "utilized_amount"=>"10000",
            "balance_amount"=>"10000",
            ];*/
        return response()->json(['data' => $data]);
    }

    public function budgetHeadDatalist(Request $request)
    {
        $department = $request->post('department');
        $calendar = $request->post('calendar');
        if (isset($calendar) && isset($department)) {
            $data = $this->budgetMonitoringManager->getBudgetHeadListOfDept($calendar, $department);
        } else {
            $data = [];
        }

        return DataTables()->of($data)
            ->editColumn('budget_head_id', function ($data) {
                return $data->budget_head_id;
            })
            ->editColumn('budget_head_name', function ($data) {
                return $data->budget_head_name;
            })
            ->editColumn('sub_category', function ($data) {
                return $data->sub_category_name;
            })
            ->editColumn('category_name', function ($data) {
                return $data->category_name;
            })
            ->editColumn('budget_type', function ($data) {
                return $data->budget_type_name;
            })
            ->editColumn('balance', function ($data) {
                return $data->budget_balance_amt;
            })
            ->editColumn('action', function ($data) use ($department, $calendar) {
                return '<a href="#" data-budget="' . $data->budget_head_id . '" data-department="' . $department . '" data-calendar="' . $calendar . '" class="budgetSelect btn btn-sm btn-primary">Select</a>';
            })
            ->make(true);
    }

    public function getDeptPeriod(Request $request)
    {
        $departments = $this->lookupManager->getDeptCostCenter();
        $periods = $this->lookupManager->findPostingPeriod($request->get("calendarId"));
        $preDpt = $request->get("pre_selected_dpt");
        $prePeriod = $request->get("pre_selected_period");

        $departmentHtml = "<option value=''>&lt;Select&gt;</option>";
        $periodHtml = "";

        if (isset($departments)) {
            foreach ($departments as $dpt) {
                if (isset($preDpt) && ($preDpt == $dpt->cost_center_dept_id)) {
                    $departmentHtml .= "<option selected value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                } else {
                    $departmentHtml .= "<option value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                }
            }
        } else {
            $departmentHtml = "<option value=''></option>";
        }

        if (isset($periods)) {
            foreach ($periods as $period) {
                if (isset($prePeriod) && ($prePeriod == $period->posting_period_id)) {
                    $periodHtml .= "<option " . (($period->posting_period_status == 'O') ? 'selected' : '') . "
                                        data-currentdate=" . HelperClass::dateConvert($period->current_posting_date) . "
                                        data-postingname=" . $period->posting_period_name . "
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                        value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";

                } else {
                    $periodHtml .= "<option " . (($period->posting_period_status == 'O') ? 'selected' : '') . "
                                        data-currentdate=" . HelperClass::dateConvert($period->current_posting_date) . "
                                        data-postingname=" . $period->posting_period_name . "
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";
                }
            }
        } else {
            $periodHtml = "<option value=''></option>";
        }

        return response()->json(['department' => $departmentHtml, 'period' => $periodHtml]);
        /*$departments = $this->budgetMonitoringLookupManager->getDeptCostCenter();

        $departmentHtml = "<option value=''>Select Department</option>";

        if (isset($departments)) {
            foreach ($departments as $dpt) {
                if (isset($preDpt) && ($preDpt == $dpt->cost_center_dept_id)) {
                    $departmentHtml .= "<option selected value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                } else {
                    $departmentHtml .= "<option value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                }
            }
        } else {
            $departmentHtml = "<option value=''></option>";
        }

        return response()->json(['department' => $departmentHtml]);*/
    }

    public function sectionByRegisterList(Request $request, $sectionId)
    {
        $searchTerm = $request->get('term');
        $billRegisters = [];
        $billRegisters = $this->budgetMonitoringLookupManager->getBillRegistersOnSection($sectionId,$searchTerm);
        return $billRegisters;
    }

    public function vendorList(Request $request)
    {
        $vendorType = $request->post('vendorType');
        $vendorCategory = $request->post('vendorCategory');
        $vendorName = $request->post('vendorName');
        $vendorShortName = $request->post('vendorShortName');

        $data = [];

        $data = FasApVendors::where('vendor_type_id', '=', DB::raw("NVL('" . $vendorType . "',vendor_type_id)"))
            ->where('workflow_approval_status', '=', ApprovalStatus::APPROVED)
            ->where('inactive_yn', '=', YesNoFlag::NO)          // Add Where Condition- Pavel-15-02-22
            ->where('vendor_category_id', '=', DB::raw("NVL('" . $vendorCategory . "',vendor_category_id)"))
            /*->where(function ($query) use ($vendorName, $vendorShortName) {
                $query->orWhere(DB::raw("upper(vendor_name)"), 'like', '%' . strtoupper($vendorName) . '%');
                $query->orWhere(DB::raw("upper(vendor_short_name)"), 'like', '%' . strtoupper($vendorShortName) . '%');
            })*/
            ->where(DB::raw("upper(vendor_name)"), 'like', '%'. strtoupper($vendorName) .'%')  //Add two Where Condition- Pavel-14-03-22
            ->where(function ($query) use ($vendorShortName) {
                $query->where(DB::raw('upper(fas_ap_vendors.vendor_short_name)'), 'like', strtoupper('%' . trim($vendorShortName) . '%') )
                    ->orWhere( 'vendor_short_name', '=', trim($vendorShortName) )
                    ->orWhere('vendor_short_name', '=', DB::raw("NVL('" . $vendorShortName . "',vendor_name)"));
            })
            ->with(['vendor_type', 'vendor_category'])
            ->orderBy('vendor_id','asc')
            ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return $data->vendor_name;
            })
            ->editColumn('short_name', function ($data) {
                return $data->vendor_short_name;
            })
            /*->editColumn('category', function ($data) {
                return $data->vendor_category->vendor_category_name;
            })*/
            ->editColumn('address', function ($data) {
                return $data->address_line1.' '. $data->address_line2;
            })
            ->editColumn('action', function ($data) {
                return "<button class='vendorSelect btn btn-sm btn-primary'  data-vendor='" . $data->vendor_id . "'  >Select</button>";
            })
            ->make(true);
    }

    public function getVendorDetails(Request $request)
    {
        $vendorId = $request->post('vendorId');

        $vendor = FasApVendors::select('vendor_id', 'vendor_name', 'vendor_category_id')
            ->where('vendor_id', '=', $vendorId)
            ->where('inactive_yn', '=', YesNoFlag::NO)          // Add inactive_yn & workflow_approval_status Where Condition- Pavel-15-02-22
            ->where('workflow_approval_status', '=', ApprovalStatus::APPROVED)
            ->first();
        return response()->json($vendor);
    }

    public function getRegisterDetail($id)
    {
        return LBillRegister::where('bill_reg_id','=',$id)->first();
    }

    public function budgetListForReport(Request $request)
    {
        $department = $request->get('department');
        $calendar = $request->get('calendar');
        /*if (isset($calendar) && isset($department)) {*/
        /*} else {
        $data = [];
    }*/
        return $this->budgetMonitoringManager->budgetHeadListForReport($calendar, $department,$request->get('term',null));
    }
}

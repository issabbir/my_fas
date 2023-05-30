<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:১২ PM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApVendors;
use App\Entities\Gl\GlCoaParams;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorSearchController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
    }

    public function index()
    {
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();

        return view('ap.vendor-search.index', compact('data'));
    }


    public function dataList(Request $request)
    {
        $vendorType = $request->post('vendorType');
        $vendorCategory = $request->post('vendorCategory');
        $vendorName = $request->post('vendorName');
        $vendorShortName = $request->post('vendorShortName');
        $approvalStatus = $request->post('approvalStatus');

//        $data = FasApVendors::where('vendor_type_id', '=', DB::raw("NVL('" . $vendorType . "',vendor_type_id)"))
//            ->where('inactive_yn','N')
//            ->where('vendor_category_id', '=', DB::raw("NVL('" . $vendorCategory . "',vendor_category_id)"))
//            ->where(DB::raw("upper(vendor_name)"), 'like', '%'. strtoupper($vendorName) .'%')  //Add two Where Condition- Pavel-14-03-22
//            ->where(function ($query) use ($vendorShortName) {
//                $query->where(DB::raw('upper(fas_ap_vendors.vendor_short_name)'), 'like', strtoupper('%' . trim($vendorShortName) . '%') )
//                    ->orWhere( 'vendor_short_name', '=', trim($vendorShortName) )
//                    ->orWhere('vendor_short_name', '=', DB::raw("NVL('" . $vendorShortName . "',vendor_name)"));
//            })->with(['vendor_type', 'vendor_category'])
//            ->orderBy('vendor_id','asc')
//            ->get();

        $data = DB::select("select cpaacc.fas_ap_config.get_ap_vendor_reg_list (:p_vendor_name,:p_vendor_short_name,:p_vendor_type_id,:p_vendor_category_id,:p_workflow_approval_status) from dual",
            ['p_vendor_name' => $vendorName, 'p_vendor_short_name' => $vendorShortName, 'p_vendor_type_id' => $vendorType, 'p_vendor_category_id' => $vendorCategory,'p_workflow_approval_status'=>$approvalStatus]);


        return datatables()->of($data)
            ->editColumn('name', function ($data) {
                return $data->vendor_name;
            })
            ->editColumn('short_name', function ($data) {
                return $data->vendor_short_name;
            })
            ->editColumn('category', function ($data) {
                return $data->vendor_category_name;
            })
            ->editColumn('address', function ($data) {
                return $data->address;
            })
            ->editColumn('status', function ($data) {
                if ($data->workflow_approval_status == ApprovalStatus::PENDING) {
                    return '<span class="badge badge-primary badge-pill">' . ApprovalStatusView::PENDING . '</span>';
                } else if ($data->workflow_approval_status == ApprovalStatus::APPROVED) {
                    return '<span class="badge badge-success badge-pill">' . ApprovalStatusView::APPROVED . '</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">' . ApprovalStatusView::DRAFT . '</span>';
                }
            })
            ->editColumn('action', function ($data) {
                if ($data->workflow_approval_status == ApprovalStatus::PENDING) {
                    return "<a style='text-decoration:underline' href='" . route('vendor-profile.edit', ['id' => $data->vendor_id, 'view' => true]) . "' data-target='' ><i class='bx bx-show-alt'></i></button>";
                }else{
                    return "<a style='text-decoration:underline' class='' href='" . route('vendor-profile.edit', ['id' => $data->vendor_id]) . "' ><i class='bx bx-edit-alt'></i></a> || " . "<a style='text-decoration:underline' href='" . route('vendor-profile.edit', ['id' => $data->vendor_id, 'view' => true]) . "' data-target='' ><i class='bx bx-show-alt'></i></button>";

                }
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
}

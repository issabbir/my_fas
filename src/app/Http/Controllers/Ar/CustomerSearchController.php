<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:১২ PM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApVendors;
use App\Entities\Ar\FasArCustomers;
use App\Entities\Gl\GlCoaParams;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSearchController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;
    private $arLookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    public function __construct(ArLookupManager $arLookupManager, LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->arLookupManager = $arLookupManager;
    }

    public function index()
    {
        $data['customerCategory'] = $this->arLookupManager->findCustomerCategory();

        return view('ar.customer-search.index', compact('data'));
    }


    public function dataList(Request $request)
    {

        $customerCategory = $request->post('customerCategory');
        $customerName = $request->post('customerName');
        $customerShortName = $request->post('customerShortName');
        $approvalStatus = $request->post('approvalStatus');

        $data = DB::select("select cpaacc.fas_ar_config.get_ar_customer_reg_list (:p_customer_name,:p_customer_short_name,:p_customer_category_id,:p_workflow_approval_status) from dual", ['p_customer_name' => $customerName, 'p_customer_short_name' => $customerShortName, 'p_customer_category_id' => $customerCategory, 'p_workflow_approval_status' => $approvalStatus]);

        return datatables()->of($data)
            ->editColumn('name', function ($data) {
//                dd($data);
                return $data->customer_name;
            })
            ->editColumn('short_name', function ($data) {
                return $data->customer_short_name;
            })
            ->editColumn('category', function ($data) {
                return $data->customer_category_name;
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
                if ($data->workflow_approval_status != ApprovalStatus::PENDING) {
                       return "<a style='text-decoration:underline' class='' href='" . route('customer-profile.edit', ['id' => $data->customer_id]) . "' ><i class='bx bx-edit-alt'></i></a>" . "||<a style='text-decoration:underline' href='" . route('customer-profile.edit', ['id' => $data->customer_id, 'view' => true]) . "'  data-target='' ><i class='bx bx-show-alt'></i></button>";

                }else{
                    return "<a style='text-decoration:underline' href='" . route('customer-profile.edit', ['id' => $data->customer_id, 'view' => true]) . "'  data-target='' ><i class='bx bx-show-alt'></i>" ;

                }
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}

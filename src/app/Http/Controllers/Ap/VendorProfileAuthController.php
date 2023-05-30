<?php
/**
 *Created by PhpStorm
 *Created at ২/১১/২১ ৩:২৫ PM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApVendors;
use App\Entities\Ar\FasArCustomers;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Security\User;
use App\Enums\ActionType;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorProfileAuthController extends Controller
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

        return view('ap.vendor-profile-authorize.index', compact('data'));
    }

    public function vendorAuthorize(Request $request, $id)
    {


        $data['insertedData'] = FasApVendors::with( 'branch_code')->where('vendor_id', '=', $id)->first();
        $data['view'] = DB::selectOne("select CPAACC.fas_ap_config.get_ap_vendor_auth_view (:p_vendors_auth_log_id) from dual", ['p_vendors_auth_log_id' => $id]);

        $data['insertedData']['workflow_mapping_id'] = $request->get('mapid');
        $data['insertedData']['current_status'] = $request->get('current_status');
        $data['insertedData']['login_user_id'] = $request->get('login_user_id');
        $data['insertedData']['comment'] = $request->get('comment');
        $data['insertedData']['emp'] =  User::with(['employee'])->where('user_id',$request->get('login_user_id'))->first();

        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['addressType'] = $this->lookupManager->getAddressType();
        $data['county'] = $this->lookupManager->getCountry();
        $data['bank'] = $this->lookupManager->getBankInfo();
        $data['bankDistrict'] = $this->lookupManager->getBankDistrict();
        $data['bankBranch'] = $this->lookupManager->getBankBranch();
        $data['bankAccountType'] = $this->lookupManager->getBankAccountType();
        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ap.vendor-profile-authorize.view', compact('data', 'coaParams'));
    }

    public function performAuthorize(Request $request)
    {

        $wkMapId = $request->get('workflow_mapping_id');
        $refStatus = $request->get('status') == ApprovalStatus::APPROVED ? ApprovalStatus::APPROVED : ApprovalStatus::REJECT;
        $remarks = $request->get('rem') == 'true' ? 'N/A' : $request->get('rem');
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::AP_VENDOR_ENTRY_APPROVAL,
                'i_reference_table' => WkReferenceTable::FAS_AP_VENDORS_AUTH_LOG,
                'i_reference_key' => WkReferenceColumn::VENDORS_AUTH_LOG_ID,
                'i_reference_status' => $refStatus,
                'i_reference_comment' => $remarks,
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
            }else{
                DB::commit();
            }
            $flashMessage = $this->flashMessageManager->getMessage($params);
            return redirect(route('vendor-profile-authorize.index'))->with($flashMessage['class'], $flashMessage['message']);

        } catch (\Exception $e) {
            DB::rollBack();
            $flashMessage = $this->flashMessageManager->getMessage($params);
            return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);
        }
    }

    public function dataList(Request $request)
    {

        $status = $request->post('approvalStatus');
        $actionType = $request->post('actionType');


        $data = DB::select("select CPAACC.fas_ap_config.get_ap_vendor_auth_list (:p_action_type,:p_workflow_approval_status,:p_user_id) from dual",
            ['p_action_type'=>$actionType,'p_workflow_approval_status'=>$status,'p_user_id'=>Auth()->id()]);

       return datatables()->of($data)
            ->editColumn('name', function ($data) {
                return $data->vendor_name;
            })
            ->editColumn('short_name', function ($data) {
                return $data->vendor_short_name;
            })
            /*->editColumn('category', function ($data) {
                return $data->vendor_category_name;
            })*/
            ->editColumn('address', function ($data) {
                return $data->address_line1.' '. $data->address_line2;
            })
           ->editColumn('action_type', function ($data) {
               if ($data->action_type == 'I' ) {
                   return 'New';
               } else if ($data->action_type == 'U') {
                   return 'Edit';
               }
           })
            ->editColumn('status', function ($data) {
                if ($data->approval_status == ApprovalStatusView::PENDING) {
                    return '<span class="badge badge-primary badge-pill">' . ApprovalStatusView::PENDING . '</span>';
                } else if ($data->approval_status == ApprovalStatusView::FORWARDED) {
                    return '<span class="badge badge-warning badge-pill">' . ApprovalStatusView::FORWARDED . '</span>';
                } else if ($data->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">' . ApprovalStatusView::APPROVED . '</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">' . ApprovalStatusView::REJECTED . '</span>';
                }
            })
            ->editColumn('action', function ($data) {
                return "<a style='text-decoration:underline' href='".route('vendor-profile-authorize.authorize', ['id' =>$data->vendors_auth_log_id, 'mapid' => $data->workflow_mapping_id, 'current_status' => $data->workflow_reference_status,'login_user_id'=>$data->login_user_id,'comment'=>''])."' class=' ml-1'><i class='bx bx-show-alt'></i></button>";
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
}

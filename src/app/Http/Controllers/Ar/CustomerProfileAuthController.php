<?php
/**
 *Created by PhpStorm
 *Created at ২/১১/২১ ৩:২৫ PM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ar\FasArCustomers;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Security\User;
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

class CustomerProfileAuthController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;

    /** @var ApLookupManager */
    private $apLookupManager;
    private $arLookupManager;

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
        $data = $this->getLookUps();

        $data['readonly'] = false;
        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ar.customer-profile-authorize.index', compact('data', 'coaParams'));
    }

    public function customerAuthorize(Request $request, $id)
    {

        $datas = DB::selectOne("select CPAACC.fas_ar_config.get_ar_customer_auth_view (:p_customer_auth_log_id) from dual", ['p_customer_auth_log_id' => $id]);


        $data = $this->getLookUps();
        $data['insertedData'] = FasArCustomers::with('customer_category')->where('customer_id', '=', $id)->first();
        $data['insertedData']['workflow_mapping_id'] = $request->get('mapid');
        $data['insertedData']['current_status'] = $request->get('current_status');
        $data['insertedData']['login_user_id'] = $request->get('login_user_id');
        $data['insertedData']['comment'] = $request->get('comment');
        $data['insertedData']['emp'] =  User::with(['employee'])->where('user_id',$request->get('login_user_id'))->first();

        $coaParams = $this->lookupManager->findGlCoaParams();
        return view('ar.customer-profile-authorize.view', compact('data', 'coaParams','datas'));
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
                'i_workflow_master_id' => WorkFlowMaster::AR_CUSTOMER_ENTRY_APPROVAL,
                'i_reference_table' => WkReferenceTable::FAS_AR_CUSTOMERS_AUTH_LOG,
                'i_reference_key' => WkReferenceColumn::CUSTOMER_AUTH_LOG_ID,
                'i_reference_status' => $refStatus,
                'i_reference_comment' => $remarks,
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
            } else {
                DB::commit();
            }
            $flashMessage = $this->flashMessageManager->getMessage($params);
            return redirect(route('customer-profile-authorize.index'))->with($flashMessage['class'], $flashMessage['message']);

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

        /*$sql = FasArCustomers::where('customer_category_id', '=', DB::raw("NVL('" . $customerCategory . "',customer_category_id)"));

        if (isset($customerName)) {
            $sql->where(DB::raw('upper(customer_name)'), 'LIKE', '%' . strtoupper($customerName) . '%');
        }

        if (isset($customerShortName)) {
            $sql->where(DB::raw('upper(customer_short_name)'), 'LIKE', '%' . strtoupper($customerShortName) . '%');
        }*/

        /*$data = $sql->with(['customer_category'])
            ->get();*/

//dd(Auth()->id());
        $data = DB::select("select CPAACC.fas_ar_config.get_ar_customer_auth_list (:p_action_type,:p_workflow_approval_status,:p_user_id) from dual", ['p_action_type'=>$actionType, 'p_workflow_approval_status' => $status, 'p_user_id' => Auth()->id()]);

        return datatables()->of($data)
            ->editColumn('name', function ($data) {
                return $data->customer_name;
            })
            ->editColumn('short_name', function ($data) {
                return $data->customer_short_name;
            })
            /*->editColumn('category', function ($data) {
                return $data->customer_category;
            })*/
            ->editColumn('address', function ($data) {

                if (empty($data->address_line2)){

                    return $data->address_line1;
                }else{

                    return $data->address_line1.'-'.$data->address_line2;
                }

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
                return "<a style='text-decoration:underline' href='" . route('customer-profile-authorize.authorize', ['id' => $data->workflow_reference_id, 'mapid' => $data->workflow_mapping_id, 'current_status' => $data->workflow_reference_status,'login_user_id'=>$data->login_user_id,'comment'=>'']) . "' class='ml-1' ><i class='bx bx-show-alt'></i></button>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getLookUps()
    {
        $data = [];
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['addressType'] = $this->lookupManager->getAddressType();
        $data['county'] = $this->lookupManager->getCountry();
        $data['bank'] = $this->lookupManager->getBankInfo();
        $data['bankDistrict'] = $this->lookupManager->getBankDistrict();
        $data['bankBranch'] = $this->lookupManager->getBankBranch();
        $data['bankAccountType'] = $this->lookupManager->getBankAccountType();
        $data['customerCategory'] = $this->arLookupManager->findCustomerCategory();
        $data['shippingAgencies'] = $this->arLookupManager->getShippingAgents();

        return $data;
    }
}

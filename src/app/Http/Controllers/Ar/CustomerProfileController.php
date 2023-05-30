<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:৩৫ AM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApVendors;
use App\Entities\Ar\FasArCustomers;
use App\Entities\Ar\FasArCustomersAddress;
use App\Entities\Ar\FasArCustomersAuthLog;
use App\Entities\Ar\LArCustomerCategory;
use App\Entities\Gl\GlCoaParams;
use App\Enums\ApprovalStatus;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
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

        return view('ar.customer-profile.index', compact('data', 'coaParams'));
    }

    public function insert(Request $request, $id = null)
    {

        DB::beginTransaction();
        try {

            // Add Customer Duplicate Check Condition- Pavel-16-02-22
            $cus_name= strtolower( trim($request->post('name')) );
            $cus_mobile= $request->post('mobile');
            $cus_category= $request->post('customer_category');

            $cus_model = FasArCustomers::where(function($query) use ($cus_name, $cus_mobile) {
                    $query->where( DB::raw('LOWER(fas_ar_customers.customer_name)'),'=',  $cus_name  )
                        ->orWhere('contact_person_mobile' , '=' , $cus_mobile );
                    })
                ->where('customer_category_id' , '=' , $cus_category);
                //->first();
            if ($id){
                $customer = $cus_model->whereNotIn('customer_id', [$id])->first();
            } else {
                $customer = $cus_model->first();
            }

            if ( isset($customer) &&  (strtolower(trim($customer->customer_name)) == $cus_name) ) {
                if ($id) {
                    return ['status_code'=>99,'response_msg' => '[AR CUSTOMER SETUP] - Customer Name Already Exist.'];

                    //return ['o_status_code' => '99', 'o_status_message' => '[AR CUSTOMER SETUP] - Customer Name Already Exist.' ];
                } else {
                    return response()->json(['status_code'=>99,'response_msg' => '[AP CUSTOMER SETUP] - Customer Name Already Exist.']);

                    //return redirect()->back()->with('error', '[AR CUSTOMER SETUP] - Customer Name Already Exist.')->withInput();
                }
            } elseif ( isset($customer) && !empty($cus_mobile) &&($customer->contact_person_mobile == $cus_mobile)) {
                if ($id) {
                    return ['status_code'=>99,'response_msg' => '[AR CUSTOMER SETUP] - Customer Mobile Already Exist.'];

                    //return ['o_status_code' => '99', 'o_status_message' => '[AR CUSTOMER SETUP] - Customer Mobile No Already Exist.' ];
                } else {
                    return response()->json(['status_code'=>99,'response_msg' => '[AP CUSTOMER SETUP] - Customer Mobile Already Exist.']);

                    //return redirect()->back()->with('error', '[AR CUSTOMER SETUP] - Customer Mobile No Already Exist.')->withInput();
                }
            } else {

                $inactive_date = $request->post('inactive_date');
                $inactive_date = isset($inactive_date) ? HelperClass::dateFormatForDB($inactive_date) : null;
                $is_inactive = $request->post('is_inactive');
                $is_inactive = isset($is_inactive) ? $is_inactive : 'N';
                $customer_id = isset($id) ? $id : null;
                $action_type = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;

                $customer_auth_log_id = sprintf("%4000s", "");
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_action_type' => $action_type,
                    'p_customer_id' => [
                        'value' => &$customer_id,
                        'type' => \PDO::PARAM_INPUT_OUTPUT,
                        'length' => 255
                    ],
                    'p_customer_name' => $request->post('name'),
                    'p_customer_short_name' => $request->post('short_name'),
                    'p_opening_date' => HelperClass::dateFormatForDB($request->post('opening_date')),
                    'p_customer_category_id' => $request->post('customer_category'),
                    //'p_agency_id' => $request->post('shipping_agency_id'),
                    'p_enlisted_customer_yn' => ($request->post('enlisted_customer') != null) ? 'Y' : 'N',
                    'p_bin_no' => $request->post('bin'),
                    'p_tin_no' => $request->post('tin'),
                    'p_vat_registration_no' => $request->post('vat'),
                    'p_license_no' => $request->post('license'),
                    'p_license_expiry_date' => HelperClass::dateFormatForDB($request->post('license_exp_date')),
                    'p_address_line1' => $request->post('address_1'),
                    'p_address_line2' => $request->post('address_2'),
                    'p_city' => $request->post('city'),
                    'p_state_name' => $request->post('state'),
                    'p_postal_code' => $request->post('postal_code'),
                    'p_country' => $request->post('country'),
                    'p_contact_person_name' => $request->post('contact_name'),
                    'p_contact_person_designation' => $request->post('designation'),
                    'p_contact_person_phone' => $request->post('phone'),
                    'p_contact_person_mobile' => $request->post('mobile'),
                    'p_contact_person_email' => $request->post('email'),
                    'p_inactive_yn' => $is_inactive,
                    'p_inactive_date' => $inactive_date,
                    'p_user_id' => auth()->id(),
                    'o_customer_auth_log_id' => &$customer_auth_log_id,
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message
                ];

                DB::executeProcedure('CPAACC.fas_ar_config.create_ar_customers', $params);

            if ($params['o_status_code'] == "1") {

                    if ($customer_id) {
                        $customer = FasArCustomers::where('customer_id', '=', $customer_id)->first();
                        $customer_auth_log = FasArCustomersAuthLog ::where('customer_id', '=', $customer_id)->first();

                        //If Workflow Approval Status is A=Approved then mapping entry.
                        if ( !isset($id) || ($customer->workflow_approval_status ==  ApprovalStatus::PENDING) ) {
                            $wk_mapping_status_code = sprintf("%4000s", "");
                            $wk_mapping_status_message = sprintf("%4000s", "");

                            $wkMappingParams = [
                                'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AR_CUSTOMER_ENTRY_APPROVAL,
                                'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AR_CUSTOMERS_AUTH_LOG,
                                'P_REFERANCE_KEY' => WkReferenceColumn::CUSTOMER_AUTH_LOG_ID,
                                'P_REFERANCE_ID' => $customer_auth_log->customer_auth_log_id,
                                'P_TRANS_PERIOD_ID' => '',
                                'P_INSERT_BY' => auth()->id(),
                                'o_status_code' => &$wk_mapping_status_code,
                                'o_status_message' => &$wk_mapping_status_message,
                            ];

                            DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

                            if ($wkMappingParams['o_status_code'] != 1) {
                                DB::rollBack();

                                if ($id) {
                                    return ['status_code' => $wkMappingParams["o_status_code"],'response_msg' => $wkMappingParams["o_status_message"]];
                                } else {
                                    return response()->json(['status_code' => $wkMappingParams["o_status_code"],'response_msg' => $wkMappingParams["o_status_message"]]);
                                }
                                /*
                                $flashMessage = $this->flashMessageManager->getMessage($wkMappingParams);
                                return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);*/
                            }
                        }
                    }

                    DB::commit();

                    if ($id) {
                        return ['status_code' => $params["o_status_code"], 'response_msg'=>$params["o_status_message"]];
                    } else {
                        return response()->json(['status_code' => $params["o_status_code"], 'response_msg'=>$params["o_status_message"]]);
                    }
                } else {
                    DB::rollBack();

                    if ($id) {
                        return ['status_code' => $params["o_status_code"], 'response_msg'=>$params["o_status_message"]];
                    } else {
                        return response()->json(['status_code' => $params["o_status_code"], 'response_msg'=>$params["o_status_message"]]);
                    }

                    /*if ($id) {
                        return $params;
                    } else {
                        $flashMessage = $this->flashMessageManager->getMessage($params);
                        return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();
                    }*/
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();

            if ($id) {
                return ['status_code' =>99,'response_msg' => $e->getMessage()];
            } else {
                return response()->json(['status_code' =>99,'response_msg' => $e->getMessage()]);
            }

            /*if ($id) {
                return ["o_status_code" => '99', 'o_status_message' => $e->getMessage()];
            } else {
                return redirect()->back()->with("error", "Exception occurred.")->withInput();
            }*/
        }
    }

    public function edit($id, $view = false)
    {
        $data = $this->getLookUps();
        $data['readonly'] = $view;
        $data['insertedData'] = FasArCustomers::with('customer_category')->where('customer_id', '=', $id)->first();

        $coaParams = $this->lookupManager->findGlCoaParams();
        return view('ar.customer-profile.index', compact('data', 'coaParams'));
    }

    public function update(Request $request, $id)
    {

        return response()->json($this->insert($request, $id));
    }

    public function delete($id)
    {
        if (isset($id)) {
            DB::beginTransaction();
            try {
                $vendor_id = $id;
                $address = FasApVendors::where('vendor_id', '=', $id)->first();
                $address_id = $address->address_id;

                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");


                $params = [
                    'p_action_type' => ProActionType::DELETE,
                    'p_address_id' => $address_id,
                    'p_vendor_id' => $vendor_id,
                    'p_address_type_id' => '',
                    'p_address_line1' => '',
                    'p_address_line2' => '',
                    'p_city' => '',
                    'p_state_name' => '',
                    'p_postal_code' => '',
                    'p_country' => '',
                    'p_contact_person_name' => '',
                    'p_contact_person_phone' => '',
                    'p_contact_person_mobile' => '',
                    'p_contact_person_email' => '',
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message
                ];

                DB::executeProcedure('CPAACC.fas_ap_config.create_ap_vendors_address', $params);

                if ($params['o_status_code'] == "99") {
                    $params = [
                        'p_action_type' => ProActionType::DELETE,
                        'p_vendor_id' => [
                            'value' => &$vendor_id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        'p_vendor_name' => '',
                        'p_vendor_short_name' => '',
                        'p_opening_date' => '',
                        'p_vendor_type_id' => '',
                        'p_vendor_category_id' => '',
                        'p_enlisted_vendor_yn' => '',
                        'p_bin_no' => '',
                        'p_tin_no' => '',
                        'p_vat_registration_no' => '',
                        'p_cost_center_dept_control_yn' => '',
                        'p_cost_center_dept_id' => '',
                        'p_bank_id' => '',
                        'p_bank_branch_name' => '',
                        //'p_bank_branch_routing_no' => '',
                        'p_bank_account_no' => '',
                        'p_bank_account_title' => '',
                        'p_bank_account_type_id' => '',
                        'p_payment_hold_flag' => '',
                        'p_payment_hold_reason' => '',
                        'p_inactive_yn' => '',
                        'p_inactive_date' => '',
                        'p_user_id' => auth()->id(),
                        'o_status_code' => &$status_code,
                        'o_status_message' => &$status_message
                    ];

                    DB::executeProcedure('CPAACC.fas_ap_config.create_ap_vendors', $params);

                    if ($params['o_status_code'] == "99") {
                        DB::rollBack();
                        $flashMessage = $this->flashMessageManager->getMessage($params);
                        return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);
                    } else {
                        DB::commit();
                        $flashMessage = $this->flashMessageManager->getMessage($params);
                        return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();
                    }
                } else {
                    //DB::commit();
                    $flashMessage = $this->flashMessageManager->getMessage($params);
                    return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();
                }

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with("error", 'Exception occurred');
            }
        } else {
            return redirect()->back()->with('error', 'Vendor Not Found.');
        }
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

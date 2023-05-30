<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:৩৫ AM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApVendorAddress;
use App\Entities\Ap\FasApVendors;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\VendorType;
use App\Enums\ApprovalStatus;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorProfileControllerBK extends Controller
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
        $data['readonly'] = false;
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        //$data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['department'] = $this->lookupManager->getDeptCostCenter();
        $data['addressType'] = $this->lookupManager->getAddressType();
        $data['county'] = $this->lookupManager->getCountry();
        $data['bank'] = $this->lookupManager->getBankInfo();
        $data['bankDistrict'] = $this->lookupManager->getBankDistrict();
        $data['bankBranch'] = $this->lookupManager->getBankBranch();
        $data['bankAccountType'] = $this->lookupManager->getBankAccountType();

        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ap.vendor-profile.index', compact('data', 'coaParams'));
    }

    public function insert(Request $request, $id = null)
    {
        // Add Customer Duplicate Check Condition- Pavel-16-02-22
        $vendor_name= strtolower( trim($request->post('name')) );
        $vendor_mobile= $request->post('mobile');
        $vendor_type= $request->post('vendor_type');
        $vendor_category= $request->post('vendor_category');

        $vendor_model = FasApVendors::with(['vendor_address'])
            ->where(function($query) use ($vendor_name, $vendor_mobile) {
                $query->where( DB::raw('LOWER(fas_ap_vendors.vendor_name)'),'=',  $vendor_name  )
                    ->orWhereHas('vendor_address', function ( $query ) use ( $vendor_mobile ) {
                        $query->where('contact_person_mobile' , '=' , $vendor_mobile );
                    });
            })
            ->where('vendor_type_id' , '=' , $vendor_type)
            ->where('vendor_category_id' , '=' , $vendor_category);
        //->first();

        if ($id){
            $vendor = $vendor_model->whereNotIn('vendor_id', [$id])->first();
        } else {
            $vendor = $vendor_model->first();
        }

        if ( isset($vendor) &&  (strtolower(trim($vendor->vendor_name)) == $vendor_name) ) {
            if ($id) {
                return ['error' => '[AP VENDOR SETUP] - Vendor Name Already Exist.'];
                //return $a_params;
            } else {
                return response()->json(['error' => '[AP VENDOR SETUP] - Vendor Name Already Exist.']);

                /*$flashMessage = $this->flashMessageManager->getMessage($a_params);
                return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();*/
            }
        } elseif ( isset($vendor->vendor_address) && $vendor->vendor_address->contact_person_mobile == $vendor_mobile) {
            if ($id) {
                return ['error' => '[AP VENDOR SETUP] - Vendor Mobile Already Exist.'];
                //return $a_params;
            } else {
                return response()->json(['error' => '[AP VENDOR SETUP] - Vendor Mobile Already Exist.']);
                /*$flashMessage = $this->flashMessageManager->getMessage($a_params);
                return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();*/
            }
        }else{
            DB::beginTransaction();
            try {
                $vendor_id = isset($id) ? $id : null;
                $action_type = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    'p_action_type' => $action_type,
                    'p_vendor_id' => [
                        'value' => &$vendor_id,
                        'type' => \PDO::PARAM_INPUT_OUTPUT,
                        'length' => 255
                    ],
                    'p_vendor_name' => $request->post('name'),
                    'p_vendor_short_name' => $request->post('short_name'),
                    'p_opening_date' => HelperClass::dateFormatForDB($request->post('opening_date')),
                    'p_vendor_type_id' => $request->post('vendor_type'),
                    'p_vendor_category_id' => $request->post('vendor_category'),
                    'p_enlisted_vendor_yn' => ($request->post('enlisted_vendor') != null) ? 'Y' : 'N',
                    'p_bin_no' => $request->post('bin'),
                    'p_tin_no' => $request->post('tin'),
                    'p_vat_registration_no' => $request->post('vat'),
                    'p_cost_center_dept_control_yn' => ($request->post('allow_dept_cost_center') != null) ? 'Y' : 'N',
                    'p_cost_center_dept_id' => $request->post('dept_cost_center'),
                    'p_bank_code' => $request->post('bank_id'),
                    'p_bank_branch_code' => $request->post('branch_id'),
                    //'p_bank_branch_routing_no' => $request->post('routing_number'),
                    'p_bank_account_no' => $request->post('account_no'),
                    'p_bank_account_title' => $request->post('account_title'),
                    'p_bank_account_type_id' => $request->post('account_type'),
                    'p_payment_hold_flag' => ($request->post('hold_all_payment') != null) ? '1' : '0',
                    'p_payment_hold_reason' => $request->post('hold_all_payment_reason'),
                    'p_inactive_yn' => ($request->post('is_inactive') != null) ? 'Y' : 'N',
                    'p_inactive_date' => HelperClass::dateFormatForDB($request->post('inactive_date')),
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message
                ];

                DB::executeProcedure('CPAACC.fas_ap_config.create_ap_vendors', $params);

                if ($params['o_status_code'] == "1") {
                    if ($request->post('vendor_type') == VendorType::EXTERNAL) {
                        $address_id = null;
                        if ($action_type == ProActionType::UPDATE) {
                            $address = FasApVendorAddress::where('vendor_id', '=', $id)->first();
                            $address_id = $address->address_id;
                        }
                        $a_status_code = sprintf("%4000s", "");
                        $a_status_message = sprintf("%4000s", "");
                        $a_params = [
                            'p_action_type' => $action_type,
                            'p_address_id' => [
                                'value' => &$address_id,
                                'type' => \PDO::PARAM_INPUT_OUTPUT,
                                'length' => 255
                            ],
                            'p_vendor_id' => $vendor_id,
                            'p_address_type_id' => $request->post('address_type'),
                            'p_address_line1' => $request->post('address_1'),
                            'p_address_line2' => $request->post('address_2'),
                            'p_city' => $request->post('city'),
                            'p_state_name' => $request->post('state'),
                            'p_postal_code' => $request->post('postal_code'),
                            'p_country' => $request->post('country'),
                            'p_contact_person_name' => $request->post('contact_name'),
                            'p_contact_person_phone' => $request->post('phone'),
                            'p_contact_person_mobile' => $request->post('mobile'),
                            'p_contact_person_email' => $request->post('email'),
                            'p_user_id' => auth()->id(),
                            'o_status_code' => &$a_status_code,
                            'o_status_message' => &$a_status_message
                        ];

                        DB::executeProcedure('CPAACC.fas_ap_config.create_ap_vendors_address', $a_params);

                        if ($a_params['o_status_code'] != "1") {
                            DB::rollBack();
                            if ($id) {
                                return ['error' => $a_params["o_status_message"]];
                                //return $a_params;
                            } else {
                                return response()->json(['error' => $a_params["o_status_message"]]);

                                /*$flashMessage = $this->flashMessageManager->getMessage($a_params);
                                return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();*/
                            }
                        }
                    }
                } else {
                    DB::rollBack();
                    if ($id) {
                        return ['error' => $params["o_status_message"]];
                        //return $params;
                    } else {
                        return response()->json(['error' => $params["o_status_message"]]);
                    }
                    /*
                                    $flashMessage = $this->flashMessageManager->getMessage($params);
                                    return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();*/
                }

                if ($vendor_id) {
                    $vendor = FasApVendors::where('vendor_id','=',$id)->first();
                    //If Workflow Approval Status is A=Approved then mapping entry.
                    if ( !isset($id) || ($vendor->workflow_approval_status ==  ApprovalStatus::APPROVED) ) {
                        $wk_mapping_status_code = sprintf("%4000s", "");
                        $wk_mapping_status_message = sprintf("%4000s", "");

                        $wkMappingParams = [
                            'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AP_VENDOR_ENTRY_APPROVAL,
                            'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AP_VENDORS,
                            'P_REFERANCE_KEY' => WkReferenceColumn::VENDOR_ID,
                            'P_REFERANCE_ID' => $vendor_id,
                            'P_TRANS_PERIOD_ID' => '',
                            'P_INSERT_BY' => auth()->id(),
                            'o_status_code' => &$wk_mapping_status_code,
                            'o_status_message' => &$wk_mapping_status_message,
                        ];

                        DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);
                        if ($wkMappingParams['o_status_code'] != 1) {
                            DB::rollBack();
                            if ($id) {
                                return ['error' => $wkMappingParams["o_status_message"]];
                                //return $params;
                            } else {
                                return response()->json(['error' => $wkMappingParams["o_status_message"]]);

                                /*$flashMessage = $this->flashMessageManager->getMessage($wkMappingParams);
                                return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();*/
                            }
                        }
                    }
                }

                DB::commit();
                if ($id) {
                    return ['success' => $params["o_status_message"]];
                    //return $params;
                } else {
                    return response()->json(['success' => $params["o_status_message"]]);
                    /*$flashMessage = $this->flashMessageManager->getMessage($params);
                    return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);*/
                }

            } catch (\Exception $e) {
                DB::rollBack();
                if ($id) {
                    return ['error' => $e->getMessage()];
                    //return ["o_status_code" => '99', 'o_status_message' => $e->getMessage()];
                } else {
                    return response()->json(['error' => $e->getMessage()]);
                    //return redirect()->back()->with("error", "Exception occurred.")->withInput();
                }
            }
        }
    }

    public function edit($id, $view = false)
    {
        $data['readonly'] = $view;
        $data['insertedData'] = FasApVendors::with('vendor_address', 'branch_code')->where('vendor_id', '=', $id)->first();
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
        return view('ap.vendor-profile.index', compact('data', 'coaParams'));
    }

    public function update(Request $request, $id)
    {
        //$response = $this->insert($request, $id);
        return response()->json($this->insert($request, $id));

        /*$flashMessage = $this->flashMessageManager->getMessage($response);
        return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);*/
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
}

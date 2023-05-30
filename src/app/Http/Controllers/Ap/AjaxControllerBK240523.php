<?php
/**
 *Created by PhpStorm
 *Created at ৯/৯/২১ ৩:২৯ PM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApInvoiceParams;
use App\Entities\Ap\FasApVendors;
use App\Entities\Cims\PurchaseRcvMst;
use App\Entities\Cm\CmBankDistrict;
use App\Entities\Cm\CmBankInfo;
use App\Entities\Common\LBillRegister;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Security\User;
use App\Entities\WorkFlowMapping;
use App\Enums\Ap\ApChequePaymentType;
use App\Enums\Ap\ApFunType;
use App\Enums\Ap\LApInvoiceType;
use App\Enums\ApprovalStatus;
use App\Enums\Common\GlAccountID;
use App\Enums\Common\GlSubsidiaryParams;
use App\Enums\Common\LGlInteModules;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AjaxControllerBK240523 extends Controller
{
    protected $lookupManager;
    protected $purchaseRcvMst;
    protected $apLookUpManager;
    protected $glCoa;

    public function __construct(LookupManager $lookupManager, FlashMessageManager $flashMessageManager, ApLookupManager $apLookUpManager)
    {
        $this->lookupManager = $lookupManager;
        $this->purchaseRcvMst = new PurchaseRcvMst();
        $this->apLookUpManager = $apLookUpManager;
        $this->glCoa = new GlCoa();
    }

    public function contraLedgers(Request $request)
    {
        $id = $request->post('selectedLedger');
        $preSetContra = $request->post('preSelectedContra');

        $coaParams = $this->lookupManager->getContraPartySubLedger(LGlInteModules::ACC_PAY_VENDOR, $id);
        $option = '<option value="">&lt;Select&gt;</option>';

        foreach ($coaParams as $param) {
            $option .= "<option value='$param->gl_subsidiary_id'";
            if ($param->gl_subsidiary_id == $preSetContra) {
                $option .= 'Selected';
            }
            $option .= ">" . $param->gl_subsidiary_name . "</option>";
        }

        return response()->json($option);
    }

    public function getBranchesOnBank(Request $request)
    {
        $branches = $this->lookupManager->getBranchOnBank($request->get('id'));

        $option = "<option value=''>&lt;Select&gt;</option>";
        foreach ($branches as $param) {
            $option .= "<option value='$param->branch_code' data-routing='" . $param->routing_no . "'";
            if ($param->branch_code == $request->get('branch')) {
                $option .= 'Selected';
            }
            $option .= ">" . $param->bank_district->district_name . "-" . $param->branch_name . "</option>";
        }

        return response()->json($option);
    }

    public function glTypeWiseCoaList(Request $request)
    {
        $postData = $request->post();
        $queryResult = [];

        if (empty($postData['gl_type_id'])) {
            $queryResult = [];
        } else {
            $queryResult = GlCoa::where('gl_type_id', $postData['gl_type_id'])->orderBy('gl_acc_id', 'asc')->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-sm btn-primary btn-sm gl-coa" data-gl-type="' . $query->gl_type_id . '"   id="' . $query->gl_acc_id . '">Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

    public function glTypeAccWiseCoa(Request $request)
    {
        $glCoaInfo = '';
        $glTypeId = $request->get('gl_type_id');
        $glAccId = $request->get('gl_acc_id');

        $glCoaInfo = GlCoa::where(
            [
                ['gl_acc_id', '=', $glAccId],
                ['gl_type_id', '=', $glTypeId],
            ]
        )->first();
        return response()->json($glCoaInfo);
    }

    public function glAccWiseCoa(Request $request)
    {
        $glCoaInfo = '';
        $glAccId = $request->get('gl_acc_id');

        //$glCoaInfo = GlCoa::where('gl_acc_id', '=', $glAccId)->first();
        $glCoaInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual", ['p_gl_acc_id' => $glAccId]);
        return response()->json($glCoaInfo);
    }

    /*public function cmBanks(Request $request)
    {
        $cmBanks = [];
        $searchTerm = $request->get('term');

        $cmBanks = CmBankInfo::select('*')
            ->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(bank_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhere('bank_code', 'like', '' . trim($searchTerm) . '%');
            })->orderBy('bank_name', 'ASC')->limit(10)->get();

        return $cmBanks;
    }

    public function cmBank(Request $request, $bankCode)
    {
        $cmBank = '';
        $cmBank = CmBankInfo::where('bank_code', '=', $bankCode)->first();
        return $cmBank;
    }

    public function cmBankDistricts(Request $request)
    {
        $cmBankDistricts = [];
        $searchTerm = $request->get('term');

        $cmBankDistricts = CmBankDistrict::select('*')
            ->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(district_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhere('district_code', 'like', '' . trim($searchTerm) . '%');
            })->orderBy('district_name', 'ASC')->limit(10)->get();

        return $cmBankDistricts;
    }

    public function cmBankDistrict(Request $request, $districtCode)
    {
        $cmBank = '';
        $cmBank = CmBankDistrict::where('district_code', '=', $districtCode)->first();
        return $cmBank;
    }*/

    public function sectionByRegisterList(Request $request, $sectionId)
    {
        $searchTerm = $request->get('term');
        return $this->lookupManager->getBillRegistersOnSection($sectionId, $searchTerm);
    }

    public function invoiceReferenceList(Request $request)
    {
        $postData = $request->post();
        $invRefList = [];

        $vendorId = isset($postData['vendor_id']) ? $postData['vendor_id'] : '';
        $glSubsidiaryId = isset($postData['party_sub_ledger']) ? $postData['party_sub_ledger'] : '';
        $paymentQueueInvId = isset($postData['selected_pay_queue_inv_id']) ? $postData['selected_pay_queue_inv_id'] : '';
        //$chequePayTypeId = isset($postData['cheque_pay_type_id']) ? $postData['cheque_pay_type_id'] : '';
        $intBillPayYn = isset($postData['internal_bill_pay_yn']) && ($postData['internal_bill_pay_yn'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $totalChecked = 0;

        if ($vendorId && $glSubsidiaryId) {
            $postingDate = $postData['posting_date'];
            //TODO: add posting date in function call
            $invRefList = DB::select("select CPAACC.fas_ap_trans.get_ap_bill_payment_ref_mk (:p_gl_subsidiary_id, :p_vendor_id, :p_internal_bill_pmt_yn, :p_function_id) from dual", ['p_gl_subsidiary_id' => $glSubsidiaryId, 'p_vendor_id' => $vendorId, 'p_internal_bill_pmt_yn' => $intBillPayYn, 'p_function_id' => null]);
            /*****Added on: 25082022****/
            $documentDate = $request->post('document_date');
            //	0003217: ESTABLISH DOCUMENT NUMBER MANDATORY POLICY
            $documentNo = $request->post('document_number');
            //$documentNo = $request->post('draft_document_no');
            $draftData = [];

            if (isset($documentDate)) {
                $draftData = DB::select('select FAS_AP_TRANS.get_ap_payment_invoices_draft(:p_document_no, :p_document_date) from dual', ['p_document_no' => $documentNo, 'p_document_date' => HelperClass::dateFormatForDB($documentDate)]);
                if (count($draftData) > 0) {
                    foreach ($draftData as $item) {
                        foreach ($invRefList as $list) {
                            if ($item->invoice_id == $list->invoice_id) {
                                $list->payable_amount = $item->payment_amt;
                                $list->default_select = true;
                                $totalChecked++;
                                $list->order = $item->order_sl_no;  //adding order index with order value in cheque list
                            }
                        }
                    }
                    //Adding order index with INF value for non order cheque list
                    array_walk($invRefList,function (&$inv){
                        if (!array_key_exists('order',(array)$inv)){
                            $inv->order = INF;   //Setting default value infinity, to order cheque list according to sl no
                        }
                        if (!array_key_exists('default_select',(array)$inv)){
                            $inv->default_select = false;
                        }
                    });

                    //ordering according to serial no as $invRefList doesn't have any order column in database table
                    $array_column = array_column($invRefList,"order");
                    array_multisort($array_column,SORT_ASC,$invRefList);

                } elseif ($paymentQueueInvId != "") {
                    $order = 1;
                    array_walk($invRefList, function (&$d) use (&$totalChecked, $paymentQueueInvId, &$order) {
                        if ($d->invoice_id == $paymentQueueInvId) {
                            $d->default_select = true;
                            $totalChecked++;
                            $d->order = $order++;
                        }
                    });

                    //Adding order index with INF value for non order cheque list
                    array_walk($invRefList,function (&$inv){
                        if (!array_key_exists('order',(array)$inv)){
                            $inv->order = INF;   //Setting default value infinity, to  list according to sl no
                        }
                        if (!array_key_exists('default_select',(array)$inv)){
                            $inv->default_select = false;
                        }
                    });

                    //ordering according to serial no as $invRefList doesn't have any order column in database table
                    $array_column = array_column($invRefList,"order");
                    array_multisort($array_column,SORT_ASC,$invRefList);
                }else{
                    array_walk($invRefList, function (&$d) {
                        $d->default_select = false;
                        $d->order = INF;
                    });
                }
            }else{
                array_walk($invRefList, function (&$d) {
                    $d->default_select = false;
                    $d->order = INF;
                });
            }
        }

        //$html = view('ap.ajax.invoice_reference_list')->with('invRefList', $invRefList)->render();
        $html = view('ap.ajax.invoice_reference_list')->with(compact('invRefList', 'paymentQueueInvId'))->render();

        $jsonArray = [
            'html' => $html,
            'totalChecked' => $totalChecked
        ];

        return response()->json($jsonArray);
    }

    public function invoiceReferenceCashChequeList(Request $request)
    {
        $postData = $request->post();
        $invRefCashChequeList = [];

        $vendorId = null;
        $glSubsidiaryId = null;
        $chequePayTypeId = isset($postData['cheque_pay_type_id']) ? $postData['cheque_pay_type_id'] : '';
        $intBillPayYn = isset($postData['internal_bill_pay_yn']) && ($postData['internal_bill_pay_yn'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        //0003902: Need To save the narration after save draft of internal bill payment (AP module)
        $narration = "";

        if (($intBillPayYn == YesNoFlag::YES) && ($chequePayTypeId == ApChequePaymentType::CASH_CHEQUE)) {
            $pmisFunction = $request->post('pmis_function_name');

            $postingDate = $postData['posting_date'];
            //TODO: add posting date in function call
            $invRefCashChequeList = DB::select("select CPAACC.fas_ap_trans.get_ap_bill_payment_ref_mk (:p_gl_subsidiary_id, :p_vendor_id, :p_internal_bill_pmt_yn, :p_function_id) from dual", ['p_gl_subsidiary_id' => $glSubsidiaryId, 'p_vendor_id' => $vendorId, 'p_internal_bill_pmt_yn' => $intBillPayYn, 'p_function_id' => $pmisFunction]);

            //Adding custom columns(default_select, not_changeable, order) for data table
            array_walk($invRefCashChequeList, function (&$d) {
                $d->default_select = false;
                $d->order = INF;
            });
            /*****Added on: 25082022****/
            $documentDate = $request->post('document_date');
            //$documentNo = $request->post('document_number');

            //	0003217: ESTABLISH DOCUMENT NUMBER MANDATORY POLICY
            if ((DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag) == \App\Enums\Common\FasPolicyParam::LIVE_IMPLIMENTATION_STATUS_ON) {
                $documentNo = $request->post('draft_document_no');
            } else {
                $documentNo = $request->post('document_number');
            }
            $draftData = [];

            /****For PMIS function data*****/
            if (isset($pmisFunction)) {
                /***If references comes on pmis functions then setting a custom index (default_select) as true, to identify them as checked.***/
                $order = 1;
                array_walk($invRefCashChequeList, function (&$d) use(&$order) {
                    $d->default_select = true;
                    $d->not_changeable = false;
                    $d->order = $order++;
                });
            }

            /****For Draft data*****/
            $totalDraftPayment = 0;
            if (isset($documentDate) && !isset($pmisFunction)) {
                $draftData = DB::select('select FAS_AP_TRANS.get_ap_payment_invoices_draft(:p_document_no, :p_document_date) from dual', ['p_document_no' => $documentNo, 'p_document_date' => HelperClass::dateFormatForDB($documentDate)]);
                if (count($draftData) > 0) {
                    foreach ($draftData as $item) {
                        foreach ($invRefCashChequeList as $list) {
                            if ($item->invoice_id == $list->invoice_id) {
                                $list->payable_amount = $item->payment_amt;
                                $list->default_select = true;
                                $list->order = $item->order_sl_no;  //adding order index with order value in cheque list
                                $totalDraftPayment += $item->payment_amt;
                            }
                        }
                    }

                    //Adding order index with INF value for non order cheque list
                    array_walk($invRefCashChequeList,function (&$inv){
                        if (!array_key_exists('order',(array)$inv)){
                            $inv->order = INF;   //Setting default value infinity, to order cheque list according to sl no
                        }
                        if (!array_key_exists('default_select',(array)$inv)){
                            $inv->default_select = false;
                        }
                    });

                    //ordering according to serial no as $invRefCashChequeList doesn't have any order column in database table
                    $array_column = array_column($invRefCashChequeList,"order");
                    array_multisort($array_column,SORT_ASC,$invRefCashChequeList);

                    $narration = $draftData[0]->narration;
                }
            }
        }

        $html = view('ap.ajax.invoice_reference_cash_cheque_list')->with('invRefCashChequeList', $invRefCashChequeList)->render();
        //$html = view('ap.ajax.invoice_reference_list')->with(compact('invRefList', 'paymentQueueInvId'))->render();

        $jsonArray = [
            'html' => $html,
            'narration' => $narration
        ];

        return response()->json($jsonArray);
    }

    public function invoiceReferenceTaxPayList(Request $request)
    {
        $postData = $request->post();
        $taxVendorId = isset($postData['tax_vendor_id']) ? $postData['tax_vendor_id'] : '';
        $vendorId = isset($postData['vendor_id']) ? $postData['vendor_id'] : '';
        $glSubsidiaryId = isset($postData['party_sub_ledger']) ? $postData['party_sub_ledger'] : '';
        $taxPayQueueInvId = isset($postData['selected_tax_pay_queue_inv_id']) ? $postData['selected_tax_pay_queue_inv_id'] : '';
        $invRefTaxPayList = [];

        if ($vendorId && $glSubsidiaryId) {
            $invRefTaxPayList = DB::select("select CPAACC.fas_ap_trans.get_ap_tax_payment_ref_mk (:p_vendor_id) from dual", ['p_vendor_id' => $taxVendorId]);
            array_walk($invRefTaxPayList, function (&$d) {
                //$d->default_select = true;
                //$d->not_changeable = false;
                $d->order = INF;
            });
        }

        //$html = view('ap.ajax.invoice_reference_list')->with('invRefList', $invRefList)->render();
        $html = view('ap.ajax.invoice_reference_tax_pay_list')->with(compact('invRefTaxPayList', 'taxPayQueueInvId'))->render();

        $jsonArray = [
            'html' => $html
        ];

        return response()->json($jsonArray);
    }

    public function vendorList(Request $request)
    {
        $vendorType = $request->post('vendorType');
        $vendorCategory = $request->post('vendorCategory');
        $vendorName = $request->post('vendorName');
        $vendorShortName = $request->post('vendorShortName');

        /*$counter = 0;
        if (isset($vendorType)) {
            $counter++;
        }
        if (isset($vendorCategory)) {
            $counter++;
        }
        if (isset($vendorName)) {
            $counter++;
        }
        if (isset($vendorShortName)) {
            $counter++;
        }*/
        $data = [];
        /* if ($counter > 0) {*/
        $data = FasApVendors::where('vendor_type_id', '=', DB::raw("NVL('" . $vendorType . "',vendor_type_id)"))
            ->where('workflow_approval_status', '=', ApprovalStatus::APPROVED)
            ->where('inactive_yn', '=', YesNoFlag::NO)      // Add Where Condition- Pavel-15-02-22
            ->where('vendor_category_id', '=', DB::raw("NVL('" . $vendorCategory . "',vendor_category_id)"))
            /*->where(function ($query) use($vendorName,$vendorShortName){
                $query->orWhere(DB::raw("upper(vendor_name)"), 'like', '%'. strtoupper($vendorName) .'%');
                $query->orWhere(DB::raw("upper(vendor_short_name)"), 'like', '%'. strtoupper($vendorShortName) .'%');
            })*/
            ->where(DB::raw("upper(vendor_name)"), 'like', '%' . strtoupper($vendorName) . '%')  //Add two Where Condition- Pavel-14-03-22
            ->where(function ($query) use ($vendorShortName) {
                $query->where(DB::raw('upper(fas_ap_vendors.vendor_short_name)'), 'like', strtoupper('%' . trim($vendorShortName) . '%'))
                    ->orWhere('vendor_short_name', '=', trim($vendorShortName))
                    ->orWhere('vendor_short_name', '=', DB::raw("NVL('" . $vendorShortName . "',vendor_name)"));
            })
            ->with(['vendor_type', 'vendor_category'])
            ->orderBy('vendor_id', 'asc')
            ->get();
        /*}*/


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
                return $data->address_line1 . ' ' . $data->address_line2;
            })
            ->editColumn('action', function ($data) {
                return "<button class='vendorSelect btn btn-sm btn-primary'  data-vendor='" . $data->vendor_id . "'  >Select</button>";
            })
            ->make(true);
    }

    public function getVendorWithOutstandingBalance(Request $request)
    {
        $vendorId = $request->post('vendorId');
        $vendor = DB::selectOne("Select CPAACC.fas_ap_trans.get_vendor_account_info(:p_vendor_id) from dual", ['p_vendor_id' => $vendorId]);
        $ledgerInfo = DB::select("Select CPAACC.fas_ap_trans.get_vendor_ledger_info(:p_vendor_id) from dual", ['p_vendor_id' => $vendorId]);

        $tbody = "";
        foreach ($ledgerInfo as $info) {
            $tbody .= "<tr><td>" . $info->gl_subsidiary_id . "</td><td>" . $info->gl_subsidiary_name . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue($info->opening_balance) . " " . $info->opening_balance_type . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue("$info->debit_summation") . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue("$info->credit_summation") . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue($info->account_balance) . " " . $info->account_balance_type . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue($info->unauth_debit_amount) . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue($info->unauth_credit_amount) . "</td><td class='text-right-align'>" . HelperClass::getCommaSeparatedValue($info->authorized_balance) . " " . $info->authorized_balance_type . "</td></tr>";
        }
        return response()->json(["vendor" => $vendor, "ledger" => $tbody,"ledger_info"=>$ledgerInfo]);
    }

    public function getVendorDetails(Request $request)
    {
        $vendorId = $request->post('vendorId');
        $vendorType = $request->post('vendorType');
        $vendorCategory = $request->post('vendorCategory');
        $vendor = FasApVendors::select('vendor_id', 'vendor_name', 'vendor_category_id')
            ->where('vendor_type_id', '=', DB::raw("NVL('" . $vendorType . "',vendor_type_id)"))
            ->where('vendor_category_id', '=', DB::raw("NVL('" . $vendorCategory . "',vendor_category_id)"))
            ->where(
                [
                    ['vendor_id', '=', $vendorId],          //Add Where Condition- Pavel-14-02-22
                    ['inactive_yn', '=', YesNoFlag::NO],
                    ['workflow_approval_status', '=', ApprovalStatus::APPROVED],
                ]
            )->with('vendor_category')->first();


        //For Tax, vat payable party fetch only for standard and credit memo
        $invoiceType = $request->post('invoiceType');
        $sourceAllowFlag = $request->post('dlSourceAllowFlag');

        $tax_payable = ' <option value="">Party Name for Tax Payable</option>';
        $vat_payable = ' <option value="">Party Name for Vat Payable</option>';
        /**
         * Tax, vat enable disable was dependent on Standard and Credit Memo
         * Now it depends on Ded_at_source_flag = 1/0 [1 = enable Tax,Vat; 0 = disable Tax,Vat] -Ref: 27-03-2022
         * **/
        /*        if (($invoiceType == LApInvoiceType::STANDARD) || ($invoiceType == LApInvoiceType::CREDIT_MEMO)){*/
        if ($sourceAllowFlag == '1') {
            $vat_parties = DB::select("Select CPAACC.fas_ap_trans.get_ap_tax_vat_party(:p_tax_vat_flag) from dual", ['p_tax_vat_flag' => 'VAT']);
            foreach ($vat_parties as $key => $party) {
                if ($key == 0) {
                    $vat_payable .= '<option selected value="' . $party->vendor_id . '">' . $party->vendor_name . '</option>';
                } else {
                    $vat_payable .= '<option value="' . $party->vendor_id . '">' . $party->vendor_name . '</option>';
                }
            }

            $tax_parties = DB::select("Select CPAACC.fas_ap_trans.get_ap_tax_vat_party(:p_tax_vat_flag) from dual", ['p_tax_vat_flag' => 'TAX']);
            foreach ($tax_parties as $key => $party) {
                if ($key == 0) {
                    $tax_payable .= '<option selected value="' . $party->vendor_id . '">' . $party->vendor_name . '</option>';
                } else {
                    $tax_payable .= '<option value="' . $party->vendor_id . '">' . $party->vendor_name . '</option>';
                }
            }
        }

        /*** Add this section Pavel: 21-03-22 ***/
        $vendor_balance = DB::selectOne("Select CPAACC.fas_ap_trans.get_vendor_account_info(:p_vendor_id) from dual", ['p_vendor_id' => $vendorId]);

        return response()->json(['vendor' => $vendor, 'taxParty' => $tax_payable, 'vatParty' => $vat_payable, 'vendorBalance' => $vendor_balance]);
    }


    /*** Start Add block  Pavel-07-07-22 ***/
    public function getAddVendorDetails(Request $request)
    {
        $gl_subsidiary_id = $request->post('glSubsidiaryId');
        $vendor_id = $request->post('vendorId', NULL);
        $customer_id = $request->post('customerId', NULL);

        $partyInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_party_account_info (:p_gl_subsidiary_id,:p_vendor_id,:p_customer_id) from dual",
            ['p_gl_subsidiary_id' => $gl_subsidiary_id, 'p_vendor_id' => $vendor_id, 'p_customer_id' => $customer_id]);

        return response()->json(['party_info' => $partyInfo]);
    }

    /*** End Add block  Pavel-07-07-22 ***/

    public function getBankAccountDetailsOnGlType(Request $request)
    {
        $accountId = $request->post('accId');
        $allowedGlType = $request->post('allowedGlType');
        $partySubLedgers = '';  //Add-Pavel- 07-07-22
        $partyInfo = null;      //Add-Pavel- 07-07-22

        $account = GlCoa::where('gl_acc_id', '=', $accountId)->whereIn('gl_type_id', $allowedGlType)->first();
        if (isset($account)) {
            $bankAccountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual", ['p_gl_acc_id' => $accountId]);
        } else {
            $bankAccountInfo = [];
        }

        /*** Start Add block  Pavel-07-07-22 ***/
        /*** Include customers as like as journal Sujon -03-08-22 ***/
        if (isset($bankAccountInfo->module_id)) {
            $ledgers = DB::select("select CPAACC.fas_gl_trans.get_gl_subsidiary_ledger (:p_module_id,:p_gl_acc_id) from dual", ['p_module_id' => $bankAccountInfo->module_id, 'p_gl_acc_id' => $accountId]);

            foreach ($ledgers as $ledger) {
                $partySubLedgers .= '<option value="' . $ledger->gl_subsidiary_id . '" data-partyparams="' . $ledger->vendor_type_id . '#' . $ledger->vendor_category_id . '">' . $ledger->gl_subsidiary_name . '</option>';
                if ($accountId == GlAccountID::TDS_Tax_Deduction_At_Source_Payable || $accountId == GlAccountID::VDS_Vat_Deduction_At_Source_Payable) {
                    $partyInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_party_account_info (:p_gl_subsidiary_id,:p_vendor_id,:p_customer_id) from dual",
                        ['p_gl_subsidiary_id' => $ledger->gl_subsidiary_id, 'p_vendor_id' => $ledger->vendor_id, 'p_customer_id' => $ledger->customer_id]);
                }
            }
        }
        /*** Start Add block  Pavel-07-07-22 ***/

        //return response()->json($bankAccountInfo); //block sec -Pavel- 07-07-22
        return response()->json(['bankAccountInfo' => $bankAccountInfo, 'sub_ledgers' => $partySubLedgers, 'party_info' => $partyInfo]); //Add-Pavel- 07-07-22
    }

    public function coaListOnSearch(Request $request)
    {
        $glType = $request->post('glType');
        $costCenterDpt = $request->post('costCenterDpt'); //Add costCenterDpt Part :pavel-31-01-22
        $accNameCode = $request->post('searchText');

        /*$sql = GlCoa::select("*")->where('gl_type_id', '=', $glType);
        if (isset($search)) {
            $sql->where(function ($q) use ($search) {
                $q->Where(DB::raw('upper(gl_acc_name)'), 'like', '%' . strtoupper($search) . '%')
                    ->orWhere('gl_acc_id', 'like', '%' . $search . '%');
            });
        }
        $glCoaInfo = $sql->where('postable_yn', '=', 'Y')->get();*/


        $glCoaInfo = $this->glCoa->with(['cost_center_dep'])->where(
            [
                /**
                 * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID) REF# email
                 * Made glType optional.
                 * Logic added:04-04-2022**/
                ['gl_type_id', '=', DB::raw("NVL('" . $glType . "',gl_type_id)")],
                ['postable_yn', '=', YesNoFlag::YES],
                ['inactive_yn', '=', YesNoFlag::NO],   //Add Condition- Pavel-14-02-22
            ]
        )->where(function ($query) use ($costCenterDpt) {       //Add costCenterDpt Part :pavel-31-01-22
            $query->where('cost_center_dept_id', $costCenterDpt)
                ->orWhereNull('cost_center_dept_id');
        })->where(function ($query) use ($accNameCode) {
            $query->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%' . trim($accNameCode) . '%'))
                ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                //->orWhere( 'gl_acc_id', '=', trim($accNameCode) );
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_coa_code)'), '=', trim($accNameCode))      //Add two condition Part :pavel-14-03-2022
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_sub_code)'), '=', trim($accNameCode));
        })->orderBy('gl_acc_id', 'asc')->get();

        return datatables()->of($glCoaInfo)
            ->addIndexColumn()
            ->editColumn('dept_name', function ($data) {
                //$dept = (isset($data->cost_center_dep) ? $data->cost_center_dep->cost_center_dept_name : 'No Data Found');
                $dept = (isset($data->cost_center_dep) ? $data->cost_center_dep->cost_center_dept_name : '');
                return $dept;
            })
            ->editColumn('action', function ($data) use ($request) {

                return "<button  class='btn btn-sm btn-primary'  onclick='" . ($request->has('callbackType') ? __("getAccountDetail(" . $data->gl_acc_id . "," . $request->post('callbackType') . "," . $request->post('allowedGL') . "," . $request->post('callback') . ")") : "getAccountDetail($data->gl_acc_id)") . "'  >Select</button>";
            })
            ->make(true);
    }

    public function getPoDetail(Request $request)
    {
        $poNumber = $request->get('poNumber');

        if (isset($poNumber)) {
            $poMaster = $this->purchaseRcvMst
                ->where(["po_number" => $poNumber, "approval_status_id" => '1', "invoice_action_id" => null])
                ->with("purchase_rcv_dtl", "vendor", "vendor_sites")
                ->toSql();
            if ($poMaster->count() > 0) {
                $purchaseDetail = $poMaster[0]->purchase_rcv_dtl;
                return response()->json(['content' => view('ap.invoice.lineOnPO', compact(['purchaseDetail', 'itemType']))->render(), 'data' => $poMaster]);
            } else {
                $purchaseDetail = [];
                return response()->json(['content' => view('ap.invoice.lineWithoutPO', compact(['itemType']))->render(), 'data' => []]);
            }
        } else {
            return response()->json(['content' => view('ap.invoice.lineWithoutPO', compact(['itemType']))->render(), 'data' => []]);
        }
    }

    public function getInvoiceTypesOnSubsidiary(Request $request)
    {
        $subsidiaryId = $request->get('subsidiaryId');
        $invoiceTypes = FasApInvoiceParams::where('gl_subsidiary_id', '=', $subsidiaryId)->with('invoice_type')->get();
        $html = "<option value=''>&lt;Select&gt;</option>";
        foreach ($invoiceTypes as $type) {
            $html .= "<option value='" . $type->invoice_type_id . "' data-invoiceparams='" . $type->dr_cr_flag . "#" . $type->vendor_type_id . "#" . $type->vendor_category_id . "#" . $type->ded_at_source_allow_flag . "#" . $type->distrib_line_gl_sub_flag . "#" . $type->budget_head_required_yn . "'>" . $type->invoice_type->invoice_type_name . "</option>";
        }
        return response()->json($html);
    }

    public function getBillPayWiseOnSubsidiary(Request $request)
    {
        $intBillPayYn = $request->get('intBillPayYn');
        $partySubLedgerList = $this->apLookUpManager->getPartySubLedger(ApFunType::AP_INVOICE_BILL_PAYMENT, $intBillPayYn);
        $html = "<option value=''>&lt;Select&gt;</option>";
        foreach ($partySubLedgerList as $value) {
            $html .= "<option value='" . $value->gl_subsidiary_id . "' >" . $value->gl_subsidiary_name . "</option>";
        }
        return response()->json($html);
    }

    /*public function getClearingDetail(Request $request, $id, $funcType)
    {
        $response_code = '99';
        $response_data = [];
        $response = DB::selectOne('select cpaacc.fas_cm_trans.get_clearing_recon_view(:p_function_id,:p_clearing_id) from dual', ['p_function_id'=>$funcType,'p_clearing_id' => $id]);
        $authorize_view = "";
        if (isset($response)) {
            $response_code = '1';
            $response_data = $response;
            $response->clearing_date = HelperClass::dateConvert($response->clearing_date);
            $response->instrument_date = HelperClass::dateConvert($response->instrument_date);
            $response->trans_date = HelperClass::dateConvert($response->trans_date);
        }

        if (($request->get('wkf_map_id') != '') && ($request->get('wkf_user_id') != '') && ($request->get('wk_ref_status') != '')){
            $wkMapId = $request->get('wk_map_id');
            $userId = $request->get('wkf_user_id');
            $wkRefStatus = $request->get('wk_ref_status');

            $empInfo = User::with(['employee'])->where('user_id',$userId)->first();

            $wkMapInfo = WorkFlowMapping::where('workflow_mapping_id',$wkMapId)->first();
            $authorize_view = view("ap.ap-common.common_authorizer",compact('wkMapInfo', 'wkRefStatus','empInfo'))->render();

            $response_data->authorize_view = $authorize_view;
        }

        return response()->json(['response_code' => $response_code, 'response_data' => $response_data]);
    }*/

    public function poList(Request $request)
    {
        $vendorId = $request->post('vendor');
        $orderNo = $request->post('purchaseOrder');
        $orderDate = HelperClass::dateFormatForDB($request->post('purchaseDate'));
        $invoiceNo = $request->post('invoiceNo');
        $invoiceDate = HelperClass::dateFormatForDB($request->post('invoiceDate'));
        $data = [];
        $response = DB::select("select cpaacc.fas_ap_trans.get_goods_received_info(:p_vendor_id,:p_purchase_order_no,:p_purchase_order_date,:p_invoice_no,:p_invoice_date) from dual",
            ["p_vendor_id" => $vendorId,
                "p_purchase_order_no" => $orderNo,
                "p_purchase_order_date" => HelperClass::dateFormatForDB($orderDate),
                "p_invoice_no" => $invoiceNo,
                "p_invoice_date" => HelperClass::dateFormatForDB($invoiceDate)]);
        $data = isset($response) ? $response : [];


        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('po_date', function ($data) {
                return HelperClass::dateConvert($data->po_date);
            })
            ->editColumn('invoice_date', function ($data) {
                return HelperClass::dateConvert($data->invoice_date);
            })
            ->editColumn('action', function ($data) {
                return "<button style='text-decoration:underline; color:#5A8DEE' class='poSelect btn btn-dark'  data-po='" . $data->purchase_rcv_mst_id . "#" . $data->po_number . "#" . HelperClass::dateConvert($data->po_date) . "#" . $data->invoice_no . "#" . HelperClass::dateConvert($data->invoice_date) . "#" . $data->invoice_amount . "'  >Select</button>";
            })
            ->make(true);
    }

    function vendorCategories(Request $request)
    {
        $vendorType = $request->get('vendorTpe');
        $category = $request->get('preCategoryId');
        $html = "<option value=''>&lt;Select&gt;</option>";
        $data = $this->apLookUpManager->getVendorCategoryOnType($vendorType);
        if (isset($data)) {
            foreach ($data as $d) {
                $html .= "<option value='" . $d->vendor_category_id . "'";
                if (isset($category) && ($category == $d->vendor_category_id)) {
                    $html .= "selected";
                }
                $html .= ">$d->vendor_category_name</option>";
            }
        }

        return response()->json($html);
    }

    public function budgetBookDetailInfo(Request $request)
    {
        /*** Block this sec start -Pavel: 24-03-22 ***/
        /*$budget_id = $request->get('budget_booking_id');
        $data = $this->apLookUpManager->getBudgetHeadDetailInfo($budget_id);
        if (isset($data)) {
            $data->budget_booking_date = HelperClass::dateConvert($data->budget_booking_date);
        }
        */
        /*** Block this sec end -Pavel: 24-03-22 ***/

        /*** Add this sec start -Pavel: 24-03-22 ***/
        $budget_id = $request->get('budget_head_id');
        $department = $request->get('department');
        $calendar = $request->get('calendar');

        $data = $this->apLookUpManager->getBudgetHeadDetailInfo($calendar, $department, $budget_id);

        /*** Add this sec end -Pavel: 24-03-22 ***/


        return response()->json(['data' => $data]);
    }

    public function budgetHeadDatalist(Request $request)
    {
        $department = $request->post('department');
        $calendar = $request->post('calendar');
        $vendorId = $request->post('vendorId');
        $nameCode = $request->post('nameCode');
        $data = [];

        if (isset($department) && isset($calendar) /*&& isset($nameCode)*/) {
            $data = $this->apLookUpManager->getBudgetBookingHeadList($calendar, $department, $nameCode);
        }

        return DataTables()->of($data)
            ->editColumn('budget_head_id', function ($data) {
                return $data->head_id;
            })
            ->editColumn('budget_head_name', function ($data) {
                return $data->head_name;
            })
            ->editColumn('sub_category', function ($data) {
                return $data->sub_category_name;
            })
            ->editColumn('category_name', function ($data) {
                return $data->category_name;
            })
            ->editColumn('budget_type', function ($data) {
                return $data->type_name;
            })
            ->editColumn('action', function ($data) use ($department, $calendar) {
                return '<button data-headid="' . $data->head_id . '" class="budgetHeadSelect btn btn-sm btn-outline-dark">View</button>';
            })
            ->make(true);
    }

    public function budgetBookingDatalist(Request $request)
    {
        $headId = $request->post('budget_head_id');
        $department = $request->post('department');
        $calendar = $request->post('calendar');
        $vendor = $request->post('vendorId');
        $data = [];

        /*** Block this method  -Pavel: 24-03-22 ***/
        /*if (isset($department) && isset($calendar) && isset($vendor)) {

            $data = $this->apLookUpManager->getBudgetBookingTransList($calendar, $department,$headId, $vendor);
        }*/

        if (isset($department) && isset($calendar)) {
            /*** Add this method  -Pavel: 24-03-22 ***/
            $data = $this->apLookUpManager->getBudgetBookingTransList($calendar, $department);

        }

        return DataTables()->of($data)
            /*** Block this sec start  -Pavel: 24-03-22 ***/
            /*->editColumn('booking_id', function ($data) {
                return $data->budget_booking_id;
            })
            ->editColumn('booking_date', function ($data) {
                return HelperClass::dateConvert($data->budget_booking_date);
            })*/
            /*** Block this sec end  -Pavel: 24-03-22 ***/
            /*->editColumn('tender_no', function ($data) {
                return $data->tender_proposal_no;
            })
            ->editColumn('tender_date', function ($data) {
                return HelperClass::dateConvert($data->tender_proposal_date);
            })
            ->editColumn('subject', function ($data) {
                return $data->contract_subject;
            })
            ->editColumn('balance', function ($data) {
                return $data->budget_booking_amount;
            })*/
            /*** Block this sec start  -Pavel: 24-03-22 ***/
            /*->editColumn('action', function ($data) {
                return '<button data-bookingid="' . $data->budget_booking_id . '" class="budgetSelect btn btn-sm btn-outline-dark">Select</button>';
            })*/
            /*** Block this sec end -Pavel: 24-03-22 ***/
            ->editColumn('category_name', function ($data) {
                return $data->budget_category_name;
            })
            ->editColumn('budget_type', function ($data) {
                return $data->budget_type_name;
            })
            /*** Add this sec -Pavel: 24-03-22 ***/
            ->editColumn('action', function ($data) {
                return '<button data-budget-head-id="' . $data->budget_head_id . '" class="budgetSelect btn btn-sm btn-primary">Select</button>';
            })
            ->make(true);
    }

    /************************** -Report methods- ******************************************/
    public function reportGlAccounts(Request $request)
    {
        return DB::select('select CPAACC.FAS_REPORT_CONTROL.get_gl_account_name(:p_search_value) from dual', ['p_search_value' => $request->get('term')]);
    }

    public function reportGlFiscalYears(Request $request)
    {
        return DB::select('select CPAACC.FAS_REPORT_CONTROL.get_financial_years() from dual');
    }

    public function reportGlPostingPeriods(Request $request, $isNullOption = false)
    {

        $fromPeriods = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_posting_periods(:p_fiscal_year_id) from dual', ['p_fiscal_year_id' => $request->get("fiscal_year_id")]);
        $fromOptions = ($isNullOption == "true") ? '<option value="">Select Period</option>' : '';

        foreach ($fromPeriods as $period) {
            $selected = "";
            /*if ($period->posting_period_status == 'O') {
                $selected = "selected";
            }*/
            $fromOptions .= '<option ' . $selected . ' data-mindate="' . HelperClass::dateConvert($period->posting_period_beg_date) . '"
                                        data-maxdate="' . HelperClass::dateConvert($period->posting_period_end_date) . '"
                                         value="' . $period->posting_period_id . '">' . $period->posting_period_name . ' </option>';
        }

        $toPeriods = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_posting_periods(:p_fiscal_year_id) from dual', ['p_fiscal_year_id' => $request->get("fiscal_year_id")]);
        $toOptions = '';
        foreach ($toPeriods as $period) {

            $toOptions .= '<option data-mindate="' . HelperClass::dateConvert($period->posting_period_beg_date) . '"
                                        data-maxdate="' . HelperClass::dateConvert($period->posting_period_end_date) . '"
                                         value="' . $period->posting_period_id . '">' . $period->posting_period_name . ' </option>';
        }

        return response()->json(['fromOptions' => $fromOptions, 'toOptions' => $toOptions]);
    }
    public function reportApVendors(Request $request, $subsidiaryId)
    {
        $search = $request->get('term', '');

        $vendors = DB::select('select CPAACC.FAS_REPORT_CONTROL.get_ap_vendors(:p_gl_subsidiary_id,:p_search_value) from dual', ['p_gl_subsidiary_id' => $subsidiaryId, 'p_search_value' => $search]);
        /*$options = '';
        foreach ($vendors as $vendor) {
            $options .= '<option value="' . $vendor->vendor_id . '">' . $vendor->vendor_name . ' </option>';
        }*/
        return response()->json($vendors);
    }

    public function vendorWiseVatTaxInfo(Request $request)
    {
        $vendorWiseVatTaxInfo = '';
        $taxVatFlag = ($request->get('party_sub_ledger_id') == GlSubsidiaryParams::TAX_PAYABLE) ? GlSubsidiaryParams::TAX : GlSubsidiaryParams::VAT;

        $vendorWiseVatTaxInfo = DB::selectOne("select cpaacc.fas_ap_trans.get_ap_tax_vat_vendor_info (:p_tax_vat_flag) from dual", ['p_tax_vat_flag' => $taxVatFlag]);
        return response()->json($vendorWiseVatTaxInfo);
    }

    public function getRegisterDetail($id)
    {
        return LBillRegister::where('bill_reg_id', '=', $id)->first();
    }

    /******** Added on: 26/10/2022, PAVEL **********/
    public function getPaymentFavoringInfo(Request $request)
    {
        $postData = $request->post();
        $glSubsidiaryId = isset($postData['partySubLedgerId']) ? $postData['partySubLedgerId'] : '';
        $vendorId = isset($postData['vendorId']) ? $postData['vendorId'] : '';

        $paymentFavoringInfo = DB::selectOne("Select fas_ap_trans.get_ap_payment_favoring_info(:p_gl_subsidiary_id,:p_vendor_id) from dual", ['p_gl_subsidiary_id' => $glSubsidiaryId, 'p_vendor_id' => $vendorId]);

        return response()->json($paymentFavoringInfo);
    }
}

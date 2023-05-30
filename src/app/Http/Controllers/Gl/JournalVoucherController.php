<?php
/**
 *Created by PhpStorm
 *Created at ২৫/৫/২১ ৩:৪২ PM
 */

namespace App\Http\Controllers\Gl;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\Ar\ArFunType;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\Common\DebitCredit;
use App\Enums\Common\GlSubsidiaryParams;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LTransAmtType;
use App\Enums\Gl\FunctionTypes;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ar\ArLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalVoucherController extends Controller
{
    private $lookupManager;
    private $glManager;
    private $glCoa;
    private $glCoaParam;
    private $apLookupManager;
    private $arLookupManager;


    public function __construct(LookupContract $lookupManager, GlContract $glManager, ApLookupContract $apLookupManager,
                                ArLookupManager $arLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glManager = $glManager;
        $this->glCoa = new GlCoa();
        $this->glCoaParam = new GlCoaParams();
        $this->apLookupManager = $apLookupManager;
        $this->arLookupManager = $arLookupManager;
    }

    public function index()
    {
        $user_id = auth()->id();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $funcType = $this->lookupManager->getIntegrationFunType(LGlInteFun::JOURNAL_VOUCHER, LGlInteModules::FIN_ACC_GENE_LEDGER);
        $billSecs = $this->lookupManager->getBillSections(BmnFunctionType::BUDGET_BOOKING);
        $lastGlTranMst = $this->glManager->findLastGlTranMst(LGlInteFun::JOURNAL_VOUCHER, $user_id);
        $accountType = $this->glCoaParam->get();
        $arSubsidiaryType = $this->arLookupManager->getArPartySubLedger(ArFunType::AR_INVOICE_BILL_ENTRY);
        $apSubsidiaryType = $this->apLookupManager->getPartySubLedger(ApFunType::AP_INVOICE_BILL_ENTRY);
        $customerCategory = $this->arLookupManager->getCustomerCategory();
        $vendorCategory = $this->apLookupManager->getVendorCategory();
        $vendorType = $this->apLookupManager->getVendorTypes();
        $isRequired = [
            'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
        ];
        return view('gl.journal-voucher.index', compact('isRequired','apSubsidiaryType', 'customerCategory', 'vendorCategory', 'vendorType', 'arSubsidiaryType', 'accountType', 'fiscalYear', 'department', 'funcType', 'billSecs', 'lastGlTranMst'));
    }

    public function creditBankAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');

        /*$sql = $this->glCoa->where('gl_type_id', '=', $glType)->where("postable_yn", "=", "Y");
        if (isset($accNameCode)) {
            $sql->where(DB::raw('upper(gl_acc_name)'), 'like', '%' . strtoupper($accNameCode) . '%')
                ->orWhere('gl_acc_code', 'like', '%' . $accNameCode . '%')
                ->orWhere('gl_acc_id', 'like', '%' . $accNameCode . '%');
        }
        $creditBankAccounts = $sql->get();*/

        $creditBankAccounts = $this->glCoa->where(
            [
                /**
                 * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID) REF# email
                 * Made glType optional.
                 * Logic added:04-04-2022**/
                ['gl_type_id', '=', DB::raw("NVL('" . $glType . "',gl_type_id)")],
                ['postable_yn', '=', YesNoFlag::YES],
                ['inactive_yn', '=', YesNoFlag::NO], //Add Condition- Pavel-14-02-22
            ]
        )->where(function ($query) use ($accNameCode) {
            $query->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%' . trim($accNameCode) . '%'))
                ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                //->orWhere('fas_gl_coa.gl_acc_code', '=', trim($accNameCode) );
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_coa_code)'), '=', trim($accNameCode))     //Add two condition Part :pavel-14-03-2022
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_sub_code)'), '=', trim($accNameCode));
        })->orderBy('gl_acc_id','asc')->get();

        return datatables()->of($creditBankAccounts)
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
            ->editColumn('action', function ($data) {
                return "<button class='btn btn-sm btn-primary' onclick='c_getAccountDetail($data->gl_acc_id)' >Select</button>";
            })
            ->make(true);
    }

    public function debitBankAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');

        /*$sql = $this->glCoa->where('gl_type_id', '=', $glType);
        if (isset($accNameCode)) {
            $sql->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%'.trim($accNameCode).'%'))
                ->orWhere(DB::raw ('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode) )
                ->orWhere('fas_gl_coa.gl_acc_code', '=', trim($accNameCode) );
        }
        $creditBankAccounts = $sql->where("postable_yn", "=", "Y")->get();*/

        $debitBankAccounts = $this->glCoa->where(
            [
                /**
                 * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID) REF# email
                 * Made glType optional.
                 * Logic added:04-04-2022**/
                ['gl_type_id', '=', DB::raw("NVL('" . $glType . "',gl_type_id)")],
                ['postable_yn', '=', YesNoFlag::YES],
                ['inactive_yn', '=', YesNoFlag::NO],   //Add Condition- Pavel-14-02-22
            ]
        )->where(function ($query) use ($accNameCode) {
            $query->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%' . trim($accNameCode) . '%'))
                ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                //->orWhere('fas_gl_coa.gl_acc_code', '=', trim($accNameCode) );
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_coa_code)'), '=', trim($accNameCode))     //Add two condition Part :pavel-14-03-2022
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_sub_code)'), '=', trim($accNameCode));
        })->orderBy('gl_acc_id','asc')->get();

        return datatables()->of($debitBankAccounts)
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
            ->editColumn('action', function ($data) {
                return "<button class='btn btn-sm btn-primary' onclick='d_getAccountDetail($data->gl_acc_id)' >Select</button>";
            })
            ->make(true);
    }

    public function store(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            $trans_mst_id = null;
            $o_trans_batch_id = null;
            $o_document_no = sprintf("%4000s", "");
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_trans_master_id' => [
                    'value' => &$trans_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_module_id' => LGlInteModules::FIN_ACC_GENE_LEDGER,
                'p_function_id' => $request->post('function_type'),
                'p_department_id' => $request->post('department'),
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_trans_period_id' => $request->post('period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => $request->post('document_number'),
                'p_document_ref' => $request->post('document_reference'),
                'p_narration' => $request->post('narration'),
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => 'N',
                /*0003216: FAS TRANSACTIONS: CHANGE OUTPUT PARAMETERS*/
                'o_trans_batch_id' => [
                    'value' => &$o_trans_batch_id,
                    'type' => \PDO::PARAM_INT
                ],
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_master_make', $params);

            if ($params['o_status_code'] == "99") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {
                $o_trans_mst_id = $params['p_trans_master_id']['value'];
                if ($o_trans_mst_id) {
                    foreach ($request->post("line") as $key => $line) {
                        if ($line['action_type'] == "A") {
                            ${"debit_status_code" . $key} = sprintf("%4000s", "");
                            ${"debit_status_message.$key"} = sprintf("%4000s", "");

                            ${"dtlDebitParams.$key"} = [
                                'p_action_type' => ProActionType::INSERT,
                                'p_trans_detail_id' => NULL,
                                'p_trans_master_id' => $o_trans_mst_id,
                                'p_gl_acc_id' => $line['account_code'],
                                'p_dr_cr' => $line['dr_cr'],
                                'p_currency_code' => $line['currency'],
                                'p_exchange_rate' => $line['exchange_rate'],
                                'p_amount_type_id' => LTransAmtType::GENERAL_OTHERS,
                                'p_amount_ccy' => $line['amount_ccy'],
                                'p_amount_lcy' => ($line['dr_cr'] == DebitCredit::DEBIT) ? $line['debit_amount'] : $line['credit_amount'],
                                //'p_narration' => $line['d_narration'],
                                'p_without_cheque_yn' => NULL,
                                'p_cheque_no' => NULL,
                                'p_cheque_date' => NULL,
                                'p_without_challan_yn' => NULL,
                                'p_challan_type_id' => NULL,
                                'p_challan_no' => NULL,
                                'p_challan_date' => NULL,
                                'p_gl_subsidery_id' => isset($line['module_id']) ? $line['party_sub_ledger'] : NULL,
                                'p_customer_id' => isset($line['module_id']) ? (($line['module_id'] == LGlInteModules::ACCOUNT_RECEIVABLE) ? $line['party_id'] : NULL) : NULL,
                                'p_vendor_id' => isset($line['module_id']) ? (($line['module_id'] == LGlInteModules::ACC_PAY_VENDOR) ? $line['party_id'] : NULL) : NULL,
                                'p_emp_id' => NULL,
                                'p_budget_dept_id' => isset($line['budget_dept_id']) ? $line['budget_dept_id'] : NULL,
                                'p_budget_head_id' => $line['budget_head'],
                                'p_user_id' => auth()->id(),
                                'o_status_code' => &${"debit_status_code" . $key},
                                'o_status_message' => &${"debit_status_message.$key"},

                            ];

                            DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_detail_make', ${"dtlDebitParams.$key"});
                            if (${"debit_status_code" . $key} == "99") {
                                DB::rollBack();
                                return response()->json(["response_code" => ${"debit_status_code" . $key}, "response_msg" => ${"debit_status_message.$key"}]);
                            }
                        }
                    }

                    if (!is_null($request->file('attachment'))) {
                        $files = $request->file('attachment');
                        $descriptions = $request->post('attachment');
                        foreach ($files as $key => $file) {
                            if ($file['file'] && $descriptions[$key]['actionType'] == "I") {

                                $byteCode = base64_encode(file_get_contents($file['file']->getRealPath()));
                                $fileExt = $file['file']->extension();
                                $fileName = $file['file']->getClientOriginalName();

                                $fileId = "";
                                ${"file_status_code" . $key} = sprintf("%4000s", "");
                                ${"file_status_message" . $key} = sprintf("%4000s", "");

                                ${"file_params" . $key} = [
                                    'p_action_type' => 'I',
                                    'p_trans_master_id' => $o_trans_mst_id,
                                    'p_trans_doc_file_id' => [
                                        "value" => &$fileId,
                                        "type" => \PDO::PARAM_INPUT_OUTPUT,
                                        "length" => 256
                                    ],
                                    'p_trans_doc_file_name' => $fileName,
                                    'p_trans_doc_file_name_bng' => "",
                                    'p_trans_doc_file_desc' => $descriptions[$key]["description"],
                                    'p_trans_doc_file_type' => $fileExt,
                                    'p_trans_doc_file_content' => [
                                        "value" => $byteCode,
                                        "type" => SQLT_CLOB
                                    ],
                                    'p_user_id' => auth()->id(),
                                    'o_status_code' => &${"file_status_code" . $key},
                                    'o_status_message' => &${"file_status_message" . $key}
                                ];
                                DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_docs_attach', ${"file_params" . $key});

                                if (${"file_status_code" . $key} == "99") {
                                    DB::rollBack();
                                    return response()->json(["response_code" => ${"file_status_code" . $key}, "response_msg" => ${"file_status_message" . $key}]);
                                }
                            }
                        }
                    }

                    if ($o_trans_mst_id) {
                        $validate_trans_status_code = sprintf("%4000s", "");
                        $validate_trans_status_message = sprintf("%4000s", "");

                        $validateTransParams = [
                            'p_trans_master_id' => $o_trans_mst_id,
                            'o_status_code' => &$validate_trans_status_code,
                            'o_status_message' => &$validate_trans_status_message,
                        ];

                        DB::executeProcedure('CPAACC.fas_gl_trans.validate_transaction_batch', $validateTransParams);

                        if ($validateTransParams['o_status_code'] != 1) {
                            DB::rollBack();
                            return response()->json(["response_code" => $validate_trans_status_code, "response_msg" => $validate_trans_status_message]);
                        }
                    }

                    if ($o_trans_mst_id) {
                        $wk_mapping_status_code = sprintf("%4000s", "");
                        $wk_mapping_status_message = sprintf("%4000s", "");

                        $wkMappingParams = [
                            'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::GL_JOURNAL_VOUCHER_APPROVAL,
                            'P_REFERENCE_TABLE' => WkReferenceTable::FAS_GL_TRANS_MASTER,
                            'P_REFERANCE_KEY' => WkReferenceColumn::TRANS_MASTER_ID,
                            'P_REFERANCE_ID' => $o_trans_mst_id,
                            'P_TRANS_PERIOD_ID' => $request->post('period'),
                            'P_INSERT_BY' => auth()->id(),
                            'o_status_code' => &$wk_mapping_status_code,
                            'o_status_message' => &$wk_mapping_status_message,
                        ];


                        DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);
                        /*if ($wkMappingParams['o_status_code'] != 1) {
                            DB::rollBack();
                            return $wkMappingParams;
                        }*/
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => "99", "response_msg" => $e->getMessage()]);
        }
        DB::commit();
        return response()->json(["response_code" => $status_code, "response_msg" => $status_message, "o_batch" => $o_trans_batch_id, "o_document_no" => $o_document_no, "period" => $params['p_trans_period_id']]);
    }

    public function bankAccDatatable(Request $request)
    {
        $glType = $request->post('glType');
        $accNameCode = $request->post('accNameCode');

        $creditBankAccounts = $this->glCoa->where(
            [
                /**
                 * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID) REF# email
                 * Made glType optional.
                 * Logic added:04-04-2022**/
                ['gl_type_id', '=', DB::raw("NVL('" . $glType . "',gl_type_id)")],
                ['postable_yn', '=', YesNoFlag::YES],
                ['inactive_yn', '=', YesNoFlag::NO], //Add Condition- Pavel-14-02-22
            ]
        )->where(function ($query) use ($accNameCode) {
            $query->where(DB::raw('upper(fas_gl_coa.gl_acc_name)'), 'like', strtoupper('%' . trim($accNameCode) . '%'))
                ->orWhere(DB::raw('to_char(fas_gl_coa.gl_acc_id)'), '=', trim($accNameCode))
                //->orWhere('fas_gl_coa.gl_acc_code', '=', trim($accNameCode) );
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_coa_code)'), '=', trim($accNameCode))     //Add two condition Part :pavel-14-03-2022
                ->orWhere(DB::raw('to_char(fas_gl_coa.old_sub_code)'), '=', trim($accNameCode));
        })->orderBy('gl_acc_id','asc')->get();

        return datatables()->of($creditBankAccounts)
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
            ->editColumn('action', function ($data) {
                return "<button class='btn btn-sm btn-primary' onclick='getAccountDetail($data->gl_acc_id)' >Select</button>";
            })
            ->make(true);
    }

}

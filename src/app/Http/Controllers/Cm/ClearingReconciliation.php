<?php
/**
 *Created by PhpStorm
 *Created at ২৬/৯/২১ ১২:০০ PM
 */

namespace App\Http\Controllers\Cm;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Cm\CmLookupContract;
use App\Entities\Ap\FasApInvoice;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\Cm\CmFunType;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Cm\CmLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClearingReconciliation extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;
    protected $invoice;
    private $currency;
    private $attachment;

    /** @var ApLookupManager */
    private $apLookupManager;

    /** @var CmLookupManager */
    private $cmLookupManager;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager, CmLookupContract $cmLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->cmLookupManager = $cmLookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->invoice = new FasApInvoice();
        $this->currency = new LCurrency();
        $this->attachment = new FasApInvoiceDoc();
    }

    public function index()
    {
        $user_id = auth()->id();
        //$data['department'] = $this->lookupManager->findVwDepartment();
        //$data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        //$data['billSecs'] = $this->lookupManager->findLBillSec();
        //$data['vendorType'] = $this->apLookupManager->getVendorTypes();
        //$data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        //$data['invoiceStatus'] = $this->apLookupManager->getInvoiceStatus();
        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $data['functionType'] = $this->cmLookupManager->getFunctionType();
        $data['bank'] = $this->cmLookupManager->getBankAcc();
        $data['lastPostingBatch'] = $this->lookupManager->findLastPostingBatchId(LGlInteModules::CASH_MANAGEMENT, CmFunType::CM_CLEARING_CHQ_RECONCILIATION, $user_id);
        return view('cm.clearing-reconciliation.index', compact('data'));
    }

    public function dataList(Request $request)
    {
        $functionType = $request->post('function_type');
        $bank = $request->post('ap_bank_account');
        //$period = $request->post('period');

        $data = DB::select("select cpaacc.fas_cm_trans.get_clearing_recon_queue_list(:p_function_id ,:p_bank_account_id) from dual", ["p_function_id" => $functionType, "p_bank_account_id" => $bank]);
        return datatables()->of($data)
            ->editColumn('select', function ($data) {
                return '<div class="form-check"> <input class="form-check-input selectToReconcile" type="checkbox" value="" name="" id=""><input name="reconcile_id" value="' . $data->clearing_id . '" class="reconcileId" type="hidden"></div>';
            })
            ->editColumn('instrument_date', function ($data) {
                return HelperClass::dateConvert($data->instrument_date);
            })
            ->editColumn('trans_date', function ($data) {
                return HelperClass::dateConvert($data->trans_date);
            })
            ->editColumn('clearing_date', function ($data) {
                return HelperClass::dateConvert($data->clearing_date);
            })
            ->editColumn('action', function ($data) use ($functionType) {
                return "<button style='text-decoration:underline' class='clear_edit' data-clearing='$data->clearing_id' data-functiontype='$functionType' ><i class='bx bx-edit-alt'></i></button>";
            })
            ->rawColumns(['action', 'select'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $clearings = $request->post('selected_reconciles');
            $o_document_no = sprintf("%4000s", "");

            foreach ($clearings as $clearing_id) {
                $status_code = sprintf("%4000d", "");
                $status_message = sprintf("%4000s", "");
                $trans_batch_id = sprintf("%4000s", "");

                $params = [
                    'p_clearing_id' => $clearing_id,
                    'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                    'p_clearing_date' => HelperClass::dateFormatForDB($request->post('clearing_date')),
                    'p_user_id' => auth()->id(),
                    'o_trans_batch_id' => &$trans_batch_id,
                    'o_document_no' => &$o_document_no,
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message
                ];

                DB::executeProcedure('CPAACC.fas_cm_trans.clearing_reconciliation_make', $params);

                if ($params['o_status_code'] != "1") {
                    DB::rollBack();
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                } else {
                    if ($clearing_id) {
                        $wk_mapping_status_code = sprintf("%4000s", "");
                        $wk_mapping_status_message = sprintf("%4000s", "");

                        $wkMappingParams = [
                            'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AP_CLEARING_RECONCILIATION_APPROVAL,
                            'P_REFERENCE_TABLE' => WkReferenceTable::FAS_CM_CLEARING,
                            'P_REFERANCE_KEY' => WkReferenceColumn::CLEARING_ID,
                            'P_REFERANCE_ID' => $clearing_id,
                            'P_TRANS_PERIOD_ID' => $request->post('trans_period_id'),
                            'P_INSERT_BY' => auth()->id(),
                            'o_status_code' => &$wk_mapping_status_code,
                            'o_status_message' => &$wk_mapping_status_message,
                        ];

                        DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

                        if ($wkMappingParams['o_status_code'] != 1) {
                            DB::rollBack();
                            return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                        }
                    }

                }
            }
            DB::commit();
            return response()->json(["response_code" => $status_code, "response_msg" => $status_message,"o_document_no" => $o_document_no]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
    }
}

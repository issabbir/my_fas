<?php
/**
 *Created by PhpStorm
 *Created at ২৭/৯/২১ ৫:৫২ PM
 */

namespace App\Http\Controllers\Cm;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Cm\CmLookupContract;
use App\Entities\Ap\FasApInvoice;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Common\LGlInteModules;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Cm\CmLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ClearingReconciliationList extends Controller
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
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->invoice = new FasApInvoice();
        $this->currency = new LCurrency();
        $this->attachment = new FasApInvoiceDoc();
    }
    public function index()
    {
        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $data['fiscalYears'] = $this->lookupManager->getCurrentFinancialYear();
        $data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        /*$data['department'] = $this->lookupManager->findVwDepartment();
        $data['billSecs'] = $this->lookupManager->findLBillSec();
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['invoiceStatus'] = $this->apLookupManager->getInvoiceStatus();*/
        $data['bank'] = $this->cmLookupManager->getBankAcc();
        $data['functionType'] = $this->cmLookupManager->getFunctionType();

        return view('cm.clearing-reconciliation-list.index',compact('data'));
    }

    public function dataList(Request $request)
    {
        $functionType = $request->post('function_type');
        $bank =  $request->post('ap_bank_account', null);
        $period = $request->post('period', null);
        $approvalStatus = $request->post('approval_status', null);

        $data = DB::select("select cpaacc.fas_cm_trans.get_clearing_recon_make_list(:p_period,:p_function_id,:p_bank_account_id,:p_workflow_approval_status) from dual",["p_period"=>$period,"p_function_id"=>$functionType, "p_bank_account_id" => $bank,"p_workflow_approval_status"=>$approvalStatus]);

        return datatables()->of($data)
            ->editColumn('instrument_date', function ($data) {
                return HelperClass::dateConvert($data->instrument_date);
            })
            ->editColumn('amount', function ($data) {
                return HelperClass::getCommaSeparatedValue($data->amount);
            })
            ->editColumn('trans_date', function ($data) {
                return HelperClass::dateConvert($data->trans_date);
            })
            ->editColumn('clearing_date', function ($data) {
                return HelperClass::dateConvert($data->clearing_date);
            })
            ->editColumn('action', function ($data)  use ($functionType){
                return "<button style='text-decoration:underline' class='clear_edit' data-clearing='$data->clearing_id' data-functiontype='$functionType'><i class='bx bx-edit-alt'></i></button>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000d", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                'p_clearing_id' => $request->post('clearing_id'),
                'p_clearing_date' => HelperClass::dateFormatForDB($request->post('clearing_date')),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_cm_trans.clearing_reconciliation_update', $params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {
                DB::commit();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
    }
}

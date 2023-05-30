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
use App\Enums\ApprovalStatusView;
use App\Enums\Common\LGlInteModules;
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


class ClearingReconciliationAuthorizeController extends Controller
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
        //$data['department'] = $this->lookupManager->findVwDepartment();
        //$data['billSecs'] = $this->lookupManager->findLBillSec();
        //$data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        //$data['vendorType'] = $this->apLookupManager->getVendorTypes();
        // $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        //$data['invoiceStatus'] = $this->apLookupManager->getInvoiceStatus();
        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        $data['fiscalYears'] = $this->lookupManager->getCurrentFinancialYear();
        $data['postingDate'] = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $data['bank'] = $this->cmLookupManager->getBankAcc();
        $data['functionType'] = $this->cmLookupManager->getFunctionType();

        return view('cm.clearing-reconciliation-authorize.index', compact('data'));
    }

    public function searchClearingReconciliationAuthorize(Request $request)
    {
        $functionType = $request->post('function_type');
        $bank = $request->post('ap_bank_account', null);
        $period = $request->post('period', null);
        $approvalStatus = $request->post('approval_status', null);
        $user_id = auth()->id();

        $data = DB::select("select cpaacc.fas_cm_trans.get_clearing_recon_auth_list(:p_period,:p_function_id,:p_bank_account_id,:p_workflow_approval_status,:p_user_id) from dual",
            ['p_period' => $period, "p_function_id" => $functionType, "p_bank_account_id" => $bank, "p_workflow_approval_status" => $approvalStatus, "p_user_id" => $user_id]);

        return datatables()->of($data)
            ->editColumn('select', function ($data) use ($functionType) {
                return '<div class="form-check">
<input data-clearing="' . $data->clearing_id . '" data-mapid="' . $data->workflow_mapping_id . '" data-userid="' . $data->login_user_id . '" data-functiontype="' . $functionType . '" data-wkrefstatus="' . $data->workflow_reference_status . '" class="form-check-input selectToReconcile" type="checkbox" value="" name="">
                <input name="reconcile_id" value="' . $data->clearing_id . '" class="reconcileId" type="hidden"></div>';
            })
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
            ->editColumn('status', function ($query) {
                if ($query->approval_status == ApprovalStatusView::PENDING) {
                    return '<span class="badge badge-primary badge-pill">' . ApprovalStatusView::PENDING . '</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">' . ApprovalStatusView::APPROVED . '</span>';
                } else if ($query->approval_status == ApprovalStatusView::REJECTED) {
                    return '<span class="badge badge-danger badge-pill">' . ApprovalStatusView::REJECTED . '</span>';
                } else {
                    return '<span class="badge badge-warning badge-pill">' . ApprovalStatusView::FORWARDED . '</span>';
                }
            })
            /*->editColumn('action', function ($data) use ($functionType) {
                return "<button style='text-decoration:underline'  class='clear_edit'
                          data-mapid='$data->workflow_mapping_id' data-clearing='$data->clearing_id' data-userid='$data->login_user_id' data-functiontype='$functionType' data-wkrefstatus='$data->workflow_reference_status'>
                          <i class='bx bx-edit-alt'></i>
                        </button>";
            })*/
            ->rawColumns(['status', 'action', 'select'])
            ->make(true);
    }


    /*public function approveReject(Request $request, $wkMapId=null) {

         $response = $this->outward_clearing_reconciliation_api_approved_rejected($request, $wkMapId);

         $message = $response['o_status_message'];
         if($response['o_status_code'] != 1) {
             session()->flash('m-class', 'alert-danger');
             return redirect()->back()->with('message', 'error|'.$message)->withInput();
         }

         session()->flash('m-class', 'alert-success');
         session()->flash('message', $message);

         return redirect()->route('invoice-bill-payment-authorize.index');
    }*/

    public function approveReject(Request $request)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $clearings = $request->post('selected_mappings');

            foreach ($clearings as $clearing) {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'i_workflow_mapping_id' => $clearing,
                    'i_workflow_master_id' => WorkFlowMaster::AP_CLEARING_RECONCILIATION_APPROVAL,
                    'i_reference_table' => WkReferenceTable::FAS_CM_CLEARING,
                    'i_reference_key' => WkReferenceColumn::CLEARING_ID,
                    'i_reference_status' => $postData['approve_reject_value'],
                    'i_reference_comment' => $postData['comment'],
                    'i_user_id' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];

                DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);


                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                }
            }

            DB::commit();
            return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => "Exception Occurred."];
        }
    }

}

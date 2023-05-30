<?php
/**
 *Created by PhpStorm
 *Created at ৫/৯/২১ ৪:৪৮ PM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApInvoiceParams;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Common\LGlInteModules;
use App\Enums\ProActionType;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceBillParameterController extends Controller
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
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['invoice_type'] = $this->apLookupManager->findInvoiceType();
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ap.invoice-bill-parameter.index', compact('data', 'coaParams'));
    }

    public function insert(Request $request, $id = null)
    {

        DB::beginTransaction();
        try {
            $invoice_param_id = isset($id) ? $id : null;
            $actionType = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                'p_action_type' => $actionType,
                'p_invoice_param_id' => [
                    'value' => &$invoice_param_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_invoice_param_note' => $request->post('note'),
                'p_gl_subsidiary_id' => (integer)$request->post('party_sub_ledger'),
                'p_invoice_type_id' => $request->post('invoice_type'),
                'p_vendor_category_id' => $request->post('ap_vendor_category'),
                'p_dr_cr_flag' => $request->post('ap_debit_credit'),
                'p_vendor_type_id' => $request->post('ap_vendor_type'),
                'p_ded_at_source_allow_flag' => ($request->post('deduction_allowed') != null) ? $request->post('deduction_allowed') : 0 ,
                'p_tax_gl_acc_id' => $request->post('tax_account_id'),
                'p_vat_gl_acc_id' => $request->post('vat_account_id'),
                'p_sec_gl_acc_id' => $request->post('deposit_account_id'),
                /*'p_fine_gl_acc_id' => $request->post('fine_account_id'),
                'p_psi_gl_acc_id' => $request->post('psi_account_id'),
                'p_elec_gl_acc_id' => $request->post('electricity_account_id'), //Block this section GL Integration for Income Heads Pavel:06-04-22
                'p_others_gl_acc_id' => $request->post('other_account_id'),*/
                'p_distrib_line_gl_sub_flag' => ($request->post('is_party_subLedger') != null) ? $request->post('is_party_subLedger') : 0,
                'p_distrib_line_gl_sub_id' => $request->post('contra_sub_ledger'),
                'p_budget_head_required_yn' => $request->post('budget_head_required_yn') && ($request->post('budget_head_required_yn')== YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO, //Add this part pavel-18-04-22
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_ap_config.create_ap_invoice_params', $params);

            if ($params['o_status_code'] == "99") {
                DB::rollBack();
                if ($id) {
                    return $params;
                } else {
                    $flashMessage = $this->flashMessageManager->getMessage($params);
                    return redirect()->back()->with($flashMessage['class'], $flashMessage['message'])->withInput();
                }
            } else {
                DB::commit();
                if ($id) {
                    return $params;
                } else {
                    $flashMessage = $this->flashMessageManager->getMessage($params);
                    return redirect()->back()->with($flashMessage['class'], $flashMessage['message']);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($id) {
                return ["o_status_code" => '99', 'o_status_message' => "Exception Occurred"];
            } else {
                return redirect()->back()->with("error", "Exception Occurred")->withInput();
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $viewModeYN = $request->get('view', NULL); //Add Part Pavel:06-04-22
        $data['insertedData'] = FasApInvoiceParams::with('gl_subsidiary','invoice_type','tax_acc','vat_acc','sec_acc','distrib_line_gl_sub', 'fine_acc', 'psi_acc', 'elec_acc','others_acc')->where('invoice_param_id','=',$id)->first();
        $data['vendorCategory'] = $this->apLookupManager->getVendorCategory();
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['invoice_type'] = $this->apLookupManager->findInvoiceType();
        $data['vendorType'] = $this->apLookupManager->getVendorTypes();
        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ap.invoice-bill-parameter.index', compact('data', 'coaParams','viewModeYN'));
    }

    public function update(Request $request, $id)
    {
        $response = $this->insert($request, $id);
        $flashMessage = $this->flashMessageManager->getMessage($response);
        return redirect()->back()->with($flashMessage['class'],  $flashMessage['message']);
    }

    public function delete()
    {
        echo "NO procedure found for delete.";
        exit;
    }

    public function dataList()
    {
        $data = FasApInvoiceParams::with('gl_subsidiary','invoice_type','tax_acc','vat_acc','sec_acc','distrib_line_gl_sub')->orderBy('invoice_param_id','ASC')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('id', function ($data) {
                return $data->invoice_param_id;
            })
            ->editColumn('parameter', function ($data) {
                return $data->invoice_param_note;
            })
            ->editColumn('action', function ($data) {
                /*** Block exiting url -Pavel:06-04-22 ***/
                /*return "<a style='text-decoration:underline' class='' href='" . route('invoice-bill-parameter.edit', ['id' => $data->invoice_param_id]) . "' >Edit</a>" . "<span style='text-decoration:underline; color:#5A8DEE' data-target='".$data->invoice_param_id."' class='removeInvoiceBill cursor-pointer' >Delete</span>";*/

                /*** Add View url -Pavel:06-04-22 ***/
                return "<a style='text-decoration:underline' class='' href='" . route('invoice-bill-parameter.edit', ['id' => $data->invoice_param_id, 'view'=> true]) . "' >View</a> || <a style='text-decoration:underline' class='' href='" . route('invoice-bill-parameter.edit', ['id' => $data->invoice_param_id]) . "' >Edit</a> || " . "<span style='text-decoration:underline; color:#5A8DEE' data-target='".$data->invoice_param_id."' class='removeInvoiceBill cursor-pointer' >Delete</span>";
            })
            ->make(true);
    }
}

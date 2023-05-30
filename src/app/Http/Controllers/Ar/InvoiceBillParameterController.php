<?php
/**
 *Created by PhpStorm
 *Created at ৫/৯/২১ ৪:৪৮ PM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Ap\FasApInvoiceParams;
use App\Entities\Ar\FasArInvoiceParams;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Common\LGlInteModules;
use App\Enums\ProActionType;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
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
    private $arLookupManager;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager,
                                ArLookupManager $arLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glCoaParam = new GlCoaParams();
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->arLookupManager = $arLookupManager;
    }

    public function index()
    {
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $data['transactionType'] = $this->arLookupManager->getTransactionType();
        $coaParams = $this->lookupManager->findGlCoaParams();
        return view('ar.invoice-bill-parameter.index', compact('data', 'coaParams'));
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
                'p_transaction_type_id' => (integer)$request->post('transaction_type'),
                'p_vat_gl_acc_id' => $request->post('vat_account_id'),
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];
            DB::executeProcedure('CPAACC.fas_ar_config.create_ar_invoice_params', $params);

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
        $viewModeYN = $request->get('view', NULL); //Add Part Pavel:07-04-22
        $data['insertedData'] = FasArInvoiceParams::with('gl_subsidiary','vat_acc')->where('invoice_param_id','=',$id)->first();
        $data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $data['transactionType'] = $this->arLookupManager->getTransactionType();
        $coaParams = $this->lookupManager->findGlCoaParams();

        return view('ar.invoice-bill-parameter.index', compact('data', 'coaParams','viewModeYN'));
    }

    public function update(Request $request, $id)
    {
        $response = $this->insert($request, $id);
        $flashMessage = $this->flashMessageManager->getMessage($response);
        return redirect()->back()->with($flashMessage['class'],  $flashMessage['message']);
    }

    public function delete_procedure($id)
    {
        DB::beginTransaction();
        try {
            $invoice_param_id = isset($id) ? $id : null;
            $actionType = ProActionType::DELETE;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                'p_action_type' => $actionType,
                'p_invoice_param_id' => [
                    'value' => &$invoice_param_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_invoice_param_note' => '',
                'p_gl_subsidiary_id' => '',
                'p_vat_gl_acc_id' => '',
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];
            DB::executeProcedure('CPAACC.fas_ar_config.create_ar_invoice_params', $params);

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

    public function delete($id){
        $response = $this->delete_procedure($id);
        $flashMessage = $this->flashMessageManager->getMessage($response);
        return redirect()->back()->with($flashMessage['class'],  $flashMessage['message']);
    }

    public function dataList()
    {
        $data = FasArInvoiceParams::with('gl_subsidiary','vat_acc')->orderBy('invoice_param_id','ASC')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('id', function ($data) {
                return $data->invoice_param_id;
            })
            ->editColumn('parameter', function ($data) {
                return $data->invoice_param_note;
            })
            ->editColumn('action', function ($data) {
                /*** Block exiting url -Pavel:07-04-22 ***/
                /*return "<a style='text-decoration:underline' class='' href='" . route('ar-invoice-bill-parameter.edit', ['id' => $data->invoice_param_id]) . "' >Edit</a>" . "<span style='text-decoration:underline; color:#5A8DEE' data-target='".$data->invoice_param_id."' class='removeInvoiceBill ml-1 cursor-pointer' >Delete</span>";*/

                /*** Add View url -Pavel:07-04-22 ***/
                return "<a style='text-decoration:underline' class='' href='" . route('ar-invoice-bill-parameter.edit', ['id' => $data->invoice_param_id, 'view' =>true]) . "' >View</a> || <a style='text-decoration:underline' class='' href='" . route('ar-invoice-bill-parameter.edit', ['id' => $data->invoice_param_id]) . "' >Edit</a> || " . "<span style='text-decoration:underline; color:#5A8DEE' data-target='".$data->invoice_param_id."' class='removeInvoiceBill cursor-pointer' >Delete</span>";
            })
            ->make(true);
    }
}

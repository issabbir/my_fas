<?php

namespace App\Http\Controllers\Cm;

use App\Contracts\Cm\CmLookupContract;
use App\Contracts\LookupContract;
use App\Entities\Cm\CmChequeBooks;
use App\Entities\Cm\CmChequeLeaf;
use App\Enums\Cm\CmChequeLeafUsedFlag;
use App\Enums\ProActionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Cm\CmLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChequeBookSetupController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var CmLookupManager */
    private $cmLookupManager;

    public function __construct(LookupContract $lookupManager, CmLookupContract $cmLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->cmLookupManager = $cmLookupManager;
    }

    public function index()
    {
        return view('cm.cheque-book-setup.index', [
            'bankAccList' => $this->cmLookupManager->getBankAcc(),
        ]);
    }

    public function dataTableList()
    {
        $queryResult = CmChequeBooks::with(['coa_info'])->get();

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $dataString = $query->bank_gl_acc_id . "##" . $query->coa_info->gl_acc_name . "##" . $query->chq_prefix . "##" . $query->chq_leaf_beg_no . "##" . $query->chq_leaf_end_no;
                return '<a class="btn btn-sm btn-primary text-white cursor-pointer leaf-list"  id="' . $query->chq_book_id . '" data-cheque-book-data="' . $dataString . '"><i class="bx bx-show cursor-pointer"></i>View Leaf</a>
                        <a class="btn btn-sm btn-info"  href="' . route('cheque-book-setup.edit', [$query->chq_book_id]) . '"><i class="bx bx-edit cursor-pointer"></i>Edit</a>
                        <a class="btn btn-sm btn-danger"  href="' . route('cheque-book-setup.delete', [$query->chq_book_id]) . '"><i class="bx bx-trash cursor-pointer"></i>Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function dataTableLeafList(Request $request)
    {
        $postData = $request->post();

        $glAccId = isset($postData['gl_acc_id']) ? $postData['gl_acc_id'] : '' ;
        $chqBookId = isset($postData['chq_book_id']) ? $postData['chq_book_id'] : '' ;

        if (empty($glAccId) || empty($chqBookId)) {
            $queryResult = [];
        } else {
            $queryResult = DB::select("select CPAACC.fas_cm_config.get_cheque_leaf_info (:p_bank_gl_acc_id, :p_chq_book_id) from dual", ['p_bank_gl_acc_id' => $glAccId, 'p_chq_book_id' => $chqBookId ]);
        }

        return datatables()->of($queryResult)
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $chequeBooksInfo = CmChequeBooks::where('chq_book_id', $id)->first();

        return view('cm.cheque-book-setup.index', [
            'chequeBooksInfo' => $chequeBooksInfo,
            'bankAccList' => $this->cmLookupManager->getBankAcc(),
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->cheque_book_setup_api_ins($request);

        $message = $response['o_status_message'];

        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cheque-book-setup.index');
    }

    public function update(Request $request, $id)
    {
        $response = $this->cheque_book_setup_api_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cheque-book-setup.index');
    }

    public function delete(Request $request, $id)
    {
        $response = $this->cheque_book_setup_api_del($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cheque-book-setup.index');
    }

    private function cheque_book_setup_api_ins(Request $request)
    {
        $postData = $request->post();

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $chq_book_id = null;

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_chq_book_id' => $chq_book_id,
                'p_bank_gl_acc_id' => $postData['bank_acc_id'],
                'p_chq_prefix' => $postData['cheque_prefix'],
                'p_chq_leaf_beg_no' => $postData['beginning_number'],
                'p_chq_leaf_end_no' => $postData['ending_number'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_cheque_books', $params);
        } catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function cheque_book_setup_api_upd($request, $id)
    {
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::UPDATE,
                'p_chq_book_id' => $id,
                'p_bank_gl_acc_id' => $postData['bank_acc_id'],
                'p_chq_prefix' => $postData['cheque_prefix'],
                'p_chq_leaf_beg_no' => $postData['beginning_number'],
                'p_chq_leaf_end_no' => $postData['ending_number'],
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_cheque_books', $params);
        } catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    private function cheque_book_setup_api_del($request, $id)
    {
        $postData = $request->post();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::DELETE,
                'p_chq_book_id' => $id,
                'p_bank_gl_acc_id' => null,
                'p_chq_prefix' => null,
                'p_chq_leaf_beg_no' => null,
                'p_chq_leaf_end_no' => null,
                'p_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_cm_config.create_cheque_books', $params);
        } catch (\Exception $e) {
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}

<?php


namespace App\Http\Controllers\Ar;

use App\Contracts\LookupContract;
use App\Enums\ApprovalStatusView;
use App\Enums\Ar\ArFunType;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\Ar\ArLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InvoiceBillReceiptListController extends Controller
{
    use HasPermission;
    protected  $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ArLookupManager */
    private $arLookupManager;

    public function __construct(LookupContract $lookupManager, ArLookupManager $arLookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->arLookupManager = $arLookupManager;
        $this->commonManager = $commonManager;
    }


    public function index($filter = null)
    {
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $data['billSecs'] =  $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_RECEIPT);
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;
        return view('ar.invoice-bill-receipt-listing.index', compact('data','fiscalYear','filterData'));
    }

    public function searchInvoiceReceipt(Request $request)
    {
        $queryResult = [];

        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id'=> $request->post('fiscalYear'),
            'p_trans_period_id' =>  $request->post('period',null),
            'p_bill_sec_id' =>  $request->post('bill_sec_id', null),
            'p_bill_reg_id' =>  $request->post('bill_reg_id', null),
            'p_workflow_approval_status' => $request->post('approval_status', null),
            'p_user_id'=>Auth::user()->user_id,
            ];

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_ar_trans.get_ar_receipt_entry_list (:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        $filteredData = Crypt::encryptString($request->post('fiscalYear') .'#'.$request->post('period') .'#'. $request->post('bill_section') .'#'. $request->post('bill_reg_id') .'#'. $request->post('approval_status'));

        return datatables()->of($queryResult)
            ->editColumn('status', function($query) {
                if($query->approval_status == ApprovalStatusView::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                }
            })
            ->editColumn('receipt_amount',function ($d){
                return HelperClass::getCommaSeparatedValue($d->receipt_amount);
            })
            ->addColumn('action', function ($query) use ($filteredData){
                return '<a href="' . route('invoice-bill-receipt.view', [$query->receipt_id,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }
}

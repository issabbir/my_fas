<?php


namespace App\Http\Controllers\Ap;

use App\Contracts\Ap\ApLookupContract;
use App\Contracts\LookupContract;
use App\Enums\Ap\ApFunType;
use App\Enums\ApprovalStatusView;
use App\Enums\Common\LGlInteModules;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InvoiceBillPayListController extends Controller
{
    use HasPermission;
    protected  $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    public function __construct(LookupContract $lookupManager, ApLookupContract $apLookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->apLookupManager = $apLookupManager;
        $this->commonManager = $commonManager;
    }


    public function index($filter = null)
    {
        $moduleId = LGlInteModules::FIN_ACC_GENE_LEDGER;
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        return view('ap.invoice-bill-payment-listing.index', [
            'lBillSecList' => $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_PAYMENT),
            //'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            'fiscalYear' => $fiscalYear,
            'vendorList' => $this->apLookupManager->getVendors(),
            'filterData' => isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter
        ]);
    }

    public function searchInvoicePayment(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id' => $terms['fiscalYear'],
            'p_trans_period_id' =>  $terms['period'] ?  $terms['period'] : null,
            'p_bill_sec_id' =>  $terms['bill_sec_id'] ?   $terms['bill_sec_id'] : null,
            'p_bill_reg_id' =>  $terms['bill_reg_id'] ?   $terms['bill_reg_id'] : null,
            'p_workflow_approval_status' => $terms['authorization_status'] ?   $terms['authorization_status'] : null,
            'p_user_id'=>Auth::user()->user_id,
            /*'p_trans_period_id' =>  $terms['period'] ?  $terms['period'] : null,
            'p_trans_date' =>   $terms['posting_date_field'] ?   HelperClass::dateFormatForDB($terms['posting_date_field']) : null,
            'p_trans_batch_id' =>    $terms['posting_batch_id'] ?   $terms['posting_batch_id'] : null,
            'p_vendor_id' =>    $terms['vendor_id'] ?   $terms['vendor_id'] : null,
            'p_bill_sec_id' =>  $terms['bill_sec_id'] ?   $terms['bill_sec_id'] : null,
            'p_bill_reg_id' =>  $terms['bill_reg_id'] ?   $terms['bill_reg_id'] : null,
            'p_workflow_approval_status' => $terms['authorization_status'] ?   $terms['authorization_status'] : null,*/
            ];

        /** Execute Oracle Function With Params **/
        /*$sql ="select cpaacc.fas_ap_trans.get_ap_payment_entry_list (:p_trans_period_id,:p_trans_date,:p_trans_batch_id,:p_vendor_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status) from dual";*/
        $sql ="select cpaacc.fas_ap_trans.get_ap_payment_entry_list (:p_fiscal_year_id,:p_trans_period_id,:p_bill_sec_id,:p_bill_reg_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);
        $filteredData = Crypt::encryptString($request->post('fiscalYear') .'#'.$request->post('period') .'#'. $request->post('bill_sec_id') .'#'. $request->post('bill_reg_id') .'#'. $request->post('authorization_status'));
        return datatables()->of($queryResult)
            ->editColumn('document_date',function ($query){
                return HelperClass::dateConvert($query->document_date);
            })
            ->editColumn('payment_amount',function ($query){
                return HelperClass::getCommaSeparatedValue($query->payment_amount);
            })
            ->addColumn('status', function($query) {
                if($query->approval_status == ApprovalStatusView::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                }
            })
            ->editColumn('cheque_print', function($query) {
                if($query->approval_status == ApprovalStatusView::APPROVED){
                    return '<a  target="_blank" href="'.request()->root().'/report/render/cheque_print?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_CHEQUE_PRINTING.xdo&p_payment_id='.$query->payment_id.'&type=pdf&filename=cheque_print"><span class="badge badge-primary badge-pill"><i class="bx bx-printer cursor-pointer font-small-3 align-middle"></i>&nbsp;Print</span></a>';
                } else {
                    return '<span class="badge badge-danger badge-pill">N/A</span>';
                }
            })
            ->addColumn('action', function ($query) use($filteredData) {
                /*return '<button class="btn btn-primary btn-sm trans-mst"  id="'.$query->payment_id.'">Detail View</button>';
                <a class="btn btn-sm btn-info"  href="' . route('invoice-bill-payment.view', [$query->payment_id]) . '"><i class="bx bx-show cursor-pointer"></i> View</a>*/
                return '<a href="' . route('invoice-bill-payment.view', ['id'=>$query->payment_id,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->rawColumns(['status','cheque_print','action'])
            ->addIndexColumn()
            ->make(true);
    }
}

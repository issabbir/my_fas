<?php


namespace App\Http\Controllers\Gl;



use App\Contracts\Common\CommonContract;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\CalendarMaster;
use App\Entities\Gl\CalendarProperties;
use App\Entities\Gl\LFiscalPeriod;
use App\Entities\Gl\LPeriodType;
use App\Enums\Gl\CalendarStatus;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Barryvdh\Debugbar\Facade as DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bk_CalendarController extends Controller
{
    protected $lperiod;
    protected $lfiscal;
    protected $flashMessage;
    protected $calendarMaster;
    protected $calendarProperty;
    public function __construct()
    {
        $this->lperiod = new LPeriodType();
        $this->lfiscal = new LFiscalPeriod();
        $this->flashMessage = new FlashMessageManager();
        $this->calendarMaster = new CalendarMaster();
        $this->calendarProperty = new CalendarProperties();
    }

    public function index()
    {
        return view('gl.calendar.index');
    }

    public function setup()
    {
        $data['periodType'] = $this->lperiod->get();
        $data['fiscalPeriod'] = $this->lfiscal->get();
        $data['defaultProperty'] = $this->calendarProperty->where('properties_id','=','1')->first();
        //DebugBar::addMessage($data);
        return view('gl.calendar.setup',compact('data'));
    }

    public function store(Request $request, $id=null)
    {
        $request->validate([
            'yearStart' => 'required|',
            'yearEnd' => 'required|gt:yearStart'
        ]);

        $calenderId = isset($id) ? $id : '';
        $actionType = isset($id) ? 'U' : 'I';
        $status_msg = sprintf("%4000s","");
        $status_code = sprintf("%4000d","");

        $params = [
            "p_action_type" => $actionType,
            "p_calendar_id" => [
                'value' => &$calenderId,
                'type' => \PDO::PARAM_INPUT_OUTPUT
            ],
            "p_fiscal_period_id" => $request->post('fiscalYearPeriod'),
            "p_fiscal_beg_year" => $request->post('yearStart'),
            "p_fiscal_end_year" => $request->post('yearEnd'),
            "p_posting_period_code" => $request->post('postingCalendarPeriod'),
            "p_calendar_status" => 'I',
            "p_user_id" => Auth::user()->user_id,
            "o_status_code" => &$status_code,
            "o_status_message" => &$status_msg,
        ];

        try {
            DB::executeProcedure("FAS_CONFIG.create_fiscal_calendar",$params);
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage())->withInput();
        }
        $response = $this->flashMessage->getMessage($params);
        return redirect()->back()->with($response['class'],$response['message'])->withInput();
    }

    public function defaultSetup()
    {
        $data['periodType'] = $this->lperiod->get();
        $data['fiscalPeriod'] = $this->lfiscal->get();
        $data['defaultProperty'] = $this->calendarProperty->where('properties_id','=','1')->first();
        return view('gl.calendar.default_setup',compact('data'));
    }

    public function defaultStore(Request $request, $id=null)
    {
        $propertiesId = isset($id) ? $id : '';
        $actionType = isset($id) ? 'U' : 'I';
        $status_msg = sprintf("%4000s","");
        $status_code = sprintf("%4000d","");

        $params = [
            /*"p_action_type" => $actionType,
            "p_properties_id" => [
                'value' => &$propertiesId,
                'type' => \PDO::PARAM_INPUT_OUTPUT
            ],*/
            "p_fiscal_period_id" => $request->post('fiscalYearPeriod'),
            "p_posting_period_code" => $request->post('postingCalendarPeriod'),
            /*"p_posting_period_display_name" => '',*/
            "p_max_fiscal_year_open_allow" => $request->post('maxYear'),
            "p_max_positing_prd_open_allow" => $request->post('maxPeriod'),
            "p_user_id" => Auth::user()->user_id,
            "o_status_code" => &$status_code,
            "o_status_message" => &$status_msg,
        ];
        try {
            DB::executeProcedure("FAS_CONFIG.change_calendar_properties",$params);
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage())->withInput();
        }

        $response = $this->flashMessage->getMessage($params);
        return redirect()->back()->with($response['class'],$response['message'])->withInput();
    }

    public function detailView(Request $request, $id)
    {
        $calMst = CalendarMaster::with(['fiscal_period'])->where('calendar_id', $id)->first();

        //$calDtl = CalendarDetail::where('calendar_id', $id)->get();
        $calDtl = CalendarDetail::addSelect(['previous_month_status' => function ($query) {
            $query->select('posting_period_status')
                ->from('fas_calendar_detail as cd')
                ->whereRaw('cd.posting_period_beg_date = ADD_MONTHS (fas_calendar_detail.posting_period_beg_date, -1)');
        }])->where('calendar_id', $id)->get();

        //dd($calDtl);

        return view('gl.calendar.detail',
            [
                'calMst' => $calMst,
                'calDtl' => $calDtl,
            ]);
    }

    public function detailStore(Request $request)
    {
        $cal_id = $request->get('cal_id');
        //dd($invoice_id);
        $response = $this->gl_cal_dtl_api_upd($request);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message)->withInput();
        }

        $calId = isset($cal_id) ? $cal_id : 0;

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect()->route('calendar.detail-view',$calId);
    }

    private function gl_cal_dtl_api_upd(Request $request)
    {
        $postData = $request->post();
        $params = [];
        DB::beginTransaction();
        //dd($postData);

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            foreach ($postData['calendar_detail_status'] as $indx => $value) {
                $cal_dtl_id = isset($postData['calendar_detail_id'][$indx]) ? ($postData['calendar_detail_id'][$indx]) : null ;
                $cal_dtl_status = isset($postData["calendar_detail_status"][$indx]) ? $postData["calendar_detail_status"][$indx] : null;

                /*if($cal_dtl_status == 'O' || $cal_dtl_status == 'C'){
                    echo $kk =  $cal_dtl_id.'--'.$cal_dtl_status.'--';
                    echo '<br>'.'--------'.'<br>';
                    echo "<pre>";
                }*/

                if($cal_dtl_status == CalendarStatus::OPENED || $cal_dtl_status == CalendarStatus::CLOSED){
                    $params = [
                        'p_calendar_detail_id' => $cal_dtl_id,
                        'p_posting_period_status' => $cal_dtl_status,
                        'p_user_id' => auth()->id(),
                        'o_status_code' => &$status_code,
                        'o_status_message' => &$status_message
                    ];
                    //dd($params);
                    DB::executeProcedure('CPAACC.FAS_CONFIG.change_posting_period_status', $params);

                    if ($params['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params;
                    }
                }

            }

        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
         /*echo '<pre>';
         print_r($params);
         die();*/
        DB::commit();
        return $params;
    }

    public function calendarList()
    {
        $calenders = $this->calendarMaster->with('fiscal_period','period_type')->where('deleted_yn','=','N')->get();
        return datatables()->of($calenders)
            ->addIndexColumn()
            ->editColumn('fiscal_period',function ($data){
                return $data->fiscal_period->fiscal_period_nm;
            })
            ->editColumn('fiscal_year',function ($data){
                return $data->fiscal_year;
            })
            ->editColumn('posting_period',function ($data){
                return $data->period_type->period_type_nm;
            })
            ->editColumn('status',function ($data){
                switch ($data->calendar_status){
                    case CalendarStatus::INACTIVE:
                        return "Inactive";
                    case CalendarStatus::OPENED:
                        return "Opened";
                    case CalendarStatus::CLOSED:
                        return "Closed";
                }
            })
            ->editColumn('action',function ($data){
                //return '<a href="'.route('calendar.detail-view',['id'=>$data->calendar_id]).'"><i class="bx bx-show"></i> </a>|<a href="#"><i class="bx bx-wrench"></i></a>';
                return '<a class="btn btn-sm btn-primary" href="'.route('calendar.detail-view',['id'=>$data->calendar_id]).'"><i class="bx bx-show"></i>Details</a>';
            })
            ->make(true);
    }

}

<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১১:৩৮ AM
 */
?>
<div class="row mt-1">
    @if($report)
        {{-- This input field for sending employee id to the back-end for showing user name on the report page. --}}
        <input type="hidden" name="p_login_user" value="{{ \Illuminate\Support\Facades\Auth::user()->emp_id }}">

        @if($report->params)
            @foreach($report->params as $reportParam)

                {{--@if($reportParam->component == 'posting_period')
                    <div class="col-md-4">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @if($postingPeriod)
                                @foreach($postingPeriod as $option)
                                    <option
                                        {{ ($option->posting_period_status == 'O') ? "selected" : "" }}
                                        value="{{$option->posting_period_id}}"
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($option->posting_period_beg_date)}}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($option->posting_period_end_date)}}"
                                        data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($option->current_posting_date)}}"
                                        data-postingname="{{ $option->posting_period_name}}">
                                        {{ $option->posting_period_name}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @else--}}
                @if($reportParam->component == 'authorize_status')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select an option</option>
                            @foreach(\App\Enums\AuthorizeStatus::authorize_status as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>


                @elseif($reportParam->component == 'period_closing_event')
                    <div class="col-md-4">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @foreach($closingEvents as $event)
                                <option
                                    value="{{$event->period_closing_event_id}}">{{$event->period_closing_event_nm}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'gl_trans_mst')
                    <div class="col-md-4">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >

                        </select>
                    </div>
                @elseif($reportParam->component == 'gl_accounts')
                    <div class="col-md-4">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'function_type')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select Function</option>
                            {{--@foreach($apFuncType as $type)
                                <option value="{{$type->function_id}}">{{$type->function_name}}</option>
                            @endforeach--}}
                        </select>
                    </div>
                @elseif($reportParam->component == 'department')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select a department</option>
                            @foreach($department as $dpt)
                                <option value="{{$dpt->cost_center_dept_id}}">{{$dpt->cost_center_dept_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'default_bill_section')
                    <div class="col-md-3">
                        <label for="p_bill_sec_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Section</label>
                        <select @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                @endif name="p_bill_sec_id" class="form-control " id="p_bill_sec_id">
                            <option value="">Select a bill</option>
                            @foreach($billSecs as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'bill_params')
                    <div class="col-md-3">
                        <label for="p_bill_sec_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Section</label>
                        <select name="p_bill_sec_id" class="form-control select2" id="p_bill_sec_id">
                            <option value="">Select a bill</option>
                            @foreach($billSecs as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="p_bill_reg_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Register</label>
                        <select name="p_bill_reg_id" class="form-control select2" id="p_bill_reg_id">
                        </select>
                    </div>
                @elseif($reportParam->component == 'module')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select an option</option>
                            @foreach($modules as $module)
                                <option value="{{$module->module_id}}">{{$module->module_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'application_types')
                    <div class="col-md-4">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @if($appType)
                                @foreach($appType as $option)
                                    {!!$option!!}
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($reportParam->component == 'month')
                    <div class="col-md-4 month_div">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control month"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @if($month)
                                @foreach($month as $option)
                                    {!!$option!!}
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($reportParam->component == 'year')
                    <div class="col-md-4 year_div">
                        <label for="p_year"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{ $reportParam->param_label }}</label>
                        <div class="input-group date yearPiker" id="p_year"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off"
                                   class="form-control datetimepicker-input year"
                                   value="" name="{{ $reportParam->param_name }}"
                                   data-toggle="datetimepicker"
                                   data-target="#p_year"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#p_year"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'date_picker')
                    <div class="col-md-4 year_div">
                        <label for="{{ $reportParam->param_name }}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{ $reportParam->param_label }}</label>
                        <div class="input-group date" id="{{ $reportParam->param_name }}"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off"
                                   class="form-control datetimepicker-input year"
                                   value="" name="{{ $reportParam->param_name }}"
                                   data-toggle="datetimepicker"
                                   data-target="#{{ $reportParam->param_name }}" onkeydown="return false"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#{{ $reportParam->param_name }}"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'account_date_range')
                    <div class="col-md-4">
                        <label for="p_start_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">From
                            Date</label>
                        <div class="input-group date datePiker account_date_range" id="p_start_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   class="form-control datetimepicker-input"
                                   name="p_start_date"
                                   data-toggle="datetimepicker"
                                   data-target="#p_start_date"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                @endif/>
                            <div class="input-group-append" data-target="#p_start_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="p_end_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">To
                            Date</label>
                        <div class="input-group date datePiker" id="p_end_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   class="form-control datetimepicker-input"
                                   name="p_end_date"
                                   data-toggle="datetimepicker"
                                   data-target="#p_end_date"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                @endif />
                            <div class="input-group-append" data-target="#p_end_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'fiscal_year')
                    <div class="col-md-3">
                        <label for="p_start_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @foreach($fiscalYear as $year)
                                <option value="{{$year->fiscal_year_id}}" data-mindate = "{{ \App\Helpers\HelperClass::dateConvert($year->period_beg_date) }}"
                                        data-maxdate = "{{ \App\Helpers\HelperClass::dateConvert($year->period_end_date) }}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'posting_period')
                    <div class="col-md-3">
                        <label for="p_start_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'posting_period_from')
                    <div class="col-md-3">
                        <label for="p_start_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'posting_period_to')
                    <div class="col-md-3">
                        <label for="p_end_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'gl_subsidiary')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select Party Sub-Ledger</option>
                            @foreach($subsidiary_type as $type)
                                <option value="{{$type->gl_subsidiary_id}}">{{$type->gl_subsidiary_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'customer')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'posting_date')
                    <div class="col-md-3">
                        <label for="{{ $reportParam->param_name }}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{ $reportParam->param_label }}</label>
                        <div class="input-group date" id="{{ $reportParam->param_name }}"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off"
                                   class="form-control datetimepicker-input"
                                   value=""
                                   data-predefined-date=""
                                   name="{{ $reportParam->param_name }}"
                                   data-toggle="datetimepicker"
                                   data-target="#{{ $reportParam->param_name }}" onkeydown="return false"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#{{ $reportParam->param_name }}"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'posting_date_from')
                    <div class="col-md-3 year_div">
                        <label for="{{ $reportParam->param_name }}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{ $reportParam->param_label }}</label>
                        <div class="input-group date" id="{{ $reportParam->param_name }}"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off"
                                   class="form-control datetimepicker-input"
                                   value=""
                                   data-predefined-date=""
                                   name="{{ $reportParam->param_name }}"
                                   data-toggle="datetimepicker"
                                   data-target="#{{ $reportParam->param_name }}" onkeydown="return false"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#{{ $reportParam->param_name }}"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'posting_date_to')
                    <div class="col-md-3 year_div">
                        <label for="{{ $reportParam->param_name }}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{ $reportParam->param_label }}</label>
                        <div class="input-group date" id="{{ $reportParam->param_name }}"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off"
                                   class="form-control datetimepicker-input"
                                   value=""
                                   data-predefined-date=""
                                   name="{{ $reportParam->param_name }}"
                                   data-toggle="datetimepicker"
                                   data-target="#{{ $reportParam->param_name }}" onkeydown="return false"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#{{ $reportParam->param_name }}"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'user_list')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option>Select User Name</option>
                            @foreach($users as $user)
                                <option
                                    value="{{$user->user_id}}">{{$user->emp_name}}
                                    ({{$user->user_name}})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <input type="text" name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                               class="form-control"
                               @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                    </div>
                @endif
            @endforeach
        @endif

        <div class="col-md-3">
            <label for="type">Report Type</label>
            <select name="type" id="type" class="form-control">
                <option value="pdf">PDF</option>
                <option value="xlsx">Excel</option>
            </select>
            <input type="hidden" value="{{$report->report_xdo_path}}" name="xdo"/>
            <input type="hidden" value="{{$report->report_id}}" name="rid"/>
            <input type="hidden" value="{{$report->report_name}}" name="filename"/>
        </div>
        <div class="col-md-3 mt-2">
            <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Generate Report
            </button>
        </div>
    @endif
</div>
<script type="text/javascript">
    $(document).ready(function () {
        selectDebitCreditBankAcc('#p_gl_acc_id', APP_URL + '/general-ledger/ajax/gl-accounts', '', '');
        $("#p_bill_sec_id").select2();
        $("#p_bill_reg_id").select2();
        $("#p_department_id").select2();

        vendorLedgerDateRangePicker("#p_start_date", "#p_end_date", $("#p_start_date > input").data('mindate'), $("#p_end_date > input").data('maxdate'));

        let intCalendarClickCounter = 0;
        $("#p_trans_period_id").on('change', function () {
            $("#P_trans_date >input").val("");
            if (intCalendarClickCounter > 0) {
                $("#P_trans_date").datetimepicker('destroy');
                intCalendarClickCounter = 0;
            }
        });
        $("#P_trans_date").on('click', function () {
            intCalendarClickCounter++;
            $("#P_trans_date >input").val("");
            let minDate = $("#p_trans_period_id :selected").data("mindate");
            let maxDate = $("#p_trans_period_id :selected").data("maxdate");
            let currentDate = $("#p_trans_period_id :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        $("#p_function_id").on('change', function () {
            $("#p_bill_sec_id").html("");

            $("#p_bill_reg_id").select2("destroy");
            $("#p_bill_reg_id").html("");
            $("#p_bill_reg_id").select2();

            getBillSectionOnFunction($(this).find("option:selected").val(), "#p_bill_sec_id");
        })

        $('#p_bill_sec_id').change(function (e) {
            $("#p_bill_reg_id").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#p_bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
        });

        $("#p_old_fiscal_year").change(function (e) {
            let yearId = $("#p_old_fiscal_year :selected").val();
            if (!nullEmptyUndefinedChecked(yearId)){
                getOldPeriods(yearId);
            }else{
                $("#p_period_from").html("");
                $("#p_period_to").html("");
            }
        })

        $(document).on('change','#p_period_from, #p_period_to',function () {
            if ($("#p_period_from :selected").data('periodfrom') > $("#p_period_to :selected").data('periodto')){
                $(this).notify("Period from must be less then period to");
                $(this).val("");
            }
        });

        function getOldPeriods(yearId) {
            let request = $.ajax({
                url: APP_URL + "/ajax/old-periods-from-to",
                data: {yearId: yearId}
            });
            request.done(function (res) {
                $("#p_period_from").html(res.period_from);
                $("#p_period_to").html(res.period_to);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
                //Swal.fire({text:textStatus+jqXHR,type:'error'});
                //console.log(jqXHR, textStatus);
            });
        }

        $("#p_module_id").on('change', function () {
            $("#p_bill_sec_id").html("");
            $("#p_bill_reg_id").select2("destroy");
            $("#p_bill_reg_id").html("");
            $("#p_bill_reg_id").select2();

            getFunctionTypeOnModule($(this).find("option:selected").val(), "#p_function_id")
        });


        let postingDateCalendarClickCounter = 0;
        let postingDateFromCalendarClickCounter = 0;
        let postingDateToCalendarClickCounter = 0;

        $("#p_posting_period_id").on('change', function () {
            resetPostingDateRelatedFields();
            destroyPostingCalenders();

            postingDateCalendarClickCounter = 0
            postingDateFromCalendarClickCounter = 0;
            postingDateToCalendarClickCounter = 0;
        });

        function resetPostingDateRelatedFields(){
            $("#p_posting_date >input").val("");
            $("#p_posting_date_from >input").val("");
            $("#p_posting_date_to >input").val("");
        }

        function destroyPostingCalenders(){
            if (postingDateCalendarClickCounter > 0) {
                $("#p_posting_date").datetimepicker('destroy');
                postingDateCalendarClickCounter = 0;
            }
            if (postingDateFromCalendarClickCounter > 0) {
                $("#p_posting_date_from").datetimepicker('destroy');
                postingDateFromCalendarClickCounter = 0;
            }
            if (postingDateToCalendarClickCounter > 0) {
                $("#p_posting_date_to").datetimepicker('destroy');
                postingDateToCalendarClickCounter = 0;
            }
        }

        $("#p_posting_date").on('click', function () {
            postingDateCalendarClickCounter++;
            $("#p_posting_date >input").val("");
            let minDate = $("#p_posting_period_id :selected").data("mindate");
            let maxDate = $("#p_posting_period_id :selected").data("maxdate");
            let currentDate = $("#p_posting_period_id :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        $("#p_posting_date_from").on('click', function () {
            let minDate = null;
            let maxDate = null;
            let currentDate = false;

            //$("#p_posting_date_from >input").val("");
            /*If posting period field exist.*/
            if ($("#p_posting_period_id").length > 0){
                destroyPostingCalenders();
                postingDateFromCalendarClickCounter++;
                minDate = $("#p_posting_period_id :selected").data("mindate");
                maxDate = $("#p_posting_period_id :selected").data("maxdate");
                currentDate = $("#p_posting_period_id :selected").data("mindate");
            }else{
                postingDateFromCalendarClickCounter++;
                minDate = $("#p_fiscal_year_id :selected").data("mindate");
                maxDate = $("#p_fiscal_year_id :selected").data("maxdate");
                //currentDate = nullEmptyUndefinedChecked($("#p_posting_date_from >input").val()) ? false : $("#p_posting_date_from >input").val();
                currentDate = $("#p_fiscal_year_id :selected").data("mindate");
            }

            datePickerOnPeriod(this, minDate, maxDate,currentDate);
        });

        $("#p_posting_date_to").on('click', function () {
            //destroyPostingCalenders();
            let minDate = null;
            let maxDate = null;
            let currentDate = false;

            //$("#p_posting_date_to >input").val("");
            /*If posting period field exist.*/
            if ($("#p_posting_period_id").length > 0){
                destroyPostingCalenders();
                postingDateToCalendarClickCounter++;
                minDate = $("#p_posting_period_id :selected").data("mindate");
                maxDate = $("#p_posting_period_id :selected").data("maxdate");
                currentDate = $("#p_posting_period_id :selected").data("maxdate");
            }else{
                destroyPostingCalenders();
                postingDateToCalendarClickCounter++;
                minDate = $("#p_fiscal_year_id :selected").data("mindate");
                maxDate = $("#p_fiscal_year_id :selected").data("maxdate");
                //currentDate = nullEmptyUndefinedChecked($("#p_posting_date_to >input").val()) ? false : $("#p_posting_date_to >input").val();
                currentDate = $("#p_fiscal_year_id :selected").data("maxdate");
            }

            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        reportGetPostingPeriods($("#p_fiscal_year_id :selected").val(), APP_URL + '/account-receivable/gl-posting-periods', setPeriods);

        /*if ($("#p_posting_period_id").length > 0){
            $("#p_posting_date_from >input").attr('data-predefined-date',$("#p_posting_period_id :selected").data('mindate'))
            $("#p_posting_date_to >input").attr('data-predefined-date',$("#p_posting_period_id :selected").data('maxdate'))
        }else{
            $("#p_posting_date_from >input").attr('data-predefined-date',$("#p_fiscal_year_id :selected").data('mindate'))
            $("#p_posting_date_to >input").attr('data-predefined-date',$("#p_fiscal_year_id :selected").data('maxdate'))
        }*/

        $("#p_fiscal_year_id").on('change', function () {
            /*$("#p_posting_date_from >input").val("");
            $("#p_posting_date_to >input").val("");*/

            /*If posting period field exist then send ajax request.*/
            if ($("#p_posting_period_id").length > 0){
                resetPostingDateRelatedFields();
                destroyPostingCalenders();
                postingDateCalendarClickCounter = 0;
                reportGetPostingPeriods($(this).val(), APP_URL + '/account-receivable/gl-posting-periods', setPeriods);

               /* $("#p_posting_date_from >input").attr('data-predefined-date',$("#p_posting_period_id :selected").data('mindate'))
                $("#p_posting_date_to >input").attr('data-predefined-date',$("#p_posting_period_id :selected").data('maxdate'))
*/
            }else{
                resetPostingDateRelatedFields();
                destroyPostingCalenders();
                postingDateFromCalendarClickCounter = 0;
                postingDateToCalendarClickCounter = 0;

                /*$("#p_posting_date_from >input").val(moment($("#p_fiscal_year_id :selected").data('mindate')).format("DD-MM-YYYY"))
                $("#p_posting_date_to >input").val(moment($("#p_fiscal_year_id :selected").data('mindate')).format("DD-MM-YYYY"))*/
/*

                $("#p_posting_date_from >input").attr('data-predefined-date',$("#p_fiscal_year_id :selected").data('mindate'))
                $("#p_posting_date_to >input").attr('data-predefined-date',$("#p_fiscal_year_id :selected").data('maxdate'))
*/

            }
        })
        $("#p_gl_subsidiary_id").select2();
        $("#p_gl_subsidiary_id").on('change',function () {
            $('#p_customer_id').val(null).trigger('change');
            reportGetCustomers("#p_customer_id",APP_URL+'/account-receivable/ar-customers/'+$(this).find(":selected").val());
        });


        function setPeriods(response) {
            if (response.fromOptions != '') {
                $("#p_posting_period_id_from").html(response.fromOptions);
                $("#p_posting_period_id").html(response.fromOptions);
                $("#p_posting_period_id").trigger('change');
            }
            if (response.toOptions != '') {
                $("#p_posting_period_id_to").html(response.toOptions);
            }
        }

        $("#p_user_id").select2();
    });
</script>


<div class="row mt-1">
    @if($report)
        {{-- This input field for sending employee id to the back-end for showing user name on the report page. --}}
        <input type="hidden" name="p_login_user" value="{{ \Illuminate\Support\Facades\Auth::user()->emp_id }}">
        @if($report->params)
            @foreach($report->params as $reportParam)
                {{--@dd($reportParam)--}}
                {{--@if($reportParam->component == 'fiscal_year')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @if($fiscalYear)
                                @foreach($fiscalYear as $value)
                                    <option value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @else--}}
                @if($reportParam->component == 'cost_center_dept')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select One</option>
                            @if($lCostCenterDpt)
                                @foreach($lCostCenterDpt as $value)
                                    <option
                                        value="{{$value->cost_center_dept_id}}">{{ $value->cost_center_dept_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($reportParam->component == 'budget_head')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select One</option>
                        </select>
                    </div>
                @elseif($reportParam->component == 'budget_type')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select One</option>
                            @if($lBudgetType)
                                @foreach($lBudgetType as $value)
                                    <option value="{{$value->budget_type_id}}">{{ $value->budget_type_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($reportParam->component == 'fiscal_year')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @foreach($fiscalYear as $year)
                                <option value="{{$year->fiscal_year_id}}"
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($year->period_beg_date) }}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($year->period_end_date) }}">{{$year->fiscal_year_name}}</option>
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
                @elseif($reportParam->component == 'default_bill_register')
                    <div class="col-md-3">
                        <label for="p_bill_reg_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Register</label>
                        <select class="form-control select2" id="p_bill_reg_id" name="p_bill_reg_id"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif></select>
                    </div>

                @elseif($reportParam->component == 'bill_section')
                    <div class="col-md-3">
                        <label for="p_bill_sec_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Section</label>
                        <select @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                @endif name="p_bill_sec_id" class="form-control " id="p_bill_sec_id">
                            <option value="">Select a bill</option>
                            @foreach($budgetBillSections as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($reportParam->component == 'bill_register')
                    <div class="col-md-3">
                        <label for="p_bill_reg_id"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Bill
                            Register</label>
                        <select class="form-control select2" id="p_bill_reg_id" name="p_bill_reg_id"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif></select>
                    </div>
                @elseif($reportParam->component == 'date_range')
                    <div class="col-md-3">
                        <label for="p_beg_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Document
                            From
                            Date</label>
                        <div class="input-group date datePiker" id="p_beg_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   class="form-control datetimepicker-input"
                                   value="" name="p_beg_date"
                                   data-toggle="datetimepicker"
                                   data-target="#p_beg_date"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#p_beg_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="p_end_date"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Document
                            To Date</label>
                        <div class="input-group date datePiker" id="p_end_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   class="form-control datetimepicker-input"
                                   value="" name="p_end_date"
                                   data-toggle="datetimepicker"
                                   data-target="#p_end_date"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                   @endif onautocomplete="off"/>
                            <div class="input-group-append" data-target="#p_end_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                @elseif($reportParam->component == 'approval_status_without_rejected')
                    <div class="col-md-3">
                        <label for="p_approval_status"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">Approval
                            Status</label>
                        <select @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                @endif name="p_approval_status" class="form-control " id="p_approval_status">
                            <option value="">Select All</option>
                            @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                @if ( $key != \App\Enums\ApprovalStatus::REJECT)
                                    <option
                                        value="{{$key}}" {{--{{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}}--}} > {{ $value}} </option>
                                @endif
                            @endforeach
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
                @elseif($reportParam->component == 'posting_date')
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
                @elseif($reportParam->component == 'vendor')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                        </select>
                    </div>
                @elseif($reportParam->component == 'figure_format')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            @foreach(\App\Enums\BudgetMonitoring\ReportFigureFormat::REPORT_FIGURE_FORMAT as $key=>$format)
                                <option value="{{$key}}">{{$format}}</option>
                            @endforeach
                        </select>
                    </div>

                @else
                    <div class="col">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <input type="text" name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                               class="form-control"
                               @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                    </div>
                @endif
            @endforeach
            <input type="hidden" id="p_user_id" name="p_user_id" value="{{\Illuminate\Support\Facades\Auth::id()}}">
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
            <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Generate Report</button>
        </div>
    @endif
</div>
{{--<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/forms/select/form-select2.min.js')}}"></script>--}}
<script type="text/javascript">


    $(document).ready(function () {
        $("#p_gl_subsidiary_id").select2();
        $("#p_gl_subsidiary_id").on('change', function () {
            $('#p_party_id').val(null).trigger('change');
            reportGetVendors("#p_party_id", APP_URL + '/account-payable/ap-vendors/' + $(this).find(":selected").val());
        });

        dateRangePicker("#p_beg_date", "#p_end_date");

        $('#p_bill_sec_id').change(function (e) {
            $("#p_bill_reg_id").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#p_bill_reg_id', APP_URL + '/budget-monitoring/ajax/bill-section-by-register/' + billSectionId, '', '');
        });
        $("#p_dept_id").select2();
        let dept_id = $("#p_dept_id").val();
        let calendar_id = $("#p_fiscal_year_id").val();
        //$("#p_head_id").select2().empty();
        $("#p_head_id").select2({
            placeholder: "<Select>",
            width: '100%',
            allowClear: true,
            ajax: {
                url: APP_URL + '/budget-monitoring/ajax/budget-head-list', // '/ajax/Invoice'
                data: function (params) {
                    if (params.term) {
                        if (params.term.trim().length < 1) {
                            return false;
                        }
                    }
                    params.department = dept_id;
                    params.calendar = calendar_id;
                    return params;
                },
                dataType: 'json',
                processResults: function (data) {
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.budget_head_id;
                        //obj.text = obj.training_number;
                        //obj.text = obj.invoice_id+' ('+obj.invoice_num+')';
                        obj.text = obj.budget_head_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                },
            }
        });
        /**0002709: BM03: Head wise Budget Informaion (Budget Monitoring Report)
         $("#p_dept_id").select2().change(function (e) {
            let dept_id = $(this).val();
            let calendar_id = $("#p_fiscal_year_id").val();
            $("#p_head_id").select2().empty();
            $("#p_head_id").select2({
                placeholder: "<Select>",
                width: '100%',
                allowClear: true,
                ajax: {
                    url: APP_URL + '/budget-monitoring/ajax/budget-head-list', // '/ajax/Invoice'
                    data: function (params) {
                        if (params.term) {
                            if (params.term.trim().length < 1) {
                                return false;
                            }
                        }
                        params.department = dept_id;
                        params.calendar = calendar_id;
                        return params;
                    },
                    dataType: 'json',
                    processResults: function (data) {
                        var formattedResults = $.map(data, function (obj, idx) {
                            obj.id = obj.budget_head_id;
                            //obj.text = obj.training_number;
                            //obj.text = obj.invoice_id+' ('+obj.invoice_num+')';
                            obj.text = obj.budget_head_name;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    },
                }
            });
        })*/
        let postingDateCalendarClickCounter = 0;
        let postingDateFromCalendarClickCounter = 0;
        let postingDateToCalendarClickCounter = 0;
        $("#p_fiscal_year_id").on('change', function () {
            resetPostingDateRelatedFields();
            destroyPostingCalenders();
        })

        function resetPostingDateRelatedFields() {
            $("#p_posting_date >input").val("");
            $("#p_posting_date_from >input").val("");
            $("#p_posting_date_to >input").val("");
        }

        function destroyPostingCalenders() {
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
            let minDate = $("#p_fiscal_year_id :selected").data("mindate");
            let maxDate = $("#p_fiscal_year_id :selected").data("maxdate");
            let currentDate = $("#p_fiscal_year_id :selected").data("mindate");
            datePickerOnPeriod("#p_posting_date", minDate, maxDate, currentDate);
        });
        $("#p_posting_date_from").on('click', function () {
            let minDate = null;
            let maxDate = null;
            let currentDate = false;

            postingDateFromCalendarClickCounter++;
            minDate = $("#p_fiscal_year_id :selected").data("mindate");
            maxDate = $("#p_fiscal_year_id :selected").data("maxdate");
            currentDate = $("#p_fiscal_year_id :selected").data("mindate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        $("#p_posting_date_to").on('click', function () {
            let minDate = null;
            let maxDate = null;
            let currentDate = false;

            destroyPostingCalenders();
            postingDateToCalendarClickCounter++;
            minDate = $("#p_fiscal_year_id :selected").data("mindate");
            maxDate = $("#p_fiscal_year_id :selected").data("maxdate");
            currentDate = $("#p_fiscal_year_id :selected").data("maxdate");

            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });
    });


</script>


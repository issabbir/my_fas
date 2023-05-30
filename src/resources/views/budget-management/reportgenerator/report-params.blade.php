<div class="row mt-1">
    @if($report)
        {{-- This input field for sending employee id to the back-end for showing user name on the report page. --}}
        <input type="hidden" name="p_login_user" value="{{ \Illuminate\Support\Facades\Auth::user()->emp_id }}">
        @if($report->params)
            @foreach($report->params as $reportParam)
                @if($reportParam->component == 'fiscal_year')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select One</option>
                            @if($fiscalYear)
                                @foreach($fiscalYear as $value)
                                    <option value="{{$value->fiscal_year_id}}"
                                        {{isset($value->calendar_status) && ($value->calendar_status == \App\Enums\Gl\CalendarStatus::OPENED) ? 'selected' : ''}}>
                                        {{ $value->fiscal_year_name}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @elseif($reportParam->component == 'cost_center_dept')
                    <div class="col-md-3">
                        <label for="{{$reportParam->param_name}}"
                               class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                        <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                class="form-control select2"
                                @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif >
                            <option value="">Select One</option>
                            @if($lCostCenterDpt)
                                @foreach($lCostCenterDpt as $value)
                                    <option value="{{$value->cost_center_dept_id}}">{{ $value->cost_center_dept_name}}</option>
                                @endforeach
                            @endif
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

    });


</script>


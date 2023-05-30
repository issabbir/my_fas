@extends('layouts.default')

@section('title')
    Report Generator
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Report Generator</div>
                    <hr>
                    <form id="report-generator" method="POST" action="" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label class="required" for="report">Report</label>
                                <select name="report" id="report" required class="form-control">
                                    <option value="">Select Report</option>
                                    @foreach($reports as $report)
                                        <option value="{{ $report->report_id }}"
                                                data-report-name="{{$report->report_name}}">{{ $report->report_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             {{--<div class="col-md-1" id="report-params"></div>--}}
                        </div>
                        <div id="report-params"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $("#report").on('change', function (e) {
            e.preventDefault();
            let reportId = $(this).val();
            let reportName = $(this).find(":selected").data('report-name');

            if (!nullEmptyUndefinedChecked(reportId) && !nullEmptyUndefinedChecked(reportName)) {
                $.ajax({
                    type: "GET",
                    url: APP_URL + '/budget-management/report-generator-params/' + reportId,
                    success: function (data) {
                        $("#report-generator").attr("action", APP_URL + '/report/render/' + reportName)
                        $("#report-params").html(data);
                    },
                    error: function (err) {
                        alert('error', err);
                    }
                });
            } else {
                $("#report-generator").attr('action', '');
                $("#report-params").html('');
            }
        });
    </script>
@endsection

@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')

    <div class="card">
        <div class="card-header bg-dark text-white p-75">FDR Maturity Authorize</div>
        <div class="card-body border">
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <fieldset class="border p-1 mb-1">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <form method="POST" id="fdr-interest-prov-hist-search-form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group make-select2-readonly-bg">
                                <label for="inv_type_id" class="">Investment Type</label>
                                <select name="inv_type_id" class="custom-select form-control form-control-sm required search-param select2" id="inv_type_id">
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($invTypeList as $value)
                                        <option value="{{$value->investment_type_id}}"
                                            {{old('inv_type_id', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $value->investment_type_id ? 'selected' : ''}}
                                            {{--{{isset($filterData) ? (($value->investment_type_id == $filterData[0]) ? 'selected' : '') : ''}}--}}>{{$value->investment_type_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="fiscal_year" class="">Fiscal Year</label>
                                <select name="fiscal_year" class="custom-select form-control form-control-sm required search-param" id="fiscal_year">
                                    {{--<option value="" >&lt;Select&gt;</option>--}}
                                    @foreach($fiscalYear as $year)
                                        <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[1]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="period" class="">Posting Period</label>
                                <select name="period" class="custom-select form-control form-control-sm required search-param" id="period">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="authorization_status" class="">Approval Status</label>
                                <select class="form-control form-control-sm select2 search-param" name="authorization_status" id="authorization_status">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        {{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}
                                        <option {{isset($filterData) ? (($key == $filterData[3]) ? 'selected' : '') : (($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : '')}} value="{{$key}}" {{--{{ (($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : '')}}--}} > {{ $value}} </option>
                                        {{--@endif--}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
            @include('cm.fdr-maturity-authorize.list')
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        function fiscalYear(){
            $("#fiscal_year").on('change',function () {
                getPostingPeriod($("#fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);  //Route Call General Leader
            });
        }

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }

        function paramWiseSearchList(){
            $(".search-param").on('change', function () {
                oTable.draw();
            });

            let oTable = $('#fdr-maturity-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/cash-management/fdr-maturity-authorize-search',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.inv_type_id = $('#inv_type_id :selected').val();
                        params.fiscal_year = $('#fiscal_year').val();
                        params.period = $('#period').val();
                        params.authorization_status = $('#authorization_status').val();
                    }
                },
                "columns": [
                    {"data": "posting_date"},
                    {"data": "bank_name"},
                    {"data": "branch_name"},
                    {"data": "fdr_no"},
                    {"data": "total_amount"},
                    {"data": "status"},
                    {"data": "action", "orderable": false},
                ],
                "columnDefs": [
                    {targets: 4, className: 'text-right-align'},
                    {targets: 5, className: 'text-center'},
                ]
            });
        }

        $(document).ready(function () {
            paramWiseSearchList();
            fiscalYear();
            getPostingPeriod($("#fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod,{{isset($filterData) ? $filterData[1] : ''}});  //Route Call General Leader
        });
    </script>
@endsection

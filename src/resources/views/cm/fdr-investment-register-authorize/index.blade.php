@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')

    <div class="card">
        <div class="card-header bg-dark text-white p-75">FDR Investment Authorize</div>
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
                <form method="POST" id="fdr-investment-reg-search-form">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group make-select2-readonly-bg">
                                <label for="inv_type_id" class="">Investment Type</label>
                                <select name="inv_type_id" class="custom-select form-control form-control-sm required search-param select2" id="inv_type_id">
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($invTypeList as $value)
                                        <option value="{{$value->investment_type_id}}"
                                            {{old('inv_type_id', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $value->investment_type_id ? 'selected' : ''}}
                                            {{--{{isset($filterData) ? (($value->investment_type_id == $filterData[0]) ? 'selected' : '') : ''}}--}} >{{$value->investment_type_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="bank_id" class="">Bank</label>
                                <select name="bank_id" class="custom-select form-control form-control-sm required search-param" id="bank_id"
                                        data-cm-bank-id="{{ isset($filterData) ? $filterData[1] : '' }}">
                                    <option value="" >&lt;Select&gt;</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="branch_id" class="">Branch</label>
                                <select name="branch_id" class="custom-select form-control form-control-sm required search-param select2"
                                        id="branch_id"
                                        data-branch-code="{{isset($filterData) ? $filterData[2] : ''}}">
                                    <option value="" >&lt;Select&gt;</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="action_type" class="">Action Type</label>
                                <select class="form-control form-control-sm select2 search-param" name="action_type" id="action_type">
                                    <option value="">All</option>
                                    @foreach(\App\Enums\ApprovalStatus::CRUD_ACTION as $key=>$value)
                                        @if ( $key != \App\Enums\ApprovalStatus::DELETE)
                                        <option value="{{$key}}" > {{ $value}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="authorization_status" class="">Approval Status</label>
                                <select class="form-control form-control-sm select2 search-param" name="authorization_status" id="authorization_status">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        {{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}
                                        <option {{isset($filterData) ? (($key == $filterData[4]) ? 'selected' : '') : (($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : '')}} value="{{$key}}" {{--{{ (($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : '')}}--}} > {{ $value}} </option>
                                        {{--@endif--}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
            @include('cm.fdr-investment-register-authorize.list')
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function listBranch() {
            $('#bank_id').change(function (e) {
                e.preventDefault();
                let bankId = $(this).val();
                $("#branch_id").val('');
                selectCmBranch('#branch_id', APP_URL + '/cash-management/ajax/cm-branches/' +bankId, '/cash-management/ajax/cm-branch/', '');
            });
        }

        function paramWiseSearchList(){
            $(".search-param").on('change', function () {
                oTable.draw();
            });

            let oTable = $('#fdr-investment-reg-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/cash-management/fdr-investment-register-authorize-search',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.inv_type_id = $('#inv_type_id :selected').val();
                        params.bank_id = $('#bank_id').val();
                        params.branch_id = $('#branch_id').val();
                        params.action_type = $('#action_type').val();
                        params.authorization_status = $('#authorization_status').val();
                    }
                },
                "columns": [
                    {"data": "investment_date"},
                    {"data": "fdr_no"},
                    {"data": "investment_amount"},
                    {"data": "interest_rate"},
                    {"data": "maturity_date"},
                    {"data": "action_type"},
                    {"data": "status"},
                    {"data": "action", "orderable": false},
                ],
                "columnDefs": [
                    {targets: 2, className: 'text-right-align'},
                ]
            });
        }

        $(document).ready(function () {

            listBranch();
            selectCmBankInfo('#bank_id', APP_URL + '/cash-management/ajax/cm-banks', APP_URL+'/cash-management/ajax/cm-bank/', '');
            paramWiseSearchList();

        });
    </script>
@endsection

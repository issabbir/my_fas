@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }

        .text-right-align {
            text-align: right;
        }

        /*.bootstrap-datetimepicker-widget table td.active, .bootstrap-datetimepicker-widget table td.active:hover {
             background-color: transparent;
             color: #727E8C;
             text-shadow: 0 0 #f3f0f0;
        }*/
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="card-header bg-dark text-white p-75">Budget Concurrence Transaction Authorize</div>
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

            <fieldset class="border p-1 mt-1 mb-1">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <form method="POST" id="bud-concurrence-tran-auth-search-form">

                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="fiscal_year_id" class="col-form-label col-md-5 required">Financial Year</label>
                            <select class=" form-control form-control-sm select2 search-param" id="fiscal_year_id"
                                    name="fiscal_year_id" required>
                                @foreach($CurrentFinancialYearList as $value)
                                    <option
                                        {{isset($filterData) ? (($value->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}}
                                        value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="bill_sec_id" class="col-form-label">Bill Section</label>
                            <select name="bill_sec_id" class="form-control form-control-sm search-param select2 "
                                    id="bill_sec_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($lBillSecList as $value)
                                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">

                            <label for="auth_function_type" class="col-form-label col-md-5">Function Type</label>

                            <select name="auth_function_type"
                                    class="form-control form-control-sm select2 search-param"
                                    id="auth_function_type">
                                @foreach(\App\Enums\ApprovalStatus::AUTHORIZE_FUN_TYPE as $key=>$value)
                                    <option
                                        value="{{$key}}" {{isset($filterData) ? (($key == $filterData[1]) ? 'selected' : '') : (($key == \App\Enums\ApprovalStatus::MAKE) ? 'selected' : '')}}>
                                        {{ $value}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-3">

                            <label for="approval_status" class="col-form-label col-md-5">Approval Status</label>

                            <select class="form-control form-control-sm search-param" name="approval_status"
                                    id="approval_status">
                                <option value="">&lt;Select&gt;</option>
                                @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                    <option
                                        value="{{$key}}" {{isset($filterData) ? (($key == $filterData[2]) ? 'selected' : '') : (($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : '')}}>
                                        {{ $value}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    {{--<div class="row">
                        <div class="col-md-5">
                            <div class="form-group row">
                                <label for="fiscal_year_id" class="col-md-3 required">Financial Year</label>
                                <div class="col-md-8">
                                    <select class=" form-control form-control-sm select2" id="fiscal_year_id"
                                            name="fiscal_year_id" required>
                                        @foreach($CurrentFinancialYearList as $value)
                                            <option
                                                value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="department" class="col-md-3">Dept/Cost Center</label>
                                <div class="col-md-8">
                                    <select class=" form-control form-control-sm select2" id="department" name="department" >
                                        <option value="">&lt;Select&gt;</option>
                                        --}}{{--@foreach($CurrentFinancialYearList as $value)
                                            <option value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                        @endforeach--}}{{--
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bill_sec_id" class="col-md-3">Bill Section</label>
                                <div class="col-md-8">
                                    <select class=" form-control form-control-sm select2" id="bill_sec_id"
                                            name="bill_sec_id">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($lBillSecList as $value)
                                            <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bill_reg_id" class="col-md-3">Bill Register</label>
                                <div class="col-md-8">
                                    <select class="form-control form-control-sm" id="bill_reg_id" name="bill_reg_id">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group row">
                                <label for="vendor_id" class="col-md-3">Party/Vendor</label>
                                <div class="col-md-8">
                                    <select class=" form-control form-control-sm select2" id="vendor_id"
                                            name="vendor_id">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($vendorList as $value)
                                            <option value="{{$value->vendor_id}}">{{ $value->vendor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="transaction_date" class="col-md-3">Transaction Date</label>
                                <div class="input-group date transaction_date col-md-4" id="transaction_date"
                                     data-target-input="nearest">
                                    <input type="text" name="transaction_date" id="transaction_date_field"
                                           class="form-control form-control-sm datetimepicker-input transaction_date"
                                           data-target="#transaction_date" data-toggle="datetimepicker"
                                           value=""
                                           --}}{{--data-predefined-date=""--}}{{--
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append transaction_date" data-target="#transaction_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted form-text"></div>
                            </div>
                            --}}{{--<div class="col-md-3">
                                <div class="form-group">
                                    <label for="posting_batch_id" class="">Posting Batch Id</label>
                                    <input class="form-control form-control-sm6" id="posting_batch_id" name="posting_batch_id"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="vendor_id" class="">Vendor</label>
                                    <select class="custom-select form-control form-control-sm6 select2" id="vendor_id" name="vendor_id">
                                        <option value="">Select One</option>
                                        @foreach($vendorList as $value)
                                            <option value="{{$value->vendor_id}}">{{$value->vendor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>--}}{{--
                            <div class="form-group row">
                                <label for="authorization_status" class="col-md-3">Approval Status</label>
                                <div class="col-md-3">
                                    <select class="form-control form-control-sm" name="authorization_status"
                                            id="authorization_status">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                            @if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)
                                                <option
                                                    value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} > {{ $value}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 offset-4">
                                    <button type="submit" class="btn btn-sm btn-primary"><i
                                            class="bx bx-search font-size-small"></i><span
                                            class="align-middle ">Search</span></button>
                                    <button type="button" class="btn btn-sm btn-secondary " id="reset"><i
                                            class="bx bx-reset font-size-small"></i><span
                                            class="align-middle">Reset</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    {{--<div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="fiscal_year_id" class="required">Financial Year</label>
                                <select class="form-control form-control-sm select2" id="fiscal_year_id" name="fiscal_year_id" required>
                                    <option value="">Select One</option>
                                    @foreach($CurrentFinancialYearList as $value)
                                        <option value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="department" class="required">Dept/Cost Center</label>
                                <select class="form-control form-control-sm select2" id="department" name="department" required>
                                    <option value="">Select One</option>
                                    --}}{{--@foreach($CurrentFinancialYearList as $value)
                                        <option value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                                    @endforeach--}}{{--
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="bill_sec_id" class="">Bill Section</label>
                                <select class="form-control form-control-sm select2" id="bill_sec_id" name="bill_sec_id">
                                    <option value="">Select One</option>
                                    @foreach($lBillSecList as $value)
                                        <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="dpt_id" class="">Bill Register</label>
                                <select class="form-control form-control-sm" id="bill_reg_id" name="bill_reg_id">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaction_date" class="">Transaction Date</label>
                                <div class="input-group date transaction_date" id="transaction_date" data-target-input="nearest">
                                    <input type="text" name="transaction_date" id="transaction_date_field"
                                           class="form-control form-control-sm datetimepicker-input transaction_date"
                                           data-target="#transaction_date" data-toggle="datetimepicker"
                                           value=""
                                           --}}{{--data-predefined-date=""--}}{{--
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append transaction_date" data-target="#transaction_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted form-text"></div>
                            </div>
                        </div>
                        --}}{{--<div class="col-md-3">
                            <div class="form-group">
                                <label for="posting_batch_id" class="">Posting Batch Id</label>
                                <input class="form-control form-control-sm" id="posting_batch_id" name="posting_batch_id"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="vendor_id" class="">Vendor</label>
                                <select class="form-control form-control-sm select2" id="vendor_id" name="vendor_id">
                                    <option value="">Select One</option>
                                    @foreach($vendorList as $value)
                                        <option value="{{$value->vendor_id}}">{{$value->vendor_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>--}}{{--
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="authorization_status" class="">Approval Status</label>
                                <select class="form-control form-control-sm" name="authorization_status" id="authorization_status">
                                    <option value="">Select All</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        --}}{{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}{{--
                                        <option
                                            value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} > {{ $value}} </option>
                                       --}}{{-- @endif--}}{{--
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-end pl-0 ">
                            <div class="mt-2">
                                <button type="submit" class="btn btn-sm btn-primary mb-2 "><i class="bx bx-search font-size-small"></i><span class="align-middle ">Search</span></button>
                                <button type="button" class="btn btn-sm btn-secondary mb-2" id="reset"><i class="bx bx-reset font-size-small"></i><span class="align-middle">Reset</span></button>
                                <button type="reset" class="btn btn-sm btn-secondary mb-2 d-none" id="resetMain"></button>
                            </div>
                        </div>
                    </div>--}}
                </form>
            </fieldset>

            @include('budget-monitoring.concurrence-transaction-authorize.list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function deptCostCenter() {
            $('#fiscal_year_id').on('change', function (e) {
                e.preventDefault();
                let fiscalYearId = $(this).val();

                if (!nullEmptyUndefinedChecked(fiscalYearId)) {
                    let request = $.ajax({
                        url: APP_URL + "/budget-monitoring/ajax/dept-period-on-calender",
                        data: {
                            calendarId: fiscalYearId,
                        }
                    });

                    request.done(function (data) {
                        $("#department").html(data.department);
                    })

                    request.fail(function (jqXHR, textStatus) {
                        swal.fire({
                            text: jqXHR.responseJSON['message'],
                            type: 'warning',
                        })
                    })
                }

            });
        }

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/budget-monitoring/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }

        $(".search-param").on('change', function () {
            oTable.draw();
        });

        var oTable = $('#bud-concurrence-tran-auth-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 10,
            bFilter: true,
            ordering: false,
            lengthMenu: [[10, 20, -1], [10, 20, 'All']],
            ajax: {
                url: APP_URL + '/budget-monitoring/concurrence-transaction-authorization-search-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.fiscal_year_id = $('#fiscal_year_id').val();
                    params.auth_function_type = $('#auth_function_type').val();
                    params.bill_sec_id = $('#bill_sec_id').val();
                    params.approval_status = $('#approval_status').val();
                    /*params.department_id = $('#department').val();
                    params.bill_sec_id = $('#bill_sec_id').val();
                    params.bill_reg_id = $('#bill_reg_id').val();
                    params.vendor_id = $('#vendor_id').val();
                    params.transaction_date_field = $('#transaction_date_field').val();*/
                }
            },
            "order": [],
            "columns": [
                /* {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                /*{"data": "budget_booking_id"},*/
                {"data": "document_date"},
                /*{"data": "bill_sec_name"},
                {"data": "bill_reg_name"},*/
                {"data": "document_no"},
                {"data": "department_name"},
                {"data": "budget_head_name"},
                {"data": "budget_type_name"},
                {"data": "tender_type_name"},
                {"data": "estimate_amount"},
                {"data": "budget_booking_amt"},
                {"data": "status"},
                {"data": "action", "orderable": false},
            ],
            "columnDefs": [
                {targets: 6, className: "text-right-align"},
                {targets: 7, className: "text-right-align"},
            ]
        });

        function checkConcurrenceTransAuthForm() {
            $(document).on("click", ".approve-reject-btn", function (e) {
                e.preventDefault();
                let approval_status = $(this).val();
                let mapId = $(this).data('map');
                let mode = $(this).data('mode');
                let swal_input_type;

                $('#approve_reject_value').val(approval_status);

                /*if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else if(approval_status == '{{\App\Enums\ApprovalStatus::CANCEL}}'){
                    approval_status_val = '{{\App\Enums\ApprovalStatus::CANCEL}}';
                    swal_input_type = 'text';
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }*/

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Concurrence Transaction Authorize',
                    type: 'warning',
                    /* input: swal_input_type,
                     inputPlaceholder: 'Reason For ' + approval_status_val+'?',
                     inputValidator: (result) => {
                         return !result && 'You need to provide a comment'
                     },*/
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (result) {
                    if (result.value) {
                        let request = $.ajax({
                            url: '{{route("concurrence-transaction-authorization.approve")}}',
                            data: {wk_map_id: mapId, approve_reject_value: 'A', mode: mode},
                            dataType: "JSON",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (res.response_code == "1") {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    oTable.draw();
                                });
                            } else {
                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                            //Swal.fire({text:textStatus+jqXHR,type:'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                })
            });
        }

        $(document).ready(function () {
            checkConcurrenceTransAuthForm();
            deptCostCenter();
            $('#fiscal_year_id').trigger('change');
            $('#bud-concurrence-tran-auth-search-form').on('submit', function (e) {
                e.preventDefault();
                oTable.draw();
            });

            $('#reset').on('click', function () {
                $("#fiscal_year_id").val('').trigger('change');
                $("#department").val('').trigger('change');
                $("#bill_sec_id").val('').trigger('change');
                $("#bill_reg_id").val('').trigger('change');
                $("#transaction_date_field").val('').trigger('change');
                $("#auth_function_type").val('').trigger('change');
                $("#approval_status").val('').trigger('change');
                $('#resetMain').click();
                oTable.draw();
            });

            listBillRegister();
            datePicker("#transaction_date");
        });

    </script>
@endsection

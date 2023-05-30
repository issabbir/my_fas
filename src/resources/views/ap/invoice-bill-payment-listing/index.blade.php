@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    {{--<style type="text/css" rel="stylesheet">
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1em + .94rem + 0.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 20px;
        }
        .form-group {
            margin-bottom: .3rem;
        }
    </style>--}}
@endsection
@section('content')

    <div class="card">
        <div class="card-header bg-dark text-white p-75">Search Invoice Payment</div>
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
            <form method="POST" id="invoice-bill-pay-search-form">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="th_fiscal_year" class="">Fiscal Year</label>
                            <select name="th_fiscal_year"
                                    class="custom-select form-control form-control-sm  required search-param"
                                    id="th_fiscal_year">
                                @foreach($fiscalYear as $year)
                                    <option
                                        {{isset($filterData) ? (($year->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="period" class="required">Posting Period</label>
                            <select class="custom-select form-control form-control-sm select2 search-param" id="period"
                                    name="period" required>
                                {{--TODO: Block-Start Pavel-03-07-22--}}
                                {{--<option value="">Select One</option>
                                @foreach($postPeriodList as $key=>$value)
                                    <option
                                        {{  ((old('period') ==  $value->posting_period_id) || ($value->posting_period_status == 'O')) ? "selected" : "" }}
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_beg_date)}}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_end_date)}}"
                                        data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($value->current_posting_date)}}"
                                        data-postingname="{{ $value->posting_period_name}}"
                                        value="{{$value->posting_period_id}}">{{ $value->posting_period_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>

                    {{--TODO: Block-Start Pavel-03-07-22--}}
                    {{--<div class="col-md-3">
                        <div class="form-group">
                            <label for="posting_date" class="">Posting Date</label>
                            <div class="input-group date posting_date" id="posting_date" data-target-input="nearest">
                                <input type="text" name="posting_date" id="posting_date_field" autocomplete="off"
                                       class="form-control form-control-sm datetimepicker-input posting_date"
                                       data-target="#posting_date" data-toggle="datetimepicker"
                                       value="{{ old('posting_date','') }}"
                                       data-predefined-date="{{ old('posting_date', '') }}"

                                       --}}{{--data-predefined-date=""--}}{{--
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append posting_date" data-target="#posting_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted form-text"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="posting_batch_id" class="">Posting Batch Id</label>
                            <input class="form-control form-control-sm" id="posting_batch_id" name="posting_batch_id"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vendor_id" class="">Vendor</label>
                            <select class="custom-select form-control form-control-sm select2" id="vendor_id" name="vendor_id">
                                <option value="">Select One</option>
                                @foreach($vendorList as $value)
                                    <option value="{{$value->vendor_id}}">{{$value->vendor_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    {{--TODO: Block-End Pavel-03-07-22--}}

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="bill_sec_id" class="">Bill Section</label>
                            <select class="custom-select form-control form-control-sm select2 search-param"
                                    id="bill_sec_id" name="bill_sec_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($lBillSecList as $value)
                                    <option
                                        {{isset($filterData) ? (($value->bill_sec_id == $filterData[2]) ? 'selected' : '') : ''}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="bill_reg_id" class="">Bill Register</label>
                            <select class="form-control form-control-sm select2 search-param" id="bill_reg_id"
                                    name="bill_reg_id">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="authorization_status" class="">Approval Status</label>
                            <select class="form-control form-control-sm select2 search-param"
                                    name="authorization_status" id="authorization_status">
                                <option value="">&lt;Select&gt;</option>
                                @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                    {{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}
                                    {{--<option
                                        value="{{$key}}" --}}{{--{{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}}--}}{{-- > {{ $value}} </option>--}}
                                    {{--@endif--}}
                                    <option
                                        {{isset($filterData) ? (($key == $filterData[4]) ? 'selected' : '') : ''}} value="{{$key}}">{{ $value}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--TODO: Block-Start Pavel-03-07-22--}}
                    {{--<div class="col-md-3 d-flex justify-content-end pl-0 ">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-sm btn-primary mb-2 "><i class="bx bx-search font-size-small"></i><span
                                    class="align-middle ">Search</span></button>
                            <button type="button" class="btn btn-sm btn-secondary mb-2" id="reset"><i
                                    class="bx bx-reset font-size-small"></i><span class="align-middle">Reset</span></button>
                            <button type="reset" class="btn btn-sm btn-secondary mb-2 d-none" id="resetMain"></button>
                        </div>
                    </div>--}}
                    {{--TODO: Block-End Pavel-03-07-22--}}
                </div>
            </form>

            @include('ap.invoice-bill-payment-listing.list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        //let postingCalendarClickCounter = 0; //Block Pavel-03-07-22

        /** Add Block-Start Pavel-03-07-22 **/
        function fiscalYear() {
            $("#th_fiscal_year").on('change', function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);  //Route Call General Leader
            });
        }

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }

        /** Add Block-End Pavel-03-07-22 **/

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }

        function searchParamWiseList() {
            $(".search-param").on('change', function () {
                oTable.draw();
            });

            var oTable = $('#invoice-bill-pay-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/account-payable/invoice-bill-payment-search-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.fiscalYear = $('#th_fiscal_year :selected').val();
                        params.period = $('#period').val();
                        params.bill_sec_id = $('#bill_sec_id').val();
                        params.bill_reg_id = $('#bill_reg_id').val();
                        params.authorization_status = $('#authorization_status').val();
                        /*params.period = $('#period').val();
                        params.posting_date_field = $('#posting_date_field').val();
                        params.posting_batch_id = $('#posting_batch_id').val();
                        params.vendor_id = $('#vendor_id').val();
                        params.bill_sec_id = $('#bill_sec_id').val();
                        params.bill_reg_id = $('#bill_reg_id').val();
                        params.authorization_status = $('#authorization_status').val();*/
                    }
                },
                "columns": [
                    {"data": "document_date"},
                    {"data": "document_no"},
                    {"data": "cheque_no"},
                    {"data": "cheque_date"},
                    {"data": "payment_amount"},
                    {"data": "vendor_name"},
                    {"data": "status"},
                    {"data": "cheque_print"},
                    {"data": "action", "orderable": false},
                ],
                "columnDefs": [
                    {targets: 4, className: 'text-right-align'},
                ]
            });
        }


        $(document).ready(function () {
            listBillRegister();
            fiscalYear();
            searchParamWiseList();
            getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod,{{isset($filterData) ? $filterData[1] : ''}}); //Add-Pavel: 03-07-22 //Route Call General Leader

            /** Block-Start Pavel-03-07-22 **/
            /*$('#invoice-bill-pay-search-form').on('submit', function (e) {
                e.preventDefault();
                $('.trans-dtl-sec').hide();
                oTable.draw();
            });

            $('#reset').on('click', function () {
                $("#posting_date_field").val('').trigger('change');
                $("#vendor_id").val('').trigger('change');
                $("#bill_sec_id").val('').trigger('change');
                $("#bill_reg_id").val('').trigger('change');
                $("#authorization_status").val('').trigger('change');
                $('#resetMain').click();
                oTable.draw();
            });

            $("#posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });*/
            /** Block-End Pavel-03-07-22 **/
            //datePicker("#posting_date");
        });
    </script>
@endsection

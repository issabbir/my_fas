<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৯ PM
 */
?>
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
    {{--Removed on: 06/06/2022, sujon--}}
    {{-- @include('ap.invoice-bill-listing.form')--}}
    <div class="card">
        <div class="card-body">
            <h5 style="text-decoration: underline">Invoice/Bill List</h5>
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="th_fiscal_year" class="">Fiscal Year</label>
                    <select name="th_fiscal_year"
                            class="form-control required search-param"
                            id="th_fiscal_year">
                        @foreach($fiscalYear as $year)
                            <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="period" class="">Posting Period</label>
                    <select class="form-control search-param" id="period" name="period" required>
                        {{--@foreach($data['postingDate'] as $post)
                            <option
                                {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                data-postingname="{{ $post->posting_period_name}}"
                                value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                            </option>
                        @endforeach--}}
                    </select>
                </div>
                {{-- Start Add Block Pavel-08-06-22/09-06-22 --}}
                <div class="form-group col-md-2">
                    <label for="bill_section" class="">Bill Section</label>
                    <select name="bill_section" class="form-control form-control-sm select2 search-param"
                            id="bill_section">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($data['billSecs'] as $value)
                            <option {{isset($filterData) ? (($value->bill_sec_id == $filterData[2]) ? 'selected' : '') : ''}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="bill_reg_id" class="">Bill Register</label>
                    <select class="form-control form-control-sm select2 search-param" id="bill_reg_id" name="bill_reg_id">
                        <option value=""></option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="approval_status" class="">Approval Status</label>
                    <select name="approval_status" class="form-control form-control-sm select2 search-param"
                            id="approval_status">
                        {{--<option value="">Select Status</option>--}}
                        @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                            <option {{isset($filterData) ? (($key == $filterData[4]) ? 'selected' : '') : ''}} value="{{$key}}">{{ $value}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- End Add Block Pavel-08-06-22/09-06-22 --}}
            </div>
            <div class="row">
                <div class="col-md-12  table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="invoiceBillSearch">
                        <thead class="thead-dark">
                        <tr>
                            {{--<th>SL</th>
                            <th>Batch ID</th>--}}
                            {{--<th>Vendor</th>--}}
                            <th width="5%">Document Date</th>
                            <th width="5%">Document No</th>
                            <th class="text-right-align" width="14%">Invoice Amount</th>
                            <th class="text-right-align" width="10%">Tax Amount</th>
                            <th class="text-right-align" width="10%">Vat Amount</th>
                            <th class="text-right-align" width="10%">security Deposit</th>
                            <th class="text-right-align" width="10%">Other Charges</th>
                            <th class="text-right-align" width="14%">Payable Amount</th>
                            <th class="text-center" width="5%">Approval Status</th>
                            <th class="text-center" width="15%">Action</th>
                            {{--<th width="5%">View</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $("#ap_party_sub_ledger").on('change', function () {
            let subsidiaryId = $(this).val();
            if (!nullEmptyUndefinedChecked(subsidiaryId)) {
                var request = $.ajax({
                    url: APP_URL + '/account-payable/ajax/get-invoice-types-on-subsidiary',
                    data: {subsidiaryId: subsidiaryId}
                });

                request.done(function (d) {
                    if (!$.isEmptyObject(d)) {
                        $("#ap_invoice_type").html(d);
                    } else {
                        $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            } else {
                $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
            }
        })
        let postingCalendarClickCounter = 0;
        $("#period").on('change', function () {
            /* $("#posting_date >input").val("");
             if (postingCalendarClickCounter > 0) {
                 $("#posting_date").datetimepicker('destroy');
                 postingCalendarClickCounter = 0;
             }*/
            reloadDataTable();
        });
        $("#posting_date").on('click', function () {
            postingCalendarClickCounter++;
            $("#posting_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        $('#bill_section').select2().change(function (e) {
            $("#bill_reg_id").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
        });
        selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + $('#bill_section').select2().find(':selected').val(), '', '');

        function reloadDataTable() {
            vendorTable.draw();
            /* $('#invoiceBillSearch').data("dt_params", {
                 period: $('#period :selected').val(),
                 /!*ap_party_sub_ledger: $('#ap_party_sub_ledger :selected').val(),
                 ap_invoice_type: $('#ap_invoice_type :selected').val(),
                 ap_vendor: $('#ap_vendor :selected').val(),
                 posting_date: $('#posting_date_field').val(),
                 posting_batch_id: $('#posting_batch_id').val(),
                 ap_document_no: $('#ap_document_no').val(),
                 department: $('#department').val(),
                 bill_section: $('#bill_section :selected').val(),
                 bill_reg_id: $('#bill_reg_id :selected').val(),*!/
                 approval_status: $('#approval_status :selected').val()
             }).DataTable().draw();*/
        }

        $("#invoice-bill-search-form").on('submit', function (e) {
            e.preventDefault();
            reloadDataTable();
        });

        /** Start Add Block Pavel-08-06-22/09-06-22 **/
        $(".search-param").on('change', function () {
            reloadDataTable();
        });
        /** End Add Block Pavel-08-06-22/09-06-22 **/

        let vendorTable = $('#invoiceBillSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-payable/invoice-bill-listing-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    var dt_params = {
                        fiscalYear: $('#th_fiscal_year :selected').val(),
                        period: $('#period :selected').val(),
                        approval_status: $('#approval_status :selected').val(), /** Add Block Pavel-08-06-22 **/
                        bill_section: $('#bill_section :selected').val(), /** Add Block Pavel-09-06-22 **/
                        bill_reg_id: $('#bill_reg_id :selected').val(),
                    };
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /* {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                /*{"data": "batch_id"}, */
                {"data": "document_date"},
                {"data": "document_no"},
                {"data": "invoice_amount"},
                {"data": "tax_amount"},
                {"data": "vat_amount"},
                {"data": "security_deposit"},
                {"data": "other_amount"},
                {"data": "payable_amount"},
                {"data": "invoice_status"},
                /*{"data": "hold_unhold"},*/
                {"data": "action", "orderable": false}
            ], createdRow: function (row, data, index) {
                /*$('td', row).eq(0).css("padding",'0px');*/
                $('td', row).eq(2).addClass("text-right");
                $('td', row).eq(3).addClass("text-right");
                $('td', row).eq(4).addClass("text-right");
                $('td', row).eq(5).addClass("text-right");
                $('td', row).eq(6).addClass("text-right");
                $('td', row).eq(7).addClass("text-right");
            }
        });

        $(document).on('click', '.hold_un_hold_invoice', function () {
            let invoiceId = $(this).data('invoiceid');
            let currentFlag = $(this).data('currentflag');
            let title = (currentFlag == '1') ? "Confirm Un-hold!" : "Confirm Hold!";
            let btn = (currentFlag == '1') ? "Un-hold" : "Hold";
            Swal.fire({
                title: title,
                text: 'Write a valid reason.',
                input: 'textarea',
                type: 'question',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: btn,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Write a valid reason.')
                        }
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (!nullEmptyUndefinedChecked(result.value)) {
                    let request = $.ajax({
                        url: APP_URL + "/account-payable/invoice-bill-listing",
                        data: {holdUnHoldReason: result.value, oldFlag: currentFlag, invoiceId: invoiceId},
                        dataType: "JSON",
                        method: "PUT",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });
                    request.done(function (res) {
                        if (res.response_code == "1") {
                            Swal.fire({text: res.response_msg, type: 'success'});
                            reloadDataTable();
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
        })

        $("#th_fiscal_year").on('change',function () {
            getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
        });
        getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod,{{isset($filterData) ? $filterData[1] : ''}});
        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }
    </script>
@endsection


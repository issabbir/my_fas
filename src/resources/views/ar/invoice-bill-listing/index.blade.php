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
    @include('ar.invoice-bill-listing.form')
    <div class="card">
        <div class="card-body">
            <h5 style="text-decoration: underline">Transaction List</h5>
            <div class="row">
                <div class="col-md-12  table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="invoiceBillSearch">
                        <thead class="thead-dark">
                        <tr>
                            <th>Batch Id</th>
                            <th>Document No</th>
                            <th>Document Date</th>
                            <th>Document Reference</th>
                            <th  width="15%" class="text-center">Invoice Amount</th>
                            <th  width="15%" class="text-center">Vat Amount</th>
                            <th  width="15%" class="text-center">Receivable Amount</th>
                            <th  width="10%" class="text-center">Approval Status</th>
                            <th  width="5%">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        // $("#ap_party_sub_ledger").on('change', function () {
        //     let subsidiaryId = $(this).val();
        //     if (!nullEmptyUndefinedChecked(subsidiaryId)) {
        //         var request = $.ajax({
        //             url: APP_URL + '/account-receivable/ajax/get-invoice-types-on-subsidiary',
        //             data: {subsidiaryId: subsidiaryId}
        //         });
        //
        //         request.done(function (d) {
        //             if (!$.isEmptyObject(d)) {
        //                 $("#ap_invoice_type").html(d);
        //             } else {
        //                 $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
        //             }
        //         });
        //         request.fail(function (jqXHR, textStatus) {
        //             console.log(jqXHR);
        //         });
        //     } else {
        //         $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
        //     }
        // })

        function reloadDataTable() {
            billListingTable.draw();
        }

        $("#invoice-bill-search-form").on('submit', function (e) {
            e.preventDefault();
            reloadDataTable();
        });

        let billListingTable = $('#invoiceBillSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-receivable/ar-invoice-bill-listing-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = {
                        fiscalYear : $('#th_fiscal_year :selected').val(),
                        period: $('#period :selected').val(),
                        approval_status: $('#approval_status :selected').val(),
                        bill_section: $('#bill_section :selected').val(),
                        bill_reg_id: $('#bill_reg_id :selected').val()
                    };
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                /*{"data": "customer_name"},*/
                {"data": "batch_id"},
                {"data": "document_no"},
                {"data": "document_date"},
                {"data": "document_reference"},
                {"data": "invoice_amount"},
                {"data": "vat_amount"},
                {"data": "receivable_amount"},
                {"data": "approval_status"},
                {"data": "action", "orderable": false}
            ], createdRow: function (row, data, index) {
                $('td', row).eq(4).addClass("text-right");
                $('td', row).eq(5).addClass("text-right");
                $('td', row).eq(6).addClass("text-right");
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
                        url: APP_URL + "/account-receivable/ar-invoice-bill-listing",
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
            reloadDataTable();
        });

        $('#bill_reg_id').select2().on('change', function () {
            reloadDataTable();
        })

        $("#approval_status").on('change',function () {
            reloadDataTable();
        })

        function reloadDataTable() {
            billListingTable.draw();
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


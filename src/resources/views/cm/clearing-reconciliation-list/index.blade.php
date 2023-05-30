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
    @include('cm.clearing-reconciliation-list.form')

@endsection

@section('footer-script')
    <script type="text/javascript">

        let postingCalendarClickCounter = 0;

        $(document).ready(function () {

            let reconcileTable = $('#outwardListSearch').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/clearing-reconciliation-list-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        // Retrieve dynamic parameters
                        var dt_params = {
                            ap_bank_account: $('#ap_bank_account :selected').val(),
                            period: $('#period :selected').val(),
                            function_type: $("#function_type :selected").val(),
                            /*bill_section: $('#bill_section :selected').val(),
                            bill_reg_id: $('#bill_reg_id :selected').val(),*/
                            approval_status: $('#approval_status :selected').val()
                        };
                        // Add dynamic parameters to the data object sent to the server
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }
                    }
                },
                "columns": [
                    {"data": "bank_account_name"},
                    {"data": "trans_date"},
                    {"data": "amount"},
                    {"data": "dr_cr"},
                    {"data": "instrument_type_name"},
                    {"data": "instrument_no"},
                    {"data": "instrument_date"},
                    {"data": "clearing_date"},
                    {"data": "approval_status"},
                    {"data": "action", "orderable":false}
                ], createdRow: function (row, data, index) {
                    $('td', row).eq(2).addClass("text-right");
                    $('td', row).eq(3).addClass("text-center");
                    $('td', row).eq(4).addClass("text-center");
                    $('td', row).eq(5).addClass("text-center");
                    $('td', row).eq(6).addClass("text-center");
                    $('td', row).eq(7).addClass("text-center");
                    $('td', row).eq(8).addClass("text-center");
                }
            });

            $('#bill_section').select2().change(function (e) {
                $("#bill_reg_id").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
            });

            function reloadDataTable() {
               /* $('#outwardListSearch').data("dt_params", {
                    ap_bank_account: $('#ap_bank_account :selected').val(),
                    period: $('#period :selected').val(),
                    bill_section: $('#bill_section :selected').val(),
                    bill_reg_id: $('#bill_reg_id :selected').val(),
                    approval_status: $('#approval_status :selected').val()
                }).DataTable().draw();*/
                reconcileTable.draw();
            }

            $("#reconciliation-search-form").on('submit', function (e) {
                e.preventDefault();
                reloadDataTable();
                resetPostingFields();
                focusOnMe("#outward_clearing_queue_area");
            });


            $("#clearing_date").on('click', function () {
                let minDate = $("#cheque_date_field_v").val();
                let maxDate = false;
                //$.datepicker._clearDate('#clearing_date');
                ar_datePickerOnPeriodUp(this, minDate, maxDate);
            });


            function resetPostingFields() {
                resetField(['#ap_instrument_type','#ap_dr_cr','#ap_bank_account_v', '#trans_date_field_v', '#clearing_date_field_v', '#ap_amount_ccy_v', '#ap_amount_lcy_v', '#ap_cheque_no_v', '#cheque_date_field_v', '#ap_vendor_id_v', '#ap_vendor_name_v', '#ap_vendor_category_v']);
            }

            $(document).on('click', '.clear_edit', function () {
                resetPostingFields();
                let clearingId = $(this).data('clearing');
                let functionType = $(this).data('functiontype');

                let request = $.ajax({
                    url: APP_URL + "/cash-management/ajax/clearing_detail/" + clearingId+"/"+functionType,
                });
                request.done(function (res) {

                    if (res.response_code == "1") {
                        //reloadDataTable();
                        resetPostingFields();
                        $("#ap_bank_account_v").val(res.response_data.bank_account_name);
                        $("#trans_date_field_v").val(res.response_data.trans_date);
                        $("#clearing_date_field_v").val(res.response_data.clearing_date);
                        $("#ap_amount_ccy_v").val(res.response_data.amount_ccy);
                        $("#ap_amount_lcy_v").val(res.response_data.amount_lcy);
                        $("#ap_cheque_no_v").val(res.response_data.instrument_no);
                        $("#cheque_date_field_v").val(res.response_data.instrument_date);
                        $("#clearing_id").val(res.response_data.clearing_id);
                        $("#clearing_date_field_v").val(res.response_data.clearing_date);
                        $("#ap_vendor_id_v").val(res.response_data.party_id);
                        $("#ap_vendor_name_v").val(res.response_data.party_name);
                        $("#ap_vendor_category_v").val(res.response_data.party_category_name);
                        $("#ap_dr_cr").val(res.response_data.dr_cr);
                        $("#ap_instrument_type").val(res.response_data.instrument_type_name);

                        focusOnMe("#clearing_date_field_v");
                        //setClearingDate($("#cheque_date_field_v").val());
                        $("#clearing_date").datetimepicker('destroy');
                    } else {
                        Swal.fire({text: res.response_msg, type: 'error'});
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                    //Swal.fire({text:textStatus+jqXHR,type:'error'});
                    //console.log(jqXHR, textStatus);
                });
            })
            $("#outward_clearing_form").on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    text: 'Confirm Submit?',
                    type: 'question',
                    showCancelButton: true,
                }).then((result) => {
                    if (!nullEmptyUndefinedChecked(result.value)) {
                        let request = $.ajax({
                            url: APP_URL + "/cash-management/clearing-reconciliation-list",
                            data: {clearing_id: $("#clearing_id").val(), clearing_date: $("#clearing_date_field_v").val()},
                            dataType: "JSON",
                            method: "PUT",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });
                        request.done(function (res) {
                            if (res.response_code == "1") {
                                Swal.fire({
                                    text: res.response_msg,
                                    type: 'success',
                                    timer: 2000
                                }).then(() => {
                                    reloadDataTable();
                                    resetPostingFields()
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
        });
    </script>
@endsection



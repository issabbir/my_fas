<?php
/**
 *Created by PhpStorm
 *Created at ২৬/৯/২১ ১২:০১ PM
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
    @include('cm.clearing-reconciliation.form')
@endsection

@section('footer-script')
    <script type="text/javascript">

        let postingCalendarClickCounter = 0;
        let clearingDateClickCounter = 0;

        $('#bill_section').select2().change(function (e) {
            $("#bill_reg_id").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
        });

        function reloadDataTable() {
            $('#outwardListSearch').data("dt_params", {
                function_type: $('#function_type :selected').val(),
                ap_bank_account: $('#ap_bank_account :selected').val(),
                //period: $('#period :selected').val(),
                /*bill_section: $('#bill_section :selected').val(),
                bill_reg_id: $('#bill_reg_id :selected').val(),
                approval_status: $('#approval_status :selected').val()*/
            }).DataTable().draw();
        }

        $("#reconciliation-search-form").on('submit', function (e) {
            e.preventDefault();
            reloadDataTable();
            resetPostingFields();
            focusOnMe("#outward_clearing_queue_area");
        });


        let reconcileTable = $('#outwardListSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            /*pageLength: 2,*/
            pagingType: "simple",
            ajax: {
                url: APP_URL + '/cash-management/clearing-reconciliation-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#outwardListSearch').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                },
                complete: function (e, d) {
                    holdCurrentNextPreviousBtnState();
                },
                /*dataSrc:function (json) {
                    if (json.data.length > 0){
                        $("#posting_date").removeClass('make-readonly');
                        $("#posting_date_field").removeAttr('readonly');
                        $("#clearing_date").removeClass('make-readonly');
                        $("#clearing_date_field_v").removeAttr('readonly');
                    }else{
                        $("#posting_date").addClass('make-readonly');
                        $("#posting_date_field").attr('readonly');
                        $("#clearing_date").addClass('make-readonly');
                        $("#clearing_date_field_v").attr('readonly');
                    }
                    return json.data;
                }*/
            },
            "columns": [
                {"data": "select", "orderable": false},
                {"data": "bank_account_name"},
                {"data": "trans_date"},
                {"data": "amount"},
                {"data": "dr_cr"},
                {"data": "instrument_type_name"},
                {"data": "instrument_no"},
                {"data": "instrument_date"},
                {"data": "clearing_date"},/*
                {"data": "action", "orderable":false}*/
            ], createdRow: function (row, data, index) {
                $('td', row).eq(1).addClass("text-center");
                $('td', row).eq(2).addClass("text-right");
                $('td', row).eq(3).addClass("text-center");
                $('td', row).eq(4).addClass("text-center");
                $('td', row).eq(5).addClass("text-center");
                $('td', row).eq(6).addClass("text-center");
                $('td', row).eq(7).addClass("text-center");
            }
        });

        function resetPostingFields() {
            resetField(['#ap_instrument_type', '#ap_dr_cr', '#ap_bank_account_v', '#trans_date_field_v', '#clearing_date_field_v', '#ap_amount_ccy_v', '#ap_amount_lcy_v', '#ap_cheque_no_v', '#cheque_date_field_v', '#ap_vendor_id_v', '#ap_vendor_name_v', '#ap_vendor_category_v']);
        }

        $(document).on('click', '.clear_edit', function () {
            resetPostingFields();
            let clearingId = $(this).data('clearing');
            let functionType = $(this).data('functiontype');
            let request = $.ajax({
                url: APP_URL + "/cash-management/ajax/clearing_detail/" + clearingId + "/" + functionType,
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
                    $("#trans_period_id").val(res.response_data.trans_period_id);
                    $("#ap_vendor_id_v").val(res.response_data.party_id);
                    $("#ap_vendor_name_v").val(res.response_data.party_name);
                    $("#ap_vendor_category_v").val(res.response_data.party_category_name);
                    $("#ap_dr_cr").val(res.response_data.dr_cr);
                    $("#ap_instrument_type").val(res.response_data.instrument_type_name);

                    focusOnMe("#clearing_date_field_v");
                    //setClearingDate($("#cheque_date_field_v").val());
                    if (clearingDateClickCounter > 0) {
                        $("#clearing_date").datetimepicker('destroy');
                        clearingDateClickCounter = 0
                    }
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
                        url: APP_URL + "/cash-management/clearing-reconciliation",
                        data: {
                            posting_date: $("#posting_date_field").val(),
                            clearing_id: $("#clearing_id").val(),
                            clearing_date: $("#clearing_date_field_v").val(),
                            trans_period_id: $("#trans_period_id").val(),
                            selected_reconciles: countSelectedRowsWithMaxDate()[2] //getting selected reconciles
                        },
                        dataType: "JSON",
                        method: "POST",
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
                                resetPostingFields();
                                $("#outward_clearing_form_submit_btn").prop('disabled', true);
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


        /*********************Yajraj Datatable pagination enable disable on checkbox************/
        let isPreviousDisabled;
        let isNextDisabled;

        //Calling this function only when data fetched from ajax
        function holdCurrentNextPreviousBtnState() {
            let previousBtnObject = $(document).find(' #outwardListSearch_previous');
            let nextBtnObject = $(document).find(' #outwardListSearch_next');

            isPreviousDisabled = !!previousBtnObject.hasClass('disabled');
            isNextDisabled = !!nextBtnObject.hasClass('disabled');
        }

        $(document).on('click', '.selectToReconcile', function () {
            let previousBtnObject = $('#outwardListSearch_previous');
            let nextBtnObject = $('#outwardListSearch_next');
            $("#clearing_date_field_v").val("");

            if ($(this).prop('checked')) {
                //We must disable this when is checked.
                nextBtnObject.addClass('disabled');
                previousBtnObject.addClass('disabled');
                enableDisableDateFields(true);

            } else {
                let totalChecked = countSelectedRowsWithMaxDate()[0];

                if (totalChecked === 0) {
                    if (!isPreviousDisabled) {
                        previousBtnObject.removeClass('disabled');
                    }

                    if (!isNextDisabled) {
                        nextBtnObject.removeClass('disabled');
                    }
                }

                if (totalChecked <= 0) {
                    enableDisableDateFields(false);
                }
            }
            $("#posting_date_field").val(countSelectedRowsWithMaxDate()[1]);
            $("#clearing_date_field_v").val(countSelectedRowsWithMaxDate()[1]);
            enableDisableSaveBtn();

            if (clearingDateClickCounter > 0) {
                $("#clearing_date").datetimepicker('destroy');
                clearingDateClickCounter = 0
            }

        })

        function enableDisableDateFields(key) {
            if (key) {   //true
                $("#posting_date").removeClass('make-readonly');
                $("#posting_date_field").removeAttr('readonly');
                $("#clearing_date").removeClass('make-readonly');
                $("#clearing_date_field_v").removeAttr('readonly');
            } else { //false
                $("#posting_date").addClass('make-readonly');
                $("#posting_date_field").attr('readonly', 'readonly');
                $("#clearing_date").addClass('make-readonly');
                $("#clearing_date_field_v").attr('readonly', 'readonly');
            }
        }

        $("#all_check").on('click', function () {
            if ($(this).prop('checked')) {
                $('#outwardListSearch').children('tbody').children('tr').each(function () {
                    $(this).find('.selectToReconcile').prop('checked', true)/*.trigger('click')*/
                });

                enableDisableDateFields(true);
            } else {
                $('#outwardListSearch').children('tbody').children('tr').each(function () {
                    $(this).find('.selectToReconcile').prop('checked', false)/*.trigger('click')*/;
                });

                enableDisableDateFields(false);
            }

            $("#posting_date_field").val(countSelectedRowsWithMaxDate()[1]);
            $("#clearing_date_field_v").val(countSelectedRowsWithMaxDate()[1]);

            enableDisableSaveBtn();
            if (clearingDateClickCounter > 0) {
                $("#clearing_date").datetimepicker('destroy');
                clearingDateClickCounter = 0
            }
        })

        function countSelectedRowsWithMaxDate() {
            let countedNumberOfChecked = 0;
            let selectedDates = [];
            let selectedReconcile = []
            let maxDate;

            $('#outwardListSearch').children('tbody').children('tr').each(function () {
                if ($(this).find('.selectToReconcile').prop('checked')) {
                    countedNumberOfChecked++;
                    //Get max date
                    selectedDates.push(moment($(this).find("td:eq(7)").html(), "DD-MM-YYYY"));

                    selectedReconcile.push($(this).find('input[type=hidden]').val());
                }
            });
            if (selectedDates.length > 0) {
                maxDate = moment(Math.max.apply(null, selectedDates)).format("DD-MM-YYYY");
            }
            return [countedNumberOfChecked, maxDate, selectedReconcile];
        }

        /*********************Yajraj Datatable pagination enable disable on checkbox end************/

        function enableDisableSaveBtn() {
            if (nullEmptyUndefinedChecked(countSelectedRowsWithMaxDate()[0]) || nullEmptyUndefinedChecked($("#clearing_date_field_v").val())) {
                $("#outward_clearing_form_submit_btn").prop('disabled', true);
            } else {
                $("#outward_clearing_form_submit_btn").prop('disabled', false);
            }
        }

        function reconciliationEntryFields() {
            let postingCalendarClickCounter = 0;

            $("#period").on('change', function () {
                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                }
            });

            $("#posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod_top(this, minDate, maxDate, currentDate);
            });
            let postingDateClickCounter = 0;

            $("#posting_date").on("change.datetimepicker", function () {
                let newDueDate;

                if (!nullEmptyUndefinedChecked($("#posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#posting_date_field").val()).format("DD-MM-YYYY");
                    } else {
                        newDueDate = moment($("#posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                    }
                }
                if (Date.parse($("#posting_date_field").val()) < Date.parse($("#clearing_date_field_v").val())) {
                    $("#clearing_date_field_v").val("");
                }

                postingDateClickCounter++;
            });

            $("#clearing_date").on('click', function () {
                //let minDate = countSelectedRowsWithMaxDate()[1];
                let minDate = countSelectedRowsWithMaxDate()[1];
                //let maxDate = false;
                let maxDate = $("#posting_date_field").val();
                //datePickerTop(this);
                //$("#clearing_date").datetimepicker('destroy');

                if (!nullEmptyUndefinedChecked(minDate)) {
                    if (clearingDateClickCounter > 0) {
                        $("#clearing_date").datetimepicker('destroy');
                        clearingDateClickCounter = 0
                    }
                    $("#clearing_date_field_v").val("");

                    if (minDate > maxDate) {
                        $("#posting_date_field").notify('Transaction date must be greater or equal to instrument date:'+minDate+'.', 'error');
                    }else{
                        ar_datePickerOnPeriodUp("#clearing_date", minDate, maxDate);
                        clearingDateClickCounter++;
                    }


                    /*if (Date.parse($("#clearing_date_field_v").val()) > Date.parse(minDate)){
                        $("#clearing_date_field_v").val("");
                    }*/


                    $(this).on("change.datetimepicker", function () {
                        enableDisableSaveBtn();
                    })
                }

            });
        }

        reconciliationEntryFields();
    </script>
@endsection



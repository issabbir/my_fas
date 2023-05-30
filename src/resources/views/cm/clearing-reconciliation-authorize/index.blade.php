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
    @include('cm.clearing-reconciliation-authorize.form')

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
                /*pageLength: 2,*/
                pagingType: "simple",
                ajax: {
                    url: APP_URL + '/cash-management/clearing-reconciliation-authorize-search',
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
                            approval_status: $('#approval_status :selected').val()
                        };
                        // Add dynamic parameters to the data object sent to the server
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }
                    },
                    complete: function () {
                        holdCurrentNextPreviousBtnState();
                    }
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
                    {"data": "clearing_date"},
                    {"data": "approval_status"},
                    /*{"data": "action", "orderable":false}*/
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
                resetField(['#authorizer', '#ap_instrument_type', '#ap_dr_cr', '#ap_bank_account_v', '#trans_date_field_v', '#clearing_date_field_v', '#ap_amount_ccy_v', '#ap_amount_lcy_v', '#ap_cheque_no_v', '#cheque_date_field_v', '#ap_vendor_id_v', '#ap_vendor_name_v', '#ap_vendor_category_v']);
                $("#authorize_section").html("");
            }

            $(document).on('click', '.clear_edit', function () {
                resetPostingFields();
                $("#authorize_section").html('');

                let clearingId = $(this).data('clearing');
                let mapId = $(this).data('mapid');
                let userId = $(this).data('userid');
                let wk_ref_status = $(this).data('wkrefstatus');
                let functionType = $(this).data('functiontype');

                let request = $.ajax({
                    url: APP_URL + "/cash-management/ajax/clearing_detail/" + clearingId + "/" + functionType,
                    data: {
                        wkf_map_id: mapId,
                        wkf_user_id: userId,
                        wk_ref_status: wk_ref_status,
                    }
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
                        $("#map_id_v").val(mapId);
                        $("#ap_dr_cr").val(res.response_data.dr_cr);
                        $("#ap_instrument_type").val(res.response_data.instrument_type_name);
                        $("#authorize_section").html(res.response_data.authorize_view);
                        $("#authorizer_back_btn").remove();
                        $("#approve_reject_btn").remove();

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
            /*$(document).on('click', '.approve-reject-btn', function () {
                /!*$(".approve-reject-btn").on('click',function () {*!/
                //alert('1');
                $("#approve_reject_value").val($(this).val());
                $("#outward_clearing_form").submit();
            })*/

            /*$("#outward_clearing_form").on('submit', function (e) {
                e.preventDefault();*/

            $('.approve-reject-btn').click(function (e) {
                e.preventDefault();

                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;

                $('#approve_reject_value').val(approval_status);

                if (approval_status == 'A') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Reconciliation ' + approval_status_val,
                    type: 'warning',
                    input: swal_input_type,
                    inputPlaceholder: 'Reason For Decline?',
                    inputValidator: (result) => {
                        return !result && 'You need to provide a comment'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        //form.submit();
                        let request = $.ajax({
                            url: APP_URL + "/cash-management/clearing-reconciliation-authorize",
                            data: {
                                /*map_id: $("#map_id_v").val(),*/
                                approve_reject_value: $("#approve_reject_value").val(),
                                /*authorizer: $("#authorizer").val(),*/
                                comment: (isConfirm.value !== true) ? isConfirm.value : '',
                                selected_mappings: countSelectedRowsWithMaxDate()[2]
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
                                    //enableDisableSaveBtn();
                                    $(".approve-reject-btn").prop('disabled', true);
                                });
                            } else {
                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            //console.log(jqXHR);
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });


            /*Swal.fire({
                text: 'Confirm Submit?',
                type: 'question',
                showCancelButton: true,
            }).then((result) => {
                if (!nullEmptyUndefinedChecked(result.value)) {
                    let request = $.ajax({
                        url: APP_URL + "/cash-management/clearing-reconciliation-authorize",
                        data: {
                            /!*map_id: $("#map_id_v").val(),*!/
                            approve_reject_value: $("#approve_reject_value").val(),
                            /!*authorizer: $("#authorizer").val(),*!/
                            comment: $("#comment").val(),
                            selectedReconcile: countSelectedRowsWithMaxDate()[2]
                        },
                        dataType: "JSON",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": ''
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
            })*/
            /* })*/

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
                }

                enableDisableSaveBtn();
            })

            $("#all_check").on('click', function () {
                if ($(this).prop('checked')) {
                    $('#outwardListSearch').children('tbody').children('tr').each(function () {
                        $(this).find('.selectToReconcile')/*.prop('checked', true)*/.trigger('click');
                    });
                } else {
                    $('#outwardListSearch').children('tbody').children('tr').each(function () {
                        $(this).find('.selectToReconcile')/*.prop('checked', false)*/.trigger('click');
                    });
                }
                enableDisableSaveBtn();
            })

            function countSelectedRowsWithMaxDate() {
                let countedNumberOfChecked = 0;
                let selectedDates = [];
                let selectedReconcile = [];
                let maxDate;

                $('#outwardListSearch').children('tbody').children('tr').each(function () {
                    if ($(this).find('.selectToReconcile').prop('checked')) {
                        countedNumberOfChecked++;
                        //Get max date
                        selectedDates.push(moment($(this).find("td:eq(7)").html(), "DD-MM-YYYY"));

                        selectedReconcile.push(
                            $(this).find('.selectToReconcile').data('mapid')
                            //{
                            //mapid: $(this).find('.selectToReconcile').data('mapid'),
                            //userid: $(this).find('.selectToReconcile').data('userid'),
                            //functiontype: $(this).find('.selectToReconcile').data('functiontype'),
                            //wkrefstatus: $(this).find('.selectToReconcile').data('wkrefstatus'),
                            //clearing: $(this).find('.selectToReconcile').data('clearing')
                            //}
                        );
                    }
                });

                if (selectedDates.length > 0) {
                    maxDate = moment(Math.max.apply(null, selectedDates)).format("DD-MM-YYYY");
                }
                return [countedNumberOfChecked, maxDate, selectedReconcile];
            }

            /*********************Yajraj Datatable pagination enable disable on checkbox end************/

            function enableDisableSaveBtn() {
                if (nullEmptyUndefinedChecked(countSelectedRowsWithMaxDate()[0])) {
                    $(".approve-reject-btn").prop('disabled', true);
                } else {
                    $(".approve-reject-btn").prop('disabled', false);
                }
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

        });
    </script>
@endsection



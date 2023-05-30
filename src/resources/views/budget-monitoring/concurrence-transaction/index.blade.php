<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:১০ AM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Concurrence Information:</span>
            </h4>

            @include('budget-monitoring.concurrence-transaction.form')
        </div>
    </div>
    @include("budget-monitoring.common_budged_search")
    @include('budget-monitoring.common_vendor_list_modal')

@endsection

@section('footer-script')
    <script type="text/javascript">
        /*function getDepartmentIntPeriod() {
            $("#department").html("");
            $("#transaction_period").html("");


            let calendarId = $("#fiscal_year :selected").val();
            if (!nullEmptyUndefinedChecked(calendarId)) {
                let request = $.ajax({
                    url: APP_URL + "/budget-monitoring/ajax/dept-period-on-calender",
                    data: {
                        calendarId: calendarId,
                        pre_selected_dpt: $("#department").data('predpt'),
                        pre_selected_period: $("#transaction_period").data('preperiod')
                    }
                });

                request.done(function (e) {
                    $("#department").html(e.department);
                    $("#transaction_period").html(e.period);
                    setPeriodCurrentDate();
                    if (!nullEmptyUndefinedChecked($("#budget_head_id").val())) {
                        $("#search_budget").trigger('click');
                    }

                })

                request.fail(function (jqXHR, textStatus) {
                    swal.fire({
                        text: jqXHR.responseJSON['message'],
                        type: 'warning',
                    })
                })
            }
            $("#transaction_date").trigger("click");
        }*/

        /*$("#fiscal_year").on('change', function () {
            getDepartmentIntPeriod();
        });*/
        //getDepartmentIntPeriod();

        /********Added on: 06/06/2022, sujon**********/
        function setPeriodCurrentDate() {
            let minDate = $("#transaction_period :selected").data("mindate");
            let maxDate = $("#transaction_period :selected").data("maxdate");
            let currentDate = $("#transaction_period :selected").data("currentdate");

            datePickerOnPeriod("#transaction_date", minDate, maxDate, currentDate);
            datePickerOnPeriod("#document_date", false, maxDate, currentDate);
        }

        /********End**********/
        let transactionDateClickCounter = 0;
        let documentCalendarClickCounter = 0;

        $("#transaction_period").on('change', function () {
            $("#transaction_date >input").val("");
            if (transactionDateClickCounter > 0) {
                $("#transaction_date").datetimepicker('destroy');
                transactionDateClickCounter = 0;
            }

            $("#transaction_date >input").val("");
            if (documentCalendarClickCounter > 0) {
                $("#transaction_date").datetimepicker('destroy');
                documentCalendarClickCounter = 0;
            }
            setPeriodCurrentDate();
        });
        $("#transaction_date").on('click', function () {
            if (checkPeriodSet()) {
                transactionDateClickCounter++;
                $("#transaction_date >input").val("");
                let minDate = $("#transaction_period :selected").data("mindate");
                let maxDate = $("#transaction_period :selected").data("maxdate");
                let currentDate = $("#transaction_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            }
        });


        $("#transaction_date").on("change.datetimepicker", function () {
            let newDueDate;
            let postingDate = $("#transaction_date_field").val();

            if (!nullEmptyUndefinedChecked(postingDate)) {
                if (transactionDateClickCounter == 0) {
                    newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                } else {
                    newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                }

                $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
            }
            transactionDateClickCounter++;
        });

        $("#document_date").on('click', function () {
            documentCalendarClickCounter++;
            $("#document_date >input").val("");
            let minDate = false;
            let maxDate = $("#transaction_period :selected").data("maxdate");
            let currentDate = $("#transaction_period :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        function checkPeriodSet() {
            if (nullEmptyUndefinedChecked($("#transaction_period").val())) {
                $("#transaction_period").notify("Select Transaction Period.", {
                    position: "right",
                    className: 'info'
                });
                return false;
            }
            return true;
        }

        function checkFinancialYearSet() {
            if (nullEmptyUndefinedChecked($("#fiscal_year").val())) {
                $("#fiscal_year").notify("Select Financial Year.", {position: "right", className: 'info'});
                return false;
            }
            return true;
        }

        $("#department").on('change', function () {
            resetField(['#budget_head_id', '#budget_head_name',
                '#budget_sub_category', '#budget_category', '#budget_type',
                '#ministry_approved', '#utilized_amount', '#balance_amount',
                '#available_amount','#remaining_block_amount','#unblocked_amount',
                '#blocked_amount', '#booking_amount', '#booking_amount_word',
                '#remarks','#est_amount_word','#est_date_field','#est_amount',
                '#memo_date_field','#memo_no','#page_no','#fill_no','#vendor_id',
                '#vendor_name','#tender_proposal_date_field','#tender_proposal_no',
                '#tender_proposal_ref','#contract_date_field','#contract_no','#subject',
                '#contract_value'
            ]);
        });
        datePicker("#memo_date");
        datePicker("#est_date");
        datePicker("#contract_date");
        datePicker("#tender_proposal_date");


        /*
        * Budget search starts from here
        * */
        $(" #search_budget").on("click", function () {
            let budgetId = $('#budget_head_id').val();
            let department = $('#department :selected').val();
            let calendar = $('#fiscal_year :selected').val();

            resetBudgetField();
            reloadBudgetListTable();

            if (!nullEmptyUndefinedChecked($("#department :selected").val())) {
                if (!nullEmptyUndefinedChecked(budgetId)) {
                    getBudgetDetailInfo(budgetId, department, calendar);
                } else {
                    reloadBudgetListTable();

                    $("#s_fiscal_year").val($("#fiscal_year :selected").text().trim()); // Add trim Pavel:28-03-22
                    $("#s_department").val($("#department :selected").text());
                    $("#budgetListModal").modal('show');
                }
            } else {
                $("#department").notify("Select Department First.");
                resetField(['#budget_head_id']);
            }

        });
        $(document).on('submit', '#booking_search_form', function (e) {
            e.preventDefault();
            reloadBudgetListTable();
        })

        $(document).on('click', '.budgetSelect', function () {
            getBudgetDetailInfo($(this).data('budget'), $(this).data('department'), $(this).data('calendar'));
        });

        function getBudgetDetailInfo(budgetId, department, calendar) {
            var request = $.ajax({
                url: APP_URL + '/budget-monitoring/ajax/a-budget-detail',
                data: {budget_id: budgetId, department: department, calendar: calendar}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.data)) {
                    $("#budget_head_id").notify("Budget Head Not Found", "error");
                    resetField(['#budget_head_name',
                        '#budget_sub_category', '#budget_category', '#budget_type',
                        '#ministry_approved', '#utilized_amount', '#balance_amount']);
                } else {
                    $('#budget_head_id').val(d.data.budget_head_id);
                    $('#budget_head_name').val(d.data.budget_head_name);
                    $('#budget_sub_category').val(d.data.sub_category_name);
                    $('#budget_category').val(d.data.category_name);
                    $('#budget_type').val(d.data.budget_type_name);

                    $('#ministry_approved').val(getCommaSeparatedValue(d.data.ministry_approved_amt));
                    $('#utilized_amount').val(getCommaSeparatedValue(d.data.budget_utilized_amt));
                    $('#balance_amount').val(getCommaSeparatedValue(d.data.budget_balance_amt));

                    $('#blocked_amount').val(getCommaSeparatedValue(d.data.block_amount));
                    $('#unblocked_amount').val(getCommaSeparatedValue(d.data.unblock_amount));
                    $('#remaining_block_amount').val(getCommaSeparatedValue(d.data.remaining_block_amount));
                    $('#available_amount').val(getCommaSeparatedValue(d.data.available_amt));
                }
                $("#budgetListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        function reloadBudgetListTable() {
            budgetTable.draw();
        }

        let budgetTable = $('#budget_head_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/budget-monitoring/ajax/budget-head-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.department = $('#department :selected').val();
                    params.calendar = $('#fiscal_year :selected').val();
                }
            },
            "columns": [
                {"data": 'budget_head_id', "name": 'budget_head_id'},
                {"data": "budget_head_name"},
                {"data": "sub_category"},
                {"data": "category_name"},
                {"data": "budget_type"},
                {"data": "balance"},
                {"data": "action", "orderable": false}
            ],
        });

        function resetBudgetField() {
            resetField(['#budget_head_name',
                '#budget_sub_category', '#budget_category', '#budget_type',
                '#ministry_approved', '#utilized_amount', '#balance_amount']);
        }

        /*
        * Budget search ends here
        * */

        $('#bill_section').change(function (e) {
            $("#bill_register").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#bill_register', APP_URL + '/budget-monitoring/ajax/bill-section-by-register/' + billSectionId, APP_URL + '/budget-monitoring/ajax/get-bill-register-detail/', '');
        });

        /*
       * Vendor search starts from here
       * */
        $(" #vendor_search").on("click", function () {
            let vendorId = $('#vendor_id').val();

            if (!nullEmptyUndefinedChecked(vendorId)) {
                getVendorDetail(vendorId);
            } else {
                //let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
                reloadVendorListTable();
                $("#vendorListModal").modal('show');
            }
        });

        function reloadVendorListTable() {
            $('#vendorSearch').data("dt_params", {
                vendorType: $('#search_vendor_type :selected').val(),
                vendorCategory: $('#search_vendor_category :selected').val(),
                vendorName: $('#search_vendor_name').val(),
                vendorShortName: $('#search_vendor_short_name').val(),
            }).DataTable().draw();
        }

        $("#vendor_search_form").on('submit', function (e) {
            e.preventDefault();
            reloadVendorListTable();
            //accountTable.draw();
        });

        /*$("#ap_reset_vendor_balance_field").on("click", function () {
            resetField(['#ap_search_vendor_id', '#ap_search_vendor_name', '#ap_search_vendor_category', '#ap_bills_payable', '#ap_prepayments', '#ap_security_deposits', '#ap_advance', '#ap_imprest_cash', '#ap_revolving_cash']);
        });*/
        $(document).on('click', '.vendorSelect', function () {
            getVendorDetail($(this).data('vendor'));
        });

        function getVendorDetail(vendor_id) {

            var request = $.ajax({
                url: APP_URL + '/budget-monitoring/ajax/vendor-details',
                data: {vendorId: vendor_id}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $("#vendor_id").notify("Party/Vendor id not found", "error");
                    resetField(['#vendor_id', '#vendor_name']);
                } else {
                    $('#vendor_id').val(d.vendor_id);
                    $('#vendor_name').val(d.vendor_name);
                }
                $("#vendorListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        let vendorTable = $('#vendorSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/budget-monitoring/ajax/vendor-search-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#vendorSearch').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                {"data": 'vendor_id', "name": 'vendor_id'},
                {"data": "name"},
                {"data": "short_name"},
                {"data": "address"},
                {"data": "action", "orderable": false}
            ],
        });

        $(document).on('shown.bs.modal', '#vendorListModal', function () {
            vendorTable.columns.adjust().draw();
        });
        /*
        * Vendor search ends here
        * */


        /*$(document).on("click", "#budgetFormSubmit", function () {
            $("#concurrence_form").submit();
        })*/

        $("#tender_proposal_type").on('change', function () {
            if ($("#tender_proposal_type").find(':selected').val() == '{{\App\Enums\BudgetMonitoring\TenderType::ADVANCE}}') {
                $("#est_amount").attr('required', 'required');
                $("#est_amount").attr('tabindex', '10');
                $("#est_amount").addClass('addBooking');
                $("#est_amount").parent().prev().addClass('required');
                $("#est_amount").removeAttr('readonly');


                $("#booking_amount").attr('readonly', 'readonly');
                $("#booking_amount").attr('tabindex', '-1');

                if (!nullEmptyUndefinedChecked($("#est_amount").val())) {
                    $("#booking_amount").val($("#est_amount").val());
                }
            } else if ($("#tender_proposal_type").find(':selected').val() == '{{\App\Enums\BudgetMonitoring\TenderType::ADJUSTMENT}}') {
                $("#est_amount").removeAttr('required');
                $("#est_amount").parent().prev().removeClass('required');
                $("#est_amount").attr('tabindex', '-1');
                $("#est_amount").attr('readonly', 'readonly');
                $("#est_amount").removeClass('addBooking');

                $("#booking_amount").attr('tabindex', '13');
                $("#booking_amount").removeAttr('readonly');
            } else {
                $("#est_amount").removeAttr('required');
                $("#est_amount").parent().prev().removeClass('required');
                $("#est_amount").attr('tabindex', '-1');
                $("#est_amount").removeClass('addBooking');
                $("#est_amount").removeAttr('readonly');

                $("#booking_amount").attr('tabindex', '13');
                $("#booking_amount").removeAttr('readonly');
            }
        })
        setBookingAmount();

        function setBookingAmount() {
            $(document).on('keyup', '.addBooking', function () {
                $("#booking_amount").val($(this).val());
            })
        }


        $("#concurrence_form").on("submit", function (e) {
            e.preventDefault();

            swal.fire({
                text: 'Save Budget Booking Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value == true) {
                    let data = new FormData(this)
                    data.set('est_amount',removeCommaFromValue(data.get('est_amount')))
                    data.set('booking_amount',removeCommaFromValue(data.get('booking_amount')))

                    let request = $.ajax({
                        url: APP_URL + "/budget-monitoring/concurrence-transaction" + "{{isset($data['insertedData']) ? '/'.$data['insertedData']->budget_booking_id : ''}}",
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });

                    request.done(function (res) {
                        if (res.response_code != "99") {
                            Swal.fire({
                                type: 'success',
                                text: res.response_msg,
                                showConfirmButton: true,
                                //timer: 2000,
                                allowOutsideClick: false
                            }).then(function () {
                                //location.reload();
                                $(".resetFrom").trigger('click');
                                //window.location.href = "{{route('concurrence-transaction.index')}}";
                                $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/BUDGET_CONCURRENCE?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/Budget_Monitoring/RPT_BUDGET_CONCURRENCE.xdo&p_budget_booking_id=' + res.booking_id + '&type=pdf&filename=budget_concurrence"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Concurrence</a>');
                                focusOnMe("#document_no");
                                //window.history.back();
                            });
                        } else {
                            Swal.fire({text: res.response_msg, type: 'error'});
                        }
                    });

                    request.fail(function (jqXHR, textStatus) {
                        console.log(textStatus);
                        /*swal.fire({
                            text: jqXHR.responseJSON,
                            type: 'warning',
                        })*/
                    });
                }
            })
        });

        $(document).ready(function () {
            $('#bill_section').select2().trigger('change');
            //For adjustment update purpose
            if (!nullEmptyUndefinedChecked($("#budget_booking_id").val()) && $("#tender_proposal_type").select2().val() == '{{\App\Enums\BudgetMonitoring\TenderType::ADVANCE}}') {
                $("#tender_proposal_type").select2().val('{{\App\Enums\BudgetMonitoring\TenderType::ADJUSTMENT}}').trigger('change');
                $("#tender_proposal_type").parent('div').addClass('make-readonly');
                //What if user want's to change type from advance to something else!!!!!!!!
            } else {
                $("#tender_proposal_type").select2().trigger('change');
            }

            @if(isset($data['insertedData']))
                if(!nullEmptyUndefinedChecked($("#budget_head_id").val()))
                    $("#search_budget").trigger('click')
            @endif
            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#transaction_period").html(periods);
                //setPeriodCurrentDate();
                $("#transaction_period").trigger('change');
                //setPeriodCurrentDate();
            }

            $("#est_amount").on('keyup',function () {
                let amount = removeCommaFromValue($(this).val());
                if (amount.length < $(this).attr('maxlength')){
                    $(this).val(getCommaSeparatedValue($(this).val()));
                    $("#est_amount_word").val(amountTranslate(amount))
                }else {
                    return false;
                }
            })

            $("#booking_amount").on('keyup',function () {
                let amount = removeCommaFromValue($(this).val());
                if (amount.length < $(this).attr('maxlength')){
                    $(this).val(getCommaSeparatedValue($(this).val()));
                    $("#booking_amount_word").val(amountTranslate(amount))
                }else {
                    return false;
                }
            })

            $(".resetFrom").on('click',function () {
                resetField(['#department','#tender_proposal_type']);
                removeAllAttachments();
            })
        });
    </script>
@endsection



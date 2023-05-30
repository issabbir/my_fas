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

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('budget-management.block-amount.form')
        </div>
    </div>
    @include("budget-monitoring.common_budged_search")

    <div class="modal fade" id="unblockModal" tabindex="-1" aria-labelledby="unblockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unblockModalLabel">Unblock Budget Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="unblockAmountForm" href="#">

                    <div class="modal-body">


                        {{--<div class="row" style="margin-bottom: 0px;">
                            <label for="ministry_approved" class="col-form-label col-md-5">Approved
                                Amount</label>
                            <div class="col-md-7">
                                <input readonly tabindex="-1" name="ministry_approved"
                                       class="form-control form-control-sm text-right-align ministry_approved"
                                       value=""
                                       type="text"
                                       id="ministry_approved">
                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-md-5 pl-0">
                                <fieldset class="border p-1 h-100">
                                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Blocked Information</legend>
                                    <div class="row d-flex justify-content-end">
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <span>Figure in Tk</span>
                                        </div>
                                    </div>
                                    <div class="row " style="margin-bottom: 0px;">
                                        <label for="m_blocked_date" class="col-form-label col-md-5">Blocked Date</label>
                                        <div class="col-md-7">
                                            <input readonly tabindex="-1" name="blocked_date"
                                                   class="form-control form-control-sm text-right-align "
                                                   value=""
                                                   type="text"
                                                   id="m_blocked_date">
                                        </div>
                                    </div>
                                    <div class="row " style="margin-bottom: 0px;">
                                        <label for="m_blocked_amount" class="col-form-label col-md-5">Blocked Amt
                                            (-)</label>
                                        <div class="col-md-7">
                                            <input readonly tabindex="-1" name="blocked_amount"
                                                   class="form-control form-control-sm text-right-align"
                                                   value=""
                                                   type="text"
                                                   id="m_blocked_amount">
                                        </div>
                                    </div>
                                    <div class="row " style="margin-bottom: 0px;">
                                        <label for="m_unblocked_amount" class="col-form-label col-md-5 pr-0">Unblocked Amt
                                            (+)</label>
                                        <div class="col-md-7">
                                            <input readonly tabindex="-1" name="unblocked_amount"
                                                   class="form-control form-control-sm text-right-align"
                                                   value=""
                                                   type="text"
                                                   id="m_unblocked_amount">
                                        </div>
                                    </div>
                                    <div class="row " style="margin-bottom: 0px;">
                                        <label for="m_remain_block_amount" class="col-form-label col-md-5 pr-0">Remaining Blocked</label>
                                        <div class="col-md-7">
                                            <input readonly tabindex="-1" name="remain_block_amount" data-id="" style="background-color: yellow"
                                                   class="form-control form-control-sm text-right-align"
                                                   value=""
                                                   type="text"
                                                   id="m_remain_block_amount">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-7">
                                <fieldset class="border pl-1 pr-1">
                                    <input type="hidden" name="block" id="block">
                                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Unblock Budget Amount</legend>
                                    <div class="form-group">
                                        <label for="unblocked_amount" class="col-form-label required">Unblock Amount:</label>
                                        <input required name="new_unblocked_amount" type="text" class="form-control text-right-align" maxlength="20"
                                               id="new_unblocked_amount"
                                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);">
                                    </div>
                                    <div class="form-group">
                                        <label for="unblocked_remarks" class="col-form-label required">Unblock Remarks:</label>
                                        <textarea required name="unblocked_remarks" class="form-control"
                                                  id="unblocked_remarks"></textarea>
                                    </div>
                                    <div class="form-group d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary btn-sm" id="unblock_budget"><i class="bx bx-save font-size-small"></i>Unblock Budget</button>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><span aria-hidden="true">&times;</span>Close</button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        {{--<div class="form-group">
                            <label for="pre_blocked_amount" class="col-form-label">Blocked Amount:</label>
                            <input name="pre_blocked_amount" data-id="" type="text" class="form-control"
                                   id="pre_blocked_amount" readonly>
                        </div>--}}


                        <div class="row mt-1">
                            <div class="col-md-12">
                                <h4 class="border-bottom">Budget Unblocked History</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="historyTable table table-sm table-bordered table-hover no-footer" style="border-bottom: 1px solid #DFE3E7;">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th width="20%">Date</th>
                                            <th width="20%" class="text-right-align">Unblock Amount</th>
                                            <th width="60%">Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        let blockedTable;
        let transactionDateClickCounter = 0;
        let documentCalendarClickCounter = 0;
        let allBlockedListTable;

        $(document).ready(function () {
            $("#department").on('change', function () {
                resetField(['#budget_head_id']);
                resetBudgetField();
                resetBudgetBlockedList();
            });
            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
                resetField(['#budget_head_id']);
                resetBudgetField();
                resetBudgetBlockedList();
            });
            getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#transaction_period").html(periods);
                $("#transaction_period").trigger('change');
            }
        });

        function setPeriodCurrentDate() {
            let minDate = nullEmptyUndefinedChecked($("#transaction_period :selected").data("mindate")) ? false: $("#transaction_period :selected").data("mindate");
            let maxDate = nullEmptyUndefinedChecked($("#transaction_period :selected").data("maxdate")) ? false : $("#transaction_period :selected").data("maxdate");
            let currentDate = nullEmptyUndefinedChecked($("#transaction_period :selected").data("currentdate")) ? false : $("#transaction_period :selected").data("currentdate");

            datePickerOnPeriod("#block_date", minDate, maxDate, currentDate);
            datePickerOnPeriod("#document_date", false, maxDate, currentDate);
        }

        function checkFinancialYearSet() {
            if (nullEmptyUndefinedChecked($("#fiscal_year").val())) {
                $("#fiscal_year").notify("Select Financial Year.", {position: "right", className: 'info'});
                return false;
            }
            return true;
        }

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

        $("#transaction_period").on('change', function () {
            $("#block_date >input").val("");
            if (transactionDateClickCounter > 0) {
                $("#block_date").datetimepicker('destroy');
                transactionDateClickCounter = 0;
            }
            setPeriodCurrentDate();
        });

        $("#block_date").on('click', function () {
            if (checkPeriodSet()) {
                transactionDateClickCounter++;
                $("#block_date >input").val("");
                let minDate = $("#transaction_period :selected").data("mindate");
                let maxDate = $("#transaction_period :selected").data("maxdate");
                let currentDate = $("#transaction_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            }
        });
        $("#block_date").on("change.datetimepicker", function () {
            let newDueDate;
            let postingDate = $("#block_date_field").val();

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


        /*
        * Budget search starts from here
        * */
        $(" #search_budget").on("click", function () {
            let budgetId = $('#budget_head_id').val();
            let department = $('#department :selected').val();
            let calendar = $('#fiscal_year :selected').val();

            resetBudgetField();
            reloadBudgetListTable();
            resetBudgetBlockedList();

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

                    $('.ministry_approved').val(getCommaSeparatedValue(d.data.ministry_approved_amt));
                    $('.utilized_amount').val(getCommaSeparatedValue(d.data.budget_utilized_amt));
                    $('.balance_amount').val(getCommaSeparatedValue(d.data.budget_balance_amt));

                    $('.blocked_amount').val(getCommaSeparatedValue(d.data.block_amount));
                    $('.unblocked_amount').val(getCommaSeparatedValue(d.data.unblock_amount));
                    $('.remain_block_amount').val(getCommaSeparatedValue(d.data.remaining_block_amount));
                    $('.available_amount').val(getCommaSeparatedValue(d.data.available_amt));
                }
                $("#budgetListModal").modal('hide');
                callBlockedListTable();
            });

            request.fail(function (jqXHR, textStatus) {
                //console.log(jqXHR);
                Swal.fire({text:textStatus+jqXHR,type:'error'});
            });
        }

        function reloadBudgetListTable() {
            budgetTable.draw();
        }

        function resetBudgetField() {
            resetField(['#budget_head_name',
                '#budget_sub_category', '#budget_category', '#budget_type','.remain_block_amount',
                '.ministry_approved', '.utilized_amount', '.balance_amount', '.blocked_amount',
                '.unblocked_amount', '.available_amount']);
        }

        function resetBudgetBlockedList() {
            resetTablesDynamicRow("#blocked_list")
        }

        function callBlockedListTable() {
            let fiscal_year = $('#fiscal_year :selected').val();
            let department = $('#department').select2().find(':selected').val();
            let budget = $('#budget_head_id').val();
            let budget_name = $('#budget_head_name').val();

            if (nullEmptyUndefinedChecked(fiscal_year)) {
                focusOnMe('#fiscal_year');
                $('#fiscal_year').notify('Fiscal year required.', 'info');
            } else if (nullEmptyUndefinedChecked(department)) {
                focusOnMe('#department');
                $('#department').notify('Department required.', 'info');
            } else if (nullEmptyUndefinedChecked(budget)) {
                focusOnMe('#budget_head_id');
                $('#budget_head_id').notify('Budget Head required.', 'info');
            } else if (nullEmptyUndefinedChecked(budget_name)) {
                focusOnMe('#budget_head_id');
                $('#budget_head_id').notify('Invalid Budget Head.', 'info');
            } else {
                //blockedListTable();
                reloadBlockedListTable();
            }
        }

        function reloadBlockedListTable() {
            blockedTable.draw();
        }

        function setBookingAmount() {
            $(document).on('keyup', '.addBooking', function () {
                $("#booking_amount").val($(this).val());
            })
        }

        setBookingAmount();

        let budgetTable = $('#budget_head_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
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
        $(" #allBlockedBudgets").on("click", function () {
            let calendar = $('#fiscal_year :selected').val();
            resetBudgetField();
            reloadBudgetListTable();
            resetBudgetBlockedList();
            resetAllBlockedList();

            $("#b_fiscal_year").val($("#fiscal_year :selected").text().trim()); // Add trim Pavel:28-03-22
            reloadAllBlockedListTable()
            $("#blockedListModal").modal('show');
        });
        function resetAllBlockedList() {
            resetTablesDynamicRow("#all_blocked_list")
        }
        function reloadAllBlockedListTable() {
            allBlockedListTable.draw();
        }
        allBlockedListTable = $('#all_blocked_list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/budget-management/block-amount-list',
                data: function (params) {
                    params.fiscal_year = $('#fiscal_year :selected').val();
                }
            },
            "columns": [
                {"data": "blocked_date"},
                {"data": "budget_head_name"},
                {"data": "cost_center_dept_name"},
                {"data": "blocked_amount"},
                {"data": "remaining_block_amount"},
                {"data": "action", "orderable": false}
            ],
            "columnDefs": [
                {targets: 3, className: 'text-right-align'},
                {targets: 4, className: 'text-right-align'},
            ]
        });

        blockedTable = $('#blocked_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: APP_URL + '/budget-management/block-amount-list',
                data: function (params) {
                    params.fiscal_year = $('#fiscal_year :selected').val();
                    params.department = $('#department').select2().find(':selected').val();
                    params.budget_head = $('#budget_head_id').val();
                }
            },
            "columns": [
                {"data": "blocked_date"},
                {"data": "blocked_amount"},
                {"data": "block_descrip"},
                {"data": "unblock_amount"},
                /*{"data": "remarks"},*/
                {"data": "action", "orderable": false}
            ],
            "columnDefs": [
                {targets: 1, className: 'text-right-align'},
                {targets: 3, className: 'text-right-align'},
                {targets: 4, className: 'text-center'},
            ]
        });

        $(document).on('click', '.unblock', function () {
            let blockingId = $(this).data('blockingid');
            let bAmount = $(this).data('bamount');
            let unbAmount = $(this).data('unblockamnt');

            $("#block").val(blockingId);
            //$("#remain_block_amount").val(getCommaSeparatedValue(bAmount.toString()));

            $("#new_unblocked_amount").val("");
            $("#new_unblocked_amount").attr('max', bAmount);

            $("#unblocked_amount").val(unbAmount);
            $("#unblocked_remarks").val("");

            getUnblockInfoHistory(blockingId);

        });
        /*let historyTable = $(".historyTable").dataTable({
            searching: false,
            paging: true,
            columns: [
                { width: '50%' },
                { width: '50%'},
                { width: '500%'}
            ]
        });*/
        function getUnblockInfoHistory(blockId){
            let response = $.ajax({
                url: APP_URL+'/budget-management/block-amount-history/'+blockId,
                /*type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },*/
                dataType: "json"
            });
            response.done(function (data) {
                $(".historyTable >tbody").html(data.tbody);
                resetField(["#m_blocked_date","#m_blocked_amount",
                    "#m_unblocked_amount",
                    "#m_remain_block_amount"])
                $("#m_blocked_date").val(data.info.budget_blocking_date);
                $("#m_blocked_amount").val(getCommaSeparatedValue(data.info.block_amount));
                $("#m_unblocked_amount").val(getCommaSeparatedValue(data.info.unblock_amount));
                $("#m_remain_block_amount").val(getCommaSeparatedValue(data.info.remaining_block_amount));
                $("#new_unblocked_amount").attr('max', data.info.remaining_block_amount);

                $("#unblockModal").modal('show');
            });

            response.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }


        $("#new_blocked_amount").on('keyup', function () {
            let amount = removeCommaFromValue($(this).val());
            if (amount.length < $(this).attr('maxlength') && !nullEmptyUndefinedChecked(amount)) {
                $(this).val(getCommaSeparatedValue($(this).val()));
                $("#amount_word").val(amountTranslate(amount))
            } else {
                return false;
            }
        })
        $("#new_unblocked_amount").on('keyup', function () {
            maxLengthValidAmount(this); //Imam vai, don't add limit validation in frontend.
            let amount = removeCommaFromValue($(this).val());
            if (amount.length < $(this).attr('maxlength') && !nullEmptyUndefinedChecked(amount)) {
                $(this).val(getCommaSeparatedValue($(this).val()));
                $("#amount_word").val(amountTranslate(amount))
            } else {
                return false;
            }
        })
        $("#unblockAmountForm").on('submit', function (e) {
            e.preventDefault();
            let blockId = $("#block").val();
            let response = $.ajax({
                url: '{{route("budget-block-amount.update")}}',
                //url: APP_URL + '/budget-management/block-amount-update/',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    blocking_id: blockId,
                    unblock_remarks: $("#unblocked_remarks").val(),
                    unblock_amount: removeCommaFromValue($("#new_unblocked_amount").val())
                },
                dataType: "json"
            });
            response.done(function (d) {
                if (d.response_code == 1) {
                    Swal.fire({
                        type: 'success',
                        text: d.response_msg,
                        allowOutsideClick: false,
                    }).then(function () {
                        getUnblockInfoHistory(blockId);
                        $("#search_budget").trigger('click');
                        blockedTable.draw();
                        allBlockedListTable.draw();
                        resetField(["#new_unblocked_amount","#unblocked_remarks"]);
                    });
                } else {
                    Swal.fire({text: d.response_msg, type: 'error'});
                }
            });

            response.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        })

        $("#blocked_form").on("submit", function (e) {
            e.preventDefault();

            swal.fire({
                text: 'Save Block Budget Amount Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value == true) {
                    let data = new FormData(this)
                    data.set('new_blocked_amount', removeCommaFromValue(data.get('new_blocked_amount')))

                    let request = $.ajax({
                        url: APP_URL + "/budget-management/block-amount",
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
                        if (res.response_code == "1") {
                            Swal.fire({
                                type: 'success',
                                text: res.response_msg,
                                /*showConfirmButton: false,
                                timer: 2000,
                                allowOutsideClick: false*/
                            }).then(function () {
                                $("#search_budget").trigger('click');
                                resetField(['#new_blocked_amount', '#amount_word', '#description'])
                            });
                        } else {
                            Swal.fire({text: res.response_msg, type: 'error'});
                        }
                    });

                    request.fail(function (jqXHR, textStatus) {
                        //console.log(textStatus);
                        Swal.fire({text:textStatus+jqXHR,type:'error'});
                    });
                }
            })
        });
        $(".resetFrom").on('click', function () {
            resetField(["#department"])
            resetBudgetBlockedList();
        })
    </script>
@endsection



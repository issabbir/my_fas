<?php
/**
 *Created by PhpStorm
 *Created at ১৭/১১/২১ ২:৫৬ PM
 */

?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        #progressbar li {
            width: 12.50% !important;
        }

        span.badge.badge-pill {
            font-size: x-small;
        }

        /*.fixed-height-scrollable {
            max-height: 609px;
            display: block;
            overflow: auto;
        }*/
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Initialization</span></h4>
            {{--<div class="row">
                --}}{{--Workflow steps start--}}{{--
                {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_BUDGET_MGT_MASTER, App\Enums\WkReferenceColumn::BUDGET_MASTER_ID,(isset($data['insertedData']) ? $data['insertedData']->budget_master_id : null) , \App\Enums\WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL) !!}
                --}}{{--Workflow steps end--}}{{--
            </div>--}}

            @include('budget-management.preparation.form')
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="search_fiscal_year" class="required col-md-2 col-form-label">Financial Year</label>

                <select required name="search_fiscal_year" class="form-control col-md-3" id="search_fiscal_year">
                    @foreach($data['financialYear'] as $year)
                        <option
                            {{ ((isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id : '') == $year->fiscal_year_id) ? __('selected') : '' }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="budgetListTable">
                        <thead class="thead-dark">
                        <tr>
                            <th>Fiscal Year</th>
                            <th>Department/Cost Center</th>
                            <th>Initialization Period</th>
                            <th>Initialization Date</th>
                            <th class="text-center">Workflow Status</th>
                            <th class="text-center">Action</th>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var getBudgetDetails;

        function getDepartmentIntPeriod() {
            $("#department").html("");
            $("#initialization_period").html("");


            let calendarId = $("#fiscal_year :selected").val();
            if (!nullEmptyUndefinedChecked(calendarId)) {
                let request = $.ajax({
                    url: APP_URL + "/budget-management/ajax/dept-period-on-calender",
                    data: {
                        calendarId: calendarId,
                        pre_selected_dpt: $("#department").data('predpt'),
                        pre_selected_period: $("#initialization_period").data('preperiod')
                    }
                });

                request.done(function (e) {
                    $("#department").html(e.department);
                    $("#initialization_period").html(e.period);
                    if (!nullEmptyUndefinedChecked({{isset($data['insertedData'])? $data['insertedData']->budget_master_id:''}})) {
                        $("#load_budget_detail").trigger('click');
                    }
                })

                request.fail(function (jqXHR, textStatus) {
                    swal.fire({
                        text: jqXHR.responseJSON['message'],
                        type: 'warning',
                    })
                })
            }
            $("#initialization_date").trigger("click");
        }

        $("#fiscal_year").on('change', function () {
            //$("#budget_lists >tbody").html("");
            /*$("#budget_lists >tbody").html("");
            $("#budgetDetail").hide(1000);*/
            resetBudgetTable();
            getDepartmentIntPeriod();
        });
        $("#department").on('change', function () {
            //$("#budget_lists >tbody").html("");
            /*$("#budget_lists >tbody").html("");
            $("#budgetDetail").hide(1000);*/
            resetBudgetTable();
        })

        function tableSearchAllColumns() {
            // Search all columns
            $('#table_search').keyup(function () {
                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('#budget_lists table').find('tbody >tr').hide();

                // Count total search result
                var len = $('#budget_lists table').find('tbody tr:not(.notfound) td:contains("' + search + '")').length;
                if (len > 0) {
                    // Searching text in columns and show match row
                    $('#budget_lists table').find('tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                        $(this).closest('tr').show();
                    });
                } else {
                    $('.notfound').show();
                }

            });
            // Case-insensitive searching (Note - remove the below script for Case sensitive search )
            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });
        }

        function resetBudgetTable() {
            /*$("#budgetSearchBox").hide({
                effect: "slide",
                direction: "right",
                duration: 400,
                complete: hideBudgetList()
            });*/

            /*function hideBudgetList() {*/
            $("#budget_lists").hide({
                effect: "slide",
                direction: "right",
                duration: 500,
                complete: hideBudgetDetail()
            });

            /*}*/

            function hideBudgetDetail() {
                $("#budgetDetail").hide({effect: "slide", direction: "right", duration: 500});
            }

            $("#budget_lists table").remove();
            /*$("#budget_lists").hide(2000);
            //$("#budget_lists >table").hide(1000);
            $("#budgetDetail").hide(2000);*/
        }

        $(document).ready(function () {
            //For update
            getDepartmentIntPeriod();
            enableDisableSubmitBtn();
            //Ends here
            $("#budgetDetail").hide();
            resetBudgetTable();
            tableSearchAllColumns();

            function enableDisableSubmitBtn() {
                $(document).on("keyup", ".valueChangeEvent, .attachmentDescription", function (e) {
                    $("#submitBudgetBtn").prop('disabled', true);
                    $("#budgetFormDraftBtn").prop('disabled', false);

                    //If Probable Amount is negative disable save and submit
                    //0003246: Budget Estimation Training issue (UI Modification Needed)

                    $(document).find("#budget_details_list >tbody >tr").each(function(){
                        if ($("#budgetFormDraftBtn").prop('disabled')){
                            return false;
                        }else{
                            if($(this).find(".probableAmount").val() < 0){
                                $("#budgetFormDraftBtn").prop('disabled', true);
                                //$("#budgetFormDraftBtn").attr("title","Probable Amount can't be negative.");
                            }
                        }
                    });

                                     //Implementing two digit after decimal commented on 02-03-2022
                    /*if ((e.which == 109) || (e.which == 189)) {
                        let number = $(this).val();
                        if (!nullEmptyUndefinedChecked(number)) {
                            $(this).val(parseFloat(number.replace(String.fromCharCode(number.charCodeAt(0)), '')));
                        }
                    } else {
                        $(this).val(roundToTwoDecimalWithoutRule($(this).val()));
                    }*/
                });

                //#addAttachmentLine for file upload in common_file_upload.blade
                $(document).on("click", "#addAttachmentLine, #removeAttachmentBtn, .file-validation-rules", function () {
                    if (!nullEmptyUndefinedChecked($("#budget_master").val())) {
                        //console.log("Here",newAttachmentDifference());
                        /*if (newAttachmentDifference() === 0){
                            $("#submitBudgetBtn").prop('disabled', false);
                            $("#budgetFormDraftBtn").prop('disabled', true);
                        }else{
                            $("#submitBudgetBtn").prop('disabled', true);
                            $("#budgetFormDraftBtn").prop('disabled', false);
                        }*/

                        $("#submitBudgetBtn").prop('disabled', true);
                        $("#budgetFormDraftBtn").prop('disabled', false);
                    }
                });
            }

            let intCalendarClickCounter = 0;
            $("#initialization_period, #fiscal_year, #estimation_type").on('change', function () {
                resetBudgetTable();
                /*0003246: Budget Estimation Training issue (UI Modification Needed)
                $("#initialization_date >input").val("");
                if (intCalendarClickCounter > 0) {
                    $("#initialization_date").datetimepicker('destroy');
                    intCalendarClickCounter = 0;
                }*/
            });
            $("#initialization_date").on('click', function () {
                intCalendarClickCounter++;
                $("#initialization_date >input").val("");
                let minDate = $("#initialization_period :selected").data("mindate");
                let maxDate = $("#initialization_period :selected").data("maxdate");
                let currentDate = $("#initialization_period :selected").data("currentdate");

                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            $("#load_budget_detail").on('click', function () {
                let fiscalYear = $("#fiscal_year :selected");
                let department = $("#department");
                let iniPeriod = $("#initialization_period :selected");
                let iniDate = $("#initialization_date_field");
                let loadFor = $(this).data("loadfor");
                let estimationType = $("#estimation_type");

                if (nullEmptyUndefinedChecked(fiscalYear.val())) {
                    $("#fiscal_year").notify("Select Year", "notify");
                } else if (nullEmptyUndefinedChecked(department.val())) {
                    $("#department").notify("Select Department", "notify");
                } else if (nullEmptyUndefinedChecked(iniPeriod.val())) {
                    $("#initialization_period").notify("Select Period", "notify");
                }
                /*0003246: Budget Estimation Training issue (UI Modification Needed)
                else if (nullEmptyUndefinedChecked(iniDate.val())) {
                    $("#initialization_date_field").notify("Set Date", "notify");
                }*/
                else {
                    getBudgetDetails(fiscalYear.val(), department.val(), iniPeriod.val(), estimationType.val(),loadFor)
                }
                //getBudgetDetails(fiscalYear.val(),department.val(),iniDate.val())
            });

            getBudgetDetails = function (fiscal_year, dept_id, ini_period_id, estimation_type, loadFor) {
                var request = $.ajax({
                    url: APP_URL + '/budget-management/ajax/initial-budget-details',
                    method: 'GET',
                    data: {fiscal_year: fiscal_year, dept_id: dept_id, ini_period_id: ini_period_id,estimation_type:estimation_type, load_for: loadFor}
                });

                request.done(function (d) {
                    resetBudgetTable();
                    resetField(['#c_account_name', '#c_account_type', '#c_account_balance', '#c_authorized_balance', '#c_budget_head', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_amount_word']);

                    if (d.status_code != 1) {
                        $("#department").notify(d.status_message, {
                            position: "top",
                            className: 'error',
                            showDuration: 500,
                        });
                    } else {
                        $("#budget_lists").html(d.table);

                        $("#budgetDetail").show({
                            effect: "slide",
                            direction: "left",
                            duration: 1000,
                            complete: showBudgetList()
                        });

                        function showBudgetList() {
                            $("#budget_lists").show({
                                effect: "slide",
                                direction: "left",
                                duration: 800,
                                complete: showBudgetSearchBox()
                            });
                        }

                        function showBudgetSearchBox() {
                            $("#budgetSearchBox").show({effect: "slide", direction: "left"}, 1000);
                        }
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    swal.fire({
                        text: jqXHR.responseJSON['message'],
                        type: 'warning',
                    })
                });
            }

            $(document).on("click", "#budgetFormDraftBtn, #submitBudgetBtn", function () {
                $("#submission_type").val($(this).data("submission_type"));
                $("#budget_initialize").submit();
            })
            $("#budget_initialize").on("submit", function (e) {
                e.preventDefault();
                let submissionType = $("#submission_type").val();
                let text = "";

                if ($("#budget_lists tbody tr").length > 0) {
                    if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}') {
                        text = "Save Budget Data for " + pascal($("#department :selected").text()) + "?";
                    } else if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SUBMIT}}') {
                        text = "Submit Budget Data for " + pascal($("#department :selected").text()) + "?";
                    }

                    /*swal.fire({
                        text: text,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value == true) {
                            let request = $.ajax({
                                url: APP_URL + "/budget-management/preparation",
                                data: new FormData(this),
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
                                        showConfirmButton: false,
                                        timer: 2000,
                                        allowOutsideClick: false
                                    }).then(function () {
                                        //location.reload();
                                        if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}') {
                                            let redirectUrl = "{{route('preparation.edit',['id'=>':p_id'])}}";
                                            window.location.href = redirectUrl.replace(':p_id', res.last_inserted_id);
                                        } else if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SUBMIT}}') {
                                            window.location.href = "{{route('preparation.index')}}";
                                        }

                                        //window.history.back();
                                    });
                                } else {
                                    Swal.fire({text: res.response_msg, type: 'error'});
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                swal.fire({
                                    text: textStatus,
                                    type: 'warning',
                                })
                            });
                        }
                    })*/

                    swal.fire({
                        text: text,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        showLoaderOnConfirm: true,
                        preConfirm: (data) => {

                            return $.ajax({
                                url: APP_URL + "/budget-management/preparation",
                                data: new FormData(this),
                                processData: false,
                                contentType: false,
                                dataType: "JSON",
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": '{{ csrf_token()}}'
                                }
                            }).done(res => {
                                return res;
                            }).catch(error => {
                                swal.showValidationMessage(
                                    `Request failed: ${error}`
                                )
                                /*swal.fire({
                                    text: jqXHR.responseJSON,
                                    type: 'warning',
                                })*/
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (nullEmptyUndefinedChecked(result.dismiss)) {
                            //if true comes here
                            if (result.value.response_code == 1) {
                                Swal.fire({
                                    type: 'success',
                                    text: result.value.response_msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    //location.reload();
                                    if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}') {
                                        let redirectUrl = "{{route('preparation.edit',['id'=>':p_id'])}}";
                                        window.location.href = redirectUrl.replace(':p_id', result.value.last_inserted_id);
                                    } else if (submissionType == '{{\App\Enums\BudgetManagement\SubmissionType::SUBMIT}}') {
                                        window.location.href = "{{route('preparation.index')}}";
                                    }

                                    //window.history.back();
                                });
                            }else{
                                swal.fire({
                                    text: result.value.response_msg,
                                    type: 'warning',
                                })
                            }
                        }
                    })
                } else {
                    Swal.fire({
                        type: 'warning',
                        text: "Load budget details first.",
                        showConfirmButton: false,
                        timer: 2000,
                        allowOutsideClick: false
                    })
                }
            });


            $("#search_fiscal_year").on('change', function () {
                /*$('#budgetListTable').data('dt_params', {
                    calendar_id: $("#search_fiscal_year :selected").val()
                }).DataTable().draw();*/
                /*budgetTable.data('dt_params', {
                    calendar_id: $("#search_fiscal_year :selected").val()
                }).DataTable().draw();*/
                budgetTable.draw();
            });

            let budgetTable = $('#budgetListTable').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: '{{route('preparation.budget-datalist')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        // Retrieve dynamic parameters
                        params.calendar_id = $("#search_fiscal_year :selected").val();
                        /*var dt_params = $('#budgetListTable').data('dt_params');
                        // Add dynamic parameters to the data object sent to the server
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }*/
                    }
                },
                "columns": [
                    {data: 'fiscal_calendar_name', "name": 'fiscal_calendar_name'},
                    {data: 'cost_center_dept_name', "name": 'cost_center_dept_name'},
                    {data: "budget_init_period_name", name: "budget_init_period_name"},
                    {data: "budget_init_date", name: "budget_init_date"},
                    {data: "workflow_status_name", name: "workflow_status_name"},
                    {data: "action", "orderable": false}
                ],
            });
        });
    </script>

@endsection

@extends("layouts.default")

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
            {{--<div class="spinner-border text-secondary" id="loader" role="status">
                <span class="sr-only">Loading...</span>
            </div>--}}
            @include("gl.journal-voucher.form")
        </div>
    </div>

    <section>
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="accountListModal" tabindex="-1" role="dialog"
                         aria-labelledby="accountListModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="accountListModalLabel">Account Search</h4>
                                    <button type="button" class="close btn btn-sm" data-dismiss="modal"
                                            aria-label="Close"><i
                                            class="bx bx-x font-size-small"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="#" id="acc_search_form">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label for="acc_department" class="col-md-4 col-form-label">Department</label>
                                                    <input type="text" disabled name="acc_department"
                                                           id="acc_department"
                                                           class="form-control form-control-sm col-md-8">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class=" form-group row">
                                                    <label for="acc_type" class="col-md-5 col-form-label">Account
                                                        Type</label>
                                                    <select class="form-control form-control-sm col-md-7"
                                                            name="acc_type"
                                                            id="acc_type">
                                                        <option value="">&lt;Select&gt;</option>
                                                        @foreach($accountType as $type)
                                                            <option
                                                                value="{{$type->gl_type_id}}">{{$type->gl_type_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="acc_name_code" id="acc_name_code"
                                                       class="form-control form-control-sm"
                                                       placeholder="Look for Account Name or Code">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-sm btn-success acc_search"><i
                                                        class="bx bx-search font-size-small align-middle"></i><span
                                                        class="align-middle ml-25">Search</span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-dark acc_reset"
                                                        id="acc_modal_reset"><i
                                                        class="bx bx-reset font-size-small align-middle"></i><span
                                                        class="align-middle ml-25">Reset</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table id="account_list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    {{--<th>SL</th>--}}
                                                    <th>Account ID</th>
                                                    <th>Account Name</th>
                                                    <th>Account Code</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal"><i
                                            class="bx bx-x d-block d-sm-none font-size-small"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('ar.ar-common.common_customer_list_modal')
    @include('ap.ap-common.common_vendor_list_modal')
    @include('ap.invoice-bill-entry.common_budged_search')

@endsection

@section('footer-script')
    <script type="text/javascript">
        var getAccountDetail;
        var resetAccountField;
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var resetPayableReceivableFields;
        $(document).ready(function () {

            resetAccountField = function () {
                $('#account_balance_type').text('');
                $('#authorized_balance_type').text('');
                resetField(['#module_id', '#account_name', '#account_type', '#account_balance',
                    '#authorized_balance', '#budget_head', '#currency', '#amount_ccy', '#amount_lcy',
                    '#exchange_rate', '#amount_word', '#department_cost_center']);

                /**0002588:Add logic for provision journal**/
                enableDisableDrCr();
                /*$("#addNewLineBtn").removeAttr('disabled');
                $("#addNewLineBtn").html("<i class='bx bxs-plus-circle'></i>Add");
                $("#addNewLineBtn").attr('data-type', 'A');
                $("#addNewLineBtn").attr('data-line', '');*/
                /**0002588:End logic for provision journal**/
            }

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            $("#period").on('change', function () {
                $("#document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                setPeriodCurrentDate();
            });

            /********Added on: 06/06/2022, sujon**********/
            function setPeriodCurrentDate() {
                $("#posting_date_field").val($("#period :selected").data("currentdate"));
                $("#document_date_field").val($("#period :selected").data("currentdate"));
            }

            /********End**********/

            $("#document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#document_date >input").val("");
                let minDate = false;
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            $("#posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            let postingDateClickCounter = 0;
            $("#posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                let postingDate = $("#posting_date_field").val();
                if (!nullEmptyUndefinedChecked($("#posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                    } else {
                        newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                    }

                    $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            function listBillRegister() {
                $('#bill_section').change(function (e) {
                    $("#bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#bill_register', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            $("#acc_modal_reset").on('click', function () {
                $("#acc_type").val('');
                $("#acc_name_code").val('');
                accountTable.draw();
            });


            /*
            * journal start
            * */
            $("#amount_ccy").on("keyup", function () {
                let amount_ccy_keyup = parseFloat($(this).val());
                if (!is_negative(amount_ccy_keyup) && amount_ccy_keyup != 0) {
                    let exchange_rate_get = parseFloat($("#exchange_rate").val());
                    //$('#amount_ccy').val(amount_ccy_keyup);

                    if (amount_ccy_keyup && exchange_rate_get) {
                        let lcy = (amount_ccy_keyup * exchange_rate_get);
                        $('#amount_lcy').val(lcy);
                    } else {
                        $('#amount_lcy').val('0');
                    }
                } else {
                    $('#amount_ccy').val('0');
                    $('#amount_lcy').val('0');
                }
            });

            let accountTable = $('#account_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                /*bDestroy : true,
                pageLength: 20,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
                ajax: {
                    url: APP_URL + '/general-ledger/journal-acc-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.glType = $('#acc_type :selected').val();
                        params.accNameCode = $('#acc_name_code').val();
                    }
                },
                "columns": [
                    /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                    {"data": "gl_acc_id", "class": "25"},   // ADD THIS TWO ROW CLASS. PAVEL-11-04-22
                    {"data": "gl_acc_name", "class": "w-50"},
                    {"data": "gl_acc_code"},
                    {"data": "action"}
                ],

                /*language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }*/
            });

            $("#acc_search_form").on('submit', function (e) {
                e.preventDefault();
                accountTable.draw();
            });

            $("#searchAccount").on("click", function () {
                $accId = $("#account_id").val();
                let costCenterDpt = $('#department :selected').val(); //Add costCenterDpt Part :PAVEL-11-04-22
                if (!nullEmptyUndefinedChecked(costCenterDpt)) {
                    if ($accId != "") {
                        getAccountDetail($accId);
                    } else {
                        $("#accountListModal").modal('show');
                        accountTable.draw();
                        $("#acc_department").val($("#department :selected").text()); // ADD THIS SEC. PAVEL-11-04-22
                    }
                } else {
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});
                }

            });

            /**0002588:Add logic for provision journal**/
            function enableDisableDrCr() {
                if ($("#provision_journal").is(":checked")) {
                    if (($("#account_type").val() == '{{\App\Enums\Common\GlCoaParams::EXPENSE_KEY}}') || ($("#account_type").val() == '{{\App\Enums\Common\GlCoaParams::ASSET_KEY}}')) {
                        $("#dr_cr").val('{{\App\Enums\Common\DebitCredit::DEBIT}}').trigger('change').addClass('make-readonly-bg');
                        $(".budget_booking_utilized_div").removeClass('d-none')
                    } else if ($("#account_type").val() == '{{\App\Enums\Common\GlCoaParams::LIABILITY_KEY}}') {
                        $("#dr_cr").val('{{\App\Enums\Common\DebitCredit::CREDIT}}').trigger('change').addClass('make-readonly-bg');
                        resetBudgetField();
                        $(".budget_booking_utilized_div").addClass('d-none')
                    } else {
                        $("#dr_cr").removeClass('make-readonly-bg');
                        resetBudgetField();
                        $(".budget_booking_utilized_div").addClass('d-none')
                    }
                } else {
                    $("#dr_cr").removeClass('make-readonly-bg');
                    resetBudgetField();
                    $(".budget_booking_utilized_div").addClass('d-none')
                }
            }

            resetTransactionForProvision();

            function resetTransactionForProvision() {
                $("#provision_journal").on('click', function () {
                    resetAccountField();
                    resetPayableReceivableFields();
                    resetTablesDynamicRow("#account_table");
                    resetField(['#total_debit', '#total_credit']);
                })
            }

            function validateProvisionLogic() {
                if (($("#provision_journal").is(":checked")) && ($("#dr_cr").val() == '{{\App\Enums\Common\DebitCredit::DEBIT}}') && ($("#account_type").val() != '{{\App\Enums\Common\GlCoaParams::EXPENSE_KEY}}') && ($("#account_type").val() != '{{\App\Enums\Common\GlCoaParams::ASSET_KEY}}')) {
                    $("#addNewLineBtn").attr('disabled', 'disabled');
                    Swal.fire({text: 'For Debit side: account type must be EXPENSE OR ASSET.', type: 'warning'});
                    return false;
                } else if (($("#provision_journal").is(":checked")) && ($("#dr_cr").val() == '{{\App\Enums\Common\DebitCredit::CREDIT}}')) {
                    let vendorParams = $("#ap_party_sub_ledger").find(':selected').data("partyparams");
                    if (!nullEmptyUndefinedChecked(vendorParams)) {
                        let vendorArray = vendorParams.split("#");
                        if (vendorArray[2] != '{{\App\Enums\Common\LGlSubsidiaryType::PROVISION}}') {
                            $("#addNewLineBtn").attr('disabled', 'disabled');
                            Swal.fire({
                                text: 'Party Sub Ledger must be \'Sundry Credit / Provision for Expense\'.',
                                type: 'warning'
                            });
                            return false;
                        } else {
                            return fieldsAreSet(['#ap_vendor_id']);
                        }
                    } else {
                        $("#addNewLineBtn").attr('disabled', 'disabled');
                        Swal.fire({
                            text: 'Party Sub Ledger must be \'Sundry Credit / Provision for Expense\'.',
                            type: 'warning'
                        });
                        return false;
                    }
                } else if (($("#provision_journal").is(":checked")) && ($("#dr_cr").val() == '{{\App\Enums\Common\DebitCredit::DEBIT}}')) {
                    if (!$("#ap_without_budget_info").is(":checked")) {
                        return fieldsAreSet(['#b_head_id']);
                    } else {
                        return true;
                    }
                } else {
                    $("#addNewLineBtn").removeAttr('disabled');
                    return true;
                }
            }

            enableAddButtonForDrCr();

            function enableAddButtonForDrCr() {
                $("#dr_cr").on('change', function () {
                    $("#addNewLineBtn").removeAttr('disabled')
                })
            }

            $("#department").on('change', function () {
                resetField(["#b_head_id"]);
                resetBudgetField();
                resetBudgetHeadBookingTables();
            });
            /**Start Budget Booking Info***/
            $("#ap_without_budget_info").on("click", function () {

                if ($(this).prop('checked')) {
                    $("#b_booking_search").prop('disabled', true);
                    $("#budget_department").attr('disabled', 'disabled');
                    $("#budget_department").val('').trigger('change');
                    resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);

                } else {
                    $("#b_booking_search").prop('disabled', false);
                    $("#budget_department").removeAttr('disabled');
                }

            });

            $("#b_booking_search").on("click", function () {
                //let department = $('#department :selected').val();
                let department = $('#budget_department :selected').val();
                resetBudgetField();

                if (!nullEmptyUndefinedChecked(department)) {
                    $('#b_head_id').val("")
                    reloadBudgetListTable();

                    $("#s_fiscal_year").val($("#th_fiscal_year :selected").text().trim());
                    $("#s_department").val($("#budget_department :selected").text());
                    //$("#s_department").val($("#department :selected").text());

                    $("#budget_booking_list").data("dt_params", {
                        "department": $('#budget_department :selected').val(),
                        //"department": $('#department :selected').val(),
                        "calendar": $('#th_fiscal_year :selected').val(),
                        "nameCode": $('#s_budget_head_name_code').val(),
                        "vendorId": $('#ap_vendor_id').val()
                    }).DataTable().draw();

                    $("#budgetListModal").modal('show');
                } else {
                    resetField(['#b_head_id']);
                    if (nullEmptyUndefinedChecked(department)) {
                        $("#budget_department").focus();
                        $('html, body').animate({scrollTop: ($("#budget_department").offset().top - 400)}, 2000);
                        $("#budget_department").notify("Select Department First.", {position: 'left'});
                    }
                }

            })

            $(document).on('submit', '#booking_search_form', function (e) {
                e.preventDefault();
                //resetBudgetHeadBookingTables();

                /*$("#budget_head_list").data("dt_params", {*/
                $("#budget_booking_list").data("dt_params", {
                    "department": $('#department :selected').val(),
                    "calendar": $('#th_fiscal_year :selected').val(), //$('#fiscal_year :selected').val(),
                    "nameCode": $('#s_budget_head_name_code').val(),
                    "vendorId": $('#ap_vendor_id').val()
                }).DataTable().draw();
            })

            let budgetBookingTable = $('#budget_booking_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/account-payable/ajax/budget-booking-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        let dt_params = $("#budget_booking_list").data('dt_params');
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }
                    }
                },
                "columns": [
                    {"data": 'budget_head_id', "name": 'budget_head_id'},
                    {"data": "budget_head_name"},
                    {"data": "category_name"},
                    {"data": "budget_type"},
                    {"data": "budget_booking_amt"},
                    {"data": "budget_utilized_amt"},
                    {"data": "available_amount"},
                    {"data": "action", "orderable": false}
                ], createdRow: function (row, data, index) {
                    $('td', row).eq(2).addClass("text-right");
                    $('td', row).eq(3).addClass("text-right");
                    $('td', row).eq(4).addClass("text-right");
                }
            });

            $(document).on('click', '.budgetSelect', function () {
                let budgetHeadId = $(this).data('budget-head-id');
                let department = $('#department :selected').val();
                let calendar = $('#th_fiscal_year :selected').val();  //$('#fiscal_year :selected').val();
                getBudgetBookingDetailInfo(budgetHeadId, department, calendar)
            });

            function getBudgetBookingDetailInfo(budgetHeadId, department, calendar) {
                var request = $.ajax({
                    url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                    data: {budget_head_id: budgetHeadId, department: department, calendar: calendar}
                });

                request.done(function (d) {
                    if ($.isEmptyObject(d.data)) {
                        $("#b_head_id").notify("Budget Head ID Not Found", "error");
                        resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt',
                            '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
                    } else {
                        $('#b_head_id').val(d.data.budget_head_id);
                        $('#b_amt').val(d.data.budget_booking_amt);
                        $('#b_head_name').val(d.data.budget_head_name);
                        $('#b_sub_category').val(d.data.sub_category_name);
                        $('#b_category').val(d.data.category_name);
                        $('#b_type').val(d.data.budget_type);
                        $('#b_utilized_amt').val(d.data.budget_utilized_amt);
                        $('#b_available_amt').val(d.data.available_amount);
                    }
                    $("#budgetListModal").modal('hide');
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

            function reloadBudgetListTable() {
                budgetBookingTable.draw();
            }

            function resetBudgetField() {
                resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt',
                    '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
                $("#ap_without_budget_info").prop('checked', false);
                $("#b_booking_search").prop('disabled', false);
            }


            function resetBudgetHeadBookingTables() {
                $('#budget_booking_list').data('dt_params', {
                    "budget_head_id": "",
                    "department": "",
                    "calendar": "",
                    "vendorId": ""
                }).DataTable().draw();
            }

            /**End Budget Booking Info***/
            /**0002588:End logic for provision journal**/

            //src = 1 from modal, src = 2 from search
            getAccountDetail = function (accId) {
                var request = $.ajax({
                    url: APP_URL + '/general-ledger/ajax/get-account-details',
                    method: 'POST',
                    data: {accId: accId},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                request.done(function (d) {
                    resetField(['#account_name', '#account_type', '#account_balance', '#authorized_balance', '#budget_head', '#currency', '#exchange_rate']);

                    if ($.isEmptyObject(d.account_info)) {
                        $("#account_id").notify("Account id not found", "error");
                    } else {
                        $("#account_id").val(d.account_info.gl_acc_id);
                        $("#account_name").val(d.account_info.gl_acc_name);
                        $("#account_type").val(d.account_info.gl_type_name);
                        $("#account_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                        $("#account_balance_type").text(d.account_info.account_balance_type);
                        $("#authorized_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                        $("#authorized_balance_type").text(d.account_info.authorize_balance_type);

                        $("#budget_head").val(d.account_info.budget_head_line_name);
                        $("#currency").val(d.account_info.currency_code);
                        $("#exchange_rate").val(d.account_info.exchange_rate);
                        if (nullEmptyUndefinedChecked(d.account_info.cost_center_dept_name)) {
                            $("#department_cost_center").html('');
                        } else {
                            $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                        }
                        $("#module_id").val(d.account_info.module_id);

                        if (!nullEmptyUndefinedChecked(d.account_info.module_id)) {
                            if (d.account_info.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                                $(".receivableArea").removeClass('hidden');
                                $("#ar_party_sub_ledger").html(d.sub_ledgers);
                            } else if (d.account_info.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                                $(".payableArea").removeClass('hidden');
                                $("#ap_party_sub_ledger").html(d.sub_ledgers);

                                if (!nullEmptyUndefinedChecked(d.party_info)) {
                                    console.log(d.party_info)
                                    $("#ap_vendor_id").val(d.party_info.party_id).addClass('make-readonly-bg').attr("tabindex", "-1");
                                    $("#ap_vendor_search").attr('disabled', 'disabled');
                                    $("#ap_vendor_name").val(d.party_info.party_name);
                                    $("#ap_vendor_category").val(d.party_info.party_category);
                                    $("#ap_account_balance").val(getCommaSeparatedValue(d.party_info.account_balance));
                                    $("#ap_authorized_balance").val(getCommaSeparatedValue(d.party_info.authorized_balance));
                                    $("#ap_account_balance_type").text(d.party_info.account_balance_type);
                                    $("#ap_authorized_balance_type").text(d.party_info.authorized_balance_type);
                                } else {
                                    $("#ap_vendor_id").removeClass('make-readonly-bg').removeAttr("tabindex");
                                    $("#ap_vendor_search").removeAttr('disabled');
                                }
                            } else {
                                resetPayableReceivableFields();
                            }
                        } else {
                            resetPayableReceivableFields();
                        }

                        /**0002588:Add logic for provision journal**/
                        enableDisableDrCr();
                        $("#addNewLineBtn").removeAttr('disabled');
                        /**0002588:End logic for provision journal**/
                        openCloseRateLcy(d.account_info.currency_code);

                        $("#accountListModal").modal('hide');

                        /*$("#amount_ccy").focus();
                        $('html, body').animate({scrollTop: ($("#amount_ccy").offset().top - 400)}, 2000);
                    */
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

            function openCloseRateLcy(currency) {
                if (currency == 'USD') {
                    $("#exchange_rate").removeAttr('readonly');
                    $("#amount_lcy").removeAttr('readonly');
                } else {
                    $("#exchange_rate").attr('readonly', 'readonly');
                    $("#amount_lcy").attr('readonly', 'readonly');
                }
            }

            addLineRow = function (selector) {
                if (fieldsAreSet(['#amount_ccy', '#account_id', '#account_name', '#amount_lcy']) && validateProvisionLogic()) {
                    if ($(selector).attr('data-type') == 'A') {
                        let transaction = getTransactionFieldsData();
                        let count = $("#account_table >tbody").children("tr").length;

                        let html = '<tr>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" name="line[' + count + '][account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + transaction.account_id + '" readonly/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][dr_cr]" id="dr_cr' + count + '" value="' + transaction.debitCredit + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][currency]" id="currency' + count + '" value="' + transaction.currency + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][exchange_rate]" id="exchange_rate' + count + '" value="' + transaction.exchangeRate + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][acc_type]" id="c_account_type' + count + '" value="' + transaction.accountType + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][budget_head]" id="budget_head' + count + '" value="' + transaction.budgetHead + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][budget_head_name]" id="budget_head_name' + count + '" value="' + transaction.budgetHeadName + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][acc_balance]" id="account_balance' + count + '" value="' + transaction.accountBalance + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][module_id]" id="module_id' + count + '" value="' + transaction.module_id + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][party_sub_ledger]" id="party_sub_ledger' + count + '" value="' + transaction.partySubLedger + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][amount_ccy]" id="amount_ccy' + count + '" value="' + transaction.amountCcy + '"/>' +

                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_sub_category]" id="b_sub_category' + count + '" value="' + transaction.b_sub_category + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_category]" id="b_category' + count + '" value="' + transaction.b_category + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_type]" id="b_type' + count + '" value="' + transaction.b_type + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_booking_amt]" id="b_booking_amt' + count + '" value="' + transaction.b_booking_amt + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_utilized_amt]" id="b_utilized_amt' + count + '" value="' + transaction.b_utilized_amt + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][b_available_amt]" id="b_available_amt' + count + '" value="' + transaction.b_available_amt + '"/>' +

                            '<input tabindex="-1" type="hidden" name="line[' + count + '][action_type]" id="action_type' + count + '" value="A" />' +
                            '</td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + transaction.accountName + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][party_id]" id="party_id' + count + '" class="form-control form-control-sm" value="' + transaction.partyId + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][party_name]" id="party_name' + count + '" class="form-control form-control-sm" value="' + transaction.partyName + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" type="text" class="form-control form-control-sm text-right-align debit" name="line[' + count + '][debit_amount]" id="debit_amount' + count + '" value="' + transaction.debitAmountLcy + '" readonly>' +
                            '</td>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" type="text" class="form-control form-control-sm text-right-align credit" name="line[' + count + '][credit_amount]" id="credit_amount' + count + '" value="' + transaction.creditAmountLcy + '" readonly>' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][budget_name]" id="budget_name' + count + '" class="form-control form-control-sm" value="' + transaction.budgetHeadName + '" readonly/></td></td>\n' +
                            '</td>\n' +
                            '      <td style="padding: 2px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editAccountBtn" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#account_table >tbody").append(html);
                    } else {
                        var lineToUpdate = $(selector).attr('data-line');
                        updateLineValue(lineToUpdate);
                    }

                    let total = sumOfDebitSumOfCredit();
                    setTotalDebitCredit(total);
                    resetField(['#account_id', '#account_name', '#account_type', '#account_balance', '#authorized_balance', '#budget_head', '#currency', '#amount_ccy', '#amount_lcy', '#exchange_rate', '#amount_word']);

                    $("#account_balance_type").text('');
                    $("#authorized_balance_type").text('');

                    $("#ap_account_balance_type").text('');
                    $("#ap_authorized_balance_type").text('');

                    $("#ar_account_balance_type").text('');
                    $("#ar_authorized_balance_type").text('');

                    if (!nullEmptyUndefinedChecked($("#total_debit").val()) && !nullEmptyUndefinedChecked($("#total_credit").val()) && (total.debit == total.credit)) {
                        $("#journalFormSubmitBtn").focus();
                        $('html, body').animate({scrollTop: ($("#journalFormSubmitBtn").offset().top - 400)}, 2000);
                    } else {
                        $("#account_id").val('').focus();
                        $('html, body').animate({scrollTop: ($("#account_id").offset().top - 400)}, 2000);
                    }
                    /**0002588:Add logic for provision journal**/
                    enableDisableDrCr();
                    resetBudgetField();
                    resetBudgetHeadBookingTables();
                    $(".budget_booking_utilized_div").addClass('d-none');
                    /**0002588:End logic for provision journal**/
                    enableDisableSaveBtn();
                    openCloseRateLcy('');

                    resetPayableReceivableFields();
                }
            }

            removeLineRow = function (select, lineRow) {
                $("#action_type" + lineRow).val('D');
                $(select).closest("tr").hide();
                setTotalDebitCredit(sumOfDebitSumOfCredit());
                enableDisableSaveBtn();
                openCloseRateLcy('');
            }

            editAccount = function (selector, line) {
                $("#remove_btn" + line).hide();
                $("#module_id").val($("#module_id" + line).val());
                $("#account_id").val($("#account_code" + line).val());
                $("#searchAccount").trigger('click');
                $("#dr_cr").val($("#dr_cr" + line).val());

                //----------Added by Sabbir 28/2/23--------Start//
                $("#b_head_id").val($("#budget_head" + line).val());
                $("#b_head_name").val($("#budget_head_name" + line).val());
                $("#b_sub_category").val($("#b_sub_category" + line).val());
                $("#b_category").val($("#b_category" + line).val());
                $("#b_type").val($("#b_type" + line).val());
                $("#b_amt").val($("#b_booking_amt" + line).val());
                $("#b_utilized_amt").val($("#b_utilized_amt" + line).val());
                $("#b_available_amt").val($("#b_available_amt" + line).val());
                //----------Added by Sabbir 28/2/23--------End

                let amountCCY = $("#amount_ccy" + line).val();
                let amountLCY = 0.0;
                if ($("#dr_cr" + line).val() == '{{\App\Enums\Common\DebitCredit::DEBIT}}') {
                    amountLCY = $("#debit_amount" + line).val();
                } else {
                    amountLCY = $("#credit_amount" + line).val();
                }
                $("#amount_ccy").val(amountCCY);
                $("#amount_lcy").val(amountLCY);

                if ($("#module_id" + line).val() == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                    $("#ar_party_sub_ledger").val($("#party_sub_ledger" + line).val());
                    $("#ar_customer_id").val($("#party_id" + line).val());
                    if (!nullEmptyUndefinedChecked($("#party_id" + line).val())) {
                        $("#ar_customer_search").trigger('click');
                    }
                } else {
                    $("#ap_party_sub_ledger").val($("#party_sub_ledger" + line).val());
                    $("#ap_vendor_id").val($("#party_id" + line).val());
                    if (!nullEmptyUndefinedChecked($("#party_id" + line).val())) {
                        $("#ap_vendor_search").trigger('click');
                    }
                }

                if (!nullEmptyUndefinedChecked($("#budget_head" + line).val())) {
                    //getBudgetBookingDetailInfo($("#b_head_id").val($("#budget_head"+line).val()), $('#department :selected').val(), $('#th_fiscal_year :selected').val());
                }
                $(".editAccountBtn").addClass('d-none');
                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-edit'></i>UPDATE");
                $(select).attr('data-type', 'U');
                $(select).attr('data-line', line);
                $("#preview_btn").prop('disabled', true);
                $("#journalFormSubmitBtn").prop('disabled', true);
                $("#amount_word").val(amountTranslate(amountCCY));
            }

            function getTransactionFieldsData() {
                let account_id = $('#account_id').val();
                let debitCredit = $("#dr_cr :selected").val();
                let currency = $("#currency").val();
                let exchangeRate = $('#exchange_rate').val();
                let accountType = $('#account_type').val();
                let budgetHead = $('#b_head_id').val();
                let budgetHeadName = $('#b_head_name').val();
                let accountBalance = $('#account_balance').val();
                let module_id = $("#module_id").val();
                let partySubLedger = "";
                let amountCcy = $("#amount_ccy").val();
                let accountName = $("#account_name").val();
                //------------Added by Sabbir 28/2/23---------
                let b_sub_category = $("#b_sub_category").val();
                let b_category = $("#b_category").val();
                let b_type = $("#b_type").val();
                let b_booking_amt = $("#b_amt").val();
                let b_utilized_amt = $("#b_utilized_amt").val();
                let b_available_amt = $("#b_available_amt").val();
                //------------Added by Sabbir 28/2/23---------
                let partyId = "";
                let partyName = "";
                let debitAmountLcy = 0.0;
                let creditAmountLcy = 0.0;

                if (!nullEmptyUndefinedChecked(module_id)) {
                    if (module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                        partySubLedger = $("#ar_party_sub_ledger :selected").val();
                        partyId = $("#ar_customer_id").val();
                        partyName = $("#ar_customer_name").val();
                    } else {
                        partySubLedger = $("#ap_party_sub_ledger :selected").val();
                        partyId = $("#ap_vendor_id").val();
                        partyName = $("#ap_vendor_name").val();
                    }
                }

                if (debitCredit == '{{\App\Enums\Common\DebitCredit::DEBIT}}') {
                    debitAmountLcy = $("#amount_lcy").val();
                } else {
                    creditAmountLcy = $("#amount_lcy").val();
                }

                return {
                    account_id
                    , b_sub_category
                    , b_category
                    , b_type
                    , b_booking_amt
                    , b_utilized_amt
                    , b_available_amt
                    , debitCredit
                    , currency
                    , exchangeRate
                    , accountType
                    , budgetHead
                    , budgetHeadName
                    , accountBalance
                    , module_id
                    , partySubLedger
                    , amountCcy
                    , accountName
                    , partyId
                    , partyName
                    , debitAmountLcy
                    , creditAmountLcy
                };
            }

            function updateLineValue(line) {
                let transaction = getTransactionFieldsData();

                $("#account_code" + line).val(transaction.account_id);
                $("#dr_cr" + line).val(transaction.debitCredit)
                $("#currency" + line).val(transaction.currency);
                $("#exchange_rate" + line).val(transaction.exchangeRate);
                $("#account_type" + line).val(transaction.accountType);
                $("#budget_head" + line).val(transaction.budgetHead);
                $("#budget_name" + line).val(transaction.budgetHeadName);
                $("#account_balance" + line).val(transaction.accountBalance);
                $("#module_id" + line).val(transaction.module_id);
                $("#party_sub_ledger" + line).val(transaction.partySubLedger);
                $("#amount_ccy" + line).val(transaction.amountCcy);
                $("#account_name" + line).val(transaction.accountName);
                $("#party_id" + line).val(transaction.partyId);
                $("#party_name" + line).val(transaction.partyName);
                $("#debit_amount" + line).val(transaction.debitAmountLcy);
                $("#credit_amount" + line).val(transaction.creditAmountLcy);
                $(".editAccountBtn").removeClass('d-none');

                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-plus-circle'></i>ADD");
                $(select).attr('data-type', 'A');
                $(select).attr('data-line', '');
                $("#preview_btn").prop('disabled', false);
                $("#journalFormSubmitBtn").prop('disabled', false);
                enableDisableSaveBtn();
                $("#remove_btn" + line).show();
            }

            function setTotalDebitCredit(total) {
                /** Block previous code- Pavel:04-07-22 **/
                /*$("#total_debit").val(total.debit);
                $("#total_credit").val(total.credit);*/

                /** Add to digit- Pavel:04-07-22 **/
                $("#total_debit").val((total.debit).toFixed(2));
                $("#total_credit").val((total.credit).toFixed(2));
            }

            function sumOfDebitSumOfCredit() {
                let debit = $("#account_table >tbody >tr").find(".debit");
                let credit = $("#account_table >tbody >tr").find(".credit");
                let totalDebit = 0.0;
                let totalCredit = 0.0;

                function getTotal() {
                    if ($(this).is(":hidden") == false) {
                        if ($(this).val() != "" && $(this).val() != "0") {
                            return parseFloat($(this).val());
                        }
                    }
                }

                debit.each(function () {
                    if ($(this).is(":hidden") == false) {
                        if ($(this).val() != "" && $(this).val() != "0") {
                            totalDebit += parseFloat($(this).val());
                        }
                    }
                })

                credit.each(function () {
                    if ($(this).is(":hidden") == false) {
                        if ($(this).val() != "" && $(this).val() != "0") {
                            totalCredit += parseFloat($(this).val());
                        }
                    }
                })

                return {debit: totalDebit, credit: totalCredit};
            }

            /*
            * journal ends
            * */

            resetPayableReceivableFields = function () {
                resetField([
                    "#ar_party_sub_ledger"
                    , "#ar_customer_id"
                    , "#ar_customer_name"
                    , "#ar_customer_category"
                    , "#ar_account_balance"
                    , "#ar_authorized_balance"

                    , "#ap_party_sub_ledger"
                    , "#ap_vendor_id"
                    , "#ap_vendor_name"
                    , "#ap_vendor_category"
                    , "#search_vendor_type"
                    , "#search_vendor_category"
                    , "#ap_account_balance"
                    , "#ap_authorized_balance"]
                )
                $(".receivableArea").addClass('hidden');
                $(".payableArea").addClass('hidden');

            }

            /*
            * Customer search starts from here
            * */
            function customerInfoList() {
                $("#ar_customer_search").on("click", function () {
                    let customerId = $('#ar_customer_id').val();

                    if (!nullEmptyUndefinedChecked(customerId)) {
                        getCustomerDetail(customerId);
                    } else {
                        reloadCustomerListTable();
                        $("#customerListModal").modal('show');
                    }
                });

                function reloadCustomerListTable() {
                    $('#customerSearch').data("dt_params", {
                        customerCategory: $('#search_customer_category :selected').val(),
                        customerName: $('#search_customer_name').val(),
                        customerShortName: $('#search_customer_short_name').val(),
                    }).DataTable().draw();
                }

                $("#customer_search_form").on('submit', function (e) {
                    e.preventDefault();
                    reloadCustomerListTable();
                    //accountTable.draw();
                });

                $("#ar_reset_customer_balance_field").on("click", function () {
                    resetField(['#ar_search_customer_id', '#ar_search_customer_name', '#ar_search_customer_category', '#ar_bills_receivable', '#ar_prepayments', '#ar_security_deposits', '#ar_advance', '#ar_imprest_cash', '#ar_revolving_cash']);
                });

                $(document).on('click', '.customerSelect', function () {
                    getCustomerDetail($(this).data('customer'));
                });

                function getCustomerDetail(customer_id) {
                    //let invoiceParams = $("#ar_transaction_type").find(':selected').data("invoiceparams");
                    let customerType = '';
                    let customerCategory = '';

                    var request = $.ajax({
                        url: APP_URL + '/general-ledger/ajax/get-party-account-details',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        data: {
                            glSubsidiaryId: $("#ar_party_sub_ledger :selected").val(),
                            customerId: customer_id,
                        }
                    });

                    request.done(function (d) {
                        if ($.isEmptyObject(d.party_info)) {
                            $("#ar_customer_id").notify("Customer id not found", "error");
                            resetField(['#ar_customer_id', '#ar_customer_name', '#ar_customer_category']);
                        } else {
                            $('#ar_customer_id').val(d.party_info.party_id);
                            $('#ar_customer_name').val(d.party_info.party_name);
                            $('#ar_customer_category').val(d.party_info.party_category);
                            $('#ar_account_balance').val(getCommaSeparatedValue(d.party_info.account_balance));
                            $('#ar_authorized_balance').val(getCommaSeparatedValue(d.party_info.authorized_balance));

                            $("#ar_account_balance_type").text(d.party_info.account_balance_type);
                            $("#ar_authorized_balance_type").text(d.party_info.authorized_balance_type);
                        }
                        $("#customerListModal").modal('hide');
                    });

                    request.fail(function (jqXHR, textStatus) {
                        console.log(jqXHR);
                    });
                }

                let customerTable = $('#customerSearch').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ordering: false,
                    ajax: {
                        url: APP_URL + '/account-receivable/ajax/customer-search-datalist',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: function (params) {
                            // Retrieve dynamic parameters
                            var dt_params = $('#customerSearch').data('dt_params');
                            // Add dynamic parameters to the data object sent to the server
                            if (dt_params) {
                                $.extend(params, dt_params);
                            }
                        }
                    },
                    "columns": [
                        {"data": 'customer_id', "name": 'customer_id'},
                        {"data": "name"},
                        {"data": "short_name"},
                        {"data": "address"},
                        {"data": "action", "orderable": false}
                    ],
                });
                $(document).on('shown.bs.modal', '#customerListModal', function () {
                    customerTable.columns.adjust().draw();
                });
            }

            /*
            * customer search ends here
            * */

            /*
            * Vendor search starts from here
            * */
            function vendorInfoList() {

                $(" #ap_vendor_search").on("click", function () {
                    let vendorId = $('#ap_vendor_id').val();

                    $('#ap_switch_pay_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}'); /*** Add this variable -Pavel: 23-03-22 ***/


                    if (!nullEmptyUndefinedChecked(vendorId)) {
                        getVendorDetail(vendorId);
                    } else {
                        let vendorParams = $("#ap_party_sub_ledger").find(':selected').data("partyparams");
                        if (!nullEmptyUndefinedChecked(vendorParams)) {
                            let vendorParamArray = vendorParams.split("#");
                            /*
                            0=> vendor type
                            1=> vendor category
                            2=> GL Subsidiary Type Id
                             */
                            if (!nullEmptyUndefinedChecked(vendorParamArray[0])) {
                                $("#search_vendor_type").val(vendorParamArray[0]).addClass('make-readonly-bg');
                            } else {
                                $("#search_vendor_type").val('').removeClass('make-readonly-bg');
                            }

                            if (!nullEmptyUndefinedChecked(vendorParamArray[1])) {
                                $("#search_vendor_category").val(vendorParamArray[1]).addClass('make-readonly-bg');
                            } else {
                                $("#search_vendor_category").val('').removeClass('make-readonly-bg');
                            }
                        }
                        reloadVendorListTable();
                        $("#vendorListModal").modal('show');
                    }
                });

                /*** Add this section start -Pavel: 23-03-22 ***/
                $("#ap_switch_pay_vendor_search").on("click", function () {
                    let vendorId = $("#ap_switch_pay_vendor_id").val();
                    let invoiceType = $("#ap_invoice_type").val();

                    $('#ap_switch_pay_vendor_search').val('{{\App\Enums\YesNoFlag::YES}}');

                    if (!nullEmptyUndefinedChecked(vendorId)) {
                        getSwitchPaymentVendorDetail(vendorId);
                    } else {

                        if (invoiceType == '{{\App\Enums\Ap\LApInvoiceType::SWC_ADJ_PRO_CON_SUPP}}') {
                            $("#search_vendor_type").val('{{\App\Enums\Ap\VendorType::EXTERNAL}}').addClass('make-readonly-bg');
                            $("#search_vendor_category").val('{{\App\Enums\Ap\LApVendorCategory::SUPP_CONT}}').parent('div').addClass('make-readonly-bg');
                        } else {
                            $("#search_vendor_type").val('').parent('div').removeClass('make-readonly-bg');
                            $("#search_vendor_category").val('').parent('div').removeClass('make-readonly-bg');
                        }
                        reloadVendorListTable();
                        $("#vendorListModal").modal('show');
                    }
                });

                /*** Add this section end -Pavel: 23-03-22 ***/

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

                $("#ap_reset_vendor_balance_field").on("click", function () {
                    resetField(['#ap_search_vendor_id', '#ap_search_vendor_name', '#ap_search_vendor_category', '#ap_bills_payable', '#ap_prepayments', '#ap_security_deposits', '#ap_advance', '#ap_imprest_cash', '#ap_revolving_cash']);
                });

                function emptyTaxVatPayableDropdown() {
                    $("#party_name_for_tax").html('<option value="">Party Name for Tax Payable</option>');
                    $("#party_name_for_vat").html('<option value="">Party Name for Vat Payable</option>');
                }

                $(document).on('click', '.vendorSelect', function () {
                    /*** Add this if else condition -Pavel: 23-03-22 ***/
                    if (($('#ap_switch_pay_vendor_search').val()) == '{{\App\Enums\YesNoFlag::YES}}') {
                        getSwitchPaymentVendorDetail($(this).data('vendor'));
                    } else {
                        getVendorDetail($(this).data('vendor'));
                    }
                });

                function getVendorDetail(vendor_id) {
                    let vendorParams = $("#ap_party_sub_ledger").find(':selected').data("partyparams");
                    let vendorType = '';
                    let vendorCategory = '';
                    let dlSourceAllowFlag = '';
                    if (!nullEmptyUndefinedChecked(vendorParams)) {
                        let vendorArray = vendorParams.split("#");
                        /*
                         0=> vendor type
                         1=> vendor category
                         2=> GL Subsidiary Type Id
                        */
                        if (!nullEmptyUndefinedChecked(vendorParams[0])) {
                            vendorType = vendorParams[0];
                        }
                        if (!nullEmptyUndefinedChecked(vendorParams[1])) {
                            vendorCategory = vendorParams[1];
                        }
                    }

                    var request = $.ajax({
                        url: APP_URL + '/general-ledger/ajax/get-party-account-details',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            glSubsidiaryId: $("#ap_party_sub_ledger :selected").val(),
                            vendorId: vendor_id,
                        }
                    });

                    request.done(function (d) {
                        if ($.isEmptyObject(d.party_info)) {
                            $("#ap_vendor_id").notify("Vendor id not found", "error");
                            resetField(['#ap_vendor_id', '#ap_vendor_name', '#ap_vendor_category', '#party_name_for_tax', '#party_name_for_vat', '#bl_bills_payable', '#bl_provision_exp', '#bl_security_dep_pay', '#bl_os_advances', '#bl_os_prepayments', '#bl_os_imp_rev']);
                            emptyTaxVatPayableDropdown();
                        } else {
                            $('#ap_vendor_id').val(d.party_info.party_id);
                            $('#ap_vendor_name').val(d.party_info.party_name);
                            $('#ap_vendor_category').val(d.party_info.party_category);
                            $('#ap_account_balance').val(getCommaSeparatedValue(d.party_info.account_balance));
                            $('#ap_authorized_balance').val(getCommaSeparatedValue(d.party_info.authorized_balance));

                            $("#ap_account_balance_type").text(d.party_info.account_balance_type);
                            $("#ap_authorized_balance_type").text(d.party_info.authorized_balance_type);

                        }
                        $("#vendorListModal").modal('hide');
                    });

                    request.fail(function (jqXHR, textStatus) {
                        console.log(jqXHR);
                    });
                }

                /*** Add this section start -Pavel: 23-03-22 ***/
                function getSwitchPaymentVendorDetail(vendor_id) {
                    let vendorType = '{{\App\Enums\Ap\VendorType::EXTERNAL}}';
                    let vendorCategory = '{{\App\Enums\Ap\LApVendorCategory::SUPP_CONT}}';
                    let invoiceType = $("#ap_invoice_type :selected").val();

                    var request = $.ajax({
                        url: APP_URL + '/account-payable/ajax/vendor-details',
                        data: {
                            vendorId: vendor_id,
                            vendorType: vendorType,
                            vendorCategory: vendorCategory,
                            invoiceType: invoiceType
                        }
                    });

                    request.done(function (d) {
                        if ($.isEmptyObject(d.vendor)) {
                            $("#ap_switch_pay_vendor_id").notify("Payment Vendor id not found", "error");
                            resetField(['#ap_switch_pay_vendor_id', '#ap_switch_pay_vendor_name', '#ap_switch_pay_vendor_category']);
                        } else {
                            $('#ap_switch_pay_vendor_id').val(d.vendor.vendor_id);
                            $('#ap_switch_pay_vendor_name').val(d.vendor.vendor_name);
                            $('#ap_switch_pay_vendor_category').val(d.vendor.vendor_category.vendor_category_name);

                        }
                        $("#vendorListModal").modal('hide');
                    });

                    request.fail(function (jqXHR, textStatus) {
                        console.log(jqXHR);
                    });
                }

                /*** Add this section end -Pavel: 23-03-22 ***/

                let vendorTable = $('#vendorSearch').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ordering: false,
                    ajax: {
                        url: APP_URL + '/account-payable/ajax/vendor-search-datalist',
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

            }

            /*
            * Vendor search ends here
            * */

            function enableDisableSaveBtn() {
                let totalDebit = $("#total_debit").val();
                let totalCredit = $("#total_credit").val();
                if (!nullEmptyUndefinedChecked(totalDebit) && !nullEmptyUndefinedChecked(totalCredit) && (totalDebit == totalCredit)) {
                    $("#preview_btn").prop('disabled', false);
                    $("#journalFormSubmitBtn").prop('disabled', false);
                } else {
                    $("#preview_btn").prop('disabled', true);
                    $("#journalFormSubmitBtn").prop('disabled', true);
                }
            }

            $("#preview_btn").on("click", function (e) {
                e.preventDefault();
                $(".p_date").html($("#posting_date_field").val());
                $(".d_date").html($("#document_date_field").val());
                $(".d_no").html($("#document_number").val());
                $(".d_ref").html($("#document_reference").val());
                $(".nara").html($("#narration").val());
                $(".dept").html($("#department :selected").text());
                $(".b_reg").html($("#bill_register :selected").text());
                $(".b_sec").html($("#bill_section :selected").text());

                $("#distribution_content").html("");
                $("#account_table").clone(false).off("click").appendTo("#distribution_content");

                $(/*'#distribution_content > #account_table th:nth-child(7)' +*/
                    '#distribution_content > #account_table th:nth-child(8)' +
                    /*',#distribution_content > #account_table td:nth-child(7)' +*/
                    ',#distribution_content > #account_table td:nth-child(8)').remove();

                $("#previewModal").modal('show');

                /*let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
                let invoiceArray = invoiceParams.split("#");
                let budgetHeadId = $("#b_head_id").val();

                if ((invoiceArray[4] == '0') && nullEmptyUndefinedChecked(budgetHeadId) && (invoiceArray[5] == 'Y') && ($("#ap_without_budget_info").prop("checked") == false)) {
                    $("#b_head_id").focus();
                    $('html, body').animate({scrollTop: ($("#b_head_id").offset().top - 200)}, 2000);
                    $("#b_head_id").notify("Please Add Budget Booking Info.", {position: 'bottom'});

                } else {
                    var formData = $("#invoice_bill_entry_form").serialize();
                    var request = $.ajax({
                        url: APP_URL + "/account-payable/invoice-bill-preview",
                        method: "POST",
                        data: new FormData($("#invoice_bill_entry_form")[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        headers : {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });
                    request.done(function (res) {
                        if (res.response_code == "1") {
                            //$("#preview_content").html("");
                            $("#preview_content").html(res.table_content);
                            $("#previewModal").modal('show');
                        } else {

                            Swal.fire({text: res.response_msg, type: 'error'});
                        }
                    });

                    request.fail(function (jqXHR, textStatus) {
                        Swal.fire({text: textStatus + jqXHR, type: 'error'});
                    });
                }*/
            })

            $("#journal_voucher_form").on("submit", function (e) {
                e.preventDefault();

                swal.fire({
                    text: 'Save Confirm?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value == true) {
                        let request = $.ajax({
                            url: APP_URL + "/general-ledger/journal-voucher",
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
                            if (!nullEmptyUndefinedChecked(res.response_code) && (res.response_code != "99")) {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_msg,
                                    showConfirmButton: true,
                                    //timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    $("#reset_form").trigger('click');
                                    $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/GENERAL_LEDGER/RPT_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_detail"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');
                                    enableDisableSaveBtn();
                                    focusOnMe("#document_number");

                                    //location.reload();
                                    //window.location.href = url;
                                    //window.history.back();
                                });
                            } else {
                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                        });
                    }
                })

            });

            $("#amount_ccy").on('keyup', function () {
                $("#amount_word").val(amountTranslate($(this).val()));
            });

            $('#function_type').change(function (e) {
                let funTypeId = $(this).val();
                if (funTypeId != {{ \App\Enums\Gl\FunctionTypes::BANK_RECEIVE }}) {
                    $("#chalanRow").addClass('hidden');
                    $("#chalanRow").find("label").removeClass('required');
                    $("#d_chalan_no").removeAttr('required', 'required');
                    $("#d_chalan_date").find("input").removeAttr('required', 'required');
                } else {
                    $("#chalanRow").removeClass('hidden');
                    $("#chalanRow").find("label").addClass('required');
                    $("#d_chalan_no").attr('required', 'required');
                    $("#d_chalan_date").find("input").attr('required', 'required');
                }
                listCashBankAcc(funTypeId);

                $("#bill_section").html("");
                $("#bill_register").select2("destroy");
                $("#bill_register").html("");
                $("#bill_register").select2();
                getBillSectionOnFunction(funTypeId, "#bill_section");
            });

            getBillSectionOnFunction($('#function_type').val(), "#bill_section");

            listBillRegister();
            //listCashBankAcc($("#function_type :selected").val());
            //enable_disable_chalan();

            $("#reset_form").on('click', function () {
                resetTablesDynamicRow('#account_table');
                //resetHeaderField();
                removeAllAttachments();
                resetPayableReceivableFields();
                resetField(['#narration', '#account_id', '#account_type', '#account_balance', '#authorized_balance', '#account_name', '#currency', '#exchange_rate', '#total_debit', '#total_credit']);
            })

            $("#th_fiscal_year").on('change', function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#period").html(periods);
                //setPeriodCurrentDate();
                $("#period").trigger('change');
            }

            customerInfoList();
            vendorInfoList();

            $("#ap_vendor_id").on('keyup', function () {
                $("#ap_account_balance_type").text('');
                $("#ap_authorized_balance_type").text('');
                resetField(['#ap_vendor_name', '#ap_vendor_category', '#ap_account_balance', '#ap_authorized_balance']);
            });

            $("#ar_customer_id").on('keyup', function () {
                $("#ar_account_balance_type").text('');
                $("#ar_authorized_balance_type").text('');
                resetField(['#ar_customer_name', '#ar_customer_category', '#ar_account_balance', '#ar_authorized_balance']);
            });

        });
    </script>
@endsection

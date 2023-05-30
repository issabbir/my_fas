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
    <div class="card">
        <div class="card-body">
            @include("gl.cash-payment.form")
        </div>
    </div>
    @include("gl.common_coalist_modal")
    @include('ar.ar-common.common_customer_list_modal')
    @include('ap.ap-common.common_vendor_list_modal')
    {{--<section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                --}}{{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}{{--

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="accountListModal" tabindex="-1" role="dialog"
                         aria-labelledby="accountListModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="accountListModalLabel">Account Search</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="#" id="acc_search_form">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class=" form-group row">
                                                    <label for="acc_type" class="col-md-5 col-form-label required">Account
                                                        Type</label>
                                                    <select class="form-control form-control-sm col-md-7" name="acc_type" id="acc_type"
                                                            required>
                                                        <option value="">Select a type</option>
                                                        @foreach($accountType as $type)
                                                            <option
                                                                value="{{$type->gl_type_id}}">{{$type->gl_type_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="acc_name_code" id="acc_name_code"
                                                       class="form-control form-control-sm" placeholder="Look for Account Name or Code">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success acc_search"><i class="bx bx-search"></i>Search</button>
                                                <button type="button" class="btn btn-dark acc_reset" id="acc_modal_reset"><i class="bx bx-reset"></i>Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table id="account_list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
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
                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i
                                            class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
@endsection

@section('footer-script')
    <script type="text/javascript">
        var resetCreditAccountField;
        var resetDebitAccountField;
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;
        var enableDisableSaveBtn;
        var resetPayableReceivableFields;

        $(document).ready(function () {
            //datePicker("#c_cheque_date");
            /*
            * Master start
            * */
            /* Start calender logic*/
            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let chequeCalendarClickCounter = 0;

            $("#period").on('change', function () {
                $("#document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#c_cheque_date >input").val("");
                if (chequeCalendarClickCounter > 0) {
                    $("#c_cheque_date").datetimepicker('destroy');
                    chequeCalendarClickCounter = 0;
                }

                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                setPeriodCurrentDate()
            });

            /********Added on: 06/06/2022, sujon**********/
            function setPeriodCurrentDate() {
                $("#posting_date_field").val($("#period :selected").data("currentdate"));
                $("#document_date_field").val($("#period :selected").data("currentdate"));
            }

            //setPeriodCurrentDate()
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
                if (!nullEmptyUndefinedChecked(postingDate)) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                    } else {
                        newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                    }

                    $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            $("#c_cheque_date").on('click', function () {
                chequeCalendarClickCounter++;
                $("#cheque_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                datePickerOnPeriod(this, minDate, maxDate);
            });

            /* End calender logic*/
            $("#acc_modal_reset").on('click', function () {
                $("#acc_type").val('');
                $("#acc_name_code").val('');
                accountTable.draw();
            });

            function listBillRegister() {
                $('#bill_section').change(function (e) {
                    $("#bill_register").select2("destroy");
                    $("#bill_register").html("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#bill_register', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            /********Added on: 06/06/2022, sujon**********/
            function setBillSection() {
                $("#bill_register").change(function (e) {
                    $bill_sec_id = $("#bill_register :selected").data('secid');
                    $bill_sec_name = $("#bill_register :selected").data('secname');
                    if (!nullEmptyUndefinedChecked($bill_sec_id)) {
                        $("#bill_section").html("<option value='" + $bill_sec_id + "'>" + $bill_sec_name + "</option>")
                    } else {
                        $("#bill_section").html("<option value=''></option>")
                    }
                });
            }

            //setBillSection();
            /********End**********/

            $("#bill_register").on('select2:select', function (e) {
                //console.log('hello')
                setCreditBankAccount($(this).val(), $("#bill_section :selected").val());
            });

            function setCreditBankAccount(regId, secId) {
                let request = $.ajax({
                    url: "{{route('ajax.get-current-bank-account')}}",
                    data: {regId, secId},
                    dataType: "JSON",
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token()}}'
                    }
                });

                request.done(function (res) {
                    if (res.predefined == true) {
                        $("#c_bank_account").attr('data-gl-acc-id', res.selected.gl_acc_id);
                        selectDebitCreditBankAcc('#c_bank_account', APP_URL + '/general-ledger/ajax/fun-type-by-credit-bank-acc/' + $("#function_type :selected").val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields);
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

            /*
            * Master end
            * */

            $('#function_type').change(function (e) {
                let funTypeId = $(this).val();

                if (funTypeId != {{ \App\Enums\Gl\FunctionTypes::BANK_PAYMENT }}) {
                    $("#chequeRow").addClass('hidden');
                    $("#chequeRow").find("label").removeClass('required');
                    $("#c_cheque_no").removeAttr('required', 'required');
                    $("#c_cheque_date").find("input").removeAttr('required', 'required');
                } else {
                    $("#chequeRow").removeClass('hidden');
                    $("#chequeRow").find("label").addClass('required');
                    $("#c_cheque_no").attr('required', 'required');
                    $("#c_cheque_date").find("input").attr('required', 'required');
                }

                listCashBankAcc(funTypeId);
            });

            /*
            * Credit starts
            * */
            function listCashBankAcc(funTypeId) {
                resetField(['#c_account_balance', '#c_authorized_balance', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_amount_word']);

                selectDebitCreditBankAcc('#c_bank_account', APP_URL + '/general-ledger/ajax/fun-type-by-credit-bank-acc/' + funTypeId, APP_URL + '/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields);
            }

            /*
             * Credit ends
             * */
            function populateCreditBankInfoFields(that, data) {
                let currency = (data.currency_code);
                $(that).parent().parent().parent().find('#c_account_balance').val(getCommaSeparatedValue(data.account_balance));
                $(that).parent().parent().parent().find('#c_authorized_balance').val(getCommaSeparatedValue(data.authorize_balance));

                $(that).parent().parent().parent().find('#c_account_balance_type').text(data.account_balance_type);
                $(that).parent().parent().parent().find('#c_authorized_balance_type').text(data.authorize_balance_type);

                $(that).parent().parent().parent().find('#c_currency').val(data.currency_code);
                $(that).parent().parent().parent().find('#c_exchange_rate').val(data.exchange_rate);
                $('#c_currency').val(data.currency_code);
                $('#c_exchange_rate').val(data.exchange_rate);

                //$('#c_amount_ccy').val('');
                $('#c_amount_lcy').val(parseFloat($("#c_exchange_rate").val()) * totalLcy());
                enableDisableSaveBtn();
                //$('#c_amount_word').val('');
            }

            function enable_disable_cheque() {
                $("#withoutCheque").on('click', function () {
                    if ($(this).prop("checked") == true) {
                        $("#c_cheque_no").val('').prop('readonly', true);
                        $("#c_cheque_date_field").val('').addClass("make-readonly-bg");
                        $("#c_cheque_date").addClass("make-readonly-bg");

                        $("#chequeRow").find("label").removeClass('required');
                        $("#c_cheque_no").find("input").removeAttr('required', 'required');
                        $("#c_cheque_date").find("input").removeAttr('required', 'required');

                    } else if ($(this).prop("checked") == false) {
                        $("#c_cheque_no").prop('readonly', false);
                        $("#c_cheque_date_field").removeClass("make-readonly-bg");
                        $("#c_cheque_date").removeClass("make-readonly-bg");

                        $("#chequeRow").find("label").addClass('required');
                        $("#c_cheque_no").find("input").attr('required', 'required');
                        $("#c_cheque_date").find("input").attr('required', 'required');
                    }
                });

                if ($("#withoutCheque").prop("checked") == true) {
                    $("#c_cheque_no").val('').prop('readonly', true);
                    $("#c_cheque_date_field").val('').addClass("make-readonly-bg");
                    $("#c_cheque_date").addClass("make-readonly-bg");

                    $("#chequeRow").find("label").removeClass('required');
                    $("#c_cheque_no").find("input").removeAttr('required', 'required');
                    $("#c_cheque_date").find("input").removeAttr('required', 'required');

                } else if ($("#withoutCheque").prop("checked") == false) {
                    $("#c_cheque_no").prop('readonly', false);
                    $("#c_cheque_date_field").removeClass("make-readonly-bg");
                    $("#c_cheque_date").removeClass("make-readonly-bg");

                    $("#chequeRow").find("label").addClass('required');
                    $("#c_cheque_no").find("input").attr('required', 'required');
                    $("#c_cheque_date").find("input").attr('required', 'required');
                }
            }

            resetCreditAccountField = function () {
                //$("#c_account_id").val('');
                //$("#c_account_name").val('');
                //$("#c_account_type").val('');
                /*$("#c_account_balance").val('');
                $("#c_authorized_balance").val('');
                //$("#c_budget_head").val('');
                $("#c_currency").val('');
                $("#c_amount_ccy").val('');
                $("#c_amount_lcy").val('');
                $("#c_exchange_rate").val('');
                $("#c_narration").val('');*/
                //$("#c_bank_account").select2().val('').empty("");
                resetField(['#c_account_balance', '#c_authorized_balance', '#c_currency', '#c_amount_lcy', '#c_exchange_rate']);
            }
            resetDebitAccountField = function () {
                $("#d_account_balance_type").text('');
                $("#d_authorized_balance_type").text('');
                resetField(['#d_account_name', '#d_account_type', '#d_account_balance', '#d_authorized_balance', '#d_budget_head', '#d_currency', '#d_amount_ccy', '#d_exchange_rate', '#d_amount_lcy', '#d_exchange_rate', '#d_amount_word', '#department_cost_center']);
            }

            $("#d_amount_ccy").on("keyup", function () {
                let d_amount_ccy_keyup = parseFloat($('#d_amount_ccy').val());
                if (!is_negative(d_amount_ccy_keyup) && d_amount_ccy_keyup != 0) {
                    let d_exchange_rate_get = parseFloat($("#d_exchange_rate").val());
                    //$('#d_amount_ccy').val(d_amount_ccy_keyup);

                    if (d_amount_ccy_keyup && d_exchange_rate_get) {
                        let debit_credit_lcy = (d_amount_ccy_keyup * d_exchange_rate_get);

                        $('#d_amount_lcy').val(debit_credit_lcy);
                        //alert(d_amount_ccy_keyup * d_exchange_rate_get);
                    } else {
                        $('#d_amount_lcy').val('0');
                    }
                } else {
                    $('#d_amount_ccy').val('0');
                    $('#d_amount_lcy').val('0');
                }
            });

            $("#c_amount_ccy").on("keyup", function () {
                let c_amount_ccy_keyup = parseFloat($(this).val());
                if (!is_negative(c_amount_ccy_keyup) && c_amount_ccy_keyup != 0) {
                    let c_exchange_rate_get = parseFloat($("#c_exchange_rate").val());
                    //$('#c_amount_ccy').val(c_amount_ccy_keyup);

                    if (c_amount_ccy_keyup && c_exchange_rate_get) {
                        let lcy = (c_amount_ccy_keyup * c_exchange_rate_get);
                        $('#c_amount_lcy').val(lcy);
                    } else {
                        $('#c_amount_lcy').val('0');
                    }
                } else {
                    $('#c_amount_ccy').val('0');
                    $('#c_amount_lcy').val('0');
                }
                enableDisableSaveBtn();
            });

            let accountTable = $('#account_list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                /*bDestroy : true,
                pageLength: 20,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
                ajax: {
                    url: APP_URL + '/general-ledger/debit-acc-datalist',
                    'type': 'POST',
                    'headers': {
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

            /*$('.showAccountListModal').on('click', function () {
                $("#accountListModal").modal('show');
                accountTable.draw();
            });*/

            $("#searchAccount").on("click", function () {
                $accId = $("#d_account_id").val();
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

            //src = 1 from modal, src = 2 from search
            getAccountDetail = function (d_accId) {
                var request = $.ajax({
                    url: APP_URL + '/general-ledger/ajax/get-account-details',
                    method: 'POST',
                    data: {accId: d_accId},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                request.done(function (d) {
                    resetField(['#d_account_name', '#d_account_type', '#d_account_balance', '#d_authorized_balance', '#d_budget_head', '#d_currency', '#d_exchange_rate']);

                    if ($.isEmptyObject(d.account_info)) {
                        $("#d_account_id").notify("Account id not found", "error");
                    } else {
                        $("#d_account_id").val(d.account_info.gl_acc_id);
                        $("#d_account_name").val(d.account_info.gl_acc_name);
                        $("#d_account_type").val(d.account_info.gl_type_name);
                        $("#d_account_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                        $("#d_authorized_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                        $("#d_budget_head").val(d.account_info.budget_head_line_name);
                        $("#d_currency").val(d.account_info.currency_code);
                        $("#d_exchange_rate").val(d.account_info.exchange_rate);

                        $("#d_account_balance_type").text(d.account_info.account_balance_type);
                        $("#d_authorized_balance_type").text(d.account_info.authorize_balance_type);

                        if (nullEmptyUndefinedChecked(d.account_info.cost_center_dept_name)) {
                            $("#department_cost_center").html('');
                        } else {
                            $("#department_cost_center").html('<option value="' + d.account_info.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
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
                                    $("#ap_vendor_id").val(d.party_info.party_id).addClass('make-readonly-bg').attr("tabindex","-1");
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

                        openCloseDebitRateLcy(d.account_info.currency_code);

                        $("#accountListModal").modal('hide');

                        /*$("#d_amount_ccy").focus();
                        $('html, body').animate({scrollTop: ($("#d_amount_ccy").offset().top - 400)}, 2000);*/
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

            function openCloseDebitRateLcy(currency) {

                if (currency == 'USD') {
                    $("#d_exchange_rate").removeAttr('readonly');
                    $("#d_amount_lcy").removeAttr('readonly');
                } else {
                    $("#d_exchange_rate").attr('readonly', 'readonly');
                    $("#d_amount_lcy").attr('readonly', 'readonly');
                }
            }

            function getTransactionFieldsData() {
                let account_id = $('#d_account_id').val();
                //let debitCredit = $("#dr_cr :selected").val();
                let currency = $("#d_currency").val();
                let exchangeRate = $('#d_exchange_rate').val();
                let accountType = $('#d_account_type').val();
                let budgetHead = $('#d_budget_head').val();
                let accountBalance = $('#d_account_balance').val();
                let module_id = $("#module_id").val();
                let partySubLedger = "";
                let amountCcy = $("#d_amount_ccy").val();
                let accountName = $("#d_account_name").val();
                let partyId = "";
                let partyName = "";
                let amountLcy = $("#d_amount_lcy").val();

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

                return {
                    account_id
                    , currency
                    , exchangeRate
                    , accountType
                    , budgetHead
                    , accountBalance
                    , module_id
                    , partySubLedger
                    , amountCcy
                    , accountName
                    , partyId
                    , partyName
                    , amountLcy
                };
            }

            addLineRow = function (selector) {
                if (fieldsAreSet(['#d_amount_ccy', '#d_account_id', '#d_account_name', '#d_amount_lcy'])) {
                    if ($(selector).attr('data-type') == 'A') {
                        let transaction = getTransactionFieldsData();
                        let count = $("#d_account_table >tbody").children("tr").length;

                        let html = '<tr>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" name="line[' + count + '][d_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + transaction.account_id + '" readonly/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][d_currency]" id="d_currency' + count + '" value="' + transaction.currency + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][d_exchange_rate]" id="d_exchange_rate' + count + '" value="' + transaction.exchangeRate + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][d_acc_type]" id="d_acc_type' + count + '" value="' + transaction.accountType + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][d_acc_balance]" id="d_acc_balance' + count + '" value="' + transaction.accountBalance + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][module_id]" id="module_id' + count + '" value="' + transaction.module_id + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][party_sub_ledger]" id="party_sub_ledger' + count + '" value="' + transaction.partySubLedger + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][d_amount_ccy]" id="amount_ccy' + count + '" value="' + transaction.amountCcy + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][action_type]" id="action_type' + count + '" value="A" />' +
                            '</td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][d_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + transaction.accountName + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][party_id]" id="party_id' + count + '" class="form-control form-control-sm" value="' + transaction.partyId + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px"><input tabindex="-1" name="line[' + count + '][party_name]" id="party_name' + count + '" class="form-control form-control-sm" value="' + transaction.partyName + '" readonly/></td></td>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" type="text" class="form-control form-control-sm text-right-align ccy" name="line[' + count + '][d_amount_ccy]" id="ccy' + count + '" value="' + transaction.amountCcy + '" readonly>' +
                            '</td>\n' +
                            '<td style="padding: 2px;">' +
                            '<input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][d_amount_lcy]" id="lcy' + count + '" value="' + transaction.amountLcy + '" readonly>' +
                            '</td>\n' +
                            '      <td style="padding: 2px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editAccountBtn" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="d_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#d_account_table >tbody").append(html);
                    } else {
                        var lineToUpdate = $(selector).attr('data-line');
                        updateLineValue(lineToUpdate);
                    }

                    /*if(totalLcy() != parseFloat($("#c_amount_lcy").val())){
                        $("#d_account_id").val('').focus();
                        $('html, body').animate({scrollTop: ($("#d_account_id").offset().top-400)}, 2000);
                    }else{
                        $("#paymentFormSubmitBtn").focus();
                        $('html, body').animate({scrollTop: ($("#paymentFormSubmitBtn").offset().top-400)}, 2000);
                    }*/

                    resetField(['#d_account_name', '#d_account_id', '#d_account_type', '#d_account_balance', '#d_authorized_balance', '#d_budget_head', '#d_currency', '#d_amount_ccy', '#d_exchange_rate', '#d_amount_lcy', '#d_amount_word']);

                    $("#d_account_balance_type").text('');
                    $("#d_authorized_balance_type").text('');

                    $("#ap_account_balance_type").text('');
                    $("#ap_authorized_balance_type").text('');

                    $("#ar_account_balance_type").text('');
                    $("#ar_authorized_balance_type").text('');

                    setTotalLcy();
                    //setTotalLcy();
                    enableDisableSaveBtn();
                    openCloseDebitRateLcy('');
                    resetPayableReceivableFields();

                } /*else {
                $(selector).notify("Missing input.");
            }*/
            }
            removeLineRow = function (select, lineRow) {
                $("#action_type" + lineRow).val('D');
                $(select).closest("tr").hide();
                setTotalLcy();
                enableDisableSaveBtn();
                openCloseDebitRateLcy('');
            }
            editAccount = function (selector, line) {
                $("#d_remove_btn" + line).hide();
                $("#module_id").val($("#module_id" + line).val());
                $("#d_account_id").val($("#account_code" + line).val());
                $("#searchAccount").trigger('click');

                /*$("#d_account_name").val($("#account_name" + line).val());
                $("#d_account_type").val($("#account_type" + line).val());
                $("#d_account_balance").val($("#account_balance" + line).val());
                $("#d_authorized_balance").val($("#authorized_balance" + line).val());
                $("#d_budget_head").val($("#budget_head" + line).val());
                $("#d_currency").val($("#currency" + line).val());
                $("#d_exchange_rate").val($("#exchange_rate" + line).val());
                */

                $("#d_amount_ccy").val($("#ccy" + line).val());
                $("#d_amount_lcy").val($("#lcy" + line).val());

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
                $(".editAccountBtn").addClass('d-none');
                //removeLineRow(selector,line);
                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-edit'></i>UPDATE");
                $(select).attr('data-type', 'U');
                $(select).attr('data-line', line);
                $("#paymentFormSubmitBtn").prop('disabled', true);
                $("#d_amount_word").val(amountTranslate($("#ccy" + line).val()));
            }

            function updateLineValue(line) {
                let transaction = getTransactionFieldsData();

                $("#account_code" + line).val(transaction.account_id);
                $("#dr_cr" + line).val(transaction.debitCredit)
                $("#currency" + line).val(transaction.currency);
                $("#exchange_rate" + line).val(transaction.exchangeRate);
                $("#account_type" + line).val(transaction.accountType);
                $("#budget_head" + line).val(transaction.budgetHead);
                $("#account_balance" + line).val(transaction.accountBalance);
                $("#module_id" + line).val(transaction.module_id);
                $("#party_sub_ledger" + line).val(transaction.partySubLedger);
                $("#ccy" + line).val(transaction.amountCcy);
                $("#account_name" + line).val(transaction.accountName);
                $("#party_id" + line).val(transaction.partyId);
                $("#party_name" + line).val(transaction.partyName);
                $("#lcy" + line).val(transaction.amountLcy);

                //$("#narration" + line).val($("#d_narration").val());
                $(".editAccountBtn").removeClass('d-none');

                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-plus-circle'></i>ADD");
                $(select).attr('data-type', 'A');
                $(select).attr('data-line', '');
                $("#paymentFormSubmitBtn").prop('disabled', false);
                enableDisableSaveBtn();

                $("#d_remove_btn" + line).show();
            }

            enableDisableSaveBtn = function () {
                let totalLcy1 = totalLcy();
                let currencyAmount = $("#c_amount_lcy").val();
                if (!nullEmptyUndefinedChecked(totalLcy1) && !nullEmptyUndefinedChecked(currencyAmount) && (totalLcy1 == currencyAmount)) {
                    $("#paymentFormSubmitBtn").prop('disabled', false);
                } else {
                    $("#paymentFormSubmitBtn").prop('disabled', true);
                }
            }

            function setTotalLcy() {
                let total = totalLcy();
                $("#total_lcy").val(total);
                $("#c_amount_ccy").val(total);
                $("#c_amount_word").val(amountTranslate(total));

                if (!nullEmptyUndefinedChecked($("#c_bank_account :selected").val())) {
                    $('#c_amount_lcy').val(parseFloat($("#c_exchange_rate").val()) * totalLcy());
                }

            }

            function totalLcy() {
                let debit = $("#d_account_table >tbody >tr").find(".lcy");
                let totalLcy = 0;
                debit.each(function () {
                    if ($(this).is(":hidden") == false) {
                        if ($(this).val() != "" && $(this).val() != "0") {
                            totalLcy += parseFloat($(this).val());
                        }
                    }
                });

                return totalLcy;
            }

            $("#cash_payment_form").on("submit", function (e) {
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
                            url: APP_URL + "/general-ledger/cash-payment",
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
                                    showConfirmButton: true,
                                    //timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    $("#reset_form").trigger('click');
                                    listCashBankAcc($("#function_type :selected").val());
                                    $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/GENERAL_LEDGER/RPT_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_detail"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');
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

            $("#c_amount_ccy").on('keyup', function () {
                $("#c_amount_word").val(amountTranslate($(this).val()));
            });

            $("#d_amount_ccy").on('keyup', function () {
                $("#d_amount_word").val(amountTranslate($(this).val()));
            });

            $('#function_type').change(function (e) {
                let funTypeId = $(this).val();

                $("#bill_section").html("");
                $("#bill_register").select2("destroy");
                $("#bill_register").html("");
                $("#bill_register").select2();
                getBillSectionOnFunction(funTypeId, "#bill_section");
            });

            listBillRegister();
            $("#bill_section").trigger('change');

            listCashBankAcc($("#function_type :selected").val());
            enable_disable_cheque();

            $("#reset_form").on('click', function () {
                resetTablesDynamicRow();
                resetCreditAccountField();
                removeAllAttachments();
                enableDisableSaveBtn()
                resetPayableReceivableFields();
                resetField(['#narration', '#c_amount_ccy', '#total_lcy']);
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
            customerInfoList();
            vendorInfoList();

            $("#ap_vendor_id").on('keyup',function () {
                $("#ap_account_balance_type").text('');
                $("#ap_authorized_balance_type").text('');
                resetField(['#ap_vendor_name','#ap_vendor_category','#ap_account_balance','#ap_authorized_balance']);
            });

            $("#ar_customer_id").on('keyup',function () {
                $("#ar_account_balance_type").text('');
                $("#ar_authorized_balance_type").text('');
                resetField(['#ar_customer_name','#ar_customer_category','#ar_account_balance','#ar_authorized_balance']);
            });
        });
    </script>
@endsection

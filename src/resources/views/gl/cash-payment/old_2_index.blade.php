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
                $(that).parent().parent().parent().find('#c_account_balance').val(data.account_balance);
                $(that).parent().parent().parent().find('#c_authorized_balance').val(data.authorize_balance);
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
                    url: APP_URL + '/general-ledger/ajax/bank-account-details',
                    method: 'POST',
                    data: {accId: d_accId},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                request.done(function (d) {
                    resetField(['#d_account_name', '#d_account_type', '#d_account_balance', '#d_authorized_balance', '#d_budget_head', '#d_currency', '#d_amount_ccy', '#d_exchange_rate', '#d_amount_lcy', '#d_amount_word']);

                    if ($.isEmptyObject(d)) {
                        $("#d_account_id").notify("Account id not found", "error");
                    } else {
                        $("#d_account_id").val(d.gl_acc_id);
                        $("#d_account_name").val(d.gl_acc_name);
                        $("#d_account_type").val(d.gl_type_name);
                        $("#d_account_balance").val(d.account_balance);
                        $("#d_authorized_balance").val(d.authorize_balance);
                        $("#d_budget_head").val(d.budget_head_line_name);
                        $("#d_currency").val(d.currency_code);
                        $("#d_exchange_rate").val(d.exchange_rate);
                        if (nullEmptyUndefinedChecked(d.cost_center_dept_name)) {
                            $("#department_cost_center").html('');
                        } else {
                            $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                        }

                        openCloseDebitRateLcy(d.currency_code);

                        $("#accountListModal").modal('hide');

                        $("#d_amount_ccy").focus();
                        $('html, body').animate({scrollTop: ($("#d_amount_ccy").offset().top - 400)}, 2000);
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

            addLineRow = function (selector) {
                if (fieldsAreSet(['#d_amount_ccy', '#d_account_id', '#d_account_name', '#d_amount_lcy'])) {
                    if ($(selector).data('type') == 'A') {
                        let count = $("#d_account_table >tbody").children("tr").length;

                        let html = '<tr>\n' +
                            '      <td style="padding: 4px;"><input name="line[' + count + '][d_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + $('#d_account_id').val() + '" readonly/></td>\n' +
                            '      <td style="padding: 4px"><input name="line[' + count + '][d_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + $('#d_account_name').val() + '" readonly/></td></td>\n' +
                            '      <td style="padding: 4px;"><span>Debit</span><input type="hidden" name="line[' + count + '][d_currency]" value="' + $("#d_currency").val() + '"/>' +
                            '<input type="hidden" name="line[' + count + '][d_exchange_rate]" id="exchange_rate' + count + '" value="' + $('#d_exchange_rate').val() + '"/>' +
                            '<input type="hidden" name="line[' + count + '][d_acc_type]" id="account_type' + count + '" value="' + $('#d_account_type').val() + '"/>' +
                            '<input type="hidden" name="line[' + count + '][d_budget_head]" id="budget_head' + count + '" value="' + $('#d_budget_head').val() + '"/>' +
                            '<input type="hidden" name="line[' + count + '][d_acc_balance]" id="account_balance' + count + '" value="' + $('#d_account_balance').val() + '"/>' +
                            '<input type="hidden" name="line[' + count + '][d_authorized_balance]" id="authorized_balance' + count + '" value="' + $('#d_authorized_balance').val() + '"/>' +
                            /*'<input type="hidden" name="line[' + count + '][d_narration]" id="narration' + count + '" value="' + $('#d_narration').val() + '"/>' +*/
                            '<input type="hidden" name="line[' + count + '][d_action_type]" id="action_type' + count + '" value="A" />' +
                            '</td>\n' +
                            '<td style="padding: 4px;"><input type="text" class="form-control form-control-sm text-right-align" name="line[' + count + '][d_amount_ccy]" id="ccy' + count + '" value="' + $('#d_amount_ccy').val() + '" readonly></td>\n' +
                            '<td style="padding: 4px;"><input type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][d_amount_lcy]" id="lcy' + count + '" value="' + $('#d_amount_lcy').val() + '" readonly></td>\n' +
                            '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="d_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#d_account_table >tbody").append(html);
                    } else {
                        var lineToUpdate = $(selector).data('line');
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
                    setTotalLcy();
                    //setTotalLcy();
                    enableDisableSaveBtn();
                    openCloseDebitRateLcy('');
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

                $("#d_account_id").val($("#account_code" + line).val());
                $("#d_account_name").val($("#account_name" + line).val());
                $("#d_account_type").val($("#account_type" + line).val());
                $("#d_account_balance").val($("#account_balance" + line).val());
                $("#d_authorized_balance").val($("#authorized_balance" + line).val());
                $("#d_budget_head").val($("#budget_head" + line).val());
                $("#d_currency").val($("#currency" + line).val());
                $("#d_amount_ccy").val($("#ccy" + line).val());
                $("#d_amount_lcy").val($("#lcy" + line).val());
                $("#d_exchange_rate").val($("#exchange_rate" + line).val());
                //$("#d_narration").val($("#narration" + line).val());

                //removeLineRow(selector,line);
                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-edit'></i>UPDATE");
                $(select).data('type', 'U');
                $(select).data('line', line);
                $("#paymentFormSubmitBtn").prop('disabled', true);
                $("#d_amount_word").val(amountTranslate($("#ccy" + line).val()));
            }

            function updateLineValue(line) {
                $("#account_code" + line).val($("#d_account_id").val());
                $("#account_name" + line).val($("#d_account_name").val());
                $("#account_type" + line).val($("#d_account_type").val());
                $("#account_balance" + line).val($("#d_account_balance").val());
                $("#authorized_balance" + line).val($("#d_authorized_balance").val());
                $("#budget_head" + line).val($("#d_budget_head").val());
                $("#currency" + line).val($("#d_currency").val());
                $("#ccy" + line).val($("#d_amount_ccy").val());
                $("#lcy" + line).val($("#d_amount_lcy").val());
                $("#exchange_rate" + line).val($("#d_exchange_rate").val());
                //$("#narration" + line).val($("#d_narration").val());

                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-plus-circle'></i>ADD");
                $(select).data('type', 'A');
                $(select).data('line', '');
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
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    $("#reset_form").trigger('click');
                                    listCashBankAcc($("#function_type :selected").val());

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
        });
    </script>
@endsection

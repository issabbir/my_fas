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
            @include("gl.cash-receive.form")
        </div>
    </div>
    @include("gl.common_coalist_modal")
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

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let chalanCalendarClickCounter = 0;
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

                $("#d_chalan_date >input").val("");
                if (chalanCalendarClickCounter > 0) {
                    $("#d_chalan_date").datetimepicker('destroy');
                    chalanCalendarClickCounter = 0;
                }

                setPeriodCurrentDate();
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
                $("#ap_payment_due_date_field").val("");
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

            $("#d_chalan_date").on('click', function () {
                chalanCalendarClickCounter++;
                $("#d_chalan_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            function listBillRegister() {
                $('#bill_section').change(function (e) {
                    $("#bill_register").val("");
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
                setDebitBankAccount($(this).val(), $("#bill_section :selected").val());
            });

            function setDebitBankAccount(regId, secId) {
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
                        $("#d_bank_account").attr('data-gl-acc-id', res.selected.gl_acc_id);
                        selectDebitCreditBankAcc('#d_bank_account', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#function_type :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

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


            function listCashBankAcc(funTypeId) {
                //resetDebitAccountField();
                resetField(['#d_bank_account', '#d_account_balance', '#d_authorized_balance', '#d_currency', '#d_amount_ccy', '#d_exchange_rate', '#d_amount_lcy', '#d_chalan_no', '#d_chalan_date_field', '#d_amount_word']);

                selectDebitCreditBankAcc('#d_bank_account', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + funTypeId, APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
            }

            function populateDebitBankInfoFields(that, data) {
                let currency = (data.currency_code);
                $(that).parent().parent().parent().find('#d_account_balance').val(data.account_balance);
                $(that).parent().parent().parent().find('#d_authorized_balance').val(data.authorize_balance);
                $(that).parent().parent().parent().find('#d_currency').val(data.currency_code);
                $(that).parent().parent().parent().find('#d_exchange_rate').val(data.exchange_rate);
                $('#d_currency').val(data.currency_code);
                $('#d_exchange_rate').val(data.exchange_rate);

                //$('#d_amount_ccy').val('');
                $('#d_amount_lcy').val(parseFloat($("#d_exchange_rate").val()) * totalLcy());
                enableDisableSaveBtn();
                //$('#d_amount_word').val('');
            }

            function enable_disable_chalan() {
                $("#withoutChalan").on('click', function () {
                    if ($(this).prop("checked") == true) {
                        $("#d_chalan_no").val('').prop('readonly', true);
                        $("#d_chalan_date_field").val('').addClass("make-readonly-bg");
                        $("#d_chalan_date").addClass("make-readonly-bg");

                        $("#chalanRow").find("label").removeClass('required');
                        $("#d_chalan_no").find("input").removeAttr('required', 'required');
                        $("#d_chalan_date").find("input").removeAttr('required', 'required');
                    } else if ($(this).prop("checked") == false) {
                        $("#d_chalan_no").prop('readonly', false);
                        $("#d_chalan_date_field").removeClass("make-readonly-bg");
                        $("#d_chalan_date").removeClass("make-readonly-bg");

                        $("#chalanRow").find("label").addClass('required');
                        $("#d_chalan_no").find("input").attr('required', 'required');
                        $("#d_chalan_date").find("input").attr('required', 'required');
                    }
                });

                if ($("#withoutChalan").prop("checked") == true) {
                    $("#d_chalan_no").val('').prop('readonly', true);
                    $("#d_chalan_date_field").val('').addClass("make-readonly-bg");
                    $("#d_chalan_date").addClass("make-readonly-bg");

                    $("#chalanRow").find("label").removeClass('required');
                    $("#d_chalan_no").removeAttr('required');
                    $("#d_chalan_date").find("input").removeAttr('required');

                } else if ($("#withoutChalan").prop("checked") == false) {
                    $("#d_chalan_no").prop('readonly', false);
                    $("#d_chalan_date_field").removeClass("make-readonly-bg");
                    $("#d_chalan_date").removeClass("make-readonly-bg");

                    $("#chalanRow").find("label").addClass('required');
                    $("#d_chalan_no").attr('required', 'required');
                    $("#d_chalan_date").find("input").attr('required', 'required');
                }
            }

            $("#acc_modal_reset").on('click', function () {
                $("#acc_type").val('');
                $("#acc_name_code").val('');
                accountTable.draw();
            });

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
                enableDisableSaveBtn();
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

            });

            let accountTable = $('#account_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                /*bDestroy : true,
                pageLength: 20,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
                ajax: {
                    url: APP_URL + '/general-ledger/credit-acc-datalist',
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

            $("#searchAccount").on("click", function () {
                $accId = $("#c_account_id").val();
                let costCenterDpt = $('#department :selected').val(); //Add costCenterDpt Part :PAVEL-11-04-22
                if (!nullEmptyUndefinedChecked(costCenterDpt)) {
                    if (!nullEmptyUndefinedChecked($accId)) {
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
            getAccountDetail = function (c_accId) {
                var request = $.ajax({
                    url: APP_URL + '/general-ledger/ajax/bank-account-details',
                    method: 'POST',
                    data: {accId: c_accId},
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                request.done(function (d) {
                    resetField(['#c_account_name', '#c_account_type', '#c_account_balance', '#c_authorized_balance', '#c_budget_head', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_amount_word']);

                    if ($.isEmptyObject(d)) {
                        $("#c_account_id").notify("Account id not found", "error");
                    } else {
                        $("#c_account_id").val(d.gl_acc_id);
                        $("#c_account_name").val(d.gl_acc_name);
                        $("#c_account_type").val(d.gl_type_name);
                        $("#c_account_balance").val(d.account_balance);
                        $("#c_authorized_balance").val(d.authorize_balance);
                        $("#c_budget_head").val(d.budget_head_line_name);
                        $("#c_currency").val(d.currency_code);
                        $("#c_exchange_rate").val(d.exchange_rate);
                        if (nullEmptyUndefinedChecked(d.cost_center_dept_name)) {
                            $("#department_cost_center").html('');
                        } else {
                            $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                        }

                        openCloseCreditRateLcy(d.currency_code);

                        $("#accountListModal").modal('hide');

                        $("#c_amount_ccy").focus();
                        $('html, body').animate({scrollTop: ($("#c_amount_ccy").offset().top - 400)}, 2000);
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }

            function openCloseCreditRateLcy(currency) {

                if (currency == 'USD') {
                    $("#c_exchange_rate").removeAttr('readonly');
                    $("#c_amount_lcy").removeAttr('readonly');
                } else {
                    $("#c_exchange_rate").attr('readonly', 'readonly');
                    $("#c_amount_lcy").attr('readonly', 'readonly');
                }
            }

            resetDebitAccountField = function () {
                //$("#d_bank_account").select2().trigger('change');
                $("#d_account_balance").val('');
                $("#d_authorized_balance").val('');
                $("#d_currency").val('');
                $("#d_amount_ccy").val('');
                $("#d_exchange_rate").val('');
                $("#d_amount_lcy").val('');
                /*$("#d_chalan_no").val('');
                $("#d_chalan_date_field").val('');
                $("#d_narration").val('');*/
                $("#d_amount_word").val('');
            }


            addLineRow = function (selector) {
                if (fieldsAreSet(['#c_amount_ccy', '#c_account_id', '#c_account_name', '#c_amount_lcy'])) {
                    if ($(selector).data('type') == 'A') {
                        let count = $("#c_account_table >tbody").children("tr").length;

                        let html = '<tr>\n' +
                            '      <td style="padding: 4px;"><input tabindex="-1" name="line[' + count + '][c_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + $('#c_account_id').val() + '" readonly/></td>\n' +
                            '      <td style="padding: 4px"><input tabindex="-1" name="line[' + count + '][c_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + $('#c_account_name').val() + '" readonly/></td></td>\n' +
                            '      <td style="padding: 4px;"><span>Credit</span><input tabindex="-1" type="hidden" name="line[' + count + '][c_currency]" id="currency' + count + '" value="' + $("#c_currency").val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_exchange_rate]" id="exchange_rate' + count + '" value="' + $('#c_exchange_rate').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_acc_type]" id="account_type' + count + '" value="' + $('#c_account_type').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_budget_head]" id="budget_head' + count + '" value="' + $('#c_budget_head').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_acc_balance]" id="account_balance' + count + '" value="' + $('#c_account_balance').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_authorized_balance]" id="authorized_balance' + count + '" value="' + $('#c_authorized_balance').val() + '"/>' +
                            /*'<input tabindex="-1" type="hidden" name="line[' + count + '][c_narration]" id="narration' + count + '" value="' + $('#c_narration').val() + '"/>' +*/
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][c_action_type]" id="action_type' + count + '" value="A" />' +
                            '</td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align" name="line[' + count + '][c_amount_ccy]" id="ccy' + count + '" value="' + $('#c_amount_ccy').val() + '" readonly></td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][c_amount_lcy]" id="lcy' + count + '" value="' + $('#c_amount_lcy').val() + '" readonly></td>\n' +
                            '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="c_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#c_account_table >tbody").append(html);
                    } else {
                        var lineToUpdate = $(selector).data('line');
                        updateLineValue(lineToUpdate);
                    }

                    /* if(totalLcy() != parseFloat($("#d_amount_lcy").val())){
                         $("#c_account_id").val('').focus();
                         $('html, body').animate({scrollTop: ($("#c_account_id").offset().top-400)}, 2000);
                     }else{
                         $("#receiveFormSubmitBtn").focus();
                         $('html, body').animate({scrollTop: ($("#receiveFormSubmitBtn").offset().top-400)}, 2000);
                     }*/


                    resetField(['#c_account_name', '#c_account_id', '#c_account_type', '#c_account_balance', '#c_authorized_balance', '#c_budget_head', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_amount_word']);
                    setTotalLcy();
                    enableDisableSaveBtn();
                    openCloseCreditRateLcy('');
                } /*else {
                $(selector).notify("Missing input.", "error", {position: "left"});
            }*/
            }

            removeLineRow = function (select, lineRow) {
                $("#action_type" + lineRow).val('D');
                $(select).closest("tr").hide();
                setTotalLcy();
                enableDisableSaveBtn();
                openCloseCreditRateLcy('');
            }

            resetCreditAccountField = function () {
                resetField(['#c_account_name', '#c_account_type', '#c_account_balance', '#c_authorized_balance', '#c_budget_head', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_amount_word', '#department_cost_center']);
            }

            editAccount = function (selector, line) {
                $("#c_remove_btn" + line).hide();

                $("#c_account_id").val($("#account_code" + line).val());
                $("#c_account_name").val($("#account_name" + line).val());
                $("#c_account_type").val($("#account_type" + line).val());
                $("#c_account_balance").val($("#account_balance" + line).val());
                $("#c_authorized_balance").val($("#authorized_balance" + line).val());
                $("#c_budget_head").val($("#budget_head" + line).val());
                $("#c_currency").val($("#currency" + line).val());
                $("#c_amount_ccy").val($("#ccy" + line).val());
                $("#c_amount_lcy").val($("#lcy" + line).val());
                $("#c_exchange_rate").val($("#exchange_rate" + line).val());
                //$("#c_narration").val($("#narration" + line).val());

                //removeLineRow(selector,line);
                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-edit'></i>UPDATE");
                $(select).data('type', 'U');
                $(select).data('line', line);
                $("#receiveFormSubmitBtn").prop('disabled', true);
                $("#c_amount_word").val(amountTranslate($("#ccy" + line).val()));
            }

            function updateLineValue(line) {
                $("#account_code" + line).val($("#c_account_id").val());
                $("#account_name" + line).val($("#c_account_name").val());
                $("#account_type" + line).val($("#c_account_type").val());
                $("#account_balance" + line).val($("#c_account_balance").val());
                $("#authorized_balance" + line).val($("#c_authorized_balance").val());
                $("#budget_head" + line).val($("#c_budget_head").val());
                $("#currency" + line).val($("#c_currency").val());
                $("#ccy" + line).val($("#c_amount_ccy").val());
                $("#lcy" + line).val($("#c_amount_lcy").val());
                $("#exchange_rate" + line).val($("#c_exchange_rate").val());
                //$("#narration" + line).val($("#c_narration").val());

                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-plus-circle'></i>ADD");
                $(select).data('type', 'A');
                $(select).data('line', '');
                $("#receiveFormSubmitBtn").prop('disabled', false);
                enableDisableSaveBtn();
                $("#c_remove_btn" + line).show();
            }

            enableDisableSaveBtn = function () {
                let totalLcy1 = totalLcy();
                let currencyAmount = $("#d_amount_lcy").val();
                if (nullEmptyUndefinedChecked(totalLcy1) || nullEmptyUndefinedChecked(currencyAmount) || (totalLcy1 != currencyAmount)) {
                    $("#receiveFormSubmitBtn").prop('disabled', true);
                } else {
                    $("#receiveFormSubmitBtn").prop('disabled', false);
                }
            }

            function setTotalLcy() {
                let total = totalLcy();
                $("#total_lcy").val(totalLcy());
                $("#d_amount_ccy").val(total);
                $("#d_amount_word").val(amountTranslate(total));

                if (!nullEmptyUndefinedChecked($("#d_bank_account :selected").val())) {
                    $('#d_amount_lcy').val(parseFloat($("#d_exchange_rate").val()) * totalLcy());
                }
            }

            function totalLcy() {
                let debit = $("#c_account_table >tbody >tr").find(".lcy");
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

            $("#cas_receive_form").on("submit", function (e) {
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
                            url: APP_URL + "/general-ledger/cash-receive",
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

            $("#d_amount_ccy").on('keyup', function () {
                $("#d_amount_word").val(amountTranslate($(this).val()));
            });

            $("#c_amount_ccy").on('keyup', function () {
                $("#c_amount_word").val(amountTranslate($(this).val()));
            });


            listBillRegister();
            $("#bill_section").trigger('change');

            listCashBankAcc($("#function_type :selected").val());
            enable_disable_chalan();

            $("#reset_form").on('click', function () {
                resetTablesDynamicRow();
                removeAllAttachments();
                resetDebitAccountField();
                enableDisableSaveBtn()
                $("#d_bank_account").val('').trigger('change');
                resetField(['#narration', '#total_lcy']);
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

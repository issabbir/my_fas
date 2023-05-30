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

        .d_bank_acc_field .select2-selection {
            background-color: #F2F4F4;
        }
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{--<form class="form form-horizontal isConfirmOnSubmit" id="from_val_reset" action="{{route('cash-transfer.store')}}" method="post" enctype="multipart/form-data">--}}
            <form class="form form-horizontal transferVoucherForm" id="from_val_reset" method="post"
                  enctype="multipart/form-data">
                @csrf
                <h5 style="text-decoration: underline">Transfer Voucher</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="fun_type_id" class="col-md-4 col-form-label required">Function Type</label>
                            <div class="col-md-4 pr-0">
                                <select name="fun_type_id" class="form-control form-control-sm" id="fun_type_id"
                                        required>
                                    {{--<option value="" >Select One</option>--}}
                                    @foreach($cashTranFunTypeList as $value)
                                        <option value="{{$value->function_id}}"
                                            {{--{{old('department',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->gl_type_id ? 'selected' : '')}}--}} >{{ $value->function_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                            <div class="col-md-4 pr-0">
                                <select required name="th_fiscal_year"
                                        class="form-control form-control-sm required"
                                        id="th_fiscal_year">
                                    @foreach($fiscalYear as $year)
                                        <option
                                            {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="period" class="col-md-4 col-form-label required">Posting Period</label>
                            <div class="col-md-4 pr-0">
                                <select name="period" class="form-control form-control-sm" id="period" required>
                                    {{--<option value="" >Select One</option>--}}
                                    {{--@foreach($postPeriodList as $post)
                                        <option
                                            {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }}
                                            data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                            data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                            data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                            data-postingname="{{ $post->posting_period_name}}"
                                            value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                        </option>
                                    @endforeach--}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="posting_date_field" class="col-md-4 col-form-label required">Posting
                                Date</label>
                            <div class="input-group date posting_date col-md-4 pr-0"
                                 id="posting_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false" required
                                       name="posting_date"
                                       id="posting_date_field" tabindex="-1"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#posting_date"
                                       data-toggle="datetimepicker"
                                       value=""
                                       data-predefined-date=""
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append posting_date" data-target="#posting_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="document_date_field" class="col-md-4 col-form-label">Document Date</label>
                            <div class="input-group date document_date col-md-4 pr-0"
                                 id="document_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="document_date" tabindex="-1"
                                       id="document_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#document_date"
                                       data-toggle="datetimepicker"
                                       value=""
                                       data-predefined-date=""
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append document_date" data-target="#document_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="form-group row">
                            <label for="document_number" class="col-md-4 col-form-label">Document Number</label>
                            <input maxlength="50" type="text" class="form-control form-control-sm col-md-6"
                                   name="document_number" id="document_number" value=""
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group row">
                            <label for="document_reference" class="col-md-4 col-form-label">Document Reference</label>
                            <input maxlength="200" type="text" class="form-control form-control-sm col-md-6"
                                   id="document_reference" name="document_reference" value="">
                        </div>--}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row d-flex justify-content-end">
                            <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                            <div class="col-md-5">
                                <select name="department" class="form-control form-control-sm select2" id="department"
                                        required>
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($dptList as $value)
                                        <option value="{{$value->cost_center_dept_id}}"
                                            {{ $value->cost_center_dept_id == \App\Enums\Gl\TransHeader::DEFAULT_DEPARTMENT ? 'selected' : ''}}>{{ $value->cost_center_dept_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--<div class="form-group row justify-content-end">
                            <label for="bill_reg_id" class="required col-md-4 col-form-label">Bill Register</label>
                            <div class="col-md-5">
                                <select required name="bill_reg_id" class="form-control form-control-sm select2"
                                        id="bill_reg_id">
                                    <option value="">Select Bill Register</option>
                                    @foreach($billRegs as $value)
                                        <option data-secid="{{$value->bill_sec_id}}" data-secname="{{$value->bill_sec_name}}" value="{{$value->bill_reg_id}}">{{ $value->bill_reg_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end make-readonly">
                            <label for="bill_sec_id" class="required col-md-4 col-form-label">Bill Section</label>
                            <div class="col-md-5">
                                <select required name="bill_sec_id" class="form-control form-control-sm" readonly=""
                                        id="bill_sec_id">
                                </select>
                            </div>
                        </div>--}}
                        <div class="form-group row d-flex justify-content-end">
                            <label for="bill_sec_id" class="col-form-label col-md-4 required">Bill Section</label>
                            <div class="col-md-5">
                                <select name="bill_sec_id" class="form-control form-control-sm select2 bill_section"
                                        id="bill_sec_id" required>
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($lBillSecList as $value)
                                        <option value="{{$value->bill_sec_id}}"
                                            {{$value->bill_sec_id == \App\Enums\Gl\TransHeader::DEFAULT_BILL_SECTION ? 'selected' : ''}}
                                        >{{ $value->bill_sec_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row d-flex justify-content-end">
                            <label for="bill_reg_id" class="col-form-label col-md-4 required">Bill Register</label>
                            <div class="col-md-5">
                                <select data-bill-register-id="{{\App\Enums\Gl\TransHeader::CASH_BOOK_REGISTER}}"
                                        name="bill_reg_id" class="form-control form-control-sm bill_register select2"
                                        id="bill_reg_id" required>
                                    <option value="">&lt;Select&gt;</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="document_number" class="col-md-2 col-form-label {{isset($isRequired) ? $isRequired['document_required'] : ''}}">Document Number</label>
                    <div class="col-md-2 pr-0">
                        <input maxlength="50" type="text" class="form-control form-control-sm" {{isset($isRequired) ? $isRequired['document_required'] : ''}}
                               name="document_number" oninput="this.value = this.value.toUpperCase()"
                               id="document_number"
                               value="">
                    </div>
                    <label for="document_reference" class="col-md-3 col-form-label text-right-align offset-2">Document Reference</label>
                    <div class="col-md-3 justify-content-end">
                        <input maxlength="200" type="text" class="form-control form-control-sm"
                               id="document_reference"
                               name="document_reference"
                               value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                    <div class=" col-md-10">
                        <textarea maxlength="500" required name="narration"
                                  class="required form-control form-control-sm"
                                  id="narration"></textarea>
                    </div>

                </div>
                @include('gl.cash-transfer.credit_account')

                @include('gl.cash-transfer.debit_account')

                <section>
                    @include('gl.common_file_upload')
                </section>

                <div class="row mt-1">
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-sm btn-success mr-1"><i
                                class="bx bx-save font-size-small"></i><span class="align-middle ml-25">Save</span>
                        </button>
                        <button type="button" id="reset_form" class="btn btn-sm btn-dark"><i
                                class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Reset</span>
                        </button>
                        {{--Print last voucher--}}
                        <div class="ml-1" id="print_btn"></div>
                        <h6 class="text-primary ml-2">Last Posting Batch ID
                            <span
                                class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '0'}}</span>
                        </h6>
                    </div>
                    {{--<div class="col-md-6 ml-1">
                        <h6 class="text-primary">Last Posting Batch ID
                            <span class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
                        </h6>
                    </div>--}}
                </div>
            </form>

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        /* End calender logic*/
        let documentCalendarClickCounter = 0;
        let postingCalendarClickCounter = 0;
        let chequeCalendarClickCounter = 0;
        let resetDebitCreditFields;

        resetDebitCreditFields = function () {
            $("#fun_type_id").trigger('change');
        }

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

            $("#c_cheque_date >input").val("");
            if (chequeCalendarClickCounter > 0) {
                $("#c_cheque_date").datetimepicker('destroy');
                chequeCalendarClickCounter = 0;
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

        $("#c_cheque_date").on('click', function () {
            chequeCalendarClickCounter++;
            $("#c_cheque_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriodUp(this, minDate, maxDate, currentDate);
        });

        /* End calender logic*/

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

        function listCashBankAcc() {
            $('#fun_type_id').change(function (e) {
                e.preventDefault();
                let funTypeId = $(this).val();

                if ((funTypeId == {{ \App\Enums\Gl\FunctionTypes::BANK_TRANSFER}}) || (funTypeId == {{ \App\Enums\Gl\FunctionTypes::CASH_WITHDRAWL}})) {
                    $("#chequeRow").removeClass('hidden');

                    $("#chequeRow").find("label").addClass('required');
                    $("#c_cheque_no").attr('required', 'required');
                    $("#c_cheque_date").find("input").attr('required', 'required');
                } else {
                    $("#chequeRow").addClass('hidden');

                    $("#chequeRow").find("label").removeClass('required');
                    $("#c_cheque_no").removeAttr('required', 'required');
                    $("#c_cheque_date").find("input").removeAttr('required', 'required');
                }

                //alert(funTypeId);
                $('#d_bank_acc_id').empty();
                resetField(['#d_amount_ccy', '#d_amount_lcy'])
                $('#c_bank_acc_id').empty();
                $('.input-value-clear').val('');
                $('#c_amount_ccy').val('0');
                $("#d_amount_word").val('');

                //selectDebitCreditBankAcc('#d _bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + funTypeId, APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
                selectDebitCreditBankAcc('#c_bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-credit-bank-acc/' + funTypeId, APP_URL + '/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields);

            });
        }

        function listBillRegister() {
            let billSectionId = $('#bill_sec_id').val();
            selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, APP_URL + '/general-ledger/ajax/bill-register-detail/', setBankAccounts);

            $('#bill_sec_id').on('change', function () {
                //e.preventDefault();
                /*$("#bill_reg_id").select2("destroy");
                $("#bill_reg_id").html("");*/
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, APP_URL + '/general-ledger/ajax/bill-register-detail/', setBankAccounts);

            });
        }

        function setBankAccounts(selector, data) {
            $("#c_bank_acc_id").empty();
            $("#d_bank_acc_id").empty();
            resetField(['#c_account_balance', '#c_currency', '#c_amount_ccy', '#c_amount_lcy', '#c_exchange_rate', '#c_authorized_balance']);
            resetField(['#d_account_balance', '#d_currency', '#d_amount_ccy', '#d_amount_lcy', '#d_exchange_rate', '#d_authorized_balance']);

            $("#d_bank_acc_id").attr('data-gl-acc-id', data.current_account);
            selectDebitCreditBankAcc('#d_bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#fun_type_id :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);

            $("#c_bank_acc_id").attr('data-gl-acc-id', data.contra_gl_acc_id);
            selectDebitCreditBankAcc('#c_bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#fun_type_id :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields);
        }

        /********Added on: 06/06/2022, sujon**********/
        /*function setBillSection() {
            $("#bill_reg_id").change(function (e) {
                $bill_sec_id = $("#bill_reg_id :selected").data('secid');
                $bill_sec_name = $("#bill_reg_id :selected").data('secname');
                if (!nullEmptyUndefinedChecked($bill_sec_id)) {
                    $("#bill_sec_id").html("<option value='" + $bill_sec_id + "'>" + $bill_sec_name + "</option>")
                } else {
                    $("#bill_sec_id").html("<option value=''></option>")
                }
            });
        }*/

        //setBillSection();
        /********End**********/

        /*$("#bill_reg_id").on('select2:select',function (e) {
            setDebitBankAccount($(this).val(),$("#bill_sec_id :selected").val());
        });*/

        /*function setDebitBankAccount(regId, secId) {
            let request = $.ajax({
                url: "{{--{{route('ajax.get-current-bank-account')}}--}}",
                data: {regId, secId},
                dataType: "JSON",
                headers: {
                    "X-CSRF-TOKEN": '{{--{{ csrf_token()}}--}}'
                }
            });

            request.done(function (res) {
                if (res.predefined == true) {
                    $("#d_bank_acc_id").attr('data-gl-acc-id', res.selected.gl_acc_id);
                    selectDebitCreditBankAcc('#d _bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#fun_type_id :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }*/

        function populateDebitBankInfoFields(that, data) {
            if (!nullEmptyUndefinedChecked(data)) {
                let currency = (data.currency_code);
                let exchange_rate = (data.exchange_rate);
                let d_amount_ccy = parseFloat($('#d_amount_ccy').val());

                $(that).parent().parent().parent().find('#d_account_balance').val(getCommaSeparatedValue(data.account_balance));
                $(that).parent().parent().parent().find('#d_authorized_balance').val(getCommaSeparatedValue(data.authorize_balance));

                $(that).parent().parent().parent().find('#d_account_balance_type').text(data.account_balance_type);
                $(that).parent().parent().parent().find('#d_authorized_balance_type').text(data.authorize_balance_type);

                $(that).parent().parent().parent().find('#d_currency').val(currency);
                $(that).parent().parent().parent().find('#d_exchange_rate').val(exchange_rate);

                let debit_credit_lcy = (d_amount_ccy * exchange_rate);
                if (currency == 'BDT') {
                    $("#d_exchange_rate").prop("readonly", true);
                    $('#d_amount_lcy').val(debit_credit_lcy);
                    //$('#d_amount_lcy').val(debit_credit_lcy);
                } else {
                    $("#d_exchange_rate").prop("readonly", false);
                }

                /*if ($("#fun_type_id :selected").val() == '{{\App\Enums\Gl\FunctionTypes::BANK_TRANSFER}}') {
                    if (!nullEmptyUndefinedChecked(data)) {
                        makeCreditBankAccSelected(data.contra_gl_acc_id);
                    }
                }*/
            }

        }

        function populateCreditBankInfoFields(that, data) {
            if (!nullEmptyUndefinedChecked(data)) {
                let currency = (data.currency_code);
                let exchange_rate = (data.exchange_rate);
                let c_amount_ccy = parseFloat($('#c_amount_ccy').val());
                let debit_credit_lcy = (c_amount_ccy * exchange_rate);

                $(that).parent().parent().parent().find('#c_account_balance').val(getCommaSeparatedValue(data.account_balance));
                $(that).parent().parent().parent().find('#c_authorized_balance').val(getCommaSeparatedValue(data.authorize_balance));

                $(that).parent().parent().parent().find('#c_account_balance_type').text(data.account_balance_type);
                $(that).parent().parent().parent().find('#c_authorized_balance_type').text(data.authorize_balance_type);

                $(that).parent().parent().parent().find('#c_currency').val(currency);
                $(that).parent().parent().parent().find('#c_exchange_rate').val(exchange_rate);
                //$('#d_currency').val(currency);
                //$('#d_exchange_rate').val(exchange_rate);

                if (currency == 'BDT') {
                    $("#c_exchange_rate").prop("readonly", true);
                    $('#c_amount_lcy').val(debit_credit_lcy);
                    //$('#d_amount_lcy').val(debit_credit_lcy);
                } else {
                    $("#c_exchange_rate").prop("readonly", false);
                }

                if (data.contra_gl_acc_id != $("#d_bank_acc_id :selected").select2().val()) {
                    makeDebitBankAccSelected(data.contra_gl_acc_id);
                }

            }
        }

        function makeDebitBankAccSelected(debitBank) {
            $("#d_bank_acc_id").attr('data-gl-acc-id', debitBank);
            $("#c_bank_acc_id").attr('data-gl-acc-id', '');
            selectDebitCreditBankAcc("#d_bank_acc_id", APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#fun_type_id :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
        }

        /*function makeCreditBankAccSelected(creditBank) {
            $("#c_bank_acc_id").attr('data-gl-acc-id', creditBank);
            $("#d_bank_acc_id").attr('data-gl-acc-id', '');
            selectDebitCreditBankAcc("#c_bank_acc_id", APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' + $('#fun_type_id :selected').val(), APP_URL + '/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields());
        }*/

        function lcyCalculation() {
            $('#c_amount_ccy, #c_exchange_rate').keyup(function (e) {
                //console.log($("#c_amount_ccy").val(),$("#d_exchange_rate").val());
                let c_amount_ccy_keyup = parseFloat($('#c_amount_ccy').val());
                e.preventDefault();
                if (!is_negative(c_amount_ccy_keyup) && c_amount_ccy_keyup != 0) {
                    let c_exchange_rate_get_keyup = parseFloat($("#c_exchange_rate").val());
                    $('#d_amount_ccy').val(c_amount_ccy_keyup);
                    //This line added sujon
                    //$('#c_amount_ccy').val(c_amount_ccy_keyup);

                    $('#c_exchange_rate').val(c_exchange_rate_get_keyup);

                    if (!nullEmptyUndefinedChecked(c_amount_ccy_keyup) && !nullEmptyUndefinedChecked(c_exchange_rate_get_keyup)) {
                        let debit_credit_lcy = (c_amount_ccy_keyup * c_exchange_rate_get_keyup);

                        $('#c_amount_lcy').val(debit_credit_lcy);

                        if (!nullEmptyUndefinedChecked($("#d_bank_acc_id :selected").select2().val())) {
                            $("#d_amount_lcy").val(parseFloat($("#d_amount_ccy").val()) * parseFloat($("#d_exchange_rate").val()));
                        }
                        //$('#d_amount_lcy').val(debit_credit_lcy);
                        //alert(c_amount_ccy_keyup * d_exchange_rate_get);
                    } else {
                        $('#c_amount_lcy').val('0');
                        $('#d_amount_lcy').val('0');
                    }
                } else {
                    $('#c_amount_ccy').val('0');
                    $('#d_amount_ccy').val('0');
                    $('#c_amount_lcy').val('0');
                    $('#d_amount_lcy').val('0');
                }
            });
        }

        $(document).ready(function () {
            listBillRegister();
            listCashBankAcc();
            lcyCalculation();
            enable_disable_cheque();
            //$('#fun_type_id').trigger('change');
            /*$('#fun_type_id').change(function (e) {
                let funTypeId = $(this).val();

                $("#bill_sec_id").html("");
                $("#bill_reg_id").select2("destroy");
                $("#bill_reg_id").html("");
                $("#bill_reg_id").select2();
                getBillSectionOnFunction(funTypeId, "#bill_sec_id");
            });*/

            $("#c_amount_ccy").on('keyup', function () {
                $("#c_amount_word").val(amountTranslate($(this).val()));
            });

            $(".transferVoucherForm").on("submit", function (e) {
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
                            url: "{{route('cash-transfer.store')}}",
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

            $("#reset_form").on('click', function () {
                //resetHeaderField();
                resetDebitCreditFields();
                removeAllAttachments();
                resetField(['#c_amount_word']);
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

@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }
        .text-right-align{
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
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form class="form form-horizontal isConfirmOnSubmit" id="from_val_reset" action="{{route('cash-transfer.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <h5  style="text-decoration: underline">Transfer Voucher</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="fun_type_id" class="col-md-4 col-form-label required">Function Type</label>
                            <select name="fun_type_id" class="form-control form-control-sm col-md-6" id="fun_type_id" required>
                                <option value="" >Select One</option>
                                @foreach($cashTranFunTypeList as $value)
                                    <option value="{{$value->function_id}}"
                                        {{--{{old('department',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->gl_type_id ? 'selected' : '')}}--}} >{{ $value->function_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="period" class="col-md-4 col-form-label required">Posting Period</label>
                            <select name="period" class="form-control form-control-sm col-md-4" id="period" required>
                                {{--<option value="" >Select One</option>--}}
                                @foreach($postPeriodList as $post)
                                    <option
                                        {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }}
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                        data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                        data-postingname="{{ $post->posting_period_name}}"
                                        value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="posting_date_field" class="col-md-4 col-form-label required">Posting Date</label>
                            <div class="input-group date posting_date col-md-4 pl-0 pr-0"
                                 id="posting_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false" required
                                       name="posting_date"
                                       id="posting_date_field"
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
                            <div class="input-group date document_date col-md-4 pl-0 pr-0"
                                 id="document_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="document_date"
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
                        <div class="form-group row">
                            <label for="document_number" class="col-md-4 col-form-label">Document Number</label>
                            <input maxlength="50" type="text" class="form-control form-control-sm col-md-6" name="document_number" id="document_number" value="" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group row">
                            <label for="document_reference" class="col-md-4 col-form-label">Document Reference</label>
                            <input maxlength="200" type="text" class="form-control form-control-sm col-md-6" id="document_reference" name="document_reference" value="">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row d-flex justify-content-end">
                            <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                            <div class="col-md-5">
                                <select name="department" class="form-control form-control-sm select2" id="department" required>
                                    <option value="" >Select One</option>
                                    @foreach($dptList as $value)
                                        <option value="{{$value->cost_center_dept_id}}"
                                            {{--{{old('department',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->gl_type_id ? 'selected' : '')}}--}}>{{ $value->cost_center_dept_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row d-flex justify-content-end">
                            <label for="bill_sec_id" class="col-form-label col-md-4 required">Bill Section</label>
                            <div class="col-md-5">
                                <select name="bill_sec_id" class="form-control form-control-sm select2 bill_section" id="bill_sec_id" required>
                                    <option value="" >Select One</option>
                                    @foreach($lBillSecList as $value)
                                        <option value="{{$value->bill_sec_id}}"
                                            {{--{{old('department',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->gl_type_id ? 'selected' : '')}}--}}>{{ $value->bill_sec_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row d-flex justify-content-end">
                            <label for="bill_reg_id" class="col-form-label col-md-4 required">Bill Register</label>
                            <div class="col-md-5">
                                <select name="bill_reg_id" class="form-control form-control-sm bill_register select2" id="bill_reg_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row pr-1">
                    <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                    <textarea maxlength="500" required name="narration" class="required form-control form-control-sm col-md-10 "
                              id="narration"></textarea>
                </div>

                @include('gl.cash-transfer.debit_account')

                @include('gl.cash-transfer.credit_account')

                <section>
                    @include('gl.common_file_upload')
                </section>

                <div class="row mt-1">
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-sm btn-success mr-1"><i class="bx bx-save font-size-small"></i><span class="align-middle ml-25">Save</span></button>
                        <button type="reset" onclick="resetHeaderField();removeAllAttachments()" class="btn btn-sm btn-dark"><i class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Reset</span></button>
                        <h6 class="text-primary ml-2">Last Posting Batch ID
                            <span class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '0'}}</span>
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
            }

            $("#c_cheque_date >input").val("");
            if (chequeCalendarClickCounter > 0) {
                $("#c_cheque_date").datetimepicker('destroy');
                chequeCalendarClickCounter = 0;
            }
        });

        $("#document_date").on('click', function () {
            documentCalendarClickCounter++;
            $("#document_date >input").val("");
            let minDate = false;
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate,currentDate);
        });
        $("#posting_date").on('click', function () {
            postingCalendarClickCounter++;
            $("#posting_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate,currentDate);
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

                $("#document_date >input").val(newDueDate);
            }
            postingDateClickCounter++;
        });

        $("#c_cheque_date").on('click', function () {
            chequeCalendarClickCounter++;
            $("#c_cheque_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriodUp(this, minDate, maxDate,currentDate);
        });
        /* End calender logic*/

        function enable_disable_cheque() {
            $("#withoutCheque").on('click', function () {
                if ($(this).prop("checked") == true) {
                    $("#c_cheque_no").val('').prop('readonly', true);
                    $("#c_cheque_date_field").val('').addClass("make-readonly-bg");
                    $("#c_cheque_date").addClass("make-readonly-bg");

                    $("#chequeRow").find("label").removeClass('required');
                    $("#c_cheque_no").find("input").removeAttr('required','required');
                    $("#c_cheque_date").find("input").removeAttr('required','required');
                } else if ($(this).prop("checked") == false) {
                    $("#c_cheque_no").prop('readonly', false);
                    $("#c_cheque_date_field").removeClass("make-readonly-bg");
                    $("#c_cheque_date").removeClass("make-readonly-bg");

                    $("#chequeRow").find("label").addClass('required');
                    $("#c_cheque_no").find("input").attr('required','required');
                    $("#c_cheque_date").find("input").attr('required','required');
                }
            });

            if ($("#withoutCheque").prop("checked") == true) {
                $("#c_cheque_no").val('').prop('readonly', true);
                $("#c_cheque_date_field").val('').addClass("make-readonly-bg");
                $("#c_cheque_date").addClass("make-readonly-bg");

                $("#chequeRow").find("label").removeClass('required');
                $("#c_cheque_no").find("input").removeAttr('required','required');
                $("#c_cheque_date").find("input").removeAttr('required','required');
            } else if ($("#withoutCheque").prop("checked") == false) {
                $("#c_cheque_no").prop('readonly', false);
                $("#c_cheque_date_field").removeClass("make-readonly-bg");
                $("#c_cheque_date").removeClass("make-readonly-bg");

                $("#chequeRow").find("label").addClass('required');
                $("#c_cheque_no").find("input").attr('required','required');
                $("#c_cheque_date").find("input").attr('required','required');
            }
        }

        function listCashBankAcc() {
            $('#fun_type_id').change(function (e) {
                e.preventDefault();
                let funTypeId = $(this).val();

                if ((funTypeId == {{ \App\Enums\Gl\FunctionTypes::BANK_TRANSFER}}) || (funTypeId == {{ \App\Enums\Gl\FunctionTypes::CASH_WITHDRAWL}}) ){
                    $("#chequeRow").removeClass('hidden');

                    $("#chequeRow").find("label").addClass('required');
                    $("#c_cheque_no").attr('required','required');
                    $("#c_cheque_date").find("input").attr('required','required');
                }else{
                    $("#chequeRow").addClass('hidden');

                    $("#chequeRow").find("label").removeClass('required');
                    $("#c_cheque_no").removeAttr('required','required');
                    $("#c_cheque_date").find("input").removeAttr('required','required');
                }

                //alert(funTypeId);
                $('#d_bank_acc_id').val('');
                $('#c_bank_acc_id').val('');
                $('.input-value-clear').val('');
                $('#d_amount_ccy').val('0');
                $("#d_amount_word").val('');

                selectDebitCreditBankAcc('#d_bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-debit-bank-acc/' +funTypeId, APP_URL+'/general-ledger/ajax/bank-account-details/', populateDebitBankInfoFields);
                selectDebitCreditBankAcc('#c_bank_acc_id', APP_URL + '/general-ledger/ajax/fun-type-by-credit-bank-acc/' +funTypeId, APP_URL+'/general-ledger/ajax/bank-account-details/', populateCreditBankInfoFields);

            });
        }

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                $("#bill_reg_id").select2("destroy");
                $("#bill_reg_id").html("");

                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' +billSectionId, '', '');

            });
        }

        function populateDebitBankInfoFields(that, data){
            let currency = (data.currency_code);
            let exchange_rate = (data.exchange_rate);
            let d_amount_ccy = parseFloat($('#d_amount_ccy').val());
            let debit_credit_lcy = (d_amount_ccy * exchange_rate);

            $(that).parent().parent().parent().find('#d_account_balance').val(data.account_balance);
            $(that).parent().parent().parent().find('#d_authorized_balance').val(data.authorize_balance);

            $(that).parent().parent().parent().find('#d_currency').val(currency);
            $(that).parent().parent().parent().find('#d_exchange_rate').val(exchange_rate);
            $('#c_currency').val(currency);
            $('#c_exchange_rate').val(exchange_rate);

            if (currency == 'BDT'){
                $("#d_exchange_rate").prop("readonly", true);
                $('#d_amount_lcy').val(debit_credit_lcy);
                $('#c_amount_lcy').val(debit_credit_lcy);
            }else{
                $("#d_exchange_rate").prop("readonly", false);
            }
        }

        function populateCreditBankInfoFields(that, data){
            $(that).parent().parent().parent().find('#c_account_balance').val(data.account_balance);
            $(that).parent().parent().parent().find('#c_authorized_balance').val(data.authorize_balance);
        }

        function lcyCalculation(){
            $('#d_amount_ccy, #d_exchange_rate').keyup(function (e) {
                //console.log($("#d_amount_ccy").val(),$("#d_exchange_rate").val());
                let d_amount_ccy_keyup = parseFloat($('#d_amount_ccy').val());
                e.preventDefault();
                if (!is_negative(d_amount_ccy_keyup) && d_amount_ccy_keyup != 0){
                    let d_exchange_rate_get_keyup = parseFloat($("#d_exchange_rate").val());
                    $('#c_amount_ccy').val(d_amount_ccy_keyup);
                    //This line added sujon
                    //$('#d_amount_ccy').val(d_amount_ccy_keyup);

                    $('#c_exchange_rate').val(d_exchange_rate_get_keyup);

                    if(!nullEmptyUndefinedChecked(d_amount_ccy_keyup) && !nullEmptyUndefinedChecked(d_exchange_rate_get_keyup)){
                        let debit_credit_lcy = (d_amount_ccy_keyup * d_exchange_rate_get_keyup);

                        $('#d_amount_lcy').val(debit_credit_lcy);
                        $('#c_amount_lcy').val(debit_credit_lcy);
                        //alert(d_amount_ccy_keyup * d_exchange_rate_get);
                    } else {
                        $('#d_amount_lcy').val('0');
                        $('#c_amount_lcy').val('0');
                    }
                }else{
                    $('#d_amount_ccy').val('0');
                    $('#c_amount_ccy').val('0');
                    $('#d_amount_lcy').val('0');
                    $('#c_amount_lcy').val('0');
                }
            });
        }

        $(document).ready(function () {
            listBillRegister();
            listCashBankAcc();
            lcyCalculation();
            enable_disable_cheque();


            $('#fun_type_id').change(function (e) {
                let funTypeId = $(this).val();

                $("#bill_sec_id").html("");
                $("#bill_reg_id").select2("destroy");
                $("#bill_reg_id").html("");
                $("#bill_reg_id").select2();
                getBillSectionOnFunction(funTypeId, "#bill_sec_id");
            });

            $("#d_amount_ccy").on('keyup',function () {
                $("#d_amount_word").val(amountTranslate($(this).val()));
            });
        });
    </script>
@endsection

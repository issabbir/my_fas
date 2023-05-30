<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৭ PM
 */
?>
<form id="invoice_bill_entry_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <h5 style="text-decoration: underline">Invoice/Bill Entry</h5>
    <div class="row">
        <fieldset class="border p-2 col-md-12 mt-2 mb-2">
            <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Reference</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                        <div class="col-md-5">
                            <select required name="th_fiscal_year"
                                    class="form-control form-control-sm required"
                                    id="th_fiscal_year">
                                @foreach($fiscalYear as $year)
                                    <option {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                        <div class="col-md-5">
                            <select required name="period" class="form-control form-control-sm" id="period">
                                {{--<option value="">Select a period</option>--}}
                                {{--@foreach($postingDate as $post)
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
                    <input type="hidden" name="posting_name" id="ar_posting_name">
                    <input type="hidden" name="po_master_id" id="po_master_id">
                    <div class="form-group row">
                        <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting Date</label>
                        <div class="input-group date posting_date col-md-5"
                             id="posting_date"
                             data-target-input="nearest">
                            <input required type="text" autocomplete="off" onkeydown="return false"
                                   name="posting_date"
                                   id="posting_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#posting_date"
                                   data-toggle="datetimepicker"
                                   value="{{ old('posting_date', isset($data['insertedData']->posting_date) ?  $data['insertedData']->posting_date : '') }}"
                                   data-predefined-date="{{ old('posting_date', isset($data['insertedData']->posting_date) ?  $data['insertedData']->posting_date : '') }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append posting_date" data-target="#posting_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="document_date_field" class="required col-md-4 col-form-label">Document Date</label>
                        <div class="input-group date document_date col-md-5"
                             id="document_date"
                             data-target-input="nearest">
                            <input  type="text" autocomplete="off" onkeydown="return false" required
                                    name="document_date"
                                    id="document_date_field"
                                    class="form-control form-control-sm datetimepicker-input"
                                    data-target="#document_date"
                                    data-toggle="datetimepicker"
                                    value="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                                    data-predefined-date="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                                    placeholder="DD-MM-YYYY">
                            <div class="input-group-append document_date" data-target="#document_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label for="document_number" class="required col-md-4 col-form-label">Document No</label>
                        <div class="col-md-5">
                            <input maxlength="50" required type="text" class="form-control form-control-sm" name="document_number" oninput="this.value = this.value.toUpperCase()"
                                   id="document_number"
                                   value="">
                        </div>

                    </div>--}}
                </div>
                <div class="col-md-6">
                    <div class="form-group row d-flex justify-content-end">
                        <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                        <div class="col-md-6 pl-0">
                            <select required name="department" class="form-control form-control-sm select2" id="department">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($department as $dpt)
                                    <option
                                        {{  old('department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end">
                        <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                        <div class="col-md-6 pl-0">
                            <select required name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($billSecs as $value)
                                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end">
                        <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                        <div class="col-md-6 pl-0">
                            <select required name="bill_register" class="form-control form-control-sm select2" id="bill_register">
                            </select>
                        </div>
                    </div>
                    {{--<div class="form-group row justify-content-end">
                        <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                        <div class="col-md-6 pl-0">
                            <select required name="bill_register" class="form-control form-control-sm select2"
                                    id="bill_register">
                                <option value="">Select Bill Register</option>
                                @foreach($billRegs as $value)
                                    <option data-secid="{{$value->bill_sec_id}}" data-secname="{{$value->bill_sec_name}}" value="{{$value->bill_reg_id}}">{{ $value->bill_reg_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-end make-readonly">
                        <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                        <div class="col-md-6 pl-0">
                            <select required name="bill_section" class="form-control form-control-sm" readonly=""
                                    id="bill_section">
                            </select>
                        </div>
                    </div>--}}
                </div>
            </div>
            <div class="form-group row">
                {{--<label for="document_reference" class=" col-md-2 col-form-label">Document Ref</label>
                <div class="col-md-10">
                    <input maxlength="200" type="text" class="form-control form-control-sm" id="document_reference"
                           name="document_reference"
                           value="">
                </div>--}}
                <label for="document_number" class="col-md-2 col-form-label {{isset($isRequired) ? $isRequired['document_required'] : ''}}">Document No</label>
                <div class="col-md-3 pr-5">
                    <input maxlength="50" type="text" class="form-control form-control-sm" {{isset($isRequired) ? $isRequired['document_required'] : ''}}
                           oninput="this.value = this.value.toUpperCase()"
                           name="document_number"
                           id="document_number"
                           value="">
                </div>


                <label for="document_reference" class="col-md-2 col-form-label text-right">Document Ref</label>
                <div class="col-md-5">
                    <input maxlength="200" type="text" class="form-control form-control-sm" id="document_reference"
                           name="document_reference"
                           value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                <div class="col-md-10">
                    <textarea maxlength="500" required name="narration" class="required form-control form-control-sm "
                              id="narration"></textarea>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="form-group row">
        <label class="required col-md-2 col-form-label" for="ar_party_sub_ledger">Party Sub-Ledger</label>
        <div class="col-md-6 pl-2">
            <select class="form-control form-control-sm col-md-9" id="ar_party_sub_ledger" name="ar_party_sub_ledger" required>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['subsidiary_type'] as $type)
                    <option
                        value="{{$type->gl_subsidiary_id}}" {{ (old('ar_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label required" for="ar_transaction_type">Transaction Type</label>
        <div class="col-md-6 pl-2">
            <select required class="form-control form-control-sm col-md-9" id="ar_transaction_type" name="ar_transaction_type"
                    data-pretransaction="{{ old('ar_transaction_type', isset($data['insertedData']) ? $data['insertedData']->transaction_type_id : '') }}">
                <option value="">&lt;Select&gt;</option>
                {{--@foreach($transactionType as $transaction)
                    <option
                        value="{{$transaction->transaction_type_id}}" {{ (old('ar_transaction_type', isset($data['insertedData']) ? $data['insertedData']->transaction_type_id : '' ) == $transaction->transaction_type_id) ? 'Selected' : '' }}>{{$transaction->transaction_type_name}}</option>
                @endforeach--}}
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-md-2 required" for="ar_customer_id">Customer ID</label>
        <div class="col-md-6 pl-2">
            <div class="form-group row">
                <div class="input-group col-md-6">
                    <input required name="ar_customer_id" class="form-control form-control-sm " value="" type="number"
                           id="ar_customer_id"
                           maxlength="10"
                           onfocusout="addZerosInAccountId(this)"
                           oninput="maxLengthValid(this)"
                           onkeyup="resetField(['#ar_customer_name','#ar_customer_category']);enableDisablePoCheck(0)">
                </div>
                <div class="col-md-5">
                    <button class="btn btn-sm btn-primary customerIdSearch ml-1" id="ar_customer_search" type="button"
                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ml-25">Search</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-md-2" for="ar_customer_name">Customer Name</label>
        <div class="col-md-7 pl-2">
            <input required type="text" class="form-control form-control-sm" id="ar_customer_name" name="ar_customer_name" readonly tabindex="-1">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-md-2" for="ar_customer_category">Customer Category</label>
        <div class="col-md-7 pl-2">
            <input required type="text" class="form-control form-control-sm" id="ar_customer_category" name="ar_customer_category"
                   readonly tabindex="-1">
        </div>
    </div>

    <div class="form-group row po_base_invoice">
        <div class="offset-5 col-md-4">
            <div class="form-group row">
                <label for="ar_purchase_order_date" class="col-form-label col-md-4">PO Date</label>
                <input type="text" id="ar_purchase_order_date" readonly
                       class="form-control form-control-sm col-md-8"
                       name="ar_purchase_order_date"
                       autocomplete="off"
                >
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <fieldset class="border p-2 col-md-12">
            <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Master</legend>
            <div class="form-group row">
                <label class="col-form-label col-md-2 required" for="ar_payment_currency">Payment Currency</label>
                <div class="col-md-2">
                    <select required name="ar_payment_currency" class="form-control form-control-sm" id="ar_payment_currency">
                        @foreach($data['currency'] as $cur)
                            <option
                                value="{{ $cur->currency_code }}" {{ ( $cur->currency_code == \App\Enums\Common\Currencies::O_BD) ? "selected" : '' }}>{{ $cur->currency_code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-md-2 required" for="ar_exchange_rate">Exchange Rate</label>
                <div class="col-md-2">
                    <input readonly required value="1" class="form-control form-control-sm" id="ar_exchange_rate"
                           name="ar_exchange_rate" min="0" step="0.01" type="number">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-md-2" for="ar_invoice_amount"></label>
                <div class="col-md-3 text-center">
                    <label class="col-form-label" for="">Amount in CCY</label>
                </div>
                <div class="col-md-2 text-center">
                    <label class="col-form-label" for="">Amount in LCY</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-md-2 required" for="ar_invoice_amount_ccy">Invoice Amount</label>
                <div class="col-md-3">
                    {{--<input required type="number" step="0.1" class="form-control form-control-sm text-right-align"
                           id="ar_invoice_amount_ccy"
                           name="ar_invoice_amount_ccy">--}}
                    <input required class="form-control form-control-sm text-right-align" id="ar_invoice_amount_ccy"
                           maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                           name="ar_invoice_amount_ccy"
                           type="text">
                </div>
                <div class="col-md-2">
                    {{--<input readonly step="0.1" type="number" class="form-control form-control-sm text-right-align"
                           id="ar_invoice_amount_lcy"
                           name="ar_invoice_amount_lcy">--}}
                    <input readonly tabindex="-1" class="form-control form-control-sm text-right-align" id="ar_invoice_amount_lcy"
                           name="ar_invoice_amount_lcy" type="text">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" name="ar_calculate_tax_vat"
                               id="ar_calculate_tax_vat">
                        <label class="form-check-label" for="ar_calculate_tax_vat">
                            Calculate Vat
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="ar_amount_word_ccy" class="col-form-label col-md-2">In Words</label>
                <div class="col-md-5">
                    <textarea readonly class="form-control form-control-sm" id="ar_amount_word_ccy"
                              tabindex="-1"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-md-2 required" for="ar_vat_amount_ccy">VAT Amount</label>
                {{--<div class="col-md-1">
                    <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                           id="ar_vat_amount_ccy_percentage"
                           name="ar_vat_amount_ccy_percentage">
                </div>
                <div class="col-md-2">
                    <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                           id="ar_vat_amount_ccy"
                           name="ar_vat_amount_ccy">
                </div>--}}

                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-3 pr-0">
                            <input type="text"
                                   class="form-control form-control-sm text-right-align"
                                   readonly
                                   placeholder="%"
                                   maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   id="ar_vat_amount_ccy_percentage" value=""
                                   name="ar_vat_amount_ccy_percentage">

                            {{--<input required class="form-control form-control-sm text-right-align" id="ar_vat_amount_ccy_percentage" maxlength="17" oninput="maxLengthValid(this)"
                                   name="ar_vat_amount_ccy_percentage" min="0"  step="0.01" type="number" placeholder="%" readonly>--}}
                        </div>
                        <div class="col-md-9">
                            {{--<input type="number" step="0.1" class="form-control form-control-sm text-right-align"
                                   id="ar_vat_amount_ccy"
                                   name="ar_vat_amount_ccy">--}}
                            <input class="form-control form-control-sm text-right-align"
                                   id="ar_vat_amount_ccy" required
                                   maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   name="ar_vat_amount_ccy" type="text">
                            {{--<input required class="form-control form-control-sm text-right-align" id="ar_vat_amount_ccy" maxlength="17" oninput="maxLengthValid(this)"
                                   name="ar_vat_amount_ccy" min="0"  step="0.01" type="number">--}}
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <input readonly tabindex="-1" type="text" class="form-control form-control-sm text-right-align"
                           id="ar_vat_amount_lcy" name="ar_vat_amount_lcy">
                    {{--<input readonly class="form-control form-control-sm text-right-align" id="ar_vat_amount_lcy" maxlength="17" oninput="maxLengthValid(this)"
                           name="ar_vat_amount_lcy" min="0"  step="0.01" type="number">--}}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-md-2" for="ar_receivable_amount_ccy">Receivable Amount</label>
                <div class="col-md-3">
                    <input readonly tabindex="-1" type="text" class="form-control form-control-sm text-right-align" id="ar_receivable_amount_ccy"
                           name="ar_receivable_amount_ccy">
                </div>
                <div class="col-md-2">
                    <input readonly tabindex="-1" type="text" class="form-control form-control-sm text-right-align" id="ar_receivable_amount_lcy"
                           name="ar_receivable_amount_lcy">
                </div>
            </div>
            <br>
            <span style="text-decoration: underline">Receipt Conditions</span>
            <div class="form-group row">
                <label class="col-md-2 col-form-label required" for="ar_payment_method">Receipt
                    Method</label>
                <div class=" col-md-3">
                    <select required name="ar_payment_method" class="form-control form-control-sm select2" id="ar_payment_method">
                        {{--
                        * Receipt Method: top 1 (challan) (by default) selected. REF# email
                        * Logic modified:04-04-2022--}}
                        {{--<option value="">Select Receipt Method</option>--}}
                        @foreach($paymentMethod as $value)
                            <option value="{{$value->receipt_method_id}}">{{ $value->receipt_method_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label required" for="ar_payment_terms">Receipt Terms</label>
                <div class="col-md-3">
                    <select required name="ar_payment_terms" class="form-control form-control-sm select2" id="ar_payment_terms">
{{--
                        <option value="">Select Receipt Terms</option>
--}}
                        @foreach($paymentTerms as $value)
                            <option value="{{$value->receipt_term_id}}"
                                    data-termdate="{{$value->receipt_term_days}}">{{ $value->receipt_term_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="ar_payment_due_date" class="required col-md-2 col-form-label">Receipt Due Date</label>
                <div class="input-group date ar_payment_due_date col-md-3"
                     id="ar_payment_due_date"
                     data-target-input="nearest">
                    <input required type="text" autocomplete="off" onkeydown="return false" readonly tabindex="-1"
                           name="ar_payment_due_date"
                           id="ar_payment_due_date_field"
                           class="form-control form-control-sm"
                           data-target="#ar_payment_due_date"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append" data-target="#ar_payment_due_date">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="row mt-1 distribution_line_div">
        <input type="hidden" name="ar_distribution_flag" value="1" id="ar_distribution_flag">
        <fieldset class="col-md-12 border p-2">
            <legend class="w-auto" style="font-size: 15px;">Distribution Line (Revenue Income)
            </legend>
            <div class="form-group row">
                <label class="required col-md-2 col-form-label" for="ar_account_id">Account ID</label>
                <div class="form-group row col-md-6 mr-1">
                    <div class="input-group col-md-5">
                        <input name="ar_account_id" class="form-control form-control-sm" value="" type="number"
                               id="ar_account_id"
                               maxlength="10" oninput="maxLengthValid(this)"
                               onfocusout="addZerosInAccountId(this)"
                               onkeyup="resetAccountField()">
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-sm btn-primary searchAccount" id="ar_search_account" type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ml-25">Search</span>
                        </button>
                    </div>
                </div>

                <label class="col-md-2 col-form-label" for="ar_account_balance">Account Balance</label>
                <input class="form-control form-control-sm col-md-2 text-right-align" id="ar_account_balance" tabindex="-1"
                       name="ar_account_balance"
                       type="text" readonly>
            </div>

            <div class="form-group row">
                <label for="ar_account_name" class="col-md-2 col-form-label">Account Name</label>
                <div class="col-md-6 pr-0">
                    <input name="ar_account_name" class="form-control form-control-sm" value="" id="ar_account_name" tabindex="-1"
                           readonly>
                </div>

                <label class="col-md-2 col-form-label" for="ar_authorized_balance">Authorized Balance</label>
                <input name="ar_authorized_balance" class="form-control form-control-sm text-right-align col-md-2" value=""
                       tabindex="-1"
                       id="ar_authorized_balance" readonly>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ar_account_type">Account Type</label>
                <div class="col-md-6">
                    {{--<div class="form-group row mb-0">--}}
                        <input class="form-control form-control-sm col-md-4" id="ar_account_type" name="ar_account_type"
                               type="text" readonly tabindex="-1">
                        {{--<label class="col-md-4 col-form-label" for="c_account_balance">Account Balance</label>
                        <input class="form-control form-control-sm col-md-4" id="c_account_balance" name="c_account_balance"
                               type="text" readonly tabindex="-1">--}}
                    {{--</div>--}}
                </div>
            </div>
            <div class="form-group row">
                <label for="ar_budget_head" class="col-md-2 col-form-label">Budget Head</label>
                <div class="col-md-6 pr-0">
                    <input name="ar_budget_head" class="form-control form-control-sm" value="" id="ar_budget_head" type="text"
                           readonly tabindex="-1">
                </div>
            </div>
            {{--New requirement Imam vai: 28-07-2022

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ar_currency">Currency</label>
                <div class="col-md-6">
                    <div class="form-group row">
                        <input class="form-control form-control-sm col-md-2" id="ar_currency" name="ar_currency" type="text" readonly
                               tabindex="-1">
                        <div class="col-md"></div>
                        <label class="required col-md-4 col-form-label" for="ar_amount_ccy">Amount CCY</label>
                        <input class="required form-control form-control-sm col-md-4 text-right-align" id="ar_amount_ccy"
                               maxlength="17"
                               oninput="maxLengthValid(this)"
                               name="ar_amount_ccy" min="0" step="0.01"
                               type="number">
                    </div>
                </div>
            </div>--}}
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ar_currency">Currency</label>
                <div class="col-md-6">
                    <div class="form-group row pl-1">
                        <input class="form-control form-control-sm col-md-2"
                               id="ar_currency" name="ar_currency" type="text" readonly
                               value="BDT"
                               tabindex="-1">
                        <div class="col-md"></div>
                        <label class="required col-md-4 col-form-label" for="ar_amount_ccy">Amount CCY</label>
                        <input class="required form-control form-control-sm col-md-4 text-right-align" id="ar_amount_ccy"
                               maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ar_amount_ccy"
                               type="text">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ar_acc_exchange_rate">Exchange Rate</label>
                <div class="col-md-6">
                    <div class="form-group row pl-1">
                        <input class="form-control form-control-sm col-md-2"
                               id="ar_acc_exchange_rate" name="ar_acc_exchange_rate" value="1"
                               type="text"
                               readonly tabindex="-1">
                        <div class="col-md"></div>
                        <label class="required col-md-4 col-form-label" for="ar_amount_lcy">Amount LCY</label>
                        <input class="required form-control form-control-sm col-md-4 text-right-align" id="ar_amount_lcy"
                               name="ar_amount_lcy" min="0" step="0.01"
                               type="number" readonly tabindex="-1">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-info btn-sm" type="button" tabindex="-1" onclick="addLineRow(this)" data-type="A"
                            data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle font-size-small"></i>
                        <span class="align-middle">ADD</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="ar_amount_word">In Words</label>
                    <textarea readonly class="form-control form-control-sm" id="ar_amount_word"
                              tabindex="-1"></textarea>
                </div>
            </div>
            {{--<div class="form-group row">
                <label for="c_narration" class="required col-md-2 col-form-label">Narration</label>
                <textarea name="c_narration" class="required form-control form-control-sm col-md-6 " id="c_narration"></textarea>
                <div class="col-md-2">
                    <button class="btn btn-info " type="button" tabindex="-1" onclick="addLineRow(this)" data-type="A"
                            data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle"></i>ADD
                    </button>
                </div>
            </div>--}}
            <div class="row mt-1">
                <div class="col-md-12 table-responsive">
                    <table class="table table-sm table-hover table-bordered " id="ar_account_table">
                        <thead class="thead-dark">
                        <tr>
                            <th width="12%" class="text-center">Account Code</th>
                            <th width="28%" class="text-center">Account Name</th>
                            {{--<th width="5%" class="text-center">Dr/Cr</th>--}}
                            <th width="16%" class="text-center">Amount CCY</th>
                            <th width="16%" class="text-center">Amount LCY</th>
                            <th width="5%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            {{--<td></td>--}}
                            <td class="text-right-align">Total Amount</td>
                            <td><input type="text" name="total_lcy" id="total_lcy"
                                       class="form-control form-control-sm text-right-align"
                                       readonly tabindex="-1"/></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>

    <section class="row">
        <div class="col-md-12 pr-0 pl-0">
            @include('gl.common_file_upload')
        </div>
    </section>

    <div class="row mt-1">
        <div class="col-md-12 d-flex">
            <button type="submit" class="btn btn-sm btn-success mr-1" id="invoice_bill_entry_form_submit_btn" disabled><i
                    class="bx bxs-save font-size-small"></i><span class="align-middle ml-25">Save</span>
            </button>
            <button type="button" class="btn btn-sm btn-dark" id="reset_form">
                <i class="bx bx-reset font-size-small"></i><span class="align-middle ml-25 ml-75">Reset</span>
            </button>
            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>
            <h6 class="text-primary ml-2">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{(isset($lastPostingBatch->last_posting_batch_id) ? $lastPostingBatch->last_posting_batch_id : '0')}}</span>
            </h6>
        </div>
        {{--<div class="col-md-6 ml-1">
            <h6 class="text-primary">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
            </h6>
            --}}{{--<div class="form-group row ">
                <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                <input type="text" readonly tabindex="-1" class="form-control form-control-sm col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
            </div>--}}{{--
        </div>--}}
    </div>
</form>

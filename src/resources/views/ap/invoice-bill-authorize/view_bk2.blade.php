<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৭ PM
 */
?>
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
    </style>

@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h5 style="text-decoration: underline">Invoice/Bill Entry Authorize Viewss</h5>
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            {{--{{Session::get('m-class')}}
            @php
                die(Session::get('message'));
            @endphp--}}
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{--Workflow steps start--}}
            {!! \App\Helpers\HelperClass::workflow(\App\Enums\WkReferenceTable::FAS_AP_INVOICE, App\Enums\WkReferenceColumn::INVOICE_ID, $inserted_data->invoice_id, \App\Enums\WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="invoice-bill-authorize-form" @if(isset($wkMapId)) action="{{route('invoice-bill-authorize.approve-reject-store',[$wkMapId])}}" @endif method="post">
                @csrf
                <div class="form-group row">
                    <label class="required col-md-2 col-form-label" for="ap_party_sub_ledger">Party Sub-Ledger</label>
                    <div class="col-md-6 make-readonly">
                        <select readonly="" class="form-control col-md-9" id="ap_party_sub_ledger" name="ap_party_sub_ledger">
                            <option value="">Select Party Sub Ledger</option>
                            @foreach($data['subsidiary_type'] as $type)
                                <option
                                    value="{{$type->gl_subsidiary_id}}" {{ ($inserted_data->gl_subsidiary_id == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row d-flex justify-content-end">
                            <label class="col-md-4 col-form-label required" for="ap_invoice_type">Invoice Type</label>
                            <div class="col-md-8 make-readonly">
                                <select readonly="" required class="form-control" id="ap_invoice_type" name="ap_invoice_type">
                                    <option value="{{ $inserted_data->invoice_type_id }}">{{ $inserted_data->invoice_type_name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 required" for="ap_vendor_id">Vendor ID</label>
                    <div class="col-md-6">
                        <div class="form-group row make-readonly">
                            <div class="input-group col-md-6">
                                <input required name="ap_vendor_id" class="form-control " type="number" readonly
                                       id="ap_vendor_id"
                                       maxlength="10"
                                       value="{{ $inserted_data->vendor_id }}"
                                       oninput="maxLengthValid(this)"
                                       onkeyup="resetField(['#ap_vendor_name','#ap_vendor_category']);enableDisablePoCheck(0)">
                            </div>
                            <div class="col-md-5 pl-0">
                                <button disabled class="btn btn-primary vendorIdSearch" id="ap_vendor_search" type="button"
                                        tabindex="-1"><i
                                        class="bx bx-search"></i>Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 required" for="ap_vendor_name">Vendor Name</label>
                    <div class="col-md-7 pr-0">
                        <input required type="text" value="{{ $inserted_data->vendor_name }}" class="form-control" id="ap_vendor_name" name="ap_vendor_name" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 required" for="ap_vendor_category">Vendor Category</label>
                    <div class="col-md-7 pr-0">
                        <input required type="text" value="{{ $inserted_data->vendor_category_name }}"
                               class="form-control" id="ap_vendor_category" name="ap_vendor_category"
                               readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" disabled type="checkbox" value="1" name="po_based_yn" {{ isset($inserted_data->po_number) ? 'Checked' : '' }}
                                   tabindex="" id="po_based_yn">
                            <label class="form-check-label" for="po_based_yn">
                                PO Based Invoice
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 po_base_invoice">
                        <button type="button" disabled class="btn btn-light-info" id="search_po" data-toggle="tooltip"
                                data-placement="bottom" title="Search Purchase Order detail">Good Received Info
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <div class="col-md-4 po_base_invoice">
                        <div class="form-group row">
                            <label for="ap_purchase_order_no" class="col-form-label col-md-4">PO No</label>
                            <input type="text" id="ap_purchase_order_no" readonly value="{{$inserted_data->po_number}}"
                                   class="form-control col-md-8"
                                   name="ap_purchase_order_no"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group row po_base_invoice">
                    <div class="offset-5 col-md-4">
                        <div class="form-group row">
                            <label for="ap_purchase_order_date" class="col-form-label col-md-4">PO Date</label>
                            <input type="text" id="ap_purchase_order_date" readonly
                                   class="form-control col-md-8"
                                   name="ap_purchase_order_date"
                                   autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <fieldset class="border p-2 col-md-12">
                        <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Reference
                        </legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row make-readonly">
                                    <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                                    <div class="col-md-5">
                                        <select readonly="" required name="period" class="form-control" id="period">
                                            {{--<option value="">Select a period</option>--}}
                                            @foreach($postingDate as $post)
                                                <option
                                                    {{  ((old('period',$inserted_data->trans_period_id) ==  $post->calendar_detail_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                                    data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                                    data-postingname="{{ $post->posting_period_display_name}}"
                                                    value="{{$post->calendar_detail_id}}">{{ $post->posting_period_display_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row make-readonly">
                                    <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                                        Date</label>
                                    <div class="input-group date posting_date col-md-5"
                                         id="posting_date"
                                         data-target-input="nearest">
                                        <input readonly required type="text" autocomplete="off" onkeydown="return false"
                                               name="posting_date"
                                               id="posting_date_field"
                                               class="form-control datetimepicker-input"
                                               data-target="#posting_date"
                                               data-toggle="datetimepicker"
                                               value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->document_date) }}"
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append posting_date" data-target="#posting_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row make-readonly">
                                    <label for="document_date_field" class="required col-md-4 col-form-label">Document
                                        Date</label>
                                    <div class="input-group date document_date col-md-5"
                                         id="document_date"
                                         data-target-input="nearest">
                                        <input readonly required type="text" autocomplete="off" onkeydown="return false"
                                               name="document_date"
                                               id="document_date_field"
                                               class="form-control datetimepicker-input"
                                               data-target="#document_date"
                                               data-toggle="datetimepicker"
                                               value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date) }}"
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append document_date" data-target="#document_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row make-readonly">
                                    <label for="document_number" class="required col-md-4 col-form-label">Document
                                        Number</label>
                                    <div class="col-md-5">
                                        <input readonly maxlength="25" required type="text" class="form-control"
                                               name="document_number"
                                               id="document_number"
                                               value="{{ $inserted_data->document_no }}">
                                    </div>

                                </div>
                                <div class="form-group row make-readonly">
                                    <label for="document_reference" class="col-md-4 col-form-label required">Document
                                        Reference</label>
                                    <div class="col-md-5">
                                        <input readonly maxlength="25" type="text" class="form-control"
                                               id="document_reference"
                                               name="document_reference"
                                               value="{{ $inserted_data->document_ref }}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row d-flex justify-content-end make-readonly">
                                    <label for="department" class="col-form-label col-md-4 required">Department/Cost
                                        Center</label>
                                    <div class="col-md-5">
                                        <select  readonly required name="department" class="form-control"
                                                id="department">
                                            <option value="">Select a department</option>
                                            @foreach($department as $dpt)
                                                <option
                                                    {{  $inserted_data->department_id ==  $dpt->department_id ? "selected" : "" }} value="{{$dpt->department_id}}"> {{ $dpt->department_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row d-flex justify-content-end make-readonly">
                                    <label for="bill_section" class="required col-md-4 col-form-label">Bill
                                        Section</label>
                                    <div class="col-md-5">
                                        <select readonly="" required name="bill_section" class="form-control"
                                                id="bill_section">
                                            <option value="">Select a bill</option>
                                            @foreach($billSecs as $value)
                                                <option
                                                    {{  $inserted_data->bill_sec_id ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row d-flex justify-content-end make-readonly">
                                    <label for="bill_register" class="required col-md-4 col-form-label">Bill
                                        Register</label>
                                    <div class="col-md-5">
                                        <select readonly="" required name="bill_register" class="form-control "
                                                id="bill_register">
                                            <option
                                                value="{{ $inserted_data->bill_reg_id }}">{{ $inserted_data->bill_reg_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row pr-1">
                            <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                            <div class="col-md-8">
                    <textarea readonly maxlength="500" required name="narration" class="required form-control "
                              id="narration">{{ $inserted_data->narration }}</textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="row mt-1">
                    <fieldset class="border p-2 col-md-12">
                        <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Master
                        </legend>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_payment_currency">Payment Currency</label>
                            <div class="col-md-2">
                                <select readonly="" name="ap_payment_currency" class="form-control" id="ap_payment_currency">
                                    @foreach($data['currency'] as $cur)
                                        <option
                                            value="{{ $cur->currency_code }}" {{ ( $cur->currency_code == $inserted_data->currency_code) ? "selected" : '' }}>{{ $cur->currency_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_exchange_rate">Exchange Rate</label>
                            <div class="col-md-2">
                                <input readonly type="text" value="{{$inserted_data->exchange_rate}}" class="form-control" id="ap_exchange_rate"
                                       name="ap_exchange_rate">
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_invoice_amount"></label>
                            <div class="col-md-3 text-center">
                                <label class="col-form-label" for="">CCY</label>
                            </div>
                            <div class="col-md-3 text-center">
                                <label class="col-form-label" for="">LCY</label>
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2 required" for="ap_invoice_amount_ccy">Invoice
                                Amount</label>
                            <div class="col-md-3">
                                <input readonly required type="text" class="form-control text-right-align"
                                       id="ap_invoice_amount_ccy" value="{{ $inserted_data->invoice_amount_ccy }}"
                                       name="ap_invoice_amount_ccy">
                            </div>
                            <div class="col-md-3 make-readonly">
                                <input readonly type="text" class="form-control text-right-align"
                                       value="{{ $inserted_data->invoice_amount_lcy }}"
                                       id="ap_invoice_amount_lcy"
                                       name="ap_invoice_amount_lcy">
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="ap_calculate_tax_vat" disabled
                                           id="ap_calculate_tax_vat">
                                    <label class="form-check-label" for="ap_calculate_tax_vat">
                                        Calculate Tax,Vat,Security Deposit
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_tax_amount_ccy">Tax Amount</label>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       id="ap_tax_amount_ccy" value="{{ $inserted_data->tax_amount_ccy }}"
                                       name="ap_tax_amount_ccy">
                            </div>
                            <div class="col-md-3 make-readonly">
                                <input readonly type="text" class="form-control text-right-align"
                                       value="{{ $inserted_data->tax_amount_lcy }}"
                                       id="ap_tax_amount_lcy" name="ap_tax_amount_lcy">
                            </div>
                            {{--<div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="ap_calculate_tax_vat"
                                           id="ap_calculate_tax_vat">
                                    <label class="form-check-label" for="ap_calculate_tax_vat">
                                        Calculate Tax & Vat
                                    </label>
                                </div>
                            </div>--}}
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_vat_amount_ccy">VAT Amount</label>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       id="ap_vat_amount_ccy" value="{{ $inserted_data->vat_amount_ccy }}"
                                       name="ap_vat_amount_ccy">
                            </div>
                            <div class="col-md-3 make-readonly">
                                <input readonly type="text" class="form-control text-right-align"
                                       value="{{ $inserted_data->vat_amount_lcy }}"
                                       id="ap_vat_amount_lcy" name="ap_vat_amount_lcy">
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_security_deposit_amount_ccy">Security Deposit
                                Amount</label>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       value="{{ $inserted_data->security_deposit_ccy }}"
                                       id="ap_security_deposit_amount_ccy"
                                       name="ap_security_deposit_amount_ccy">
                            </div>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       value="{{ $inserted_data->security_deposit_lcy }}"
                                       id="ap_security_deposit_amount_lcy"
                                       name="ap_security_deposit_amount_lcy">
                            </div>
                            {{--<div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                           name="ap_calculate_security_deposit"
                                           id="ap_calculate_security_deposit">
                                    <label class="form-check-label" for="ap_calculate_security_deposit">
                                        Calculate Security Deposit
                                    </label>
                                </div>
                            </div>--}}
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_payable_amount_ccy">Payable Amount</label>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       id="ap_payable_amount_ccy"
                                       value="{{ $inserted_data->payable_amount_ccy }}"
                                       name="ap_payable_amount_ccy">
                            </div>
                            <div class="col-md-3">
                                <input readonly type="text" class="form-control text-right-align"
                                       id="ap_payable_amount_lcy"
                                       value="{{ $inserted_data->payable_amount_lcy }}"
                                       name="ap_payable_amount_lcy">
                            </div>
                        </div>
                        <br>
                        <span style="text-decoration: underline">Payment Conditions</span>
                        <div class="form-group row make-readonly">
                            <label class="col-md-2 col-form-label" for="ap_payment_terms">Payment Terms</label>
                            <div class="col-md-3">
                                <select readonly="" name="ap_payment_terms" class="form-control" id="ap_payment_terms">
                                    <option value="">Select Payment Terms</option>
                                    @foreach($paymentTerms as $value)
                                        <option
                                            value="{{$value->payment_term_id}}" {{ ($inserted_data->payment_terms_id == $value->payment_term_id) ? 'selected' : ''  }} >{{ $value->payment_term_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row d-flex justify-content-end">
                                    <label class="col-md-6 col-form-label text-right-align" for="ap_payment_method">Payment
                                        Method</label>
                                    <div class=" col-md-6">
                                        <select readonly="" name="ap_payment_method" class="form-control"
                                                id="ap_payment_method">
                                            <option value="">Select Payment Method</option>
                                            @foreach($paymentMethod as $value)
                                                <option
                                                    value="{{$value->payment_method_id}}" {{ ($inserted_data->payment_methods_id == $value->payment_method_id) ? 'selected' : ''  }}>{{ $value->payment_method_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label for="ap_payment_due_date" class="required col-md-2 col-form-label">Payment Due
                                Date</label>
                            <div class="input-group date ap_payment_due_date col-md-3"
                                 id="ap_payment_due_date"
                                 data-target-input="nearest">
                                <input readonly required type="text" autocomplete="off" onkeydown="return false"
                                       name="ap_payment_due_date"
                                       id="ap_payment_due_date_field"
                                       class="form-control datetimepicker-input"
                                       data-target="#ap_payment_due_date"
                                       data-toggle="datetimepicker"
                                       value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->payment_due_date) }}"
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append" data-target="#ap_payment_due_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="row mt-1">
                    <fieldset class="col-md-12 border p-2">
                        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Payment Control
                        </legend>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_hold_all_payment"></label>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input disabled class="form-check-input" type="checkbox" value="1" name="ap_hold_all_payment"
                                           tabindex="-1"
                                           {{  ($inserted_data->payment_hold_flag == '1') ? 'Checked' : '' }}
                                           id="ap_hold_all_payment">
                                    <label class="form-check-label" for="ap_hold_all_payment">
                                        Hold All Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row make-readonly">
                            <label class="col-form-label col-md-2" for="ap_hold_all_payment_reason">Payment Hold
                                Reason</label>
                            <div class="col-md-10">
                                <input type="text"  class="form-control " id="ap_hold_all_payment_reason"
                                       value="{{  $inserted_data->payment_hold_reason }}"
                                       name="ap_hold_all_payment_reason" readonly>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="row mt-1 distribution_line_div make-readonly-bg">
                    <input type="hidden" name="ap_distribution_flag" value="0" id="ap_distribution_flag">

                    <fieldset class="col-md-12 border p-2">
                        <legend class="w-auto" style="font-size: 15px;">Distribution Line (Capital Expenditure / Revenue
                            Expenditure)
                        </legend>

                        <!--Emergency Change ,Not Necessary for this section-->
                        {{--<div class="form-group row">
                            <label class="required col-md-2 col-form-label" for="ap_account_id">Account ID</label>
                            <div class="form-group row col-md-6 pl-0 mr-1">
                                <div class="input-group col-md-5">
                                    <input readonly name="ap_account_id" class="form-control" value="" type="number"
                                           id="ap_account_id"
                                           maxlength="10" oninput="maxLengthValid(this)"
                                           onfocusout="addZerosInAccountId(this)"
                                           onkeyup="resetAccountField()">
                                </div>
                                <div class="col-md-5">
                                    <button disabled class="btn btn-primary searchAccount" id="ap_search_account" type="button"
                                            tabindex="-1"><i class="bx bx-search"></i>Search
                                    </button>
                                </div>
                            </div>

                            <label class="col-md-2 col-form-label" for="ap_account_balance">Account Balance</label>
                            <input class="form-control col-md-2 text-right-align" id="ap_account_balance" tabindex="-1"
                                   name="ap_account_balance"
                                   type="text" readonly>
                        </div>

                        <div class="form-group row">
                            <label for="ap_account_name" class="col-md-2 col-form-label">Account Name</label>
                            <input name="ap_account_name" class="form-control col-md-6" value="" id="ap_account_name"
                                   tabindex="-1"
                                   readonly>
                            <label class="col-md-2 col-form-label" for="ap_authorized_balance">Authorized
                                Balance</label>
                            <input name="ap_authorized_balance" class="form-control text-right-align col-md-2" value=""
                                   tabindex="-1"
                                   id="ap_authorized_balance" readonly>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="ap_account_type">Account Type</label>
                            <div class="col-md-6">
                                <div class="form-group row mb-0">
                                    <input class="form-control col-md-4" id="ap_account_type" name="ap_account_type"
                                           type="text" readonly tabindex="-1">
                                    --}}{{--<label class="col-md-4 col-form-label" for="c_account_balance">Account Balance</label>
                                    <input class="form-control col-md-4" id="c_account_balance" name="c_account_balance"
                                           type="text" readonly tabindex="-1">--}}{{--
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ap_budget_head" class="col-md-2 col-form-label">Budget Head</label>
                            <div class="col-md-6 pl-0 pr-0">
                                <input name="ap_budget_head" class="form-control" value="" id="ap_budget_head"
                                       type="text"
                                       readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="ap_currency">Currency</label>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <input class="form-control col-md-2" id="ap_currency" name="ap_currency" type="text"
                                           readonly
                                           tabindex="-1">
                                    <div class="col-md"></div>
                                    <label class="required col-md-4 col-form-label" for="ap_amount_ccy">Amount
                                        CCY</label>
                                    <input readonly class="required form-control col-md-4 text-right-align"
                                           id="ap_amount_ccy"
                                           maxlength="17"
                                           oninput="maxLengthValid(this)"
                                           name="ap_amount_ccy" min="0" step="0.01"
                                           type="number">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="ap_acc_exchange_rate">Exchange Rate</label>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <input class="form-control col-md-2" id="ap_acc_exchange_rate"
                                           name="ap_acc_exchange_rate"
                                           type="text"
                                           readonly tabindex="-1">
                                    <div class="col-md"></div>
                                    <label class="required col-md-4 col-form-label" for="ap_amount_lcy">Amount
                                        LCY</label>
                                    <input class="required form-control col-md-4 text-right-align" id="ap_amount_lcy"
                                           name="ap_amount_lcy" min="0" step="0.01"
                                           type="number" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button disabled class="btn btn-info " type="button" tabindex="-1" onclick="addLineRow(this)"
                                        data-type="A"
                                        data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle"></i>ADD
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="ap_amount_word">In Words</label>
                                <textarea readonly class="form-control" id="ap_amount_word"
                                          tabindex="-1"></textarea>
                            </div>
                        </div>--}}
                    <!--Emergency Change ,Not Necessary for this section-->

                        <div class="row mt-1">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-sm table-hover table-bordered " id="ap_account_table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th width="12%" class="text-center">Account Code</th>
                                        <th width="28%" class="text-center">Account Name</th>
                                        <th width="5%" class="text-center">Dr/Cr</th>
                                        <th width="16%" class="text-center">Amount CCY</th>
                                        <th width="16%" class="text-center">Amount LCY</th>
                                        <th width="5%" class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @forelse($inserted_data->invoice_line as $line)
                                        @php
                                            $total += $line->amount_lcy;
                                        @endphp
                                        <tr>
                                            <td>{{ $line->account_id }}</td>
                                            <td>{{ $line->account_name }}</td>
                                            <td>{{ $line->dr_cr }}</td>
                                            <td class="text-right-align">{{ $line->amount_ccy }}</td>
                                            <td class="text-right-align">{{ $line->amount_lcy }}</td>
                                            <td>N/A</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    <tfoot class="border-top-dark">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right-align">Total Amount</td>
                                        <td class="text-right-align">{{ $total }}</td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <section>
                    @forelse($inserted_data->invoice_file as $file)
                        <p>File description: {{$file->doc_file_desc}} File: <a
                                href="{{ route('invoice-bill-listing.download',['id'=>$file->doc_file_id]) }}">{{$file->doc_file_name}}</a>
                        </p>
                    @empty
                    @endforelse
                </section>

                {{--<div class="row mt-1">
                    --}}{{--<div class="col-md-5">
                        <a href="{{ route('invoice-bill-listing.index') }}" class="btn btn-dark">
                            <i class="bx bx-reset"></i>Back
                        </a>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 d-flex">
                            <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                            <button type="button" class="btn btn-primary approve-reject-btn mr-1" name="authorize" value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i class="bx bx-check-double"></i><span class="align-middle ml-25"></span>Authorize</button>
                            <button type="button" class="btn btn-danger approve-reject-btn" name="decline" value="{{ App\Enums\ApprovalStatus::REJECT }}"><i class="bx bx-x"></i><span class="align-middle ml-25"></span>Decline</button>
                        </div>
                    </div>--}}{{--
                    <div class="col-md-6 d-flex">
                        <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                        <button type="button" class="btn btn-primary approve-reject-btn mr-1" name="authorize" value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i class="bx bx-check-double"></i><span class="align-middle ml-25"></span>Authorize</button>
                        <button type="button" class="btn btn-danger approve-reject-btn" name="decline" value="{{ App\Enums\ApprovalStatus::REJECT }}"><i class="bx bx-x"></i><span class="align-middle ml-25"></span>Decline</button>
                    </div>
                    <div class="col-md-6 ml-1">
                        <h6 class="text-primary">Last Posting Batch ID
                            <span
                                class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
                        </h6>
                        --}}{{--<div class="form-group row ">
                            <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                            <input type="text" readonly tabindex="-1" class="form-control col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
                        </div>--}}{{--
                    </div>
                </div>--}}


                @include("ap.ap-common.common_authorizer")
                <!---Emergency Case Changed--->
                {{--<div class="row mt-1">
                    @if (isset($wkRefStatus) && ($wkRefStatus == \App\Enums\ApprovalStatus::APPROVED || $wkRefStatus == \App\Enums\ApprovalStatus::REJECT))
                        <div class="col-md-2"><label for="authorizer" class="required">Authorizer </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="text" id="authorizer" class="form-control" name="authorizer" disabled
                                   value="{{$empInfo->employee->emp_name.' ('.$empInfo->employee->emp_code.')'}}"/>
                        </div>
                    @endif
                    <div class="col-md-2"><label for="comment" class="">Comment </label></div>
                    <div class="col-md-3 form-group pl-0">
                        <input type="text" id="comment" class="form-control" name="comment"  @if (isset($wkRefStatus) && ($wkRefStatus == \App\Enums\ApprovalStatus::APPROVED || $wkRefStatus == \App\Enums\ApprovalStatus::REJECT)) disabled @endif
                        value="{{isset($wkMapInfo->reference_comment) ? $wkMapInfo->reference_comment : ''}}"/>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex">
                        @if (isset($wkRefStatus) &&  $wkRefStatus == \App\Enums\ApprovalStatus::PENDING)
                            <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                            <button type="button" class="btn btn-primary approve-reject-btn mr-1" name="authorize" value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i class="bx bx-check-double"></i><span class="align-middle ml-25"></span>Authorize</button>
                            <button type="button" class="btn btn-danger approve-reject-btn mr-1" name="decline" value="{{ App\Enums\ApprovalStatus::REJECT }}"><i class="bx bx-x"></i><span class="align-middle ml-25"></span>Decline</button>
                        @endif
                        <a href="{{route('invoice-bill-authorize.index')}}" class="btn btn-dark"><i class="bx bx-log-out"></i><span class="align-middle ml-25"></span>Back</a>
                    </div>
                </div>--}}
                <!---Emergency Case Changed--->

            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript" >

        function checkInvBillAuthForm() {
            $('.approve-reject-btn').click(function (e) {
                e.preventDefault();

                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;

                $('#approve_reject_value').val(approval_status);

                if (approval_status == 'A') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Invoice Bill ' + approval_status_val,
                    type: 'warning',
                    input: swal_input_type,
                    inputPlaceholder: 'Reason For Decline?',
                    inputValidator: (result) => {
                        return !result && 'You need to provide a comment'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        //form.submit();
                        $("#comment_on_decline").val( (isConfirm.value !== true) ? isConfirm.value : '' );
                        $('#invoice-bill-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkInvBillAuthForm();
        });

    </script>
@endsection

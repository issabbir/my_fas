<?php
/**
 *Created by PhpStorm
 *Created at ২৫/৫/২১ ৩:৩৫ PM
 */
?>
<form id="journal_voucher_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <h5 style="text-decoration: underline">Journal Voucher</h5>
    <div class="row">
        <div class="col-md-6">
            <div class=" row">
                <label for="function_type" class="required col-md-4 col-form-label">Function Type</label>
                <div class="col-md-4 pr-0">
                <select required name="function_type" class="form-control form-control-sm make-readonly-bg"
                        id="function_type"
                        tabindex="-1">
                    @foreach($funcType as $type)
                        <option
                            {{  old('function_type') ==  $type->function_id ? "selected" : "" }} value="{{$type->function_id}}">{{ $type->function_name}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class=" row">
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
            <div class=" row">
                <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                <div class="col-md-4 pr-0">
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
            <div class=" row">
                <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting Date</label>
                <div class="input-group date posting_date col-md-4 pr-0"
                     id="posting_date"
                     data-target-input="nearest">
                    <input required type="text" autocomplete="off" onkeydown="return false"
                           name="posting_date" tabindex="-1"
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
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" row">
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
                           value="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           data-predefined-date="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append document_date" data-target="#document_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class=" row align-items-end">
                <div class="offset-3 col-md-7">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" name="provision_journal"
                               id="provision_journal"
                            {{--TODO::Add role validation as like as budget head--}}
                            {{--{{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AP_MODULE_ID, \App\Enums\WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE,\App\Enums\RolePermissionsKey::CAN_BE_ADD_BUDGET_BOOK_TO_AP_INVOICE_MAKE )) ) ? 'disabled' : '' }}--}}>
                        <label class="form-check-label" for="provision_journal">Provision Booking Journal</label> {{--Check issue#3378--}}
                    </div>
                </div>

            </div>
            <div class=" row d-flex justify-content-end">
                <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                <div class="col-md-5">
                    <select required name="department" class="form-control form-control-sm select2" id="department">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($department as $dpt)
                            <option
                                {{  old('department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}">{{ $dpt->cost_center_dept_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class=" row d-flex justify-content-end">
                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                <div class="col-md-5">
                    <select required name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                        <option value="">&lt;Select&gt;</option>
                        {{--@foreach($billSecs as $value)
                            <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                            </option>
                        @endforeach--}}
                    </select>
                </div>
            </div>
            <div class=" row d-flex justify-content-end">
                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                <div class="col-md-5">
                    <select required name="bill_register" class="form-control form-control-sm select2"
                            id="bill_register">
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class=" row">
        <label for="document_number" class="col-md-2 col-form-label {{isset($isRequired) ? $isRequired['document_required'] : ''}}">Document Number</label>
        <div class="col-md-2 pr-0">
            <input maxlength="50" type="text" class="form-control form-control-sm" name="document_number" {{isset($isRequired) ? $isRequired['document_required'] : ''}}
                   oninput="this.value = this.value.toUpperCase()"
                   id="document_number"
                   value="">
        </div>

        <label for="document_reference" class="col-md-3 col-form-label text-right-align offset-2">Document
            Reference</label>
        <div class="col-md-3 justify-content-end">
            <input maxlength="200" type="text" class="form-control form-control-sm" id="document_reference"
                   name="document_reference"
                   value="">
        </div>
    </div>
    <div class=" row">
        <label for="narration" class="required col-md-2 col-form-label">Narration</label>
        <div class="col-md-10">
            <textarea maxlength="500" required name="narration" class="required form-control form-control-sm"
                      id="narration"></textarea>
        </div>
    </div>

    <h5 style="text-decoration: underline">Transaction Detail</h5>
    <fieldset class="col-md-12 border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">GL Account</legend>
        <div class="row pb-1">
            <div class="col-md-8">
                <div class="row ">
                    <input type="hidden" data-module-id="" id="module_id"/>

                    <label class="required col-md-3 col-form-label" for="account_id">Account ID</label>
                    <input name="account_id" class="form-control form-control-sm col-md-3" value=""
                           id="account_id" maxlength="10" type="number" oninput="maxLengthValid(this)"
                           onfocusout="addZerosInAccountId(this)"
                           onkeyup="resetAccountField();resetPayableReceivableFields();">
                    <div class="col-md-2 pr-0">
                        <button class="btn btn-sm btn-primary searchAccount" id="searchAccount" type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small align-top"></i><span
                                class="align-middle ml-25">Search</span>
                        </button>
                    </div>
                    <label class="col-md-2 col-form-label text-right-align" for="account_type">Type</label>
                    <input class="form-control form-control-sm col-md-2" id="account_type" name="account_type"
                           tabindex="-1" type="text" readonly="">
                </div>
                <div class="row ">
                    <label for="account_name" class="col-md-3 col-form-label">Account Name</label>
                    <input name="account_name" class="form-control form-control-sm col-md-9" value=""
                           id="account_name"
                           readonly
                           tabindex="-1">
                </div>
                <div class=" row hidden">
                    <label for="department_cost_center" class="col-form-label col-md-3">Department/Cost
                        Center</label>
                    <select name="department_cost_center"
                            class="form-control form-control-sm make-readonly-bg col-md-9"
                            id="department_cost_center">
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-form-label col-md-6" for="account_balance">Account Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input class="form-control form-control-sm text-right-align " style="height: auto;"
                               id="account_balance"
                               tabindex="-1"
                               name="account_balance"
                               type="text" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text" style="font-size: 13px" id="account_balance_type"></span>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <label class="col-md-6 col-form-label" for="authorized_balance">Authorized Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input name="authorized_balance"
                               class="form-control form-control-sm text-right-align" style="height: auto;"
                               value="" tabindex="-1"
                               id="authorized_balance" readonly>
                        <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="authorized_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="receivableArea hidden">
        <fieldset class="col-md-12 border pl-1 pr-1">
            <legend class="w-auto" style="font-size: 12px; font-weight: bold">Party Accounts (AR)</legend>
            <div class="row">
                <div class="col-md-8">
                    <div class="row ">
                        <label class="required col-md-3 col-form-label" for="ar_party_sub_ledger">Party
                            Sub-Ledger</label>
                        <select class="form-control form-control-sm col-md-9" name="ar_party_sub_ledger"
                                id="ar_party_sub_ledger">
                            <option value="">&lt;Select&gt;</option>
                            {{--@foreach($arSubsidiaryType as $type)
                                <option
                                    value="{{$type->gl_subsidiary_id}}" {{ (old('ar_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                            @endforeach--}}
                        </select>
                    </div>
                    <div class="row ">
                        <label class="required col-md-3 col-form-label" for="ar_customer_id">Party/Customer ID</label>
                        <input name="ar_customer_id" class="form-control form-control-sm col-md-3" value=""
                               type="number"
                               id="ar_customer_id"
                               maxlength="10"
                               onfocusout="addZerosInAccountId(this)"
                               oninput="maxLengthValid(this)">
                        <div class="col-md-2 pr-0">
                            <button class="btn btn-sm btn-primary customerIdSearch" id="ar_customer_search"
                                    type="button"
                                    tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Search</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="row ">
                        <label for="ar_customer_name" class="col-md-3 col-form-label">Party/Customer Name</label>
                        <input type="text" class="form-control form-control-sm col-md-9" id="ar_customer_name"
                               name="ar_customer_name" readonly tabindex="-1">
                    </div>
                    <div class="row ">
                        <label for="ar_customer_category" class="col-md-3 col-form-label">Party/Customer
                            Category</label>
                        <input name="ar_customer_category" class="form-control form-control-sm col-md-9" value=""
                               id="ar_customer_category"
                               readonly
                               tabindex="-1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row ">
                        <label class="col-form-label col-md-6" for="ar_account_balance">Account Balance</label>
                        <div class="input-group col-md-6 pl-0">
                            <input class="form-control form-control-sm text-right-align" style="height: auto;"
                                   id="ar_account_balance"
                                   tabindex="-1"
                                   name="ar_account_balance"
                                   type="text" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px;"
                                      id="ar_account_balance_type"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="col-md-6 col-form-label" for="ar_authorized_balance">Authorized Balance</label>
                        <div class="input-group col-md-6 pl-0">
                            <input name="ar_authorized_balance"
                                   class="form-control form-control-sm text-right-align" style="height: auto"
                                   value="" tabindex="-1"
                                   id="ar_authorized_balance" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="ar_authorized_balance_type"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="payableArea hidden">
        <fieldset class="col-md-12 border pl-1 pr-1">
            <legend class="w-auto" style="font-size: 12px; font-weight: bold">Party Accounts (AP)</legend>
            <div class="row">
                <div class="col-md-8">
                    <div class="row ">
                        <label class="required col-md-3 col-form-label" for="ap_party_sub_ledger">Party
                            Sub-Ledger</label>
                        <select class="form-control form-control-sm col-md-9" name="ap_party_sub_ledger"
                                id="ap_party_sub_ledger">
                            <option value="">&lt;Select&gt;</option>
                            {{--@foreach($apSubsidiaryType as $type)
                                <option
                                    value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                            @endforeach--}}
                        </select>
                    </div>
                    <div class="row ">
                        <label class="required col-md-3 col-form-label" for="ap_vendor_id">Party/Vendor ID</label>
                        <input name="ap_vendor_id" class="form-control form-control-sm col-md-3" value="" type="number"
                               id="ap_vendor_id"
                               maxlength="10"
                               onfocusout="addZerosInAccountId(this)"
                               oninput="maxLengthValid(this)">
                        <div class="col-md-2 pr-0">
                            <button class="btn btn-sm btn-primary vendorIdSearch" id="ap_vendor_search" type="button"
                                    tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Search</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="row ">
                        <label for="ap_vendor_name" class="col-md-3 col-form-label">Party/Vendor Name</label>
                        <input name="ap_vendor_name" class="form-control form-control-sm col-md-9" value=""
                               id="ap_vendor_name"
                               readonly
                               tabindex="-1">
                    </div>
                    <div class="row ">
                        <label for="ap_vendor_category" class="col-md-3 col-form-label">Party/Vendor Category</label>
                        <input name="ap_vendor_category" class="form-control form-control-sm col-md-9" value=""
                               id="ap_vendor_category"
                               readonly
                               tabindex="-1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row ">
                        <label class="col-form-label col-md-6" for="ap_account_balance">Account Balance</label>
                        <div class="input-group col-md-6 pl-0">
                            <input class="form-control form-control-sm text-right-align" style="height: auto"
                                   id="ap_account_balance"
                                   tabindex="-1"
                                   name="ap_account_balance"
                                   type="text" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="ap_account_balance_type"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-md-6 col-form-label" for="ap_authorized_balance">Authorized Balance</label>
                        <div class="input-group col-md-6 pl-0">
                            <input name="ap_authorized_balance"
                                   class="form-control form-control-sm text-right-align" style="height: auto"
                                   value="" tabindex="-1"
                                   id="ap_authorized_balance" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="ap_authorized_balance_type"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <fieldset class="col-md-12 border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Transaction Amount</legend>
        <div class=" row">
            <label class="required col-md-2 col-form-label" for="dr_cr">Dr/Cr</label>
            <select class="form-control form-control-sm col-md-1 dr_cr" name="dr_cr" id="dr_cr">
                <option value="{{\App\Enums\Common\DebitCredit::DEBIT}}">Debit</option>
                <option value="{{\App\Enums\Common\DebitCredit::CREDIT}}">Credit</option>
            </select>
        </div>
        <div class=" row">
            <label class="required col-md-2 col-form-label" for="currency">Currency</label>
            <div class="col-md-6">
                <div class=" row ">
                    <input class="form-control form-control-sm col-md-2" id="currency" name="currency"
                           type="text" readonly
                           tabindex="-1">
                    <div class="col-md"></div>
                    <label class="required col-md-4 col-form-label pl-5" for="amount_ccy">Amount CCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" id="amount_ccy"
                           name="amount_ccy" maxlength="17"
                           oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                           type="text">
                </div>
            </div>
        </div>
        <div class=" row">
            <label class="required col-md-2 col-form-label" for="exchange_rate">Exchange Rate</label>
            <div class="col-md-6">
                <div class=" row">
                    <input class="required form-control form-control-sm col-md-2" id="exchange_rate"
                           name="exchange_rate"
                           type="text" tabindex="-1"
                           readonly>
                    <div class="col-md"></div>
                    <label class="required col-md-4 col-form-label pl-5" for="amount_lcy">Amount LCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" id="amount_lcy"
                           name="amount_lcy"
                           min="0" step="0.01" tabindex="-1"
                           type="number" readonly>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <label class="col-md-2">In Words</label>
            <textarea rows="1" readonly tabindex="-1" class="form-control form-control-sm col-md-6"
                      id="amount_word"></textarea>
        </div>
        <div class="budget_booking_utilized_div d-none mb-1">
            <fieldset>
                <legend class="w-auto" style="font-size: 12px; font-weight: bold; text-decoration: underline">Budget
                    Concurrence / Booking Information
                </legend>
                <div class="form-group row">
                    <label for="budget_department" class="col-form-label col-md-2 required">Budget Department</label>
                    <div class="col-md-3 p-0">
                        <select name="budget_department" class="form-control form-control-sm select2" style="width: 100%!important;"
                                id="budget_department">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($department as $dpt)
                                <option
                                    {{  old('budget_department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 required" for="b_head_id">Budget Head ID</label>
                    <div class="input-group col-md-2 p-0">
                        <input name="b_head_id" class="form-control form-control-sm " value="0" readonly
                               type="text" id="b_head_id">
                    </div>
                    <div class="col-md-3 pl-1">
                        <button class="btn btn-sm btn-primary bookingIdSearch" id="b_booking_search"
                                type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ">Get Budget Booking Info</span>
                        </button>
                    </div>
                    <div class="col-md-4 p-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" name="ap_without_budget_info"
                                   id="ap_without_budget_info"
                                {{--@if (!isset($roleWiseUser)) disabled @endif--}}
                                {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::GL_MODULE_ID, \App\Enums\WorkFlowRoleKey::GL_JOURNAL_VOUCHER_MAKE,\App\Enums\RolePermissionsKey::CAN_BE_ADD_BUDGET_BOOK_TO_GL_JOURNAL_MAKE )) ) ? 'disabled' : '' }}>
                            <label class="form-check-label" for="ap_without_budget_info">Without Budget Booking</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="b_head_name" class=" col-md-2 col-form-label">Budget Head Name</label>
                    <div class="input-group col-md-6 p-0">
                        <input readonly type="text"
                               class="form-control form-control-sm" name="b_head_name"
                               id="b_head_name"
                               value="">
                    </div>

                    <label for="b_amt" class=" col-md-2 col-form-label">Booked Amount{{--Booking Amt--}}</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control form-control-sm text-right" name="b_amt"
                               id="b_amt"
                               value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="b_sub_category" class=" col-md-2 col-form-label">Budget Sub-Category</label>
                    <div class="col-md-6 p-0">
                        <input type="text" readonly class="form-control form-control-sm" name="b_sub_category"
                               id="b_sub_category"
                               value="">
                    </div>

                    <label for="b_utilized_amt" class=" col-md-2 col-form-label">Utilized Amount</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control form-control-sm text-right"
                               name="b_utilized_amt"
                               id="b_utilized_amt"
                               value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="b_category" class=" col-md-2 col-form-label">Budget Category</label>
                    <div class="col-md-6 p-0">
                        <input type="text" readonly class="form-control form-control-sm" name="b_category"
                               id="b_category"
                               value="">
                    </div>

                    <label for="b_available_amt" class=" col-md-2 col-form-label">Available Amount</label>
                    <div class="col-md-2">
                        <input type="text" readonly class="form-control form-control-sm text-right"
                               name="b_available_amt"
                               id="b_available_amt"
                               value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="b_type" class=" col-md-2 col-form-label">Budget Type</label>
                    <div class="col-md-6 p-0">
                        <input type="text" readonly class="form-control form-control-sm" name="b_type"
                               id="b_type"
                               value="">
                    </div>
                    {{--<div class="col-md-4 p-0 d-flex justify-content-end pr-1">
                        <button class="btn btn-sm btn-info" type="button" onclick="addLineRow(this)" data-type="A"
                                tabindex="-1"
                                data-line="" id="addNewLineBtn"><i
                                class="bx bxs-plus-circle font-size-small align-top"></i><span
                                class="align-middle ml-25">ADD</span>
                        </button>
                    </div>--}}
                </div>
            </fieldset>
        </div>
        <div class="form-group row">
            <div class="col-md-8 p-0 d-flex justify-content-end">
                <button class="btn btn-sm btn-info" type="button" onclick="addLineRow(this)" data-type="A"
                        tabindex="-1"
                        data-line="" id="addNewLineBtn"><i
                        class="bx bxs-plus-circle font-size-small align-top"></i><span
                        class="align-middle ml-25">ADD</span>
                </button>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-hover table-bordered " id="account_table">
                    <thead class="thead-dark">
                    <tr>
                        <th width="12%" class="text-center">GL Account ID</th>
                        <th width="20%" class="text-center">GL Account Name</th>
                        <th width="12%" class="text-center">Party ID</th>
                        <th width="20%" class="text-center">Party Name</th>
                        <th width="11%" class="text-center">Debit</th>
                        <th width="11%" class="text-center">Credit</th>
                        <th width="14%" class="text-center">Budget Head Name</th>
                        <th width="5%" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right-align">Total</td>
                        <td><input type="text" name="total_debit" id="total_debit"
                                   class="form-control form-control-sm text-right-align" readonly tabindex="-1"/>
                        </td>
                        <td><input type="text" name="total_credit" id="total_credit"
                                   class="form-control form-control-sm text-right-align" readonly tabindex="-1"/>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </fieldset>

    <section>
        @include('gl.preview')
    </section>

    <section>
        @include('gl.common_file_upload')
    </section>

    <div class="row mt-1">
        <div class="col-md-12 d-flex">
            <button type="submit" class="btn btn-sm btn-info mr-1" id="preview_btn" disabled>
                <i class="bx bx-printer font-size-small align-top"></i><span class="align-middle m-25">Preview</span>
            </button>
            <button type="submit" disabled class="btn btn-sm btn-success mr-1" id="journalFormSubmitBtn"><i
                    class="bx bx-save font-size-small"></i><span class="align-middle ml-25">Save</span></button>
            <button type="button" class="btn btn-sm btn-dark" id="reset_form"><i
                    class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Reset</span></button>
            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>
            <h6 class="text-primary ml-2">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '0'}}</span>
            </h6>
        </div>
        {{--<div class="col-md-6 ml-1">
            <h6 class="text-primary">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
            </h6>
            --}}{{--<div class="form-group row ">
                <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                <input type="text" readonly class="form-control form-control-sm col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
            </div>--}}{{--
        </div>--}}
    </div>
</form>


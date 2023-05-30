@extends('layouts.default')

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
            <h4><span class="border-bottom-secondary border-bottom-2">Invoice/Bill Receipt</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form id="invoice-bill-receipt-form" enctype="multipart/form-data"
                  action="# {{--{{route('invoice-bill-payment.store')}}--}} " method="post">
                @csrf
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Transaction Reference</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="th_fiscal_year" class="required col-md-4 col-form-label">Fiscal Year</label>
                                    <select required name="th_fiscal_year"
                                            class="form-control form-control-sm required col-md-6"
                                            id="th_fiscal_year">
                                        @foreach($fiscalYear as $year)
                                            <option {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group row">
                                <label for="period" class="required col-md-4 col-form-label">Posting Period</label>

                                <select required name="period" class="form-control form-control-sm col-md-6" id="period">
                                    {{--<option value="">Select a period</option>--}}
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
                            <div class="form-group row">
                                <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                                    Date</label>
                                <div class="input-group date posting_date col-md-6 pl-0 pr-0"
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
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="document_date_field" class="col-md-4 col-form-label">Document Date</label>
                                <div class="input-group date document_date col-md-6 pl-0 pr-0"
                                     id="document_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
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
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="form-group row">
                                <label for="document_number" class=" col-md-4 col-form-label">Document No</label>
                                <input maxlength="50" type="text" class="form-control form-control-sm col-md-6"
                                       name="document_number" id="document_number"
                                       oninput="this.value = this.value.toUpperCase()"
                                       value="">
                            </div>--}}
                            {{--<div class="form-group row">
                                <label for="document_reference" class="col-md-4 col-form-label">Document Reference</label>
                                <input maxlength="25" type="text" class="form-control form-control-sm col-md-6" id="document_reference" name="document_reference" value="">
                            </div>--}}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row d-flex justify-content-end">
                                <label for="department" class="col-form-label col-md-4">Dept/Cost Center</label>
                                <div class="col-md-6">
                                    <select name="department" class="form-control form-control-sm select2"
                                            id="department">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($department as $dpt)
                                            <option
                                                {{  old('department', \App\Enums\Gl\TransHeader::DEFAULT_DEPARTMENT) ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}">{{ $dpt->cost_center_dept_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_section" class="col-md-4 col-form-label">Bill Section</label>
                                <div class="col-md-6">
                                    <select name="bill_section" class="form-control form-control-sm select2"
                                            id="bill_section">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($billSecs as $value)
                                            <option {{  old('bill_section',\App\Enums\Gl\TransHeader::DEFAULT_BILL_SECTION) ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_register" class="col-md-4 col-form-label">Bill
                                    Register</label>
                                <div class="col-md-6">
                                    <select name="bill_register" class="form-control form-control-sm select2"
                                            id="bill_register">
                                    </select>
                                </div>
                            </div>
                            {{--<div class="form-group row justify-content-end">
                                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <select required name="bill_section" class="form-control form-control-sm" readonly=""
                                            id="bill_section">
                                    </select>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="row">
                        {{--<div class="col-md-2"><label for="document_reference" class="">Document Ref </label></div>
                        <div class="col-md-8 form-group pl-0">
                            <input maxlength="200" type="text" class="form-control form-control-sm"
                                   id="document_reference" name="document_reference" value="">
                        </div>--}}
                        <label for="document_number" class="required col-md-2 col-form-label">Document No</label>
                        <div class="col-md-3 pl-0 pr-0">
                            <input maxlength="50" type="text" required class="form-control form-control-sm pr-5"
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
                    <div class="row">
                        <div class="col-md-2"><label for="narration" class="required">Narration </label></div>
                        <div class="col-md-8 form-group pl-0">
                            <textarea class="form-control form-control-sm" id="narration" name="narration" rows="3"
                                      placeholder="" required maxlength="500"></textarea>
                        </div>
                    </div>
                    {{--<div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4"><label for="period" class="required">Posting Period </label></div>
                                <div class="col-md-6 form-group ">
                                    <select name="period" class="form-control form-control-sm" id="period" required>
                                        --}}{{--<option value="" >Select One</option>--}}{{--
                                        @foreach($postPeriodList as $post)
                                            <option
                                                {{  ((old('period') ==  $post->calendar_detail_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                                data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                                value="{{$post->calendar_detail_id}}">{{ $post->posting_period_display_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="posting_date" class="required">Posting Date </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <div class="input-group date posting_date"
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
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-4"><label for="document_number" class="required">Document
                                        Number </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" id="document_number" class="form-control form-control-sm" name="document_number"
                                           placeholder="" required/>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-4"><label for="document_reference" class="">Document
                                        Reference </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" id="document_reference" class="form-control form-control-sm"
                                           name="document_reference" placeholder=""/>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                </fieldset>

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Customer Account Info</legend>
                    {{--<div class="row">
                        <div class="col-md-2"><label for="party_sub_ledger" class="required">Party-Sub
                                Ledger </label></div>
                        <div class="col-md-9 form-group pl-0">
                            <select name="party_sub_ledger" class="form-control form-control-sm" id="party_sub_ledger" required>
                                <option value="">Select One</option>
                                @foreach($partySubLedgerList as $value)
                                    <option
                                        value="{{$value->gl_subsidiary_id}}">{{ $value->gl_subsidiary_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    <div class="row">
                        <div class="col-md-2"><label for="customer_id" class="required">Customer ID </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="customer_id" class="form-control form-control-sm" name="customer_id" placeholder="" onfocusout="addZerosInAccountId(this)"
                                   onkeyup="resetField(['#customer_name', '#customer_category','#customer_bills_receivable']); invRefList()"
                            />
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-info " id="customer_search_btn">
                                <i class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Search</span></button>
                        </div>
                        {{--<div class="col-md-3">
                            <button type="button" class="btn btn-outline-dark" id="receipt_search_btn">
                                <i class="bx bx-search"></i><span class="align-middle">Receipt Queue</span>
                            </button>
                        </div>--}}
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="customer_name" class="">Customer Name </label></div>
                        <div class="col-md-9 form-group pl-0">
                            <input type="text" id="customer_name" class="form-control form-control-sm"
                                   name="customer_name"
                                   placeholder="" readonly/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="customer_category" class="">Customer Category </label></div>
                        <div class="col-md-9 form-group pl-0">
                            <input type="text" id="customer_category" class="form-control form-control-sm"
                                   name="customer_category"
                                   placeholder="" readonly/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="customer_bills_receivable" class="">Bills Receivable </label>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="text" id="customer_bills_receivable" class="form-control form-control-sm"
                                   name="customer_bills_receivable"
                                   placeholder="" readonly/>
                        </div>
                    </div>
                </fieldset>
                {{--<fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Receipt Reference</legend>
                </fieldset>--}}
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">invoice/Bill Reference</legend>
                    <div class=" table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                <th>select</th>
                                <th>Party Sub-Ledger</th> {{--Add this col: Pavel-25-04-22--}}
                                <th>Document No</th>
                                <th>Document date</th>
                                <th>Document Reference</th>
                                <th>Invoice Amount</th>
                                {{--<th>Vat Amount</th>--}} {{--Block this col: Pavel-25-04-22--}}
                                <th>Due Amount</th>
                                <th>Receipt Amount</th>
                            </tr>
                            </thead>
                            <tbody id="invRefList"></tbody>
                        </table>
                    </div>
                </fieldset>

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Collection/Receipt Info</legend>

                    <div class="row">
                        <div class="col-md-2"><label for="bank_id" class="required">Bank Account </label></div>
                        <div class="col-md-6 form-group pl-0">
                            <select class="custom-select form-control form-control-sm select2" name="bank_id" required
                                    id="bank_id"
                                    data-cm-bank-id="">
                                @foreach($bankAccList as $value)
                                    <option value="{{$value->gl_acc_id}}"
                                        {{old('bank_id',isset($clgAccInfo->bank_account_id) && $clgAccInfo->bank_account_id == $value->gl_acc_id ? 'selected' : '')}} >{{$value->gl_acc_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label" for="account_balance">Account Balance</label>
                        <input name="account_balance" class="form-control form-control-sm text-right-align col-md-2"
                               value=""
                               id="account_balance" readonly tabindex="-1">
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="currency" class="">Currency</label></div>
                        <div class="col-md-1 form-group pl-0">
                            <input type="text" id="currency" class="form-control form-control-sm" name="currency" placeholder="" readonly/>
                        </div>

                        <div class="col-md-1 pr-0"><label for="exc_rate" class="">Exchange Rate</label></div>
                        <div class="col-md-1 form-group pl-0">
                            {{--<input class="form-control form-control-sm" id="exc_rate" name="exc_rate" type="number" maxlength="17" oninput="maxLengthValid(this)" min="0" required value="0" step="0.01" />--}}
                            <input type="number" id="exc_rate" value="0" class="form-control form-control-sm exc_rate text-left"
                                   name="exc_rate" value="0" placeholder="" readonly/>
                        </div>

                        <label class="col-md-2 col-form-label offset-3" for="authorized_balance">Authorized Balance</label>
                        <input name="authorized_balance" class="form-control form-control-sm text-right-align col-md-2" value="" id="authorized_balance" readonly tabindex="-1">
                    </div>

                    <div class="row">
                        <div class="col-md-2"><label for="receipt_instrument" class="required">Instrument Type</label>
                        </div>
                        <div class="col-md-6 form-group pl-0">
                            <select class="custom-select form-control form-control-sm select2" name="receipt_instrument"
                                    required
                                    id="receipt_instrument"
                                    data-cm-bank-id="">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($receiptMethods as $value)
                                    <option value="{{$value->instrument_type_id}}"{{ ($value->instrument_type_id == \App\Enums\Ar\LArReceiptMethods::CHALLAN_CASH) ? "selected" : '' }} >{{$value->receipt_method_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="challan_amount" class="">Challan Amount </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="challan_amount" class="form-control form-control-sm"
                                   name="challan_amount"
                                   placeholder=""
                             />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="instrument_no" class="required">Challan No </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="text" id="instrument_no" class="form-control form-control-sm"
                                   name="instrument_no"
                                   placeholder=""
                                   required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="cheque_date" class="required">{{--Cheque Date--}}Challan Date</label></div>
                        <div class="col-md-3 form-group pl-0">
                            <div class="input-group date cheque_date" id="cheque_date" data-target-input="nearest">
                                <input type="text" name="instrument_date" id="cheque_date_field" required
                                       autocomplete="off"
                                       class="form-control form-control-sm datetimepicker-input cheque_date"
                                       data-target="#cheque_date" data-toggle="datetimepicker"
                                       value="{{ old('instrument_date', '') }}"
                                       data-predefined-date="{{ old('instrument_date', '') }}"
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append cheque_date" data-target="#cheque_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="offset-2 col-md-3 form-group pl-0 mb-0">
                            <label for="">Amount in CCY </label>
                        </div>
                        <div class="col-md-3 form-group pl-0 mb-0">
                            <label for="">Amount in LCY </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="receipt_amt_ccy" class="required">Receipt Amount</label></div>
                        <div class="col-md-3 form-group pl-0">
                            {{--<input class="form-control form-control-sm text-right" id="receipt_amt_ccy" name="receipt_amt_ccy"
                                   type="number" maxlength="17" oninput="maxLengthValid(this)" min="0" required value="0" step="0.1" />--}}

                            <input class="form-control form-control-sm text-right-align" id="receipt_amt_ccy" readonly
                                   name="receipt_amt_ccy"
                                   type="number" maxlength="17" oninput="maxLengthValid(this)" min="0" required
                                   value="0" step="0.01"/>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="receipt_amt_lcy" value="0" step="0.01" min="0"
                                   class="form-control form-control-sm text-right"
                                   name="receipt_amt_lcy" placeholder="" readonly/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="misc_amt_ccy" class="required">Miscellaneous Amount</label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="misc_amt_ccy" readonly
                                   name="misc_amt_ccy"
                                   type="number" maxlength="17" oninput="maxLengthValid(this)" min="0"
                                   value="0" step="0.01"/>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="misc_amt_lcy" value="0" step="0.01" min="0"
                                   class="form-control form-control-sm text-right"
                                   name="misc_amt_lcy" placeholder="" readonly/>
                        </div>
                    </div>
                    {{--<div class="row">
                        <div class="col-md-2"><label for="amt_ccy_in_words" class="">In Words </label></div>
                        <div class="col-md-6 form-group pl-0">
                            <textarea class="form-control form-control-sm" id="amt_ccy_in_words" name="amt_ccy_in_words"
                                      rows="2"
                                      placeholder="" readonly></textarea>
                        </div>
                    </div>--}}
                    {{--<div class="row">
                        <div class="col-md-2"><label for="narration" class="required">Narration </label></div>
                        <div class="col-md-8 form-group pl-0">
                            <textarea class="form-control form-control-sm" id="narration" name="narration" rows="3" placeholder=""
                                      required></textarea>
                        </div>
                    </div>--}}
                </fieldset>

                <section class="col-md-12 pl-0 pr-0">
                    @include('gl.common_file_upload')
                </section>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-sm btn-success mr-1"><i
                                class="bx bx-save font-size-small"></i><span
                                class="align-middle ml-25">Save</span></button>
                        <button type="button" id="reset_form" class="btn btn-sm btn-dark"><i
                                class="bx bx-reset font-size-small"></i><span
                                class="align-middle ml-25">Reset</span></button>
                        {{--Print last voucher--}}
                        <div class="ml-1" id="print_btn"></div>
                        <h6 class="text-primary ml-2">Last Posting Batch ID
                            <span
                                class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{(isset($lastPostingBatch->last_posting_batch_id) ? $lastPostingBatch->last_posting_batch_id : '0')}}</span>
                        </h6>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('ar.ar-common.common_customer_list_modal')
    {{--Receipt Queue Modal--}}
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="receiptListModal" tabindex="-1" role="dialog"
                         aria-labelledby="receiptListModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="receiptListModalLabel">Receipt Queue</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="#" id="customer_search_form">
                                                <fieldset class="border p-2">
                                                    <legend class="w-auto font-weight-bold" style="font-size: 15px">
                                                        Customer Search
                                                    </legend>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-2"
                                                               for="search_customer_name">Name</label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="search_customer_name"
                                                                   name="search_customer_name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-2"
                                                               for="search_customer_short_name">Short Name</label>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="search_customer_short_name"
                                                                   name="search_customer_short_name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-2 text-left"
                                                               for="search_customer_category">Customer
                                                            Category</label>
                                                        <div class="col-md-3">
                                                            <select class="form-control form-control-sm"
                                                                    id="search_customer_category"
                                                                    name="search_customer_category">
                                                                <option value="">&lt;Select&gt;</option>
                                                                @foreach($customerCategory as $type)
                                                                    <option
                                                                        value="{{$type->customer_category_id}}">{{$type->customer_category_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="row mt-1">
                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary"
                                                                id="ar_customer_search_submit"><i
                                                                class="bx bx-search"></i>Search
                                                        </button>
                                                        <button type="reset" class="btn btn-dark ml-1"
                                                                id="ar_customer_search_reset">
                                                            <i class="bx bx-reset"></i>Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-hover  w-100"
                                                   id="customerSearch">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Short Name</th>
                                                    <th>Category</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
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
    </section>
    {{--Receipt Queue Modal Ends--}}
@endsection

@section('footer-script')
    <script type="text/javascript">
        function billSectionBillRegister() {
            $('#bill_section').change(function (e) {
                $("#bill_register").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + billSectionId, '', '');
            });
        }

        /********Added on: 06/06/2022, sujon**********/
        function setPeriodCurrentDate(){
            $("#posting_date_field").val($("#period :selected").data("currentdate"));
            $("#document_date_field").val($("#period :selected").data("currentdate"));
            $("#cheque_date_field").val($("#period :selected").data("currentdate"));
        }
        //setPeriodCurrentDate()
        /********End**********/

        function dateValidation() {
            /* End calender logic*/
            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let chequeCalendarClickCounter = 0;

            $("#period").on('change', function () {
                $("#document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                    documentDateClickCounter = 0;
                }

                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                $("#cheque_date >input").val("");
                if (chequeCalendarClickCounter > 0) {
                    $("#cheque_date").datetimepicker('destroy');
                    chequeCalendarClickCounter = 0;
                }

                setPeriodCurrentDate()
            });


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
                if (!nullEmptyUndefinedChecked($("#posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#posting_date_field").val(),"YYYY-MM-DD");
                    } else {
                        newDueDate = moment($("#posting_date_field").val(), "DD-MM-YYYY");
                    }
                    $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                    $("#cheque_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            let documentDateClickCounter = 0;
            $("#document_date").on("change.datetimepicker", function () {
                let newDueDate;
                if (!nullEmptyUndefinedChecked($("#document_date_field").val())) {
                    if (documentDateClickCounter == 0) {
                        newDueDate = moment($("#document_date_field").val(),"YYYY-MM-DD");
                    } else {
                        newDueDate = moment($("#document_date_field").val(), "DD-MM-YYYY");
                    }
                    $("#cheque_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                documentDateClickCounter++;
            });

            $("#cheque_date").on('click', function () {
                chequeCalendarClickCounter++;
                $("#cheque_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = false;
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
        }

        /* End calender logic*/

        /*function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }*/
        /********Added on: 06/06/2022, sujon**********/
        /*function setBillSection(){
            $("#bill_register").change(function (e) {
                $bill_sec_id = $("#bill_register :selected").data('secid');
                $bill_sec_name = $("#bill_register :selected").data('secname');
                if (!nullEmptyUndefinedChecked($bill_sec_id)){
                    $("#bill_section").html("<option value='"+$bill_sec_id+"'>"+$bill_sec_name+"</option>")
                }else{
                    $("#bill_section").html("<option value=''></option>")
                }
            });
        }*/
        //setBillSection();
        /********End**********/
        function customerSearch() {
            $("#customer_search_btn").on("click", function () {
                //e.preventDefault();
                let customerId = $("#customer_id").val();

                if (!nullEmptyUndefinedChecked(customerId)) {
                    invRefList();
                    getCustomerDetail(customerId);
                } else {
                    $("#customerListModal").modal('show');
                }
            });
        }

        function receiptSearch() {
            $("#receipt_search_btn").on("click", function () {
                //e.preventDefault();
                let customerId = $("#customer_id").val();

                if (!nullEmptyUndefinedChecked(customerId)) {
                    invRefList();
                    getCustomerDetail(customerId);
                } else {
                    $("#receiptListModal").modal('show');
                }
            });
        }

        function customerList() {
            $("#customer_search_form").on('submit', function (e) {
                e.preventDefault();

                $('#customerSearch').data("dt_params", {
                    /* vendorType: $('#search_vendor_type :selected').val(),*/
                    customerCategory: $('#search_customer_category :selected').val(),
                    customerName: $('#search_customer_name').val(),
                    customerShortName: $('#search_customer_short_name').val(),
                }).DataTable().draw();
                //accountTable.draw();
            });


            $('#customerSearch').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: APP_URL + '/account-receivable/ajax/customer-search-datalist',
                    'type': 'POST',
                    'headers': {
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
                    //{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'customer_id', "name": 'customer_id'},
                    {"data": "name"},
                    {"data": "short_name"},
                    {"data": "category"},
                    {"data": "action", "orderable": false}
                ],
            });
        }

        $(document).on("click", '.customerSelect', function (e) {
            //e.preventDefault();
            let customer_id = $(this).data('customer');
            getCustomerDetail(customer_id);
        });

        function getCustomerDetail(customer_id) {

            var request = $.ajax({
                url: APP_URL + '/account-receivable/ajax/customer-with-outstanding-balance',
                data: {customerId: customer_id}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $("#customer_id").notify("Customer id not found", "error");
                    resetField(['#customer_id', '#customer_name', '#customer_category', '#customer_bills_receivable']);
                } else {
                    $('#customer_id').val(d.customer_id);
                    $('#customer_name').val(d.customer_name);
                    $('#customer_category').val(d.customer_category_name);
                    $('#customer_bills_receivable').val(d.os_bill_receivable);

                    invRefList();
                    $("#customerListModal").modal('hide');
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        function invRefList() {
            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/account-receivable/ajax/invoice-reference-list',
                data: $('#invoice-bill-receipt-form').serialize(),
                success: function (data) {
                    $('#invRefList').html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function bankAcc() {
            $('#bank_id').change(function (e) {
                e.preventDefault();
                resetField(["#currency", "#exc_rate", "#account_balance", "#authorized_balance"]);
                let glAccId = $(this).val();
                let receipt_amt_ccy = Number($('#receipt_amt_ccy').val()); //Add this part: Pavel-25-04-22

                if (!nullEmptyUndefinedChecked(glAccId)){
                    $.ajax({
                        type: 'GET',
                        /*'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },*/
                        url: APP_URL + '/account-payable/ajax/gl-acc-wise-coa',
                        data: {gl_acc_id: glAccId},
                        success: function (data) {
                            //console.log(data);
                            $("#currency").val(data.currency_code);
                            $("#exc_rate").val(data.exchange_rate); //Update this part: Pavel-25-04-22
                            $("#account_balance").val(data.account_balance);
                            $("#authorized_balance").val(data.authorize_balance);

                            if (data.currency_code == "{{\App\Enums\Common\Currencies::O_BD}}") {
                                $("#exc_rate").prop("readonly", true);
                                /** Add this two filed : Pavel-25-04-22 **/
                                $('#receipt_amt_lcy').val(receipt_amt_ccy * data.exchange_rate);
                                $("#amt_ccy_in_words").val(amountTranslate(receipt_amt_ccy));
                            } else {
                                $("#exc_rate").prop("readonly", false);
                                /** Add this two filed : Pavel-25-04-22 **/
                                $('#receipt_amt_lcy').val(receipt_amt_ccy * data.exchange_rate);
                                $("#amt_ccy_in_words").val(amountTranslate(receipt_amt_ccy));
                            }
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });
        }

        function invRefSum() {
            $(document).on("click", '.inv-ref-check', function (e) {
                //e.preventDefault();
                getInvRefTotal(false, this);
            });
            getInvRefTotal(true, this);
        }

        function getInvRefTotal(isInit, selector) {
            //let totalDueAmt = 0; //Block this part: Pavel-25-04-22
            let totalReceiptAmt = 0;
            let check = isInit ? ".inv-ref-check" : ".inv-ref-check:checked";
            $(check).each(function () {
                //totalDueAmt += Number($(this).closest("tr").find("td:eq(7)").text()); //Block this col: Pavel-25-04-22
                totalReceiptAmt += Number($(this).closest("tr").find(".receipt-amount").val()); //Add this col : Pavel-25-04-22
            });

            if ($(selector).prop("checked")) {
                $(selector).removeClass("bg-primary").addClass("bg-success");
                $(selector).closest("tr").find(".receipt-amount").prop("disabled", false); //Add this part: Pavel-25-04-22
                $(selector).closest("tr").find(".receipt-amount").focus(); //Add this part: Pavel-27-04-22
            } else {
                $(selector).removeClass("bg-success").addClass("bg-primary");
                $(selector).closest("tr").find(".receipt-amount").prop("disabled", true); //Add this part: Pavel-25-04-22
            }

            if (totalReceiptAmt == 0) {
                //$('#total_due_amt').html('0'); //Block this part & Previous if condition totalDueAmt: Pavel-25-04-22
                /** Add this part : Pavel-25-04-22 **/
                $('#total_receipt_amt').html('0');
                $('#receipt_amt_ccy').val('0');
                $("#total_receipt_amt_in_words").html(amountTranslate(totalReceiptAmt));
                getCcyLcyCalculation();
            } else {
                //$('#total_due_amt').html(totalDueAmt); //Block this part: Pavel-25-04-22
                /** Add this part : Pavel-25-04-22 **/
                $('#total_receipt_amt').html(totalReceiptAmt.toFixed(2));
                $('#receipt_amt_ccy').val(totalReceiptAmt.toFixed(2));
                $("#total_receipt_amt_in_words").html(amountTranslate(totalReceiptAmt));
                getCcyLcyCalculation();
            }
        }

        /** Add this part start : Pavel-25-04-22 **/
        function receiptAmtSum(){
            $(document).on("keyup",'.receipt-amount', function (e) {
                //e.preventDefault();
                let receiptAmt = $(this).val();
                let dueAmt= $(this).closest("tr").find("td:eq(6)").text();
                getReceiptAmtTotal();

                if ( Number(receiptAmt) > Number(dueAmt)){
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Receipt amount cannot be more than '+ dueAmt,
                        type: 'warning',
                    });
                    $(this).closest('tr').find(".receipt-amount").val('0');
                    getReceiptAmtTotal();
                }
            });
        }

        function getReceiptAmtTotal(){
            let totalReceiptAmt = 0;
            $('.receipt-amount').each(function () {
                if ( $(this).closest("tr").find(".receipt-amount").prop('disabled') == false ){
                    totalReceiptAmt += Number($(this).closest("tr").find(".receipt-amount").val());
                }
            });

            if (totalReceiptAmt == 0) {
                $('#total_receipt_amt').html('0');
                $('#receipt_amt_ccy').val('0');
                $("#total_receipt_amt_in_words").html(amountTranslate(totalReceiptAmt));
                getCcyLcyCalculation();
            } else {
                $('#total_receipt_amt').html(totalReceiptAmt.toFixed(2));
                $('#receipt_amt_ccy').val(totalReceiptAmt.toFixed(2));
                $('#total_receipt_amt_in_words').html(amountTranslate(totalReceiptAmt));
                getCcyLcyCalculation();
            }
        }

        function getCcyLcyCalculation() {
            let challanAmt = Number($('#challan_amount').val());  //add pavel-30-06-22
            let receipt_amt_ccy = Number($('#receipt_amt_ccy').val());
            let exc_rate = Number($('#exc_rate').val());
            let miscAmt = challanAmt - receipt_amt_ccy; //add pavel-30-06-22

            $('#receipt_amt_lcy').val(receipt_amt_ccy * exc_rate);
            //$("#amt_ccy_in_words").val(amountTranslate(receipt_amt_ccy));

            //add pavel-30-06-22
            if (miscAmt > 0 ){
                $('#misc_amt_ccy').val( (challanAmt - receipt_amt_ccy).toFixed(2)  );
                $('#misc_amt_lcy').val( (challanAmt - (receipt_amt_ccy * exc_rate )).toFixed(2) );
            } else {
                $('#misc_amt_ccy').val('0');
                $('#misc_amt_lcy').val('0');
            }
        }
        /** Add this part end : Pavel-25-04-22 **/

        function ccyLcyCalculation() {
            $('#receipt_amt_ccy').keyup(function (e) {
                e.preventDefault();
                let receipt_amt_ccy = Number($('#receipt_amt_ccy').val());
                let exc_rate = Number($('#exc_rate').val());

                $('#receipt_amt_lcy').val(receipt_amt_ccy * exc_rate);
                $("#amt_ccy_in_words").val(amountTranslate(receipt_amt_ccy));
            });
        }

        //add pavel-30-06-22
        function challanAmount(){
            $('#challan_amount').keyup(function (e) {
                e.preventDefault();

                let challanAmt = Number($(this).val());
                let receipt_amt_ccy = Number($('#receipt_amt_ccy').val());
                let exc_rate = Number($('#exc_rate').val());
                let miscAmt = challanAmt - receipt_amt_ccy;

                if (miscAmt > 0 ){
                    $('#misc_amt_ccy').val( (challanAmt - receipt_amt_ccy).toFixed(2)  );
                    $('#misc_amt_lcy').val( (challanAmt - (receipt_amt_ccy * exc_rate )).toFixed(2) );
                } else {
                    $('#misc_amt_ccy').val('0');
                    $('#misc_amt_lcy').val('0');
                }
            });
        }

        function setInstrumentNo() {
            $("#document_number").on('keyup',function () {
                $("#instrument_no").val($(this).val());
            })
        }

        function preSubmitValidateFrom() {
            $('#invoice-bill-receipt-form').submit(function (e) {
                e.preventDefault();
                let form = this;
                let receiptAmt = Number($('#receipt_amt_ccy').val());
                let customerId = $('#customer_id').val();
                let customerName = $('#customer_name').val();
                let receiptAmtLcy = $('#receipt_amt_lcy').val();
                let countReceiptAmt = 0;
                let countCheck = 0;

                if (($('#receipt_amt_ccy').val()) > Number($('#total_receipt_amt').text())) { //Update this part-Previous Number($('#total_receipt_amt').text(): Pavel-25-04-22
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Payment Can\'t be more than total payment due',
                        type: 'error',
                    });
                    return false;
                }

                /*** Block this condition start Pavel-31-07-22 ***/
                /*$('#inv_ref_table').find('input[type=checkbox]:checked').each(function () {
                    let checkRowAmt = Number($(this).closest("tr").find("td:eq(6)").text());
                    if (receiptAmt > 0) {
                        receiptAmt = (receiptAmt - checkRowAmt);
                        countReceiptAmt++;
                    }
                    countCheck++;
                });

                if (countReceiptAmt != countCheck) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Payment/Adjustment does fulfilled with Invoice References Selections.',
                        type: 'error',
                    });
                }*/
                /*** Block this condition end Pavel-31-07-22 ***/

                else {
                    Swal.fire({
                        title: 'Are you sure?',
                        //text: '',
                        html: 'Submit' + '<br>' +
                            'Customer ID : ' + customerId + '<br>' +
                            'Customer Name : ' + customerName + '<br>' +
                            'Total Receivable Amount: ' + receiptAmtLcy + '<br>',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#dd3333',
                        confirmButtonText: 'Yes, Save it!'
                    }).then(function (result) {
                        if (result.value) {
                            let request = $.ajax({
                                url: APP_URL + "/account-receivable/invoice-bill-receipt",
                                data: new FormData($("#invoice-bill-receipt-form")[0]),
                                processData: false,
                                contentType: false,
                                dataType: "JSON",
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": '{{ csrf_token()}}'
                                }
                            });

                            request.done(function (res) {
                                if (res.response_code == "1") {
                                    Swal.fire({
                                        type: 'success',
                                        text: res.response_msg,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        allowOutsideClick: false
                                    }).then(function () {
                                        $("#reset_form").trigger('click');
                                        $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');
                                        /*let url = '{{ route('invoice-bill-receipt.index') }}';
                                        window.location.href = url;*/
                                    });
                                } else {
                                    Swal.fire({text: res.response_msg, type: 'error'});
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                console.log(jqXHR);
                                //Swal.fire({text:textStatus+jqXHR,type:'error'});
                                //console.log(jqXHR, textStatus);
                            });
                        }
                    });
                }
            });
        }

        $("#th_fiscal_year").on('change',function () {
            getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
        });
        getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }

        $(document).ready(function () {
            //listBillRegister();
            billSectionBillRegister();
            customerSearch();
            receiptSearch();
            invRefList();
            customerList();
            bankAcc();
            $("#bank_id").trigger('change');
            invRefSum();
            receiptAmtSum(); //Add this part: Pavel-25-04-22
            challanAmount();
            ccyLcyCalculation();
            dateValidation();
            //datePicker("#cheque_date");
            preSubmitValidateFrom();
            setInstrumentNo();
            $("#bill_section").trigger('change');

            $("#reset_form").on('click',function () {
                resetField(['#narration']);
                resetField(['#customer_id','#customer_name', '#customer_category','#customer_bills_receivable','#receipt_amt_ccy','#receipt_amt_lcy','#challan_amount']);
                invRefList();
                removeAllAttachments();
                $("#bank_id").trigger('change');    //Amount will change after submit.
            })
        });

    </script>
@endsection

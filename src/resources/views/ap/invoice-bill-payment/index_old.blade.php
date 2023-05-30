@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
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
            <h4><span class="border-bottom-secondary border-bottom-2">Invoice/Bill Payment</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form id="invoice-bill-pay-form" enctype="multipart/form-data"
                  action="# {{--{{route('invoice-bill-payment.store')}}--}} " method="post">
                @csrf
                <fieldset class="border pl-1 pr-1">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Transaction Reference</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                                <div class="col-md-5">
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
                            <div class="row">
                                <div class="col-md-4"><label for="period" class="required">Posting Period </label></div>
                                <div class="col-md-5 form-group ">
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
                            <div class="row">
                                <div class="col-md-4"><label for="posting_date" class="required">Posting Date </label>
                                </div>
                                <div class="col-md-5 form-group">
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

                            <div class="form-group row">
                                <label for="document_date_field" class=" col-md-4 col-form-label">Document Date</label>
                                <div class="input-group date document_date col-md-5"
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
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="form-group row">
                                <div class="col-md-4">
                                    <label for="document_number" class="">Document No </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" id="document_number" maxlength="50"
                                           class="form-control form-control-sm" name="document_number"
                                           oninput="this.value = this.value.toUpperCase()"
                                           placeholder=""/>
                                </div>
                            </div>--}}
                        </div>
                        <div class="col-md-6">
                            {{--CPA don't want department: 07/06/2022--}}
                            <div class="form-group row d-flex justify-content-end make-readonly">
                                <div class="col-md-3 pr-0"><label for="department" class="">Dept/Cost Center </label>
                                </div>
                                <div class="col-md-6">
                                    <select name="department" readonly="" class="form-control form-control-sm"
                                            id="department">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($department as $dpt)
                                            <option
                                                {{  old('department', \App\Enums\Gl\TransHeader::DEFAULT_DEPARTMENT) ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="bill_sec_id" class="required">Bill
                                        Section </label></div>
                                <div class="col-md-6 form-group">
                                    <select required name="bill_sec_id" class="form-control form-control-sm select2"
                                            id="bill_sec_id">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($lBillSecList as $value)
                                            <option
                                                {{  old('bill_sec_id',\App\Enums\Gl\TransHeader::DEFAULT_BILL_SECTION) ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="bill_reg_id" class="required">Bill
                                        Register </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <select required name="bill_reg_id"
                                            class="form-control form-control-sm bill_register" id="bill_reg_id">
                                        <option value="">&lt;Select&gt;</option>
                                    </select>
                                </div>
                            </div>
                            {{--<div class="row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="bill_register" class="required">Bill
                                        Register </label></div>
                                <div class="col-md-6 form-group">
                                    <select required name="bill_reg_id"
                                            class="form-control form-control-sm bill_register select2" id="bill_reg_id">
                                        <option value="">Select Bill Register</option>
                                        @foreach($billRegs as $value)
                                            <option data-secid="{{$value->bill_sec_id}}"
                                                    data-secname="{{$value->bill_sec_name}}"
                                                    value="{{$value->bill_reg_id}}">{{ $value->bill_reg_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end make-readonly">
                                <div class="col-md-3 pr-0"><label for="bill_sec_id" class="required">Bill
                                        Section </label></div>
                                <div class="col-md-6 form-group">
                                    <select required name="bill_sec_id" class="form-control form-control-sm"
                                            id="bill_sec_id" readonly="">
                                    </select>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{--<div class="col-md-2"><label for="document_reference" class="">Document Ref</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="document_reference" class="form-control form-control-sm"
                                   maxlength="200"
                                   name="document_reference" placeholder=""/>
                        </div>--}}
                        <label for="document_number" class="required col-md-2 col-form-label">Document No</label>
                        <div class="col-md-3 pr-5">
                            <input maxlength="50" type="text" required class="form-control form-control-sm pr-5"
                                   oninput="this.value = this.value.toUpperCase()"
                                   name="document_number"
                                   id="document_number"
                                   value="">
                        </div>


                        <label for="document_reference" class="col-md-2 col-form-label text-right">Document Ref</label>
                        <div class="col-md-5">
                            <input maxlength="200" type="text" class="form-control form-control-sm"
                                   id="document_reference"
                                   name="document_reference"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><label for="narration" class="required">Narration </label></div>
                        <div class="col-md-10">
                                 <textarea class="form-control form-control-sm" id="narration" name="narration" rows="2"
                                           placeholder="" maxlength="500"
                                           required></textarea>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border  pl-1 pr-1">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Party Leader Info</legend>
                    <div class="row">
                        <div class="col-md-2"><label for="party_sub_ledger" class="required">Party-Sub Ledger </label>
                        </div>
                        <div class="col-md-9 form-group pl-0">
                            <select name="party_sub_ledger" class="form-control form-control-sm" id="party_sub_ledger"
                                    required>
                                <option value="">&lt;Select&gt;</option>
                                @foreach($partySubLedgerList as $value)
                                    <option
                                        value="{{$value->gl_subsidiary_id}}">{{$value->gl_subsidiary_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="vendor_id" class="required">Party/Vendor ID </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="vendor_id" class="form-control form-control-sm" name="vendor_id"
                                   placeholder=""
                                   onfocusout="addZerosInAccountId(this)"
                                   onkeyup="resetField(['#vendor_name', '#vendor_category','#os_bills_payable','#os_security_deposits','#os_prepayments','#good_for_payment',,'#bank_pay_amt_ccy', '#bank_pay_amt_lcy', '#adj_pre_pay_amt_ccy', '#adj_pre_pay_amt_lcy','#fine_forfeiture_ccy', '#fine_forfeiture_lcy', '#pay_adj_amt_ccy','#pay_adj_amt_lcy']);resetTablesDynamicRow('#inv_ref_table')"
                                {{--value="{{old('branch_id',isset($bankBranchInfo->branch_code) ? $bankBranchInfo->branch_code : '')}}"--}}/>
                        </div>
                        <div class="col-md-7">
                            <button type="button" class="btn btn-sm btn-info mb-1" id="vendor_search_btn"><i
                                    class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Search</span></button>
                            <button type="button" class="btn btn-sm btn-primary mb-1" id="pay_que_search_btn"><i
                                    class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Payment Queue</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><label for="vendor_name" class="">Party/Vendor Name </label></div>
                        <div class="col-md-9 pl-0">
                            <input type="text" id="vendor_name" class="form-control form-control-sm" name="vendor_name"
                                   placeholder="" readonly/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><label for="vendor_category" class="">Party/Vendor Category </label></div>
                        <div class="col-md-9 pl-0">
                            <input type="text" id="vendor_category" class="form-control form-control-sm"
                                   name="vendor_category"
                                   placeholder="" readonly/>
                        </div>
                    </div>
                    <div class="row text-center mt-1">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_bills_payable">O/S Bills Payable(A)</label>
                                <input type="text" id="os_bills_payable" class="form-control form-control-sm text-right"
                                       name="os_bills_payable" placeholder="" readonly>
                            </div>
                        </div>
                        {{--<div class="col-md-3">
                            <div class="form-group">
                                <label for="os_advances">O/S Advances</label>
                                <input type="text" id="os_advances" class="form-control form-control-sm" name="os_advances"
                                       placeholder="" readonly>
                            </div>
                        </div>--}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_prepayments">O/S Prepayments(B)</label>
                                <input type="text" id="os_prepayments" class="form-control form-control-sm text-right"
                                       name="os_prepayments"
                                       placeholder="" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_security_deposits">O/S Security Deposits(C)</label>
                                <input type="text" id="os_security_deposits"
                                       class="form-control form-control-sm text-right" name="os_security_deposits"
                                       placeholder="" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="good_for_payment">Good For Payment(A-B)</label>
                                <input type="text" id="good_for_payment" class="form-control form-control-sm text-right"
                                       name="good_for_payment" placeholder="" readonly>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border  pl-1 pr-1 inv_ref_div">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Reference (for Bill Payment)
                    </legend>
                    <input type="hidden" name="selected_pay_queue_inv_id" id="selected_pay_queue_inv_id">
                    <div class=" table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                {{--<th>select</th>
                                <th>Document No</th>
                                <th>Transaction date</th>
                                <th>Invoice Type</th>
                                <th>Invoice Amount</th>
                                <th>Payable Amount</th>
                                <th>Payment Due Amount</th>--}}
                                <th>select</th>
                                <th>Document No</th>
                                <th>Document date</th>
                                <th>Document Ref</th>
                                <th>Invoice Type</th>
                                <th>Invoice Amount</th>
                                <th>Payable Amount</th>
                                <th>Due Amount</th>
                                <th>Payment Amount</th>
                            </tr>
                            </thead>
                            <tbody id="invRefList"></tbody>
                        </table>
                    </div>
                </fieldset>
                {{--TODO: Add this sectionstart : Pavel-09-05-22 --}}
                <fieldset class="border  pl-1 pr-1 inv_ref_tax_div">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Reference (for Tax Payment)
                    </legend>
                    <input type="hidden" name="selected_tax_pay_queue_inv_id" id="selected_tax_pay_queue_inv_id">

                    <div class="row">
                        <div class="col-md-2"><label for="tax_vendor_id" class="required">Tax Party/Vendor ID </label>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="number" id="tax_vendor_id" class="form-control form-control-sm"
                                   name="tax_vendor_id"
                                   placeholder=""
                                   onfocusout="addZerosInAccountId(this)"
                                   onkeyup="resetField(['#tax_vendor_name','#bank_pay_amt_ccy', '#bank_pay_amt_lcy', '#adj_pre_pay_amt_ccy', '#adj_pre_pay_amt_lcy','#fine_forfeiture_ccy', '#fine_forfeiture_lcy', '#pay_adj_amt_ccy','#pay_adj_amt_lcy']);resetTablesDynamicRow('#inv_ref_tax_pay_table')"
                            />
                        </div>
                        <div class="col-md-7">
                            <button type="button" class="btn btn-sm btn-info mb-1" id="tax_vendor_search_btn">
                                <i class="bx bx-search font-size-small"></i>
                                <span class="align-middle ml-25">Search</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary mb-1" id="tax_pay_que_search_btn"><i
                                    class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Tax Payment Queue</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2"><label for="tax_vendor_name" class="">Tax Party/Vendor Name </label></div>
                        <div class="col-md-9 pl-0">
                            <input type="text" id="tax_vendor_name" class="form-control form-control-sm"
                                   name="tax_vendor_name"
                                   placeholder="" readonly/>
                        </div>
                    </div>


                    <div class=" table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_ref_tax_pay_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                <th>select</th>
                                <th>Document No</th>
                                <th>Document date</th>
                                <th>Document Ref</th>
                                <th>Invoice Amount</th>
                                <th>Tax Amount</th>
                                <th>Due Amount</th>
                                <th>Payment Amount</th>
                            </tr>
                            </thead>
                            <tbody id="invRefTaxPayList"></tbody>
                        </table>
                    </div>
                </fieldset>
                {{--TODO: Add this sectionend : Pavel-09-05-22 --}}
                <fieldset class="border  pl-1 pr-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Bill Payment/Adjustment</legend>

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
                        <label class="col-md-2 col-form-label " for="account_balance">Account Balance</label>
                        <input name="account_balance" class="form-control form-control-sm text-right-align col-md-2"
                               value=""
                               id="account_balance" readonly tabindex="-1">
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="currency" class="">Currency</label></div>
                        <div class="col-md-2 form-group pl-0">
                            <input type="text" id="currency" class="form-control form-control-sm" name="currency"
                                   placeholder=""
                                   readonly/>
                        </div>
                        <label class="offset-4 col-md-2 col-form-label" for="authorized_balance">Authorized
                            Balance</label>
                        <input name="authorized_balance" class="form-control form-control-sm text-right-align col-md-2"
                               value=""
                               id="authorized_balance" readonly tabindex="-1">
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="exc_rate" class="">Exchange Rate</label></div>
                        <div class="col-md-2 form-group pl-0">
                            {{--<input class="form-control form-control-sm" id="exc_rate" name="exc_rate" type="number" maxlength="17" oninput="maxLengthValid(this)" min="0" required value="0" step="0.01" />--}}
                            <input type="number" id="exc_rate" value="0" class="form-control form-control-sm exc_rate"
                                   name="exc_rate" value="0" placeholder="" readonly/>
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
                        {{--<div class="col-md-2"><label for="bank_pay_amt_ccy" class="">Bank Payment </label></div>--}}
                        <div class="col-md-2"><label for="bank_pay_amt_ccy" class="">Payment Amount</label></div>
                        <div class="col-md-3 form-group pl-0">
                            {{--<input readonly class="form-control form-control-sm text-right-align" id="bank_pay_amt_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="bank_pay_amt_ccy" min="0" step="0.01" type="number">--}}
                            <input type="text" class="form-control form-control-sm text-right-align"
                                   id="bank_pay_amt_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   name="bank_pay_amt_ccy" readonly>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="bank_pay_amt_lcy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="bank_pay_amt_lcy" min="0" step="0.01" type="number" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="adj_pre_pay_amt_ccy" class="">Adjust Prepayments </label>
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            {{--<input class="form-control form-control-sm text-right-align" id="adj_pre_pay_amt_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="adj_pre_pay_amt_ccy" min="0" step="0.01" type="number">--}}
                            <input type="text" class="form-control form-control-sm text-right-align"
                                   id="adj_pre_pay_amt_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   name="adj_pre_pay_amt_ccy">
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="adj_pre_pay_amt_lcy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="adj_pre_pay_amt_lcy" min="0" step="0.01" type="number" readonly>
                            {{--<input type="number" id="adj_pre_pay_amt_lcy" value="0" class="form-control form-control-sm text-right-align" name="adj_pre_pay_amt_lcy" placeholder="" readonly/>--}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"><label for="fine_forfeiture_ccy" class="">Fine/Forfeiture </label></div>
                        <div class="col-md-3 form-group pl-0">
                            {{--<input class="form-control form-control-sm text-right-align" id="fine_forfeiture_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="fine_forfeiture_ccy" min="0" step="0.01" type="number">--}}
                            <input type="text" class="form-control form-control-sm text-right-align"
                                   id="fine_forfeiture_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   name="fine_forfeiture_ccy">
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="fine_forfeiture_lcy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)"
                                   name="fine_forfeiture_lcy" min="0" step="0.01" type="number" readonly>
                            {{--<input type="number" id="adj_pre_pay_amt_lcy" value="0" class="form-control form-control-sm text-right-align" name="adj_pre_pay_amt_lcy" placeholder="" readonly/>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="pay_adj_amt_ccy" class="">Total Amount </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="pay_adj_amt_ccy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)" readonly
                                   name="pay_adj_amt_ccy" min="0" step="0.01" type="number">
                        </div>
                        <div class="col-md-3 form-group pl-0">
                            <input class="form-control form-control-sm text-right-align" id="pay_adj_amt_lcy"
                                   maxlength="17"
                                   oninput="maxLengthValid(this)" readonly
                                   name="pay_adj_amt_lcy" min="0" step="0.01" type="number">
                            {{--<input type="number" id="pay_adj_amt_lcy" value="0" class="form-control form-control-sm text-right-align"
                                   name="pay_adj_amt_lcy" placeholder="" readonly/>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="cheque_no" class="">Cheque No </label></div>
                        <div class="col-md-3 form-group pl-0">
                            <input type="text" id="cheque_no" class="form-control form-control-sm" name="cheque_no"
                                   placeholder=""
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="cheque_date" class="">Cheque Date</label></div>
                        <div class="col-md-3 form-group pl-0">
                            <div class="input-group date cheque_date" id="cheque_date" data-target-input="nearest">
                                <input type="text" name="cheque_date" id="cheque_date_field" autocomplete="off"
                                       class="form-control form-control-sm datetimepicker-input cheque_date"
                                       data-target="#cheque_date" data-toggle="datetimepicker"
                                       value="{{ old('cheque_date', '') }}"
                                       data-predefined-date="{{ old('cheque_date', '') }}"
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append cheque_date" data-target="#cheque_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <section class="col-md-12 pl-0 pr-0">
                    @include('gl.common_file_upload')
                </section>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span
                                class="align-middle ml-25">Save</span></button>
                        <button type="button" class="btn btn-dark" id="reset_form"><i class="bx bx-reset"></i><span
                                class="align-middle ml-25 ml-75">Reset</span></button>
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

    @include('ap.ap-common.common_vendor_list_modal')

    <!-Invoice Bill Payment Queue Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-Button trigger for Extra Large modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="invBillPayQueueModal" tabindex="-1" role="dialog"
                         aria-labelledby="invBillPayQueueModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="invBillPayQueueModalLabel">Invoice/Bill Payment
                                        Queue</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card shadow-none">
                                        <div class="row">
                                            <div class="col-md-2"><label class="required text-bold-700 font-small-3"
                                                                         for="selected_party_sub_ledger">Party-Sub
                                                    Ledger</label></div>
                                            <div class="col-md-5 form-group pl-0">
                                                <input type="text" id="selected_party_sub_ledger"
                                                       class="form-control form-control-sm" placeholder="" disabled/>
                                            </div>
                                        </div>
                                        <hr class="mb-0">
                                        <div class="table-responsive">
                                            <table id="invoice-bill-payment-queue-list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Document Date</th>
                                                    <th>Document No</th>
                                                    <th>Invoice Type</th>
                                                    <th>Invoice Amount</th>
                                                    <th>Payable Amount</th>
                                                    <th>Party ID</th>
                                                    <th>Party/Vendor Name</th>
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
    </section>
    <!-Invoice Bill Payment Queue Modal end -->

    {{--TODO: Add this modalstart : Pavel-09-05-22 --}}
    <!-Invoice Bill Tax Payment Queue Modal start -->
    <section id="modal-sizes-tax">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-Button trigger for Extra Large modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="invTaxPayQueueModal" tabindex="-1" role="dialog"
                         aria-labelledby="invTaxPayQueueModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="invTaxPayQueueModalLabel">Invoice/Bill Payment
                                        Queue</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card shadow-none">
                                        <div class="row">
                                            <div class="col-md-2"><label class="required text-bold-700 font-small-3"
                                                                         for="tax_selected_party_sub_ledger">Party-Sub
                                                    Ledger</label></div>
                                            <div class="col-md-6 form-group pl-0">
                                                <input type="text" id="tax_selected_party_sub_ledger"
                                                       class="form-control form-control-sm" placeholder="" disabled/>
                                            </div>
                                        </div>
                                        <hr class="mb-0">
                                        <div class="table-responsive">
                                            <table id="invoice-tax-payment-queue-list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Document No</th>
                                                    <th>Document Date</th>
                                                    <th>Invoice Type</th>
                                                    <th>Invoice Amount</th>
                                                    <th>Tax Amount</th>
                                                    <th>Party ID</th>
                                                    <th>Party/Vendor Name</th>
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
    </section>
    <!-Invoice Bill Tax Payment Queue Modal end -->
    {{--TODO: Add this modalend : Pavel-09-05-22 --}}


@endsection

@section('footer-script')
    <script type="text/javascript">

        /**** Start calender logic ****/
        /********Added on: 06/06/2022, sujon**********/
        function setPeriodCurrentDate() {
            $("#posting_date_field").val($("#period :selected").data("currentdate"));
            $("#document_date_field").val($("#period :selected").data("currentdate"));
            $("#cheque_date_field").val($("#period :selected").data("currentdate"));
        }

        //setPeriodCurrentDate()
        /********End**********/
        function dateValidation() {

            let postingCalendarClickCounter = 0;
            let chequeCalendarClickCounter = 0;
            let documentCalendarClickCounter = 0;

            $("#period").on('change', function () {
                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                $("#document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                    documentDateClickCounter = 0;
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
                        newDueDate = moment($("#posting_date_field").val(), "YYYY-MM-DD");
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
                        newDueDate = moment($("#document_date_field").val(), "YYYY-MM-DD");
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

        $("#th_fiscal_year").on('change', function () {
            getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
        });
        getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }

        /**** End calender logic ****/

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                $("#bill_reg_id").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }

        function partySubLedger() {
            $("#party_sub_ledger").on("change", function () {
                //e.preventDefault();
                let partySubLedgerId = $('#party_sub_ledger').val();

                /** Block this if elsestart : Pavel-09-05-22 **/
                /*if ( ("{{--{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}--}}" != partySubLedgerId) && ("{{--{{\App\Enums\Common\GlSubsidiaryParams::VAT_PAYABLE}}--}}" != partySubLedgerId) ) {

                     $('.inv_ref_div').removeClass('d-none');
                     $("#vendor_id").prop("readonly", false);
                     $('#vendor_search_btn').removeClass('d-none');
                     $('#pay_que_search_btn').removeClass('d-none');
                 } else {

                     $('.inv_ref_div').addClass('d-none');
                     $("#vendor_id").prop("readonly", true);
                     $('#vendor_search_btn').addClass('d-none');
                     $('#pay_que_search_btn').addClass('d-none');
                 }*/
                /** Add this if elseend : Pavel-09-05-22 **/

                /** Add this if elsestart : Pavel-09-05-22 **/

                if ("{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}" == partySubLedgerId) {
                    $('.inv_ref_div').addClass('d-none');
                    $('.inv_ref_tax_div').removeClass('d-none');
                    $("#vendor_id").prop("readonly", true);
                    //$("#bank_pay_amt_ccy").prop("readonly", true);
                    $("#adj_pre_pay_amt_ccy").prop("readonly", true);
                    $("#fine_forfeiture_ccy").prop("readonly", true);
                    $('#vendor_search_btn').addClass('d-none');
                    $('#pay_que_search_btn').addClass('d-none');
                    $('#tax_pay_que_search_btn').removeClass('d-none');

                    vendorWiseVatTaxInfo(partySubLedgerId);

                } else if ("{{\App\Enums\Common\GlSubsidiaryParams::VAT_PAYABLE}}" == partySubLedgerId) {
                    $('.inv_ref_div').addClass('d-none');
                    $('.inv_ref_tax_div').addClass('d-none');
                    $("#vendor_id").prop("readonly", true);
                    //$("#bank_pay_amt_ccy").prop("readonly", true);
                    $("#adj_pre_pay_amt_ccy").prop("readonly", true);
                    $("#fine_forfeiture_ccy").prop("readonly", true);
                    $('#vendor_search_btn').addClass('d-none');
                    $('#pay_que_search_btn').addClass('d-none');
                    $('#tax_pay_que_search_btn').addClass('d-none');

                    vendorWiseVatTaxInfo(partySubLedgerId);

                } else {
                    $('.inv_ref_div').removeClass('d-none');
                    $('.inv_ref_tax_div').addClass('d-none');
                    $("#vendor_id").prop("readonly", false);
                    //$("#bank_pay_amt_ccy").prop("readonly", false);
                    $("#adj_pre_pay_amt_ccy").prop("readonly", false);
                    $("#fine_forfeiture_ccy").prop("readonly", false);
                    $('#vendor_search_btn').removeClass('d-none');
                    $('#pay_que_search_btn').removeClass('d-none');
                    $('#tax_pay_que_search_btn').addClass('d-none');
                }
                /** Add this if elseend : Pavel-09-05-22 **/

                /** Update & Add Reset Fields Function: Pavel-09-05-22 **/
                resetField(['#vendor_id', '#vendor_name', '#vendor_category', '#os_bills_payable', '#os_security_deposits', '#os_prepayments', '#good_for_payment', '#tax_vendor_id', '#tax_vendor_name', '#selected_tax_pay_queue_inv_id', '#selected_tax_pay_queue_inv_id', '#bank_pay_amt_ccy', '#bank_pay_amt_lcy', '#adj_pre_pay_amt_ccy', '#adj_pre_pay_amt_lcy', '#fine_forfeiture_ccy', '#fine_forfeiture_lcy', '#pay_adj_amt_ccy', '#pay_adj_amt_lcy']);
                invRefList();
                invRefTaxPayList();
            });
        }

        /**** INVOICE REFERENCE FOR BILL PAYMENT FUNCTION. START  ****/
        function vendorSearch() {
            $("#vendor_search_btn").on("click", function () {
                //e.preventDefault();
                let vendorId = $("#vendor_id").val();
                let partySubLedgerId = $('#party_sub_ledger').val();

                if (nullEmptyUndefinedChecked(partySubLedgerId)) {
                    $("#party_sub_ledger").notify("Please Select Party-Sub Ledger", "error");
                } else if (!nullEmptyUndefinedChecked(vendorId)) {
                    invRefList();
                    getVendorDetail(vendorId);
                } else {
                    $("#vendorListModal").modal('show');
                }
            });
        }

        function vendorList() {
            $("#vendor_search_form").on('submit', function (e) {
                e.preventDefault();

                $('#vendorSearch').data("dt_params", {
                    vendorType: $('#search_vendor_type :selected').val(),
                    vendorCategory: $('#search_vendor_category :selected').val(),
                    vendorName: $('#search_vendor_name').val(),
                    vendorShortName: $('#search_vendor_short_name').val(),
                }).DataTable().draw();
                //accountTable.draw();
            });


            $('#vendorSearch').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: APP_URL + '/account-payable/ajax/vendor-search-datalist',
                    'type': 'POST',
                    'headers': {
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
                    //{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'vendor_id', "name": 'vendor_id'},
                    {"data": "name"},
                    {"data": "short_name"},
                    {"data": "category"},
                    {"data": "action", "orderable": false}
                ],
            });
        }

        $(document).on("click", '.vendorSelect', function (e) {
            //e.preventDefault();
            let vendor_id = $(this).data('vendor');
            getVendorDetail(vendor_id);
        });

        function invBillPayQueueList() {
            $("#pay_que_search_btn").on("click", function () {
                //e.preventDefault();
                let partySubLedgerId = $('#party_sub_ledger').val();

                if (nullEmptyUndefinedChecked(partySubLedgerId)) {
                    $("#party_sub_ledger").notify("Please Select Party-Sub Ledger", "error");
                } else {
                    $("#selected_party_sub_ledger").val($('#party_sub_ledger option:selected').text());
                    $("#invBillPayQueueModal").modal('show');
                    oTable.draw();
                }
            });

            let oTable = $('#invoice-bill-payment-queue-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/account-payable/invoice-bill-payment-queue-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.party_sub_ledger_id = $('#party_sub_ledger').val();
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "document_date"},
                    {"data": "document_no"},
                    {"data": "invoice_type_name"},
                    {"data": "invoice_amount"},
                    {"data": "payable_amount"},
                    {"data": "vendor_id"},
                    {"data": "vendor_name"},
                    {"data": "select"}
                ],

                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        }

        $(document).on("click", '.payment-queue-inv', function (e) {
            //e.preventDefault();
            let invoiceId = $(this).attr('id');
            let vendor_id = $(this).data('vendor');
            let exc_rate = parseFloat($('#exc_rate').val());
            let pay_adj_amt_ccy = parseFloat($(this).closest("tr").find("td:eq(5)").text());

            $("#selected_pay_queue_inv_id").val(invoiceId);
            getVendorDetail(vendor_id);
            getCcyLcyCalculation(pay_adj_amt_ccy, exc_rate);
        });

        function getVendorDetail(vendor_id) {
            let partySubLedgerId = $('#party_sub_ledger').val();
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-with-outstanding-balance',
                data: {vendorId: vendor_id}
            });

            request.done(function (d) {
                //console.log(d);
                if ("{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}" == partySubLedgerId) {
                    if ($.isEmptyObject(d)) {
                        $("#tax_vendor_id").notify("Tax Vendor id not found", "error");
                        resetField(['#tax_vendor_id', '#tax_vendor_name', 'selected_tax_pay_queue_inv_id']);
                    } else {
                        $('#tax_vendor_id').val(d.vendor_id);
                        $('#tax_vendor_name').val(d.vendor_name);

                        invRefTaxPayList();
                        $("#vendorListModal").modal('hide');
                    }
                } else {
                    if ($.isEmptyObject(d)) {
                        $("#vendor_id").notify("Vendor id not found", "error");
                        resetField(['#vendor_id', '#vendor_name', '#vendor_category', '#os_bills_payable', '#os_security_deposits', '#os_prepayments', '#good_for_payment', 'selected_pay_queue_inv_id']);
                    } else {
                        $('#vendor_id').val(d.vendor_id);
                        $('#vendor_name').val(d.vendor_name);
                        $('#vendor_category').val(d.vendor_category_name);
                        $('#os_bills_payable').val(d.bills_payable);
                        $('#os_security_deposits').val(d.security_deposit_payable);
                        $('#os_prepayments').val(d.os_prepayment);
                        $('#good_for_payment').val(d.good_for_payment);

                        invRefList();
                        $("#vendorListModal").modal('hide');
                        $("#invBillPayQueueModal").modal('hide');
                    }
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
                url: APP_URL + '/account-payable/ajax/invoice-reference-list',
                data: $('#invoice-bill-pay-form').serialize(),
                success: function (data) {
                    $('#invRefList').html(data.html);
                    //getInvRefTotal(true, ($("#selected_pay_queue_inv_id").val()) );
                    resetField(['#selected_pay_queue_inv_id']);
                },
                error: function (data) {
                    alert('error');
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
            let totalDueAmt = 0;
            let exc_rate = parseFloat($('#exc_rate').val());
            let dueAmt = $(selector).closest("tr").find("td:eq(7)").text();
            let check = isInit ? ".inv-ref-check" : ".inv-ref-check:checked";
            $(check).each(function () {
                //totalDueAmt += Number($(this).closest("tr").find("td:eq(7)").text());
                totalDueAmt += Number($(this).closest("tr").find(".inv-ref-pay-amt").val());
            });

            if ($(selector).prop("checked")) {
                $(selector).removeClass("bg-primary").addClass("bg-success");
                $(selector).closest("tr").find(".inv-ref-pay-amt").prop("disabled", false);
                $(selector).closest("tr").find(".inv-ref-pay-amt").focus();
            } else {
                $(selector).removeClass("bg-success").addClass("bg-primary");
                $(selector).closest("tr").find(".inv-ref-pay-amt").prop("disabled", true).val(dueAmt);
            }

            if (totalDueAmt == 0) {
                $('#total_due_amt').html('0');
                getCcyLcyCalculation(totalDueAmt, exc_rate);
            } else {
                $('#total_due_amt').html(totalDueAmt.toFixed(2));
                getCcyLcyCalculation(totalDueAmt, exc_rate);
            }
        }

        function invRefAmtSum() {
            $(document).on("keyup", '.inv-ref-pay-amt', function (e) {
                //e.preventDefault();
                let payAmt = $(this).val();
                let dueAmt = $(this).closest("tr").find("td:eq(7)").text();
                getInvRefAmtTotal();

                if (Number(payAmt) > Number(dueAmt)) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Bill payment amount cannot be more than ' + payAmt,
                        type: 'warning',
                    });
                    $(this).closest('tr').find(".inv-ref-pay-amt").val('0');
                    getInvRefAmtTotal();
                }
            });
        }

        function getInvRefAmtTotal() {
            let totalDueAmt = 0;
            let exc_rate = parseFloat($('#exc_rate').val());

            $('.inv-ref-pay-amt').each(function () {
                if ($(this).closest("tr").find(".inv-ref-pay-amt").prop('disabled') == false) {
                    totalDueAmt += Number($(this).closest("tr").find(".inv-ref-pay-amt").val());
                }
            });

            if (totalDueAmt == 0) {
                $('#total_due_amt').html('0');
                getCcyLcyCalculation(totalDueAmt, exc_rate);
            } else {
                $('#total_due_amt').html(totalDueAmt.toFixed(2));
                getCcyLcyCalculation(totalDueAmt, exc_rate);
            }
        }

        /**** INVOICE REFERENCE FOR BILL PAYMENT FUNCTION. END  ****/

        /**** INVOICE REFERENCE FOR TAX PAYMENT FUNCTION. START  ****/
        function taxVendorSearch() {
            $("#tax_vendor_search_btn").on("click", function () {
                //e.preventDefault();
                let taxVendorId = $("#tax_vendor_id").val();
                let partySubLedgerId = $('#party_sub_ledger').val();

                if (nullEmptyUndefinedChecked(partySubLedgerId)) {
                    $("#tax_vendor_id").notify("Please Select Party-Sub Ledger", "error");
                } else if (!nullEmptyUndefinedChecked(taxVendorId)) {
                    invRefTaxPayList();
                    getVendorDetail(taxVendorId);
                } else {
                    $("#vendorListModal").modal('show');
                }
            });
        }

        function invRefTaxPayList() {
            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/account-payable/ajax/invoice-reference-tax-pay-list',
                data: $('#invoice-bill-pay-form').serialize(),

                success: function (data) {
                    $('#invRefTaxPayList').html(data.html);
                    //getInvRefTotal(true, ($("#selected_pay_queue_inv_id").val()) );
                    resetField(['#selected_tax_pay_queue_inv_id']);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        /** Add this all partstart : Pavel-09-05-22 **/
        function vendorWiseVatTaxInfo(partySubLedgerId) {
            $.ajax({
                type: 'GET',
                url: APP_URL + '/account-payable/ajax/vendor-wise-vat-tax-info',
                data: {party_sub_ledger_id: partySubLedgerId},
                success: function (data) {
                    $("#vendor_id").val(data.vendor_id);
                    $("#vendor_name").val(data.vendor_name);
                    $("#vendor_category").val(data.vendor_category_name);
                    $("#os_bills_payable").val(data.bills_payable);
                    $("#os_prepayments").val(data.os_prepayment);
                    $("#os_security_deposits").val(data.security_deposit_payable);
                    $("#good_for_payment").val(data.good_for_payment);

                    if ("{{\App\Enums\Common\GlSubsidiaryParams::VAT_PAYABLE}}" == partySubLedgerId) {
                        let pay_adj_amt_ccy = nullEmptyUndefinedChecked(data.good_for_payment) ? 0 : parseFloat(data.good_for_payment);
                        let exc_rate = parseFloat($('#exc_rate').val());
                        getCcyLcyCalculation(pay_adj_amt_ccy, exc_rate);
                    } else if ("{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}" == partySubLedgerId) {
                        invRefTaxPayList();
                    }

                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function invTaxPayQueueList() {
            $("#tax_pay_que_search_btn").on("click", function () {
                //e.preventDefault();
                let partySubLedgerId = $('#party_sub_ledger').val();

                if (nullEmptyUndefinedChecked(partySubLedgerId)) {
                    $("#party_sub_ledger").notify("Please Select Party-Sub Ledger", "error");
                } else {
                    $("#tax_selected_party_sub_ledger").val($('#party_sub_ledger option:selected').text());
                    $("#invTaxPayQueueModal").modal('show');
                    oTable.draw();
                }
            });

            let oTable = $('#invoice-tax-payment-queue-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/account-payable/invoice-bill-payment-tax-queue-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    /*data: function (params) {
                        params.party_sub_ledger_id = $('#party_sub_ledger').val();
                    }*/
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "document_no"},
                    {"data": "document_date"},
                    {"data": "invoice_type_name"},
                    {"data": "invoice_amount"},
                    {"data": "tax_amount"},
                    {"data": "vendor_id"},
                    {"data": "vendor_name"},
                    {"data": "select"}
                ],

                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        }

        $(document).on("click", '.tax-pay-queue-inv', function (e) {
            //e.preventDefault();
            let invoiceId = $(this).attr('id');
            let pay_adj_amt_ccy = parseFloat($(this).closest("tr").find("td:eq(5)").text());
            let tax_party_id = $(this).closest("tr").find("td:eq(6)").text();
            let tax_party_name = $(this).closest("tr").find("td:eq(7)").text();
            let exc_rate = parseFloat($('#exc_rate').val());

            $("#tax_vendor_id").val(tax_party_id);
            $("#tax_vendor_name").val(tax_party_name);
            $("#selected_tax_pay_queue_inv_id").val(invoiceId);
            $("#invTaxPayQueueModal").modal('hide');
            invRefTaxPayList();
            getCcyLcyCalculation(pay_adj_amt_ccy, exc_rate);

        });

        function invRefTaxSum() {
            $(document).on("click", '.inv-ref-tax-pay-check', function (e) {
                //e.preventDefault();
                getInvRefTaxTotal(false, this);
            });
            getInvRefTaxTotal(true, this);
        }

        function getInvRefTaxTotal(isInit, selector) {
            let totalDueTaxAmt = 0;
            let exc_rate = parseFloat($('#exc_rate').val());
            let dueTaxAmt = $(selector).closest("tr").find("td:eq(6)").text();
            let check = isInit ? ".inv-ref-tax-pay-check" : ".inv-ref-tax-pay-check:checked";

            $(check).each(function () {
                //totalDueTaxAmt += Number($(this).closest("tr").find("td:eq(6)").text());
                totalDueTaxAmt += Number($(this).closest("tr").find(".tax-payment-amount").val());
            });

            if ($(selector).prop("checked")) {
                $(selector).removeClass("bg-primary").addClass("bg-success");
                $(selector).closest("tr").find(".tax-payment-amount").prop("disabled", false);
                $(selector).closest("tr").find(".tax-payment-amount").focus();
            } else {
                $(selector).removeClass("bg-success").addClass("bg-primary");
                $(selector).closest("tr").find(".tax-payment-amount").prop("disabled", true).val(dueTaxAmt);
            }

            if (totalDueTaxAmt == 0) {
                $('#total_due_tax_amt').html('0');
                getCcyLcyCalculation(totalDueTaxAmt, exc_rate);
            } else {
                $('#total_due_tax_amt').html(totalDueTaxAmt);
                getCcyLcyCalculation(totalDueTaxAmt, exc_rate);
            }
        }

        function invRefTaxAmtSum() {
            $(document).on("keyup", '.tax-payment-amount', function (e) {
                //e.preventDefault();
                let taxAmt = $(this).val();
                let dueTaxAmt = $(this).closest("tr").find("td:eq(6)").text();
                getInvRefTaxAmtTotal();

                if (Number(taxAmt) > Number(dueTaxAmt)) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Tax payment amount cannot be more than ' + taxAmt,
                        type: 'warning',
                    });
                    $(this).closest('tr').find(".tax-payment-amount").val('0');
                    getInvRefTaxAmtTotal();
                }
            });
        }

        function getInvRefTaxAmtTotal() {
            let totalDueTaxAmt = 0;
            let exc_rate = parseFloat($('#exc_rate').val());
            $('.tax-payment-amount').each(function () {
                if ($(this).closest("tr").find(".tax-payment-amount").prop('disabled') == false) {
                    totalDueTaxAmt += Number($(this).closest("tr").find(".tax-payment-amount").val());
                }
            });

            if (totalDueTaxAmt == 0) {
                $('#total_due_tax_amt').html('0');
                getCcyLcyCalculation(totalDueTaxAmt, exc_rate);
            } else {
                $('#total_due_tax_amt').html(totalDueTaxAmt);
                getCcyLcyCalculation(totalDueTaxAmt, exc_rate);
            }
        }

        /** INVOICE REFERENCE FOR TAX PAYMENT FUNCTION. END  **/

        function bankAcc() {
            $('#bank_id').change(function (e) {
                e.preventDefault();
                resetField(["#currency", "#exc_rate", "#account_balance", "#authorized_balance"]);
                let glAccId = $(this).val();
                $.ajax({
                    type: 'GET',
                    /*'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },*/
                    url: APP_URL + '/account-payable/ajax/gl-acc-wise-coa',
                    data: {gl_acc_id: glAccId},
                    success: function (data) {
                        /** Add this partstart : Pavel-09-05-22 **/

                        let pay_adj_amt_ccy = parseFloat($('#bank_pay_amt_ccy').val());
                        let exc_rate = parseFloat(data.exchange_rate);

                        $("#currency").val(data.currency_code);
                        $("#exc_rate").val(data.exchange_rate);
                        $("#account_balance").val(data.account_balance);
                        $("#authorized_balance").val(data.authorize_balance);

                        getCcyLcyCalculation(pay_adj_amt_ccy, exc_rate);

                        /** Add this partend : Pavel-09-05-22 **/

                        if (data.currency_code == "{{\App\Enums\Common\Currencies::O_BD}}") {
                            $("#exc_rate").prop("readonly", true);
                        } else {
                            $("#exc_rate").prop("readonly", false);
                        }
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            });
        }

        function getCcyLcyCalculation(pay_adj_amt_ccy, exc_rate) {
            let fine_forfeiture_ccy = nullEmptyUndefinedChecked($('#fine_forfeiture_ccy').val()) ? 0 : parseFloat($('#fine_forfeiture_ccy').val());
            let adj_pre_pay_amt_ccy = nullEmptyUndefinedChecked($('#adj_pre_pay_amt_ccy').val()) ? 0 : parseFloat($('#adj_pre_pay_amt_ccy').val());

            //$("#bank_pay_amt_ccy").val(bank_pay_amt_ccy);
            $("#pay_adj_amt_ccy").val(pay_adj_amt_ccy.toFixed(2));
            $("#adj_pre_pay_amt_ccy").val(adj_pre_pay_amt_ccy);
            $("#fine_forfeiture_ccy").val(fine_forfeiture_ccy);

            //$('#bank_pay_amt_lcy').val(bank_pay_amt_ccy * exc_rate);
            $('#pay_adj_amt_lcy').val((pay_adj_amt_ccy.toFixed(2)) * exc_rate);
            $('#adj_pre_pay_amt_lcy').val(adj_pre_pay_amt_ccy * exc_rate);
            $('#fine_forfeiture_lcy').val(fine_forfeiture_ccy * exc_rate);


            //$('#pay_adj_amt_ccy').val((bank_pay_amt_ccy + adj_pre_pay_amt_ccy + fine_forfeiture_ccy).toFixed(2));
            //$('#pay_adj_amt_lcy').val((parseFloat($('#bank_pay_amt_lcy').val()) + parseFloat($('#adj_pre_pay_amt_lcy').val()) + parseFloat($('#fine_forfeiture_lcy').val())).toFixed(2));

            $('#bank_pay_amt_ccy').val((pay_adj_amt_ccy(adj_pre_pay_amt_ccy + fine_forfeiture_ccy)).toFixed(2));
            $('#bank_pay_amt_lcy').val((parseFloat($('#pay_adj_amt_lcy').val())(parseFloat($('#adj_pre_pay_amt_lcy').val()) + parseFloat($('#fine_forfeiture_lcy').val()))).toFixed(2));

        }

        /** Add this all partend : Pavel-09-05-22 **/

        function ccyLcyCalculation() {
            $('#bank_pay_amt_ccy, #adj_pre_pay_amt_ccy, #fine_forfeiture_ccy').keyup(function (e) {
                //console.log($("#d_amount_ccy").val(),$("#d_exchange_rate").val());
                e.preventDefault();
                let pay_adj_amt_ccy = nullEmptyUndefinedChecked($('#pay_adj_amt_ccy').val()) ? 0 : parseFloat($('#pay_adj_amt_ccy').val());
                let bank_pay_amt_ccy = nullEmptyUndefinedChecked($('#bank_pay_amt_ccy').val()) ? 0 : parseFloat($('#bank_pay_amt_ccy').val());
                let fine_forfeiture_ccy = nullEmptyUndefinedChecked($('#fine_forfeiture_ccy').val()) ? 0 : parseFloat($('#fine_forfeiture_ccy').val());
                let adj_pre_pay_amt_ccy = nullEmptyUndefinedChecked($('#adj_pre_pay_amt_ccy').val()) ? 0 : parseFloat($('#adj_pre_pay_amt_ccy').val());

                /*let bank_pay_amt_lcy = Number($('#bank_pay_amt_lcy').val());
                let adj_pre_pay_amt_lcy = Number($('#adj_pre_pay_amt_lcy').val());*/
                let exc_rate = parseFloat($('#exc_rate').val());

                $('#bank_pay_amt_lcy').val(bank_pay_amt_ccy * exc_rate);
                $('#adj_pre_pay_amt_lcy').val(adj_pre_pay_amt_ccy * exc_rate);
                $('#fine_forfeiture_lcy').val(fine_forfeiture_ccy * exc_rate);

                $('#bank_pay_amt_ccy').val((pay_adj_amt_ccy(adj_pre_pay_amt_ccy + fine_forfeiture_ccy)).toFixed(2));
                $('#bank_pay_amt_lcy').val((parseFloat($('#pay_adj_amt_lcy').val())(parseFloat($('#adj_pre_pay_amt_lcy').val()) + parseFloat($('#fine_forfeiture_lcy').val()))).toFixed(2));

            });
        }

        function preSubmitValidateFrom() {
            $('#invoice-bill-pay-form').submit(function (e) {
                //$("#invoice-bill-pay-form").on("submit", function (e) {
                e.preventDefault();
                let form = this;
                /*swal.fire({
                    title: 'Are you sure?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        form.submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })*/
                let bankPayAmt = Number($('#bank_pay_amt_ccy').val()); //Payment Amount
                let adjPrePayAmt = Number($('#adj_pre_pay_amt_ccy').val());
                let payAdjAmtLcy = Number($('#pay_adj_amt_lcy').val()); //Total Amount
                let chequeNo = $('#cheque_no').val();
                let chequeDate = $('#cheque_date_field').val();
                let countPayAdjAmt = 0;
                let countCheck = 0;
                let payAdjAmtLcy2;

                let vendor_id = $('#vendor_id').val();
                let vendor_name = $('#vendor_name').val();
                let party_sub_ledger = $('#party_sub_ledger').val();
                //let chequeNoVal = chequeNo ? chequeNo : 'N/A';
                //let chequeDateVal = chequeDate ? chequeDate : 'N/A';

                /*** Block this validation ***/
                /*$('#inv_ref_table').find('input[type=checkbox]:checked').each(function () {
                    /!*$('input:checkbox').each(function () {*!/
                    /!*if(this.checked && Number($(this).val())>0){
                        alert($(this).val());
                    }*!/
                    //let checkRowAmt = Number($(this).closest("tr").find("td:eq(4)").text());
                    let checkRowAmt = Number($(this).closest("tr").find("td:eq(7)").text());
                    if (payAdjAmtLcy > 0) {
                        payAdjAmtLcy2 = (payAdjAmtLcy checkRowAmt);
                        if ( payAdjAmtLcy2 >= 0 ) {
                            countPayAdjAmt++;
                        } else {
                            countPayAdjAmt = 0;
                        }

                        //alert(payAdjAmtLcy2);
                    }
                    countCheck++;
                });*/
                //alert(countPayAdjAmt+'=='+countCheck);
                /*** Block this validation ***/

                /** Add this if & elseif partstart : Pavel-09-05-22 **/
                if (("{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}" == party_sub_ledger) && nullEmptyUndefinedChecked(bankPayAmt)) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Tax & Payment Amount Can\'t be blank. Please Select at least one for Invoice Reference (Tax Payment) table',
                        type: 'warning',
                    });
                } else if (("{{\App\Enums\Common\GlSubsidiaryParams::VAT_PAYABLE}}" == party_sub_ledger) && nullEmptyUndefinedChecked(bankPayAmt)) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Vat Sub-Ledger Payment Amount Can\'t be blank.',
                        type: 'warning',
                    });
                }
                /** Add this if & elseif partstart : Pavel-09-05-22 **/
                else if (nullEmptyUndefinedChecked(bankPayAmt) && nullEmptyUndefinedChecked(adjPrePayAmt)) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Please Fill Up Bank Payment Or Adjust Prepayments Field.',
                        type: 'warning',
                    });
                } else if (!nullEmptyUndefinedChecked(bankPayAmt) && (nullEmptyUndefinedChecked(chequeNo) || nullEmptyUndefinedChecked(chequeDate))) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Please Fill Up Cheque No And Cheque Date Field.',
                        type: 'warning',
                    });
                } else if (("{{\App\Enums\Common\GlSubsidiaryParams::TAX_PAYABLE}}" != party_sub_ledger) && ("{{\App\Enums\Common\GlSubsidiaryParams::VAT_PAYABLE}}" != party_sub_ledger)) {
                    if (payAdjAmtLcy > Number($('#total_due_amt').text())) {
                        swal.fire({
                            title: 'Sorry...',
                            text: 'Payment Can\'t be more than total payment due',
                            type: 'error',
                        });
                        return false;
                    }
                        /*** Block this validation ***/
                        /*else if (countPayAdjAmt != countCheck) {
                            swal.fire({
                                title: 'Sorry...',
                                text: 'Payment/Adjustment does fulfilled with Invoice References Selections.',
                                type: 'error',
                            });
                        }
                        else if (bankPayAmt > Number($('#account_balance').val())) {
                            swal.fire({
                                title: 'Sorry...',
                                text: 'Bank Payment amount can not be larger than authorize balance.',
                                type: 'error',
                            });
                        }*/
                    /*** Block this validation ***/
                    else {
                        fromSubmit(vendor_id, vendor_name, payAdjAmtLcy);
                    }
                } else {
                    fromSubmit(vendor_id, vendor_name, payAdjAmtLcy);
                }
            });
        }

        function fromSubmit(vendor_id, vendor_name, payAdjAmtLcy) {
            //form.submit();
            /*swal.fire({
                title: 'Are you sure?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it!'
            }).then(function (isConfirm) {
                if (isConfirm.value == true) {
                    form.submit();
                } else if (isConfirm.dismiss == "cancel") {
                    //return false;
                    e.preventDefault();
                }
            });*/
            Swal.fire({
                /*title: 'Are you sure?',
                type: "info",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ok",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1*/
                title: 'Are you sure?',
                //text: '',
                html: 'Submit' + '<br>' +
                    'Party/Vendor ID: ' + vendor_id + '<br>' +
                    'Party/Vendor Name: ' + vendor_name + '<br>' +
                    'Total Settlement Amount: ' + payAdjAmtLcy + '<br>', /*+
                               'Payment Tk: ' + bankPayAmt + ', ' + 'Cheque No: ' + chequeNoVal  + ', ' + 'Cheque Date: ' + chequeDateVal,*/
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it!'
            }).then(function (result) {
                if (result.value) {
                    let request = $.ajax({
                        url: APP_URL + "/account-payable/invoice-bill-payment",
                        data: new FormData($("#invoice-bill-pay-form")[0]),
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
                                $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');

                                /*let url = '{{ route('invoice-bill-payment.index') }}';
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

        function resetForm() {
            $("#reset_form").on('click', function () {
                removeAllAttachments();
                $("#party_sub_ledger").val('').trigger('change');
                resetField(['#narration', '#cheque_no']);
            });
        }

        /********Added on: 07/06/2022, sujon**********/
        function setBillSection() {
            $("#bill_reg_id").change(function (e) {
                $bill_sec_id = $("#bill_reg_id :selected").data('secid');
                $bill_sec_name = $("#bill_reg_id :selected").data('secname');
                if (!nullEmptyUndefinedChecked($bill_sec_id)) {
                    $("#bill_sec_id").html("<option value='" + $bill_sec_id + "'>" + $bill_sec_name + "</option>")
                } else {
                    $("#bill_sec_id").html("<option value=''></option>")
                }
            });
        }

        /********Added on: 07/06/2022, sujon**********/

        $(document).ready(function () {
            resetForm();
            listBillRegister();
            $("#bill_sec_id").trigger('change');
            partySubLedger();
            vendorSearch();
            vendorList();
            taxVendorSearch();
            //invRefList();
            invBillPayQueueList();
            invTaxPayQueueList(); //Add this part: Pavel-09-05-22
            bankAcc();
            invRefSum();
            invRefAmtSum();
            invRefTaxSum();
            invRefTaxAmtSum();
            ccyLcyCalculation();
            dateValidation();
            //datePicker("#cheque_date");
            preSubmitValidateFrom();
            $("#bank_id").trigger('change');    /*****Added on: 07/06/2022, sujon********/

        });

    </script>
@endsection

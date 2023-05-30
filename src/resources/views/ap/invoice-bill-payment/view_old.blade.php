@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-header d-flex justify-content-between align-items-center p-0">
                {{-- <h4 class="card-title"> Add  Chart Of Accounts (COA)</h4>--}}
                <h4><span class="border-bottom-secondary border-bottom-2">Invoice/Bill Payment View</span></h4>
                <a href="{{route('invoice-bill-payment-list.index')}}"><span class="badge badge-primary font-small-4"><i
                            class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{--Workflow steps start--}}
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_AP_PAYMENT, App\Enums\WkReferenceColumn::PAYMENT_ID, $invBillPayInfo->payment_id, \App\Enums\WorkFlowMaster::AP_INVOICE_BILL_PAYMENT_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="invoice-bill-pay-form" enctype="multipart/form-data"
                  action="{{route('invoice-bill-payment.store')}}" method="post">
                @csrf
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Detail View</legend>
                    <input type="hidden" name="payment_id" id="payment_id"
                           value="{{ isset($invBillPayInfo) ? $invBillPayInfo->payment_id : '' }}">

                    <div class="row">
                        <div class="col-md-6">
                            <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Transaction Reference</span>
                                <span class="ml-3">
                                    <input class="form-check-input" type="checkbox" value="" id="chnTransRef"
                                       {{--@if (!isset($roleWiseUser)) disabled @endif--}}
                                        {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AP_MODULE_ID, \App\Enums\WorkFlowRoleKey::AP_INVOICE_BILL_PAYMENT_MAKE,\App\Enums\RolePermissionsKey::CAN_EDIT_AP_PAYMENT_MAKE )) ) ? 'disabled' : '' }}>
                                    <label class="form-check-label font-small-3" for="chnTransRef">
                                        Change Trans Reference
                                    </label>
                                </span>
                            </h6>
                            <div class="viewDocumentRef">
                                <div class="row">
                                    <div class="col-md-4"><label for="batch_id" class="">Batch ID </label></div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" class="form-control form-control-sm" id="batch_id"
                                               value="{{isset($invBillPayInfo->batch_id) ? $invBillPayInfo->batch_id : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal
                                        Year</label>
                                    <div class="col-md-5">
                                        <select required name="th_fiscal_year"
                                                class="form-control form-control-sm required make-readonly-bg"
                                                id="th_fiscal_year">
                                            <option
                                                value="{{ isset($invBillPayInfo) ? $invBillPayInfo->fiscal_year_id : '' }}">{{ isset($invBillPayInfo) ? $invBillPayInfo->fiscal_year_name : '' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="period" class="required col-md-4 col-form-label">Posting
                                        Period</label>
                                    <div class="col-md-5">
                                        <select required name="period"
                                                class="form-control form-control-sm make-readonly-bg" id="period">
                                            <option
                                                data-mindate="{{ isset($invBillPayInfo) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->posting_period_beg_date) : '' }}"
                                                data-maxdate="{{ isset($invBillPayInfo) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->posting_period_end_date) : '' }}"
                                                value="{{ isset($invBillPayInfo) ? $invBillPayInfo->trans_period_id : '' }}">{{ isset($invBillPayInfo) ? $invBillPayInfo->trans_period_name : '' }}</option>

                                        </select>
                                    </div>
                                </div>
                                {{--<div class="row">
                                    <div class="col-md-4"><label for="period" class="">Posting Period </label></div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{isset($invBillPayInfo->trans_period_name) ? $invBillPayInfo->trans_period_name : ''}}"
                                               disabled/>
                                    </div>
                                </div>--}}
                                <div class="row">
                                    <div class="col-md-4"><label for="posting_date" class="">Posting Date </label></div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{isset($invBillPayInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->trans_date) : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="document_date_field" class=" col-md-4 col-form-label">Document
                                        Date</label>
                                    <div class="col-md-5">
                                        <input type="text" readonly class="form-control form-control-sm"
                                               id="document_date"
                                               value="{{\App\Helpers\HelperClass::dateConvert($invBillPayInfo->document_date)}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="document_number" class=" col-md-4 col-form-label">Document
                                        No</label>
                                    <div class="col-md-5">
                                        <input maxlength="25" type="text" readonly
                                               class="form-control form-control-sm"
                                               name="document_number"
                                               id="document_number"
                                               value="{{$invBillPayInfo->document_no}}">
                                    </div>
                                </div>
                            </div>
                            <div class="editDocumentRef d-none">
                                <div class="row">
                                    <div class="col-md-4"><label for="batch_id" class="">Batch ID </label></div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" class="form-control form-control-sm" id="batch_id"
                                               value="{{isset($invBillPayInfo->batch_id) ? $invBillPayInfo->batch_id : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edt_fiscal_year" class="required col-sm-4 col-form-label">Fiscal
                                        Year</label>
                                    <div class="col-md-5">
                                        <select required name="edt_fiscal_year"
                                                class="form-control form-control-sm required"
                                                id="edt_fiscal_year">
                                            @foreach($fiscalYear as $year)
                                                <option
                                                    {{isset($invBillPayInfo) ? ($invBillPayInfo->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '' : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="period" class="required col-md-4 col-form-label">Posting
                                        Period</label>
                                    <div class="col-md-5">
                                        <select required name="edt_period" class="form-control form-control-sm"
                                                id="edt_period">
                                            <option
                                                data-mindate="{{ isset($invBillPayInfo) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->posting_period_beg_date) : '' }}"
                                                data-maxdate="{{ isset($invBillPayInfo) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->posting_period_end_date) : '' }}"
                                                value="{{ isset($invBillPayInfo) ? $invBillPayInfo->trans_period_id : '' }}">{{ isset($invBillPayInfo) ? $invBillPayInfo->trans_period_name : '' }}</option>
                                        </select>
                                    </div>
                                </div>

                                {{--<div class="row d-none">
                                    <div class="col-md-4"><label for="period" class="required">Posting
                                            Period </label>
                                    </div>
                                    <div class="col-md-6 form-group ">
                                        <select name="period" class="form-control form-control-sm" id="period"
                                                required>
                                            --}}{{--<option value="" >Select One</option>--}}{{--
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
                                </div>--}}
                                <div class="form-group row">
                                    <label for="edt_posting_date" class="required col-form-label col-md-4">Posting
                                        Date </label>
                                    <div class="input-group date posting_date col-md-5"
                                         id="edt_posting_date"
                                         data-target-input="nearest">
                                        <input required type="text" autocomplete="off" onkeydown="return false"
                                               name="edt_posting_date"
                                               id="edt_posting_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#edt_posting_date"
                                               data-toggle="datetimepicker"
                                               value="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->trans_date)) }}"
                                               data-predefined-date="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->trans_date)) }}"
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append edt_posting_date" data-target="#edt_posting_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bxs-calendar font-size-small"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edt_document_date_field" class="required col-md-4 col-form-label">Document
                                        Date</label>
                                    <div class="input-group date document_date col-md-5"
                                         id="edt_document_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false" required
                                               name="edt_document_date"
                                               id="edt_document_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#edt_document_date"
                                               data-toggle="datetimepicker"
                                               value="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->document_date)) }}"
                                               data-predefined-date="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->document_date)) }}"
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append edt_document_date"
                                             data-target="#edt_document_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bxs-calendar font-size-small"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="edt_document_number" class="">Document No </label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="edt_document_number" maxlength="50"
                                               class="form-control form-control-sm" name="edt_document_number"
                                               value="{{$invBillPayInfo->document_no}}"
                                               oninput="this.value = this.value.toUpperCase()"
                                               placeholder=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row  mb-25">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <a target="_blank" class="btn btn-sm btn-info cursor-pointer"
                                       @if($invBillPayInfo->cheque_pmt_type_flag == \App\Enums\Ap\ApChequePaymentType::CASH_CHEQUE)
                                       href="{{request()->root()}}/report/render/TRANSACTION_LIST_CASH_CHEQUE_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_ACCOUNT_CASH_WISE.xdo&p_posting_period_id={{$invBillPayInfo->trans_period_id}}&p_trans_batch_id={{$invBillPayInfo->batch_id}}&type=pdf&filename=transaction_list_cash_cheque_wise">
                                    @else
                                       href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$invBillPayInfo->trans_period_id}}&p_trans_batch_id={{$invBillPayInfo->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                                    @endif
                                        <i class="bx bx-printer"></i>Print Voucher
                                    </a>
                                </div>
                            </div>
                            <div class="viewDocumentRef">
                                <div class="form-group row d-flex justify-content-end">
                                    {{--<label for="department" class="col-form-label col-md-3 required  pl-0 pr-0">Dept/Cost Center</label>--}}
                                    <div class="col-md-3 pr-0"><label for="department" class="required">Dept/Cost
                                            Center </label></div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{isset($invBillPayInfo->cost_center_dept_name) ? $invBillPayInfo->cost_center_dept_name : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-3 pr-0"><label for="bill_sec_id" class="required pl-0 pr-0">Bill
                                            Section </label></div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{isset($invBillPayInfo->bill_sec_name) ? $invBillPayInfo->bill_sec_name : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-3 pr-0"><label for="bill_register" class="required">Bill
                                            Register </label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{isset($invBillPayInfo->bill_reg_name) ? $invBillPayInfo->bill_reg_name : ''}}"
                                               disabled/>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-3">
                                        <label for="document_reference" class="col-form-label text-right-align">Document
                                            Ref</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input maxlength="25" readonly type="text"
                                               class="form-control form-control-sm justify-content-end"
                                               id="document_reference"
                                               name="document_reference"
                                               value="{{$invBillPayInfo->document_ref}}">
                                    </div>
                                </div>
                            </div>
                            <div class="editDocumentRef d-none">
                                <div class="form-group row d-flex justify-content-end">
                                    <div class="col-md-3 pr-0"><label for="department" class="required">Dept/Cost
                                            Center </label></div>
                                    <div class="col-md-6 form-group">
                                        <select required name="edt_department" style="width: 100%"
                                                class="form-control form-control-sm select2" id="edt_department">
                                            <option value="">&lt;Select&gt;</option>
                                            @foreach($department as $dpt)
                                                <option
                                                    {{  old('edt_department', $dpt->cost_center_dept_id) ==  $invBillPayInfo->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-3 pr-0"><label for="edt_bill_section" class="">Bill
                                            Section </label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <select name="edt_bill_section" class="form-control form-control-sm select2"
                                                id="edt_bill_section">
                                            <option value="">&lt;Select&gt;</option>
                                            @foreach($lBillSecList as $value)
                                                <option
                                                    {{  old('edt_bill_section',$invBillPayInfo->bill_sec_id) ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-3 pr-0"><label for="edt_bill_register" class="">Bill
                                            Register </label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <select required name="edt_bill_register" style="width: 100%"
                                                class="form-control form-control-sm select2"
                                                data-bill-register-id="{{$invBillPayInfo->bill_reg_id}}"
                                                id="edt_bill_register">
                                        </select>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <label for="edt_document_reference" class="col-md-3 col-form-label">Document
                                        Ref</label>
                                    <div class="col-md-6">
                                        <input maxlength="200" type="text" class="form-control form-control-sm"
                                               id="edt_document_reference"
                                               name="edt_document_reference"
                                               value="{{$invBillPayInfo->document_ref}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="offset-2 col-md-5">
                            <div class="row">
                                <div class="col-md-6"><label for="document_number" class="">Document Number </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->document_no) ? $invBillPayInfo->document_no : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label for="document_reference" class="">Document Reference </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->document_ref) ? $invBillPayInfo->document_ref : ''}}" disabled/>
                                </div>
                            </div>
                        </div>--}}
                        {{--<div class="offset-2 col-md-5">
                            <div class="row">
                                <div class="col-md-6"><label for="bill_sec_id" class="">Bill Section </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->bill_sec_name) ? $invBillPayInfo->bill_sec_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label for="bill_register" class="">Bill Register </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->bill_reg_name) ? $invBillPayInfo->bill_reg_name : ''}}" disabled/>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    <div class="form-group row viewDocumentRef">
                        <div class="col-md-2"><label for="narration" class="required">Narration </label>
                        </div>
                        <div class="col-md-10 form-group">
                                <textarea class="form-control form-control-sm" id="narration" name="narration" rows="3"
                                          placeholder=""
                                          disabled>{{isset($invBillPayInfo->narration) ? $invBillPayInfo->narration : ''}}</textarea>
                        </div>
                    </div>
                    <div class="editDocumentRef d-none">
                        <div class="form-group row">
                            <label for="edt_narration" class="required col-md-2 col-form-label">Narration</label>
                            <div class="col-md-10">
                                <textarea maxlength="500" required name="edt_narration"
                                          class="required form-control form-control-sm"
                                          id="edt_narration">{{$invBillPayInfo->narration}}</textarea>

                            </div>
                        </div>
                    </div>


                    <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Party Leader Info</span>
                    </h6>
                    <div class="row">
                        <div class="col-md-2"><label for="vendor_id" class="">Party/Vendor ID </label></div>
                        <div class="col-md-3 form-group ">
                            <input type="text" class="form-control form-control-sm" name="vendor_id"
                                   value="{{isset($invBillPayInfo->vendor_id) ? $invBillPayInfo->vendor_id : ''}}"
                                   readonly/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="vendor_name" class="">Party/Vendor Name </label></div>
                        <div class="col-md-10 form-group ">
                            <input type="text" class="form-control form-control-sm"
                                   value="{{isset($invBillPayInfo->vendor_name) ? $invBillPayInfo->vendor_name : ''}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="vendor_category" class="">Party/Vendor Category </label></div>
                        <div class="col-md-10 form-group">
                            <input type="text" class="form-control form-control-sm"
                                   value="{{isset($invBillPayInfo->vendor_category_name) ? $invBillPayInfo->vendor_category_name : ''}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="row text-center mt-1">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_bills_payable">O/S Bills Payable(A)</label>
                                <input type="text" id="os_bills_payable" class="form-control form-control-sm text-right"
                                       value="{{isset($invBillPayInfo->os_bill_payable) ? $invBillPayInfo->os_bill_payable : ''}}"
                                       disabled/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_prepayments">O/S Prepayments(B)</label>
                                <input type="text" id="os_prepayments" class="form-control form-control-sm text-right"
                                       value="{{isset($invBillPayInfo->os_prepayment) ? $invBillPayInfo->os_prepayment : ''}}"
                                       disabled/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_security_deposits">O/S Security Deposits(C)</label>
                                <input type="text" id="os_security_deposits"
                                       class="form-control form-control-sm text-right"
                                       value="{{isset($invBillPayInfo->os_security_deposit) ? $invBillPayInfo->os_security_deposit : ''}}"
                                       disabled/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="good_for_payment">Good For Payment(A-B)</label>
                                <input type="text" id="good_for_payment" class="form-control form-control-sm text-right"
                                       value="{{isset($invBillPayInfo->good_for_payment) ? $invBillPayInfo->good_for_payment : ''}}"
                                       disabled/>
                            </div>
                        </div>
                    </div>
                    <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Invoice References</span></h6>
                    <div class="row">
                        <div class="col-md-12 table-responsive fixed-height-scrollable">
                            <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                                <thead class="thead-light sticky-head">
                                <tr>
                                    <th>#SL No</th>
                                    <th>Document No</th>
                                    <th>Document Date</th>
                                    <th>Document Ref</th>
                                    <th>Invoice Type</th>
                                    <th>Invoice Amount</th>
                                    <th>Due Amount</th>
                                    <th>Payment Amount</th>
                                </tr>
                                </thead>
                                <tbody id="invRefList">
                                @if(count($invReferenceList) > 0)
                                    @php $index=1; $totalDue = 0; @endphp
                                    @foreach ($invReferenceList as $value)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $value->document_no }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
                                            <td>{{ $value->document_ref }}</td>
                                            <td>{{ $value->invoice_type_name }}</td>
                                            <td class="text-right">{{ $value->invoice_amount }}</td>
                                            <td class="text-right">{{ $value->payment_due }}</td>
                                            <td class="text-right">{{ $value->payment_amount }}</td>
                                        </tr>
                                        @php
                                            $index++;
                                            $totalDue += $value->payment_amount;
                                        @endphp
                                    @endforeach
                                    <tr class="font-small-3">
                                        <th colspan="7" class="text-right pr-2"> Total Due Amount</th>
                                        <th id="total_due_amt" class="text-right">{{$totalDue}}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="8" class="text-center"> No Data Found</th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- TODO: Add this section- start : Pavel-09-05-22 --}}
                    <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Invoice Reference (for Tax Payment)</span>
                    </h6>
                    <div class="row">
                        <div class="col-md-12 table-responsive fixed-height-scrollable">
                            <table class="table table-sm table-bordered table-striped" id="inv_ref_tax_pay_table">
                                <thead class="thead-light sticky-head">
                                <tr>
                                    <th>#SL No</th>
                                    <th>Document No</th>
                                    <th>Document Date</th>
                                    <th>Document Ref</th>
                                    <th>Invoice Amount</th>
                                    <th>Tax Amount</th>
                                    <th>Due Amount</th>
                                    <th>Payment Amount</th>
                                </tr>
                                </thead>
                                <tbody id="invRefTaxPayList">
                                @if(count($invRefTaxPayList) > 0)
                                    @php $index=1; $totalTaxDue = 0; @endphp
                                    @foreach ($invRefTaxPayList as $value)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $value->document_no }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
                                            <td>{{ $value->document_ref }}</td>
                                            <td class="text-right">{{ $value->invoice_amount }}</td>
                                            <td class="text-right">{{ $value->tax_amount }}</td>
                                            <td class="text-right">{{ $value->payment_due }}</td>
                                            <td class="text-right">{{ $value->payment_amount }}</td>
                                        </tr>
                                        @php
                                            $index++;
                                            $totalTaxDue += $value->payment_amount;
                                        @endphp
                                    @endforeach
                                    <tr class="font-small-3">
                                        <th colspan="7" class="text-right pr-2"> Total Due Amount</th>
                                        <th id="total_due_tax_amt" class="text-right">{{$totalTaxDue}}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="8" class="text-center"> No Data Found</th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- TODO: Add this section- start : Pavel-09-05-22 --}}
                    <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Payment Adjustment</span>
                    </h6>
                    <div class="row">
                        <div class="col-md-2"><label for="bank_id" class="required">Bank Account </label></div>
                        <div class="col-md-9 form-group pl-0">
                            <input type="text" id="bank_id" class="form-control form-control-sm"
                                   value="{{isset($invBillPayInfo->bank_account_name) ? $invBillPayInfo->bank_account_name : ''}}"
                                   disabled/>
                        </div>
                    </div>
                    {{-- TODO: Update this section-start : Pavel-09-05-22 --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="offset-4 col-md-4 form-group pl-0 mb-0">
                                    <label for="">Amount in CCY </label>
                                </div>
                                <div class="col-md-4 form-group pl-0 mb-0">
                                    <label for="">Amount in LCY </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="bank_pay_amt_ccy" class="required">Payment
                                        Amount </label>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="bank_pay_amt_ccy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->payment_amount_ccy) ? $invBillPayInfo->payment_amount_ccy : ''}}"
                                           disabled/>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="bank_pay_amt_lcy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->payment_amount_lcy) ? $invBillPayInfo->payment_amount_lcy : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="adj_pre_pay_amt_ccy" class="required">Adjust
                                        Prepayments </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="adj_pre_pay_amt_ccy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->adjust_prepayments_ccy) ? $invBillPayInfo->adjust_prepayments_ccy : ''}}"
                                           disabled/>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="adj_pre_pay_amt_lcy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->adjust_prepayments_lcy) ? $invBillPayInfo->adjust_prepayments_lcy : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="fine_forfeiture_ccy"
                                                             class="required">Fine/Forfeiture </label>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="fine_forfeiture_ccy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->fine_forfeiture_ccy) ? $invBillPayInfo->fine_forfeiture_ccy : ''}}"
                                           disabled/>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="fine_forfeiture_lcy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->fine_forfeiture_lcy) ? $invBillPayInfo->fine_forfeiture_lcy : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="bank_pay_amt_ccy" class="required">Total
                                        Amount </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="total_amount_ccy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->total_amount_ccy) ? $invBillPayInfo->total_amount_ccy : ''}}"
                                           disabled/>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="total_amount_lcy"
                                           class="form-control form-control-sm text-right"
                                           value="{{isset($invBillPayInfo->total_amount_lcy) ? $invBillPayInfo->total_amount_lcy : ''}}"
                                           disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 mt-2 ml-1">
                            <div class="row">
                                <div class="col-md-4"><label for="currency" class="">Currency</label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input type="text" id="currency" class="form-control form-control-sm"
                                           value="{{isset($invBillPayInfo->currency_code) ? $invBillPayInfo->currency_code : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="exc_rate" class="">Exchange Rate</label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input type="text" id="exc_rate" class="form-control form-control-sm exc_rate"
                                           value="{{isset($invBillPayInfo->exchange_rate) ? $invBillPayInfo->exchange_rate : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="viewDocumentRef row">
                                <div class="col-md-4"><label for="cheque_no" class="required">Cheque No </label></div>
                                <div class="col-md-4 form-group pl-0 ">
                                    <input type="text" class="form-control form-control-sm"
                                           value="{{isset($invBillPayInfo->cheque_no) ? $invBillPayInfo->cheque_no : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="viewDocumentRef row">
                                <div class="col-md-4"><label for="cheque_date" class="required">Cheque Date</label>
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm"
                                           value="{{isset($invBillPayInfo->cheque_date) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->cheque_date)  : ''}}"
                                           disabled/>
                                </div>
                            </div>
                            <div class="editDocumentRef d-none">
                                <div class="row">
                                    <div class="col-md-4"><label for="edt_cheque_no" class="required">Cheque No </label>
                                    </div>
                                    <div class="col-md-4 form-group pl-0 ">
                                        <input type="text" class="form-control form-control-sm" name="edt_cheque_no" id="edt_cheque_no"
                                               value="{{isset($invBillPayInfo->cheque_no) ? $invBillPayInfo->cheque_no : ''}}"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="editDocumentRef d-none">
                                <div class="row">
                                    <div class="col-md-4"><label for="cheque_date" class="required">Cheque Date</label>
                                    </div>
                                    <div class="input-group date edt_cheque_date col-md-4 pl-0"
                                         id="edt_cheque_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false" required
                                               name="edt_cheque_date"
                                               id="edt_cheque_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#edt_cheque_date"
                                               data-toggle="datetimepicker"
                                               value="{{ old('edt_cheque_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->cheque_date)) }}"
                                               data-predefined-date="{{ old('edt_cheque_date', \App\Helpers\HelperClass::dateConvert($invBillPayInfo->cheque_date)) }}"
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append edt_cheque_date"
                                             data-target="#edt_cheque_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bxs-calendar font-size-small"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    {{-- TODO: Update this section-end : Pavel-09-05-22 --}}
                </fieldset>
                {{--<fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Vendor Account Master</legend>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-2"><label for="party_sub_ledger" >Party-Sub Ledger </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="hidden" name="party_sub_ledger" value="{{isset($invBillPayInfo->gl_subsidiary_id) ? $invBillPayInfo->gl_subsidiary_id : ''}}" />
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->gl_subsidiary_name) ? $invBillPayInfo->gl_subsidiary_name : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_id" class="">Vendor ID </label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm"  name="vendor_id" value="{{isset($invBillPayInfo->vendor_id) ? $invBillPayInfo->vendor_id : ''}}" readonly/>
                                </div>--}}{{--
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-info mb-1" id="vendor_search_btn">
                                        <i class="bx bx-search"></i><span class="align-middle ml-25">Search</span></button>
                                </div>--}}{{--
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_name" class="">Vendor Name </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->vendor_name) ? $invBillPayInfo->vendor_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_category" class="">Vendor Category </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($invBillPayInfo->vendor_category_name) ? $invBillPayInfo->vendor_category_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row text-right mt-1">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_bills_payable">O/S Bills Payable</label>
                                        <input type="text" id="os_bills_payable" class="form-control form-control-sm" value="{{isset($invBillPayInfo->os_bill_payable) ? $invBillPayInfo->os_bill_payable : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_advances">O/S Advances</label>
                                        <input type="text" id="os_advances" class="form-control form-control-sm" value="{{isset($invBillPayInfo->os_security_deposit) ? $invBillPayInfo->os_security_deposit : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_prepayments">O/S Prepayments</label>
                                        <input type="text" id="os_prepayments" class="form-control form-control-sm" value="{{isset($invBillPayInfo->os_prepayment) ? $invBillPayInfo->os_prepayment : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="good_for_payment">Good For Payment</label>
                                        <input type="text" id="good_for_payment" class="form-control form-control-sm" value="{{isset($invBillPayInfo->good_for_payment) ? $invBillPayInfo->good_for_payment : ''}}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>--}}
                {{--<fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Reference</legend>
                    <div class="col-md-12 table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                <th>#SL No</th>
                                <th>Document No</th>
                                <th>Invoice Type</th>
                                <th>Invoice Amount</th>
                                <th>Payment Amount</th>
                            </tr>
                            </thead>
                            <tbody id="invRefList">
                            @if(count($invReferenceList) > 0)
                                @php $index=1; @endphp
                                @foreach ($invReferenceList as $value)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $value->document_no }}</td>
                                        <td>{{ $value->invoice_type_name }}</td>
                                        <td>{{ $value->invoice_amount }}</td>
                                        <td>{{ $value->payable_amount }}</td>
                                    </tr>
                                    @php $index++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="5" class="text-center"> No Data Found</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </fieldset>--}}

                {{--<fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Vendor Payment/Adjustment</legend>
                    <div class="col-md-10">

                        <div class="row">
                            <div class="col-md-3"><label for="narration" class="required">Narration </label></div>
                            <div class="col-md-9 form-group pl-0">
                                <textarea class="form-control form-control-sm" id="narration" rows="3" disabled >{{isset($invBillPayInfo->narration) ? $invBillPayInfo->narration : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>--}}

                {{--<div class="row mt-2">
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span class="align-middle ml-25">{{ (isset($chequeBooksInfo->chq_book_id) ? 'Update' : 'Save') }}</span></button>
                        <button type="reset" class="btn btn-dark"><i class="bx bx-reset"></i><span class="align-middle ml-25 ml-75">Reset</span></button>
                        <h6 class="text-primary ml-2">Last Posting Batch ID<span class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25"> 100</span></h6>
                    </div>
                </div>--}}
            </form>
            @if (isset($invoice_line))
                <fieldset class="col-md-12 border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Transaction Detail</legend>
                    <div class="row mt-1">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-sm table-hover table-bordered " id="ap_account_table">
                                <thead class="thead-dark">
                                <tr>
                                    <th width="2%" class="">Account ID</th>
                                    <th width="28%" class="">Account Name</th>
                                    <th width="28%" class="">Party ID</th>
                                    <th width="28%" class="">Party Name</th>
                                    <th width="5%" class="text-right-align">Debit</th>
                                    <th width="5%" class="text-right-align">Credit</th>
                                    {{--<th width="16%" class="text-center">Amount CCY</th>
                                    <th width="16%" class="text-center">Amount LCY</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($invoice_line as $line)
                                    <tr>
                                        <td>{{ $line->account_id }}</td>
                                        <td>{{ $line->account_name }}</td>
                                        <td>{{ $line->party_code }}</td>
                                        <td>{{ $line->party_name }}</td>
                                        <td class="text-right-align">{{ $line->debit }}</td>
                                        <td class="text-right-align">{{ $line->credit }}</td>
                                        {{--<td class="text-right-align">{{ $line->amount_ccy }}</td>
                                        <td class="text-right-align">{{ $line->amount_lcy }}</td>--}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        {{--<td></td>--}}
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot class="border-top-dark bg-dark text-white">
                                <tr>
                                    <td colspan="4" class="text-right-align">Total Amount</td>
                                    <td class="text-right-align">{{ $invoice_line[0]->total_debit }}</td>
                                    <td class="text-right-align">{{ $invoice_line[0]->total_credit }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </fieldset>
            @endif
            <fieldset class="border p-2 mt-2">
                <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Payment Attachments</legend>
                <div class="col-md-12 table-responsive fixed-height-scrollable">
                    <table class="table table-sm table-bordered table-striped" id="inv_pay_attach_table">
                        <thead class="thead-light sticky-head">
                        <tr>
                            <th>#SL No</th>
                            <th>Attachment Name</th>
                            <th>Attachment Type</th>
                            <th>Download</th>
                        </tr>
                        </thead>
                        <tbody id="invPayAttachList">
                        @if(count($invPaymentDocsList) > 0)
                            @php $index=1; @endphp
                            @foreach ($invPaymentDocsList as $value)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{{ $value->doc_file_name }}</td>
                                    <td>{{ $value->doc_file_desc }}</td>
                                    <td>
                                        @if($value && $value->doc_file_name)
                                            <a href="{{ route('invoice-bill-payment.attachment-download', [$value->doc_file_id]) }}"
                                               target="_blank"><i class="bx bx-download cursor-pointer"></i></a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach
                        @else
                            <tr>
                                <th colspan="5" class="text-center"> No Data Found</th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <div class="editDocumentRef d-none">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-info mt-1" id="updateReference">Update
                            Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">

        $(document).ready(function () {
            /**Update Transaction Reference Start**/
            $("#chnTransRef").on('change', function () {
                if ($(this).is(":checked")) {
                    $(".viewDocumentRef").addClass('d-none');
                    $(".editDocumentRef").removeClass('d-none');

                    $("#edt_bill_section").select2().val('{{isset($invBillPayInfo->bill_sec_id) ? $invBillPayInfo->bill_sec_id : ''}}').css('width:', '100%');
                    $("#edt_bill_section").select2().trigger('change');

                    selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');
                } else {
                    $(".viewDocumentRef").removeClass('d-none');
                    $(".editDocumentRef").addClass('d-none');
                    /* $("#edt_department").select2().val('{{isset($invBillPayInfo->cost_center_dept_id) ? $invBillPayInfo->cost_center_dept_id : ''}}');
                    $("#edt_department").select2().trigger('change');*/
                }
            });

            $("#edt_period").on('change', function () {
                $("#edt_document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#edt_document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#edt_posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#edt_posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                $("#edt_cheque_date >input").val("");
                if (chequeCalendarClickCounter > 0) {
                    $("#edt_cheque_date").datetimepicker('destroy');
                    chequeCalendarClickCounter = 0;
                }
                setPeriodCurrentDate();
            });

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let chequeCalendarClickCounter = 0;

            $("#edt_posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#edt_posting_date >input").val("");
                let minDate = $("#edt_period :selected").data("mindate");
                let maxDate = $("#edt_period :selected").data("maxdate");
                let currentDate = $("#edt_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
            let postingDateClickCounter = 0;

            $("#edt_posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                if (!nullEmptyUndefinedChecked($("#edt_posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#edt_posting_date_field").val()).format("DD-MM-YYYY");
                    } else {
                        newDueDate = moment($("#edt_posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                    }
                    $("#edt_document_date >input").val(newDueDate);
                    $("#edt_cheque_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            $("#edt_document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#edt_document_date >input").val("");
                let minDate = false;
                let maxDate = $("#edt_period :selected").data("maxdate");
                let currentDate = $("#edt_period :selected").data("currentdate");

                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
            let documentDateClickCounter = 0;
            $("#edt_document_date").on("change.datetimepicker", function () {
                let newDueDate;
                if (!nullEmptyUndefinedChecked($("#edt_document_date_field").val())) {
                    if (documentDateClickCounter == 0) {
                        newDueDate = moment($("#edt_document_date_field").val(),"YYYY-MM-DD");
                    } else {
                        newDueDate = moment($("#edt_document_date_field").val(), "DD-MM-YYYY");
                    }
                    $("#edt_cheque_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                documentDateClickCounter++;
            });
            $("#edt_cheque_date").on('click', function () {
                chequeCalendarClickCounter++;
                $("#edt_cheque_date >input").val("");
                let minDate = $("#edt_period :selected").data("mindate");
                let maxDate = false;
                let currentDate = $("#edt_period :selected").data("currentdate");

                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            function listBillRegister() {
                $('#edt_bill_section').change(function (e) {
                    $("#edt_bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            listBillRegister();


            $("#edt_fiscal_year").on('change', function () {
                getPostingPeriod($("#edt_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            //getPostingPeriod($("#edt_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            function setPostingPeriod(periods) {
                $("#edt_period").html(periods);
                //setPeriodCurrentDate();
                $("#edt_period").trigger('change');
            }

            $("#updateReference").on('click', () => {
                swal.fire({
                    title: 'Are you sure?',
                    type: 'info',
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok",
                    confirmButtonClass: "btn btn-primary",
                    cancelButtonClass: "btn btn-danger ml-1",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {

                        let paymentId = $("#payment_id").val();
                        let postingPeriod = $("#edt_period :selected").val();
                        let postingDate = $("#edt_posting_date_field").val();
                        let documentDate = $("#edt_document_date_field").val();
                        let documentNumber = $("#edt_document_number").val();
                        let documentRef = $("#edt_document_reference").val();
                        let documentNarration = $("#edt_narration").val();
                        let department = $("#edt_department :selected").val();
                        let billSection = $("#edt_bill_section :selected").val();
                        let billRegister = $("#edt_bill_register :selected").val();
                        let chequeDate = $("#edt_cheque_date_field").val();
                        let chequeNo = $("#edt_cheque_no").val();


                        let request = $.ajax({
                            url: APP_URL + "/account-payable/invoice-bill-payment-update",
                            data: {
                                paymentId,
                                postingPeriod,
                                postingDate,
                                documentDate,
                                documentNumber,
                                documentRef,
                                documentNarration,
                                department,
                                billSection,
                                billRegister,
                                chequeDate,
                                chequeNo
                            },
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
                                    text: res.response_message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    let urlStr = '{{ route('invoice-bill-payment.view',['id'=>'_p']) }}';
                                    window.location.href = urlStr.replace('_p', paymentId);
                                });
                            } else {
                                Swal.fire({text: res.response_message, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
            });

            /**Update Transaction Reference End**/

        });

    </script>
@endsection

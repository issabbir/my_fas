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
            <div class="card-header d-flex justify-content-between align-items-center p-0">
                {{-- <h4 class="card-title"> Add  Chart Of Accounts (COA)</h4>--}}
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Entry Authorize Views</span></h4>
                <a href="{{route('invoice-bill-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>
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

            <form id="invoice-bill-authorize-form" @if(isset($wkMapId)) action="{{route('invoice-bill-authorize.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                <input type="hidden" name="invoice_id" value="{{$inserted_data->invoice_id}}">
                <fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Reference</legend>
                        <div class="row mb-1">
                            <div class="col-md-12 d-flex justify-content-end">
                                <a target="_blank" class="btn btn-sm btn-info cursor-pointer"
                                   href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$inserted_data->trans_period_id}}&p_trans_batch_id={{$inserted_data->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                                    <i class="bx bx-printer"></i>Print Voucher
                                </a>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="posting_name" id="ap_posting_name">
                            <input type="hidden" name="po_master_id" id="po_master_id">
                            <div class="form-group row">
                                <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                                    Date</label>
                                <div class="col-md-5">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="document_date_field" class=" col-md-4 col-form-label">Document Date</label>
                                <div class="col-md-5">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->document_date)}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="document_number" class=" col-md-4 col-form-label">Document No</label>
                                <div class="col-md-5">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           name="document_number"
                                           id="document_number"
                                           value="{{$inserted_data->document_no}}">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group row  justify-content-end">
                                <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" readonly class="form-control form-control-sm" name="department"
                                           id="department"
                                           value="{{$inserted_data->cost_center_dept_name}}">
                                    {{--<select required name="department" class="form-control form-control-sm select2" id="department">
                                        <option value="">Select a department</option>
                                        @foreach($department as $dpt)
                                            <option
                                                {{  old('department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                        @endforeach
                                    </select>--}}
                                </div>
                            </div>
                            <div class="form-group row justify-content-end">
                                <label for="budget_department" class="col-form-label col-md-4 required ">Budget Department</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           name="budget_department" id="budget_department"
                                           value="{{$inserted_data->budget_dept_name}}">
                                </div>
                            </div>
                            <div class="form-group row justify-content-end">
                                <label for="bill_register" class="required col-md-4 col-form-label">Bill
                                    Register</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           name="bill_register"
                                           id="bill_register"
                                           value="{{$inserted_data->bill_reg_name}}">
                                    {{--<select required name="bill_register" class="form-control form-control-sm select2"
                                            id="bill_register">
                                    </select>--}}
                                </div>
                            </div>
                            <div class="form-group row justify-content-end">
                                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" readonly class="form-control form-control-sm" name="bill_section"
                                           id="bill_section"
                                           value="{{$inserted_data->bill_sec_name}}">
                                    {{--<select required name="bill_section" class="form-control form-control-sm select2"
                                            id="bill_section">
                                        <option value="">Select a bill</option>
                                        @foreach($billSecs as $value)
                                            <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                            </option>
                                        @endforeach
                                    </select>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="document_reference" class="col-md-2 col-form-label">Document Ref</label>
                        <div class="col-md-10">
                            <input  readonly type="text" class="form-control form-control-sm"
                                   id="document_reference"
                                   name="document_reference"
                                   value="{{$inserted_data->document_ref}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                        <div class="col-md-10">
                            <textarea  name="narration"
                                      class="required form-control form-control-sm" readonly
                                      id="narration">{{$inserted_data->narration}}</textarea>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Party Ledger Info</legend>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="required col-md-4 col-form-label" for="ap_party_sub_ledger">Party
                                    Sub-Ledger</label>
                                <div class="col-md-8">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           value="{{$inserted_data->party_sub_ledger_name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label required" for="ap_invoice_type">Invoice
                                    Type</label>
                                <div class="col-md-8">
                                    <input type="text" readonly class="form-control form-control-sm"
                                           value="{{$inserted_data->invoice_type_name}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group row d-flex justify-content-end">
                                <label for="ap_purchase_order_date" class="col-form-label col-md-4">Purchase Order No</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" id="ap_purchase_order_date" readonly
                                           class="form-control form-control-sm"
                                           value="{{isset($inserted_data->po_number) ? $inserted_data->po_number : 'N/A'}}"
                                           name="ap_purchase_order_date"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="ap_purchase_order_date" class="col-form-label col-md-4">Purchase Order Date</label>
                                <div class="col-md-5 pl-0">
                                    <input type="text" id="ap_purchase_order_date" readonly
                                           class="form-control form-control-sm"
                                           value="{{isset($inserted_data->po_date) ? $inserted_data->po_date : 'N/A'}}"
                                           name="ap_purchase_order_date"
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2 required" for="ap_vendor_id">Vendor ID</label>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="input-group col-md-5">
                                    <input  name="ap_vendor_id" readonly
                                           class="form-control form-control-sm "
                                           value="{{$inserted_data->vendor_id}}" type="number"
                                           id="ap_vendor_id"
                                           oninput="maxLengthValid(this)"
                                           onkeyup="resetField(['#ap_vendor_name','#ap_vendor_category']);enableDisablePoCheck(0)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_vendor_name">Vendor Name</label>
                        <div class="col-md-10">
                            <input  type="text" class="form-control form-control-sm" id="ap_vendor_name"
                                   name="ap_vendor_name" readonly value="{{$inserted_data->vendor_name}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_vendor_category">Vendor Category</label>
                        <div class="col-md-10">
                            <input  type="text" class="form-control form-control-sm" id="ap_vendor_category"
                                   name="ap_vendor_category" value="{{$inserted_data->vendor_category_name}}"
                                   readonly>
                        </div>
                    </div>

                </fieldset>
                @if (isset($inserted_data->budget_head_id))
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 15px;">{{--Budget Booking/Utilized Info--}}Budget Head Info</legend>
                        {{--<div class="form-group row">
                            <label class="col-form-label col-md-2 required" for="b_booking_id">Budget Booking ID</label>
                            <div class="input-group col-md-2">
                                <input  name="b_booking_id" class="form-control form-control-sm" readonly
                                        type="number"
                                        value="{{$inserted_data->budget_booking_id}}"
                                        id="b_booking_id"
                                        maxlength="15"
                                        oninput="maxLengthValid(this)"
                                    --}}{{--onkeyup="resetBudgetField()"--}}{{-->
                            </div>
                        </div>--}}
                        <div class="form-group row">
                            <label for="b_head_id" class=" col-md-2 col-form-label required">Budget Head</label>
                            <div class="input-group col-md-2">
                                <input readonly type="text"
                                       class="form-control form-control-sm" name="b_head_id"
                                       id="b_head_id"
                                       value="{{ $inserted_data->budget_head_id }}">
                            </div>
                            {{--<div class="input-group col-md-8">
                                <input readonly type="text"
                                       class="form-control form-control-sm" name="b_head_name"
                                       id="b_head_name"
                                       value="{{ $inserted_data->budget_head_name }}">
                            </div>--}}
                        </div>
                        <div class="form-group row">
                            <label for="b_head_name" class=" col-md-2 col-form-label">Budget Head Name</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control form-control-sm" name="b_head_name"
                                       id="b_head_name"
                                       value="{{ $inserted_data->budget_head_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="b_sub_category" class=" col-md-2 col-form-label">Budget Sub-Category</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control form-control-sm" name="b_sub_category"
                                       id="b_sub_category"
                                       value="{{ $inserted_data->budget_sub_category_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="b_category" class=" col-md-2 col-form-label">Budget Category</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control form-control-sm" name="b_category"
                                       id="b_category"
                                       value="{{ $inserted_data->budget_category_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="b_type" class=" col-md-2 col-form-label">Budget Type</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control form-control-sm" name="b_type"
                                       id="b_type"
                                       value="{{ $inserted_data->budget_type_name }}">
                            </div>
                        </div>
                        {{--<div class="form-group row">
                            <label for="b_date" class="col-md-2 col-form-label">Budget Booking Date</label>
                            <div class="col-md-2">
                                <input type="text" readonly class="form-control form-control-sm" name="b_date"
                                       id="b_date"
                                       value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->budget_booking_date) }}">
                            </div>
                            <label for="b_amt" class=" col-md-2 col-form-label">Booking Amt</label>
                            <div class="col-md-2">
                                <input type="text" readonly class="form-control form-control-sm" name="b_amt"
                                       id="b_amt"
                                       value="{{ $inserted_data->budget_booking_amount }}">
                            </div>
                            <label for="b_available_amt" class=" col-md-2 col-form-label">Available Amt</label>
                            <div class="col-md-2">
                                <input type="text" readonly class="form-control form-control-sm" name="b_available_amt"
                                       id="b_available_amt"
                                       value="{{ $inserted_data->available_booking_amount }}">
                            </div>
                        </div>--}}
                    </fieldset>
                @endif
                {{--<fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Budget Booking Info</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 required" for="b_booking_id">Budget Booking
                                    ID</label>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <div class="input-group col-md-7 pr-0">
                                            <input  name="b_booking_id" readonly
                                                   class="form-control form-control-sm "
                                                   value="{{$inserted_data->budget_booking_id}}"
                                                   type="number"
                                                   id="b_booking_id"
                                                   oninput="maxLengthValid(this)"
                                                   onkeyup="resetBudgetField()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="b_head_id" class=" col-md-4 col-form-label required">Budget Head ID</label>
                                <div class="col-md-5">
                                    <input  readonly type="text"
                                           class="form-control form-control-sm" name="b_head_id"
                                           id="b_head_id"
                                           value="{{ $inserted_data->budget_head_id }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row d-flex justify-content-end">
                                <label for="b_date" class=" col-md-4 col-form-label">Budget Booking Date</label>
                                <div class="col-md-5">
                                    <input  type="text" readonly class="form-control form-control-sm"
                                           name="b_date"
                                           id="b_date"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->budget_booking_date) }}">
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="b_amt" class=" col-md-4 col-form-label">Budget Booking Amt</label>
                                <div class="col-md-5">
                                    <input  type="text" readonly class="form-control form-control-sm"
                                           name="b_amt"
                                           id="b_amt"
                                           value="{{ $inserted_data->budget_booking_amount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="b_head_name" class=" col-md-2 col-form-label">Budget Head Name</label>
                        <div class="col-md-10">
                            <input  type="text" readonly class="form-control form-control-sm"
                                   name="b_head_name"
                                   id="b_head_name"
                                   value="{{ $inserted_data->budget_head_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="b_sub_category" class=" col-md-2 col-form-label">Budget Sub-Category</label>
                        <div class="col-md-10">
                            <input  type="text" readonly class="form-control form-control-sm"
                                   name="b_sub_category"
                                   id="b_sub_category"
                                   value="{{ $inserted_data->budget_sub_category_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="b_category" class=" col-md-2 col-form-label">Budget Category</label>
                        <div class="col-md-10">
                            <input  type="text" readonly class="form-control form-control-sm"
                                   name="b_category"
                                   id="b_category"
                                   value="{{ $inserted_data->budget_category_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="b_type" class=" col-md-2 col-form-label">Budget Type</label>
                        <div class="col-md-10">
                            <input  type="text" readonly class="form-control form-control-sm"
                                   name="b_type"
                                   id="b_type"
                                   value="{{ $inserted_data->budget_type_name }}">
                        </div>
                    </div>
                </fieldset>--}}
                <fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Master Info</legend>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_invoice_amount"></label>
                        <div class="col-md-3 text-center">
                            <label class="col-form-label" for="">Amount in CCY</label>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="col-form-label" for="">Amount in LCY</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_invoice_amount">Invoice Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_invoice_amount_ccy" value="{{$inserted_data->invoice_amount_ccy}}"
                                           name="ap_invoice_amount_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_invoice_amount_lcy" value="{{$inserted_data->invoice_amount_lcy}}"
                                   name="ap_invoice_amount_lcy">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_tax_amount_ccy">Tax Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="text"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%" max="100"
                                           id="ap_tax_amount_ccy_percentage" value="{{$inserted_data->tax_amount_pct}}"
                                           name="ap_tax_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_tax_amount_ccy"
                                           maxlength="17"
                                           oninput="maxLengthValid(this)" value="{{$inserted_data->tax_amount_ccy}}"
                                           name="ap_tax_amount_ccy" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_tax_amount_lcy" name="ap_tax_amount_lcy"
                                   value="{{$inserted_data->tax_amount_lcy}}">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <div class="col-md-11">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="party_name_for_tax" value="{{isset($inserted_data->tax_party_name) ? $inserted_data->tax_party_name : 'N/A' }}"
                                           name="party_name_for_tax">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_vat_amount_ccy">VAT Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="text"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%" value="{{$inserted_data->vat_amount_pct}}"
                                           id="ap_vat_amount_ccy_percentage"
                                           name="ap_vat_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">
                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_vat_amount_ccy"
                                           maxlength="17" value="{{ $inserted_data->vat_amount_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_vat_amount_ccy" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_vat_amount_lcy" name="ap_vat_amount_lcy"
                                   value="{{ $inserted_data->vat_amount_ccy }}">
                        </div>

                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <div class="col-md-11">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="party_name_for_vat" value="{{isset($inserted_data->vat_party_name) ? $inserted_data->vat_party_name : 'N/A'}}"
                                           name="party_name_for_vat">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_security_deposit_amount_ccy">Security
                            Deposit </label>

                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="text"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%"
                                           id="ap_security_deposit_amount_ccy_percentage" value="{{$inserted_data->security_deposit_pct}}"
                                           name="ap_security_deposit_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_security_deposit_amount_ccy"
                                           maxlength="17" value="{{ $inserted_data->security_deposit_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_security_deposit_amount_ccy" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_security_deposit_amount_lcy"
                                   value="{{ $inserted_data->security_deposit_lcy }}"
                                   name="ap_security_deposit_amount_lcy">
                        </div>

                        {{--<div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_payment_currency">Payment Currency</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_currency" value="{{$inserted_data->currency_code}}"
                                           name="ap_payment_currency">
                                </div>
                            </div>
                        </div>--}}
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_extra_security_deposit_amount_ccy_percentage">Extra
                            Security
                            Deposit </label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="text"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%"
                                           id="ap_extra_security_deposit_amount_ccy_percentage" value="{{$inserted_data->extra_security_deposit_pct}}"
                                           name="ap_extra_security_deposit_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_extra_security_deposit_amount_ccy"
                                           maxlength="17" value="{{ $inserted_data->extra_security_deposit_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_extra_security_deposit_amount_ccy"
                                           type="text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_extra_security_deposit_amount_lcy"
                                   value="{{ $inserted_data->extra_security_deposit_lcy }}"
                                   name="ap_extra_security_deposit_amount_lcy">
                        </div>

                        {{--<div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_exchange_rate">Exchange Rate</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_exchange_rate" value="{{$inserted_data->exchange_rate}}"
                                           name="ap_exchange_rate">
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_fine_forfeiture_ccy">Fine/Forfeiture</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_fine_forfeiture_ccy"
                                           value="{{$inserted_data->fine_forfeiture_amount_ccy}}"
                                           name="ap_fine_forfeiture_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_fine_forfeiture_lcy" value="{{$inserted_data->fine_forfeiture_amount_lcy}}"
                                   name="ap_fine_forfeiture_lcy">
                        </div>


                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_preshipment_ccy">Preshipment Inspection
                            (PSI)</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_preshipment_ccy" value="{{$inserted_data->psi_amount_ccy}}"
                                           name="ap_preshipment_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_preshipment_lcy" value="{{$inserted_data->psi_amount_lcy}}"
                                   name="ap_preshipment_lcy">
                        </div>
                        <div class="col-md-5">
                            <label class="col-form-label offset-1" style="text-decoration: underline">Payment Conditions</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_electricity_bill_ccy">Electricity Bill</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_electricity_bill_ccy"
                                           value="{{$inserted_data->electric_bill_amount_ccy}}"
                                           name="ap_electricity_bill_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_electricity_bill_lcy" value="{{$inserted_data->electric_bill_amount_lcy}}"
                                   name="ap_electricity_bill_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_payment_method">Payment
                                    Method</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_method" value="{{$inserted_data->payment_methods_name}}"
                                           name="ap_payment_method">
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_other_charge_ccy">Additional Account {{--Other Charge (if any)--}}</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_other_charge_ccy" value="{{$inserted_data->other_amount_ccy}}"
                                           name="ap_other_charge_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_other_charge_lcy" value="{{$inserted_data->other_amount_lcy}}"
                                   name="ap_other_charge_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_payment_currency">Payment Currency</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_currency" value="{{$inserted_data->currency_code}}"
                                           name="ap_payment_currency">
                                </div>
                            </div>
                        </div>
                        {{--<div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_payment_terms">Payment
                                    Terms</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_terms" value="{{$inserted_data->payment_term_name}}"
                                           name="ap_payment_terms">
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_payable_amount_ccy">Net Payable Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_payable_amount_ccy" value="{{$inserted_data->payable_amount_ccy}}"
                                           name="ap_payable_amount_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_payable_amount_lcy" value="{{$inserted_data->payable_amount_lcy}}"
                                   name="ap_payable_amount_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-5 required pr-0" for="ap_exchange_rate">Exchange Rate</label>
                                <div class="col-md-6">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_exchange_rate" value="{{$inserted_data->exchange_rate}}"
                                           name="ap_exchange_rate">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                {{--<fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Master Info</legend>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_invoice_amount"></label>
                        <div class="col-md-3 text-center">
                            <label class="col-form-label" for="">Amount in CCY</label>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="col-form-label" for="">Amount in LCY</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_invoice_amount">Invoice Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_invoice_amount_ccy" value="{{$inserted_data->invoice_amount_ccy}}"
                                           name="ap_invoice_amount_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_invoice_amount_lcy" value="{{$inserted_data->invoice_amount_lcy}}"
                                   name="ap_invoice_amount_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 required pr-0" for="ap_payment_currency">Payment
                                    Currency</label>
                                <div class="col-md-4">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_currency" value="{{$inserted_data->currency_code}}"
                                           name="ap_payment_currency">
                                    --}}{{-- <select required name="ap_payment_currency" class="form-control form-control-sm"
                                             id="ap_payment_currency">
                                         @foreach($data['currency'] as $cur)
                                             <option
                                                 value="{{ $cur->currency_code }}" {{ ( $cur->currency_code == \App\Enums\Common\Currencies::O_BD) ? "selected" : '' }}>{{ $cur->currency_code }}</option>
                                         @endforeach
                                     </select>--}}{{--
                                </div>
                            </div>
                            --}}{{--<div class="form-group row">
                                <label class="col-form-label col-md-2 required" for="ap_exchange_rate">Exchange Rate</label>
                                <div class="col-md-1">
                                    <input readonly required value="1" class="form-control form-control-sm" id="ap_exchange_rate"
                                           name="ap_exchange_rate" min="0" step="0.01" type="number">
                                </div>
                            </div>--}}{{--
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_tax_amount_ccy">Tax Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="number" min="0" step="0.1"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%" max="100"
                                           id="ap_tax_amount_ccy_percentage" value=""
                                           name="ap_tax_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">
                                    --}}{{--<input readonly type="number"
                                           min="0" step="0.1"
                                           class="form-control form-control-sm text-right-align"
                                           id="ap_tax_amount_ccy"
                                           name="ap_tax_amount_ccy">--}}{{--

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_tax_amount_ccy"
                                           oninput="maxLengthValid(this)" value="{{$inserted_data->tax_amount_ccy}}"
                                           name="ap_tax_amount_ccy" min="0" step="0.01" type="number">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_tax_amount_lcy" name="ap_tax_amount_lcy"
                                   value="{{$inserted_data->tax_amount_lcy}}">
                        </div>

                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 required pr-0" for="ap_exchange_rate">Exchange
                                    Rate</label>
                                <div class="col-md-4">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_exchange_rate" value="{{$inserted_data->exchange_rate}}"
                                           name="ap_exchange_rate">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_vat_amount_ccy">VAT Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="number" min="0" step="0.1"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%"
                                           id="ap_vat_amount_ccy_percentage"
                                           name="ap_vat_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">
                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_vat_amount_ccy" value="{{ $inserted_data->vat_amount_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_vat_amount_ccy" min="0" step="0.01" type="number">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_vat_amount_lcy" name="ap_vat_amount_lcy"
                                   value="{{ $inserted_data->vat_amount_ccy }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_security_deposit_amount_ccy">Security
                            Deposit </label>

                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="number" min="0" step="0.1"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%"
                                           id="ap_security_deposit_amount_ccy_percentage" value=""
                                           name="ap_security_deposit_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_security_deposit_amount_ccy" value="{{ $inserted_data->security_deposit_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_security_deposit_amount_ccy" min="0" step="0.01" type="number">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_security_deposit_amount_lcy"
                                   value="{{ $inserted_data->security_deposit_lcy }}"
                                   name="ap_security_deposit_amount_lcy">
                        </div>
                        <div class="col-md-5 d-flex justify-content-end">
                            <label class="col-form-label" style="text-decoration: underline">Payment Conditions</label>
                            <div class="col-md-5"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_extra_security_deposit_amount_ccy_percentage">Extra
                            Security
                            Deposit </label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <input type="number" min="0" step="0.1"
                                           class="form-control form-control-sm text-right-align"
                                           readonly
                                           placeholder="%"
                                           id="ap_extra_security_deposit_amount_ccy_percentage" value=""
                                           name="ap_extra_security_deposit_amount_ccy_percentage">
                                </div>
                                <div class="col-md-9">
                                    --}}{{--<input readonly type="number" min="0"
                                           step="0.1" class="form-control form-control-sm text-right-align"
                                           id="ap_security_deposit_amount_ccy"
                                           name="ap_security_deposit_amount_ccy">--}}{{--

                                    <input readonly class="form-control form-control-sm text-right-align"
                                           id="ap_extra_security_deposit_amount_ccy" value="{{ $inserted_data->extra_security_deposit_ccy }}"
                                           oninput="maxLengthValid(this)"
                                           name="ap_extra_security_deposit_amount_ccy" min="0" step="0.01"
                                           type="number">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_extra_security_deposit_amount_lcy"
                                   value="{{ $inserted_data->extra_security_deposit_lcy }}"
                                   name="ap_extra_security_deposit_amount_lcy">
                        </div>

                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 required pr-0" for="ap_payment_method">Payment
                                    Method</label>
                                <div class="col-md-4">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_method" value="{{$inserted_data->payment_methods_name}}"
                                           name="ap_payment_method">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_fine_forfeiture_ccy">Fine/Forfeiture</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_fine_forfeiture_ccy"
                                           value="{{$inserted_data->fine_forfeiture_amount_ccy}}"
                                           name="ap_fine_forfeiture_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_fine_forfeiture_lcy" value="{{$inserted_data->fine_forfeiture_amount_lcy}}"
                                   name="ap_fine_forfeiture_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 required pr-0" for="ap_payment_terms">Payment
                                    Terms</label>
                                <div class="col-md-4">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_terms" value="{{$inserted_data->payment_term_name}}"
                                           name="ap_payment_terms">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_preshipment_ccy">Preshipment Inspection
                            (PSI)</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_preshipment_ccy" value="{{$inserted_data->psi_amount_ccy}}"
                                           name="ap_preshipment_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_preshipment_lcy" value="{{$inserted_data->psi_amount_lcy}}"
                                   name="ap_preshipment_lcy">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 pr-0" for="ap_payment_due_date">Payment Due
                                    Date</label>
                                <div class="col-md-4 ">
                                    <input readonly type="text" class="form-control form-control-sm"
                                           id="ap_payment_due_date"
                                           value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->payment_due_date)}}"
                                           name="ap_payment_due_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_electricity_bill_ccy">Electricity Bill</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_electricity_bill_ccy"
                                           value="{{$inserted_data->electric_bill_amount_ccy}}"
                                           name="ap_electricity_bill_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_electricity_bill_lcy" value="{{$inserted_data->electric_bill_amount_lcy}}"
                                   name="ap_electricity_bill_lcy">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_other_charge_ccy">Other/Miscellanies Charge --}}{{--Other Charge (if any)--}}{{--</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_other_charge_ccy" value="{{$inserted_data->other_charge_amount_ccy}}"
                                           name="ap_other_charge_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_other_charge_lcy" value="{{$inserted_data->other_charge_amount_lcy}}"
                                   name="ap_other_charge_lcy">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_payable_amount_ccy">Net Payable Amount</label>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-9 offset-3">
                                    <input readonly type="text" class="form-control form-control-sm text-right-align"
                                           id="ap_payable_amount_ccy" value="{{$inserted_data->payable_amount_ccy}}"
                                           name="ap_payable_amount_ccy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm text-right-align"
                                   id="ap_payable_amount_lcy" value="{{$inserted_data->payable_amount_lcy}}"
                                   name="ap_payable_amount_lcy">
                        </div>
                    </div>
                </fieldset>--}}
                <fieldset class="col-md-12 border pl-1 pr-1">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Payment Options</legend>
                    <div class="row">
                        <label class="col-form-label col-md-2" style="text-decoration: underline" for="">Payment Conditions</label>

                        <div class="form-group col-md-3">
                            <label class="col-form-label " for="ap_payment_method">Payment
                                Method</label>
                            <input required name="ap_payment_method" value="{{$inserted_data->payment_methods_name}}" readonly class="form-control form-control-sm"
                                   id="ap_payment_method"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label " for="ap_payment_terms">Payment Terms</label>
                            <input required name="ap_payment_terms" value="{{$inserted_data->payment_term_name}}" readonly class="form-control form-control-sm"
                                   id="ap_payment_terms"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="ap_payment_due_date" class=" col-form-label">Payment Due Date</label>
                            <div class="input-group date ap_payment_due_date"
                                 id="ap_payment_due_date"
                                 data-target-input="nearest">
                                <input required type="text" autocomplete="off" onkeydown="return false" readonly
                                       name="ap_payment_due_date" value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->payment_due_date)}}"
                                       id="ap_payment_due_date_field"
                                       class="form-control form-control-sm"
                                       data-target="#ap_payment_due_date">
                                <div class="input-group-append" data-target="#ap_payment_due_date">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_hold_all_payment_reason">Payment Hold Reason</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" value="{{$inserted_data->payment_hold_reason}}" id="ap_hold_all_payment_reason"
                                   name="ap_hold_all_payment_reason" readonly>
                        </div>
                    </div>
                </fieldset>

                {{--Block this Pavel-28-08-22--}}
                {{--@if (isset($inserted_data->switch_payment_vendor_id))
                    --}}{{--Add this section start Pavel: 23-03-22--}}{{--
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 15px;">Switch Payment to Party/Vendor (Contra & Supplier For Provision Adjustment)</legend>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_id">Party/Vendor ID</label>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="input-group col-md-5">
                                        <input  name="ap_switch_pay_vendor_id" class="form-control form-control-sm " value="{{$inserted_data->switch_payment_vendor_id}}" type="text"
                                                id="ap_switch_pay_vendor_id" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_name">Party/Vendor Name</label>
                            <div class="col-md-10">
                                <input  type="text" class="form-control form-control-sm" id="ap_switch_pay_vendor_name" value="{{$inserted_data->switch_payment_vendor_name}}"
                                        name="ap_switch_pay_vendor_name" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_category">Party/Vendor Category</label>
                            <div class="col-md-10">
                                <input  type="text" class="form-control form-control-sm" id="ap_switch_pay_vendor_category" value="{{$inserted_data->switch_payment_vendor_category}}"
                                        name="ap_switch_pay_vendor_category"
                                        readonly>
                            </div>
                        </div>
                    </fieldset>
                    --}}{{--Add this section end Pavel: 23-03-22--}}{{--
                @endif--}}

                @if (count($inserted_data->invoice_line) > 0)
                    <fieldset class="col-md-12 border p-2">
                        <legend class="w-auto" style="font-size: 15px;">Transaction Detail
                        </legend>

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
                                    @forelse($inserted_data->invoice_line as $line)
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
                                        <td class="text-right-align">{{ $line->total_debit }}</td>
                                        <td class="text-right-align">{{ $line->total_credit }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                @endif
                @if (count($inserted_data->invoice_file) > 0)
                    <fieldset class="col-md-12 border p-2">
                        <legend class="w-auto" style="font-size: 15px;">Attachments
                        </legend>
                        <section>
                            @forelse($inserted_data->invoice_file as $file)
                                <p>File description: {{$file->doc_file_desc}} File: <a
                                        href="{{ route('invoice-bill-listing.download',['id'=>$file->doc_file_id]) }}">{{$file->doc_file_name}}</a>
                                </p>
                            @empty
                            @endforelse
                        </section>
                    </fieldset>
                @endif

                @include("ap.ap-common.common_authorizer")
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

                if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else if(approval_status == '{{\App\Enums\ApprovalStatus::CANCEL}}'){
                    approval_status_val = '{{\App\Enums\ApprovalStatus::CANCEL}}';
                    swal_input_type = 'text';
                }  else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Invoice Bill ' + approval_status_val,
                    type: 'warning',
                    input: swal_input_type,
                    inputPlaceholder: 'Reason For ' + approval_status_val+'?',
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

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
                <h4><span class="border-bottom-secondary border-bottom-2">Invoice/Bill Entry View</span></h4>
                <a href="{{route('invoice-bill-listing.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i
                            class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>

            @if (empty($inserted_data))
                <h6 class="text-center font-weight-bold mt-2">AP: Invoice Bill Data Not Found.</h6>
            @else
                {{--Workflow steps start--}}
                {!! \App\Helpers\HelperClass::workflow(\App\Enums\WkReferenceTable::FAS_AP_INVOICE, App\Enums\WkReferenceColumn::INVOICE_ID, $inserted_data->invoice_id, \App\Enums\WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL) !!}
                {{--Workflow steps end--}}

                <form id="invoice_bill_entry_form" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="border p-2">
                        <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Reference</legend>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="posting_name" id="ap_posting_name">
                                <input type="hidden" name="po_master_id" id="po_master_id">
                                <input type="hidden" name="invoice_id" id="invoice_id"
                                       value="{{ isset($inserted_data) ? $inserted_data->invoice_id : '' }}">
                                <div class="form-group row">
                                    <div class="offset-4 col-md-5 pl-0">
                                        <input class="form-check-input ml-1" type="checkbox" value="" id="chnTransRef"
                                            {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AP_MODULE_ID, \App\Enums\WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE,\App\Enums\RolePermissionsKey::CAN_EDIT_AP_INVOICE_MAKE )) ) ? 'disabled' : '' }} >
                                        <label class="form-check-label font-small-3 ml-3" for="chnTransRef">
                                            Change Trans Reference
                                        </label>
                                    </div>
                                </div>
                                <div class="viewDocumentRef">
                                    <div class="form-group row">
                                        <label for="posting_date_field" class="required col-md-4 col-form-label ">Batch
                                            ID</label>
                                        <div class="col-md-5">
                                            <input type="text" readonly tabindex="-1"
                                                   class="form-control form-control-sm"
                                                   value="{{ isset($inserted_data) ? $inserted_data->batch_id : '' }}">
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
                                                    value="{{ isset($inserted_data) ? $inserted_data->fiscal_year_id : '' }}">{{ isset($inserted_data) ? $inserted_data->fiscal_year_name : '' }}</option>
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
                                                    data-mindate="{{ isset($inserted_data) ? \App\Helpers\HelperClass::dateConvert($inserted_data->posting_period_beg_date) : '' }}"
                                                    data-maxdate="{{ isset($inserted_data) ? \App\Helpers\HelperClass::dateConvert($inserted_data->posting_period_end_date) : '' }}"
                                                    value="{{ isset($inserted_data) ? $inserted_data->trans_period_id : '' }}">{{ isset($inserted_data) ? $inserted_data->trans_period_name : '' }}</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                                            Date</label>
                                        <div class="col-md-5">
                                            <input type="text" readonly class="form-control form-control-sm"
                                                   id="posting_date"
                                                   value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="document_date_field" class=" col-md-4 col-form-label">Document
                                            Date</label>
                                        <div class="col-md-5">
                                            <input type="text" readonly class="form-control form-control-sm"
                                                   id="document_date"
                                                   value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->document_date)}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="editDocumentRef d-none">
                                    <div class="row">
                                        <div class="col-md-4"><label for="edt_batch_id" class="">Batch ID </label></div>
                                        <div class="col-md-5 form-group">
                                            <input type="text" class="form-control form-control-sm" name="edt_batch_id"
                                                   id="edt_batch_id"
                                                   value="{{ isset($inserted_data) ? $inserted_data->batch_id : '' }}"
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
                                                        {{isset($inserted_data) ? ($inserted_data->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '' : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
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
                                                    data-mindate="{{ isset($inserted_data) ? \App\Helpers\HelperClass::dateConvert($inserted_data->posting_period_beg_date) : '' }}"
                                                    data-maxdate="{{ isset($inserted_data) ? \App\Helpers\HelperClass::dateConvert($inserted_data->posting_period_end_date) : '' }}"
                                                    value="{{ isset($inserted_data) ? $inserted_data->trans_period_id : '' }}">{{ isset($inserted_data) ? $inserted_data->trans_period_name : '' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{--<div class="row d-none">
                                        <label for="period" class="required col-md-4 col-form-label">Posting
                                            Period</label>
                                        <div class="col-md-5">
                                            <select required name="edt_period" class="form-control form-control-sm"
                                                    id="period">
                                                --}}{{--<optin value="">Select a period</option>--}}{{--

                                            </select>
                                        </div>
                                    </div>--}}
                                    <div class="form-group row">
                                        <label for="edt_posting_date_field" class="required col-md-4 col-form-label ">Posting
                                            Date</label>
                                        <div class="input-group date posting_date col-md-5"
                                             id="edt_posting_date"
                                             data-target-input="nearest">
                                            <input required type="text" autocomplete="off" onkeydown="return false"
                                                   name="edt_posting_date"
                                                   id="edt_posting_date_field"
                                                   class="form-control form-control-sm datetimepicker-input"
                                                   data-target="#edt_posting_date"
                                                   data-toggle="datetimepicker"
                                                   {{--value="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)) }}"
                                                   data-predefined-date="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)) }}"--}}

                                                   value="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)) }}"
                                                   data-predefined-date="{{ old('edt_posting_date', \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date)) }}"
                                                   placeholder="DD-MM-YYYY">
                                            <div class="input-group-append edt_posting_date"
                                                 data-target="#edt_posting_date"
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
                                                   value="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($inserted_data->document_date)) }}"
                                                   data-predefined-date="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($inserted_data->document_date)) }}"
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
                                        <label for="edt_document_number" class="required col-md-4 col-form-label">Document
                                            No</label>
                                        <div class="col-md-5">
                                            <input maxlength="50" type="text" required
                                                   class="form-control form-control-sm"
                                                   oninput="this.value = this.value.toUpperCase()"
                                                   name="edt_document_number"
                                                   id="edt_document_number"
                                                   value="{{$inserted_data->document_no}}">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row  mb-25">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <a target="_blank" class="btn btn-sm btn-info cursor-pointer"
                                           href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$inserted_data->trans_period_id}}&p_trans_batch_id={{$inserted_data->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                                            <i class="bx bx-printer"></i>Print Voucher
                                        </a>
                                    </div>
                                </div>
                                {{--<div class="form-group row d-flex justify-content-end">
                                    --}}{{--<button type="button" class="btn btn-sm btn-outline-dark col-form-label mr-1">Print Voucher</button>--}}{{--
                                </div>--}}
                                <div class="viewDocumentRef">
                                    <div class="form-group row justify-content-end">
                                        <label for="department" class="col-form-label col-md-4 required ">Dept/Cost
                                            Center</label>
                                        <div class="col-md-5 pl-0">
                                            <input type="text" readonly class="form-control form-control-sm"
                                                   name="department" id="department"
                                                   value="{{$inserted_data->cost_center_dept_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-end">
                                        <label for="budget_department" class="col-form-label col-md-4 required ">Budget
                                            Department</label>
                                        <div class="col-md-5 pl-0">
                                            <input type="text" readonly class="form-control form-control-sm"
                                                   name="budget_department" id="budget_department"
                                                   value="{{$inserted_data->budget_dept_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group row  justify-content-end">
                                        <label for="bill_section" class="required col-md-4 col-form-label">Bill
                                            Section</label>
                                        <div class="col-md-5 pl-0">
                                            <input type="text" readonly class="form-control form-control-sm"
                                                   name="bill_section"
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
                                    <div class="form-group row  justify-content-end">
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

                                </div>

                                <div class="editDocumentRef d-none">
                                    <div class="form-group row justify-content-end">
                                        <label for="edt_department" class="col-form-label col-md-4 required ">Dept/Cost
                                            Center</label>
                                        <div class="col-md-5 pl-0">
                                            <select required name="edt_department" style="width: 100%"
                                                    class="form-control form-control-sm select2" id="edt_department">
                                                <option value="">&lt;Select&gt;</option>
                                                @foreach($department as $dpt)
                                                    <option
                                                        {{  old('department', $dpt->cost_center_dept_id) ==  $inserted_data->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-end">
                                        <label for="edt_budget_department" class="col-form-label col-md-4 required ">Budget
                                            Department</label>
                                        <div class="col-md-5 pl-0 ">
                                            {{--<input type="text" readonly class="form-control form-control-sm"
                                                   name="edt_budget_department" id="edt_budget_department"
                                                   value="{{$inserted_data->budget_dept_name}}">--}}
                                            <select required name="edt_budget_department"
                                                    class="form-control form-control-sm select2 "
                                                    id="edt_budget_department">
                                                <option value="">Select Budget Department</option>
                                                @foreach($department as $dpt)
                                                    <option
                                                        {{  old('budget_department',$inserted_data->budget_dept_id) ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex justify-content-end">
                                        <label for="edt_bill_section" class="required col-md-4 col-form-label">Bill
                                            Section</label>
                                        <div class="col-md-5 pl-0">
                                            <select readonly="" name="edt_bill_section"
                                                    class="form-control form-control-sm select2"
                                                    id="edt_bill_section">
                                                <option value="">&lt;Select&gt;</option>
                                                @foreach($billSecs as $value)
                                                    <option
                                                        {{  $inserted_data->bill_sec_id ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-end">
                                        <label for="edt_bill_register" class="required col-md-4 col-form-label">Bill
                                            Register</label>
                                        <div class="col-md-5 pl-0">
                                            <select required name="edt_bill_register"
                                                    class="form-control form-control-sm select2"
                                                    data-bill-register-id="{{$inserted_data->bill_reg_id}}"
                                                    id="edt_bill_register">
                                                {{--<option
                                                    value="{{ $inserted_data->bill_reg_id }}">{{ $inserted_data->bill_reg_name }}</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row justify-content-end">
                                        <label for="edt_document_reference" class="col-md-4 col-form-label">Document
                                            Ref</label>
                                        <div class="col-md-5 pl-0">
                                            <input maxlength="200" type="text" class="form-control form-control-sm"
                                                   id="edt_document_reference"
                                                   name="edt_document_reference"
                                                   value="{{$inserted_data->document_ref}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editDocumentRef d-none">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="narration" class="required col-form-label">Narration</label>
                                </div>
                                <div class="col-md-10">
                            <textarea maxlength="500" required name="edt_narration"
                                      class="required form-control form-control-sm"
                                      id="edt_narration">{{$inserted_data->narration}}</textarea>
                                    {{--<button type="button" disabled class="btn btn-sm btn-light mt-1" id="updateTrans">
                                        Update Changes
                                    </button>--}}
                                </div>
                            </div>
                        </div>

                        <div class="viewDocumentRef">
                            <div class="form-group row">
                                <label for="document_number" class=" col-md-2 col-form-label">Document
                                    No</label>
                                <div class="col-md-3">
                                    <input maxlength="25" type="text" readonly
                                           class="form-control form-control-sm"
                                           name="document_number"
                                           id="document_number"
                                           value="{{$inserted_data->document_no}}">
                                </div>

                                <label for="document_reference" class="col-md-2 col-form-label text-right-align">Document
                                    Ref</label>
                                <div class="col-md-5 d-flex">
                                    <input maxlength="25" readonly type="text"
                                           class="form-control form-control-sm justify-content-end"
                                           id="document_reference"
                                           name="document_reference"
                                           value="{{$inserted_data->document_ref}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="narration" class="required col-form-label">Narration</label>
                                </div>
                                <div class="col-md-10">
                            <textarea maxlength="500" required name="narration"
                                      class="required form-control form-control-sm" readonly
                                      id="narration">{{$inserted_data->narration}}</textarea>
                                    {{--<button type="button" disabled class="btn btn-sm btn-light mt-1" id="updateTrans">
                                        Update Changes
                                    </button>--}}
                                </div>
                            </div>
                        </div>

                        {{--<div class="editDocumentRef d-none">
                            <div class="form-group row">
                                <label for="edt_document_reference" class="col-md-2 col-form-label">Document Ref</label>
                                <div class="col-md-10">
                                    <input maxlength="200" type="text" class="form-control form-control-sm"
                                           id="edt_document_reference"
                                           name="edt_document_reference"
                                           value="{{$inserted_data->document_ref}}">
                                </div>
                            </div>
                        </div>--}}

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
                                    <label for="ap_purchase_order_date" class="col-form-label col-md-4">Purchase Order
                                        No</label>
                                    <div class="col-md-5 pl-0">
                                        <input type="text" id="ap_purchase_order_date" readonly
                                               class="form-control form-control-sm"
                                               value="{{isset($inserted_data->po_number) ? $inserted_data->po_number : 'N/A'}}"
                                               name="ap_purchase_order_date"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row d-flex justify-content-end">
                                    <label for="ap_purchase_order_date" class="col-form-label col-md-4">Purchase Order
                                        Date</label>
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
                                        <input required name="ap_vendor_id" readonly
                                               class="form-control form-control-sm "
                                               value="{{$inserted_data->vendor_id}}" type="number"
                                               id="ap_vendor_id"
                                               maxlength="10"
                                               oninput="maxLengthValid(this)"
                                               onkeyup="resetField(['#ap_vendor_name','#ap_vendor_category']);enableDisablePoCheck(0)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_vendor_name">Vendor Name</label>
                            <div class="col-md-10">
                                <input required type="text" class="form-control form-control-sm" id="ap_vendor_name"
                                       name="ap_vendor_name" readonly value="{{$inserted_data->vendor_name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="ap_vendor_category">Vendor Category</label>
                            <div class="col-md-10">
                                <input required type="text" class="form-control form-control-sm" id="ap_vendor_category"
                                       name="ap_vendor_category" value="{{$inserted_data->vendor_category_name}}"
                                       readonly>
                            </div>
                        </div>

                    </fieldset>
                    @if (isset($inserted_data->budget_head_id))
                        <fieldset class="border pl-1 pr-1">
                            <legend class="w-auto" style="font-size: 15px;">{{--Budget Booking/Utilized Info--}}Budget
                                Head Info
                            </legend>

                            {{--Block this section start Pavel: 24-03-22--}}
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
                            {{--Block this section start Pavel: 24-03-22--}}

                            <div class="form-group row">
                                <label for="b_head_id" class=" col-md-2 col-form-label required">Budget Head</label>
                                <div class="input-group col-md-2">
                                    <input readonly type="text"
                                           class="form-control form-control-sm" name="b_head_id"
                                           id="b_head_id"
                                           value="{{ $inserted_data->budget_head_id }}">
                                </div>
                                <div class="col-md-4">
                                    <button disabled class="btn btn-sm btn-primary bookingIdSearch"
                                            id="b_booking_search"
                                            type="button"
                                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                            class="align-middle ">Get Budget Booking Info</span>
                                    </button>
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
                                    <input type="text" readonly class="form-control form-control-sm"
                                           name="b_sub_category"
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
                    <fieldset class="border p-2 d-none">
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
                                        <input readonly type="text"
                                               class="form-control form-control-sm text-right-align"
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
                                               id="ap_tax_amount_ccy_percentage"
                                               value="{{$inserted_data->tax_amount_pct}}"
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
                                               id="party_name_for_tax" value="{{$inserted_data->tax_party_name}}"
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
                                               id="party_name_for_vat" value="{{$inserted_data->vat_party_name}}"
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
                                               id="ap_security_deposit_amount_ccy_percentage"
                                               value="{{$inserted_data->security_deposit_pct}}"
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
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2"
                                   for="ap_extra_security_deposit_amount_ccy_percentage">Extra
                                Security
                                Deposit </label>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-3 pr-0">
                                        <input type="text"
                                               class="form-control form-control-sm text-right-align"
                                               readonly
                                               placeholder="%"
                                               id="ap_extra_security_deposit_amount_ccy_percentage"
                                               value="{{$inserted_data->extra_security_deposit_pct}}"
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
                            <label class="col-form-label col-md-2" for="ap_other_charge_ccy">Additional
                                Account {{--Other Charge (if any)--}}</label>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-9 offset-3">
                                        <input readonly type="text"
                                               class="form-control form-control-sm text-right-align"
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
                                    <label class="col-form-label col-md-5 required pr-0" for="ap_payment_currency">Payment
                                        Currency</label>
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
                            <label class="col-form-label col-md-2" for="ap_payable_amount_ccy">Net Payable
                                Amount</label>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-9 offset-3">
                                        <input readonly type="text"
                                               class="form-control form-control-sm text-right-align"
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
                                    <label class="col-form-label col-md-5 required pr-0" for="ap_exchange_rate">Exchange
                                        Rate</label>
                                    <div class="col-md-6">
                                        <input readonly type="text" class="form-control form-control-sm"
                                               id="ap_exchange_rate" value="{{$inserted_data->exchange_rate}}"
                                               name="ap_exchange_rate">
                                    </div>
                                </div>
                            </div>
                            {{--<div class="col-md-5">
                                <div class="form-group row d-flex justify-content-end">
                                    <label class="col-form-label col-md-5 pr-0" for="ap_payment_due_date">Payment Due
                                        Date</label>
                                    <div class="col-md-6">
                                        <input readonly type="text" class="form-control form-control-sm"
                                               id="ap_payment_due_date"
                                               value="{{\App\Helpers\HelperClass::dateConvert($inserted_data->payment_due_date)}}"
                                               name="ap_payment_due_date">
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                    </fieldset>
                    <fieldset class="col-md-12 border pl-1 pr-1">
                        <legend class="w-auto font-weight-bold" style="font-size: 15px">Payment Options</legend>
                        <div class="row">
                            <label class="col-form-label col-md-2" style="text-decoration: underline" for="">Payment
                                Conditions</label>

                            <div class="form-group col-md-3">
                                <label class="col-form-label " for="ap_payment_method">Payment
                                    Method</label>
                                <input required name="ap_payment_method"
                                       value="{{$inserted_data->payment_methods_name}}" readonly
                                       class="form-control form-control-sm"
                                       id="ap_payment_method"/>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="col-form-label " for="ap_payment_terms">Payment Terms</label>
                                <input required name="ap_payment_terms" value="{{$inserted_data->payment_term_name}}"
                                       readonly class="form-control form-control-sm"
                                       id="ap_payment_terms"/>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="ap_payment_due_date" class=" col-form-label">Payment Due Date</label>
                                <div class="input-group date ap_payment_due_date"
                                     id="ap_payment_due_date"
                                     data-target-input="nearest">
                                    <input required type="text" autocomplete="off" onkeydown="return false" readonly
                                           name="ap_payment_due_date"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->payment_due_date)}}"
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
                            <label class="col-form-label col-md-2" for="ap_hold_all_payment_reason">Payment Hold
                                Reason</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control form-control-sm"
                                       value="{{$inserted_data->payment_hold_reason}}" id="ap_hold_all_payment_reason"
                                       name="ap_hold_all_payment_reason" readonly>
                            </div>
                        </div>
                    </fieldset>

                    {{--Block this Pavel-28-08-22--}}
                    {{--@if (isset($inserted_data->switch_payment_vendor_id))
                        --}}{{--Add this section start Pavel: 23-03-22--}}{{--
                        <fieldset class="border pl-1 pr-1">
                            <legend class="w-auto" style="font-size: 15px;">Switch Payment to Party/Vendor (Contra &
                                Supplier For Provision Adjustment)
                            </legend>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_id">Party/Vendor
                                    ID</label>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="input-group col-md-5">
                                            <input name="ap_switch_pay_vendor_id" class="form-control form-control-sm "
                                                   value="{{$inserted_data->switch_payment_vendor_id}}" type="text"
                                                   id="ap_switch_pay_vendor_id" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_name">Party/Vendor
                                    Name</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-control-sm"
                                           id="ap_switch_pay_vendor_name"
                                           value="{{$inserted_data->switch_payment_vendor_name}}"
                                           name="ap_switch_pay_vendor_name" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_category">Party/Vendor
                                    Category</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-control-sm"
                                           id="ap_switch_pay_vendor_category"
                                           value="{{$inserted_data->switch_payment_vendor_category}}"
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

                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-sm table-hover table-bordered " id="ap_account_table">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th width="2%" class="">Account ID</th>
                                            <th width="28%" class="">Account Name</th>
                                            <th width="28%" class="">Party Code</th>
                                            <th width="28%" class="">Party Name</th>
                                            <th width="5%" class="text-right-align">Debit</th>
                                            <th width="5%" class="text-right-align">Credit</th>
                                            {{-- <th width="16%" class="text-center">Amount CCY</th>
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
                                            <td class="text-right-align">{{ $inserted_data->invoice_line[0]->total_debit }}</td>
                                            <td class="text-right-align">{{ $inserted_data->invoice_line[0]->total_credit }}</td>
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
                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <a href="{{ route('invoice-bill-listing.index',['filter'=>(isset($filter) ? $filter : '')]) }}" class="btn btn-sm btn-dark">
                                <i class="bx bx-reset"></i>Back
                            </a>
                            <button type="button" class="btn btn-sm btn-info d-none" id="updateReference"><i
                                    class="bx bx-up-arrow-alt"></i>Update
                                Changes
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @include('ap.invoice-bill-entry.common_budged_search')

@endsection

@section('footer-script')
    <script type="text/javascript">

        $(document).ready(function () {
            /**Update Transaction Reference Start**/
            $("#chnTransRef").on('change', function () {
                if ($(this).is(":checked")) {
                    $(".viewDocumentRef").addClass('d-none');
                    $(".editDocumentRef").removeClass('d-none');
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');

                    //$("#edt_bill_register").select2().val('{{isset($inserted_data->bill_reg_id) ? $inserted_data->bill_reg_id : ''}}').trigger('change');
                    //$("#edt_bill_section").html('<option value="'+$("#edt_bill_register :selected").data('secid')+'">'+$("#edt_bill_register :selected").data('secname')+'</option>');

                    $("#edt_bill_section").select2().val('{{isset($inserted_data->bill_sec_id) ? $inserted_data->bill_sec_id : ''}}').css('width:', '100%');
                    $("#edt_bill_section").select2().trigger('change');

                    $("#edt_department").select2().val('{{isset($inserted_data->cost_center_dept_id) ? $inserted_data->cost_center_dept_id : ''}}').trigger('change');

                    $("#updateReference").removeClass('d-none');
                    $("#b_booking_search").removeAttr('disabled');

                    /*$("#edt_fiscal_year").val($("#th_fiscal_year :selected").val()).trigger('change');
                    $("#edt_period").val($("#period :selected").val());
                    console.log($("#period :selected").val())
                    setDefaultDate()*/
                    let defaultPeriod = $("#edt_period :selected").val();
                    let defaultPostingDate = $("#edt_posting_date_field").val();
                    let defaultDocumentDate = $("#edt_document_date_field").val();
                    getPostingPeriod($("#edt_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
                    function setPostingPeriod(periods) {    //Over writing setPostingPeriod
                        $("#edt_period").html(periods);
                        $("#edt_period").val(defaultPeriod).trigger('change');

                        $("#edt_posting_date_field").val(defaultPostingDate);
                        $("#edt_document_date_field").val(defaultDocumentDate);
                    }

                } else {
                    $("#b_booking_search").attr('disabled', 'disabled');
                    $(".viewDocumentRef").removeClass('d-none');
                    $(".editDocumentRef").addClass('d-none');
                    $("#updateReference").addClass('d-none');
                }
            });

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;

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

                //setPeriodCurrentDate();
            });

            function setDefaultDate() {
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentPostingDate = $("#posting_date").val();
                let currentDocumentDate = $("#document_date").val();
                datePickerOnPeriod("#edt_posting_date", minDate, maxDate, currentPostingDate, false);
                datePickerOnPeriod("#edt_document_date", minDate, maxDate, currentDocumentDate, false);
            }

            function setPeriodCurrentDate() {
                let minDate = $("#edt_period :selected").data("mindate");
                let maxDate = $("#edt_period :selected").data("maxdate");
                let currentDate = $("#edt_period :selected").data('currentdate');
                datePickerOnPeriod("#edt_posting_date", minDate, maxDate, currentDate, false);
                datePickerOnPeriod("#edt_document_date", minDate, maxDate, currentDate, false);
            }


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
                    /*newDueDate = moment($("#edt_posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");*/
                    $("#edt_document_date >input").val(newDueDate);
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

            function listBillRegister() {
                $('#edt_bill_section').change(function (e) {
                    $("#edt_bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            listBillRegister();

            /********Added on: 06/06/2022, sujon**********/
            function setBillSection() {
                $("#edt_bill_register").change(function (e) {
                    $bill_sec_id = $("#edt_bill_register :selected").data('secid');
                    $bill_sec_name = $("#edt_bill_register :selected").data('secname');
                    if (!nullEmptyUndefinedChecked($bill_sec_id)) {
                        $("#edt_bill_section").html("<option value='" + $bill_sec_id + "'>" + $bill_sec_name + "</option>")
                    } else {
                        $("#edt_bill_section").html("<option value=''></option>")
                    }
                });
            }

            //setBillSection();
            /********End**********/

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

                        let invoiceId = $("#invoice_id").val();
                        let postingPeriod = $("#edt_period :selected").val();
                        let postingDate = $("#edt_posting_date_field").val();
                        let documentDate = $("#edt_document_date_field").val();
                        let documentNumber = $("#edt_document_number").val();
                        let documentRef = $("#edt_document_reference").val();
                        let documentNarration = $("#edt_narration").val();
                        let department = $("#edt_department :selected").val();
                        let budgetDepartment = $("#edt_budget_department :selected").val();
                        let billSection = $("#edt_bill_section :selected").val();
                        let billRegister = $("#edt_bill_register :selected").val();
                        let budgetHead = $("#b_head_id").val();


                        let request = $.ajax({
                            url: APP_URL + "/account-payable/invoice-bill-listing-update",
                            data: {
                                invoiceId, postingPeriod, postingDate, documentDate, documentNumber, documentRef,
                                documentNarration, department,budgetDepartment, billSection, billRegister, budgetHead
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
                                    let urlStr = '{{ route('invoice-bill-listing.view',['id'=>'_p']) }}';
                                    window.location.href = urlStr.replace('_p', invoiceId);
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
            $("#edt_fiscal_year").on('change', function () {
                getPostingPeriod($("#edt_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            //getPostingPeriod($("#edt_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            function setPostingPeriod(periods) {
                $("#edt_period").html(periods);
                //setPeriodCurrentDate();
                $("#edt_period").trigger('change');
            }
        });

        /*
        * Budget list search starts from here
        * */
        $("#b_booking_search").on("click", function () {
            //let bookingId = $('#b_booking_id').val();
            let department = $('#edt_budget_department :selected').val();
            let calendar = $('#edt_fiscal_year :selected').val(); // $('#fiscal_year :selected').val();

            //let vendorId = $('#ap_vendor_id').val(); // block this sec -Pavel: 24-03-22

            resetBudgetField();
            //resetBudgetHeadBookingTables();

            // if ( !nullEmptyUndefinedChecked(department) && !nullEmptyUndefinedChecked(vendorId) ) { // block this sec -Pavel: 24-03-22
            if (!nullEmptyUndefinedChecked(department)) {
                /*if (!nullEmptyUndefinedChecked(bookingId)) {
                    getBudgetBookingDetailInfo(bookingId, department, calendar);
                } else {*/
                //$('#b_booking_id').val("") //Remove this line when open if condition // block this sec -Pavel: 24-03-22

                $('#b_head_id').val("") // Add this sec -Pavel: 24-03-22
                reloadBudgetListTable();

                $("#s_fiscal_year").val($("#edt_fiscal_year").text().trim()); //val($("#fiscal_year").text().trim());
                $("#s_department").val($("#edt_budget_department :selected").text());
                //$("#s_part_vendor_id").val($("#ap_vendor_id").val());
                //$("#s_budget_head_name_code").val('');

                $("#budget_booking_list").data("dt_params", {
                    "department": $('#edt_budget_department :selected').val(),
                    "calendar": $('#edt_fiscal_year :selected').val(), //$('#fiscal_year :selected').val(),
                    "nameCode": $('#s_budget_head_name_code').val(),
                    "vendorId": $('#ap_vendor_id').val()
                }).DataTable().draw();

                $("#budgetListModal").modal('show');
                /*}*/
            } else {

                /*** Block this sec start -Pavel: 24-03-22 ***/
                /*resetField(['#b_booking_id']);

                if (nullEmptyUndefinedChecked(vendorId)) {
                    $("#ap_vendor_id").focus();
                    $('html, body').animate({scrollTop: ($("#ap_vendor_id").offset().top - 200)}, 2000);
                    $("#ap_vendor_id").notify("Please Add Vendor ID.", {position: 'right'});
                } else if ( nullEmptyUndefinedChecked(department) ){
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});
                }*/
                /*** Block this sec end -Pavel: 24-03-22 ***/

                /*** Add this sec start -Pavel: 24-03-22 ***/
                resetField(['#b_head_id']);
                if (nullEmptyUndefinedChecked(department)) {
                    $("#edt_budget_department").focus();
                    $('html, body').animate({scrollTop: ($("#edt_budget_department").offset().top - 400)}, 2000);
                    $("#edt_budget_department").notify("Select Department First.", {position: 'left'});
                }
                /*** Add this sec end -Pavel: 24-03-22 ***/

            }

        })
        $(document).on('submit', '#booking_search_form', function (e) {
            e.preventDefault();
            //resetBudgetHeadBookingTables();

            /*$("#budget_head_list").data("dt_params", {*/
            $("#budget_booking_list").data("dt_params", {
                "department": $('#budget_department :selected').val(),
                "calendar": $('#th_fiscal_year :selected').val(), //$('#fiscal_year :selected').val(),
                "nameCode": $('#s_budget_head_name_code').val(),
                "vendorId": $('#ap_vendor_id').val()
            }).DataTable().draw();
        })
        let budgetBookingTable = $('#budget_booking_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-payable/ajax/budget-booking-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    let dt_params = $("#budget_booking_list").data('dt_params');
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            /*** Block this sec start  -Pavel: 24-03-22 ***/
            /*"columns": [
                {"data": 'booking_id', "name": 'booking_id'},
                {"data": "booking_date"},
                {"data": "budget_booking_amount"},
                {"data": "budget_head_name"},
                {"data": "budget_category_name"},
                {"data": "budget_type_name"},
                {"data": "action", "orderable": false}
            ],*/
            /*** Block this sec end  -Pavel: 24-03-22 ***/

            /*** Add this sec start  -Pavel: 24-03-22 ***/
            "columns": [
                {"data": 'budget_head_id', "name": 'budget_head_id'},
                {"data": "budget_head_name"},
                {"data": "budget_booking_amt"},
                {"data": "budget_utilized_amt"},
                {"data": "available_amount"},
                {"data": "action", "orderable": false}
            ],
            /*** Add this sec start  -Pavel: 24-03-22 ***/
        });
        $(document).on('click', '.budgetSelect', function () {

            //let bookingId = $(this).data('bookingid'); /*** Block this sec -Pavel: 24-03-22 ***/
            let budgetHeadId = $(this).data('budget-head-id');
            /*** Add this sec -Pavel: 24-03-22 ***/
            let department = $('#edt_budget_department :selected').val();
            let calendar = $('#edt_fiscal_year :selected').val();  //$('#fiscal_year :selected').val();
            //console.log(budgetHeadId, this);
            //getBudgetBookingDetailInfo(bookingId, department, calendar) /*** Block this sec -Pavel: 24-03-22 ***/
            getBudgetBookingDetailInfo(budgetHeadId, department, calendar)
        });

        /*
        * Budget list search ends here
        * */

        function getBudgetBookingDetailInfo(budgetHeadId, department, calendar) {
            /*var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                data: {budget_booking_id: bookingId, department: department, calendar: calendar}
            });*/

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                data: {budget_head_id: budgetHeadId, department: department, calendar: calendar}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.data)) {
                    //$("#b_booking_id").notify("Budget Booking ID Not Found", "error"); // block this sec -Pavel: 24-03-22
                    /*resetField(['#b_head_id',
                        '#b_date', '#b_amt','#b_available_amt', '#b_head_name', // block this sec -Pavel: 24-03-22
                        '#b_sub_category', '#b_category', '#b_type']);*/

                    /*** Add this sec start  -Pavel: 24-03-22 ***/
                    $("#b_head_id").notify("Budget Head ID Not Found", "error");
                    resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
                    /*** Add this sec end  -Pavel: 24-03-22 ***/

                } else {
                    //$('#b_booking_id').val(d.data.budget_booking_id); //block this sec -Pavel: 24-03-22
                    $('#b_head_id').val(d.data.budget_head_id);
                    //$('#b_date').val(d.data.budget_booking_date); // block this sec -Pavel: 24-03-22
                    $('#b_amt').val(d.data.budget_booking_amt);
                    $('#b_head_name').val(d.data.budget_head_name);
                    $('#b_sub_category').val(d.data.sub_category_name);
                    $('#b_category').val(d.data.category_name);
                    $('#b_type').val(d.data.budget_type);
                    $('#b_utilized_amt').val(d.data.budget_utilized_amt); //Add this sec -Pavel: 24-03-22
                    $('#b_available_amt').val(d.data.available_amount);
                }
                $("#budgetListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        function reloadBudgetListTable() {
            budgetBookingTable.draw();
            //budgetTable.draw();
        }

        function resetBudgetField() {
            /*resetField(['#b_head_id',
                '#b_date', '#b_amt','#b_available_amt', '#b_head_name',  //Block this sec -Pavel: 24-03-22
                '#b_sub_category', '#b_category', '#b_type']);*/

            //Add this sec -Pavel: 24-03-22
            resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt',
                '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
        }

        function resetBudgetHeadBookingTables() {
            /*$("#budget_head_list").data("dt_params", {
                "department": "",
                "calendar": "",
                "nameCode": ""
            }).DataTable().draw();*/
            $('#budget_booking_list').data('dt_params', {
                "budget_head_id": "",
                "department": "",
                "calendar": "",
                "vendorId": ""
            }).DataTable().draw();
        }

        /*
        * Budget Head search ends here
        * */

        /*
        * Budget search ends here
        * */

    </script>
@endsection

<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <h5 style="text-decoration: underline">Search Invoice/Bill Authorize</h5>
        @if(Session::has('message'))
            <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                 role="alert">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <fieldset class="border col-md-12">
                <legend class="w-auto" style="font-size: 15px;">Search Criteria
                </legend>
                <div class="col-md-12">
                    <form method="POST" id="invoice-bill-search-form">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="th_fiscal_year" class="">Fiscal Year</label>
                                <select name="th_fiscal_year"
                                        class="form-control form-control-sm required select2 search-param"
                                        id="th_fiscal_year">
                                    @foreach($fiscalYear as $year)
                                        <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="period" class="">Posting Period</label>
                                <select class="form-control form-control-sm select2 search-param" id="period" name="period" required>
                                    {{--@foreach($data['postingDate'] as $post)
                                        <option
                                            {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                            data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                            data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                            data-postingname="{{ $post->posting_period_name}}"
                                            value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                        </option>
                                    @endforeach--}}
                                </select>
                            </div>
                            {{-- Start Add Block Pavel-08-06-22/09-06-22 --}}
                            <div class="form-group col-md-2">
                                <label for="bill_section" class="">Bill Section</label>
                                <select name="bill_section" class="form-control form-control-sm select2 search-param"
                                        id="bill_section">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['billSecs'] as $value)
                                        <option {{isset($filterData) ? (($value->bill_sec_id == $filterData[2]) ? 'selected' : '') : ''}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                        {{--<option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>--}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="bill_reg_id" class="">Bill Register</label>
                                <select class="form-control form-control-sm select2 search-param" id="bill_reg_id" name="bill_reg_id">
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="approval_status" class="">Approval Status</label>
                                <select name="approval_status" class="form-control form-control-sm select2 search-param"
                                        id="approval_status">
                                    <option value="">&lt;Select&gt;</option>
                                    {{--<option value="">Select Status</option>--}}
                                    {{--@foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        <option value="{{$key}}">{{ $value}}
                                        </option>
                                    @endforeach--}}
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        <option {{isset($filterData) ? (($key == $filterData[4]) ? 'selected' : '') : ''}} value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} >{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- End Add Block Pavel-08-06-22/09-06-22 --}}
                        </div>

                    </form>


                    {{--<form method="POST" id="invoice-bill-search-form">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="ap_party_sub_ledger" class="">Party Sub-Ledger</label>
                                <select class="custom-select form-control form-control-sm select2" id="ap_party_sub_ledger"
                                        name="ap_party_sub_ledger">
                                    <option value="">Select Party Sub Ledger</option>
                                    @foreach($data['subsidiary_type'] as $type)
                                        <option
                                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                    @endforeach
                                </select>
                            </div>

--}}{{--                            <div class="form-group col-md-3">--}}{{--
--}}{{--                                <label for="ap_invoice_type" class="">Invoice Type</label>--}}{{--
--}}{{--                                <select  class="form-control form-control-sm" id="ap_invoice_type" name="ap_invoice_type">--}}{{--
--}}{{--                                    <option value="">Select Invoice Type</option>--}}{{--
--}}{{--                                </select>--}}{{--
--}}{{--                            </div>--}}{{--

                            <div class="form-group col-md-6">
                                <label for="ap_customer" class="">Customer</label>
                                <select  class="form-control form-control-sm" id="ap_customer" name="ap_customer">
                                    <option value="">Select Customer</option>
                                    @foreach($data['customers'] as $type)
                                        <option
                                            value="{{$type->customer_id}}">{{$type->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="period" class="required">Posting Period</label>
                                <select class="custom-select form-control form-control-sm select2" id="period" name="period" required>
                                    @foreach($data['postingDate'] as $post)
                                        <option
                                            {{  ((old('period') ==  $post->calendar_detail_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                            data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                            data-postingname="{{ $post->posting_period_display_name}}"
                                            value="{{$post->calendar_detail_id}}">{{ $post->posting_period_display_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="posting_date_field" class="">Posting Date</label>
                                <div class="input-group date posting_date"
                                     id="posting_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
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
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="posting_batch_id" class="">Posting Batch Id</label>
                                <input class="form-control form-control-sm" id="posting_batch_id" name="posting_batch_id"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="ap_document_no" class="">Document No</label>
                                <input class="form-control form-control-sm" id="ap_document_no" name="ap_document_no"/>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="department" class="col-form-label">Dept/Cost Center</label>
                                <select name="department" class="form-control form-control-sm select2" id="department">
                                    <option value="">Select Dept/Cost Center</option>
                                    @foreach($data['department'] as $dpt)
                                        <option
                                            {{  old('department') ==  $dpt->department_id ? "selected" : "" }} value="{{$dpt->department_id}}"> {{ $dpt->department_name}} </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="bill_section" class="col-form-label">Bill Section</label>
                                <select  name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                                    <option value="">Select a bill</option>
                                    @foreach($data['billSecs'] as $value)
                                        <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="bill_reg_id" class="col-form-label">Bill Register</label>
                                <select class="form-control form-control-sm" id="bill_reg_id" name="bill_reg_id">
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="approval_status" class="col-form-label">Approval Status</label>
                                <select  name="approval_status" class="form-control form-control-sm select2" id="approval_status">
                                    <option value="">Select Status</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        --}}{{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}{{--
                                        <option
                                            value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} > {{ $value}} </option>
                                        --}}{{--@endif--}}{{--
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end pl-0 ">
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary mb-2 "><i
                                            class="bx bx-search"></i><span
                                            class="align-middle ">Search</span></button>
                                    <button type="button" class="btn btn-secondary mb-2" id="reset"><i
                                            class="bx bx-reset"></i><span class="align-middle">Reset</span></button>
                                    <button type="reset" class="btn btn-secondary mb-2 d-none" id="resetMain"></button>
                                </div>
                            </div>
                        </div>

                    </form>--}}
                </div>
            </fieldset>
        </div>
    </div>
</div>

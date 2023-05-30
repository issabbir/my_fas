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
                            <div class="form-group col-md-3">
                                <label for="period" class="required">Posting Period</label>
                                <select class="custom-select form-control form-control-sm select2" id="period" name="period" required>
                                    @foreach($data['postingDate'] as $post)
                                        <option
                                            {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }}
                                            data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                            data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                            data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                            data-postingname="{{ $post->posting_period_name}}"
                                            value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
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
                                            <i class="bx bxs-calendar font-size-small"></i>
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
                                <label for="ap_party_sub_ledger" class="">Party Sub-Ledger</label>
                                <select class="custom-select form-control form-control-sm select2" id="ap_party_sub_ledger"
                                        name="ap_party_sub_ledger">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['subsidiary_type'] as $type)
                                        <option
                                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="ap_invoice_type" class="">Invoice Type</label>
                                <select  class="form-control form-control-sm" id="ap_invoice_type" name="ap_invoice_type">
                                    <option value="">&lt;Select&gt;</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ap_vendor" class="">Vendor</label>
                                <select  class="form-control form-control-sm" id="ap_vendor" name="ap_vendor">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['vendors'] as $type)
                                        <option
                                            value="{{$type->vendor_id}}">{{$type->vendor_name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="department" class="col-form-label">Dept/Cost Center</label>
                                <select name="department" class="form-control form-control-sm select2" id="department">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['department'] as $dpt)
                                        <option
                                            {{  old('department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="bill_section" class="col-form-label">Bill Section</label>
                                <select  name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['billSecs'] as $value)
                                        <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="bill_reg_id" class="col-form-label">Bill Register</label>
                                <select class="form-control form-control-sm" id="bill_reg_id" name="bill_reg_id">
                                    <option value="">&lt;Select&gt;</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="approval_status" class="col-form-label">Approval Status</label>
                                <select  name="approval_status" class="form-control form-control-sm select2" id="approval_status">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        <option value="{{$key}}">{{ $value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end pl-0 ">
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary mb-2 "><i
                                            class="bx bx-search font-size-small"></i><span
                                            class="align-middle ">Search</span></button>
                                    <button type="button" class="btn btn-sm btn-secondary mb-2" id="reset"><i
                                            class="bx bx-reset font-size-small"></i><span class="align-middle">Reset</span></button>
                                    <button type="reset" class="btn btn-sm btn-secondary mb-2 d-none" id="resetMain"></button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>

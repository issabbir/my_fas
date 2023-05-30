<?php
/**
 *Created by PhpStorm
 *Created at ২৬/৯/২১ ১২:০২ PM
 */
?>
<div class="card">
    <div class="card-body">
        <h5 style="text-decoration: underline">Clearing Reconciliation</h5>
        <div class="row">
            <fieldset class="border col-md-12">
                <legend class="w-auto" style="font-size: 15px;">Search Criteria
                </legend>
                <div class="col-md-12">
                    <form method="POST" id="reconciliation-search-form">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="function_type" class="required col-form-label">Function Type</label>
                                <select class=" form-control form-control-sm" id="function_type" name="function_type"
                                        required>
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['functionType'] as $type)
                                        <option
                                            {{  (old('function_type') ==  $type->function_id) ? "selected" : "" }}
                                            value="{{$type->function_id}}">{{ $type->function_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{--<div class="form-group col-md-4">
                                <label for="period" class="required col-form-label">Posting Period</label>
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
                            </div>--}}
                            <div class="form-group col-md-4">
                                <label for="ap_bank_account" class="col-form-label">Bank Account</label>
                                <select class="form-control form-control-sm" id="ap_bank_account"
                                        name="ap_bank_account">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['bank'] as $type)
                                        <option
                                            value="{{$type->gl_acc_id}}">{{$type->gl_acc_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mt-1">
                                <div class="mt-1">
                                    <button type="submit" class="btn btn-sm btn-primary "><i
                                            class="bx bx-search font-size-small"></i><span
                                            class="align-middle ">Search</span></button>
                                    <button type="button" class="btn btn-sm btn-secondary ml-1" id="reset"><i
                                            class="bx bx-reset font-size-small"></i><span
                                            class="align-middle">Reset</span></button>
                                    <button type="reset" class="btn btn-sm btn-secondary  d-none"
                                            id="resetMain"></button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{--<div class="form-group col-md-3">
                                <label for="bill_section" class="col-form-label">Bill Section</label>
                                <select name="bill_section" class="form-control form-control-sm select2" id="bill_section">
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
                                <select name="approval_status" class="form-control form-control-sm" id="approval_status">
                                    <option value="">Select Status</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                        <option value="{{$key}}">{{ $value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>--}}
                        </div>
                        {{--<div class="row">
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
                        </div>--}}
                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5 style="text-decoration: underline">Clearing Reconciliation Queue</h5>
        <div class="row" id="outward_clearing_queue_area">
            <div class="col-md-12  table-responsive">
                <table class="table table-sm table-bordered table-hover" id="outwardListSearch">
                    <thead class="thead-dark">
                    <tr>
                        <th class="text-center"><input type="checkbox" name="0" id="all_check"/> Select All</th>
                        <th>Bank Account</th>
                        <th>Trans Date</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Dr/Cr</th>
                        <th class="text-center">Instrument Type</th>
                        <th class="text-center">Instrument No</th>
                        <th class="text-center">Instrument Date</th>
                        {{--<th class="text-center">Cheque No</th>
                        <th class="text-center">Cheque Date</th>--}}
                        <th class="text-center">Clearing Date</th>
                        {{--<th class="text-center">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="outward_clearing_form">
            <div class="row">
                <fieldset class="border p-2 col-md-12" id="outward_clearing_post_area">
                    <legend class="w-auto" style="font-size: 15px;">Clearing Reconciliation Entry
                    </legend>
                    <div class="form-group row">
                        <label for="period" class="required col-md-2 col-form-label">Transaction Period</label>
                        <div class="col-md-3">
                            <select required name="period" class="form-control form-control-sm" id="period">
                                {{--<optin value="">Select a period</option>--}}
                                @foreach($data['postingDate'] as $post)
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
                    </div>
                    <div class="form-group row">
                        <label for="posting_date_field" class="required col-md-2 col-form-label ">Transaction
                            Date</label>
                        <div class="input-group date posting_date col-md-3 make-readonly"
                             id="posting_date"
                             data-target-input="nearest">
                            <input required type="text" autocomplete="off" onkeydown="return false"
                                   name="posting_date" readonly
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

                        <label for="clearing_date_field_v" class="col-md-2 col-form-label">Clearing
                            Date</label>
                        <div class="input-group date clearing_date col-md-3 make-readonly"
                             id="clearing_date"
                             data-target-input="nearest">
                            <input required type="text" autocomplete="off" onkeydown="return false"
                                   name="clearing_date"
                                   id="clearing_date_field_v" readonly
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#clearing_date"
                                   data-toggle="datetimepicker"
                                   value=""
                                   data-predefined-date=""
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append clearing_date" data-target="#clearing_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bxs-calendar font-size-small"></i>
                                </div>
                            </div>

                            {{--<label for="ap_vendor_category_v" class=" col-md-2 col-form-label ml-2">Party Category</label>
                            <div class="col-md-4">
                                <input readonly type="text" class="form-control form-control-sm" name="ap_vendor_category"
                                       id="ap_vendor_category_v"
                                       value="">
                            </div>--}}
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label for="ap_bank_account_v" class=" col-md-2 col-form-label">Bank Account</label>
                        <div class="col-md-8">
                            <input readonly type="text" name="ap_bank_account_v" class="form-control form-control-sm"
                                   id="ap_bank_account_v">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="trans_date_field_v" class=" col-md-2 col-form-label">Trans Date</label>
                        <div class="input-group date trans_date col-md-3 make-readonly"
                             id="trans_date"
                             data-target-input="nearest">
                            <input readonly type="text" autocomplete="off" onkeydown="return false"
                                   name="trans_date"
                                   id="trans_date_field_v"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#trans_date"
                                   data-toggle="datetimepicker"
                                   value="{{ old('trans_date', isset($data['insertedData']->trans_date) ?  $data['insertedData']->trans_date : '') }}"
                                   data-predefined-date="{{ old('trans_date', isset($data['insertedData']->trans_date) ?  $data['insertedData']->trans_date : '') }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append trans_date" data-target="#trans_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row make-readonly">
                        <label for="ap_dr_cr" class=" col-md-2 col-form-label">DR/CR</label>
                        <div class="col-md-3">
                            <input readonly type="text" class="form-control form-control-sm" name="ap_dr_cr"
                                   id="ap_dr_cr"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group row make-readonly">
                        <label for="ap_amount_ccy_v" class=" col-md-2 col-form-label">Amount CCY</label>
                        <div class="col-md-3">
                            <input readonly type="text" class="form-control form-control-sm text-right" name="ap_amount_ccy"
                                   id="ap_amount_ccy_v"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group row make-readonly">
                        <label for="ap_amount_lcy_v" class=" col-md-2 col-form-label">Amount LCY</label>
                        <div class="col-md-3">
                            <input readonly type="text" class="form-control form-control-sm text-right" name="ap_amount_lcy"
                                   id="ap_amount_lcy_v"
                                   value="">
                        </div>
                    </div>

                    <div class="form-group row make-readonly">
                        <label for="ap_instrument_type" class=" col-md-2 col-form-label">Instrument Type</label>
                        <div class="col-md-3">
                            <input readonly type="text" class="form-control form-control-sm" name="ap_instrument_type"
                                   id="ap_instrument_type"
                                   value="">
                        </div>
                    </div>

                    <div class="form-group row make-readonly">
                        <label for="ap_cheque_no_v" class=" col-md-2 col-form-label">Instrument No</label>
                        <div class="col-md-3">
                            <input readonly type="text" class="form-control form-control-sm" name="ap_cheque_no"
                                   id="ap_cheque_no_v"
                                   value="">
                        </div>

                        <label for="ap_vendor_id_v" class=" col-md-2 col-form-label ml-2">Party ID</label>
                        <div class="col-md-2">
                            <input readonly type="text" class="form-control form-control-sm" name="ap_vendor_id"
                                   id="ap_vendor_id_v"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group row make-readonly">
                        <label for="cheque_date_field_v" class=" col-md-2 col-form-label">Instrument Date</label>
                        <div class="input-group date cheque_date col-md-3"
                             id="cheque_date"
                             data-target-input="nearest">
                            <input readonly type="text" autocomplete="off" onkeydown="return false"
                                   name="clearing_date"
                                   id="cheque_date_field_v"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#cheque_date"
                                   data-toggle="datetimepicker"
                                   value="{{ old('cheque_date', isset($data['insertedData']->cheque_date) ?  $data['insertedData']->cheque_date : '') }}"
                                   data-predefined-date="{{ old('cheque_date', isset($data['insertedData']->cheque_date) ?  $data['insertedData']->cheque_date : '') }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append cheque_date" data-target="#cheque_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <label for="ap_vendor_name_v" class=" col-md-2 col-form-label ml-2">Party Name</label>
                        <div class="col-md-4">
                            <input readonly type="text" class="form-control form-control-sm" name="ap_vendor_name"
                                   id="ap_vendor_name_v"
                                   value="">
                        </div>
                    </div>--}}

                    <input type="hidden" value="" name="clearing_id" id="clearing_id">
                    <input type="hidden" value="" name="trans_period_id" id="trans_period_id">
                </fieldset>
            </div>
            <div class="row mt-1">
                <div class="col-md-12 d-flex">
                    <button type="submit" class="btn btn-success mr-1" id="outward_clearing_form_submit_btn" disabled><i
                            class="bx bx-save"></i>Save
                    </button>
                    <button type="reset" class="btn btn-dark"
                            onclick="resetTablesDynamicRow();resetHeaderField();removeAllAttachments()">
                        <i class="bx bx-reset"></i>Reset
                    </button>
                    <h6 class="text-primary ml-2">Last Posting Batch ID
                        <span
                            class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{(isset($data['lastPostingBatch']->last_posting_batch_id) ? $data['lastPostingBatch']->last_posting_batch_id : '0')}}</span>
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
    </div>
</div>


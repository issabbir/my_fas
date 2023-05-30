<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:১০ AM
 */
?>
<form id="concurrence_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        {{--<h5 class="ml-2" style="text-decoration: underline">Department-wise Budget Initialization</h5>--}}
        <div class="col-sm-6 pr-0">
            <fieldset class="border pl-1 pr-1">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="fiscal_year" class="required col-sm-4 col-form-label">Financial Year</label>
                    <select required name="fiscal_year" readonly=""
                            class="form-control form-control-sm col-sm-3 required"
                            id="fiscal_year">
                        @foreach($data['financialYear'] as $year)
                            <option value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                        @endforeach
                    </select>
                    <div class="col-sm-2 pr-0 d-flex justify-content-end">
                        <label for="transaction_period" class="required col-form-label pr-0">Posting Period</label>
                    </div>

                    <div class="col-sm-3 pl-0">
                        <select required name="transaction_period"
                                class="form-control form-control-sm required"
                                data-preperiod="{{ old('transaction_period')}}"
                                id="transaction_period">
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="department" class="col-form-label col-sm-4 required">Dept/Cost Center</label>
                    <div class="col-sm-8 pl-0">
                        <select required name="department"
                                class="form-control form-control-sm select2"
                                id="department"
                                data-predpt="{{ old('department')}}">
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label class="col-sm-4 col-form-label" for="budget_head_id">Budget Head ID</label>
                    <input name="budget_head_id" class="form-control form-control-sm col-sm-4"
                           value="" type="number"
                           id="budget_head_id"
                           maxlength="5" oninput="maxLengthValid(this)"
                           onkeyup="resetBudgetField()">
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary searchBudget" id="search_budget"
                                type="button"
                                tabindex="-1"><i class="bx bxs-search font-size-small"></i>Search
                        </button>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="budget_head_name" class="col-form-label col-sm-4">Budget Head Name</label>
                    <div class="col-sm-8 pl-0">
                        <input readonly name="budget_head_name" class="form-control form-control-sm"
                               value="" type="text"
                               id="budget_head_name">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="budget_sub_category" class="col-form-label col-sm-4">Budget Sub Category</label>
                    <div class="col-sm-8 pl-0">
                        <input readonly name="budget_sub_category" class="form-control form-control-sm" value=""
                               type="text"
                               id="budget_sub_category">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="budget_category" class="col-form-label col-sm-4">Budget Category</label>
                    <div class="col-sm-8 pl-0">
                        <input readonly name="budget_category" class="form-control form-control-sm" value="" type="text"
                               id="budget_category">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="budget_type" class="col-form-label col-sm-4">Budget Type</label>
                    <div class="col-sm-8 pl-0">
                        <input readonly name="budget_type" class="form-control form-control-sm" value="" type="text"
                               id="budget_type">
                    </div>
                </div>
            </fieldset>
        </div>

        {{--<div style="width: 2%!important"></div>--}}
        <div class="col-sm-6">
            <fieldset class="border pl-1 pr-1" style="height: 100%;">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Summary</legend>
                <div class="row mb-1">
                    <div class="col-sm-10 d-flex justify-content-end">
                        <span>Figure in Tk</span>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="ministry_approved" class="col-form-label col-sm-5">Ministry Approved Amount</label>
                    <div class="col-sm-5">
                        <input readonly name="ministry_approved" class="form-control form-control-sm text-right-align"
                               value=""
                               type="number" step="0.1" oninput="maxLengthValid(this)"
                               id="ministry_approved"
                               maxlength="20">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="utilized_amount" class="col-form-label col-sm-5">Utilized Amount</label>
                    <div class="col-sm-5">
                        <input readonly name="utilized_amount" class="form-control form-control-sm text-right-align"
                               value=""
                               type="number" step="0.1" oninput="maxLengthValid(this)"
                               id="utilized_amount"
                               maxlength="20">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="balance_amount" class="col-form-label col-sm-5">Balance Amount</label>
                    <div class="col-sm-5">
                        <input readonly name="balance_amount" class="form-control form-control-sm text-right-align"
                               value=""
                               type="number" step="0.1" oninput="maxLengthValid(this)"
                               id="balance_amount"
                               maxlength="20">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 pr-0">
            <fieldset class="border pl-1 pr-1" style="height: 100%">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Transaction Info</legend>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="bill_section" class="required col-sm-4 col-form-label">Bill Section</label>
                    <div class="col-sm-8 pl-0">
                        <select required name="bill_section" class="form-control form-control-sm"
                                id="bill_section">
                            <option value="">Select Bill Section</option>
                            @foreach($data['billSecs'] as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="bill_register" class="required col-sm-4 col-form-label">Bill Register</label>
                    <div class="col-sm-8 pl-0">
                        <select required name="bill_register" class="form-control form-control-sm select2"
                                id="bill_register">
                        </select>
                    </div>
                </div>
                <div class="form-group row ">
                    <label for="transaction_date_field" class="required col-sm-4 col-form-label ">Transaction
                        Date</label>
                    <div
                        class="input-group date transaction_date col-sm-5 pl-0"
                        id="transaction_date"
                        data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="transaction_date"
                               id="transaction_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#transaction_date"
                               data-toggle="datetimepicker"
                               value="{{ old('transaction_date') }}"
                               data-predefined-date="{{ old('transaction_date') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append transaction_date" data-target="#transaction_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="voucher_no" class="required col-sm-4 col-form-label">Voucher No</label>
                    <div class="col-sm-8 pl-0">
                        <input maxlength="100" required name="voucher_no" type="text"
                               value="{{ old('voucher_no')}}"
                               class="required form-control form-control-sm"
                               id="voucher_no">
                    </div>
                </div>
            </fieldset>
        </div>
        {{--<div style="width: 2%!important;"></div>--}}
        <div class="col-sm-6">
            <fieldset class="border pl-2 pr-1">
                <legend class="w-auto" style="font-size: 15px; font-weight: bold">Transaction Reference</legend>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="fill_no" class="col-sm-5 col-form-label pl-0">File No</label>
                            <div class="col-sm-7 pl-0 pr-0">
                                <input maxlength="50" name="fill_no" type="text"
                                       value="{{ old('fill_no')}}"
                                       class="required form-control form-control-sm"
                                       id="fill_no">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="memo_no" class=" col-sm-5 col-form-label pl-0">Memo No</label>
                            <div class="col-sm-7 pl-0 pr-0">
                                <input maxlength="50" name="memo_no" type="text"
                                       value="{{ old('memo_no')}}"
                                       class="required form-control form-control-sm"
                                       id="memo_no">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="est_amount" class=" col-sm-5 col-form-label pl-0">Est. Amount</label>
                            <div class="col-sm-7 pl-0 pr-0">
                                <input maxlength="20" step="0.1" name="est_amount"
                                       type="number" oninput="maxLengthValid(this)"
                                       value="{{ old('est_amount')}}"
                                       class=" form-control form-control-sm"
                                       id="est_amount">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="page_no" class=" col-sm-5 col-form-label">Page No</label>
                            <div class="col-sm-7 pl-0">
                                <input maxlength="10" name="page_no" type="number"
                                       oninput="maxLengthValid(this)"
                                       value="{{ old('page_no')}}"
                                       class="required form-control form-control-sm"
                                       id="page_no">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="memo_date_field" class="col-sm-5 col-form-label">Memo Date</label>
                            <div class="input-group date memo_date col-sm-7 pl-0"
                                 id="memo_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="memo_date"
                                       id="memo_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#memo_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('memo_date') }}"
                                       data-predefined-date="{{ old('memo_date') }}"
                                       placeholder="DD-MM-YYYY">
                                {{--<div class="input-group-append memo_date" data-target="#memo_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="est_date_field" class=" col-sm-5 col-form-label">Est. Date</label>
                            <div class="input-group date est_date col-sm-7 pl-0"
                                 id="est_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="est_date"
                                       id="est_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#est_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('est_date') }}"
                                       data-predefined-date="{{ old('est_date') }}"
                                       placeholder="DD-MM-YYYY">
                                {{--<div class="input-group-append est_date" data-target="#est_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="remarks" class=" col-sm-2 col-form-label pl-0">Remarks</label>
                    <div class="col-sm-10">
                    <textarea maxlength="500" name="remarks" class="required form-control form-control-sm "
                              id="remarks"></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <fieldset class="border pl-1 pr-1">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold"><strong>Contract Tender Info</strong>
                </legend>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="contract_id" class="col-form-label col-sm-4">Contract Id</label>
                            <div class="col-sm-4 pl-0">
                                <input name="contract_id" class="form-control form-control-sm bg-info bg-accent-2"
                                       value="" type="text" readonly
                                       id="contract_id"
                                       maxlength="10">
                            </div>
                            <div class="col-sm-4 pl-0 pr-0">
                                <button disabled type="button" class="btn btn-sm btn-info"><i
                                        class="bx bxs-search font-size-small"></i>Search
                                </button>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="tender_proposal_no" class="col-form-label col-sm-4">Tender/Prop. No</label>
                            <div class="col-sm-4 pl-0">
                                <input name="tender_proposal_no"
                                       class="form-control form-control-sm" value="" type="text"
                                       id="tender_proposal_no"
                                       maxlength="50">
                            </div>
                            <label for="tender_proposal_date_field" class=" col-sm-1 col-form-label pr-0">Date</label>
                            <div class="input-group date tender_proposal_date col-sm-3 pl-0"
                                 id="tender_proposal_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="tender_proposal_date"
                                       id="tender_proposal_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#tender_proposal_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('tender_proposal_date') }}"
                                       data-predefined-date="{{ old('tender_proposal_date') }}"
                                       placeholder="DD-MM-YYYY">
                                {{--<div class="input-group-append tender_proposal_date" data-target="#tender_proposal_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="tender_proposal_ref" class="col-form-label col-sm-4">Tender Ref</label>
                            <div class="col-sm-8 pl-0">
                                <input name="tender_proposal_ref" class="form-control form-control-sm" value=""
                                       type="text"
                                       id="tender_proposal_ref"
                                       maxlength="50">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="tender_proposal_type" class=" col-sm-4 col-form-label">Tender Type</label>
                            <div class="col-sm-4 pl-0">
                                <select name="tender_proposal_type" class="form-control form-control-sm select2 "
                                        id="tender_proposal_type">
                                    <option value="">Select Tender</option>
                                    @foreach( $data['lTenderType'] as $value)
                                        <option value="{{$value->tender_type_id}}">{{ $value->tender_type_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="booking_amount" class="col-form-label col-sm-4 required">Booking Amount</label>
                            <div class="col-sm-4 pl-0">
                                <input required name="booking_amount" class="form-control form-control-sm" value=""
                                       type="number" step="0.1" oninput="maxLengthValid(this)"
                                       id="booking_amount"
                                       maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="contract_no" class="col-form-label col-sm-3 pr-0">Contract No</label>
                            <div class="col-sm-3 pl-0 pr-0">
                                <input name="contract_no" class="form-control form-control-sm"
                                       value="" type="text"
                                       id="contract_no"
                                       maxlength="100">
                            </div>
                            <label for="contract_date_field" class="col-sm-2 col-form-label">Date</label>
                            <div class="input-group date contract_date col-sm-4 pl-0"
                                 id="contract_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="contract_date"
                                       id="contract_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#contract_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('contract_date') }}"
                                       data-predefined-date="{{ old('contract_date') }}"
                                       placeholder="DD-MM-YYYY">
                                {{--<div class="input-group-append contract_date" data-target="#contract_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="party_name" class="col-form-label col-sm-3">Party Name</label>
                            <div class="col-sm-9 pl-0">
                                <input name="party_name" class="form-control form-control-sm" value="" type="text"
                                       id="party_name"
                                       maxlength="100">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="subject" class="required col-form-label col-sm-3 required pr-0">Subject</label>
                            <div class="col-sm-9 pl-0">
                                <input required name="subject" class="form-control form-control-sm" value="" type="text"
                                       id="subject"
                                       maxlength="200">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="contract_value" class="col-form-label col-sm-3 pr-0">Contract Value</label>
                            <div class="col-sm-4 pl-0">
                                <input name="contract_value" class="form-control form-control-sm" value="" type="text"
                                       id="contract_value"
                                       maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <section>
        @include('gl.common_file_upload')
    </section>

    <div class="row mt-1">
        <div class="col-sm-12 d-flex">
            <button type="button"
                    class="btn btn-sm btn-success mr-1" id="budgetFormSubmit"><i
                    class="bx bxs-save font-size-small"></i>Save
            </button>
            <button type="reset" class="btn btn-sm btn-dark">
                <i class="bx bx-reset font-size-small"></i>Reset
            </button>
        </div>
        {{--<div class="col-md-6 ml-1">
            <h6 class="text-primary">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
            </h6>
            --}}{{--<div class="form-group row ">
                <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                <input type="text" readonly tabindex="-1" class="form-control col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
            </div>--}}{{--
        </div>--}}
    </div>
</form>



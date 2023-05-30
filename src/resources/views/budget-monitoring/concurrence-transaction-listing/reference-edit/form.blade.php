<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:১০ AM
 */
?>
<form id="concurrence_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="budget_booking_id" id="budget_booking_id"
           value="{{ old('budget_booking_id',isset($data['insertedData']) ? $data['insertedData']->budget_booking_id : '')}}">
    <div class="row">
        <div class="col-sm-6 pr-0">
            <fieldset class="border pl-1 pr-1" style="height: 100%">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Transaction Reference</legend>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="bill_section" class="col-sm-3 col-form-label required">Section</label>
                    <div class="col-sm-9">
                        <select name="bill_section" class="form-control form-control-sm select2" tabindex="1" required
                                id="bill_section">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($data['billSecs'] as $value)
                                <option
                                    {{old('bill_section', isset($data['insertedData']) ? ($data['insertedData']->bill_sec_id == $value->bill_sec_id) ? 'selected' : '' : '' )}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="bill_register" class="col-sm-3 col-form-label required">Register</label>
                    <div class="col-sm-9">
                        <select required
                                data-bill-register-id="{{ $data['insertedData']->bill_reg_id}}"
                                name="bill_register" class="form-control form-control-sm select2" tabindex="2"
                                id="bill_register">
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom:8px">
                    <label for="fiscal_year" class="required col-sm-3 col-form-label">Financial Year</label>
                    <div class="col-sm-3">
                        <select required name="fiscal_year" tabindex="3"
                                class="form-control form-control-sm required make-readonly-bg"
                                id="fiscal_year">
                            @foreach($data['financialYear'] as $year)
                                <option
                                    {{  old('fiscal_year',isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id  : '') ==  $year->fiscal_year_id ? "selected" : "" }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-end col-sm-3 pr-0">
                        <label for="transaction_period" class="required col-form-label">Posting Period</label>
                    </div>
                    <div class="col-sm-3">
                        <select required name="transaction_period"
                                class="form-control form-control-sm required"
                                data-preperiod="{{ old('transaction_period',isset($data['insertedData']) ? $data['insertedData']->trans_period_id  : '')}}"
                                id="transaction_period">
                        </select>
                    </div>
                </div>
                <div class="row ">
                    <label for="transaction_date_field" class="required col-sm-3 col-form-label ">Posting Date</label>
                    <div
                        class="input-group date transaction_date col-sm-5 mb-2"
                        id="transaction_date"
                        data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="transaction_date" tabindex="4"
                               id="transaction_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#transaction_date"
                               data-toggle="datetimepicker"
                               value="{{ old('transaction_date',isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->trans_date) : '' ) }}"
                               data-predefined-date="{{ old('transaction_date',isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->trans_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append transaction_date" data-target="#transaction_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="document_date_field" class="required col-md-3 col-form-label">Document Date</label>
                    <div class="input-group date document_date col-md-5 mb-2"
                         id="document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="document_date" tabindex="-1"
                               id="document_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#document_date"
                               data-toggle="datetimepicker"
                               value="{{ old('document_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->document_date) : '') }}"
                               data-predefined-date="{{ old('document_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->document_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append document_date" data-target="#document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="document_no" class="col-sm-3 col-form-label required">Document No</label>
                    <div class="col-sm-9">
                        <input maxlength="100" name="document_no" type="text" tabindex="6" required
                               value="{{ old('document_no',isset($data['insertedData']) ? $data['insertedData']->document_no : '')}}"
                               class="required form-control form-control-sm"
                               id="document_no"
                               oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
            </fieldset>
        </div>

        {{-- Make all field hidden except reference Yousouf Imam: 30/08/2022
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
                        <input readonly tabindex="-1" name="ministry_approved"
                               class="form-control form-control-sm text-right-align"
                               value=""
                               type="text"
                               id="ministry_approved">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="utilized_amount" class="col-form-label col-sm-5">--}}{{--Utilized Amount--}}{{--Utilized
                        Amount</label>
                    <div class="col-sm-5">
                        <input readonly tabindex="-1" name="utilized_amount"
                               class="form-control form-control-sm text-right-align"
                               value=""
                               type="text"
                               id="utilized_amount">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="balance_amount" class="col-form-label col-sm-5">Balance Amount</label>
                    <div class="col-sm-5">
                        <input readonly tabindex="-1" name="balance_amount"
                               class="form-control form-control-sm text-right-align"
                               value=""
                               type="text"
                               id="balance_amount">
                    </div>
                </div>
            </fieldset>
        </div>--}}
    </div>
    {{-- Make all field hidden except reference Yousouf Imam: 30/08/2022
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 pr-0">
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>

                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="department" class="col-form-label col-sm-3 required pr-0">Dept/Cost
                                Center</label>
                            <div class="col-sm-9 make-select2-readonly-bg">
                                <select required name="department" tabindex="7"
                                        class="form-control form-control-sm select2"
                                        id="department">
                                    <option
                                        value="{{$data['insertedData']->cost_center_dept_id}}"> {{ $data['insertedData']->cost_center_dept}} </option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label class="col-sm-3 col-form-label" for="budget_head_id">Budget Head ID</label>
                            <div class="col-sm-4">
                                <input name="budget_head_id" class="form-control form-control-sm"
                                       value="{{$data['insertedData']->budget_head_id}}"
                                       type="number" readonly
                                       id="budget_head_id" tabindex="8"
                                       maxlength="5"
                                       onfocusout="addZerosInAccountId(this, 5)"
                                       oninput="maxLengthValid(this)"
                                       onkeyup="resetBudgetField()">
                            </div>

                            <div class="col-sm-4">
                                <button class="btn btn-sm btn-primary searchBudget" id="search_budget" disabled
                                        type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                        class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="budget_head_name" class="col-form-label col-sm-3 pr-0">Budget Head Name</label>
                            <div class="col-sm-9">
                                <input readonly tabindex="-1" name="budget_head_name"
                                       class="form-control form-control-sm"
                                       value="{{$data['insertedData']->budget_head_name}}"
                                       type="text"
                                       id="budget_head_name">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="budget_sub_category" class="col-form-label col-sm-3">Sub Category</label>
                            <div class="col-sm-9">
                                <input readonly tabindex="-1" name="budget_sub_category"
                                       class="form-control form-control-sm"
                                       value="{{$data['insertedData']->sub_category_name}}"
                                       type="text"
                                       id="budget_sub_category">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="budget_category" class="col-form-label col-sm-3">Category</label>
                            <div class="col-sm-9">
                                <input readonly tabindex="-1" name="budget_category"
                                       class="form-control form-control-sm"
                                       value="{{$data['insertedData']->category_name}}" type="text"
                                       id="budget_category">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="budget_type" class="col-form-label col-sm-3">Budget Type</label>
                            <div class="col-sm-9">
                                <input readonly tabindex="-1" name="budget_type" class="form-control form-control-sm"
                                       value="{{$data['insertedData']->budget_type}}"
                                       type="text"
                                       id="budget_type">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 pr-0">
                    <fieldset class="border pl-2 pr-1">
                        <legend class="w-auto" style="font-size: 14px; font-weight: bold">File/Memo Reference</legend>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="fill_no" class="col-sm-5 col-form-label pl-0">File No</label>
                                    <div class="col-sm-7 pl-0 pr-0">
                                        <input maxlength="50" name="fill_no" type="text" readonly
                                               value="{{ $data['insertedData']->file_no }}"
                                               class="required form-control form-control-sm"
                                               id="fill_no">
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="memo_no" class=" col-sm-5 col-form-label pl-0">Memo No</label>
                                    <div class="col-sm-7 pl-0 pr-0">
                                        <input maxlength="50" name="memo_no" type="text" readonly
                                               value="{{$data['insertedData']->memo_no}}"
                                               class="required form-control form-control-sm"
                                               id="memo_no">
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="est_amount" class=" col-sm-5 col-form-label pl-0">Est. Amount</label>
                                    <div class="col-sm-7 pl-0 pr-0">
                                        <input maxlength="20" name="est_amount" tabindex="-1" autocomplete="off"
                                               type="text" readonly
                                               value="{{$data['insertedData']->estimate_amount}}"
                                               class=" form-control form-control-sm text-right"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="page_no" class=" col-sm-5 col-form-label">Page No</label>
                                    <div class="col-sm-7 pl-0">
                                        <input maxlength="10" name="page_no" type="number"
                                               oninput="maxLengthValid(this)" readonly
                                               value="{{ $data['insertedData']->page_no}}"
                                               class="required form-control form-control-sm"
                                               id="page_no">
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="memo_date_field" class="col-sm-5 col-form-label">Memo Date</label>
                                    <div class="input-group date memo_date col-sm-7 pl-0 make-readonly"
                                         id="memo_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false"
                                               name="memo_date" readonly
                                               id="memo_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#memo_date"
                                               data-toggle="datetimepicker"
                                               value="{{  \App\Helpers\HelperClass::dateConvert($data['insertedData']->memo_date) }}"
                                               data-predefined-date="{{  \App\Helpers\HelperClass::dateConvert($data['insertedData']->memo_date)  }}"
                                               placeholder="DD-MM-YYYY">
                                        --}}{{--<div class="input-group-append memo_date" data-target="#memo_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>--}}{{--
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="est_date_field" class=" col-sm-5 col-form-label">Est. Date</label>
                                    <div class="input-group date est_date col-sm-7 pl-0 make-readonly"
                                         id="est_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false"
                                               name="est_date" readonly
                                               id="est_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#est_date"
                                               data-toggle="datetimepicker"
                                               value="{{\App\Helpers\HelperClass::dateConvert($data['insertedData']->estimate_date) }}"
                                               data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($data['insertedData']->estimate_date) }}"
                                               placeholder="DD-MM-YYYY">
                                        --}}{{--<div class="input-group-append est_date" data-target="#est_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>--}}{{--
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 offset-2 mb-1">
                                <textarea readonly rows="2" class="form-control form-control-sm pl-1"
                                          id="est_amount_word"
                                          tabindex="-1"></textarea>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:8px">
                            <label for="remarks" class=" col-sm-2 col-form-label pl-0">Remarks</label>
                            <div class="col-sm-10">
                    <textarea maxlength="500" name="remarks" class="required form-control form-control-sm " readonly
                              id="remarks">{{ $data['insertedData']->remarks }}</textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 14px; font-weight: bold"><strong>Contract / Tender
                                Info</strong></legend>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="contract_id" class="col-form-label col-sm-3">Contract Id</label>
                                    <div class="col-sm-5">
                                        <input name="contract_id"
                                               class="form-control form-control-sm bg-info bg-accent-2"
                                               value="{{  $data['insertedData']->contract_id }}"
                                               type="text" readonly tabindex="-1"
                                               id="contract_id">
                                    </div>
                                    <div class="col-sm-4">
                                        <button disabled type="button" class="btn btn-sm btn-info"><i
                                                class="bx bx-search font-size-small"></i><span
                                                class="align-middle ml-25">Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="tender_proposal_type" class="required col-sm-3 col-form-label">Tender
                                        Type</label>
                                    <div class="col-sm-9">
                                        <select name="tender_proposal_type" tabindex="9" readonly=""
                                                class="form-control form-control-sm make-readonly-bg"
                                                id="">
                                            <option
                                                value="{{$data['insertedData']->tender_type_id}}">{{ $data['insertedData']->tender_proposal_type}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label class="col-form-label col-md-3" for="vendor_id">Party/Vendor ID</label>
                                    <div class="col-md-5">
                                        <input name="vendor_id" class="form-control form-control-sm "
                                               value="{{ $data['insertedData']->vendor_id }}"
                                               type="number" tabindex="11" readonly
                                               id="vendor_id"
                                               maxlength="10"
                                               onfocusout="addZerosInAccountId(this)"
                                               oninput="maxLengthValid(this)"
                                               onkeyup="resetField(['#vendor_name']);">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-sm btn-primary "
                                                type="button" disabled
                                                tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                                class="align-middle ml-25">Search</span>
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label class="col-form-label col-md-3 pr-0" for="vendor_name">Party/Vendor
                                        Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-sm"
                                               id="vendor_name"
                                               value="{{ $data['insertedData']->vendor_name }}"
                                               name="vendor_name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="tender_proposal_no" class="col-form-label col-sm-3">Tender/Prop.
                                        No</label>
                                    <div class="col-sm-4 pr-0">
                                        <input name="tender_proposal_no"
                                               class="form-control form-control-sm" readonly
                                               value="{{ $data['insertedData']->tender_proposal_no }}"
                                               type="text"
                                               id="tender_proposal_no"
                                               maxlength="50">
                                    </div>
                                    <label for="tender_proposal_date_field"
                                           class=" col-sm-2 col-form-label pr-0">Tender Date</label>
                                    <div class="input-group date tender_proposal_date col-sm-3 make-readonly"
                                         id="tender_proposal_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false"
                                               name="tender_proposal_date" readonly
                                               id="tender_proposal_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#tender_proposal_date"
                                               data-toggle="datetimepicker"
                                               value="{{ \App\Helpers\HelperClass::dateConvert($data['insertedData']->tender_proposal_date) }}"
                                               data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($data['insertedData']->tender_proposal_date)  }}"
                                               placeholder="DD-MM-YYYY">
                                        --}}{{--<div class="input-group-append tender_proposal_date" data-target="#tender_proposal_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>--}}{{--
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="tender_proposal_ref" class="col-form-label col-sm-3">Tender/Prop.
                                        Ref</label>
                                    <div class="col-sm-9">
                                        <input name="tender_proposal_ref"
                                               class="form-control form-control-sm" readonly
                                               value="{{$data['insertedData']->tender_proposal_ref  }}"
                                               type="text"
                                               id="tender_proposal_ref"
                                               maxlength="50">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="contract_no" class="col-form-label col-sm-3">Contract No</label>
                                    <div class="col-sm-4 pr-0">
                                        <input name="contract_no" class="form-control form-control-sm" readonly
                                               value="{{$data['insertedData']->contract_no  }}"
                                               type="text"
                                               id="contract_no"
                                               maxlength="100">
                                    </div>
                                    <label for="contract_date_field" class="col-sm-2 pr-0 col-form-label text-right">Date</label>
                                    <div class="input-group date contract_date col-sm-3 make-readonly"
                                         id="contract_date"
                                         data-target-input="nearest">
                                        <input type="text" autocomplete="off" onkeydown="return false"
                                               name="contract_date" readonly
                                               id="contract_date_field"
                                               class="form-control form-control-sm datetimepicker-input"
                                               data-target="#contract_date"
                                               data-toggle="datetimepicker"
                                               value="{{ old('contract_date', isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->contract_date)  : '') }}"
                                               data-predefined-date="{{ old('contract_date', isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->contract_date)  : '') }}"
                                               placeholder="DD-MM-YYYY">
                                        --}}{{--<div class="input-group-append contract_date" data-target="#contract_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>--}}{{--
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="subject" class="col-form-label col-sm-3">Subject</label>
                                    <div class="col-sm-9">
                                        <input name="subject" tabindex="12" readonly
                                               class="form-control form-control-sm"
                                               value="{{old('subject',isset($data['insertedData']) ? $data['insertedData']->contract_subject  : '')}}"
                                               type="text"
                                               id="subject"
                                               maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="contract_value" class="col-form-label col-sm-3">Contract
                                        Value</label>
                                    <div class="col-sm-4 pr-0">
                                        <input name="contract_value" readonly
                                               class="form-control form-control-sm"
                                               value="{{old('contract_value',isset($data['insertedData']) ? $data['insertedData']->contract_value  : '')}}"
                                               type="text"
                                               id="contract_value"
                                               maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row" style="margin-bottom:8px">
                                    <label for="booking_amount" class="col-form-label col-sm-3 required pr-0">Booking
                                        Amount</label>
                                    <div class="col-sm-4 pr-0">
                                        --}}{{--<input required name="booking_amount" class="form-control form-control-sm" value=""
                                               type="number" step="0.1" oninput="maxLengthValid(this)"
                                               id="booking_amount"
                                               maxlength="20">--}}{{--
                                        --}}{{--Add this part Pavel:13-03-22--}}{{--
                                        <input required name="booking_amount" tabindex="13" autocomplete="off" readonly
                                               class="form-control form-control-sm text-right"
                                               value="{{old('booking_amount',isset($data['insertedData']) ? $data['insertedData']->budget_booking_amount  : '')}}"
                                               type="text"
                                               id="booking_amount"
                                               --}}{{--
                                                                                              oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                               --}}{{--
                                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                                               maxlength="20">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 offset-3 mb-1">
                                <textarea readonly class="form-control form-control-sm pl-1" rows="2"
                                          id="booking_amount_word"
                                          tabindex="-1"></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <section>
        @include('gl.common_file_upload')
    </section>--}}
    <div class="row mt-1">
        <div class="col-sm-12 d-flex">
            <button type="submit" tabindex="14"
                    class="btn btn-sm btn-success mr-1" id="budgetFormSubmit"><i
                    class="bx bxs-save font-size-small"></i><span class="align-middle ml-25">
                    {{isset($data['insertedData']) ? 'Update': 'Save'}}</span>
            </button>
            @if(isset($data['insertedData']))
                <a href="{{route('concurrence-transaction-list.index',['filter'=>(isset($filter) ? $filter : '')])}}" class="btn btn-sm btn-dark">
                    <i class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Back</span>
                </a>
            @else
                <button type="reset" class="btn btn-sm btn-dark resetFrom"
                        onclick="resetField(['#department','#tender_proposal_type'])">
                    <i class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Reset</span>
                </button>
            @endif

            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>

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



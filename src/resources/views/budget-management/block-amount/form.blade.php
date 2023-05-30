<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:১০ AM
 */
?>
<form id="blocked_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="budget_booking_id" id="budget_booking_id"
           value="{{ old('budget_booking_id',isset($data['insertedData']) ? $data['insertedData']->budget_booking_id : '')}}">
    <div class="row ">
        <div class="col-md-4">
            <h4><span class="border-bottom-secondary border-bottom-2">Block Budget Amount:</span></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 pr-0">
            <fieldset class="border pl-1 pr-1">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>

                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <label for="fiscal_year" class="required col-sm-3 col-form-label">Financial Year</label>
                            <div class="col-sm-4 ml-1">
                                <select required name="fiscal_year" tabindex="3"
                                        class="form-control form-control-sm required {{isset($data['insertedData']) ? 'make-readonly-bg' : ''}} "
                                        id="fiscal_year">
                                    @foreach($data['financialYear'] as $year)
                                        <option
                                            {{  old('fiscal_year',isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id  : '') ==  $year->fiscal_year_id ? "selected" : "" }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-sm btn-primary" id="allBlockedBudgets"><i class="bx bx-search font-size-small"></i>Blocked List</button>
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="department" class="col-form-label col-sm-3 required pr-0 pb-1">Dept/Cost
                                Center</label>
                            <div
                                class="col-sm-8 {{isset($data['insertedData']) ? 'make-select2-readonly-bg' : ''}} ml-1">
                                <select required name="department" tabindex="7"
                                        class="form-control form-control-sm select2"
                                        id="department"
                                        data-predpt="{{ old('department',isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id  : '')}}">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($department as $dpt)
                                        <option
                                            {{  old('department',isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id  : '') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label class="col-sm-3 col-form-label required" for="budget_head_id">Budget Head ID</label>
                            <div class="col-sm-4 ml-1">
                                <input required name="budget_head_id" class="form-control form-control-sm"
                                       value="{{old('budget_head_id',isset($data['insertedData']) ? $data['insertedData']->budget_head_id  : '')}}"
                                       type="number" {{isset($data['insertedData']) ? 'readonly' : ''}}
                                       id="budget_head_id" tabindex="8"
                                       maxlength="5"
                                       onfocusout="addZerosInAccountId(this, 5)"
                                       oninput="maxLengthValid(this)"
                                       onkeyup="resetBudgetField();resetBudgetBlockedList()">
                            </div>

                            <div class="col-sm-4">
                                <button class="btn btn-sm btn-primary searchBudget" id="search_budget"
                                        {{isset($data['insertedData']) ? 'disabled' : ''}}
                                        type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                        class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="budget_head_name" class="col-form-label col-sm-3 pr-0">Budget Head Name</label>
                            <div class="col-sm-8 ml-1">
                                <input readonly tabindex="-1" name="budget_head_name"
                                       class="form-control form-control-sm"
                                       value="" type="text"
                                       id="budget_head_name">
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="budget_sub_category" class="col-form-label col-sm-3">Sub Category</label>
                            <div class="col-sm-8 ml-1">
                                <input readonly tabindex="-1" name="budget_sub_category"
                                       class="form-control form-control-sm"
                                       value=""
                                       type="text"
                                       id="budget_sub_category">
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="budget_category" class="col-form-label col-sm-3">Category</label>
                            <div class="col-sm-8 ml-1">
                                <input readonly tabindex="-1" name="budget_category"
                                       class="form-control form-control-sm"
                                       value="" type="text"
                                       id="budget_category">
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="budget_type" class="col-form-label col-sm-3">Budget Type</label>
                            <div class="col-sm-8 ml-1">
                                <input readonly tabindex="-1" name="budget_type" class="form-control form-control-sm"
                                       value=""
                                       type="text"
                                       id="budget_type">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 ">
                        <div class="row d-flex justify-content-end">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <span>Figure in Tk</span>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="ministry_approved" class="col-form-label col-md-5">Approved
                                Amount</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="ministry_approved"
                                       class="form-control form-control-sm text-right-align ministry_approved"
                                       value=""
                                       type="text"
                                       id="ministry_approved">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="utilized_amount"
                                   class="col-form-label col-md-5">{{--Utilized Amount--}}
                                Booking
                                Amount</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="utilized_amount"
                                       class="form-control form-control-sm text-right-align utilized_amount"
                                       value=""
                                       type="text"
                                       id="utilized_amount">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="balance_amount" class="col-form-label col-md-5">Balance Amount</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="balance_amount" style="background-color: yellow"
                                       class="form-control form-control-sm text-right-align balance_amount "
                                       value=""
                                       type="text"
                                       id="balance_amount">
                            </div>
                        </div>

                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="blocked_amount" class="col-form-label col-md-5">Blocked Amount
                                (-)</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="blocked_amount"
                                       class="form-control form-control-sm text-right-align blocked_amount"
                                       value=""
                                       type="text"
                                       id="blocked_amount">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="unblocked_amount" class="col-form-label col-md-5">Unblocked Amount
                                (+)</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="unblocked_amount"
                                       class="form-control form-control-sm text-right-align unblocked_amount"
                                       value=""
                                       type="text"
                                       id="unblocked_amount">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="remain_block_amt" class="col-form-label col-md-5">Remaining Block Amount</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="remain_block_amount" style="background-color: yellow"
                                       class="form-control form-control-sm text-right-align remain_block_amount"
                                       value=""
                                       type="text"
                                       id="remain_block_amt">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end" style="margin-bottom: 0px;">
                            <label for="available_amount" class="col-form-label col-md-5">Available
                                Amount</label>
                            <div class="col-md-5">
                                <input readonly tabindex="-1" name="available_amount" style="background-color: yellow"
                                       class="form-control form-control-sm text-right-align available_amount"
                                       value=""
                                       type="text"
                                       id="available_amount">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 pr-0">
            <fieldset class="border pl-1 pr-1">
                <legend class="w-auto" style="font-size: 14px; font-weight: bold">Block Amount Details</legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class=" row">
                            <label for="transaction_period" class="required col-form-label col-sm-2">Posting
                                Period</label>
                            <div class="col-sm-2">
                                <select required name="transaction_period"
                                        class="form-control form-control-sm required {{isset($data['insertedData']) ? 'make-readonly-bg' : ''}}"
                                        data-preperiod="{{ old('transaction_period',isset($data['insertedData']) ? $data['insertedData']->trans_period_id  : '')}}"
                                        id="transaction_period">
                                </select>
                            </div>
                        </div>
                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="block_date_field" class="required col-sm-2 col-form-label">Blocked Date</label>
                            <div class="input-group date block_date col-sm-2"
                                 id="block_date" style="margin-bottom: 2px;"
                                 data-target-input="nearest">
                                <input required type="text" autocomplete="off" onkeydown="return false"
                                       name="block_date" tabindex="4"
                                       id="block_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#block_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('block_date',isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->trans_date) : '' ) }}"
                                       data-predefined-date="{{ old('block_date',isset($data['insertedData']) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->trans_date) : '') }}"
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append block_date" data-target="#block_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="new_blocked_amount" class="col-form-label col-md-2 required">Blocked
                                Amount</label>
                            <div class="col-sm-2">
                                <input required name="new_blocked_amount" tabindex="13" autocomplete="off"
                                       class="form-control form-control-sm text-right"
                                       value="{{old('new_blocked_amount',isset($data['insertedData']) ? $data['insertedData']->budget_booking_amount  : '')}}"
                                       type="text"
                                       id="new_blocked_amount"
                                       oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                                       maxlength="20">
                            </div>
                        </div>

                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="amount_word" class="col-form-label col-md-2">Amount In Words</label>
                            <div class="col-sm-10">
                                <input readonly tabindex="-1" name="amount_word"
                                       class="form-control form-control-sm"
                                       value="" type="text"
                                       id="amount_word">
                            </div>
                        </div>

                        <div class=" row" style="margin-bottom: 0px;">
                            <label for="description" class="col-form-label col-md-2 required">Description</label>
                            <div class="col-sm-10">
                                <textarea required tabindex="-1" name="description"
                                          class="form-control form-control-sm"
                                          value="" type="text"
                                          id="description"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </fieldset>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-sm-12 d-flex">
            <button type="submit" tabindex="14"
                    class="btn btn-sm btn-success mr-1" id="budgetFormSubmit"><i
                    class="bx bxs-save font-size-small"></i><span class="align-middle ml-25">
                    {{isset($data['insertedData']) ? 'Update': 'Save'}}</span>
            </button>
            <button type="reset" class="btn btn-sm btn-dark resetFrom mr-1">
                <i class="bx bx-reset font-size-small"></i><span class="align-middle">Reset</span>
            </button>
            {{--<button type="button" class="btn btn-sm btn-info" id="loadBlockList">
                <i class="bx bx-search font-size-small"></i><span class="align-middle">Load Block List</span>
            </button>--}}
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12  table-responsive mt-2">
        <h4><span class="border-bottom-secondary border-bottom-2">Budget Amount Blocked List</span></h4>
        <table class="table table-sm table-bordered table-hover" id="blocked_list">
            <thead class="thead-dark">
            <tr>
                <th width="10%">Blocked Date</th>
                <th width="10%">Blocked Amount</th>
                <th width="20%">Description</th>
                <th width="10%">Unblocked Amount</th>
                {{--  <th>Remarks</th>--}}
                <th width="10%" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<section id="modal-sizes">
    <div class="row">
        <div class="col-12">
            <div class="mr-1 mb-1 d-inline-block">
                <div class="modal fade text-left w-100" id="blockedListModal" tabindex="-1" role="dialog"
                     aria-labelledby="blockedListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="blockedListModalLabel">Search Blocked Lists</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <fieldset class="border p-1">
                                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Search Result For</legend>
                                    <form action="#" id="blocked_search_form">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="b_fiscal_year" class="col-form-label required">Financial
                                                        Year</label>
                                                    <input type="text" name="b_fiscal_year" id="b_fiscal_year" readonly tabindex="-1"
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </fieldset>
                                <div class="card shadow-none">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover" id="all_blocked_list">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th width="5%">Blocked Date</th>
                                                <th width="35%">Budget Head</th>
                                                <th width="35%">Department</th>
                                                <th width="10%">Blocked Amount</th>
                                                <th width="10%">Remain Block</th>
                                                <th width="5%" class="text-center">Action</th>
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

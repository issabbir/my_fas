<div class="row">
    {{--<h5 class="ml-2" style="text-decoration: underline">Department-wise Budget Initialization</h5>--}}
    <div class="col-sm-6 pr-0">
        <fieldset class="border pl-1 pr-1" style="height: 100%">
            <legend class="w-auto" style="font-size: 14px; font-weight: bold">Transaction Reference</legend>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="bill_section" class="col-sm-3 col-form-label">Section</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" name="bill_section" id="bill_section"
                           value="{{isset($concurrenceTranInfo->bill_sec_name) ? $concurrenceTranInfo->bill_sec_name : ''}}">
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="bill_register" class="col-sm-3 col-form-label">Register</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" name="bill_register" id="bill_register"
                           value="{{isset($concurrenceTranInfo->bill_reg_name) ? $concurrenceTranInfo->bill_reg_name : ''}}">

                </div>
            </div>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="fiscal_year" class="required col-sm-3 col-form-label">Financial Year</label>
                <div class="col-sm-3">
                    <input disabled name="fiscal_year" class="form-control form-control-sm" type="text" id="fiscal_year"
                           value="{{isset($concurrenceTranInfo->fiscal_year) ? $concurrenceTranInfo->fiscal_year : ''}}">

                </div>

                <div class="d-flex justify-content-end col-sm-3 pr-0">
                    <label for="transaction_period" class="required col-form-label">Posting Period</label>
                </div>
                <div class="col-sm-3">
                    <input disabled class="form-control form-control-sm" name="transaction_period" id="transaction_period"
                           value="{{isset($concurrenceTranInfo->trans_period_name) ? $concurrenceTranInfo->trans_period_name : ''}}">

                </div>

            </div>
            <div class="form-group row ">
                <label for="transaction_date_field" class="required col-sm-3 col-form-label ">Posting Date</label>
                <div
                    class="input-group transaction_date col-sm-5"
                    id="transaction_date"
                    data-target-input="nearest">
                    <input disabled class="form-control form-control-sm " name="transaction_date_field" id="transaction_date_field"
                           value="{{isset($concurrenceTranInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->trans_date) : ''}}">

                </div>
            </div>
            <div class="form-group row">
                <label for="document_date_field" class="required col-md-3 col-form-label">Document Date</label>
                <div class="input-group date document_date col-md-5"
                     id="document_date"
                     data-target-input="nearest">
                    <input disabled class="form-control form-control-sm" name="transaction_date_field" id="transaction_date_field"
                           value="{{isset($concurrenceTranInfo->document_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->trans_date) : ''}}">

                </div>
            </div>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="voucher_no" class="col-sm-3 col-form-label">Document No</label>
                <div class="col-sm-9">
                    <input style="background-color: yellow" disabled class="form-control form-control-sm " name="voucher_no" id="voucher_no"
                           value="{{isset($concurrenceTranInfo->document_no) ? $concurrenceTranInfo->document_no : ''}}">

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
            <div class="row pt-2">
                <div class="col-md-6">
                    <div class="row">
                        <label for="ministry_approved" class="col-form-label col-md-5 pr-0">Approved Amount</label>
                        <div class="col-md-7 pl-0">
                            <input readonly tabindex="-1" name="ministry_approved"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="ministry_approved">
                        </div>
                    </div>
                    <div class="row">
                        <label for="utilized_amount" class="col-form-label col-md-5 pr-0">{{--Utilized Amount--}}Booking
                            Amount</label>
                        <div class="col-md-7 pl-0">
                            <input style="background-color: yellow" readonly tabindex="-1" name="utilized_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="utilized_amount">
                        </div>
                    </div>
                    <div class="row">
                        <label for="balance_amount" class="col-form-label col-md-5 pr-0">Balance Amount</label>
                        <div class="col-md-7 pl-0">
                            <input style="background-color: yellow" readonly tabindex="-1" name="balance_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="balance_amount">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="blocked_amount" class="col-form-label col-md-5 pr-0 pl-0">Blocked Amount (-)</label>
                        <div class="col-md-7 pl-0">
                            <input readonly tabindex="-1" name="blocked_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="blocked_amount">
                        </div>
                    </div>
                    <div class="row">
                        <label for="unblocked_amount" class="col-form-label col-md-5 pr-0 pl-0">Unblocked Amt (+)</label>
                        <div class="col-md-7 pl-0">
                            <input readonly tabindex="-1" name="unblocked_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="unblocked_amount">
                        </div>
                    </div>
                    <div class="row">
                        <label for="remaining_block_amount" class="col-form-label col-md-5 pr-0 pl-0">Remaining Blocked</label>
                        <div class="col-md-7 pl-0">
                            <input readonly tabindex="-1" name="remaining_block_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="remaining_block_amount">
                        </div>
                    </div>
                    <div class="row">
                        <label for="available_amount" class="col-form-label col-md-5 pr-0 pl-0">Available Amount</label>
                        <div class="col-md-7 pl-0">
                            <input readonly tabindex="-1" name="available_amount"
                                   class="form-control form-control-sm text-right-align"
                                   value=""
                                   type="text"
                                   id="available_amount">
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="form-group row" style="margin-bottom:8px">
                <label for="ministry_approved" class="col-form-label col-sm-5">Ministry Approved Amount</label>
                <div class="col-sm-5">
                    <input disabled name="ministry_approved" class="form-control form-control-sm text-right-align"
                           value=""
                           type="text"
                           id="ministry_approved"
                    >
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="utilized_amount" class="col-form-label col-sm-5">--}}{{--Utilized Amount--}}{{--Utilized
                    Amount</label>
                <div class="col-sm-5">
                    <input style="background-color: yellow" disabled name="utilized_amount" class="form-control form-control-sm text-right-align"
                           value=""
                           type="text"
                           id="utilized_amount"
                    >
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:8px">
                <label for="balance_amount" class="col-form-label col-sm-5">Balance Amount</label>
                <div class="col-sm-5">
                    <input style="background-color: yellow" disabled name="balance_amount" class="form-control form-control-sm text-right-align "
                           value=""
                           type="text"
                           id="balance_amount">
                </div>
            </div>--}}
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 pr-0">
                <fieldset class="border pl-1 pr-1">
                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>

                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="department" class="col-form-label col-sm-3 required pr-0">Dept/Cost Center</label>
                        <div class="col-sm-9">
                            <input disabled name="department" class="form-control form-control-sm" type="text" id="department"
                                   value="{{isset($concurrenceTranInfo->cost_center_dept) ? $concurrenceTranInfo->cost_center_dept : ''}}">

                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label class="col-sm-3 col-form-label" for="budget_head_id">Budget Head ID</label>
                        <div class="col-sm-4">
                            <input disabled name="budget_head_id" class="form-control form-control-sm" type="text" id="budget_head_id"
                                   value="{{isset($concurrenceTranInfo->budget_head_id) ? $concurrenceTranInfo->budget_head_id : ''}}">

                        </div>

                        <div class="col-sm-4">
                            <button class="btn btn-sm btn-primary searchBudget" id="search_budget"
                                    type="button" disabled
                                    tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                    class="align-middle ml-25">Search</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="budget_head_name" class="col-form-label col-sm-3 pr-0">Budget Head Name</label>
                        <div class="col-sm-9">
                            <input style="background-color: yellow" disabled name="budget_head_name" class="form-control form-control-sm "
                                   value="{{isset($concurrenceTranInfo->budget_head_name) ? $concurrenceTranInfo->budget_head_name : ''}}"
                                   type="text" id="budget_head_name">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="budget_sub_category" class="col-form-label col-sm-3">Sub Category</label>
                        <div class="col-sm-9">
                            <input disabled name="budget_sub_category" class="form-control form-control-sm"
                                   value="{{isset($concurrenceTranInfo->sub_category_name) ? $concurrenceTranInfo->sub_category_name : ''}}"
                                   type="text"
                                   id="budget_sub_category">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="budget_category" class="col-form-label col-sm-3">Category</label>
                        <div class="col-sm-9">
                            <input disabled name="budget_category" class="form-control form-control-sm"
                                   value="{{isset($concurrenceTranInfo->category_name) ? $concurrenceTranInfo->category_name : ''}}"
                                   type="text"
                                   id="budget_category">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="budget_type" class="col-form-label col-sm-3">Budget Type</label>
                        <div class="col-sm-9">
                            <input style="background-color: yellow" disabled name="budget_type" class="form-control form-control-sm "
                                   value="{{isset($concurrenceTranInfo->budget_type) ? $concurrenceTranInfo->budget_type : ''}}"
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
                                    <input disabled class="form-control form-control-sm" name="fill_no" id="fill_no"
                                           value="{{isset($concurrenceTranInfo->file_no) ? $concurrenceTranInfo->file_no : ''}}">
                                </div>
                            </div>
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="memo_no" class=" col-sm-5 col-form-label pl-0">Memo No</label>
                                <div class="col-sm-7 pl-0 pr-0">
                                    <input disabled class="form-control form-control-sm" name="memo_no" id="memo_no"
                                           value="{{isset($concurrenceTranInfo->memo_no) ? $concurrenceTranInfo->memo_no : ''}}">
                                </div>
                            </div>
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="est_amount" class=" col-sm-5 col-form-label pl-0">Est. Amount</label>
                                <div class="col-sm-7 pl-0 pr-0">
                                    <input style="background-color: yellow" disabled class="form-control form-control-sm text-right " name="est_amount" id="est_amount"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="page_no" class=" col-sm-5 col-form-label">Page No</label>
                                <div class="col-sm-7 pl-0">
                                    <input disabled class="form-control form-control-sm" name="page_no" id="page_no"
                                           value="{{isset($concurrenceTranInfo->page_no) ? $concurrenceTranInfo->page_no : ''}}">
                                </div>
                            </div>
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="memo_date_field" class="col-sm-5 col-form-label">Memo Date</label>
                                <div class="input-group memo_date col-sm-7 pl-0"
                                     id="memo_date"
                                     data-target-input="nearest">
                                    <input disabled class="form-control form-control-sm" name="memo_date_field" id="memo_date_field"
                                           value="{{isset($concurrenceTranInfo->memo_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->memo_date) : ''}}">
                                </div>
                            </div>
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="est_date_field" class=" col-sm-5 col-form-label">Est. Date</label>
                                <div class="input-group date est_date col-sm-7 pl-0"
                                     id="est_date"
                                     data-target-input="nearest">
                                    <input disabled class="form-control form-control-sm" name="est_date_field" id="est_date_field"
                                           value="{{isset($concurrenceTranInfo->estimate_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->estimate_date) : ''}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 offset-2 mb-1">
                                <textarea readonly rows="2" class="form-control form-control-sm pl-1" id="est_amount_word"
                                          tabindex="-1"></textarea>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:8px">
                        <label for="remarks" class=" col-sm-2 col-form-label pl-0">Remarks</label>
                        <div class="col-sm-10">
                <textarea  name="remarks" class=" form-control form-control-sm" disabled
                           id="remarks">{{isset($concurrenceTranInfo->remarks) ? $concurrenceTranInfo->remarks : ''}}</textarea>

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
                            Info</strong>
                    </legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="tender_proposal_type" class=" col-sm-3 col-form-label">Tender
                                    Type</label>
                                <div class="col-sm-9">
                                    <input name="tender_proposal_type" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->tender_proposal_type) ? $concurrenceTranInfo->tender_proposal_type : ''}}"
                                           type="text"
                                           id="tender_proposal_type" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label class="col-form-label col-md-3" for="vendor_id">Party/Vendor ID</label>
                                <div class="col-md-5">
                                    <input  name="vendor_id" class="form-control form-control-sm " value="{{isset($concurrenceTranInfo->vendor_id) ? $concurrenceTranInfo->vendor_id : ''}}"
                                            type="text"
                                            id="vendor_id" disabled
                                            maxlength="10">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-sm btn-primary vendorIdSearch" id="vendor_search"
                                            type="button" disabled
                                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                            class="align-middle ml-25">Search</span>
                                    </button>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label class="col-form-label col-md-3 pr-0" for="vendor_name">Party/Vendor Name</label>
                                <div class="col-md-9">
                                    <input  type="text" class="form-control form-control-sm" id="vendor_name" value="{{isset($concurrenceTranInfo->vendor_name) ? $concurrenceTranInfo->vendor_name : ''}}"
                                            name="vendor_name" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="contract_id" class="col-form-label col-sm-3">Contract Id</label>
                                <div class="col-sm-5">
                                    <input name="contract_id" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->contract_id) ? $concurrenceTranInfo->contract_id : ''}}"
                                           type="text" disabled
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
                                <label for="tender_proposal_no" class="col-form-label col-sm-3">Tender/Prop.
                                    No</label>
                                <div class="col-sm-4 pr-0">
                                    <input name="tender_proposal_no" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->tender_proposal_no) ? $concurrenceTranInfo->tender_proposal_no : ''}}"
                                           type="text"
                                           id="tender_proposal_no" disabled
                                    >
                                </div>
                                <label for="tender_proposal_date_field"
                                       class=" col-sm-2 col-form-label pr-0">Tender Date</label>
                                <div class="input-group tender_proposal_date col-sm-3"
                                     id="tender_proposal_date"
                                     data-target-input="nearest">
                                    <input name="tender_proposal_date_field" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->tender_proposal_date) ?  \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->tender_proposal_date) : ''}}"
                                           type="text"
                                           id="tender_proposal_date_field" disabled>
                                    {{--<div class="input-group-append tender_proposal_date" data-target="#tender_proposal_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="tender_proposal_ref" class="col-form-label col-sm-3">Tender/Prop. Ref</label>
                                <div class="col-sm-9">
                                    <input name="tender_proposal_ref" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->tender_proposal_ref) ? $concurrenceTranInfo->tender_proposal_ref : ''}}"
                                           type="text"
                                           id="tender_proposal_ref"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="contract_no" class="col-form-label col-sm-3">Contract No</label>
                                <div class="col-sm-4 pr-0">
                                    <input name="contract_no" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->contract_no) ? $concurrenceTranInfo->contract_no : ''}}"
                                           type="text" disabled
                                           id="contract_no"
                                    >
                                </div>
                                <label for="contract_date_field" class="col-sm-2 pr-0 col-form-label text-right">Date</label>
                                <div class="input-group date contract_date col-sm-3"
                                     id="contract_date"
                                     data-target-input="nearest">
                                    <input disabled class="form-control form-control-sm" name="contract_date_field" id="contract_date_field"
                                           value="{{isset($concurrenceTranInfo->contract_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->contract_date) : ''}}">

                                    {{--<div class="input-group-append contract_date" data-target="#contract_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="subject" class="col-form-label col-sm-3">Subject</label>
                                <div class="col-sm-9">
                                    <input name="subject" class="form-control form-control-sm"
                                           value="{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}"
                                           type="text"
                                           id="subject" disabled
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="contract_value" class="col-form-label col-sm-3">Contract
                                    Value</label>
                                <div class="col-sm-4 pr-0">
                                    <input name="contract_value" class="form-control form-control-sm"
                                           value="{{--{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}--}}"
                                           type="text"
                                           id="contract_value" disabled
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-bottom:8px">
                                <label for="booking_amount" class="col-form-label col-sm-3 required pr-0">Booking Amount</label>
                                <div class="col-sm-4 pr-0">
                                    {{--<input required name="booking_amount" class="form-control form-control-sm" value=""
                                           type="number" step="0.1" oninput="maxLengthValid(this)"
                                           id="booking_amount"
                                           maxlength="20">--}}
                                    {{--Add this part Pavel:13-03-22--}}
                                    <input style="background-color: yellow" name="booking_amount" class="form-control form-control-sm text-right "
                                           value=""
                                           type="text"
                                           id="booking_amount" disabled
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 offset-3 mb-1">
                                <textarea style="background-color: yellow" readonly class="form-control form-control-sm pl-1 " rows="2" id="booking_amount_word"
                                          tabindex="-1"></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <fieldset class="border p-1 mt-2 col-sm-12">
        <legend class="w-auto " style="font-size: 14px;"><strong> Attachments</strong></legend>
        <div class="table-responsive fixed-height-scrollable">
            <table class="table table-sm table-bordered table-striped" id="inv_pay_attach_table">
                <thead class="thead-light sticky-head">
                <tr>
                    <th>#SL No</th>
                    <th>Attachment Name</th>
                    <th>Attachment Type</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                @if(count($budgetBookingDocsList) > 0)
                    @php $index=1; @endphp
                    @foreach ($budgetBookingDocsList as $value)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $value->doc_file_name }}</td>
                            <td>{{ $value->doc_file_desc }}</td>
                            <td>
                                @if($value && $value->doc_file_name)
                                    <a href="{{ route('budget-mon-download.download-budget-mon-attachment', [$value->doc_file_id]) }}"
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
                        <th colspan="4" class="text-center"> No Data Found</th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </fieldset>
</div>

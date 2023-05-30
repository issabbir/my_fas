<div class="row">
    {{--<h5 class="ml-2" style="text-decoration: underline">Department-wise Budget Initialization</h5>--}}
    <fieldset class="width-45-per p-1 border">
        <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="fiscal_year" class="required col-sm-4 col-form-label">Financial Year</label>
            <input disabled name="fiscal_year" class="form-control form-control-sm col-md-3" type="text" id="fiscal_year"
                   value="{{isset($concurrenceTranInfo->fiscal_year) ? $concurrenceTranInfo->fiscal_year : ''}}">
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="department" class="col-form-label col-sm-4 required">Dept/Cost Center</label>
            <div class="col-sm-8 pl-0">
                <input disabled name="department" class="form-control form-control-sm" type="text" id="department"
                       value="{{isset($concurrenceTranInfo->cost_center_dept) ? $concurrenceTranInfo->cost_center_dept : ''}}">
            </div>

        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label class="col-sm-4 col-form-label" for="budget_head_id">Budget Head ID</label>
            <input disabled name="budget_head_id" class="form-control form-control-sm col-md-3" type="text" id="budget_head_id"
                   value="{{isset($concurrenceTranInfo->budget_head_id) ? $concurrenceTranInfo->budget_head_id : ''}}">
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="budget_head_name" class="col-form-label col-sm-4">Budget Head Name</label>
            <div class="col-sm-8 pl-0">
                <input disabled name="budget_head_name" class="form-control form-control-sm"
                       value="{{isset($concurrenceTranInfo->budget_head_name) ? $concurrenceTranInfo->budget_head_name : ''}}"
                       type="text" id="budget_head_name">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="budget_sub_category" class="col-form-label col-sm-4">Budget Sub Category</label>
            <div class="col-sm-8 pl-0">
                <input disabled name="budget_sub_category" class="form-control form-control-sm"
                       value="{{isset($concurrenceTranInfo->sub_category_name) ? $concurrenceTranInfo->sub_category_name : ''}}"
                       type="text"
                       id="budget_sub_category">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="budget_category" class="col-form-label col-sm-4">Budget Category</label>
            <div class="col-sm-8 pl-0">
                <input disabled name="budget_category" class="form-control form-control-sm"
                       value="{{isset($concurrenceTranInfo->category_name) ? $concurrenceTranInfo->category_name : ''}}"
                       type="text"
                       id="budget_category">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="budget_type" class="col-form-label col-sm-4">Budget Type</label>
            <div class="col-sm-8 pl-0">
                <input disabled name="budget_type" class="form-control form-control-sm"
                       value="{{isset($concurrenceTranInfo->budget_type) ? $concurrenceTranInfo->budget_type : ''}}"
                       type="text"
                       id="budget_type">
            </div>
        </div>
    </fieldset>
    <div style="width: 2%!important"></div>
    <fieldset class="width-50-per p-1 border" {{--style="margin-left: 5px;"--}}>
        <legend class="w-auto" style="font-size: 14px; font-weight: bold">Summary</legend>
        <div class="row mb-1">
            <div class="col-sm-10 d-flex justify-content-end">
                <span>Figure in Tk</span>
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="ministry_approved" class="col-form-label col-sm-5">Ministry Approved
                Amount</label>
            <div class="col-sm-5">
                <input disabled name="ministry_approved" class="form-control form-control-sm text-right-align"
                       value="{{isset($concurrenceTranInfo->budget_approved_amt) ? $concurrenceTranInfo->budget_approved_amt : ''}}"
                       type="text"
                       id="ministry_approved"
                       >
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="utilized_amount" class="col-form-label col-sm-5">Booked Amount {{--Utilized Amount--}}</label>
            <div class="col-sm-5">
                <input disabled name="utilized_amount" class="form-control form-control-sm text-right-align"
                       value="{{isset($concurrenceTranInfo->budget_utilized_amt) ? $concurrenceTranInfo->budget_utilized_amt : ''}}"
                       type="text"
                       id="utilized_amount"
                       >
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="balance_amount" class="col-form-label col-sm-5">Balance Amount</label>
            <div class="col-sm-5">
                <input disabled name="balance_amount" class="form-control form-control-sm text-right-align"
                       value="{{isset($concurrenceTranInfo->budget_balance_amt) ? $concurrenceTranInfo->budget_balance_amt : ''}}"
                       type="text"
                       id="balance_amount"
                       >
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <fieldset class="width-45-per border p-2">
        <legend class="w-auto" style="font-size: 14px; font-weight: bold">Transaction Reference</legend>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="bill_section" class="required col-sm-4 col-form-label">Bill Section</label>
            <div class="col-sm-8 pl-0">
                <input disabled class="form-control form-control-sm" name="bill_section" id="bill_section"
                       value="{{isset($concurrenceTranInfo->bill_sec_name) ? $concurrenceTranInfo->bill_sec_name : ''}}">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="bill_register" class="required col-sm-4 col-form-label">Select Bill Register</label>
            <div class="col-sm-8 pl-0">
                <input disabled class="form-control form-control-sm" name="bill_register" id="bill_register"
                       value="{{isset($concurrenceTranInfo->bill_reg_name) ? $concurrenceTranInfo->bill_reg_name : ''}}">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="transaction_period" class="required col-sm-4 col-form-label">Transaction
                Period</label>
            <div class="col-sm-5 pl-0">
                <input disabled class="form-control form-control-sm" name="transaction_period" id="transaction_period"
                       value="{{isset($concurrenceTranInfo->trans_period_name) ? $concurrenceTranInfo->trans_period_name : ''}}">
            </div>

        </div>
        <div class="form-group row ">
            <label for="transaction_date_field" class="required col-sm-4 col-form-label ">Transaction Date</label>
            <div class="col-sm-5 pl-0">
                <input disabled class="form-control form-control-sm " name="transaction_date_field" id="transaction_date_field"
                       value="{{isset($concurrenceTranInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->trans_date) : ''}}">
            </div>
        </div>
        <div class="form-group row" style="margin-bottom:8px">
            <label for="voucher_no" class="required col-sm-4 col-form-label">Voucher No</label>
            <div class="col-sm-8 pl-0">
                <input disabled class="form-control form-control-sm" name="voucher_no" id="voucher_no"
                       value="{{isset($concurrenceTranInfo->voucher_no) ? $concurrenceTranInfo->voucher_no : ''}}">
            </div>
        </div>
    </fieldset>
    <div style="width: 2%!important;"></div>
    <fieldset class="width-50-per border p-2">
        <legend class="w-auto" style="font-size: 14px; font-weight: bold">File/Memo Reference</legend>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="fill_no" class="col-sm-4 col-form-label pl-0">File No</label>
                    <div class="col-sm-8">
                        <input disabled class="form-control form-control-sm" name="fill_no" id="fill_no"
                               value="{{isset($concurrenceTranInfo->file_no) ? $concurrenceTranInfo->file_no : ''}}">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="memo_no" class=" col-sm-4 col-form-label pl-0">Memo No</label>
                    <div class="col-sm-8">
                        <input disabled class="form-control form-control-sm" name="memo_no" id="memo_no"
                               value="{{isset($concurrenceTranInfo->memo_no) ? $concurrenceTranInfo->memo_no : ''}}">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="est_amount" class=" col-sm-4 col-form-label pl-0">Estimation Amount</label>
                    <div class="col-sm-8">
                        <input disabled class="form-control form-control-sm text-right" name="est_amount" id="est_amount"
                               value="{{isset($concurrenceTranInfo->estimate_amount) ? $concurrenceTranInfo->estimate_amount : ''}}">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="page_no" class=" col-sm-4 col-form-label pl-0">Page No</label>
                    <div class="col-sm-8 pl-0">
                        <input disabled class="form-control form-control-sm" name="page_no" id="page_no"
                               value="{{isset($concurrenceTranInfo->page_no) ? $concurrenceTranInfo->page_no : ''}}">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="memo_date_field" class="col-sm-4 col-form-label pl-0">Memo Date</label>
                    <div class="col-sm-8 pl-0">
                        <input disabled class="form-control form-control-sm" name="memo_date_field" id="memo_date_field"
                               value="{{isset($concurrenceTranInfo->memo_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->memo_date) : ''}}">
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="est_date_field" class=" col-sm-4 col-form-label pl-0">Est. Date</label>
                    <div class="col-sm-8 pl-0">
                        <input disabled class="form-control form-control-sm" name="est_date_field" id="est_date_field"
                               value="{{isset($concurrenceTranInfo->estimate_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->estimate_date) : ''}}">
                    </div>
                </div>
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
<div class="row">
    <fieldset class="col-sm-12 border p-2">
        <legend class="w-auto" style="font-size: 14px; font-weight: bold"><strong>Contract Tender Info</strong>
        </legend>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="vendor_id">Party/Vendor ID</label>
            <div class="col-md-2 pl-0">
                <input  name="vendor_id" class="form-control form-control-sm " value="{{isset($concurrenceTranInfo->vendor_id) ? $concurrenceTranInfo->vendor_id : ''}}"
                       type="text"
                       id="vendor_id" disabled
                       maxlength="10">
            </div>
            <div class="col-md-2 pl-0">
                <button class="btn btn-sm btn-primary vendorIdSearch" id="vendor_search" type="button" disabled
                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                        class="align-middle ml-25">Search</span>
                </button>
            </div>
            <div class="col-md-6">
                <input  type="text" class="form-control form-control-sm" id="vendor_name" value="{{isset($concurrenceTranInfo->vendor_name) ? $concurrenceTranInfo->vendor_name : ''}}"
                       name="vendor_name" disabled>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="contract_id" class="col-form-label col-sm-4">Contract Id</label>
                    <div class="col-sm-4 pl-0">
                        <input name="contract_id" class="form-control form-control-sm bg-info bg-accent-2"
                               value="{{isset($concurrenceTranInfo->contract_id) ? $concurrenceTranInfo->contract_id : ''}}"
                               type="text" disabled
                               id="contract_id">
                    </div>
                    <div class="col-sm-4 pl-0 pr-0">
                        <button disabled type="button" class="btn btn-sm btn-info"><i class="bx bx-search"></i>Search
                        </button>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="tender_proposal_no" class="col-form-label col-sm-4">Tender/Proposal No</label>
                    <div class="col-sm-4 pl-0">
                        <input name="tender_proposal_no" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->tender_proposal_no) ? $concurrenceTranInfo->tender_proposal_no : ''}}"
                               type="text"
                               id="tender_proposal_no" disabled
                               >
                    </div>
                    <label for="tender_proposal_date_field" class=" col-sm-1 col-form-label pr-0">Date</label>
                    <div class="col-sm-3 pl-0">
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
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="tender_proposal_ref" class="col-form-label col-sm-4">Tender/Proposal Ref</label>
                    <div class="col-sm-8 pl-0">
                        <input name="tender_proposal_ref" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->tender_proposal_ref) ? $concurrenceTranInfo->tender_proposal_ref : ''}}"
                               type="text"
                               id="tender_proposal_ref"
                                disabled>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="tender_proposal_type" class=" col-sm-4 col-form-label">Tender/Proposal Type</label>
                    <div class="col-sm-4 pl-0">
                        <input name="tender_proposal_type" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->tender_proposal_type) ? $concurrenceTranInfo->tender_proposal_type : ''}}"
                               type="text"
                               id="tender_proposal_type" disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="contract_no" class="col-form-label col-sm-3 pr-0">Contract No</label>
                    <div class="col-sm-3 pl-0 pr-0">
                        <input name="contract_no" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->contract_no) ? $concurrenceTranInfo->contract_no : ''}}"
                               type="text" disabled
                               id="contract_no"
                               >
                    </div>
                    <label for="contract_date_field" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-4 pl-0 ">
                        <input disabled class="form-control form-control-sm" name="contract_date_field" id="contract_date_field"
                               value="{{isset($concurrenceTranInfo->contract_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->contract_date) : ''}}">
                    </div>


                </div>
                {{--<div class="form-group row" style="margin-bottom:8px">
                    <label for="party_name" class="col-form-label col-sm-3">Party Name</label>
                    <div class="col-sm-9 pl-0">
                        <input name="party_name" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->contract_party_name) ? $concurrenceTranInfo->contract_party_name : ''}}"
                               type="text" disabled
                               id="party_name"
                               >
                    </div>
                </div>--}}
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="subject" class="required col-form-label col-sm-3  pr-0">Subject</label>
                    <div class="col-sm-9 pl-0">
                        <input name="subject" class="form-control form-control-sm"
                               value="{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}"
                               type="text"
                               id="subject" disabled
                               >
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="contract_value" class="col-form-label col-sm-3 pr-0">Contract Value</label>
                    <div class="col-sm-4 pl-0">
                        <input name="contract_value" class="form-control form-control-sm"
                               value="{{--{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}--}}"
                               type="text"
                               id="contract_value" disabled
                               >
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom:8px">
                    <label for="booking_amount" class="col-form-label col-sm-3 required">Booking Amount</label>
                    <div class="col-sm-4 pl-0">
                        <input name="booking_amount" class="form-control form-control-sm text-right"
                               value="{{isset($concurrenceTranInfo->budget_booking_amount) ? $concurrenceTranInfo->budget_booking_amount : ''}}"
                               type="text"
                               id="booking_amount" disabled
                        >
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>



{{--<fieldset class="col-md-12 border p-2 mt-2">
    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Information</legend>

    <div class="form-group row">
        <label for="fiscal_year" class=" col-md-2 col-form-label">Financial Year</label>
        <input disabled name="fiscal_year" class="form-control form-control-sm col-md-3" type="text" id="fiscal_year" value="{{isset($concurrenceTranInfo->fiscal_year) ? $concurrenceTranInfo->fiscal_year : ''}}" >
        --}}{{--<select  name="fiscal_year"
                class="form-control form-control-sm col-md-3  {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                id="fiscal_year">
            <option value="">Select Year</option>
            @foreach($data['financialYear'] as $year)
                <option
                    {{ (old('fiscal_year',(isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id : '')) == $year->fiscal_year_id) ? __('selected') : '' }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
            @endforeach
        </select>--}}{{--
    </div>
    <div class="form-group row">
        <label for="department" class="col-form-label col-md-2 ">Dept/Cost Center</label>
        <div class="col-md-5 pl-0 pr-0">
            <input disabled name="department" class="form-control form-control-sm" type="text" id="department" value="{{isset($concurrenceTranInfo->cost_center_dept) ? $concurrenceTranInfo->cost_center_dept : ''}}" >
            --}}{{--<select  name="department"
                    class="form-control form-control-sm select2 {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                    id="department"
                    data-predpt="{{ old('department',(isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id : ''))}}">
            </select>--}}{{--
        </div>

    </div>
    <div class="form-group row">
        <label class=" col-md-2 col-form-label" for="budget_head_id">Budget Head ID</label>
        <input disabled name="budget_head_id" class="form-control form-control-sm col-md-3" type="text" id="budget_head_id" value="{{isset($concurrenceTranInfo->budget_head_id) ? $concurrenceTranInfo->budget_head_id : ''}}" >
        --}}{{--<input name="budget_head_id" class="form-control form-control-sm col-md-3"
               value="" type="number"
               id="budget_head_id"
                oninput="maxLengthValid(this)"
               onkeyup="resetBudgetField()">
        <div class="col-md-2 d-flex justify-content-end pr-0">
            <button class="btn btn-primary searchBudget" id="search_budget"
                    type="button"
                    tabindex="-1"><i class="bx bx-search"></i>Search
            </button>
        </div>--}}{{--
    </div>
    <div class="form-group row">
        <label for="budget_head_name" class="col-form-label col-md-2">Budget Head Name</label>
        <div class="col-md-10 pl-0">
            <input disabled name="budget_head_name" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->budget_head_name) ? $concurrenceTranInfo->budget_head_name : ''}}" type="text"
                   id="budget_head_name">
        </div>
    </div>
    <div class="form-group row">
        <label for="budget_sub_category" class="col-form-label col-md-2">Budget Sub Category</label>
        <div class="col-md-10 pl-0">
            <input disabled name="budget_sub_category" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->sub_category_name) ? $concurrenceTranInfo->sub_category_name : ''}}" type="text"
                   id="budget_sub_category">
        </div>
    </div>
    <div class="form-group row">
        <label for="budget_category" class="col-form-label col-md-2">Budget Category</label>
        <div class="col-md-10 pl-0">
            <input disabled name="budget_category" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->category_name) ? $concurrenceTranInfo->category_name : ''}}" type="text"
                   id="budget_category">
        </div>
    </div>
    <div class="form-group row">
        <label for="budget_type" class="col-form-label col-md-2">Budget Type</label>
        <div class="col-md-10 pl-0">
            <input disabled name="budget_type" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->budget_type) ? $concurrenceTranInfo->budget_type : ''}}" type="text"
                   id="budget_type">
        </div>
    </div>
    <div class="form-group row d-flex justify-content-end">
        <div class="col-md-5">
            <div class="row mb-1">
                <div class="col-md-12 d-flex justify-content-end">
                    <span>Figure in Tk</span>
                </div>
            </div>
            <div class="form-group row">
                <label for="ministry_approved" class="col-form-label col-md-6">Ministry Approved
                    Amount</label>
                <div class="col-md-6">
                    <input disabled name="ministry_approved" class="form-control form-control-sm text-right-align" value="{{isset($concurrenceTranInfo->budget_approved_amt) ? $concurrenceTranInfo->budget_approved_amt : ''}}" type="text"
                           id="ministry_approved"
                           >
                </div>
            </div>
            <div class="form-group row">
                <label for="utilized_amount" class="col-form-label col-md-6">Utilized Amount</label>
                <div class="col-md-6">
                    <input disabled name="utilized_amount" class="form-control form-control-sm text-right-align" value="{{isset($concurrenceTranInfo->budget_utilized_amt) ? $concurrenceTranInfo->budget_utilized_amt : ''}}" type="text"
                           id="utilized_amount"
                           >
                </div>
            </div>
            <div class="form-group row">
                <label for="balance_amount" class="col-form-label col-md-6">Balance Amount</label>
                <div class="col-md-6">
                    <input disabled name="balance_amount" class="form-control form-control-sm text-right-align" value="{{isset($concurrenceTranInfo->budget_balance_amt) ? $concurrenceTranInfo->budget_balance_amt : ''}}" type="text"
                           id="balance_amount"
                           >
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="col-md-12 border p-2">
    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Transaction Info</legend>

    <div class="form-group row">
        <label for="bill_section" class=" col-md-2 col-form-label">Bill Section</label>
        <div class="col-md-10 pl-0">
            <input disabled class="form-control form-control-sm" name="bill_section" id="bill_section" value="{{isset($concurrenceTranInfo->bill_sec_name) ? $concurrenceTranInfo->bill_sec_name : ''}}">
            --}}{{--<select  name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                <option value="">Select Bill Section</option>
                @foreach($data['billSecs'] as $value)
                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                    </option>
                @endforeach
            </select>--}}{{--
        </div>
    </div>
    <div class="form-group row">
        <label for="bill_register" class=" col-md-2 col-form-label">Select Bill Register</label>
        <div class="col-md-10 pl-0">
            <input disabled class="form-control form-control-sm" name="bill_register" id="bill_register" value="{{isset($concurrenceTranInfo->bill_reg_name) ? $concurrenceTranInfo->bill_reg_name : ''}}">
            --}}{{--<select  name="bill_register" class="form-control form-control-sm select2" id="bill_register">
            </select>--}}{{--
        </div>
    </div>
    <div class="form-group row">
        <label for="transaction_period" class=" col-md-2 col-form-label">Transaction
            Period</label>
        <input disabled class="form-control form-control-sm col-md-3" name="transaction_period" id="transaction_period" value="{{isset($concurrenceTranInfo->trans_period_name) ? $concurrenceTranInfo->trans_period_name : ''}}">
        --}}{{--<select  name="transaction_period"
                class="form-control form-control-sm col-md-3  {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                data-preperiod="{{ old('transaction_period',(isset($data['insertedData']) ? $data['insertedData']->budget_init_period_id : ''))}}"
                id="transaction_period">
        </select>--}}{{--
    </div>
    <div class="form-group row ">
        <label for="transaction_date_field" class=" col-md-2 col-form-label ">Transaction Date</label>
        <input disabled class="form-control form-control-sm col-md-3 " name="transaction_date_field" id="transaction_date_field" value="{{isset($concurrenceTranInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->trans_date) : ''}}">
        --}}{{--<div
            class="input-group date transaction_date col-md-3 pl-0 pr-0 {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
            id="transaction_date"
            data-target-input="nearest">
            <input  type="text" autocomplete="off" onkeydown="return false"
                   {{(isset($data['insertedData']) ? __('readonly') : '')}}
                   name="transaction_date"
                   id="transaction_date_field"
                   class="form-control form-control-sm datetimepicker-input"
                   data-target="#transaction_date"
                   data-toggle="datetimepicker"
                   value="{{ old('transaction_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->budget_init_date) : '') }}"
                   data-predefined-date="{{ old('transaction_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->budget_init_date) : '') }}"
                   placeholder="DD-MM-YYYY">
            <div class="input-group-append transaction_date" data-target="#transaction_date"
                 data-toggle="datetimepicker">
                <div class="input-group-text">
                    <i class="bx bx-calendar"></i>
                </div>
            </div>
        </div>--}}{{--
    </div>
    <div class="form-group row">
        <label for="voucher_no" class=" col-md-2 col-form-label">Voucher No</label>
        <div class="col-md-10 pl-0">
            <input disabled class="form-control form-control-sm" name="voucher_no" id="voucher_no" value="{{isset($concurrenceTranInfo->voucher_no) ? $concurrenceTranInfo->voucher_no : ''}}">
            --}}{{--<input   name="voucher_no"
                   value="{{ old('voucher_no',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}"
                   class=" form-control form-control-sm"
                   id="voucher_no">--}}{{--
        </div>
    </div>
</fieldset>

<fieldset class="col-md-12 border p-2">
    <legend class="w-auto" style="font-size: 15px; font-weight: bold">Document Reference</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="fill_no" class="col-md-4 col-form-label">File No</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input disabled class="form-control form-control-sm" name="fill_no" id="fill_no" value="{{isset($concurrenceTranInfo->file_no) ? $concurrenceTranInfo->file_no : ''}}">
                    --}}{{--<input  name="fill_no"
                           value="{{ old('fill_no',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}"
                           class=" form-control form-control-sm"
                           id="fill_no">--}}{{--
                </div>
            </div>
            <div class="form-group row">
                <label for="memo_no" class=" col-md-4 col-form-label">Memo No</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input disabled class="form-control form-control-sm" name="memo_no" id="memo_no" value="{{isset($concurrenceTranInfo->memo_no) ? $concurrenceTranInfo->memo_no : ''}}">
                    --}}{{--<input  name="memo_no"
                           value="{{ old('memo_no',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}"
                           class=" form-control form-control-sm"
                           id="memo_no">--}}{{--
                </div>
            </div>
            <div class="form-group row">
                <label for="est_amount" class=" col-md-4 col-form-label">Estimation Amount</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input disabled class="form-control form-control-sm" name="est_amount" id="est_amount" value="{{isset($concurrenceTranInfo->estimate_amount) ? $concurrenceTranInfo->estimate_amount : ''}}">
                    --}}{{--<input   name="est_amount"
                           value="{{ old('est_amount',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}"
                           class=" form-control form-control-sm"
                           id="est_amount">--}}{{--
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row d-flex justify-content-end">
                <label for="page_no" class=" col-md-3 col-form-label">Page No</label>
                <div class="col-md-5">
                    <input disabled class="form-control form-control-sm" name="page_no" id="page_no" value="{{isset($concurrenceTranInfo->page_no) ? $concurrenceTranInfo->page_no : ''}}">
                    --}}{{--<input  name="page_no"
                           value="{{ old('page_no',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}"
                           class=" form-control form-control-sm"
                           id="page_no">--}}{{--
                </div>
            </div>
            <div class="form-group row d-flex justify-content-end">
                <label for="memo_date_field" class="col-md-3 col-form-label ">Memo Date</label>
                <div class="col-md-5">
                    <input disabled class="form-control form-control-sm" name="memo_date_field" id="memo_date_field" value="{{isset($concurrenceTranInfo->memo_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->memo_date) : ''}}">
                </div>
                --}}{{--<div class="input-group date memo_date col-md-5"
                     id="memo_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="memo_date"
                           id="memo_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#memo_date"
                           data-toggle="datetimepicker"
                           value="{{ old('memo_date', isset($data['insertedData']->posting_date) ?  $data['insertedData']->posting_date : '') }}"
                           data-predefined-date="{{ old('memo_date', isset($data['insertedData']->posting_date) ?  $data['insertedData']->posting_date : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append memo_date" data-target="#memo_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>--}}{{--
            </div>
            <div class="form-group row d-flex justify-content-end">
                <label for="est_date_field" class=" col-md-3 col-form-label">Est. Date</label>
                <div class="col-md-5">
                    <input disabled class="form-control form-control-sm" name="est_date_field" id="est_date_field" value="{{isset($concurrenceTranInfo->estimate_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->estimate_date) : ''}}">
                </div>
                --}}{{--<div class="input-group date est_date col-md-5"
                     id="est_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="est_date"
                           id="est_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#est_date"
                           data-toggle="datetimepicker"
                           value="{{ old('est_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           data-predefined-date="{{ old('est_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append est_date" data-target="#est_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>--}}{{--
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="remarks" class=" col-md-2 col-form-label">Remarks</label>
        <div class="col-md-10 pl-0">
                    <textarea  name="remarks" class=" form-control form-control-sm" disabled
                              id="remarks">{{isset($concurrenceTranInfo->remarks) ? $concurrenceTranInfo->remarks : ''}}</textarea>
        </div>
    </div>
</fieldset>

<fieldset class="col-md-12 border p-2">
    <legend class="w-auto" style="font-size: 14px; font-weight: bold"><strong>Contract Tender Info</strong>
    </legend>
    <div class="form-group row">
        <label for="contract_id" class="col-form-label col-md-2">Contract Id</label>
        <input name="contract_id" class="form-control form-control-sm col-md-3 bg-info bg-accent-2" value="{{isset($concurrenceTranInfo->contract_id) ? $concurrenceTranInfo->contract_id : ''}}" type="text" disabled
               id="contract_id"
               >
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="contract_no" class="col-form-label col-md-4">Contract No</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input name="contract_no" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->contract_no) ? $concurrenceTranInfo->contract_no : ''}}" type="text" disabled
                           id="contract_no"
                           >
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row d-flex justify-content-end">
                <label for="contract_date_field" class="col-md-3 col-form-label">Contract Date</label>
                <div class="col-md-5">
                    <input disabled class="form-control form-control-sm" name="contract_date_field" id="contract_date_field" value="{{isset($concurrenceTranInfo->contract_date) ? \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->contract_date) : ''}}">
                </div>
                --}}{{--<div class="input-group date contract_date col-md-5"
                     id="contract_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="contract_date"
                           id="contract_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#contract_date"
                           data-toggle="datetimepicker"
                           value="{{ old('contract_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           data-predefined-date="{{ old('contract_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append contract_date" data-target="#contract_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>--}}{{--
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="party_name" class="col-form-label col-md-2">Party Name</label>
        <div class="col-md-10 pl-0">
            <input name="party_name" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->contract_party_name) ? $concurrenceTranInfo->contract_party_name : ''}}" type="text" disabled
                   id="party_name"
                   >
        </div>
    </div>
    <div class="form-group row">
        <label for="subject" class=" col-form-label col-md-2 ">Subject</label>
        <div class="col-md-10 pl-0">
            <input  name="subject" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}" type="text"
                    id="subject" disabled
                    >
        </div>
    </div>
    <div class="form-group row">
        <label for="contract_value" class="col-form-label col-md-2">Contract Value</label>
        <div class="col-md-3 pl-0 pr-0">
            <input name="contract_value" class="form-control form-control-sm" value="--}}{{--{{isset($concurrenceTranInfo->contract_subject) ? $concurrenceTranInfo->contract_subject : ''}}--}}{{--" type="text"
                   id="contract_value" disabled
                   >
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="tender_proposal_no" class="col-form-label col-md-4">Tender/Proposal No</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input name="tender_proposal_no" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->tender_proposal_no) ? $concurrenceTranInfo->tender_proposal_no : ''}}" type="text"
                           id="tender_proposal_no" disabled
                           >
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row d-flex justify-content-end">
                <label for="tender_proposal_date_field" class=" col-md-4 col-form-label">Tender/Proposal Date</label>
                <div class="col-md-5">
                    <input name="tender_proposal_date_field" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->tender_proposal_date) ?  \App\Helpers\HelperClass::dateConvert($concurrenceTranInfo->tender_proposal_date) : ''}}" type="text"
                           id="tender_proposal_date_field" disabled >
                </div>
                --}}{{--<div class="input-group date tender_proposal_date col-md-5"
                     id="tender_proposal_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="tender_proposal_date"
                           id="tender_proposal_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#tender_proposal_date"
                           data-toggle="datetimepicker"
                           value="{{ old('tender_proposal_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           data-predefined-date="{{ old('tender_proposal_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append tender_proposal_date" data-target="#tender_proposal_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>--}}{{--
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="tender_proposal_ref" class="col-form-label col-md-2">Tender/Proposal Ref</label>
        <div class="col-md-10 pl-0">
            <input name="tender_proposal_ref" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->tender_proposal_ref) ? $concurrenceTranInfo->tender_proposal_ref : ''}}" type="text"
                   id="tender_proposal_ref"
                    disabled>
        </div>
    </div>
    <div class="form-group row">
        <label for="tender_proposal_type" class=" col-md-2 col-form-label">Tender/Proposal Type</label>
        <div class="col-md-3 pl-0 pr-0">
            <input name="tender_proposal_type" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->tender_proposal_type) ? $concurrenceTranInfo->tender_proposal_type : ''}}" type="text"
                   id="tender_proposal_type" disabled>
            --}}{{--<select name="tender_proposal_type" class="form-control form-control-sm select2" id="tender_proposal_type">
                <option value="">Select Tender</option>
                @foreach( $data['lTenderType'] as $value)
                    <option value="{{$value->tender_type_id}}">{{ $value->tender_type_name}}
                    </option>
                @endforeach
            </select>--}}{{--
        </div>
    </div>
    <div class="form-group row">
        <label for="booking_amount" class="col-form-label col-md-2 ">Booking Amount</label>
        <div class="col-md-3 pl-0 pr-0">
            <input  name="booking_amount" class="form-control form-control-sm" value="{{isset($concurrenceTranInfo->budget_booking_amount) ? $concurrenceTranInfo->budget_booking_amount : ''}}" type="text"
                    id="booking_amount" disabled
                    >
        </div>
    </div>
</fieldset>

<fieldset class="border p-1 mt-2">
    <legend class="w-auto " style="font-size: 14px;"><strong> Attachments</strong></legend>
    <div class="row">
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
    </div>
</fieldset>--}}

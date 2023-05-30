<form id="invoice_salary_bill_entry_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <h5 style="text-decoration: underline">Salary Bill Entry</h5>
    {{--Used to set callback function name--}}
    <input type="hidden" id="callbackVar" name="callbackVar"/>
    {{--Used to set callback function name--}}

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Reference</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                    <div class="col-md-5">
                        <select required name="th_fiscal_year"
                                class="form-control form-control-sm required"
                                id="th_fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option
                                    {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                    <div class="col-md-5">
                        <select required name="period" class="form-control form-control-sm" id="period">
                            {{--<optin value="">Select a period</option>--}}
                            {{-- @foreach($postingDate as $post)
                                 <option
                                     {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }}
                                     data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                     data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                     data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                     data-postingname="{{ $post->posting_period_name}}"
                                     value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                 </option>
                             @endforeach--}}
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting Date</label>
                    <div class="input-group date posting_date col-md-5"
                         id="posting_date"
                         data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
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
                <div class="form-group row">
                    <label for="document_date_field" class="required col-md-4 col-form-label">Document Date</label>
                    <div class="input-group date document_date col-md-5"
                         id="document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="document_date"
                               id="document_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#document_date"
                               data-toggle="datetimepicker"
                               value="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                               data-predefined-date="{{ old('document_date', isset($data['insertedData']->document_date) ?  $data['insertedData']->document_date : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append document_date" data-target="#document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="form-group row">
                    <label for="document_number" class="required col-md-4 col-form-label">Document No</label>
                    <div class="col-md-5">
                        <input maxlength="50" type="text" required class="form-control form-control-sm"
                               oninput="this.value = this.value.toUpperCase()"
                               name="document_number"
                               id="document_number"
                               value="">
                    </div>

                </div>--}}
            </div>
            <div class="col-md-6">
                <div class="form-group row justify-content-end">
                    <label for="department" class="col-form-label col-md-4 required ">Dept/Cost Center</label>
                    <div class="col-md-6">
                        <select required name="department" class="form-control form-control-sm select2" id="department">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($department as $dpt)
                                <option
                                    {{  old('department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row d-flex justify-content-end">
                    <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                    <div class="col-md-6">
                        <select required name="bill_section" class="form-control form-control-sm select2"
                                id="bill_section">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($billSecs as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end">
                    <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                    <div class="col-md-6">
                        <select required name="bill_register" class="form-control form-control-sm select2"
                                id="bill_register">
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end">
                    <label for="emp_type_id" class="required col-md-4 col-form-label">Employee Type</label>
                    <div class="col-md-6">
                        <select name="emp_type_id" class="form-control form-control-sm"
                                id="emp_type_id">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($lFasEmployeeType as $value)
                                <option value="{{$value->employee_type_id}}">{{ $value->employee_type_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">

            <label for="document_number" class="{{isset($isRequired) ? $isRequired['document_required'] : ''}} col-md-2 col-form-label">Document No</label>
            <div class="col-md-3 pr-5">
                <input maxlength="50" type="text" class="form-control form-control-sm" {{isset($isRequired) ? $isRequired['document_required'] : ''}}
                       oninput="this.value = this.value.toUpperCase()"
                       name="document_number"
                       id="document_number"
                       value="">
            </div>


            <label for="document_reference" class="col-md-2 col-form-label text-right">Document Ref</label>
            <div class="col-md-5">
                <input maxlength="200" type="text" class="form-control form-control-sm" id="document_reference"
                       name="document_reference"
                       value="">
            </div>

        </div>
        <div class="form-group row">
            <label for="narration" class="required col-md-2 col-form-label">Narration</label>
            <div class="col-md-10">
                    <textarea maxlength="500" required name="narration" class="required form-control form-control-sm"
                              id="narration"></textarea>
            </div>
        </div>
    </fieldset>
@php
$gl_subSidery_id =App\Enums\Ap\ApFunType::AP_SALARY_ENTRY;

@endphp

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Party Ledger Info</legend>


        <div class="form-group row">
            <label class="required col-md-2 col-form-label" for="ap_party_sub_ledger">Party Sub-Ledger</label>
            <div class="col-md-6  make-readonly" >
                <select class="form-control form-control-sm col-md-9" id="ap_party_sub_ledger" required
                        name="ap_party_sub_ledger">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['subsidiary_type'] as $type)
                        <option data-glsubsidiary="{{$type->gl_subsidiary_type_id}}"
                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($gl_subSidery_id) ? $gl_subSidery_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                    @endforeach
{{--                    <option data-glsubsidiary="{{$data['subsidiary_type'][1]->gl_subsidiary_type_id}}"--}}
{{--                            value="{{$data['subsidiary_type'][1]->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $data['subsidiary_type'][1]->gl_subsidiary_id) ? 'Selected' : '' }}>{{$data['subsidiary_type'][1]->gl_subsidiary_name}}</option>--}}

                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label required" for="ap_invoice_type">Invoice Type</label>
            <div class="col-md-6">
                <select required class="form-control form-control-sm col-md-9" id="ap_invoice_type"
                        name="ap_invoice_type">
                    <option value="">&lt;Select&gt;</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_vendor_id">Party/Vendor ID</label>
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="input-group col-md-5">
                        <input required name="ap_vendor_id" class="form-control form-control-sm " value="" type="number"
                               id="ap_vendor_id"
                               maxlength="10"
                               oninput="maxLengthValid(this)"
                               onfocusout="addZerosInAccountId(this)"
                               onkeyup="resetField(['#ap_vendor_name','#ap_vendor_category','#party_name_for_tax',
                               '#party_name_for_vat','#bl_bills_payable','#bl_provision_exp',
                               '#bl_security_dep_pay','#bl_os_advances','#bl_os_prepayments','#bl_os_imp_rev',
                               '#b_booking_id','#b_head_id','#b_head_name','#b_sub_category','#b_category','#b_type','#b_date','#b_amt','#b_available_amt'
                               ]);enableDisablePoCheck(0);emptyTaxVatPayableDropdown()">
                    </div>
                    <div class="col-md-5 pl-0">
                        <button class="btn btn-sm btn-primary vendorIdSearch" id="ap_vendor_search" type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                class="align-middle ml-25">Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_vendor_name">Party/Vendor Name</label>
            <div class="col-md-10">
                <input tabindex="-1" required type="text" class="form-control form-control-sm" id="ap_vendor_name"
                       name="ap_vendor_name" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_vendor_category">Party/Vendor Category</label>
            <div class="col-md-10">
                <input required type="text" class="form-control form-control-sm" id="ap_vendor_category"
                       name="ap_vendor_category"
                       tabindex="-1" readonly>
            </div>
        </div>



        <div class="row">

            <div class="col-md-3 po_base_invoice">
                <button type="button" disabled class="btn btn-light-info btn-sm" id="search_po" data-toggle="tooltip"
                        data-placement="bottom" title="Search Purchase Order detail">Good Received Info
                    {{--<i class="bx bx-search"></i>--}}
                </button>
            </div>

        </div>

    </fieldset>





    <fieldset class="border  pl-1 pr-1 ">
        <legend class="w-auto text-bold-600" style="font-size: 18px;">Pay & Allowances(Debit)
        </legend>
        <input type="hidden" name="selected_pay_queue_inv_id" id="selected_pay_queue_inv_id">
        <div class="row mb-1">
            <div class="col-md-9 d-flex justify-content-end ">

            </div>
            <div class="col-md-3 form-group">
                <div class="position-relative has-icon-left mr-1">
                    <input type="text" id="table_search" class="form-control form-control-sm"
                           placeholder="Search Value"/>
                    <div class="form-control-position"><i class="bx bx-search"></i></div>
                </div>
            </div>
        </div>


        <div class="col-md-12 table-responsive {{--fixed-height-scrollable--}} table-scroll"
             id="inv_ref_pay_allow_table_search">
            <table class="table table-sm table-bordered table-striped" id="inv_ref_pay_allow_table">
                <thead class="thead-light {{--sticky-head--}}">
                <tr>

                    <th>Account Id</th>
                    <th>Account Name</th>
                    <th>Amount</th>

                </tr>
                </thead>
                <tbody id="invRefList"></tbody>
               <tfoot class="thead-light sticky-head-foot">
                    <tr class="font-small-3 ">
                        <th class="text-right" colspan="2">Total </th>
                        <th class="text-left" id="total_checked1"></th>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" name="total_pay_allowance" id="total_pay_allowance" value="" >
        </div>
        <div class="mt-1"></div>
    </fieldset>

    <fieldset class="border  pl-1 pr-1 ">
        <legend class="w-auto text-bold-600" style="font-size: 18px;">Deduction(Credit)
        </legend>
        <input type="hidden" name="selected_pay_queue_inv_id" id="selected_pay_queue_inv_id">
        <div class="row mb-1">
            <div class="col-md-9 d-flex justify-content-end ">

            </div>
            <div class="col-md-3 form-group">
                <div class="position-relative has-icon-left mr-1">
                    <input type="text" id="table_search_deduction" class="form-control form-control-sm"
                           placeholder="Search Value"/>
                    <div class="form-control-position"><i class="bx bx-search"></i></div>
                </div>
            </div>
        </div>


        <div class="col-md-12 table-responsive {{--fixed-height-scrollable--}} table-scroll"
             id="inv_ref_deduction_table_search">
            <table class="table table-sm table-bordered table-striped" id="inv_ref_deduction_table">
                <thead class="thead-light {{--sticky-head--}}">
                <tr>

                    <th>Account Id</th>
                    <th>Account Name</th>
                    <th>Amount</th>

                </tr>
                </thead>
                <tbody id="deductionList"></tbody>
                <tfoot class="thead-light sticky-head-foot">
                <tr class="font-small-3 ">
                    <th class="text-right" colspan="2">Total </th>
                    <th class="text-left" name="total_checked2" id="total_checked2" ></th>
                </tr>
                <tr class="font-small-3 ">
                    <th class="text-right" colspan="2">Net Payable </th>
                    <th class="text-left" name="total_net_payable" id="total_net_payable"></th>
                </tr>

                </tfoot>
            </table>

            {{--<table class="table table-sm table-bordered table-striped" >

                <tfoot class="thead-light sticky-head-foot">
                <tr class="font-small-3 ">
                    <th class="text-right" colspan="2">Net Payable </th>
                    <th class="text-left" name="total_net_payable" id="total_net_payable"></th>
                </tr>

                </tfoot>
            </table>--}}

            <input type="hidden" name="total_pay_deduction" id="total_pay_deduction" value="" >
            <input type="hidden" name="net_payable_amount" id="net_payable_amount" value="">
        </div>
        <div class="mt-1"></div>
    </fieldset>


    @include('ap.ap-common.salary_preview')
    @include('gl.common_file_upload')

    <div class="row mt-1">
        <div class="col-md-12 d-flex justify-content-start">
            <button type="submit" class="btn btn-sm btn-info mr-1" id="preview_btn" >
                <i
                    class="bx bx-printer font-size-small align-top"></i><span class="align-middle m-25">Preview</span>
            </button>
            <button type="submit" class="btn btn-sm btn-success mr-1" id="invoice_salary_bill_entry_form_submit_btn" >
                <i
                    class="bx bx-save font-size-small align-top"></i><span class="align-middle m-25">Save</span>
            </button>
            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>
            <div class="ml-1 mr-1" id="salary_print_btn"></div>
            <button type="button" class="btn btn-sm btn-dark" id="reset_form">
                <i class="bx bx-reset font-size-small align-top"></i><span class="align-middle ml-25">Reset</span>
            </button>
            <h6 class="text-primary ml-2">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{(isset($lastPostingBatch->last_posting_batch_id) ? $lastPostingBatch->last_posting_batch_id : '0')}}</span>
            </h6>

    </div>
</form>


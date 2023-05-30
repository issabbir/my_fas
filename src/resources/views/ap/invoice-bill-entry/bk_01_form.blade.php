<form id="invoice_bill_entry_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <h5 style="text-decoration: underline">Invoice/Bill Entry</h5>
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
                                <option {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
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
                <input type="hidden" name="posting_name" id="ap_posting_name">
                <input type="hidden" name="po_master_id" id="po_master_id">
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
                <div class="form-group row justify-content-end">
                    <label for="budget_department" class="col-form-label col-md-4 required ">Budget Department</label>
                    <div class="col-md-6">
                        <select required name="budget_department" class="form-control form-control-sm select2"
                                id="budget_department">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($department as $dpt)
                                <option
                                    {{  old('budget_department') ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{--<div class="form-group row justify-content-end">
                    <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                    <div class="col-md-5 pl-0">
                        <select required name="bill_register" class="form-control form-control-sm select2"
                                id="bill_register">
                            <option value="">Select Bill Register</option>
                            @foreach($billRegs as $value)
                                <option data-secid="{{$value->bill_sec_id}}" data-secname="{{$value->bill_sec_name}}" value="{{$value->bill_reg_id}}">{{ $value->bill_reg_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row justify-content-end make-readonly">
                    <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                    <div class="col-md-5 pl-0">
                        <select required name="bill_section" class="form-control form-control-sm" readonly=""
                                id="bill_section">
                        </select>
                    </div>
                </div>--}}
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
            </div>
        </div>
        <div class="form-group row">

            <label for="document_number" class="required col-md-2 col-form-label">Document No</label>
            <div class="col-md-3 pr-5">
                <input maxlength="50" type="text" required class="form-control form-control-sm pr-5"
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

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Party Ledger Info</legend>

        {{--{{ (!empty(\App\Helpers\HelperClass::findRoleWiseUser())) ? '' : 'test' }}--}}

        <div class="form-group row">
            <label class="required col-md-2 col-form-label" for="ap_party_sub_ledger">Party Sub-Ledger</label>
            <div class="col-md-6">
                <select class="form-control form-control-sm col-md-9" id="ap_party_sub_ledger" required
                        name="ap_party_sub_ledger">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['subsidiary_type'] as $type)
                        <option
                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                    @endforeach
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
                <input required type="text" class="form-control form-control-sm" id="ap_vendor_name"
                       name="ap_vendor_name" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_vendor_category">Party/Vendor Category</label>
            <div class="col-md-10">
                <input required type="text" class="form-control form-control-sm" id="ap_vendor_category"
                       name="ap_vendor_category"
                       readonly>
            </div>
        </div>

        {{--Add this section start Pavel: 21-03-22--}}
        <div class="row text-right mt-1">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_bills_payable">Bills Payable(A)</label>
                    <input type="text" id="bl_bills_payable" class="form-control form-control-sm text-right"
                           name="bl_bills_payable" placeholder="" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_provision_exp">Provision for Exp.</label>
                    <input type="text" id="bl_provision_exp" class="form-control form-control-sm text-right"
                           name="bl_provision_exp" placeholder="" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_security_dep_pay">Security Deposits Payable</label>
                    <input type="text" id="bl_security_dep_pay" class="form-control form-control-sm text-right"
                           name="bl_security_dep_pay" placeholder="" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_os_advances">O/S Advances</label>
                    <input type="text" id="bl_os_advances" class="form-control form-control-sm text-right"
                           name="bl_os_advances" placeholder="" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_os_prepayments">O/S Prepayments</label>
                    <input type="text" id="bl_os_prepayments" class="form-control form-control-sm text-right"
                           name="bl_os_prepayments" placeholder="" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="bl_os_imp_rev">O/S Imprest/Rev. Cash</label>
                    <input type="text" id="bl_os_imp_rev" class="form-control form-control-sm text-right"
                           name="bl_os_imp_rev" placeholder="" readonly>
                </div>
            </div>
        </div>
        {{--Add this section end Pavel: 21-03-22--}}

        <div class="row">
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" disabled type="checkbox" value="1" name="po_based_yn"
                           tabindex="" id="po_based_yn">
                    <label class="form-check-label" for="po_based_yn">
                        PO Based Invoice
                    </label>
                </div>
            </div>
            <div class="col-md-3 po_base_invoice">
                <button type="button" disabled class="btn btn-light-info btn-sm" id="search_po" data-toggle="tooltip"
                        data-placement="bottom" title="Search Purchase Order detail">Good Received Info
                    {{--<i class="bx bx-search"></i>--}}
                </button>
            </div>
            <div class="col-md-4 po_base_invoice">
                <div class="form-group row">
                    <label for="ap_purchase_order_no" class="col-form-label col-md-4">PO No</label>
                    <input type="text" id="ap_purchase_order_no" readonly
                           class="form-control form-control-sm col-md-8"
                           name="ap_purchase_order_no"
                           autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group row po_base_invoice">
            <div class="offset-5 col-md-4">
                <div class="form-group row">
                    <label for="ap_purchase_order_date" class="col-form-label col-md-4">PO Date</label>
                    <input type="text" id="ap_purchase_order_date" readonly
                           class="form-control form-control-sm col-md-8"
                           name="ap_purchase_order_date"
                           autocomplete="off"
                    >
                </div>
            </div>
        </div>
    </fieldset>

    <div class="budget_booking_utilized_div d-none">
        <fieldset class="border pl-1 pr-1">
            <legend class="w-auto" style="font-size: 15px;">{{--Budget Booking/Utilized Info--}}Budget Head Info
            </legend>
            {{--Fiscal year for Budget booking--}}
            {{--<select required name="fiscal_year" hidden
                    class="form-control form-control-sm col-sm-4 required"
                    id="fiscal_year">
                @foreach($fiscalYear as $year)
                    <option value="{{$year->fiscal_year_id}}" selected>{{$year->fiscal_year_name}}</option>
                @endforeach
            </select>--}}
            {{--Fiscal year for Budget booking ends--}}

            {{--<div class="form-group row">
                <label class="col-form-label col-md-2 required" for="b_booking_id">Budget Booking ID</label>
                <div class="input-group col-md-2">
                    <input name="b_booking_id" class="form-control form-control-sm " value="" readonly
                           type="number"
                           id="b_booking_id"
                           maxlength="15"
                           oninput="maxLengthValid(this)"
                        --}}{{--onkeyup="resetBudgetField()"--}}{{-->
                </div>
            </div>--}}

            {{--Add this section start Pavel: 24-03-22--}}
            <div class="form-group row">
                <label class="col-form-label col-md-2 required" for="b_head_id">Budget Head ID</label>
                <div class="input-group col-md-2">
                    <input name="b_head_id" class="form-control form-control-sm " value="" readonly
                           type="number" id="b_head_id">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary bookingIdSearch" id="b_booking_search"
                            type="button"
                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ">Get Budget Booking Info</span>
                    </button>
                </div>
                {{--Add this section end Pavel: 30-03-22--}}
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" name="ap_without_budget_info"
                               id="ap_without_budget_info"
                            {{--@if (!isset($roleWiseUser)) disabled @endif--}}
                            {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AP_MODULE_ID, \App\Enums\WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE,\App\Enums\RolePermissionsKey::CAN_BE_ADD_BUDGET_BOOK_TO_AP_INVOICE_MAKE )) ) ? 'disabled' : '' }}>
                        <label class="form-check-label" for="ap_without_budget_info">Without Budget Booking</label>
                    </div>
                </div>
                {{--Add this section end Pavel: 30-03-22--}}
            </div>

            <div class="form-group row">
                <label for="b_head_name" class=" col-md-2 col-form-label">Budget Head Name</label>
                <div class="input-group col-md-10">
                    <input readonly type="text"
                           class="form-control form-control-sm" name="b_head_name"
                           id="b_head_name"
                           value="">
                </div>
            </div>
            {{--Add this section end Pavel: 24-03-22--}}
            {{--<div class="form-group row">
                <label for="b_head_name" class=" col-md-2 col-form-label">Budget Head Name</label>
                <div class="col-md-10">
                    <input type="text" readonly class="form-control form-control-sm" name="b_head_name"
                           id="b_head_name"
                           value="">
                </div>
            </div>--}}
            <div class="form-group row">
                <label for="b_sub_category" class=" col-md-2 col-form-label">Budget Sub-Category</label>
                <div class="col-md-10">
                    <input type="text" readonly class="form-control form-control-sm" name="b_sub_category"
                           id="b_sub_category"
                           value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="b_category" class=" col-md-2 col-form-label">Budget Category</label>
                <div class="col-md-10">
                    <input type="text" readonly class="form-control form-control-sm" name="b_category"
                           id="b_category"
                           value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="b_type" class=" col-md-2 col-form-label">Budget Type</label>
                <div class="col-md-10">
                    <input type="text" readonly class="form-control form-control-sm" name="b_type"
                           id="b_type"
                           value="">
                </div>
            </div>

            <div class="form-group row">
                {{--Block this section start Pavel: 24-03-22--}}
                {{--<label for="b_date" class="col-md-2 col-form-label">Budget Booking Date</label>
                <div class="col-md-2">
                    <input type="text" readonly class="form-control form-control-sm" name="b_date"
                           id="b_date"
                           value="">
                </div>--}}
                {{--Block this section end Pavel: 24-03-22--}}

                <label for="b_amt" class=" col-md-2 col-form-label">Booked Amount{{--Booking Amt--}}</label>
                <div class="col-md-2">
                    <input type="text" readonly class="form-control form-control-sm text-right" name="b_amt"
                           id="b_amt"
                           value="">
                </div>

                {{--Add this section start Pavel: 24-03-22--}}
                <label for="b_utilized_amt" class=" col-md-2 col-form-label">Utilized Amount</label>
                <div class="col-md-2">
                    <input type="text" readonly class="form-control form-control-sm text-right" name="b_utilized_amt"
                           id="b_utilized_amt"
                           value="">
                </div>
                {{--Add this section end Pavel: 24-03-22--}}

                <label for="b_available_amt" class=" col-md-2 col-form-label">Available Amount</label>
                <div class="col-md-2">
                    <input type="text" readonly class="form-control form-control-sm text-right" name="b_available_amt"
                           id="b_available_amt"
                           value="">
                </div>
            </div>

        </fieldset>
    </div>

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Invoice/Bill Amount</legend>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_payment_currency">Payment Currency</label>
            <div class="col-md-2 ">
                <select required name="ap_payment_currency" class="form-control form-control-sm"
                        id="ap_payment_currency">
                    @foreach($data['currency'] as $cur)
                        <option
                            value="{{ $cur->currency_code }}" {{ ( $cur->currency_code == \App\Enums\Common\Currencies::O_BD) ? "selected" : '' }}>{{ $cur->currency_code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_exchange_rate">Exchange Rate</label>
            <div class="col-md-2">
                <input readonly required value="1" class="form-control form-control-sm" id="ap_exchange_rate"
                       name="ap_exchange_rate" min="0" step="0.01" type="number">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_invoice_amount"></label>
            <div class="col-md-3 text-center">
                <label class="col-form-label" for="">CCY</label>
            </div>
            <div class="col-md-2 text-center">
                <label class="col-form-label" for="">LCY</label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_invoice_amount">Invoice Amount</label>
            <div class="col-md-3">
                {{-- 24-03-2022
                <input required class="form-control form-control-sm text-right-align" id="ap_invoice_amount_ccy"
                       maxlength="17"
                       oninput="maxLengthValid(this)"
                       name="ap_invoice_amount_ccy" min="0" step="0.01" type="number">--}}
                <input required class="form-control form-control-sm text-right-align" id="ap_invoice_amount_ccy"
                       maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       name="ap_invoice_amount_ccy" type="text">

            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_invoice_amount_lcy"
                       name="ap_invoice_amount_lcy">
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="ap_calculate_tax_vat" disabled
                           id="ap_calculate_tax_vat">
                    <label class="form-check-label" for="ap_calculate_tax_vat">
                        Calculate Tax,Vat,Security Deposit
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="ap_amount_word_ccy" class="col-form-label col-md-2">In Words</label>
            <div class="col-md-5">
                    <textarea readonly class="form-control form-control-sm" id="ap_amount_word_ccy"
                              tabindex="-1"></textarea>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Y" name="ap_inclusive_tax_vat" disabled
                           id="ap_inclusive_tax_vat">
                    <label class="form-check-label" for="ap_inclusive_tax_vat">
                        Inclusive Tax,Vat
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 ap_tax_amount_ccy_label" for="ap_tax_amount_ccy">Tax Amount</label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        {{-- 24-03-2022
                        <input type="number" min="0" step="0.1" class="form-control form-control-sm text-right-align"
                               readonly
                               placeholder="%" max="100"
                               id="ap_tax_amount_ccy_percentage" value=""
                               name="ap_tax_amount_ccy_percentage">--}}

                        <input type="text" class="form-control form-control-sm text-right-align"
                               readonly oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               placeholder="%" maxlength="5"
                               id="ap_tax_amount_ccy_percentage" value=""
                               name="ap_tax_amount_ccy_percentage">
                    </div>
                    <div class="col-md-9">
                        {{--<input readonly type="number"
                               min="0" step="0.1"
                               class="form-control form-control-sm text-right-align"
                               id="ap_tax_amount_ccy"
                               name="ap_tax_amount_ccy">--}}

                        {{-- 24-03-2022
                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_tax_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_tax_amount_ccy" min="0" step="0.01" type="number">--}}

                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_tax_amount_ccy"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_tax_amount_ccy" maxlength="17" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_tax_amount_lcy" name="ap_tax_amount_lcy">
            </div>

            <div class="col-md-4">
                <select name="party_name_for_tax" id="party_name_for_tax"
                        class="form-control form-control-sm make-readonly-bg">
                    <option value="">Party Name for Tax Payable</option>
                </select>
            </div>

        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 ap_vat_amount_ccy_label" for="ap_vat_amount_ccy">VAT Amount</label>
            {{--<div class="col-md-1">
                <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                       id="ap_vat_amount_ccy_percentage"
                       name="ap_vat_amount_ccy_percentage">
            </div>
            <div class="col-md-2">
                <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                       id="ap_vat_amount_ccy"
                       name="ap_vat_amount_ccy">
            </div>--}}

            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        {{--<input type="number" min="0" step="0.1" class="form-control form-control-sm text-right-align"
                               readonly
                               placeholder="%"
                               id="ap_vat_amount_ccy_percentage" value=""
                               name="ap_vat_amount_ccy_percentage">--}}

                        <input type="text" class="form-control form-control-sm text-right-align"
                               readonly oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               placeholder="%" maxlength="5"
                               id="ap_vat_amount_ccy_percentage" value=""
                               name="ap_vat_amount_ccy_percentage">
                    </div>
                    <div class="col-md-9">
                        {{--<input readonly class="form-control form-control-sm text-right-align"
                               id="ap_vat_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_vat_amount_ccy" min="0" step="0.01" type="number">--}}

                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_vat_amount_ccy"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_vat_amount_ccy" maxlength="17" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_vat_amount_lcy" name="ap_vat_amount_lcy">
            </div>

            <div class="col-md-4">
                <select name="party_name_for_vat" id="party_name_for_vat"
                        class="form-control form-control-sm make-readonly-bg">
                    <option value="">Party Name for Vat Payable</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_security_deposit_amount_ccy">Security Deposit </label>
            {{--<div class="col-md-1">
                <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                       id="ap_security_deposit_amount_ccy_percentage"
                       name="ap_security_deposit_amount_ccy_percentage">
            </div>
            <div class="col-md-2">
                <input readonly type="number" step="0.1" class="form-control form-control-sm text-right-align"
                       id="ap_security_deposit_amount_ccy"
                       name="ap_security_deposit_amount_ccy">
            </div>--}}

            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        {{--<input type="number" min="0" step="0.1" class="form-control form-control-sm text-right-align"
                               readonly
                               placeholder="%"
                               id="ap_security_deposit_amount_ccy_percentage" value=""
                               name="ap_security_deposit_amount_ccy_percentage">--}}

                        <input type="text" maxlength="5"
                               class="form-control form-control-sm text-right-align"
                               readonly oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               placeholder="%"
                               id="ap_security_deposit_amount_ccy_percentage" value=""
                               name="ap_security_deposit_amount_ccy_percentage">
                    </div>
                    <div class="col-md-9">
                        {{--<input readonly type="number" min="0"
                               step="0.1" class="form-control form-control-sm text-right-align"
                               id="ap_security_deposit_amount_ccy"
                               name="ap_security_deposit_amount_ccy">--}}

                        {{--<input readonly class="form-control form-control-sm text-right-align"
                               id="ap_security_deposit_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_security_deposit_amount_ccy" min="0" step="0.01" type="number">--}}

                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_security_deposit_amount_ccy"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_security_deposit_amount_ccy" maxlength="17" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_security_deposit_amount_lcy"
                       name="ap_security_deposit_amount_lcy">
            </div>
            {{--<div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value=""
                           name="ap_calculate_security_deposit"
                           id="ap_calculate_security_deposit">
                    <label class="form-check-label" for="ap_calculate_security_deposit">
                        Calculate Security Deposit
                    </label>
                </div>
            </div>--}}
        </div>

        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_extra_security_deposit_amount_ccy_percentage">Extra Security
                Deposit </label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        {{--<input type="number" min="0" step="0.1" class="form-control form-control-sm text-right-align"
                               readonly
                               placeholder="%"
                               id="ap_extra_security_deposit_amount_ccy_percentage" value=""
                               name="ap_extra_security_deposit_amount_ccy_percentage">--}}

                        <input type="text" maxlength="5"
                               class="form-control form-control-sm text-right-align"
                               readonly oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               placeholder="%"
                               id="ap_extra_security_deposit_amount_ccy_percentage" value=""
                               name="ap_extra_security_deposit_amount_ccy_percentage">
                    </div>
                    <div class="col-md-9">
                        {{--<input readonly type="number" min="0"
                               step="0.1" class="form-control form-control-sm text-right-align"
                               id="ap_security_deposit_amount_ccy"
                               name="ap_security_deposit_amount_ccy">--}}

                        {{--<input readonly class="form-control form-control-sm text-right-align"
                               id="ap_extra_security_deposit_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_extra_security_deposit_amount_ccy" min="0" step="0.01" type="number">--}}

                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_extra_security_deposit_amount_ccy"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_extra_security_deposit_amount_ccy" maxlength="17" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_extra_security_deposit_amount_lcy"
                       name="ap_extra_security_deposit_amount_lcy">
            </div>
            {{--<div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value=""
                           name="ap_calculate_security_deposit"
                           id="ap_calculate_security_deposit">
                    <label class="form-check-label" for="ap_calculate_security_deposit">
                        Calculate Security Deposit
                    </label>
                </div>
            </div>--}}
        </div>

        <div class="form-group row">
            <label class="col-form-label col-md-2" for="add_account">Additional
                Account (s) </label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-primary additional-account-btn" data-toggle="modal"
                                data-target=".additional-account-modal" disabled
                                id="add_account" type="button">Add
                        </button>
                    </div>
                    <div class="col-md-9">
                        {{--<input readonly type="number" min="0"
                               step="0.1" class="form-control form-control-sm text-right-align"
                               id="ap_security_deposit_amount_ccy"
                               name="ap_security_deposit_amount_ccy">--}}

                        {{--<input readonly class="form-control form-control-sm text-right-align"
                               id="ap_total_add_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_total_add_amount_ccy" min="0" step="0.01" type="number">--}}

                        <input readonly class="form-control form-control-sm text-right-align"
                               id="ap_total_add_amount_ccy"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_total_add_amount_ccy" maxlength="17" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_total_add_amount_lcy"
                       name="ap_total_add_amount_lcy">
            </div>
            {{--<div class="col-md-4">
                <label class="form-check-label mt-1" for="provision_expense">
                    Provision for Expenses
                </label>
            </div>--}}
        </div>
        {{--<div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_fine_forfeiture_ccy">Fine/Forfeiture</label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-9 offset-3">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_fine_forfeiture_ccy"
                               name="ap_fine_forfeiture_ccy">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_fine_forfeiture_lcy"
                       name="ap_fine_forfeiture_lcy">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_preshipment_ccy">Preshipment Inspection (PSI)</label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-9 offset-3">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_preshipment_ccy"
                               name="ap_preshipment_ccy">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_preshipment_lcy"
                       name="ap_preshipment_lcy">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_electricity_bill_ccy">Electricity Bill</label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-9 offset-3">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_electricity_bill_ccy"
                               name="ap_electricity_bill_ccy">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_electricity_bill_lcy"
                       name="ap_electricity_bill_lcy">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_other_charge_ccy">Other/Miscellanies
                Charge --}}{{--Other/ Charge (if any)--}}{{--</label>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-9 offset-3">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_other_charge_ccy"
                               name="ap_other_charge_ccy">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_other_charge_lcy"
                       name="ap_other_charge_lcy">
            </div>
        </div>--}}
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_payable_amount_ccy">Net Payable Amount</label>
            <div class="col-md-3">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_payable_amount_ccy"

                       name="ap_payable_amount_ccy">
            </div>
            <div class="col-md-2">
                <input readonly type="text" class="form-control form-control-sm text-right-align"
                       id="ap_payable_amount_lcy"
                       name="ap_payable_amount_lcy">
            </div>
            {{--<div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="provision_expense" readonly>
            </div>--}}
        </div>
    </fieldset>

    <!-- Additional account modal -->

    {{--    <div class="modal fade additional-account-modal" tabindex="-1" role="dialog" aria-labelledby="addAccModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">Additional Accounts</div>
                    <div class="modal-body">--}}
    <fieldset class="border pl-1 pr-1 additional-account-area d-none">
        <legend class="w-auto" style="font-size: 15px;">Additional Deduction</legend>
        <div class="row">
            <label class="required col-md-2 col-form-label" for="ap_add_account_id">Account ID</label>
            <input name="ap_add_account_id" class="form-control form-control-sm col-md-2" value=""
                   type="number"
                   id="ap_add_account_id" oninput="maxLengthValid(this)"
                   onfocusout="addZerosInAccountId(this)"
                   onkeyup="resetAddAccountField();resetAdditionalSubLedgerPartyFields ();">
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm searchAccount mb-1" id="ap_add_search_account"
                        type="button"
                        tabindex="-1"><i class="bx bx-search font-size-small"></i>
                    <span class="align-middle ">Search</span>
                </button>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button class="btn btn-dark btn-sm mb-1" id="ap_add_cancel_account"
                        type="button"
                        tabindex="-1"><i class="bx bx-x font-size-small"></i>
                    <span class="align-middle ">Remove Additional Accounts</span>
                </button>
            </div>
        </div>

        <div class="form-group row">
            <input name="ap_add_module_id" id="ap_add_module_id" type="hidden">

            <label for="ap_add_account_name" class="col-md-2 col-form-label">Account Name</label>
            <input name="ap_add_account_name" class="form-control form-control-sm col-md-6" value=""
                   id="ap_add_account_name" tabindex="-1"
                   readonly>
            {{--<label class="col-md-2 col-form-label" for="ap_authorized_balance">Authorized Balance</label>
            <input name="ap_authorized_balance" class="form-control form-control-sm text-right-align col-md-2"
                   value=""
                   tabindex="-1"
                   id="ap_authorized_balance" readonly>--}}
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label" for="ap_add_account_type">Account Type</label>
            <input class="form-control form-control-sm col-md-2" id="ap_add_account_type"
                   name="ap_add_account_type"
                   type="text" readonly tabindex="-1">
        </div>

        <div class="row  payableArea hidden mb-1 mt-1`">
            <div class="col-md-12">
                <h6 style="text-decoration: underline">Party Accounts(AP)</h6>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label class=" col-md-3 col-form-label" for="ap_add_party_sub_ledger">Party Sub-Ledger</label>
                            <select class="form-control form-control-sm col-md-9" name="ap_add_party_sub_ledger" id="ap_add_party_sub_ledger">
                                <option value="">&lt;Select&gt;</option>
                                {{--@foreach($apSubsidiaryType as $type)
                                    <option
                                        value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        <div class="row ">
                            <label class=" col-md-3 col-form-label" for="ap_add_vendor_id">Party/Vendor ID</label>
                            <input name="ap_add_vendor_id" class="form-control form-control-sm col-md-3" value="" type="number"
                                   id="ap_add_vendor_id"
                                   maxlength="10"
                                   onfocusout="addZerosInAccountId(this)"
                                   oninput="maxLengthValid(this)"
                                   onkeyup="resetField(['#ap_add_vendor_name','#ap_add_vendor_category','#ap_add_account_balance','#ap_add_authorized_balance']);">
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary vendorIdAddSearch" id="ap_add_vendor_search" type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label for="ap_add_vendor_name" class="col-md-3 col-form-label">Party/Vendor Name</label>
                            <input name="ap_add_vendor_name" class="form-control form-control-sm col-md-9" value=""
                                   id="ap_add_vendor_name"
                                   readonly
                                   tabindex="-1">
                        </div>
                        <div class="row ">
                            <label for="ap_add_vendor_category" class="col-md-3 col-form-label">Party/Vendor Category</label>
                            <input name="ap_add_vendor_category" class="form-control form-control-sm col-md-9" value=""
                                   id="ap_add_vendor_category"
                                   readonly
                                   tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row ">
                            <label class="col-form-label col-md-5" for="ap_add_account_balance">Account Balance</label>
                            <input class="form-control form-control-sm text-right-align col-md-6 mr-1"
                                   id="ap_add_account_balance"
                                   tabindex="-1"
                                   name="ap_add_account_balance"
                                   type="text" readonly>
                        </div>
                        <div class="row ">
                            <label class="col-md-5 col-form-label" for="ap_add_authorized_balance">Authorized Balance</label>
                            <input name="ap_add_authorized_balance"
                                   class="form-control form-control-sm text-right-align col-md-6"
                                   value="" tabindex="-1"
                                   id="ap_add_authorized_balance" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row  receivableArea hidden mb-1 mt-1`">
            <div class="col-md-12">
                <h6 style="text-decoration: underline">Party Accounts(AR)</h6>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label class=" col-md-3 col-form-label" for="ar_add_party_sub_ledger">Party Sub-Ledger</label>
                            <select class="form-control form-control-sm col-md-9" name="ap_add_party_sub_ledger" id="ar_add_party_sub_ledger">
                                <option value="">&lt;Select&gt;</option>
                                {{--@foreach($apSubsidiaryType as $type)
                                    <option
                                        value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        <div class="row ">
                            <label class=" col-md-3 col-form-label" for="ar_add_customer_id">Party/Customer ID</label>
                            <input name="ap_add_vendor_id" class="form-control form-control-sm col-md-3" value="" type="number"
                                   id="ar_add_customer_id"
                                   maxlength="10"
                                   onfocusout="addZerosInAccountId(this)"
                                   oninput="maxLengthValid(this)"
                                   onkeyup="resetField(['#ar_add_vendor_name','#ar_add_vendor_category','#ar_add_account_balance','#ar_add_authorized_balance']);">
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary customerIdAddSearch" id="ar_customer_search" type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label for="ar_add_customer_name" class="col-md-3 col-form-label">Party/Customer Name</label>
                            <input name="ap_add_vendor_name" class="form-control form-control-sm col-md-9" value=""
                                   id="ar_add_customer_name"
                                   readonly
                                   tabindex="-1">
                        </div>
                        <div class="row ">
                            <label for="ar_add_customer_category" class="col-md-3 col-form-label">Party/Customer Category</label>
                            <input name="ap_add_vendor_category" class="form-control form-control-sm col-md-9" value=""
                                   id="ar_add_customer_category"
                                   readonly
                                   tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row ">
                            <label class="col-form-label col-md-5" for="ar_add_account_balance">Account Balance</label>
                            <input class="form-control form-control-sm text-right-align col-md-6 mr-1"
                                   id="ar_add_account_balance"
                                   tabindex="-1"
                                   name="ap_add_account_balance"
                                   type="text" readonly>
                        </div>
                        <div class="row ">
                            <label class="col-md-5 col-form-label" for="ar_add_authorized_balance">Authorized Balance</label>
                            <input name="ap_add_authorized_balance"
                                   class="form-control form-control-sm text-right-align col-md-6"
                                   value="" tabindex="-1"
                                   id="ar_add_authorized_balance" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group row">
            {{--<label class="col-md-2 col-form-label" for="ap_currency">Currency</label>
            <div class="col-md-6">
                <div class="form-group row">
                    <input class="form-control form-control-sm col-md-2" id="ap_currency" name="ap_currency"
                           type="text" readonly
                           tabindex="-1">
                    <div class="col-md"></div>--}}
            <label class="required col-md-2 col-form-label" for="ap_add_amount_ccy">Amount CCY</label>
            <input class="required form-control form-control-sm col-md-2 text-right-align"
                   id="ap_add_amount_ccy"
                   maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                   name="ap_add_amount_ccy"
                   type="text">
            {{--</div>
        </div>--}}
        </div>

        <div class="form-group row">
            {{--<label class="col-md-2 col-form-label" for="ap_acc_exchange_rate">Exchange Rate</label>
            <div class="col-md-6">
                <div class="form-group row">
                    <input class="form-control form-control-sm col-md-2" id="ap_acc_exchange_rate"
                           name="ap_acc_exchange_rate"
                           type="text"
                           readonly tabindex="-1">
                    <div class="col-md"></div>--}}
            <label class="required col-md-2 col-form-label" for="ap_add_amount_lcy">Amount LCY</label>
            <input class="required form-control form-control-sm col-md-2 text-right-align"
                   id="ap_add_amount_lcy"
                   name="ap_add_amount_lcy"
                   type="text" readonly tabindex="-1">
            {{-- </div>
         </div>--}}
            <div class="col-md-2">
                <button class="btn btn-info btn-sm mb-1" type="button" tabindex="-1"
                        onclick="addAddAccLineRow(this)"
                        data-type="A"
                        data-line="" id="addAddAccNewLineBtn"><i
                        class="bx bx-plus-circle font-size-small"></i>
                    <span class="align-middle">ADD</span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label for="ap_add_amount_word">In Words</label>
                <textarea readonly class="form-control form-control-sm" id="ap_add_amount_word"
                          tabindex="-1"></textarea>
            </div>
        </div>
        {{--<div class="form-group row">
            <label for="c_narration" class="required col-md-2 col-form-label">Narration</label>
            <textarea name="c_narration" class="required form-control form-control-sm col-md-6 " id="c_narration"></textarea>
            <div class="col-md-2">
                <button class="btn btn-info " type="button" tabindex="-1" onclick="addLineRow(this)" data-type="A"
                        data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle"></i>ADD
                </button>
            </div>
        </div>--}}
        <div class="row mt-1">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-hover table-bordered " id="ap_add_account_table">
                    <thead class="thead-dark">
                    <tr>
                        <th width="12%" class="text-center">Account Code</th>
                        <th width="20%" class="text-center">Account Name</th>
                        {{--<th width="5%" class="text-center">Dr/Cr</th>--}}
                        <th width="12%" class="text-center">Party ID</th>
                        <th width="20%" class="text-center">Party Name</th>
                        <th width="16%" class="text-center">Amount CCY</th>
                        <th width="16%" class="text-center">Amount LCY</th>
                        <th width="5%" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    {{--<tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right-align">Total Amount</td>
                        <td><input type="text" name="total_lcy" id="add_total_lcy"
                                   class="form-control form-control-sm text-right-align"
                                   readonly tabindex="-1"/></td>
                        <td></td>
                    </tr>
                    </tfoot>--}}
                </table>
            </div>
        </div>
    </fieldset>
    {{--</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>--}}

    <fieldset class="col-md-12 border pl-1 pr-1">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Payment Options</legend>
        <div class="row">
            <label class="col-form-label col-md-2" style="text-decoration: underline" for="">Payment Conditions</label>

            <div class="form-group col-md-3">
                <label class="col-form-label required" for="ap_payment_method">Payment
                    Method</label>
                <select required name="ap_payment_method" class="form-control form-control-sm select2"
                        id="ap_payment_method">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($paymentMethod as $value)
                        <option
                            value="{{$value->payment_method_id}}" {{ ( $value->payment_method_id == \App\Enums\Ap\LApPayMethods::CHEQUE) ? "selected" : '' }}>{{ $value->payment_method_name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label class="col-form-label required" for="ap_payment_terms">Payment Terms</label>
                <select required name="ap_payment_terms" class="form-control form-control-sm select2"
                        id="ap_payment_terms">
                    @foreach($paymentTerms as $value)
                        <option value="{{$value->payment_term_id}}"
                                data-termdate="{{$value->payment_term_days}}">{{ $value->payment_term_name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="ap_payment_due_date" class="required col-form-label">Payment Due Date</label>
                <div class="input-group date ap_payment_due_date"
                     id="ap_payment_due_date"
                     data-target-input="nearest">
                    <input required type="text" autocomplete="off" onkeydown="return false" readonly
                           name="ap_payment_due_date"
                           id="ap_payment_due_date_field"
                           class="form-control form-control-sm"
                           data-target="#ap_payment_due_date"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append" data-target="#ap_payment_due_date">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_hold_all_payment"></label>
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1"
                           name="ap_hold_all_payment"
                           tabindex="-1"
                           {{ old('ap_hold_all_payment', isset($data['insertedData']) ? $data['insertedData']->ded_at_source_allow_flag : '' ) == '1' ? 'Checked' : '' }}
                           id="ap_hold_all_payment">
                    <label class="form-check-label" for="ap_hold_all_payment">
                        Payment Hold
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_hold_all_payment_reason">Payment Hold Reason</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm " id="ap_hold_all_payment_reason"
                       name="ap_hold_all_payment_reason" readonly>
            </div>
        </div>
    </fieldset>

    {{--Add this section start Pavel: 23-03-22--}}
    <fieldset class="swt_pay_party_vendor_div d-none border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Switch Payment to Party/Vendor (Contra & Supplier For Provision
            Adjustment)
        </legend>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_id">Party/Vendor ID</label>
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="input-group col-md-5">
                        <input name="ap_switch_pay_vendor_id" class="form-control form-control-sm " value=""
                               type="number"
                               id="ap_switch_pay_vendor_id"
                               maxlength="10"
                               oninput="maxLengthValid(this)"
                               onfocusout="addZerosInAccountId(this)"
                               onkeyup="resetField(['#ap_switch_pay_vendor_name','#ap_switch_pay_vendor_category']);">
                    </div>
                    <div class="col-md-5 pl-0">
                        <button class="btn btn-sm btn-primary " id="ap_switch_pay_vendor_search" type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                class="align-middle ml-25">Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_name">Party/Vendor Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" id="ap_switch_pay_vendor_name"
                       name="ap_switch_pay_vendor_name" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_switch_pay_vendor_category">Party/Vendor Category</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" id="ap_switch_pay_vendor_category"
                       name="ap_switch_pay_vendor_category"
                       readonly>
            </div>
        </div>
    </fieldset>
    {{--Add this section end Pavel: 23-03-22--}}

{{--
    <div class="distribution_line_div d-none">
--}}
    <div class="distribution_line_div ">
        <input type="hidden" name="ap_distribution_flag" value="1" id="ap_distribution_flag">
        <fieldset class=" border p-2">
            <legend class="w-auto" style="font-size: 15px;">Distribution Line (Capital/Revenue Expenditure)</legend>
            <div class="form-group row">
                <label class="required col-md-2 col-form-label" for="ap_account_id">Account ID</label>
                <div class="form-group row col-md-6 pl-0 mr-1">
                    <div class="input-group col-md-5">
                        <input readonly name="ap_account_id" class="form-control form-control-sm" value="" type="number"
                               id="ap_account_id" oninput="maxLengthValid(this)"
                               onfocusout="addZerosInAccountId(this)"
                               onkeyup="resetAccountField()">
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-sm searchAccount mb-1" id="ap_search_account" type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small"></i>
                            <span class="align-middle ">Search</span>
                        </button>
                    </div>
                </div>
                <input name="ap_dist_module_id" id="ap_dist_module_id" type="hidden">

                <label class="col-md-2 col-form-label" for="ap_account_balance">Account Balance</label>
                <input class="form-control form-control-sm col-md-2 text-right-align" id="ap_account_balance"
                       tabindex="-1"
                       name="ap_account_balance"
                       type="text" readonly>
            </div>

            <div class="form-group row">
                <label for="ap_account_name" class="col-md-2 col-form-label">Account Name</label>
                <input name="ap_account_name" class="form-control form-control-sm col-md-6" value=""
                       id="ap_account_name" tabindex="-1"
                       readonly>
                <label class="col-md-2 col-form-label" for="ap_authorized_balance">Authorized Balance</label>
                <input name="ap_authorized_balance" class="form-control form-control-sm text-right-align col-md-2"
                       value=""
                       tabindex="-1"
                       id="ap_authorized_balance" readonly>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ap_account_type">Account Type</label>
                <div class="col-md-6">
                    <div class="form-group row mb-0">
                        <input class="form-control form-control-sm col-md-4" id="ap_account_type" name="ap_account_type"
                               type="text" readonly tabindex="-1">
                        {{--<label class="col-md-4 col-form-label" for="c_account_balance">Account Balance</label>
                        <input class="form-control form-control-sm col-md-4" id="c_account_balance" name="c_account_balance"
                               type="text" readonly tabindex="-1">--}}
                    </div>
                </div>
            </div>
            {{--Additional party for distribution line.--}}

            <div class="row distVendorArea hidden  mb-1 mt-1">
                <div class="col-md-12">
                    <h6 style="text-decoration: underline">Party Accounts(AP)</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row ">
                                <label class=" col-md-3 col-form-label" for="ap_dist_party_sub_ledger">Party Sub-Ledger</label>
                                <select class="form-control form-control-sm col-md-9" name="ap_dist_party_sub_ledger" id="ap_dist_party_sub_ledger">
                                    <option value="">&lt;Select&gt;</option>
                                    {{--@foreach($apSubsidiaryType as $type)
                                        <option
                                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                    @endforeach--}}
                                </select>
                            </div>
                            <div class="row ">
                                <label class=" col-md-3 col-form-label" for="ap_dist_vendor_id">Party/Vendor ID</label>
                                <input name="ap_dist_vendor_id" class="form-control form-control-sm col-md-3" value="" type="number"
                                       id="ap_dist_vendor_id"
                                       maxlength="10"
                                       onfocusout="addZerosInAccountId(this)"
                                       oninput="maxLengthValid(this)"
                                       onkeyup="resetField(['#ap_dist_vendor_name','#ap_dist_vendor_category','#ap_dist_account_balance','#ap_dist_authorized_balance']);">
                                <div class="col-md-3">
                                    <button class="btn btn-sm btn-primary vendorIdDistSearch" id="ap_dist_vendor_search" type="button"
                                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row ">
                                <label for="ap_dist_vendor_name" class="col-md-3 col-form-label">Party/Vendor Name</label>
                                <input name="ap_dist_vendor_name" class="form-control form-control-sm col-md-9" value=""
                                       id="ap_dist_vendor_name"
                                       readonly
                                       tabindex="-1">
                            </div>
                            <div class="row ">
                                <label for="ap_dist_vendor_category" class="col-md-3 col-form-label">Party/Vendor Category</label>
                                <input name="ap_dist_vendor_category" class="form-control form-control-sm col-md-9" value=""
                                       id="ap_dist_vendor_category"
                                       readonly
                                       tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row ">
                                <label class="col-form-label col-md-5" for="ap_dist_account_balance">Account Balance</label>
                                <input class="form-control form-control-sm text-right-align col-md-6 mr-1"
                                       id="ap_dist_account_balance"
                                       tabindex="-1"
                                       name="ap_dist_account_balance"
                                       type="text" readonly>
                            </div>
                            <div class="row ">
                                <label class="col-md-5 col-form-label" for="ap_dist_authorized_balance">Authorized Balance</label>
                                <input name="ap_dist_authorized_balance"
                                       class="form-control form-control-sm text-right-align col-md-6"
                                       value="" tabindex="-1"
                                       id="ap_dist_authorized_balance" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row distCustomerArea hidden mb-1 mt-1`">
                <div class="col-md-12">
                    <h6 style="text-decoration: underline">Party Accounts(AR)</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row ">
                                <label class=" col-md-3 col-form-label" for="ar_dist_party_sub_ledger">Party Sub-Ledger</label>
                                <select class="form-control form-control-sm col-md-9" name="ap_dist_party_sub_ledger" id="ar_dist_party_sub_ledger">
                                    <option value="">&lt;Select&gt;</option>
                                    {{--@foreach($apSubsidiaryType as $type)
                                        <option
                                            value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                    @endforeach--}}
                                </select>
                            </div>
                            <div class="row ">
                                <label class=" col-md-3 col-form-label" for="ar_dist_customer_id">Party/Customer ID</label>
                                <input name="ap_dist_vendor_id" class="form-control form-control-sm col-md-3" value="" type="number"
                                       id="ar_dist_customer_id"
                                       maxlength="10"
                                       onfocusout="addZerosInAccountId(this)"
                                       oninput="maxLengthValid(this)"
                                       onkeyup="resetField(['#ar_dist_customer_name','#ar_dist_customer_category','#ar_dist_account_balance','#ar_dist_authorized_balance']);">
                                <div class="col-md-3">
                                    <button class="btn btn-sm btn-primary customerIdistSearch" id="ar_dist_customer_search" type="button"
                                            tabindex="-1"><i class="bx bx-search font-size-small"></i><span class="align-middle ml-25">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row ">
                                <label for="ar_dist_customer_name" class="col-md-3 col-form-label">Party/Customer Name</label>
                                <input name="ap_dist_vendor_name" class="form-control form-control-sm col-md-9" value=""
                                       id="ar_dist_customer_name"
                                       readonly
                                       tabindex="-1">
                            </div>
                            <div class="row ">
                                <label for="ar_dist_customer_category" class="col-md-3 col-form-label">Party/Customer Category</label>
                                <input name="ap_dist_vendor_category" class="form-control form-control-sm col-md-9" value=""
                                       id="ar_dist_customer_category"
                                       readonly
                                       tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row ">
                                <label class="col-form-label col-md-5" for="ar_add_account_balance">Account Balance</label>
                                <input class="form-control form-control-sm text-right-align col-md-6 mr-1"
                                       id="ar_dist_account_balance"
                                       tabindex="-1"
                                       name="ap_dist_account_balance"
                                       type="text" readonly>
                            </div>
                            <div class="row ">
                                <label class="col-md-5 col-form-label" for="ar_dist_authorized_balance">Authorized Balance</label>
                                <input name="ap_dist_authorized_balance"
                                       class="form-control form-control-sm text-right-align col-md-6"
                                       value="" tabindex="-1"
                                       id="ar_dist_authorized_balance" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row d-none">
                <label for="ap_budget_head" class="col-md-2 col-form-label">Budget Head</label>
                <div class="col-md-6 pl-0 pr-0">
                    <input name="ap_budget_head" class="form-control form-control-sm" value="" id="ap_budget_head"
                           type="text"
                           readonly tabindex="-1">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ap_currency">Currency</label>
                <div class="col-md-6">
                    <div class="form-group row">
                        <input class="form-control form-control-sm col-md-2" id="ap_currency" name="ap_currency"
                               type="text" readonly
                               tabindex="-1">
                        <div class="col-md"></div>
                        <label class="required col-md-4 col-form-label" for="ap_amount_ccy">Amount CCY</label>
                        {{--<input readonly class="required form-control form-control-sm col-md-4 text-right-align"
                               id="ap_amount_ccy"
                               oninput="maxLengthValid(this)"
                               name="ap_amount_ccy" min="0" step="0.01"
                               type="number">--}}
                        <input readonly class="required form-control form-control-sm col-md-4 text-right-align"
                               id="ap_amount_ccy"
                               maxlength="17"
                               oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               name="ap_amount_ccy"
                               type="text">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="ap_acc_exchange_rate">Exchange Rate</label>
                <div class="col-md-6">
                    <div class="form-group row">
                        <input class="form-control form-control-sm col-md-2" id="ap_acc_exchange_rate"
                               name="ap_acc_exchange_rate"
                               type="text"
                               readonly tabindex="-1">
                        <div class="col-md"></div>
                        <label class="required col-md-4 col-form-label" for="ap_amount_lcy">Amount LCY</label>
                        <input class="required form-control form-control-sm col-md-4 text-right-align"
                               id="ap_amount_lcy"
                               name="ap_amount_lcy"
                               type="text" readonly tabindex="-1">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-info btn-sm mb-1" type="button" tabindex="-1" onclick="addLineRow(this)"
                            data-type="A"
                            data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle font-size-small"></i>
                        <span class="align-middle">ADD</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="ap_amount_word">In Words</label>
                    <textarea readonly class="form-control form-control-sm" id="ap_amount_word"
                              tabindex="-1"></textarea>
                </div>
            </div>
            {{--<div class="form-group row">
                <label for="c_narration" class="required col-md-2 col-form-label">Narration</label>
                <textarea name="c_narration" class="required form-control form-control-sm col-md-6 " id="c_narration"></textarea>
                <div class="col-md-2">
                    <button class="btn btn-info " type="button" tabindex="-1" onclick="addLineRow(this)" data-type="A"
                            data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle"></i>ADD
                    </button>
                </div>
            </div>--}}
            <div class="row mt-1">
                <div class="col-md-12 table-responsive">
                    <table class="table table-sm table-hover table-bordered " id="ap_account_table">
                        <thead class="thead-dark">
                        <tr>
                            <th width="12%" class="text-center">Account Code</th>
                            <th width="28%" class="text-center">Account Name</th>
                            {{--<th width="5%" class="text-center">Dr/Cr</th>--}}
                            <th width="16%" class="text-center">Party ID</th>
                            <th width="16%" class="text-center">Party Name</th>
                            <th width="16%" class="text-center">Amount CCY</th>
                            <th width="16%" class="text-center">Amount LCY</th>
                            <th width="5%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            {{--<td></td>--}}
                            <td class="text-right-align">Total Amount</td>
                            <td><input type="text" name="total_lcy" id="total_lcy"
                                       class="form-control form-control-sm text-right-align"
                                       readonly tabindex="-1"/></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>

    @include('gl.common_file_upload')

    <div class="row mt-1">
        <div class="col-md-12 d-flex justify-content-start">
            <button type="submit" class="btn btn-sm btn-success mr-1" id="invoice_bill_entry_form_submit_btn" disabled>
                <i
                    class="bx bx-save font-size-small align-top"></i><span class="align-middle m-25">Save</span>
            </button>
            <button type="button" class="btn btn-sm btn-dark" id="reset_form">
                <i class="bx bx-reset font-size-small align-top"></i><span class="align-middle ml-25">Reset</span>
            </button>
            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>
            <h6 class="text-primary ml-2">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{(isset($lastPostingBatch->last_posting_batch_id) ? $lastPostingBatch->last_posting_batch_id : '0')}}</span>
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

<fieldset class="border p-2 mt-2">
    <legend class="w-auto text-bold-600" style="font-size: 16px;">Detail Reference</legend>
    <div class="row">
        <div class="col-md-6">
            <h6 class="mb-1"><span
                    class="border-bottom-secondary border-bottom-1 text-bold-400">Transaction References</span>
                <span style="margin-left: 8.5%">
                <input class="form-check-input" type="checkbox" value="" id="chnTransRef"
                    {{--@if (!isset($roleWiseUser)) disabled @endif--}}
                    {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AR_MODULE_ID,\App\Enums\WorkFlowRoleKey::AR_INVOICE_BILL_RECEIPT_MAKE,\App\Enums\RolePermissionsKey::CAN_EDIT_AR_RECEIPT_MAKE )) ) ? 'disabled' : '' }}
                >
                <label class="form-check-label font-small-3" for="chnTransRef">
                    Change Trans Reference
                </label>
            </span>
            </h6>
            {{--<div class="row">
                <div class="col-md-5"><label for="batch_id" class="">Batch ID</label></div>
                <div class="col-md-7 form-group pl-0">
                    <input type="text" class="form-control form-control-sm"
                           value="{{isset($invBillReceiptInfo->batch_id) ? $invBillReceiptInfo->batch_id : ''}}"
                           disabled/>
                </div>
            </div>--}}
            <div class="viewDocumentRef">
                <div class="form-group row">
                    <label for="posting_date_field" class="required col-md-4 col-form-label ">Batch
                        ID</label>
                    <div class="col-md-5">
                        <input type="text" readonly tabindex="-1"
                               class="form-control form-control-sm"
                               value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->batch_id : '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal
                        Year</label>
                    <div class="col-md-5">
                        <select required name="th_fiscal_year"
                                class="form-control form-control-sm required make-readonly-bg"
                                id="th_fiscal_year">
                            <option
                                value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->fiscal_year_id : '' }}">{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->fiscal_year_name : '' }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="period" class="required col-md-4 col-form-label">Posting
                        Period</label>
                    <div class="col-md-5">
                        <select required name="period"
                                class="form-control form-control-sm make-readonly-bg" id="period">
                            <option
                                data-mindate="{{ isset($invBillReceiptInfo) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->posting_period_beg_date) : '' }}"
                                data-maxdate="{{ isset($invBillReceiptInfo) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->posting_period_end_date) : '' }}"
                                value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->trans_period_id : '' }}">{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->trans_period_name : '' }}</option>

                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                        Date</label>
                    <div class="col-md-5">
                        <input type="text" readonly class="form-control form-control-sm"
                               id="posting_date"
                               value="{{\App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date)}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="document_date_field" class=" col-md-4 col-form-label">Document
                        Date</label>
                    <div class="col-md-5">
                        <input type="text" readonly class="form-control form-control-sm"
                               id="document_date"
                               value="{{\App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->document_date)}}">
                    </div>
                </div>
            </div>
            <div class="editDocumentRef d-none">
                <input type="hidden" name="receipt_id" id="receipt_id"
                       value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->receipt_id : '' }}">
                <div class="row">
                    <div class="col-md-4"><label for="edit_batch_id" class="">Batch ID </label></div>
                    <div class="col-md-5 form-group">
                        <input type="text" class="form-control form-control-sm" name="edit_batch_id"
                               id="edit_batch_id"
                               value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->batch_id : '' }}"
                               disabled/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_fiscal_year" class="required col-sm-4 col-form-label">Fiscal
                        Year</label>
                    <div class="col-md-5">
                        <select required name="edit_fiscal_year"
                                class="form-control form-control-sm required"
                                id="edit_fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option
                                    {{isset($invBillReceiptInfo) ? ($invBillReceiptInfo->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '' : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="period" class="required col-md-4 col-form-label">Posting
                        Period</label>
                    <div class="col-md-5">
                        <select required name="edit_period" class="form-control form-control-sm"
                                id="edit_period">
                            <option
                                data-mindate="{{ isset($invBillReceiptInfo) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->posting_period_beg_date) : '' }}"
                                data-maxdate="{{ isset($invBillReceiptInfo) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->posting_period_end_date) : '' }}"
                                value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->trans_period_id : '' }}">{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->trans_period_name : '' }}</option>
                        </select>
                    </div>
                </div>

                {{--<div class="row d-none">
                    <label for="period" class="required col-md-4 col-form-label">Posting
                        Period</label>
                    <div class="col-md-5">
                        <select required name="edit_period" class="form-control form-control-sm"
                                id="period">
                            --}}{{--<optin value="">Select a period</option>--}}{{--

                        </select>
                    </div>
                </div>--}}
                <div class="form-group row">
                    <label for="edit_posting_date_field" class="required col-md-4 col-form-label ">Posting
                        Date</label>
                    <div class="input-group date posting_date col-md-5"
                         id="edit_posting_date"
                         data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="edit_posting_date"
                               id="edit_posting_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#edit_posting_date"
                               data-toggle="datetimepicker"
                               {{--value="{{ old('edit_posting_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date)) }}"
                               data-predefined-date="{{ old('edit_posting_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date)) }}"--}}

                               value="{{ old('edit_posting_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date)) }}"
                               data-predefined-date="{{ old('edit_posting_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date)) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append edit_posting_date"
                             data-target="#edit_posting_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_document_date_field" class="required col-md-4 col-form-label">Document
                        Date</label>
                    <div class="input-group date document_date col-md-5"
                         id="edit_document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="edit_document_date"
                               id="edit_document_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#edit_document_date"
                               data-toggle="datetimepicker"
                               value="{{ old('edit_document_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->document_date)) }}"
                               data-predefined-date="{{ old('edit_document_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->document_date)) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append edit_document_date"
                             data-target="#edit_document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="form-group row">
                    <label for="edit_document_number" class="required col-md-4 col-form-label">Document
                        No</label>
                    <div class="col-md-5">
                        <input maxlength="50" type="text" required
                               class="form-control form-control-sm"
                               oninput="this.value = this.value.toUpperCase()"
                               name="edit_document_number"
                               id="edit_document_number"
                               value="{{$invBillReceiptInfo->document_no}}">
                    </div>

                </div>--}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="row  mb-25">
                <div class="col-md-12 d-flex justify-content-end">
                    <a target="_blank" class="btn btn-sm btn-info cursor-pointer"
                       href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$invBillReceiptInfo->trans_period_id}}&p_trans_batch_id={{$invBillReceiptInfo->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                        <i class="bx bx-printer"></i>Print Voucher
                    </a>
                </div>
            </div>
            {{--<div class="form-group row d-flex justify-content-end">
                --}}{{--<button type="button" class="btn btn-sm btn-outline-dark col-form-label mr-1">Print Voucher</button>--}}{{--
            </div>--}}
            <div class="viewDocumentRef">
                <div class="form-group row justify-content-end">
                    <label for="department" class="col-form-label col-md-4 required ">Dept/Cost
                        Center</label>
                    <div class="col-md-5 pl-0">
                        <input type="text" readonly class="form-control form-control-sm"
                               name="department" id="department"
                               value="{{$invBillReceiptInfo->cost_center_dept_name}}">
                    </div>
                </div>
                {{--<div class="form-group row justify-content-end">
                    <label for="budget_department" class="col-form-label col-md-4 required ">Budget
                        Department</label>
                    <div class="col-md-5 pl-0">
                        <input type="text" readonly class="form-control form-control-sm"
                               name="budget_department" id="budget_department"
                               value="{{$invBillReceiptInfo->budget_dept_name}}">
                    </div>
                </div>--}}
                <div class="form-group row  justify-content-end">
                    <label for="bill_section" class="required col-md-4 col-form-label">Bill
                        Section</label>
                    <div class="col-md-5 pl-0">
                        <input type="text" readonly class="form-control form-control-sm"
                               name="bill_section"
                               id="bill_section"
                               value="{{$invBillReceiptInfo->bill_sec_name}}">
                        {{--<select required name="bill_section" class="form-control form-control-sm select2"
                                id="bill_section">
                            <option value="">Select a bill</option>
                            @foreach($billSecs as $value)
                                <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>--}}
                    </div>
                </div>
                <div class="form-group row  justify-content-end">
                    <label for="bill_register" class="required col-md-4 col-form-label">Bill
                        Register</label>
                    <div class="col-md-5 pl-0">
                        <input type="text" readonly class="form-control form-control-sm"
                               name="bill_register"
                               id="bill_register"
                               value="{{$invBillReceiptInfo->bill_reg_name}}">
                        {{--<select required name="bill_register" class="form-control form-control-sm select2"
                                id="bill_register">
                        </select>--}}
                    </div>
                </div>

            </div>

            <div class="editDocumentRef d-none">
                <div class="form-group row justify-content-end">
                    <label for="edit_department" class="col-form-label col-md-4 required ">Dept/Cost
                        Center</label>
                    <div class="col-md-5 pl-0 make-select2-readonly-bg">
                        <select required name="edit_department" style="width: 100%"
                                class="form-control form-control-sm select2" id="edit_department">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($department as $dpt)
                                <option
                                    {{  old('department', $dpt->cost_center_dept_id) ==  $invBillReceiptInfo->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{--<div class="form-group row justify-content-end">
                    <label for="edit_budget_department" class="col-form-label col-md-4 required ">Budget
                        Department</label>
                    <div class="col-md-5 pl-0 make-select2-readonly-bg">
                        --}}{{--<input type="text" readonly class="form-control form-control-sm"
                               name="edit_budget_department" id="edit_budget_department"
                               value="{{$invBillReceiptInfo->budget_dept_name}}">--}}{{--
                        <select required name="edit_budget_department"
                                class="form-control form-control-sm select2 "
                                id="edit_budget_department">
                            <option value="">Select Budget Department</option>
                            @foreach($department as $dpt)
                                <option
                                    {{  old('budget_department',$invBillReceiptInfo->budget_dept_id) ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}
                <div class="form-group row d-flex justify-content-end">
                    <label for="edit_bill_section" class="required col-md-4 col-form-label">Bill
                        Section</label>
                    <div class="col-md-5 pl-0">
                        <select readonly="" name="edit_bill_section"
                                class="form-control form-control-sm select2"
                                id="edit_bill_section">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($billSecs as $value)
                                <option
                                    {{  $invBillReceiptInfo->bill_sec_id ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row justify-content-end">
                    <label for="edit_bill_register" class="required col-md-4 col-form-label">Bill
                        Register</label>
                    <div class="col-md-5 pl-0">
                        <select  name="edit_bill_register"
                                class="form-control form-control-sm select2"
                                data-bill-register-id="{{$invBillReceiptInfo->bill_reg_id}}"
                                id="edit_bill_register">
                            {{--<option
                                value="{{ $invBillReceiptInfo->bill_reg_id }}">{{ $invBillReceiptInfo->bill_reg_name }}</option>--}}
                        </select>
                    </div>
                </div>
                {{--<div class="form-group row justify-content-end">
                    <label for="edit_document_reference" class="col-md-4 col-form-label">Document
                        Ref</label>
                    <div class="col-md-5 pl-0">
                        <input maxlength="200" type="text" class="form-control form-control-sm"
                               id="edit_document_reference"
                               name="edit_document_reference"
                               value="{{$invBillReceiptInfo->document_ref}}">
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
    <div class="editDocumentRef d-none">
        <div class="form-group row">
            <label for="edit_document_number" class="required col-md-2 col-form-label">Document
                No</label>
            <div class="col-md-3">
                <input maxlength="50" type="text" required
                       class="form-control form-control-sm"
                       oninput="this.value = this.value.toUpperCase()"
                       name="edit_document_number"
                       id="edit_document_number"
                       value="{{$invBillReceiptInfo->document_no}}">
            </div>

            <label for="edit_document_reference" class="col-md-2 col-form-label text-right">Document
                Ref</label>
            <div class="col-md-5 pl-0">
                <input maxlength="200" type="text" class="form-control form-control-sm"
                       id="edit_document_reference"
                       name="edit_document_reference"
                       value="{{$invBillReceiptInfo->document_ref}}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2">
                <label for="narration" class="required col-form-label">Narration</label>
            </div>
            <div class="col-md-10">
                            <textarea maxlength="500" name="edit_narration"
                                      class="required form-control form-control-sm"
                                      id="edit_narration">{{$invBillReceiptInfo->narration}}</textarea>
                {{--<button type="button" disabled class="btn btn-sm btn-light mt-1" id="updateTrans">
                    Update Changes
                </button>--}}
            </div>
        </div>
    </div>

    <div class="viewDocumentRef">
        <div class="form-group row">
            <label for="document_number" class=" col-md-2 col-form-label">Document
                No</label>
            <div class="col-md-3">
                <input maxlength="25" type="text" readonly
                       class="form-control form-control-sm"
                       name="document_number"
                       id="document_number"
                       value="{{$invBillReceiptInfo->document_no}}">
            </div>

            <label for="document_reference" class="col-md-2 col-form-label text-right-align">Document
                Ref</label>
            <div class="col-md-5 d-flex">
                <input maxlength="25" readonly type="text"
                       class="form-control form-control-sm justify-content-end"
                       id="document_reference"
                       name="document_reference"
                       value="{{$invBillReceiptInfo->document_ref}}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2">
                <label for="narration" class="required col-form-label">Narration</label>
            </div>
            <div class="col-md-10">
                            <textarea maxlength="500" name="narration"
                                      class="required form-control form-control-sm" readonly
                                      id="narration">{{$invBillReceiptInfo->narration}}</textarea>
                {{--<button type="button" disabled class="btn btn-sm btn-light mt-1" id="updateTrans">
                    Update Changes
                </button>--}}
            </div>
        </div>
    </div>


    {{--<div class="row">
        <div class="col-md-10 offset-2"  style="padding-left: 0.7%">
            <button type="button" disabled class="btn btn-sm btn-info" id="updateReference">Update Changes</button>
        </div>
    </div>--}}

    <h6 class="mb-1 mt-1"><span
            class="border-bottom-secondary border-bottom-1 text-bold-400">Customer Account Info</span></h6>
    <div class="row">
        <div class="col-md-2"><label for="customer_id" class="">Customer ID </label></div>
        <div class="col-md-3 form-group pl-50">
            <input type="text" class="form-control form-control-sm" name="customer_id"
                   value="{{isset($invBillReceiptInfo->customer_id) ? $invBillReceiptInfo->customer_id : ''}}"
                   readonly/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="customer_name" class="">Customer Name </label></div>
        <div class="col-md-10 form-group pl-50">
            <input type="text" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->customer_name) ? $invBillReceiptInfo->customer_name : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="customer_category" class="">Customer Category </label></div>
        <div class="col-md-10 form-group pl-50">
            <input type="text" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->customer_category_name) ? $invBillReceiptInfo->customer_category_name : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="customer_bills_receivable" class="">Bills Receivable </label></div>
        <div class="col-md-3 form-group pl-50">
            <input type="text" id="customer_bills_receivable" class="form-control form-control-sm"
                   name="customer_bills_receivable"
                   placeholder=""
                   value="{{isset($invBillReceiptInfo->os_bill_receivable) ? $invBillReceiptInfo->os_bill_receivable : ''}}"
                   disabled/>
        </div>
    </div>

    <h6 class="mb-1 mt-1"><span
            class="border-bottom-secondary border-bottom-1 text-bold-400">Invoice/Bill Reference</span></h6>
    <div class="row">
        <div class="col-md-12 table-responsive fixed-height-scrollable">
            <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                <thead class="thead-light sticky-head">
                <tr>
                    <th>#SL No</th>
                    <th>#Party Sub-Ledger</th> {{--TODO: Add this part: Pavel-26-04-22--}}
                    <th>Document No</th>
                    <th>Document date</th>
                    <th>Document Reference</th>
                    <th>Invoice Amount</th>
                    {{--<th>Vat Amount</th>--}} {{--TODO: Block this part: Pavel-26-04-22--}}
                    <th>Due Amount</th>
                    <th>Receipt Amount</th>
                </tr>
                </thead>
                <tbody id="invRefList">
                @if(count($invReferenceList) > 0)
                    @php $index=1; $totalDue = 0; @endphp
                    @foreach ($invReferenceList as $value)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $value->party_sub_ledger }}</td> {{--TODO: Add this part: Pavel-26-04-22--}}
                            <td>{{ $value->document_no }}</td>
                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
                            <td>{{ $value->document_ref }}</td>
                            <td>{{ $value->invoice_amount }}</td>
                            {{--<td>{{ $value->vat_amount }}</td>--}} {{--TODO: Block this part: Pavel-26-04-22--}}
                            <td>{{ $value->receipt_due }}</td>
                            <td>{{ $value->receipt_amt }}</td>
                        </tr>
                        @php $index++; $totalDue += $value->receipt_amt; @endphp
                    @endforeach
                    <tr class="font-small-3">
                        <th colspan="7" class="text-right pr-2">Total Receipt Amount</th>
                        <th id="total_due_amt">{{$totalDue}}</th>
                    </tr>
                @else
                    <tr>
                        <th colspan="8" class="text-center"> No Data Found</th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    <h6 class="mb-1 mt-1"><span
            class="border-bottom-secondary border-bottom-1 text-bold-400">Collection/Receipt Info</span></h6>
    <div class="row">
        <div class="col-md-2"><label for="bank_id" class="required">Bank Account </label></div>
        <div class="col-md-10 form-group pl-50">
            <input type="text" id="bank_id" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->bank_account_name) ? $invBillReceiptInfo->bank_account_name : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="currency" class="">Currency</label></div>
        <div class="col-md-2 form-group pl-50">
            <input type="text" id="currency" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->currency_code) ? $invBillReceiptInfo->currency_code : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="exc_rate" class="">Exchange Rate</label></div>
        <div class="col-md-2 form-group pl-50">
            <input type="text" id="exc_rate" class="form-control form-control-sm exc_rate"
                   value="{{isset($invBillReceiptInfo->exchange_rate) ? $invBillReceiptInfo->exchange_rate : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row text-center">
        <div class="offset-2 col-md-3 form-group pl-0 mb-0">
            <label for="">Amount in CCY </label>
        </div>
        <div class="col-md-3 form-group pl-0 mb-0">
            <label for="">Amount in LCY </label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="receipt_amt_ccy" class="">Receipt Amount </label></div>
        <div class="col-md-3 form-group pl-50">
            <input type="text" id="receipt_amt_ccy" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->receipt_amount_ccy) ? $invBillReceiptInfo->receipt_amount_ccy : ''}}"
                   disabled/>
        </div>
        <div class="col-md-3 form-group pl-50">
            <input type="text" id="receipt_amt_lcy" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->receipt_amount_lcy) ? $invBillReceiptInfo->receipt_amount_lcy : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"><label for="receipt_instrument" class="">Instrument Type</label></div>
        <div class="col-md-6 form-group pl-50">
            <input type="text" class="form-control form-control-sm"
                   value="{{isset($invBillReceiptInfo->instrument_name) ? $invBillReceiptInfo->instrument_name : ''}}"
                   disabled/>
        </div>
    </div>
    <div class="viewDocumentRef">
        <div class="row">
            <div class="col-md-2"><label for="instrument_no" class="required">Instrument No </label></div>
            <div class="col-md-3 form-group pl-50">
                <input type="text" class="form-control form-control-sm"
                       value="{{isset($invBillReceiptInfo->instrument_no) ? $invBillReceiptInfo->instrument_no : ''}}"
                       disabled/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"><label for="instrument_date" class="required">Instrument Date</label></div>
            <div class="col-md-3 form-group pl-50">
                <input type="text" class="form-control form-control-sm"
                       value="{{isset($invBillReceiptInfo->instrument_date) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->instrument_date)  : ''}}"
                       disabled/>
            </div>
        </div>
    </div>


    <div class="editDocumentRef d-none">
        <div class="row">
            <div class="col-md-2"><label for="edit_instrument_no" class="required">Instrument No </label></div>
            <div class="col-md-3 form-group pl-50">
                <input type="text" id="edit_instrument_no"
                       class="form-control form-control-sm"
                       name="edit_instrument_no"
                       placeholder=""
                       value="{{isset($invBillReceiptInfo->instrument_no) ? $invBillReceiptInfo->instrument_no : ''}}"
                       required/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"><label for="edit_instrument_date" class="required">Instrument Date</label></div>
            <div class="col-md-3 form-group pl-50">
                <div class="input-group date cheque_date" id="edit_instrument_date" data-target-input="nearest">
                    <input type="text" name="edit_instrument_date" id="edit_instrument_date_field"
                           autocomplete="off"
                           class="form-control form-control-sm datetimepicker-input edit_instrument_date"
                           data-target="#edit_instrument_date" data-toggle="datetimepicker"
                           value="{{isset($invBillReceiptInfo->instrument_date) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->instrument_date)  : ''}}"
                           data-predefined-date="{{isset($invBillReceiptInfo->instrument_date) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->instrument_date)  : ''}}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append edit_instrument_date" data-target="#edit_instrument_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</fieldset>
<div class="row mt-1 distribution_line_div ">
    <input type="hidden" name="ap_distribution_flag" value="0" id="ap_distribution_flag">
    <fieldset class="col-md-12 border p-2">
        <legend class="w-auto" style="font-size: 15px;">Transaction Detail
        </legend>
        <div class="row mt-1">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-hover table-bordered " id="ap_account_table">
                    <thead class="thead-dark">
                    <tr>
                        <th width="2%" class="">Account Code</th>
                        <th width="28%" class="">Account Name</th>
                        <th width="28%" class="">Party ID</th>
                        <th width="28%" class="">Party Name</th>
                        <th width="5%" class="text-right-align">Debit</th>
                        <th width="5%" class="text-right-align">Credit</th>
                        {{--<th width="16%" class="text-center">Amount CCY</th>
                        <th width="16%" class="text-center">Amount LCY</th>--}}
                        {{--<th width="5%" class="text-center">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($invBillReceiptInfo->invoice_line as $line)
                        {{--@php
                            $total += $line->amount_lcy;
                        @endphp--}}
                        <tr>
                            <td>{{ $line->account_id }}</td>
                            <td>{{ $line->account_name }}</td>
                            <td>{{ $line->party_code }}</td>
                            <td>{{ $line->party_name }}</td>
                            {{--<td>{{ $line->dr_cr }}</td>--}}
                            <td class="text-right-align">{{ $line->debit }}</td>
                            <td class="text-right-align">{{ $line->credit }}</td>
                            {{--<td class="text-right-align">{{ $line->amount_ccy }}</td>
                            <td class="text-right-align">{{ $line->amount_lcy }}</td>--}}
                            {{--<td>N/A</td>--}}
                        </tr>
                    @empty
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            {{--<td></td>--}}
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot class="border-top-dark bg-dark text-white">
                    <tr>
                        <td colspan="4" class="text-right-align">Total Amount</td>
                        <td class="text-right-align">{{ $invBillReceiptInfo->invoice_line[0]->total_debit }}</td>
                        <td class="text-right-align">{{ $invBillReceiptInfo->invoice_line[0]->total_credit }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </fieldset>
</div>
<fieldset class="border p-2 mt-2">
    <legend class="w-auto text-bold-600" style="font-size: 14px;">Attachment(s)</legend>
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
            <tbody id="invPayAttachList">
            @if(count($invReceiptDocsList) > 0)
                @php $index=1; @endphp
                @foreach ($invReceiptDocsList as $value)
                    <tr>
                        <td>{{ $index }}</td>
                        <td>{{ $value->doc_file_name }}</td>
                        <td>{{ $value->doc_file_desc }}</td>
                        <td>
                            @if($value && $value->doc_file_name)
                                <a href="{{ route('invoice-bill-receipt.attachment-download', [$value->doc_file_id]) }}"
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
                    <th colspan="5" class="text-center"> No Data Found</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</fieldset>
<div class="form-group row mt-1">
    <div class="col-md-12">
       {{-- <a href="{{ route('invoice-bill-receipt-list.index') }}" class="btn btn-sm btn-dark">
            <i class="bx bx-reset"></i>Back
        </a>--}}
        <button type="button" class="btn btn-sm btn-info d-none" id="updateReference"><i
                class="bx bx-up-arrow-alt"></i>Update
            Changes
        </button>
    </div>
</div>

@section('view-script')
    <script type="text/javascript">

        $(document).ready(function () {
            /**Update Transaction Reference Start**/
            $("#chnTransRef").on('change', function () {
                if ($(this).is(":checked")) {
                    $(".viewDocumentRef").addClass('d-none');
                    $(".editDocumentRef").removeClass('d-none');

                    /*  $("#edit_bill_section").select2().val('{{isset($inserted_date->bill_sec_id) ? $inserted_date->bill_sec_id : ''}}').css('width:', '100%');
                $("#edit_bill_section").select2().trigger('change');
*/
                    selectBillRegister('#edit_bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + $('#edit_bill_section :selected').val(), APP_URL + '/account-receivable/ajax/get-bill-register-detail/', '');
                    $("#updateReference").removeClass('d-none');


                    let defaultPeriod = $("#edit_period :selected").val();
                    let defaultPostingDate = $("#edit_posting_date_field").val();
                    let defaultDocumentDate = $("#edit_document_date_field").val();
                    getPostingPeriod($("#edit_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
                    function setPostingPeriod(periods) {    //Over writing setPostingPeriod
                        $("#edit_period").html(periods);
                        $("#edit_period").val(defaultPeriod).trigger('change');
                        $("#edit_posting_date_field").val(defaultPostingDate);
                        $("#edit_document_date_field").val(defaultDocumentDate);
                    }
                } else {
                    $("#updateReference").addClass('d-none');
                    $(".viewDocumentRef").removeClass('d-none');
                    $(".editDocumentRef").addClass('d-none');
                    /* $("#edit_department").select2().val('{{isset($inserted_date->cost_center_dept_id) ? $inserted_date->cost_center_dept_id : ''}}');
                    $("#edit_department").select2().trigger('change');*/
                }
            });

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;

            $("#edit_posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#edit_posting_date >input").val("");
                let minDate = $("#edit_period :selected").data("mindate");
                let maxDate = $("#edit_period :selected").data("maxdate");
                let currentDate = $("#edit_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
            let postingDateClickCounter = 0;

            $("#edit_posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                if (!nullEmptyUndefinedChecked($("#edit_posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#edit_posting_date_field").val()).format("DD-MM-YYYY");
                    } else {
                        newDueDate = moment($("#edit_posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                    }
                    /*newDueDate = moment($("#edit_posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");*/
                    $("#edit_document_date >input").val(newDueDate);
                }
                postingDateClickCounter++;
            });

            $("#edit_document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#edit_document_date >input").val("");
                let minDate = false;
                let maxDate = $("#edit_period :selected").data("maxdate");
                let currentDate = $("#edit_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            function listBillRegister() {
                $('#edit_bill_section').change(function (e) {
                    $("#edit_bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#edit_bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            listBillRegister();

            $("#edit_fiscal_year").on('change', function () {
                getPostingPeriod($("#edit_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            //getPostingPeriod($("#edit_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            function setPostingPeriod(periods) {
                $("#edit_period").html(periods);
                //setPeriodCurrentDate();
                $("#edit_period").trigger('change');
            }

            let chequeCalendarClickCounter = 0;

            $("#edit_period").on('change', function () {
                $("#edit_document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#edit_document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#edit_posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#edit_posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                $("#edit_instrument_date >input").val("");
                if (chequeCalendarClickCounter > 0) {
                    $("#edit_instrument_date").datetimepicker('destroy');
                    chequeCalendarClickCounter = 0;
                }

                //setPeriodCurrentDate();
            });

            $("#edit_instrument_date").on('click', function () {
                chequeCalendarClickCounter++;
                $("#edit_instrument_date >input").val("");
                let minDate = false;
                //No minimum date. Depends on document date applied on 02112022:    let minDate = $("#edt_period :selected").data("mindate");

                let maxDate = false;
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            $("#updateReference").on('click', () => {
                swal.fire({
                    title: 'Are you sure?',
                    type: 'info',
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok",
                    confirmButtonClass: "btn btn-primary",
                    cancelButtonClass: "btn btn-danger ml-1",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {

                        let receiptId = $("#receipt_id").val();
                        let period = $("#edit_period :selected").val();
                        let postingDate = $("#edit_posting_date_field").val();
                        let documentDate = $("#edit_document_date_field").val();
                        let documentNumber = $("#edit_document_number").val();
                        let documentRef = $("#edit_document_reference").val();
                        let documentNarration = $("#edit_narration").val();
                        let department = $("#edit_department :selected").val();
                        let instrumentNo = $("#edit_instrument_no").val();
                        let instrumentDate = $("#edit_instrument_date_field").val();
                        let billSection = $("#edit_bill_section :selected").val();
                        let billRegister = $("#edit_bill_register :selected").val();


                        let request = $.ajax({
                            url: APP_URL + "/account-receivable/invoice-bill-receipt-update",
                            data: {
                                receiptId,
                                period,
                                postingDate,
                                documentDate,
                                documentNumber,
                                documentRef,
                                documentNarration,
                                department,
                                instrumentNo,
                                instrumentDate,
                                billSection,
                                billRegister
                            },
                            dataType: "JSON",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (res.response_code == "1") {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    let urlStr = '{{ route('invoice-bill-receipt.view',['id'=>'_p']) }}';
                                    window.location.href = urlStr.replace('_p', receiptId);
                                });
                            } else {
                                Swal.fire({text: res.response_message, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
            });

            /**Update Transaction Reference End**/

        });

    </script>
@endsection

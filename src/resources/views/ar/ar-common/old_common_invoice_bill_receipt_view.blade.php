<fieldset class="border p-2 mt-2">
    <legend class="w-auto text-bold-600" style="font-size: 16px;">Detail Reference</legend>
    <div class="row">
        <div class="col-md-5">
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
            <div class="row">
                <div class="col-md-5"><label for="batch_id" class="">Batch ID</label></div>
                <div class="col-md-7 form-group pl-0">
                    <input type="text" class="form-control form-control-sm"
                           value="{{isset($invBillReceiptInfo->batch_id) ? $invBillReceiptInfo->batch_id : ''}}"
                           disabled/>
                </div>
            </div>
            <div class="viewDocumentRef">
                <div class="row">
                    <div class="col-md-5"><label for="period" class="">Posting Period </label></div>
                    <div class="col-md-7 form-group pl-0">
                        <input type="text" class="form-control form-control-sm"
                               value="{{isset($invBillReceiptInfo->trans_period_name) ? $invBillReceiptInfo->trans_period_name : ''}}"
                               disabled/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 "><label for="posting_date" class="">Posting Date </label></div>
                    <div class="col-md-7 form-group pl-0">
                        <input type="text" class="form-control form-control-sm"
                               value="{{isset($invBillReceiptInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date) : ''}}"
                               disabled/>
                    </div>
                </div>
            </div>

            <div class="editDocumentRef d-none">
                <input type="hidden" name="receipt_id" id="receipt_id"
                       value="{{ isset($invBillReceiptInfo) ? $invBillReceiptInfo->receipt_id : '' }}">
                <div class="form-group row make-readonly">
                    <label for="period" class="col-md-5 col-form-label">Posting Periods</label>
                    <div class="col-md-7 pl-0">
                        <select readonly="" name="period" class="form-control form-control-sm" id="period">
                            {{--<option value="">Select a period</option>--}}
                            @foreach($postingDate as $post)
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
                    <label for="edt_posting_date_field" class="required col-md-5 col-form-label ">Posting
                        Date</label>
                    <div class="input-group date edt_posting_date col-md-7 pl-0"
                         id="edt_posting_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false"
                               name="edt_posting_date"
                               id="edt_posting_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#edt_posting_date"
                               data-toggle="datetimepicker"
                               value="{{ \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->trans_date) }}"
                               data-predefined-date="{{ old('edt_posting_date', $invBillReceiptInfo->trans_date) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append edt_posting_date" data-target="#edt_posting_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edt_document_date_field" class="col-md-5 col-form-label">Document
                        Date</label>
                    <div class="input-group date edt_document_date col-md-7 pl-0"
                         id="edt_document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false"
                               name="edt_document_date"
                               id="edt_document_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#edt_document_date"
                               data-toggle="datetimepicker"
                               value="{{ \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->document_date) }}"
                               data-predefined-date="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($invBillReceiptInfo->document_date)) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append edt_document_date" data-target="#edt_document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edt_document_number" class=" col-md-5 col-form-label">Document No</label>
                    <div class="col-md-7 form-group pl-0">
                        <input type="text" class="form-control form-control-sm"
                               name="edt_document_number"
                               id="edt_document_number"
                               value="{{ $invBillReceiptInfo->document_no }}">
                    </div>
                </div>
            </div>

        </div>
        <div class="offset-2 col-md-5">
            <div class="row  mb-25">
                <div class="col-md-12 d-flex justify-content-end">
                    <a target="_blank" class="btn btn-sm btn-info cursor-pointer"
                       href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$invBillReceiptInfo->trans_period_id}}&p_trans_batch_id={{$invBillReceiptInfo->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                        <i class="bx bx-printer"></i>Print Voucher
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"><label for="dep_cost_center" class="">Dept/Cost Center</label></div>
                <div class="col-md-6 form-group pl-0">
                    <input type="text" class="form-control form-control-sm"
                           value="{{isset($invBillReceiptInfo->cost_center_dept_name) ? $invBillReceiptInfo->cost_center_dept_name : ''}}"
                           disabled/>
                </div>
            </div>

            <div class="viewDocumentRef">
                <div class="row">
                    <div class="col-md-6"><label for="bill_sec_id" class="">Bill Section </label></div>
                    <div class="col-md-6 form-group pl-0">
                        <input type="text" class="form-control form-control-sm"
                               value="{{isset($invBillReceiptInfo->bill_sec_name) ? $invBillReceiptInfo->bill_sec_name : ''}}"
                               disabled/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"><label for="bill_register" class="">Bill Register </label></div>
                    <div class="col-md-6 form-group pl-0">
                        <input type="text" class="form-control form-control-sm"
                               value="{{isset($invBillReceiptInfo->bill_reg_name) ? $invBillReceiptInfo->bill_reg_name : ''}}"
                               disabled/>
                    </div>
                </div>
            </div>

            <div class="editDocumentRef d-none">
                <div class="form-group row">
                    <label for="edt_bill_section" class="col-md-6 col-form-label">Bill
                        Section</label>
                    <div class="col-md-6 pl-0">
                        <select name="edt_bill_section" class="form-control form-control-sm select2"
                                id="edt_bill_section">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($billSecs as $value)
                                <option
                                    {{  old('edt_bill_section',$invBillReceiptInfo->bill_sec_id) ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row ">
                    <label for="edt_bill_register" class="col-md-6 col-form-label">Bill
                        Register</label>
                    <div class="col-md-6 pl-0">
                        <select name="edt_bill_register" class="form-control form-control-sm "
                                id="edt_bill_register">
                            <option
                                value="{{ $invBillReceiptInfo->bill_reg_id }}">{{ $invBillReceiptInfo->bill_reg_name }}</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="viewDocumentRef">
        <div class="row">
            <div class="col-md-2 "><label for="narration" class="required">Narration </label></div>
            <div class="col-md-10 form-group pl-50">
                <textarea class="form-control form-control-sm" id="narration" rows="3"
                          disabled>{{isset($invBillReceiptInfo->narration) ? $invBillReceiptInfo->narration : ''}}</textarea>
            </div>
        </div>
    </div>

    <div class="editDocumentRef d-none">
        <div class="form-group row">
            <label for="edt_document_reference" class="col-md-2 col-form-label">Document Ref</label>
            <div class="col-md-8 form-group" style="padding-left: 0.7%">
                <input type="text" class="form-control form-control-sm"
                       name="edt_document_reference"
                       id="edt_document_reference"
                       value="{{ $invBillReceiptInfo->document_ref }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 "><label for="edt_narration" class="required">Narration </label></div>
            <div class="col-md-8 form-group"  style="padding-left: 0.7%">
                <textarea class="form-control form-control-sm" id="edt_narration" rows="3">{{isset($invBillReceiptInfo->narration) ? $invBillReceiptInfo->narration : ''}}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 offset-2"  style="padding-left: 0.7%">
            <button type="button" disabled class="btn btn-sm btn-info" id="updateReference">Update Changes</button>
        </div>
    </div>

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
                            {{--<td></td>--}}
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot class="border-top-dark bg-dark text-white">
                    <tr>
                        <td colspan="2" class="text-right-align">Total Amount</td>
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

@section('view-script')
    <script type="text/javascript">

        $(document).ready(function () {
            /**Update Transaction Reference Start**/
            $("#chnTransRef").on('change', function () {
                if ($(this).is(":checked")) {
                    $("#updateReference").removeAttr('disabled', 'disabled');
                    $(".viewDocumentRef").addClass('d-none');
                    $(".editDocumentRef").removeClass('d-none');

                    /*  $("#edt_bill_section").select2().val('{{isset($inserted_date->bill_sec_id) ? $inserted_date->bill_sec_id : ''}}').css('width:', '100%');
                $("#edt_bill_section").select2().trigger('change');
*/
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-receivable/ajax/get-bill-register-detail/', '');
                } else {
                    $("#updateReference").attr('disabled', 'disabled');
                    $(".viewDocumentRef").removeClass('d-none');
                    $(".editDocumentRef").addClass('d-none');
                    /* $("#edt_department").select2().val('{{isset($inserted_date->cost_center_dept_id) ? $inserted_date->cost_center_dept_id : ''}}');
                    $("#edt_department").select2().trigger('change');*/
                }
            });

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;

            $("#edt_posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#edt_posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
            let postingDateClickCounter = 0;

            $("#edt_posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                if (!nullEmptyUndefinedChecked($("#edt_posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#edt_posting_date_field").val()).format("DD-MM-YYYY");
                    } else {
                        newDueDate = moment($("#edt_posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                    }
                    $("#edt_document_date >input").val(newDueDate);
                }
                postingDateClickCounter++;
            });

            $("#edt_document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#edt_document_date >input").val("");
                let minDate = false;
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            function listBillRegister() {
                $('#edt_bill_section').change(function (e) {
                    $("#edt_bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            listBillRegister();


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
                        let postingDate = $("#edt_posting_date_field").val();
                        let documentDate = $("#edt_document_date_field").val();
                        let documentNumber = $("#edt_document_number").val();
                        let documentRef = $("#edt_document_reference").val();
                        let documentNarration = $("#edt_narration").val();
                        //let department = $("#edt_department :selected").val();
                        let billSection = $("#edt_bill_section :selected").val();
                        let billRegister = $("#edt_bill_register :selected").val();


                        let request = $.ajax({
                            url: APP_URL + "/account-receivable/invoice-bill-receipt-update",
                            data: {
                                receiptId,
                                postingDate,
                                documentDate,
                                documentNumber,
                                documentRef,
                                documentNarration,
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

{{--<div class="card">
    <div class="card-body">
        --}}{{--        <form id="invoice_bill_entry_form" action="#" method="post" enctype="multipart/form-data">--}}{{--
        --}}{{--            @csrf--}}{{--
        <h5 style="text-decoration: underline">Invoice/Bill Entry View</h5>--}}

<div class="row">
    <fieldset class="border p-2 col-md-12">
        <legend class="w-auto text-bold-600" style="font-size: 15px;">Detail View</legend>
        <h6><span class="border-bottom-secondary border-bottom-1 text-bold-500">Invoice References</span>
            <span style="margin-left: 6.5%">
                <input class="form-check-input" type="checkbox" value="" id="chnTransRef"
                   {{--@if (!isset($roleWiseUser)) disabled @endif--}}
                    {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AR_MODULE_ID,\App\Enums\WorkFlowRoleKey::AR_INVOICE_BILL_ENTRY_MAKE,\App\Enums\RolePermissionsKey::CAN_EDIT_AR_INVOICE_MAKE )) ) ? 'disabled' : '' }}
                >
                <label class="form-check-label font-small-3" for="chnTransRef">
                    Change Trans Reference
                </label>
            </span>
        </h6>
        <div class="viewDocumentRef">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row make-readonly">
                        <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                        <div class="col-md-5">
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
                    <div class="form-group row make-readonly">
                        <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                            Date</label>
                        <div class="input-group date posting_date col-md-5"
                             id="posting_date"
                             data-target-input="nearest">
                            <input readonly type="text" autocomplete="off" onkeydown="return false"
                                   name="posting_date"
                                   id="posting_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#posting_date"
                                   data-toggle="datetimepicker"
                                   value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date) }}"
                                   data-predefined-date="{{ old('posting_date', $inserted_data->trans_date) }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append posting_date" data-target="#posting_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row make-readonly">
                        <label for="document_date_field" class="required col-md-4 col-form-label">Document
                            Date</label>
                        <div class="input-group date document_date col-md-5"
                             id="document_date"
                             data-target-input="nearest">
                            <input readonly type="text" autocomplete="off" onkeydown="return false"
                                   name="document_date"
                                   id="document_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#document_date"
                                   data-toggle="datetimepicker"
                                   value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->document_date) }}"
                                   data-predefined-date="{{ old('document_date', $inserted_data->document_date) }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append document_date" data-target="#document_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row make-readonly">
                        <label for="document_number" class="required col-md-4 col-form-label">Document No</label>
                        <div class="col-md-5">
                            <input readonly type="text" class="form-control form-control-sm"
                                   name="document_number"
                                   id="document_number"
                                   value="{{ $inserted_data->document_no }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row d-flex justify-content-end">
                        <div class="offset-4 col-md-5">
                            <a target="_blank" class="cursor-pointer btn btn-sm btn-info"
                               href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$inserted_data->trans_period_id}}&p_trans_batch_id={{$inserted_data->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                                <i class="bx bx-printer"></i>Print Voucher
                            </a>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end make-readonly">
                        <label for="department" class="col-form-label col-md-4 required">Dept/Cost
                            Center</label>
                        <div class="col-md-5">
                            <select readonly name="department" class="form-control form-control-sm"
                                    id="department">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($department as $dpt)
                                    <option
                                        {{  $inserted_data->department_id ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end make-readonly">
                        <label for="bill_section" class="required col-md-4 col-form-label">Bill
                            Section</label>
                        <div class="col-md-5">
                            <select readonly="" name="bill_section" class="form-control form-control-sm"
                                    id="bill_section">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($billSecs as $value)
                                    <option
                                        {{  $inserted_data->bill_sec_id ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end make-readonly">
                        <label for="bill_register" class="required col-md-4 col-form-label">Bill
                            Register</label>
                        <div class="col-md-5">
                            <select readonly="" name="bill_register" class="form-control form-control-sm "
                                    id="bill_register">
                                <option
                                    value="{{ $inserted_data->bill_reg_id }}">{{ $inserted_data->bill_reg_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row make-readonly pr-1">
                <label for="document_reference" class="col-md-2 col-form-label required">Document Ref</label>
                <div class="col-md-8">
                    <input readonly type="text" class="form-control form-control-sm"
                           id="document_reference"
                           name="document_reference"
                           value="{{ $inserted_data->document_ref }}">
                </div>
            </div>
            <div class="form-group row pr-1">
                <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                <div class="col-md-8">
                        <textarea readonly name="narration" class="required form-control form-control-sm "
                                  id="narration">{{ $inserted_data->narration }}</textarea>
                </div>
            </div>
        </div>
        <div class="editDocumentRef d-none">
            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ isset($inserted_data) ? $inserted_data->invoice_id : '' }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row make-readonly">
                        <label for="period" class="required col-md-4 col-form-label">Posting Periods</label>
                        <div class="col-md-5">
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
                        <label for="edt_posting_date_field" class="required col-md-4 col-form-label ">Posting
                            Date</label>
                        <div class="input-group date edt_posting_date col-md-5"
                             id="edt_posting_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   name="edt_posting_date"
                                   id="edt_posting_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#edt_posting_date"
                                   data-toggle="datetimepicker"
                                   value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->trans_date) }}"
                                   data-predefined-date="{{ old('edt_posting_date', $inserted_data->trans_date) }}"
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
                        <label for="edt_document_date_field" class="required col-md-4 col-form-label">Document
                            Date</label>
                        <div class="input-group date edt_document_date col-md-5"
                             id="edt_document_date"
                             data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   name="edt_document_date"
                                   id="edt_document_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#edt_document_date"
                                   data-toggle="datetimepicker"
                                   value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->document_date) }}"
                                   data-predefined-date="{{ old('edt_document_date', \App\Helpers\HelperClass::dateConvert($inserted_data->document_date)) }}"
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
                        <label for="edt_document_number" class="required col-md-4 col-form-label">Document No</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm"
                                   name="edt_document_number"
                                   id="edt_document_number"
                                   value="{{ $inserted_data->document_no }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row d-flex justify-content-end">
                        <div class="offset-4 col-md-5">
                            <a target="_blank" class="cursor-pointer btn btn-sm btn-info"
                               href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id={{$inserted_data->trans_period_id}}&p_trans_batch_id={{$inserted_data->batch_id}}&type=pdf&filename=transaction_list_batch_wise">
                                <i class="bx bx-printer"></i>Print Voucher
                            </a>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end make-readonly">
                        <label for="department" class="col-form-label col-md-4 required">Dept/Cost
                            Center</label>
                        <div class="col-md-5">
                            <select readonly name="department" class="form-control form-control-sm"
                                    id="department">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($department as $dpt)
                                    <option
                                        {{  $inserted_data->department_id ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end">
                        <label for="edt_bill_section" class="required col-md-4 col-form-label">Bill
                            Section</label>
                        <div class="col-md-5">
                            <select required name="edt_bill_section" class="form-control form-control-sm select2"
                                    id="edt_bill_section">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($billSecs as $value)
                                    <option
                                        {{  old('edt_bill_section',$inserted_data->bill_sec_id) ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end">
                        <label for="edt_bill_register" class="required col-md-4 col-form-label">Bill
                            Register</label>
                        <div class="col-md-5">
                            <select required name="edt_bill_register" class="form-control form-control-sm "
                                    id="edt_bill_register">
                                <option
                                    value="{{ $inserted_data->bill_reg_id }}">{{ $inserted_data->bill_reg_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row pr-1">
                <label for="edt_document_reference" class="col-md-2 col-form-label required">Document Ref</label>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm"
                           id="edt_document_reference"
                           name="edt_document_reference"
                           value="{{ $inserted_data->document_ref }}">
                </div>
            </div>
            <div class="form-group row pr-1">
                <label for="edt_narration" class="required col-md-2 col-form-label">Narration</label>
                <div class="col-md-8">
                        <textarea name="edt_narration" class="required form-control form-control-sm "
                                  id="edt_narration">{{ $inserted_data->narration }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-2">
                <button type="button" disabled class="btn btn-sm btn-info" id="updateReference">Update Changes</button>
            </div>
        </div>

        <h6 class="mt-2"><span
                class="border-bottom-secondary border-bottom-1 text-bold-500">Party Sub-Ledger Info</span></h6>
        <div class="form-group row mt-2">
            <label class="required col-md-2 col-form-label" for="ap_party_sub_ledger">Party Sub-Ledger</label>
            <div class="col-md-6 make-readonly">
                <select readonly="" class="form-control form-control-sm col-md-9" id="ap_party_sub_ledger"
                        name="ap_party_sub_ledger">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['subsidiary_type'] as $type)
                        <option
                            value="{{$type->gl_subsidiary_id}}" {{ ($inserted_data->gl_subsidiary_id == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label required" for="ar_transaction_type">Transaction Type</label>
            <div class="col-md-6 make-readonly">
                <select readonly class="form-control form-control-sm col-md-9" id="ar_transaction_type"
                        name="ar_transaction_type">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($transactionType as $transaction)
                        <option
                            value="{{$transaction->transaction_type_id}}" {{ (old('ar_transaction_type', isset($inserted_data) ? $inserted_data->transaction_type_id : '' ) == $transaction->transaction_type_id) ? 'Selected' : '' }}>{{$transaction->transaction_type_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_vendor_id">Customer ID</label>
            <div class="col-md-6">
                <div class="form-group row make-readonly">
                    <div class="input-group col-md-6">
                        <input name="ap_vendor_id" class="form-control form-control-sm " type="number" readonly
                               id="ap_vendor_id"
                               value="{{ $inserted_data->customer_id }}">
                    </div>
                    <div class="col-md-5 pl-0">
                        <button disabled class="btn btn-sm btn-primary vendorIdSearch" id="ap_vendor_search"
                                type="button"
                                tabindex="-1"><i
                                class="bx bx-search font-size-small"></i><span class="align-middle">Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_vendor_name">Customer Name</label>
            <div class="col-md-7 pr-0">
                <input type="text" value="{{ $inserted_data->customer_name }}" class="form-control form-control-sm"
                       id="ap_vendor_name" name="ap_vendor_name" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_vendor_category">Customer Category</label>
            <div class="col-md-7 pr-0">
                <input type="text" value="{{ $inserted_data->customer_category_name }}"
                       class="form-control form-control-sm" id="ap_vendor_category" name="ap_vendor_category"
                       readonly>
            </div>
        </div>

        <h6 class="mt-2"><span
                class="border-bottom-secondary border-bottom-1 text-bold-500">Invoice/Bill Master Info</span></h6>
        <div class="form-group row make-readonly">
            <label class="col-form-label col-md-2" for="ap_payment_currency">Payment Currency</label>
            <div class="col-md-2">
                <select readonly="" name="ap_payment_currency" class="form-control form-control-sm"
                        id="ap_payment_currency">
                    @foreach($data['currency'] as $cur)
                        <option
                            value="{{ $cur->currency_code }}" {{ ( $cur->currency_code == $inserted_data->currency_code) ? "selected" : '' }}>{{ $cur->currency_code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="ap_exchange_rate">Exchange Rate</label>
            <div class="col-md-2">
                <input readonly type="text" value="{{$inserted_data->exchange_rate}}"
                       class="form-control form-control-sm"
                       id="ap_exchange_rate"
                       name="ap_exchange_rate">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group row make-readonly">
                    <label class="col-form-label col-md-4" for="ap_invoice_amount"></label>
                    <div class="col-md-4 text-center">
                        <label class="col-form-label" for="">Amount CCY</label>
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="col-form-label" for="">Amount LCY</label>
                    </div>
                </div>
                <div class="form-group row make-readonly">
                    <label class="col-form-label col-md-4 required" for="ap_invoice_amount">Invoice Amount</label>
                    <div class="col-md-4 pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_invoice_amount_ccy" value="{{ $inserted_data->invoice_amount_ccy }}"
                               name="ap_invoice_amount_ccy">
                    </div>
                    <div class="col-md-4 make-readonly pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               value="{{ $inserted_data->invoice_amount_lcy }}"
                               id="ap_invoice_amount_lcy"
                               name="ap_invoice_amount_lcy">
                    </div>
                </div>
                <div class="form-group row make-readonly">
                    <label class="col-form-label col-md-4" for="ap_vat_amount_ccy">VAT Amount</label>
                    <div class="col-md-4 pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_vat_amount_ccy" value="{{ $inserted_data->vat_amount_ccy }}"
                               name="ap_vat_amount_ccy">
                    </div>
                    <div class="col-md-4 make-readonly pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               value="{{ $inserted_data->vat_amount_lcy }}"
                               id="ap_vat_amount_lcy" name="ap_vat_amount_lcy">
                    </div>
                </div>
                <div class="form-group row make-readonly">
                    <label class="col-form-label col-md-4" for="ap_payable_amount_ccy">Receivable Amount</label>
                    <div class="col-md-4 pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_payable_amount_ccy"
                               value="{{ $inserted_data->receivable_amount_ccy }}"
                               name="ap_payable_amount_ccy">
                    </div>
                    <div class="col-md-4 pl-0">
                        <input readonly type="text" class="form-control form-control-sm text-right-align"
                               id="ap_payable_amount_lcy"
                               value="{{ $inserted_data->receivable_amount_lcy }}"
                               name="ap_payable_amount_lcy">
                    </div>
                </div>
            </div>
            <div class="col-md-5 offset-1">
                <h6 class="mb-2"><span
                        class="border-bottom-secondary border-bottom-1 text-bold-500">Receipt Condition</span></h6>
                <div class="form-group row make-readonly">
                    @if(filled($receiptTerms))
                        <label class="col-md-4 col-form-label" for="ap_payment_terms">Receipt Terms</label>
                        <div class="col-md-8 pl-0">
                            <select readonly="" name="ap_payment_terms" class="form-control form-control-sm"
                                    id="ap_payment_terms">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($receiptTerms as $value)
                                    <option
                                        value="{{$value->receipt_term_id}}" {{ ($inserted_data->receipt_terms_id == $value->receipt_term_id) ? 'selected' : ''  }} >{{ $value->receipt_term_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                @if(filled($receiptMethods))
                    <div class="form-group row ">
                        <label class="col-md-4 col-form-label" for="ap_payment_method">Receipt
                            Method</label>
                        <div class=" col-md-8 pl-0">
                            <select readonly="" name="ap_payment_method" class="form-control form-control-sm"
                                    id="ap_payment_method">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($receiptMethods as $value)
                                    <option
                                        value="{{$value->receipt_method_id}}" {{ ($inserted_data->receipt_methods_id == $value->receipt_method_id) ? 'selected' : ''  }}>{{ $value->receipt_method_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="form-group row make-readonly">
                    <label for="ap_payment_due_date" class="required col-md-4 col-form-label">Receipt Due
                        Date</label>
                    <div class="input-group date ap_payment_due_date col-md-8 pl-0"
                         id="ap_payment_due_date"
                         data-target-input="nearest">
                        <input readonly type="text" autocomplete="off" onkeydown="return false"
                               name="ap_payment_due_date"
                               id="ap_payment_due_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#ap_payment_due_date"
                               data-toggle="datetimepicker"
                               value="{{ \App\Helpers\HelperClass::dateConvert($inserted_data->receipt_due_date) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append" data-target="#ap_payment_due_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
</div>

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

                    @forelse($inserted_data->invoice_line as $line)
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
                        <td class="text-right-align">{{ $inserted_data->invoice_line[0]->total_debit }}</td>
                        <td class="text-right-align">{{ $inserted_data->invoice_line[0]->total_credit }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </fieldset>
</div>

<section>
    @forelse($inserted_data->invoice_file as $file)
        <p>File description: {{$file->doc_file_desc}} File: <a
                href="{{ route('ar-invoice-bill-listing.download',['id'=>$file->doc_file_id]) }}">{{$file->doc_file_name}}</a>
        </p>
    @empty
    @endforelse
</section>

<div class="row mt-1">
    @if(empty($wkMapInfo))
        <div class="col-md-5">
            <a href="{{ route('ar-invoice-bill-listing.index') }}" class="btn btn-dark btn-sm">
                <i class="bx bx-reset font-size-small"></i><span class="align-middle">Back</span>
            </a>
        </div>
    @endif
    <div class="col-md-6 ml-1">
        {{--<h6 class="text-primary">Last Posting Batch ID
            <span
                class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
        </h6>--}}
        {{--<div class="form-group row ">
            <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
            <input type="text" readonly tabindex="-1" class="form-control form-control-sm col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
        </div>--}}
    </div>
</div>

{{--        </form>--}}

{{--</div>
</div>--}}
@section('view-script')
<script type="text/javascript">

    $(document).ready(function () {
        /**Update Transaction Reference Start**/
        $("#chnTransRef").on('change', function () {
            if ($(this).is(":checked")) {
                $("#updateReference").removeAttr('disabled','disabled');
                $(".viewDocumentRef").addClass('d-none');
                $(".editDocumentRef").removeClass('d-none');

              /*  $("#edt_bill_section").select2().val('{{isset($inserted_date->bill_sec_id) ? $inserted_date->bill_sec_id : ''}}').css('width:', '100%');
                $("#edt_bill_section").select2().trigger('change');
*/
                selectBillRegister('#edt_bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-receivable/ajax/get-bill-register-detail/', '');
            } else {
                $("#updateReference").attr('disabled','disabled');
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

                    let invoiceId = $("#invoice_id").val();
                    let postingDate = $("#edt_posting_date_field").val();
                    let documentDate = $("#edt_document_date_field").val();
                    let documentNumber = $("#edt_document_number").val();
                    let documentRef = $("#edt_document_reference").val();
                    let documentNarration = $("#edt_narration").val();
                    //let department = $("#edt_department :selected").val();
                    let billSection = $("#edt_bill_section :selected").val();
                    let billRegister = $("#edt_bill_register :selected").val();


                    let request = $.ajax({
                        url: APP_URL + "/account-receivable/ar-invoice-bill-listing-update",
                        data: {
                            invoiceId,
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
                                let urlStr = '{{ route('ar-invoice-bill-listing.view',['id'=>'_p']) }}';
                                window.location.href = urlStr.replace('_p', invoiceId);
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

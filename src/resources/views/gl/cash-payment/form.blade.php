<form id="cash_payment_form" action="#" method="post" enctype="multipart/form-data">
    <h5 style="text-decoration: underline">Payment Voucher</h5>
    <div class="row">
        <div class="col-md-6">
            <div class=" row">
                <label for="function_type" class="required col-md-4 col-form-label">Function Type</label>
                <div class="col-md-4 pr-0">
                    <select required name="function_type" class="form-control form-control-sm" id="function_type">
                        {{--<option value="">Select a type</option>--}}
                        @foreach($funcType as $type)
                            <option
                                {{  old('function_type') ==  $type->function_id ? "selected" : "" }} value="{{$type->function_id}}">{{ $type->function_name}}</option>
                        @endforeach
                    </select>
                </div>

                {{--<div class=" col-md-4">
                    <div class="row">
                        <label for="department" class="col-md-5 col-form-label">Department</label>
                        <div class="col-md-7">
                            <select name="department" class="form-control form-control-sm select2" id="department">
                                <option value="">Select a department</option>
                                @foreach($department as $dpt)
                                    <option
                                        {{  old('department') ==  $dpt->department_id ? "selected" : "" }} value="{{$dpt->department_id}}">{{ $dpt->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>--}}
            </div>
            <div class=" row">
                <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                <div class="col-md-4 pr-0">
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
            <div class=" row">
                <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                <div class="col-md-4 pr-0">
                    <select required name="period" class="form-control form-control-sm" id="period">
                        {{--<option value="">Select a period</option>--}}
                        {{--@foreach($postingDate as $post)
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
            <div class=" row">
                <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting Date</label>
                <div class="input-group date posting_date col-md-4 pr-0"
                     id="posting_date"
                     data-target-input="nearest">
                    <input required type="text" autocomplete="off" onkeydown="return false"
                           name="posting_date" tabindex="-1"
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
            <div class=" row">
                <label for="document_date_field" class=" col-md-4 col-form-label">Document Date</label>
                <div class="input-group date document_date col-md-4 pr-0"
                     id="document_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="document_date" tabindex="-1"
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
            {{--<div class=" row">
                <label for="document_number" class="col-md-4 col-form-label">Document Number</label>
                <input maxlength="50" type="text" class="form-control form-control-sm col-md-6" name="document_number" id="document_number" oninput="this.value = this.value.toUpperCase()"
                       value="">
            </div>
            <div class=" row">
                <label for="document_reference" class="col-md-4 col-form-label">Document Reference</label>
                <input maxlength="200" type="text" class="form-control form-control-sm col-md-6" id="document_reference" name="document_reference"
                       value="">
            </div>--}}
        </div>
        <div class="col-md-6">
            <div class=" row d-flex justify-content-end">
                <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                <div class="col-md-5">
                    <select required name="department" class="form-control form-control-sm select2" id="department">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($department as $dpt)
                            <option
                                {{  old('department',\App\Enums\Gl\TransHeader::DEFAULT_DEPARTMENT) ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}">{{ $dpt->cost_center_dept_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{--<div class=" row justify-content-end">
                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                <div class="col-md-5">
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
            <div class=" row justify-content-end make-readonly">
                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                <div class="col-md-5">
                    <select required name="bill_section" class="form-control form-control-sm" readonly=""
                            id="bill_section">
                    </select>
                </div>
            </div>--}}

            <div class=" row d-flex justify-content-end">
                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                <div class="col-md-5">
                    <select required name="bill_section" class="form-control form-control-sm select2" id="bill_section">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($billSecs as $value)
                            <option
                                {{  old('bill_section',\App\Enums\Gl\TransHeader::DEFAULT_BILL_SECTION) ==  $value->bill_sec_id ? "selected" : "" }}  value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class=" row d-flex justify-content-end">
                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                <div class="col-md-5">
                    <select required name="bill_register" class="form-control form-control-sm select2"
                            id="bill_register">
                        <option value="">&lt;Select&gt;</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
    <div class=" row">
        <label for="document_number" class="col-md-2 col-form-label {{isset($isRequired) ? $isRequired['document_required'] : ''}}">Document Number</label>
        <div class="col-md-2 pr-0">
            <input maxlength="50" type="text" class="form-control form-control-sm" name="document_number"  {{isset($isRequired) ? $isRequired['document_required'] : ''}}
                   oninput="this.value = this.value.toUpperCase()"
                   id="document_number"
                   value="">
        </div>


        <label for="document_reference" class="col-md-3 col-form-label text-right-align offset-2">Document
            Reference</label>
        <div class="col-md-3 justify-content-end">
            <input maxlength="200" type="text" class="form-control form-control-sm" id="document_reference"
                   name="document_reference"
                   value="">
        </div>
    </div>
    <div class=" row pr-1">
        <label for="narration" class="required col-md-2 col-form-label">Narration</label>
        <div class="col-md-10 pr-0">
        <textarea maxlength="500" required name="narration" class="required form-control form-control-sm"
                  id="narration"></textarea>
        </div>
    </div>

    <fieldset class="col-md-12 border p-1">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">DEBIT ACCOUNT</legend>
        <div class="row">
            <div class="col-md-8">
                <div class="row ">
                    <input type="hidden" data-module-id="" id="module_id"/>
                    <label class="required col-md-3 col-form-label" for="d_account_id">Account ID</label>
                    <input name="account_id" class="form-control form-control-sm col-md-3 " value="" type="number"
                           id="d_account_id" maxlength="10"
                           oninput="maxLengthValid(this)"
                           onfocusout="addZerosInAccountId(this)"
                           onkeyup="resetDebitAccountField();resetPayableReceivableFields()">
                    <div class="col-md-2 pr-0">
                        <button class="btn btn-sm btn-primary searchAccount" id="searchAccount" type="button"
                                tabindex="-1">
                            <i class="bx bx-search font-size-small align-top"></i><span
                                class="align-middle">Search</span>
                        </button>
                    </div>
                    <label class="col-md-2 col-form-label text-right-align" for="d_account_type">Type</label>
                    <input class="form-control form-control-sm col-md-2" id="d_account_type" name="d_account_type"
                           tabindex="-1"
                           type="text" readonly>
                </div>
                <div class="row ">
                    <label for="d_account_name" class="col-md-3 col-form-label">Account Name</label>
                    <input name="d_account_name" class="form-control form-control-sm col-md-9" value=""
                           id="d_account_name" readonly
                           tabindex="-1">
                </div>
                <div class=" row hidden">
                    <label for="department_cost_center" class="col-form-label col-md-3">Department/Cost
                        Center</label>
                    <select name="department_cost_center"
                            class="form-control form-control-sm make-readonly-bg col-md-9"
                            id="department_cost_center">
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row ">
                    <label class="col-form-label col-md-6" for="d_account_balance">Account Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input class="form-control form-control-sm text-right-align" style="height: auto"
                               id="d_account_balance" tabindex="-1"
                               name="d_account_balance"
                               type="text" readonly>
                        <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px;"
                                      id="d_account_balance_type"></span>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <label class="col-md-6 col-form-label" for="d_authorized_balance">Authorized Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input name="d_authorized_balance"
                               class="form-control form-control-sm text-right-align" value=""
                               tabindex="-1" style="height: auto"
                               id="d_authorized_balance" readonly>
                        <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px;"
                                      id="d_authorized_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" row hidden">
            <label for="d_budget_head" class="col-md-2 col-form-label">Budget Head</label>
            <div class="col-md-6 pl-0 pr-0">
                <input name="d_budget_head" class="form-control form-control-sm" value="" id="d_budget_head"
                       type="text" readonly tabindex="-1">
            </div>
        </div>

        {{--TODO: Start receipt and payment section--}}

        <div class="row  receivableArea hidden mb-1 mt-1`">
            <div class="col-md-12">
                <h6 style="text-decoration: underline">Party Accounts (AR)</h6>

                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label class="required col-md-3 col-form-label" for="ar_party_sub_ledger">Party
                                Sub-Ledger</label>
                            <select class="form-control form-control-sm col-md-9" name="ar_party_sub_ledger"
                                    id="ar_party_sub_ledger">
                                <option value="">&lt;Select&gt;</option>
                                {{--@foreach($arSubsidiaryType as $type)
                                    <option
                                        value="{{$type->gl_subsidiary_id}}" {{ (old('ar_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        <div class="row ">
                            <label class="required col-md-3 col-form-label" for="ar_customer_id">Party/Customer
                                ID</label>
                            <input name="ar_customer_id" class="form-control form-control-sm col-md-3" value=""
                                   type="number"
                                   id="ar_customer_id"
                                   maxlength="10"
                                   onfocusout="addZerosInAccountId(this)"
                                   oninput="maxLengthValid(this)"
                                   onkeyup="resetField(['#ar_customer_name','#ar_customer_category','#ar_account_balance','#ar_authorized_balance']);">
                            <div class="col-md-2 pr-0">
                                <button class="btn btn-sm btn-primary customerIdSearch" id="ar_customer_search"
                                        type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                        class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label for="ar_customer_name" class="col-md-3 col-form-label">Party/Customer
                                Name</label>
                            <input type="text" class="form-control form-control-sm col-md-9" id="ar_customer_name"
                                   name="ar_customer_name" readonly tabindex="-1">
                        </div>
                        <div class="row ">
                            <label for="ar_customer_category" class="col-md-3 col-form-label">Party/Customer
                                Category</label>
                            <input name="ar_customer_category" class="form-control form-control-sm col-md-9"
                                   value=""
                                   id="ar_customer_category"
                                   readonly
                                   tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row ">
                            <label class="col-form-label col-md-6" for="ar_account_balance">Account Balance</label>
                            <div class="input-group col-md-6 pl-0">
                                <input class="form-control form-control-sm text-right-align"
                                       id="ar_account_balance" style="height: auto"
                                       tabindex="-1"
                                       name="ar_account_balance"
                                       type="text" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px;"
                                              id="ar_account_balance_type"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <label class="col-md-6 col-form-label" for="ar_authorized_balance">Authorized
                                Balance</label>
                            <div class="input-group col-md-6 pl-0">
                                <input name="ar_authorized_balance"
                                       class="form-control form-control-sm text-right-align"
                                       value="" tabindex="-1" style="height: auto"
                                       id="ar_authorized_balance" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="ar_authorized_balance_type"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row  payableArea hidden mb-1 mt-1`">
            <div class="col-md-12">
                <h6 style="text-decoration: underline">Party Accounts (AP)</h6>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label class="required col-md-3 col-form-label" for="ap_party_sub_ledger">Party
                                Sub-Ledger</label>
                            <select class="form-control form-control-sm col-md-9" name="ap_party_sub_ledger"
                                    id="ap_party_sub_ledger">
                                <option value="">&lt;Select&gt;</option>
                                {{--@foreach($apSubsidiaryType as $type)
                                    <option
                                        value="{{$type->gl_subsidiary_id}}" {{ (old('ap_party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        <div class="row ">
                            <label class="required col-md-3 col-form-label" for="ap_vendor_id">Party/Vendor
                                ID</label>
                            <input name="ap_vendor_id" class="form-control form-control-sm col-md-3" value=""
                                   type="number"
                                   id="ap_vendor_id"
                                   maxlength="10"
                                   onfocusout="addZerosInAccountId(this)"
                                   oninput="maxLengthValid(this)"
                                   onkeyup="resetField(['#ap_vendor_name','#ap_vendor_category','#ap_account_balance','#ap_authorized_balance']);">
                            <div class="col-md-2 pr-0">
                                <button class="btn btn-sm btn-primary vendorIdSearch" id="ap_vendor_search"
                                        type="button"
                                        tabindex="-1"><i class="bx bx-search font-size-small"></i><span
                                        class="align-middle ml-25">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row ">
                            <label for="ap_vendor_name" class="col-md-3 col-form-label">Party/Vendor Name</label>
                            <input name="ap_vendor_name" class="form-control form-control-sm col-md-9" value=""
                                   id="ap_vendor_name"
                                   readonly
                                   tabindex="-1">
                        </div>
                        <div class="row ">
                            <label for="ap_vendor_category" class="col-md-3 col-form-label">Party/Vendor
                                Category</label>
                            <input name="ap_vendor_category" class="form-control form-control-sm col-md-9" value=""
                                   id="ap_vendor_category"
                                   readonly
                                   tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row ">
                            <label class="col-form-label col-md-6" for="ap_account_balance">Account Balance</label>
                            <div class="input-group col-md-6 pl-0">
                                <input class="form-control form-control-sm text-right-align"
                                       id="ap_account_balance"
                                       tabindex="-1"
                                       name="ap_account_balance"
                                       type="text" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="ap_account_balance_type"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <label class="col-md-6 col-form-label" for="ap_authorized_balance">Authorized
                                Balance</label>
                            <div class="input-group col-md-6 pl-0">
                                <input name="ap_authorized_balance"
                                       class="form-control form-control-sm text-right-align"
                                       value="" tabindex="-1"
                                       id="ap_authorized_balance" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="ap_authorized_balance_type"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--TODO: End receipt and payment section--}}

        <div class=" row">
            <label class="col-md-2 col-form-label" for="d_currency">Currency</label>
            <div class="col-md-6">
                <div class=" row">
                    <input class="form-control form-control-sm col-md-4" id="d_currency" name="d_currency"
                           tabindex="-1" type="text" readonly>
                    <span class="col-md"></span>
                    <label class="col-md-3 col-form-label required" for="d_amount_ccy">Amount CCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" id="d_amount_ccy"
                           name="d_amount_ccy" maxlength="17"
                           oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                           type="text">
                </div>
            </div>
        </div>

        <div class=" row">
            <label class="col-md-2 col-form-label" for="d_exchange_rate">Exchange Rate</label>
            <div class="col-md-6">
                <div class=" row">
                    <input class="form-control form-control-sm col-md-4" id="d_exchange_rate" name="d_exchange_rate"
                           type="text" tabindex="-1"
                           readonly>
                    <span class="col-md"></span>
                    <label class="col-md-3 col-form-label" for="d_amount_lcy">Amount LCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" id="d_amount_lcy"
                           name="d_amount_lcy" min="0" step="0.01" tabindex="-1"
                           type="number" readonly>
                </div>
            </div>
            <div class="col-md-2 ">
                <button class="btn btn-sm btn-info" type="button" onclick="addLineRow(this)" data-type="A"
                        tabindex="-1"
                        data-line="" id="addNewLineBtn"><i
                        class="bx bx-plus-circle font-size-small align-top"></i><span
                        class="align-middle ml-25">ADD</span>
                </button>
            </div>
        </div>

        <div class="row">
            <label class="col-md-2">In Words</label>
            <textarea rows="1" readonly class="form-control form-control-sm col-md-7" id="d_amount_word"
                      tabindex="-1"></textarea>

        </div>
        {{--<div class=" row">
            <label for="d_narration" class="col-md-2 col-form-label required">Narration</label>
            <textarea name="d_narration" class="required form-control form-control-sm col-md-6 " id="d_narration"></textarea>

            <div class="col-md-2 ">
                <button class="btn btn-info" type="button" onclick="addLineRow(this)" data-type="A"
                        data-line="" id="addNewLineBtn"><i class="bx bx-plus-circle"></i>ADD
                </button>
            </div>
        </div>--}}
        <div class="row mt-1">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-hover table-bordered " id="d_account_table">
                    <thead class="thead-dark">
                    <tr>
                        {{--<th width="12%" class="text-center">Account Code</th>
                        <th width="28%" class="text-center">Account Name</th>
                        <th width="5%" class="text-center">Dr/Cr</th>
                        <th width="16%" class="text-center">Amount CCY</th>
                        <th width="16%" class="text-center">Amount LCY</th>
                        <th width="5%" class="text-center">Action</th>--}}

                        <th width="12%" class="text-center">GL Account ID</th>
                        <th width="20%" class="text-center">GL Account Name</th>
                        <th width="12%" class="text-center">Party ID</th>
                        <th width="20%" class="text-center">Party Name</th>
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
                        <td class="text-right-align">Total Amount</td>
                        <td><input type="text" name="total_lcy" id="total_lcy"
                                   class="form-control form-control-sm text-right-align" tabindex="-1"
                                   readonly/></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-1 col-md-12">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">CREDIT ACCOUNT: BY BANK/CASH
        </legend>
        <div class=" row">
            <label class="col-md-2 col-form-label required" for="c_bank_account">Bank Account</label>
            <div class="col-md-6 pl-0 pr-0">
                <select data-gl-acc-id="" class="form-control form-control-sm" id="c_bank_account"
                        name="c_bank_account" required>
                    <option value="">&lt;Select&gt;</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-md-6 col-form-label" for="c_account_balance">Account Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input name="c_account_balance" style="height: auto;"
                               class="form-control form-control-sm text-right-align" value=""
                               tabindex="-1"
                               id="c_account_balance" readonly>
                        <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="c_account_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class=" row">
            <label class="col-md-2 col-form-label" for="c_currency">Currency</label>
            <div class="col-md-6">
                <div class=" row">
                    <input class="form-control form-control-sm col-md-4" id="c_currency" name="c_currency"
                           type="text" tabindex="-1" readonly/>
                    <span class="col-md"></span>
                    <label class="col-md-3 col-form-label required" for="c_amount_ccy">Amount CCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" readonly id="c_amount_ccy"
                           name="c_amount_ccy" maxlength="17" oninput="maxLengthValid(this)" min="0" step="0.01"
                           type="number" tabindex="-1" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class=" row">
                    <label class="col-md-6 col-form-label" for="c_authorized_balance">Authorized Balance</label>
                    <div class="input-group col-md-6 pl-0">
                        <input name="c_authorized_balance" style="height: auto;"
                               class="form-control form-control-sm text-right-align" value="" tabindex="-1"
                               id="c_authorized_balance" readonly>
                        <div class="input-group-append">
                                <span class="input-group-text"
                                      style="font-size: 13px" id="c_authorized_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--</div>--}}
        <div class=" row">
            <label class="col-md-2 col-form-label" for="c_exchange_rate">Exchange Rate</label>
            <div class="col-md-6">
                <div class=" row">
                    <input class="form-control form-control-sm col-md-4" id="c_exchange_rate" name="c_exchange_rate"
                           type="text" tabindex="-1"
                           readonly>
                    <span class="col-md"></span>
                    <label class="col-md-3 col-form-label" for="c_amount_lcy">Amount LCY</label>
                    <input class="form-control form-control-sm col-md-4 text-right-align" id="c_amount_lcy"
                           name="c_amount_lcy" min="0" step="0.01" tabindex="-1"
                           type="number" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <label class="col-md-2">In Words</label>
            <textarea rows="1" readonly class="form-control form-control-sm col-md-6" id="c_amount_word"
                      tabindex="-1"></textarea>
        </div>
    </fieldset>


    <section>
        @include('gl.common_file_upload')
    </section>

    <div class="row mt-1">
        <div class="col-md-12 d-flex">
            <button type="submit" class="btn btn-sm btn-success mr-1" id="paymentFormSubmitBtn" disabled><i
                    class="bx bx-save font-size-small"></i><span class="align-middle ml-25">Save</span></button>
            <button type="button" class="btn btn-sm btn-dark" id="reset_form"><i
                    class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Reset</span></button>
            {{--Print last voucher--}}
            <div class="ml-1" id="print_btn"></div>
            <h6 class="text-primary ml-2">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle ml-25">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '0'}}</span>
            </h6>
        </div>
        {{--<div class="col-md-6 ml-1">
            <h6 class="text-primary">Last Posting Batch ID
                <span class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
            </h6>
            --}}{{--<div class=" row ">
                <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                <input type="text" readonly class="form-control form-control-sm col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
            </div>--}}{{--
        </div>--}}
    </div>
</form>

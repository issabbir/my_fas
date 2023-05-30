<form id="fdr_opening_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <h5 style="text-decoration: underline">FDR OPENING TRANSACTION</h5>
        </div>
        <div class="col-md-7">
            <div class="row">
                <input type="hidden" id="opening_id"
                       value="{{isset($openingInfo) ? $openingInfo->investment_trans_id : ''}}">
                <label for="investment_type" class="col-md-3 col-form-label">Investment Type</label>
                <div class="make-select2-readonly-bg col-md-5">
                    <select class="custom-select form-control form-control-sm select2" name="investment_type"
                            readonly="" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                            required id="investment_type">
                        @foreach($investmentTypes as $type)
                            <option
                                {{old('investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                {{--{{old('investment_type',isset($investmentInfo) ? (($investmentInfo->investment_type_id == $type->investment_type_id) ? 'selected' : '') : '')}} --}}
                                value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>


    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Transaction Reference</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="fiscal_year" class="required col-md-3 col-form-label">Fiscal Year</label>
                    <div class="col-md-5">
                        <select required name="fiscal_year"
                                class="form-control form-control-sm required {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                                id="fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option
                                    {{old('fiscal_year',isset($openingInfo) ? (($openingInfo->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '') : '')}}
                                    value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="period" class="required col-md-3 col-form-label">Posting Period</label>
                    <div class="col-md-5">
                        <select required name="period"
                                data-preperiod="{{isset($openingInfo) ? $openingInfo->trans_period_id : ''}}"
                                class="form-control form-control-sm {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                                id="period">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="posting_date_field" class="required col-md-3 col-form-label ">Posting Date</label>
                    <div class="input-group date posting_date col-md-5 {{isset($openingInfo) ? "make-readonly" : ''}}"
                         id="posting_date"
                         data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="posting_date"
                               id="posting_date_field"
                               class="form-control form-control-sm datetimepicker-input {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                               data-target="#posting_date"
                               data-toggle="datetimepicker"
                               value="{{ old('posting_date', isset($openingInfo) ?  \App\Helpers\HelperClass::dateConvert($openingInfo->trans_date) : '') }}"
                               data-predefined-date="{{ old('posting_date', isset($openingInfo) ?  \App\Helpers\HelperClass::dateConvert($openingInfo->trans_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append posting_date" data-target="#posting_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="document_date_field" class="required col-md-3 col-form-label pr-0">Document Date</label>
                    <div class="input-group date document_date col-md-5 {{isset($openingInfo) ? "make-readonly" : ''}}"
                         id="document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="document_date"
                               id="document_date_field"
                               class="form-control form-control-sm datetimepicker-input {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                               data-target="#document_date"
                               data-toggle="datetimepicker"
                               value="{{ old('document_date', isset($openingInfo) ?  \App\Helpers\HelperClass::dateConvert($openingInfo->document_date) : '') }}"
                               data-predefined-date="{{ old('document_date', isset($openingInfo) ?  \App\Helpers\HelperClass::dateConvert($openingInfo->document_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append document_date" data-target="#document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    {{--0003621: FDR OPENING TRANSACTION- MAKE UI--}}
                    <label for="document_number" class="col-md-3 col-form-label">Document No</label>
                    <div class="col-md-5">
                        <input maxlength="50" type="text" readonly
                               class="form-control form-control-sm make-readonly-bg"
                               oninput="this.value = this.value.toUpperCase()"
                               name="document_number"
                               id="document_number"
                               placeholder="<Auto Generated>"
                               value="{{old('document_number',isset($openingInfo) ?  $openingInfo->document_no : '')}}">
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row justify-content-end">
                    <label for="department" class="col-form-label col-md-4 required ">Dept/Cost Center</label>
                    <div class="col-md-6 {{isset($openingInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="department" class="form-control form-control-sm select2" id="department">
{{--
                            <option value="">&lt;Select&gt;</option>
--}}
                            @foreach($department as $dpt)
                                <option
                                    {{  old('department',isset($openingInfo) ? $openingInfo->department_id : '') ==  $dpt->cost_center_dept_id ? "selected" : "" }}
                                    value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end">
                    <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                    <div class="col-md-6 {{isset($openingInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="bill_section" class="form-control form-control-sm select2"
                                id="bill_section">
                            <option
                                {{  (old('bill_section',isset($openingInfo) ? $openingInfo->bill_sec_id : '') ==  $invBillSec->billSection->bill_sec_id) ? "selected" : "" }}
                                value="{{$invBillSec->billSection->bill_sec_id}}">{{ $invBillSec->billSection->bill_sec_name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end">
                    <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                    <div class="col-md-6 {{isset($openingInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="bill_register" class="form-control form-control-sm select2"
                                data-bill-register-id="{{  old('bill_register',isset($openingInfo) ? $openingInfo->bill_reg_id : '') }}"
                                id="bill_register">
                            <option
                                {{  (old('bill_register',isset($openingInfo) ? $openingInfo->bill_reg_id : '') ==  $invBillReg->bill_reg_id_fdr_opening?? '') ? "selected" : "" }}
                                value="{{$invBillReg->billRegisterForOpening->bill_reg_id ?? ''}}">{{ $invBillReg->billRegisterForOpening->bill_reg_name?? 'N/A'}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="narration" class="required col-md-2 col-form-label" style="max-width: 12.5%">Narration</label>
            <div class="col-md-10">
                    <textarea maxlength="500" required name="narration"
                              class="required form-control form-control-sm {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                              id="narration">{{  old('narration',isset($openingInfo) ? $openingInfo->narration : '') }}</textarea>
            </div>
        </div>
    </fieldset>

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">FDR Information</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label class="col-form-label col-md-3">Investment ID</label>
                    <div class="col-md-5">
                        <input onkeyup="resetFdrInfo()"
                               class="form-control form-control-sm {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                               name="investment_id"
                               placeholder="<ID Number>"
                               value="{{  old('investment_id',isset($openingInfo) ? $openingInfo->investment_id : '') }}"
                               id="investment_id">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-sm searchFdr"
                                id="{{isset($openingInfo) ? "" : 'fdr_search'}}" type="button"
                                {{isset($openingInfo) ? "disabled" : ''}}
                                tabindex="-1"><i class="bx bx-search font-size-small "></i>
                            <span class="align-middle">Search</span>
                        </button>
                    </div>
                </div>

                <div class="row ">
                    <label for="investment_date_field" class="required col-md-3 col-form-label pr-0">Investment
                        Date</label>
                    <div class="input-group col-md-5 make-readonly"
                         id="investment_date">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="investment_date" readonly
                               id="investment_date_field"
                               class="form-control form-control-sm"
                               value=""
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append investment_date">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="bank_id" class="required col-md-3 col-form-label max-w-12">Bank</label>
                    <div
                        class="col-md-9">
                        <select class="form-control form-control-sm make-readonly-bg"
                                name="bank_id" required
                                id="bank_id" readonly>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="branch_id" class="required col-md-3 col-form-label max-w-12">Branch Name </label>
                    <div
                        class="col-md-9">
                        <select class="form-control form-control-sm make-readonly-bg" name="branch_id" required
                                id="branch_id" readonly>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="fdr_number" class="required col-md-3 col-form-label max-w-12">FDR No </label>
                    <div class="col-md-5">
                        <input type="text" id="fdr_number"
                               class="form-control form-control-sm make-readonly-bg"
                               name="fdr_number"
                               placeholder=""
                               value=""/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end">
                    <label for="term_period_type" class="required col-md-4 col-form-label max-w-30">Term Period </label>
                    <div class="col-md-2 pr-0">
                        <input type="text" id="term_period"
                               class="form-control form-control-sm make-readonly-bg"
                               name="term_period"
                               value=""/>
                    </div>

                    <div class="col-md-3">
                        <select class="form-control form-control-sm make-readonly-bg" name="term_period_type"
                                readonly
                                required id="term_period_type">
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label for="term_period_days" class="required col-md-4 col-form-label max-w-30">Term Period
                        (Days) </label>
                    <div class="col-md-5 pr-0">
                        <select name="term_period_days" id="term_period_days"
                                class="form-control col-md-4 form-control-sm make-readonly-bg">
                        </select>
                    </div>
                </div>
                <div class="row  d-flex justify-content-end">
                    <label for="maturity_date_field" class="required col-md-4 col-form-label max-w-30 ">Maturity
                        Date</label>
                    <div class="input-group maturity_date col-md-5 make-readonly"
                         id="maturity_date">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="maturity_date" tabindex="4"
                               readonly
                               id="maturity_date_field"
                               class="form-control form-control-sm"
                               value=""
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append maturity_date">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label for="interest_rate" class="required col-md-4 col-form-label max-w-30">Interest Rate </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="interest_rate"
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="interest_rate"
                                   value=""/>
                            <div class="input-group-append">
                                <div class="input-group-text font-size-small">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="amount" class="required col-md-3 col-form-label max-w-12">Amount </label>
                    <div class="col-md-5">
                        <input type="text" id="amount"
                               class="form-control form-control-sm text-right make-readonly-bg"
                               name="amount"
                               maxlength="20"
                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                               value=""/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end">
                    <label class="col-md-4 col-form-label max-w-30" for="investment_status">Investment Status</label>
                    <div class="col-md-5">
                        <select class="form-control form-control-sm make-readonly-bg" name="investment_status"
                                required id="investment_status">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-form-label col-md-2 max-w-12_5 pr-0">Amount in words</label>
            <div class="pl-1 w-86">
                <textarea rows="2" id="amount_word" class="form-control form-control-sm make-readonly-bg"
                          readonly></textarea>
            </div>
        </div>
    </fieldset>
    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">DEBIT: FDR Investment A/C</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label class="col-md-3 col-form-label" for="account_id">Account ID</label>
                    <div class="col-md-5">
                        <input readonly
                               name="db_account_id" class="form-control form-control-sm make-readonly"
                               value=""
                               type="number"
                               id="db_account_id" oninput="maxLengthValid(this)">
                    </div>
                </div>
                <div class="row">
                    <label for="db_account_name" class="col-md-3 col-form-label">Account Name</label>
                    <div class="col-md-9">
                        <input name="db_account_name" class="form-control form-control-sm make-readonly" value=""
                               id="db_account_name" tabindex="-1"
                               readonly>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="db_account_type">Account Type</label>
                    <div class="col-md-5">
                        <input class="form-control form-control-sm make-readonly" id="db_account_type"
                               name="db_account_type"
                               type="text" readonly tabindex="-1">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end">
                    <label class="col-md-4 col-form-label mx-w-30 pl-2" for="db_account_balance">Account Balance</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input class="form-control form-control-sm text-right-align make-readonly"
                                   id="db_account_balance"
                                   tabindex="-1"
                                   name="db_account_balance"
                                   type="text" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="db_account_balance_type"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label class="col-md-4 col-form-label pl-2" for="db_authorized_balance">Authorized Balance</label>
                    <div class="input-group col-md-5">
                        <input name="db_authorized_balance" style="height: auto;"
                               class="form-control form-control-sm text-right-align make-readonly"
                               value=""
                               tabindex="-1"
                               id="db_authorized_balance" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text" style="font-size: 13px"
                                  id="db_authorized_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">CREDIT: GL ACCOUNT</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="cr_account_name" class="col-md-3 col-form-label">Account Name</label>
                    <div class="col-md-9">
                        <select name="cr_account_name"
                                data-preacc="{{isset($openingInfo) ? $openingInfo->investment_contra_gl_id : ''}}"
                                class="form-control form-control-sm cr_account_name {{isset($openingInfo) ? "make-readonly-bg" : ''}}"
                                id="cr_account_name">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="cr_account_type">Account Type</label>
                    <div class="col-md-5">
                        <input class="form-control form-control-sm" id="cr_account_type" name="cr_account_type"
                               type="text" readonly tabindex="-1">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end">
                    <label class="col-md-4 col-form-label mx-w-30 pl-2" for="cr_account_balance">Account Balance</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input class="form-control form-control-sm text-right-align" id="cr_account_balance"
                                   tabindex="-1"
                                   name="cr_account_balance"
                                   type="text" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" style="font-size: 13px"
                                      id="cr_account_balance_type"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label class="col-md-4 col-form-label pl-2" for="cr_authorized_balance">Authorized Balance</label>
                    <div class="input-group col-md-5">
                        <input name="cr_authorized_balance" style="height: auto;"
                               class="form-control form-control-sm text-right-align"
                               value=""
                               tabindex="-1"
                               id="cr_authorized_balance" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text" style="font-size: 13px"
                                  id="cr_authorized_balance_type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    @if(isset($openingInfo))
        <fieldset class="border pl-1 pr-1">
            <legend class="w-auto" style="font-size: 15px;">TRANSACTION VIEW</legend>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    @include('cm.cm-common.opening_preview_table')
                    {{--<table class="table table-sm table-bordered" id="transaction_view">
                        <thead class="thead-dark">
                            <tr>
                                <th width="12%">GL Account ID</th>
                                <th width="58%">GL Account Name</th>
                                <th width="15%" class="text-right">Debit</th>
                                <th width="15%" class="text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>--}}
                </div>
            </div>
        </fieldset>
    @endif

    <div class="row mt-1">
        <div class="col-md-12 d-flex justify-content-start">

            @if(isset($openingInfo))
                <div class="d-none" id="print_btn"></div>
                <a href="{{route('fdr-opening.index')}}" type="submit" class="btn btn-sm btn-dark mr-1">
                    <i
                        class="bx bx-arrow-back font-size-small align-top"></i><span
                        class="align-middle m-25">Back</span>
                </a>

            @else
                <button type="button" class="btn btn-sm btn-dark mr-1" id="fdr_preview">
                    <i
                        class="bx bx-show font-size-small align-top"></i><span class="align-middle m-25">Preview</span>
                </button>
                <button type="submit" class="btn btn-sm btn-success mr-1" id="fdr_opening_submit">
                    <i
                        class="bx bx-save font-size-small align-top"></i><span class="align-middle m-25">Save</span>
                </button>

                <button type="button" class="btn btn-sm btn-dark" id="reset_form">
                    <i class="bx bx-reset font-size-small align-top"></i><span class="align-middle ml-25">Reset</span>
                </button>
                <a href="#" class="btn btn-sm btn-info ml-1 d-none" id="print_btn">
                    <i
                        class="bx bx-printer font-size-small align-top"></i><span class="align-middle m-25">Print</span>
                </a>
            @endif

        </div>
    </div>
</form>

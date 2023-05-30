<form id="fdr_maturity_form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <h5 style="text-decoration: underline">FDR MATURITY TRANSACTION</h5>
        </div>

    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <input type="hidden" id="maturity_id"
                       value="{{isset($maturityInfo) ? $maturityInfo->maturity_trans_id : ''}}">
                <label for="transaction_type" class="col-md-3 col-form-label ml-1">Transaction Type</label>
                <div class="col-md-8 pl-0">
                    <select class="form-control form-control-sm" name="transaction_type"
                            {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                            required id="transaction_type">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($transactionTypes as $type)
                            <option
                                value="{{$type->maturity_trans_type_id}}">{{$type->maturity_trans_type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <input type="hidden" id="maturity_id"
                       value="{{isset($maturityInfo) ? $maturityInfo->maturity_trans_id : ''}}">
                <label for="investment_type" class="col-md-5 col-form-label">Investment Type</label>
                <div class="make-select2-readonly-bg col-md-6 pl-0 pr-0">
                    <select class="custom-select form-control form-control-sm select2" name="investment_type"
                            readonly="" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                            required id="investment_type">
                        @foreach($investmentTypes as $type)
                            <option
                                {{old('investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                data-autorenfl="{{$type->fdr_auto_renewal_flag}}"
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
                <div class="form-group row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="fiscal_year" class="required col-md-3 col-form-label">Fiscal Year</label>
                    <div class="col-md-5">
                        <select required name="fiscal_year"
                                class="form-control form-control-sm required {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                                id="fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option
                                    {{old('fiscal_year',isset($maturityInfo) ? (($maturityInfo->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '') : '')}}
                                    value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="period" class="required col-md-3 col-form-label">Posting Period</label>
                    <div class="col-md-5">
                        <select required name="period"
                                data-preperiod="{{isset($maturityInfo) ? $maturityInfo->trans_period_id : ''}}"
                                class="form-control form-control-sm {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                                id="period">
                        </select>
                    </div>
                </div>
                <div class="row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="posting_date_field" class="required col-md-3 col-form-label ">Posting Date</label>
                    <div class="input-group date posting_date col-md-5 {{isset($maturityInfo) ? "make-readonly" : ''}}"
                         id="posting_date"
                         data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="posting_date"
                               id="posting_date_field"
                               class="form-control form-control-sm datetimepicker-input {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                               data-target="#posting_date"
                               data-toggle="datetimepicker"
                               value="{{ old('posting_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->trans_date) : '') }}"
                               data-predefined-date="{{ old('posting_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->trans_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append posting_date" data-target="#posting_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="document_date_field" class="required col-md-3 col-form-label pr-0">Document Date</label>
                    <div class="input-group date document_date col-md-5 {{isset($maturityInfo) ? "make-readonly" : ''}}"
                         id="document_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="document_date"
                               id="document_date_field"
                               class="form-control form-control-sm datetimepicker-input {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                               data-target="#document_date"
                               data-toggle="datetimepicker"
                               value="{{ old('document_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->document_date) : '') }}"
                               data-predefined-date="{{ old('document_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->document_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append document_date" data-target="#document_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="document_number"
                           class="required col-md-3 col-form-label">Document No</label>
                    <div class="col-md-5">
                        <input maxlength="50" type="text" required
                               class="form-control form-control-sm {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                               oninput="this.value = this.value.toUpperCase()"
                               name="document_number"
                               id="document_number"
                               value="{{old('document_number',isset($maturityInfo) ?  $maturityInfo->document_no : '')}}">
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row justify-content-end {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="department" class="col-form-label col-md-4 required ">Dept/Cost Center</label>
                    <div class="col-md-6 {{isset($maturityInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="department" class="form-control form-control-sm select2" id="department">
                            {{--
                                                        <option value="">&lt;Select&gt;</option>
                            --}}
                            @foreach($department as $dpt)
                                <option
                                    {{  old('department',isset($maturityInfo) ? $maturityInfo->department_id : '') ==  $dpt->cost_center_dept_id ? "selected" : "" }}
                                    value="{{$dpt->cost_center_dept_id}}"> {{ $dpt->cost_center_dept_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                    <div class="col-md-6 {{isset($maturityInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="bill_section" class="form-control form-control-sm select2"
                                id="bill_section">
                            <option
                                {{  (old('bill_section',isset($maturityInfo) ? $maturityInfo->bill_sec_id : '') ==  $invBillSec->billSection->bill_sec_id) ? "selected" : "" }}
                                value="{{$invBillSec->billSection->bill_sec_id}}">{{ $invBillSec->billSection->bill_sec_name}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex justify-content-end {{isset($maturityInfo) ? "make-readonly" : ''}}">
                    <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                    <div class="col-md-6 {{isset($maturityInfo) ? "make-select2-readonly-bg" : ''}}">
                        <select required name="bill_register" class="form-control form-control-sm select2"
                                data-bill-register-id="{{  old('bill_register',isset($maturityInfo) ? $maturityInfo->bill_reg_id : '') }}"
                                id="bill_register">
                            <option
                                {{  (old('bill_register',isset($maturityInfo) ? $maturityInfo->bill_reg_id : '') ==  $invBillReg->bill_reg_id_fdr_maturity?? '') ? "selected" : "" }}
                                value="{{$invBillReg->billRegisterForMaturity->bill_reg_id ?? ''}}">{{ $invBillReg->billRegisterForMaturity->bill_reg_name?? 'N/A'}}
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
                              class="required form-control form-control-sm {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                              id="narration">{{  old('narration',isset($maturityInfo) ? $maturityInfo->narration : '') }}</textarea>
            </div>
        </div>
    </fieldset>

    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">FDR Information</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label class="col-form-label col-md-3 required">Investment ID</label>
                    <div class="col-md-5">
                        <input required onkeyup="resetFdrInformation(); resetContraInformation(); resetPOInformation(); resetCurrentInformation();"
                               class="form-control form-control-sm {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                               name="investment_id"
                               placeholder="<ID Number>"
                               value="{{  old('investment_id',isset($maturityInfo) ? $maturityInfo->investment_id : '') }}"
                               id="investment_id">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-sm searchFdr"
                                id="{{isset($maturityInfo) ? "" : 'fdr_search'}}" type="button"
                                {{isset($maturityInfo) ? "disabled" : ''}}
                                tabindex="-1"><i class="bx bx-search font-size-small "></i>
                            <span class="align-middle">Search</span>
                        </button>
                    </div>
                </div>
                <div class="row ">
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
                        <input type="text" id="fdr_number" readonly
                               class="form-control form-control-sm make-readonly-bg"
                               name="fdr_number"
                               placeholder=""
                               value=""/>
                    </div>
                </div>
                <div class="row">
                    <label for="amount" class="required col-md-3 col-form-label max-w-12">Amount </label>
                    <div class="col-md-5">
                        <input type="text" id="amount" readonly
                               class="form-control form-control-sm text-right make-readonly-bg"
                               name="amount"
                               maxlength="20"
                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                               value=""/>
                    </div>
                </div>
                <div class="row">
                    <label for="amount_word" class="col-form-label col-md-3 max-w-12 pr-0">Amount in words</label>
                    <div class="col-md-9">
                        <textarea rows="2" id="amount_word" class="form-control form-control-sm make-readonly-bg"
                          readonly></textarea>
                    </div>
                </div>
                <div class="row make-readonly">
                    <label class="col-md-3 col-form-label max-w-12 pr-0" for="investment_status">Investment
                        Status</label>
                    <div class="col-md-5">
                        <select readonly="" class="form-control form-control-sm make-readonly-bg" name="investment_status"
                                required id="investment_status">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end">
                    <label for="investment_date_field" class="required col-md-4 col-form-label max-w-30">Investment
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
                <div class="row d-flex justify-content-end">
                    <label for="term_period_type" class="required col-md-4 col-form-label max-w-30">Term Period </label>
                    <div class="col-md-2 pr-0">
                        <input type="text" id="term_period" readonly
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
                    <label for="term_period_days" class="required col-md-6 col-form-label max-w-30">Term Period Type </label>
                    <div class="col-md-5 ">
                        <select name="term_period_days" id="term_period_days" readonly=""
                                class="form-control  form-control-sm make-readonly-bg">
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
                            <input type="text" id="interest_rate" readonly
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
                <div class="row mt-1">
                    <div class="col-md-10 text-right">
                        <h5 style="text-decoration: underline">Last Renewal Information:</h5>
                    </div>
                </div>
                <div class="row  d-flex justify-content-end">
                    <label for="last_renewal_date_field" class="col-md-4 col-form-label max-w-30 ">Renewal
                        Date</label>
                    <div class="input-group last_renewal_date col-md-5 make-readonly"
                         id="last_renewal_date">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="last_renewal_date" tabindex="4"
                               readonly
                               id="last_renewal_date_field"
                               class="form-control form-control-sm"
                               value=""
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append last_renewal_date">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label for="last_renewal_amount" class="col-md-4 col-form-label max-w-30">Renewal Amount </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="last_renewal_amount" readonly
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="last_renewal_amount"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row  d-flex justify-content-end">
                    <label for="last_maturity_date_field" class="col-md-4 col-form-label max-w-30 ">Maturity
                        Date</label>
                    <div class="input-group maturity_date col-md-5 make-readonly"
                         id="last_maturity_date">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="last_maturity_date" tabindex="4"
                               readonly
                               id="last_maturity_date_field"
                               class="form-control form-control-sm"
                               value=""
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append last_maturity_date">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label for="last_interest_rate" class="col-md-4 col-form-label max-w-30">Interest Rate </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="last_interest_rate" readonly
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="last_interest_rate"
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
    </fieldset>
    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Interest Information at Maturity</legend>
        <div class="row d-flex justify-content-end">
            <div class="col-md-3 text-right">
                <div class="form-group">
                    <label for="maturity_gross_interest" class="col-form-label">Gross Interest</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" name="maturity_gross_interest" readonly
                               id="maturity_gross_interest">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="form-group">
                    <label for="maturity_source_tax" class="col-form-label">Sources Tax</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" name="maturity_source_tax"
                               oninput="this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               id="maturity_source_tax">
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="form-group">
                    <label for="maturity_excise_duty" class="col-form-label">Excise Duty</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" name="maturity_excise_duty"
                               oninput="this.value = this.value.match(/\d+\.?\d{0,2}/);"
                               id="maturity_excise_duty">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="form-group">
                    <label for="maturity_net_interest" class="col-form-label">Net Interest</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" name="maturity_net_interest" readonly
                               id="maturity_net_interest">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Last Year Interest Provision Information</legend>
        <div class="row d-flex justify-content-end">
            <div class="col-md-2 text-right">
                <div class="form-group">
                    <label for="last_pro_days" class="col-form-label">No Of Days</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm" readonly
                               name="last_pro_days"
                               id="last_pro_days">
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="form-group">
                    <label for="last_pro_gross_interest" class="col-form-label">Gross Interest</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" readonly
                               name="last_pro_gross_interest"
                               id="last_pro_gross_interest">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="form-group">
                    <label for="last_pro_source_tax" class="col-form-label">Sources Tax</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" readonly
                               name="last_pro_source_tax" id="last_pro_source_tax">
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="form-group">
                    <label for="last_pro_excise_duty" class="col-form-label">Excise Duty</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" readonly
                               name="last_pro_excise_duty" id="last_pro_excise_duty">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="form-group">
                    <label for="last_pro_net_interest" class="col-form-label">Net Interest</label>
                    <div class="input-group">
                        <input class="form-control form-control-sm text-right" name="last_pro_net_interest" readonly
                               id="last_pro_net_interest">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="border pl-1 pr-1 d-none" id="crInformation">
        <legend class="w-auto" style="font-size: 15px;">Current Renewal Information</legend>
        <div class="row">
            <div class="col-md-3">
                <label for="current_renewal_date_field" class="col-form-label">Renewal
                    Date</label>
                <div
                    class="input-group date current_renewal_date make-readonly"
                    id="current_renewal_date"
                    data-target-input="nearest">
                    <input required type="text" autocomplete="off" onkeydown="return false"
                           name="current_renewal_date" readonly
                           id="current_renewal_date_field"
                           class="form-control form-control-sm {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                           data-target="#current_renewal_date"
                           data-toggle="datetimepicker"
                           value="{{ old('posting_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->trans_date) : '') }}"
                           data-predefined-date="{{ old('posting_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->trans_date) : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append current_renewal_date" data-target="#current_renewal_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label for="current_renewal_amount" class="col-form-label">Renewal Amount </label>
                <div class="">
                    <div class="input-group">
                        <input type="text" id="current_renewal_amount" readonly
                               class="form-control form-control-sm text-right make-readonly-bg"
                               name="current_renewal_amount"
                               value=""/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label for="current_maturity_date_field"
                       class="col-form-label">Maturity
                    Date</label>
                <div class="input-group current_maturity_date make-readonly"
                     id="current_maturity_date">
                    <input required type="text" autocomplete="off"
                           onkeydown="return false"
                           name="current_maturity_date" tabindex="4"
                           readonly
                           id="current_maturity_date_field"
                           class="form-control form-control-sm"
                           value=""
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append current_maturity_date">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label for="current_interest_rate" class="col-form-label required">Interest Rate </label>
                <div class="">
                    <div class="input-group">
                        <input  type="text" id="current_interest_rate"
                               class="form-control form-control-sm text-right text-black"
                               style="background-color: #f6e763"
                               name="current_interest_rate"
                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
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
    </fieldset>
    <fieldset class="border pl-1 pr-1 d-none" id="crsInformation">
        <legend class="w-auto" style="font-size: 15px;">Current Renewal With New Split Information</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="new_fdr_no" class="col-md-3 col-form-label max-w-30 pr-0">New FDR Number </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="new_fdr_no"
                                   class="form-control form-control-sm"
                                   name="new_fdr_no"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="new_amount" class="col-md-3 col-form-label max-w-30">Amount </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="new_amount"
                                   class="form-control form-control-sm text-right"
                                   name="new_amount"
                                   maxlength="20"
                                   oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                                   value=""/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button data-type="A" data-line="" class="btn btn-sm bg-info" id="addNewLineBtn" type="button"
                                onclick="addLineRow(this)">
                            <i class="bx bx-plus-circle font-size-small"></i>
                            <span class="align-middle">ADD</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <label for="new_amount_word" class="col-md-2 col-form-label max-w-12_5 pr-0">Amount In Words</label>
            <div class="col-md-9">
                <div class="input-group">
                    <input type="text" id="new_amount_word" readonly
                           class="form-control form-control-sm"
                           name="new_amount_word"
                           value=""/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-bordered" id="splitted_fdr_table">
                    <thead class="thead-dark">
                    <tr>
                        <th>Investment Date</th>
                        <th>FDR No</th>
                        <th>Amount</th>
                        <th>Interest Rate</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">Total</td>
                        <td><input type="text" name="total_fdr_amount" id="total_fdr_amount"
                                   class="form-control form-control-sm text-right-align"
                                   readonly tabindex="-1"/></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </fieldset>
    <fieldset class="border pl-1 pr-1" id="po_section">
        <legend class="w-auto" style="font-size: 15px;">P.O. Amount</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="po_number" class="col-md-3 col-form-label max-w-30 required">P.O. Number </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="po_number"
                                   class="form-control form-control-sm"
                                   name="po_number"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="po_date_field" class="col-md-3 col-form-label max-w-30 required">P.O. Date</label>
                    <div class="input-group date po_date col-md-5 {{isset($maturityInfo) ? "make-readonly" : ''}}"
                         id="po_date"
                         data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false"
                               name="po_date"
                               id="po_date_field"
                               class="form-control form-control-sm datetimepicker-input {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
                               data-target="#po_date"
                               data-toggle="datetimepicker"
                               value="{{ old('po_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->document_date) : '') }}"
                               data-predefined-date="{{ old('po_date', isset($maturityInfo) ?  \App\Helpers\HelperClass::dateConvert($maturityInfo->document_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append po_date" data-target="#po_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row d-flex justify-content-end {{--make-readonly--}}">
                    <label for="po_principal_amount" class="col-md-3 col-form-label max-w-30 pr-0">Principal Amount </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="po_principal_amount" readonly
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="po_principal_amount"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <label for="po_interest_amount" class="col-md-3 col-form-label max-w-30">Interest Amount </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="po_interest_amount" readonly
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="po_interest_amount" oninput="this.value = this.value.match(/\d+\.?\d{0,2}/);"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end make-readonly">
                    <label for="total_po_amount" class="col-md-3 col-form-label max-w-30">Total Amount </label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="total_po_amount"
                                   class="form-control form-control-sm text-right make-readonly-bg"
                                   name="total_po_amount"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="total_po_amount_word" class="col-form-label col-md-7 text-right">Amount In Words</label>
                    <div class="input-group col-md-5">
                        <textarea type="text" id="total_po_amount_word" rows="3" readonly
                               class="form-control form-control-sm pl-0 pr-0 make-readonly-bg"
                               name="total_po_amount_word"></textarea>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
    <fieldset class="border pl-1 pr-1">
        <legend class="w-auto" style="font-size: 15px;">Contra Account(Bank/Other GL)</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label for="cr_account_name" class="col-md-3 col-form-label required">Account Name</label>
                    <div class="col-md-9">
                        <select name="cr_account_name" required
                                data-preacc="{{isset($maturityInfo) ? $maturityInfo->investment_contra_gl_id : ''}}"
                                class="form-control form-control-sm cr_account_name {{isset($maturityInfo) ? "make-readonly-bg" : ''}}"
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
                    <label class="col-md-3 col-form-label max-w-30 pr-0" for="cr_account_balance">Account
                        Balance</label>
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
                    <label class="col-md-3 col-form-label max-w-30 pr-0" for="cr_authorized_balance">Auth.
                        Balance</label>
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
    <fieldset class="border pl-1 pr-1 d-none" id="transactionView">
        <legend class="w-auto" style="font-size: 15px;">TRANSACTION VIEW</legend>
        <div class="row">
            <div class="col-md-12 table-responsive">
            </div>
        </div>
    </fieldset>

    <div class="row mt-1">
        <div class="col-md-12 d-flex justify-content-start">
            @if(isset($maturityInfo))
                <div class="d-none" id="print_btn"></div>
                <a href="{{route('fdr-maturity.index')}}" type="submit" class="btn btn-sm btn-dark mr-1">
                    <i
                        class="bx bx-arrow-back font-size-small align-top"></i><span
                        class="align-middle m-25">Back</span>
                </a>

            @else
                <button type="button" class="btn btn-sm btn-dark mr-1" disabled id="maturity_preview">
                    <i
                        class="bx bx-show font-size-small align-top"></i><span class="align-middle m-25">Preview</span>
                </button>
                <button type="button" class="btn btn-sm btn-dark mr-1" disabled id="chalan_preview">
                    <i
                        class="bx bx-show font-size-small align-top"></i><span class="align-middle m-25">Chalan Preview</span>
                </button>
                <button type="submit" class="btn btn-sm btn-success mr-1" disabled id="fdr_maturity_submit_btn">
                    <i class="bx bx-save font-size-small align-top"></i><span class="align-middle m-25">Save</span>
                </button>

                <button type="button" class="btn btn-sm btn-dark mr-1" id="reset_form">
                    <i class="bx bx-reset font-size-small align-top"></i><span class="align-middle ml-25">Reset</span>
                </button>
                <div class="" id="voucher_print_btn"></div>
                <div class="d-none" id="print_btn"></div>
                {{--<a href="#" class="btn btn-sm btn-info ml-1 d-none" id="print_btn">
                    <i
                        class="bx bx-printer font-size-small align-top"></i><span class="align-middle m-25">Print</span>
                </a>--}}
            @endif

        </div>
    </div>
</form>

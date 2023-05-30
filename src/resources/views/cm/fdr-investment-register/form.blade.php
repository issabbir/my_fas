<div class="card">
    <div class="card-body">
        <h4><span class="border-bottom-secondary border-bottom-2">FDR INVESTMENT REGISTER</span></h4>
        <form id="investment_register"
              @if(isset($investmentInfo->investment_id))
              action="{{route('fdr-register.update',[$investmentInfo->investment_id])}}"
              @else
              action="{{route('fdr-register.store')}}"
              @endif
              method="post">
            @csrf
            @if (isset($investmentInfo->investment_id))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-2">
                    <label for="investment_type" class=" col-form-label">Investment Type</label>
                    <div class="  make-select2-readonly-bg">
                        <select class="custom-select form-control form-control-sm select2" name="investment_type"
                                readonly="" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                required id="investment_type">
                            @foreach($investmentTypes as $type)
                                <option
                                    {{old('investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                    {{--{{old('investment_type',isset($investmentInfo) ? (($investmentInfo->investment_type_id == $type->investment_type_id) ? 'selected' : '') : '')}}--}}
                                    value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="fiscal_year" class="required col-form-label">Fiscal Year</label>
                    <div
                        class=" {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2" name="fiscal_year"
                                required
                                {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}} id="fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option
                                    {{old('fiscal_year',isset($investmentInfo) ? (($investmentInfo->fiscal_year_id == $year->fiscal_year_id) ? 'selected' : '') : '')}}
                                    value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="posting_period" class="required col-form-label">Posting Period</label>
                    <div
                        class=" {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2" name="posting_period"
                                data-preperiod="{{isset($investmentInfo) ? $investmentInfo->posting_period_id : ''}}"
                                required
                                id="posting_period" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}>
                        </select>
                    </div>
                </div>

            </div>
            <fieldset class="border pl-2 pr-2 pb-1 mt-1">
                <legend class="w-auto text-bold-600" style="font-size: 14px;">BASIC INFORMATION</legend>
                <div class="row">
                    <label for="investment_id" class="col-md-2 col-form-label max-w-15">Investment ID </label>
                    <div class="col-md-3">
                        <input type="text" id="investment_id" class="form-control form-control-sm" name="investment_id"
                               placeholder="<Auto Generated>" disabled
                               value="{{old('investment_id',(isset($investmentInfo) ? $investmentInfo->investment_id : ''))}}"/>
                    </div>
                    <div class="col-md-1"></div>
                    <label class="col-md-2 col-form-label text-right" for="investment_status">Investment Status</label>
                    <div
                        class="col-md-3 {{--{{isset($mode) ? (($mode[1] == 'v') ? '--}}make-select2-readonly-bg{{--': '') : ''}}--}}">
                        <select class="custom-select form-control form-control-sm select2" name="investment_status"
                                required id="investment_status">
                            @foreach($investmentStatus as $status)
                                <option
                                    {{isset($investmentInfo) ? (($investmentInfo->investment_status_id == $type->period_type_code) ? 'selected' : '') : ''}} value="{{$status->investment_status_id}}">{{$status->investment_status_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row ">
                    <label for="investment_date_field" class="required col-md-2 col-form-label max-w-15">Investment
                        Date</label>
                    <div class="input-group date investment_date col-md-3"
                         id="investment_date" {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly': '') : ''}}
                         data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="investment_date" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                               id="investment_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#investment_date"
                               data-toggle="datetimepicker"
                               value="{{ old('investment_date', isset($investmentInfo) ?  \App\Helpers\HelperClass::dateConvert($investmentInfo->investment_date) : '') }}"
                               data-predefined-date="{{ old('investment_date', isset($investmentInfo) ?  \App\Helpers\HelperClass::dateConvert($investmentInfo->investment_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append investment_date" data-target="#investment_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="bank_id" class="required col-md-2 col-form-label max-w-15">Bank</label>
                    <div
                        class="col-md-5  {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2"
                                name="bank_id" required
                                id="bank_id" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                data-cm-bank-id="{{old('bank_id',isset($investmentInfo) ? $investmentInfo->bank_code : '')}}">
                            <option value="">&lt;Select&gt;</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="branch_id" class="required col-md-2 col-form-label max-w-15">Branch Name </label>
                    <div
                        class="col-md-5  {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2" name="branch_id" required
                                id="branch_id" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                data-prebranch="{{old('branch_id',isset($investmentInfo) ? $investmentInfo->branch_code : '')}}">
                            <option value="">&lt;Select&gt;</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="fdr_number" class="required col-md-2 col-form-label max-w-15">FDR Number </label>
                    <div class="col-md-3  ">
                        <input type="text" id="fdr_number"
                               class="form-control form-control-sm {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               name="fdr_number"
                               placeholder=""
                               value="{{old('fdr_number',isset($investmentInfo) ? $investmentInfo->fdr_no : '')}}"/>
                    </div>
                </div>
                <div class="row">
                    <label for="amount" class="required col-md-2 col-form-label max-w-15">Amount </label>
                    <div class="col-md-3  ">
                        <input type="text" id="amount"
                               class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               name="amount"
                               maxlength="20"
                               oninput="this.value = this.value.match(/[0-9,]+\.?\d{0,2}/);"
                               value="{{old('amount',isset($investmentInfo) ? \App\Helpers\HelperClass::getCommaSeparatedValue($investmentInfo->investment_amount) : '')}}"/>
                    </div>
                </div>
                <div class="row">
                    <label class="col-form-label col-md-2 max-w-15">Amount in words</label>
                    <div class="col-md-9">
                        <textarea id="amount_word" class="form-control form-control-sm make-readonly-bg"
                          readonly></textarea>
                    </div>
                </div>

                <div class="row">
                    <label for="term_period_type" class="required col-md-2 col-form-label max-w-15">Term Period </label>
                    <div class="col-md-1 pr-0">
                        <input type="text" id="term_period"
                               class="form-control form-control-sm make-readonly-bg"
                               name="term_period"
                               value="{{old('term_period',isset($investmentInfo) ? $investmentInfo->term_period_no : '1')}}"/>
                    </div>

                    <div class="col-md-2 make-select2-readonly-bg">
                        <select class="custom-select form-control form-control-sm select2" name="term_period_type"
                                {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                required id="term_period_type">
                            @foreach($periodTypes as $type)
                                <option
                                    {{((isset($investmentInfo) ? $investmentInfo->term_period_code : \App\Enums\Common\LPeriodType::YEAR) == $type->period_type_code) ? 'selected' : ''}} value="{{$type->period_type_code}}">{{$type->period_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label for="term_period_days" class="required col-md-2 col-form-label max-w-15">Term Period Type </label>
                    <div class="col-md-3  ">
                        <select name="term_period_days" id="term_period_days"
                                class="form-control form-control-sm {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}">
{{--                            @foreach(\App\Enums\Cm\FdrTermPeriodDays::TERM_DAYS as $term)--}}
{{--                                <option--}}
{{--                                    {{(old('term_period_days',isset($investmentInfo) ? $investmentInfo->term_period_days : '') == $term['period']) ? 'selected' : ''}} value="{{$term['period']}}">--}}
{{--                                    {{$term['period']}}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
                            <option value="{{App\Enums\Cm\FdrTermPeriodDays::A}}" {{(old('term_period_days',isset($investmentInfo) ? $investmentInfo->term_period_type : '') == 'A') ? 'selected' : ''}}>{{App\Enums\Cm\FdrTermPeriodDays::Actual}}</option>
                            <option value="{{App\Enums\Cm\FdrTermPeriodDays::F}}" {{(old('term_period_days',isset($investmentInfo) ? $investmentInfo->term_period_type : '') == 'F') ? 'selected' : ''}}>{{App\Enums\Cm\FdrTermPeriodDays::Flat}}</option>
                        </select>
                        {{--<input type="text" id="term_period_days"
                               class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               name="term_period_days"
                               maxlength="3"
                               oninput="this.value = this.value.match(/[0-9,]+/);"
                               value="{{old('term_period_days',isset($investmentInfo) ? $investmentInfo->term_period_days : '365')}}"/>--}}
                    </div>
                </div>
                <div class="row ">
                    <label for="maturity_date_field" class="required col-md-2 col-form-label max-w-15 ">Maturity
                        Date</label>
                    <div
                        class="input-group date maturity_date col-md-3 make-readonly"
                        id="maturity_date"
                        data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               name="maturity_date" tabindex="4"
                               readonly
                               id="maturity_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#maturity_date"
                               data-toggle="datetimepicker"
                               value="{{ old('maturity_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->maturity_date) : '' ) }}"
                               data-predefined-date="{{ old('maturity_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->maturity_date) : '' ) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append maturity_date" data-target="#maturity_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="interest_rate" class="required col-md-2 col-form-label max-w-15">Interest Rate </label>
                    <div class="col-md-3 ">
                        <div class="input-group">
                            <input type="text" id="interest_rate"
                                   class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                                   name="interest_rate"
                                   value="{{old('interest_rate',isset($investmentInfo) ? $investmentInfo->interest_rate : '')}}"/>
                            <div class="input-group-append" data-target="#interest_rate">
                                <div class="input-group-text font-size-small">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-md-1"></div>
                    <label class="col-md-2 col-form-label text-right" for="investment_status">Investment Status</label>
                    <div class="col-md-3 --}}{{--{{isset($mode) ? (($mode[1] == 'v') ? '--}}{{--make-select2-readonly-bg--}}{{--': '') : ''}}--}}{{--">
                        <select class="custom-select form-control form-control-sm select2" name="investment_status"
                                required id="investment_status">
                            @foreach($investmentStatus as $status)
                                <option
                                    {{isset($investmentInfo) ? (($investmentInfo->investment_status_id == $type->period_type_code) ? 'selected' : '') : ''}} value="{{$status->investment_status_id}}">{{$status->investment_status_name}}</option>
                            @endforeach
                        </select>
                    </div>--}}
                </div>
                {{--<div class="row">
                    <label class="col-md-2 col-form-label max-w-15" for="investment_status">Investment Status</label>
                    <div class="col-md-3 {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2" name="investment_status"
                                required id="investment_status">
                            @foreach($investmentStatus as $status)
                                <option
                                    {{isset($investmentInfo) ? (($investmentInfo->investment_status_id == $type->period_type_code) ? 'selected' : '') : ''}} value="{{$status->investment_status_id}}">{{$status->investment_status_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}
            </fieldset>
            <fieldset
                class="border pl-2 pr-2 pb-1 mt-1 {{ isset($investmentInfo) ? (($investmentInfo->investment_status_id == \App\Enums\Common\LFdrInvestmentStatus::RENEWED) ? '' : 'd-none'):'d-none'}}">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">RENEWAL INFORMATION</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label class="col-form-label" for="renewal_date_field">Renewal Date</label>
                        <div
                            class="input-group mb-1 date renewal_date  {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly': '') : ''}}"
                            id="renewal_date"
                            data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   name="renewal_date" tabindex="4"
                                   {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                   id="renewal_date_field" readonly
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#renewal_date"
                                   data-toggle="datetimepicker"
                                   value="{{ old('renewal_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->renewal_date) : '' ) }}"
                                   data-predefined-date="{{ old('renewal_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->renewal_date) : '' ) }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append renewal_date" data-target="#renewal_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="current_renewal_amount" class="col-form-label">Renewal Amount </label>
                        <div class="">
                            <div class="input-group">
                                <input type="text" id="current_renewal_amount" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                       class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                                       name="renewal_amount"
                                       value="{{old('renewal_amount',isset($investmentInfo) ? $investmentInfo->renewal_amount : '')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="col-form-label" for="renewal_maturity_date_field">MaturityDate</label>
                        <div
                            class="input-group mb-1 date renewal_maturity_date {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly': '') : ''}}"
                            id="renewal_maturity_date"
                            data-target-input="nearest">
                            <input type="text" autocomplete="off" onkeydown="return false"
                                   name="renewal_maturity_date" tabindex="4"
                                   {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                   id="renewal_maturity_date_field"
                                   class="form-control form-control-sm datetimepicker-input"
                                   data-target="#renewal_maturity_date"
                                   data-toggle="datetimepicker"
                                   value="{{ old('renewal_maturity_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->renewal_maturity_date) : '' ) }}"
                                   data-predefined-date="{{ old('renewal_maturity_date',isset($investmentInfo) ? \App\Helpers\HelperClass::dateConvert($investmentInfo->renewal_maturity_date) : '' ) }}"
                                   placeholder="DD-MM-YYYY">
                            <div class="input-group-append renewal_maturity_date" data-target="#renewal_maturity_date"
                                 data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="bx bx-calendar font-size-small"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="renewal_interest_rate" class="col-form-label">Interest Rate </label>
                        <div class="input-group">
                            <input type="text" id="renewal_interest_rate"
                                   class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                                   name="renewal_interest_rate" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                   value="{{old('renewal_interest_rate',isset($investmentInfo) ? $investmentInfo->renewal_interest_rate : '')}}"/>
                            <div class="input-group-append" data-target="#renewal_interest_rate">
                                <div class="input-group-text font-size-small">%</div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="row">
                    <label for="renewal_term_period" class="col-md-2 col-form-label max-w-15">Term Period </label>
                    <div class="col-md-1 ">
                        <input type="text" id="renewal_term_period"
                               class="form-control form-control-sm {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               readonly name="renewal_term_period"
                               value="{{old('renewal_term_period',isset($investmentInfo) ? $investmentInfo->renewal_term_period_no : '1')}}"/>
                    </div>
                    <div class="col-md-2 {{isset($mode) ? (($mode[1] == 'v') ? 'make-select2-readonly-bg': '') : ''}}">
                        <select class="custom-select form-control form-control-sm select2"
                                name="renewal_term_period_type"
                                id="renewal_term_period_type">
                            <option value="">Select</option>
                            @foreach($periodTypes as $type)
                                <option
                                    {{(isset($investmentInfo) ? (($investmentInfo->renewal_term_period_code == $type->period_type_code) ? 'selected' : '') : '')}} value="{{$type->period_type_code}}">{{$type->period_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}
                {{--<div class="row">
                    <label for="renewal_term_period_days" class="required col-md-2 col-form-label max-w-15">Term Period
                        (Days) </label>
                    <div class="col-md-1  ">
                        <select name="renewal_term_period_days" id="renewal_term_period_days"
                                class="form-control form-control-sm {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}">
                            @foreach(\App\Enums\Cm\FdrTermPeriodDays::TERM_DAYS as $term)
                                <option
                                    {{(old('renewal_term_period_days',isset($investmentInfo) ? $investmentInfo->renewal_term_period_days : '') == $term['period']) ? 'selected' : ''}} value="{{$term['period']}}">
                                    {{$term['period']}}
                                </option>
                            @endforeach
                        </select>
                        --}}{{--<input type="text" id="renewal_term_period_days"
                               class="form-control form-control-sm text-right {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               name="renewal_term_period_days"
                               maxlength="3"
                               oninput="this.value = this.value.match(/[0-9,]+/);"
                               value="{{old('renewal_term_period_days',isset($investmentInfo) ? $investmentInfo->renewal_term_period_days : '365')}}"/>--}}{{--
                    </div>
                </div>--}}
            </fieldset>
            <fieldset class="border pl-2 pr-2 pb-1 mt-1">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">INVESTMENT GL MAPPING</legend>
                <div class="row">
                    <label class="required col-md-2 col-form-label max-w-15" for="account_id">Account ID</label>
                    <div class=" col-md-3 ">
                        <input name="account_id"
                               class="form-control form-control-sm {{isset($mode) ? (($mode[1] == 'v') ? 'make-readonly-bg': '') : ''}}"
                               id="account_id" maxlength="10" type="number" oninput="maxLengthValid(this)"
                               onfocusout="addZerosInAccountId(this)"
                               onkeyup="resetField(['#account_type','#account_name'])"
                               value="{{old('account_id',isset($investmentInfo) ? $investmentInfo->investment_gl_acc_id : '')}}"/>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary searchAccount"
                                {{isset($mode) ? (($mode[1] == 'v') ? 'disabled': '') : ''}} id="searchAccount"
                                type="button"
                                tabindex="-1"><i class="bx bx-search font-size-small align-top"></i><span
                                class="align-middle ml-25">Search</span>
                        </button>
                    </div>
                    <label class="col-md-2 col-form-label text-right-align" for="account_type">Account Type</label>
                    <div class="col-md-2">
                        <input class="form-control form-control-sm"
                               id="account_type" name="account_type"
                               value="{{old('account_type',isset($investmentInfo) ? $investmentInfo->account_type : '')}}"
                               tabindex="-1" type="text" readonly="">
                    </div>
                </div>
                <div class="row ">
                    <label for="account_name" class="col-md-2 col-form-label max-w-15">Account Name</label>
                    <div class="col-md-9">
                        <input name="account_name" class="form-control form-control-sm"
                               value="{{old('account_name',isset($investmentInfo) ? $investmentInfo->gl_acc_name : '')}}"
                               id="account_name"
                               readonly
                               tabindex="-1">
                    </div>

                </div>
            </fieldset>
            <div class="row mt-2">
                <div class="col-md-12">
                    <input type="hidden" data-fdrStatus="" id="fdrStatus" name="fdr_status" value="{{isset($investmentInfo) ? $investmentInfo->workflow_approval_status : ''}}">
                    @if(isset($mode))
                        @if($mode[1] == 'v')
                            <a href="{{route('fdr-register.index')}}" type="reset"
                               class="btn btn-sm btn-dark reset_form"><i
                                    class="bx bx-arrow-back font-size-small"></i><span
                                    class="align-middle ml-25">Back</span></a>
                        @elseif($mode[1] == 'e')

                            <button type="submit"
                                    class="btn btn-sm btn-success mr-1" {{ ($investmentInfo->workflow_approval_status == \App\Enums\ApprovalStatus::PENDING) ? 'disabled' : '' }}>
                                <i
                                    class="bx bx-save font-size-small"></i><span
                                    class="align-middle ml-25">Update</span>
                            </button>
                            <a href="{{route('fdr-register.index')}}" type="reset"
                               class="btn btn-sm btn-dark reset_form"><i
                                    class="bx bx-arrow-back font-size-small"></i><span
                                    class="align-middle ml-25">Back</span></a>
                        @endif
                    @else
                        <button type="submit" class="btn btn-sm btn-success mr-1"><i
                                class="bx bx-save font-size-small"></i><span
                                class="align-middle ml-25">{{ (isset($investmentInfo->investment_id) ? 'Update' : 'Save') }}</span>
                        </button>
                        <button type="reset" class="btn btn-sm btn-dark reset_form"><i
                                class="bx bx-reset font-size-small"></i><span
                                class="align-middle ml-25">Reset</span></button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

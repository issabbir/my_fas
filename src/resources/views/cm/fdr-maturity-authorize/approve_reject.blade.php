@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-header d-flex justify-content-between align-items-center p-0">
                <h4> <span class="border-bottom-secondary border-bottom-2">FDR Maturity Authorize View</span></h4>
                <a href="{{route('fdr-maturity-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>

            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{--Workflow steps start--}}
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_CM_FDR_MATURITY_TRANS, App\Enums\WkReferenceColumn::MATURITY_TRANS_ID, $fdrMaturityTransView->maturity_trans_id, \App\Enums\WorkFlowMaster::CM_FDR_MATURITY_TRANSACTION) !!}
            {{--Workflow steps end--}}

            <form id="fdr-maturity-authorize-form" @if(isset($wkMapId)) action="{{route('fdr-maturity-authorize.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                {{--<input type="hidden" name="investment_auth_log_id" value="--}}{{--{{$fdrInvInfo->investment_auth_log_id}}--}}{{--">--}}

                <div class="row mt-5">
                    <div class="col-md-7">
                        <div class="row">
                            <label for="transaction_type" class="col-md-3 col-form-label ml-1">Transaction Type</label>
                            <div class="col-md-8 pl-0">
                                <input class="form-control form-control-sm" name="transaction_type" id="transaction_type" disabled
                                   value="{{old('transaction_type',isset($fdrMaturityTransView->maturity_trans_type_name) ? $fdrMaturityTransView->maturity_trans_type_name : '')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <label for="investment_type" class="col-md-5 col-form-label">Investment Type</label>
                            <div class="col-md-6 pl-0 pr-0">
                                <input class="form-control form-control-sm" name="investment_type" id="investment_type" disabled
                                       value="{{old('investment_type',isset($fdrMaturityTransView->investment_type_name) ? $fdrMaturityTransView->investment_type_name : '')}}">
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
                                    <input class="form-control form-control-sm" name="fiscal_year" id="fiscal_year" disabled
                                           value="{{old('fiscal_year',isset($fdrMaturityTransView->fiscal_year) ? $fdrMaturityTransView->fiscal_year : '')}}">
                                </div>
                            </div>
                            <div class="row ">
                                <label for="period" class="required col-md-3 col-form-label">Posting Period</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="period" id="period" disabled
                                           value="{{old('period',isset($fdrMaturityTransView->posting_period) ? $fdrMaturityTransView->posting_period : '')}}">
                                </div>
                            </div>
                            <div class="row ">
                                <label for="posting_date_field" class="required col-md-3 col-form-label ">Posting Date</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="posting_date_field" id="posting_date_field" disabled
                                           value="{{old('posting_date_field',isset($fdrMaturityTransView->trans_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->trans_date) : '')}}">
                                </div>
                            </div>
                            <div class="row">
                                <label for="document_date_field" class="required col-md-3 col-form-label pr-0">Document Date</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="document_date_field" id="document_date_field" disabled
                                           value="{{old('document_date_field',isset($fdrMaturityTransView->document_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->document_date) : '')}}">
                                </div>
                            </div>
                            <div class="form-group row {{isset($maturityInfo) ? "make-readonly" : ''}}">
                                <label for="document_number"
                                       class="required col-md-3 col-form-label">Document No</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="document_number" id="document_number" disabled
                                           value="{{old('document_number',isset($fdrMaturityTransView->document_no) ? $fdrMaturityTransView->document_no : '')}}">
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row justify-content-end">
                                <label for="department" class="col-form-label col-md-4 required ">Dept/Cost Center</label>
                                <div class="col-md-6 ">
                                    <input class="form-control form-control-sm" name="department" id="department" disabled
                                           value="{{old('department',isset($fdrMaturityTransView->department_name) ? $fdrMaturityTransView->department_name : '')}}">
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                                <div class="col-md-6">
                                    <input class="form-control form-control-sm" name="bill_section" id="bill_section" disabled
                                           value="{{old('bill_section',isset($fdrMaturityTransView->bill_section) ? $fdrMaturityTransView->bill_section : '')}}">
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                                <div class="col-md-6">
                                    <input class="form-control form-control-sm" name="bill_register" id="bill_register" disabled
                                           value="{{old('bill_register',isset($fdrMaturityTransView->bill_register) ? $fdrMaturityTransView->bill_register : '')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="narration" class="required col-md-2 col-form-label" style="max-width: 12.5%">Narration</label>
                        <div class="col-md-10">
                    <textarea maxlength="500" required name="narration" disabled
                              class="required form-control form-control-sm "
                              id="narration">{{  old('narration',isset($fdrMaturityTransView->narration) ? $fdrMaturityTransView->narration : '') }}</textarea>
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
                                    <input class="form-control form-control-sm" name="investment_id" id="investment_id" disabled
                                           value="{{old('fiscal_year',isset($fdrMaturityTransView->investment_id) ? $fdrMaturityTransView->investment_id : '')}}">
                                </div>
                            </div>
                            <div class="row ">
                                <label for="bank_name" class="required col-md-3 col-form-label max-w-12">Bank</label>
                                <div class="col-md-9">
                                    <input class="form-control form-control-sm" name="bank_name" id="bank_name" disabled
                                           value="{{old('bank_name',isset($fdrMaturityTransView->bank_name) ? $fdrMaturityTransView->bank_name : '')}}">
                                </div>
                            </div>
                            <div class="row">
                                <label for="branch_id" class="required col-md-3 col-form-label max-w-12">Branch Name </label>
                                <div class="col-md-9">
                                    <input class="form-control form-control-sm" name="branch_name" id="bank_name" disabled
                                           value="{{old('branch_name',isset($fdrMaturityTransView->branch_name) ? $fdrMaturityTransView->branch_name : '')}}">
                                </div>
                            </div>
                            <div class="row">
                                <label for="fdr_number" class="required col-md-3 col-form-label max-w-12">FDR No </label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="fdr_number" id="bank_name" disabled
                                           value="{{old('fdr_number',isset($fdrMaturityTransView->fdr_no) ? $fdrMaturityTransView->fdr_no : '')}}">
                                </div>
                            </div>
                            <div class="row">
                                <label for="amount" class="required col-md-3 col-form-label max-w-12">Amount </label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="amount" id="amount" disabled
                                           value="{{old('amount',isset($fdrMaturityTransView->investment_amount) ? $fdrMaturityTransView->investment_amount : '')}}">
                                </div>
                            </div>
                            <div class="row">
                                <label for="amount_word" class="col-form-label col-md-3 max-w-12 pr-0">Amount in words</label>
                                <div class="col-md-9">
                                    <textarea rows="2" id="amount_word" class="form-control form-control-sm" disabled>{{  old('amount_word',isset($fdrMaturityTransView->investment_amount) ? \App\Helpers\HelperClass::getCommaSeparatedValue($fdrMaturityTransView->investment_amount)  : '') }}</textarea>
                                </div>
                            </div>
                            <div class="row make-readonly">
                                <label class="col-md-3 col-form-label max-w-12 pr-0" for="investment_status">Investment
                                    Status</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="investment_status" id="investment_status" disabled
                                           value="{{old('investment_status',isset($fdrMaturityTransView->investment_status_name) ? $fdrMaturityTransView->investment_status_name : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row d-flex justify-content-end">
                                <label for="investment_date_field" class="required col-md-4 col-form-label max-w-30">Investment
                                    Date</label>
                                <div class=" col-md-5" >
                                    <input class="form-control form-control-sm" name="posting_date_field" id="investment_date_field" disabled
                                           value="{{old('investment_date_field',isset($fdrMaturityTransView->trans_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->trans_date) : '')}}">

                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="term_period_type" class="required col-md-4 col-form-label max-w-30">Term Period </label>
                                <div class="col-md-2 pr-0">
                                    <input class="form-control form-control-sm" name="term_period_type" id="term_period_type" disabled
                                           value="{{old('investment_status',isset($fdrMaturityTransView->term_period_no) ? $fdrMaturityTransView->term_period_no : '')}}">
                                </div>

                                <div class="col-md-3">
                                    <input class="form-control form-control-sm" name="term_period_type2" id="term_period_type2" disabled
                                           value="{{old('investment_status',isset($fdrMaturityTransView->term_period_code) ? $fdrMaturityTransView->term_period_code : '')}}">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="term_period_days" class="required col-md-4 col-form-label max-w-30">Term Period Type </label>
                                <div class="col-md-5 pr-0">
{{--                                    <input class="form-control form-control-sm" name="term_period_days" id="term_period_days" disabled--}}
{{--                                           value="{{old('investment_status',isset($fdrMaturityTransView->investment_status_name) ? $fdrMaturityTransView->investment_status_name : '')}}">--}}
                                    <input class="form-control form-control-sm" type="text" readonly @if($fdrMaturityTransView->term_period_type == 'A')  value="{{\App\Enums\Common\LPeriodType::Actual}}" @elseif($fdrMaturityTransView->term_period_type == 'F') value="{{\App\Enums\Common\LPeriodType::Flat}}" @endif>

                                </div>
                            </div>
                            <div class="row  d-flex justify-content-end">
                                <label for="maturity_date_field" class="required col-md-4 col-form-label max-w-30 ">Maturity
                                    Date</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="term_period_days" id="term_period_days" disabled
                                           value="{{old('investment_status',isset($fdrMaturityTransView->curr_renewal_maturity_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->curr_renewal_maturity_date) : '')}}">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="interest_rate" class="required col-md-4 col-form-label max-w-30">Interest Rate </label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="interest_rate" id="interest_rate" disabled
                                           value="{{old('investment_status',isset($fdrMaturityTransView->interest_rate) ? $fdrMaturityTransView->interest_rate : '')}}">
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
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="last_renewal_date_field" id="last_renewal_date_field" disabled
                                           value="{{old('last_renewal_date_field',isset($fdrMaturityTransView->last_renewal_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->last_renewal_date) : '')}}">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="last_renewal_amount" class="col-md-4 col-form-label max-w-30">Renewal Amount </label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" id="last_renewal_amount" readonly
                                               class="form-control form-control-sm text-right make-readonly-bg"
                                               name="last_renewal_amount"
                                               value="{{$fdrMaturityTransView->last_renewal_amount}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row  d-flex justify-content-end">
                                <label for="last_maturity_date_field" class="col-md-4 col-form-label max-w-30 ">Maturity
                                    Date</label>
                                <div class="col-md-5 " >
                                    <input class="form-control form-control-sm" name="last_maturity_date_field" id="last_maturity_date_field" disabled
                                           value="{{old('last_maturity_date_field',isset($fdrMaturityTransView->last_renewal_maturity_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->last_renewal_maturity_date) : '')}}">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="last_interest_rate" class="col-md-4 col-form-label max-w-30">Interest Rate </label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" name="last_maturity_date_field" id="last_maturity_date_field" disabled
                                           value="{{old('last_maturity_date_field',isset($fdrMaturityTransView->last_renewal_interest_rate) ? $fdrMaturityTransView->last_renewal_interest_rate : '')}}">
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
                                           id="maturity_gross_interest"
                                           value="{{old('fiscal_year',isset($fdrMaturityTransView->maturity_gross_interest_amount) ? $fdrMaturityTransView->maturity_gross_interest_amount : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group">
                                <label for="maturity_source_tax" class="col-form-label">Sources Tax</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" name="maturity_source_tax" readonly
                                           id="maturity_source_tax"
                                        value="{{old('fiscal_year',isset($fdrMaturityTransView->maturity_source_tax_amount) ? $fdrMaturityTransView->maturity_source_tax_amount : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group">
                                <label for="maturity_excise_duty" class="col-form-label">Excise Duty</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" name="maturity_excise_duty" readonly
                                           id="maturity_excise_duty"
                                        value="{{old('fiscal_year',isset($fdrMaturityTransView->maturity_excise_duty_amount) ? $fdrMaturityTransView->maturity_excise_duty_amount : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group">
                                <label for="maturity_net_interest" class="col-form-label">Net Interest</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" name="maturity_net_interest" readonly
                                           id="maturity_net_interest"
                                        value="{{old('fiscal_year',isset($fdrMaturityTransView->maturity_net_interest_amount) ? $fdrMaturityTransView->maturity_net_interest_amount : '')}}">
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
                                    <input class="form-control form-control-sm" disabled
                                           name="last_pro_days"
                                           id="last_pro_days"
                                        value="{{old('last_pro_days',isset($fdrMaturityTransView->provision_no_of_day) ? $fdrMaturityTransView->provision_no_of_day : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group">
                                <label for="last_pro_gross_interest" class="col-form-label">Gross Interest</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" disabled
                                           name="last_pro_gross_interest"
                                           id="last_pro_gross_interest"
                                        value="{{old('fiscal_year',isset($fdrMaturityTransView->provision_gross_interest) ? $fdrMaturityTransView->provision_gross_interest : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group">
                                <label for="last_pro_source_tax" class="col-form-label">Sources Tax</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" disabled
                                           name="last_pro_source_tax" id="last_pro_source_tax"
                                        value="{{old('last_pro_source_tax',isset($fdrMaturityTransView->provision_source_tax) ? $fdrMaturityTransView->provision_source_tax : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group">
                                <label for="last_pro_excise_duty" class="col-form-label">Excise Duty</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" disabled
                                           name="last_pro_excise_duty" id="last_pro_excise_duty"
                                        value="{{old('last_pro_excise_duty',isset($fdrMaturityTransView->provision_excise_duty) ? $fdrMaturityTransView->provision_excise_duty : '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group">
                                <label for="last_pro_net_interest" class="col-form-label">Net Interest</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm text-right" name="last_pro_net_interest" disabled
                                           id="last_pro_net_interest"
                                        value="{{old('last_pro_net_interest',isset($fdrMaturityTransView->provision_net_interest) ? $fdrMaturityTransView->provision_net_interest : '')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                @if (isset($fdrMaturityTransView->maturity_trans_type_id) && (($fdrMaturityTransView->maturity_trans_type_id == \App\Enums\Common\LFdrMaturityTransType::RENEWAL) || ($fdrMaturityTransView->maturity_trans_type_id == \App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT)))
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 15px;">Current Renewal Information</legend>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_date" class="col-form-label">Renewal Date</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-sm" disabled
                                               name="renewal_date"
                                               id="renewal_date"
                                               value="{{old('renewal_date',isset($fdrMaturityTransView->curr_renewal_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->curr_renewal_date) : '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_amt" class="col-form-label">Renewal Amount</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-sm text-right" disabled
                                               name="renewal_amt"
                                               id="renewal_amt"
                                               value="{{old('renewal_amt',isset($fdrMaturityTransView->curr_renewal_amount) ? $fdrMaturityTransView->curr_renewal_amount : '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="maturity_date" class="col-form-label">Maturity Date</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-sm" disabled
                                               name="maturity_date" id="maturity_date"
                                               value="{{old('maturity_date',isset($fdrMaturityTransView->curr_renewal_maturity_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->curr_renewal_maturity_date) : '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="interest_rate" class="col-form-label">Interest Rate</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-sm" disabled
                                               name="interest_rate" id="interest_rate"
                                               value="{{old('interest_rate',isset($fdrMaturityTransView->curr_renewal_interest_rate) ? $fdrMaturityTransView->curr_renewal_interest_rate : '')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                @if (isset($fdrMaturityTransView->maturity_trans_type_id) &&  ($fdrMaturityTransView->maturity_trans_type_id == \App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT))
                    <fieldset class="border pl-1 pr-1">
                        <legend class="w-auto" style="font-size: 15px;">Current Renewal with new split information</legend>
                        <div class="row">
                            <h6 class="mt-1 mb-1 ml-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                            <div class="col-md-12 table-responsive table-scroll" id="">
                                <table class="table table-sm table-bordered table-striped" id="">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>#SL No</th>
                                        <th>Investment Date</th>
                                        <th>Fdr No</th>
                                        <th>Amount</th>
                                        <th>Interest Rate</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                    </thead>
                                    <tbody id="intProvTransViewList">
                                    @if(count($fdrMaturityViewSplitList) > 0)
                                        @php $index=1; $totalDbtAmt = 0; $totalCrdAmt = 0;  @endphp
                                        @foreach ($fdrMaturityViewSplitList as $value)
                                            <tr>
                                                <td>{{ $index }}</td>
                                                <td>{{ \App\Helpers\HelperClass::dateConvert($value->investment_date) }}</td>
                                                <td>{{ $value->fdr_no }}</td>
                                                <td class="text-right">{{ $value->investment_amount }}</td>
                                                <td class="text-right">{{ $value->interest_rate }}</td>
                                                <td>{{ \App\Helpers\HelperClass::dateConvert($value->maturity_date) }}</td>
                                            </tr>
                                            @php
                                                $index++;
                                                $totalDbtAmt += $value->debit_amount;
                                                $totalCrdAmt += $value->credit_amount;
                                            @endphp
                                        @endforeach
                                        <th colspan="3" class="text-right pr-2"> Total Amount </th>
                                        <th id="" class="text-right">{{isset($totalDbtAmt) ? $totalDbtAmt : '0'}}</th>
                                        <th id="" class="text-right">{{isset($totalCrdAmt) ? $totalCrdAmt : '0'}}</th>
                                    @else
                                        <tr>
                                            <th colspan="5" class="text-center"> No Data Found</th>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                @endif

                <fieldset class="border pl-1 pr-1">
                    <legend class="w-auto" style="font-size: 15px;">P.O. Amount</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="po_amount" class="col-md-3 col-form-label max-w-30">P.O. Number </label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" id="po_number"
                                               class="form-control form-control-sm" disabled
                                               name="po_number"
                                            value="{{old('po_number',isset($fdrMaturityTransView->pay_order_no) ? $fdrMaturityTransView->pay_order_no : '')}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="po_date_field" class="col-md-3 col-form-label max-w-30 ">P.O. Date</label>
                                <div class="col-md-5 ">
                                    <input type="text" id="po_date_field"
                                           class="form-control form-control-sm" disabled
                                           name="po_date_field"
                                        value="{{old('po_date_field',isset($fdrMaturityTransView->pay_order_date) ? \App\Helpers\HelperClass::dateConvert($fdrMaturityTransView->pay_order_date) : '')}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row d-flex justify-content-end">
                                <label for="po_principal_amount" class="col-md-3 col-form-label max-w-30 pr-0">Principal
                                    Amount </label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" id="po_principal_amount"
                                               class="form-control form-control-sm text-right make-readonly-bg"
                                               name="po_principal_amount"
                                            value="{{old('po_principal_amount',isset($fdrMaturityTransView->principal_amt) ? $fdrMaturityTransView->principal_amt : '')}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="po_interest_amount" class="col-md-3 col-form-label max-w-30">Interest Amount </label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" id="po_interest_amount"
                                               class="form-control form-control-sm text-right make-readonly-bg"
                                               name="po_interest_amount"
                                            value="{{old('po_interest_amount',isset($fdrMaturityTransView->interest_amt) ? $fdrMaturityTransView->interest_amt : '')}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="tot_amt" class="col-md-3 col-form-label max-w-30">Total Amount</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" id="tot_amt"
                                               class="form-control form-control-sm text-right make-readonly-bg"
                                               name="tot_amt"
                                               value="{{old('tot_amt',isset($fdrMaturityTransView->total_amount) ? $fdrMaturityTransView->total_amount : '')}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label for="tot_amt_inward" class="col-md-3 col-form-label max-w-30">Amount In Words</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <textarea rows="3" required name="tot_amt_inward" disabled
                                                  class="form-control form-control-sm text-right make-readonly-bg"
                                                  id="tot_amt_inward">{{  old('narration',isset($fdrMaturityTransView->principal_amt_inword) ? $fdrMaturityTransView->principal_amt_inword : '') }}
                                        </textarea>
                                    </div>
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
                                <label for="cr_account_name" class="col-md-3 col-form-label">Account Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="cr_account_name"
                                           class="form-control form-control-sm"
                                           name="cr_account_name" disabled
                                        value="{{old('cr_account_name',isset($contraBankAccountInfo->gl_acc_name) ? $contraBankAccountInfo->gl_acc_name : '')}}"/>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label" for="cr_account_type">Account Type</label>
                                <div class="col-md-5">
                                    <input class="form-control form-control-sm" id="cr_account_type" name="cr_account_type"
                                           type="text" disabled
                                           value="{{old('cr_account_type',isset($contraBankAccountInfo->gl_type_name) ? $contraBankAccountInfo->gl_type_name : '')}}">
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
                                               name="cr_account_balance"
                                               type="text" disabled
                                               value="{{old('cr_account_balance',isset($contraBankAccountInfo->account_balance) ? $contraBankAccountInfo->account_balance : '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <label class="col-md-3 col-form-label max-w-30 pr-0" for="cr_authorized_balance">Auth.
                                    Balance</label>
                                <div class="input-group col-md-5">
                                    <input name="cr_authorized_balance" style="height: auto;"
                                           class="form-control form-control-sm text-right-align"
                                           value="{{old('cr_authorized_balance',isset($contraBankAccountInfo->authorize_balance) ? $contraBankAccountInfo->authorize_balance : '')}}"
                                           tabindex="-1"
                                           id="cr_authorized_balance" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="row">
                    <h6 class="mt-1 mb-1 ml-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                    <div class="col-md-12 table-responsive table-scroll" id="">
                        <table class="table table-sm table-bordered table-striped" id="">
                            <thead class="thead-light">
                            <tr>
                                <th>#SL No</th>
                                <th>Gl Account ID</th>
                                <th>GL Account Name</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                            </thead>
                            <tbody id="intProvTransViewList">
                            @if(count($fdrMaturityTransViewList) > 0)
                                @php $index=1; $totalDbtAmt = 0; $totalCrdAmt = 0;  @endphp
                                @foreach ($fdrMaturityTransViewList as $value)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $value->account_id }}</td>
                                        <td>{{ $value->account_name }}</td>
                                        <td class="text-right">{{ $value->debit_amount }}</td>
                                        <td class="text-right">{{ $value->credit_amount }}</td>
                                    </tr>
                                    @php
                                        $index++;
                                        $totalDbtAmt += $value->debit_amount;
                                        $totalCrdAmt += $value->credit_amount;
                                    @endphp
                                @endforeach
                                <th colspan="3" class="text-right pr-2"> Total Amount </th>
                                <th id="" class="text-right">{{isset($totalDbtAmt) ? $totalDbtAmt : '0'}}</th>
                                <th id="" class="text-right">{{isset($totalCrdAmt) ? $totalCrdAmt : '0'}}</th>
                            @else
                                <tr>
                                    <th colspan="5" class="text-center"> No Data Found</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="row mt-2">
                    <input type="hidden" name="comment_on_decline" id="comment_on_decline"/>
                    @if (isset($wkRefStatus) &&  $wkRefStatus == \App\Enums\ApprovalStatus::PENDING)
                        <div class="col-md-12 d-flex">
                            <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                            <button type="button" class="btn btn-primary approve-reject-btn mr-1" name="authorize"
                                    value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i class="bx bx-check-double"></i><span
                                    class="align-middle ml-25"></span>Authorize
                            </button>
                            <button type="button" class="btn btn-danger approve-reject-btn mr-1" name="decline"
                                    id="approve_reject_btn" value="{{ App\Enums\ApprovalStatus::REJECT }}"><i
                                    class="bx bx-x"></i><span class="align-middle ml-25"></span>Decline
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript" >

        function checkMaturityAuthForm() {
            $('.approve-reject-btn').click(function (e) {
                e.preventDefault();

                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;
                $('#approve_reject_value').val(approval_status);

                if (approval_status == 'A') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'FDR Maturity ' + approval_status_val,
                    type: 'warning',
                    input: swal_input_type,
                    inputPlaceholder: 'Reason For ' + approval_status_val+'?',
                    inputValidator: (result) => {
                        return !result && 'You need to provide a comment'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        //form.submit();
                        $("#comment_on_decline").val( (isConfirm.value !== true) ? isConfirm.value : '' );
                        $('#fdr-maturity-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkMaturityAuthForm();
        });

    </script>
@endsection

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
                <h4> <span class="border-bottom-secondary border-bottom-2">FDR Investment Authorize Detail View</span></h4>
                <a href="{{route('fdr-investment-register-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_CM_FDR_INVESTMENT_AUTH_LOG, App\Enums\WkReferenceColumn::INVESTMENT_AUTH_LOG_ID, $fdrInvInfo->investment_auth_log_id, \App\Enums\WorkFlowMaster::CM_FDR_INVESTMENT_REGISTER) !!}
            {{--Workflow steps end--}}

            <form id="fdr-investment-reg-authorize-form" @if(isset($wkMapId)) action="{{route('fdr-investment-register-authorize.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                <input type="hidden" name="investment_auth_log_id" value="{{$fdrInvInfo->investment_auth_log_id}}">

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="investment_type" class=" col-form-label">Investment Type</label>
                        <input type="text" id="investment_type_name" class="form-control form-control-sm" name="investment_type_name"
                               placeholder="" disabled
                               value="{{old('investment_type_name',isset($fdrInvInfo->investment_type_name) ? $fdrInvInfo->investment_type_name : '')}}"/>
                    </div>
                    <div class="col-md-2">
                        <label for="fiscal_year" class="required col-form-label">Fiscal Year</label>
                        <input type="text" id="fiscal_year" class="form-control form-control-sm" name="fiscal_year"
                               placeholder="" disabled
                               value="{{old('fiscal_year',isset($fdrInvInfo->fiscal_year) ? $fdrInvInfo->fiscal_year : '')}}"/>
                    </div>
                    <div class="col-md-2">
                        <label for="posting_period" class="required col-form-label">Posting Period</label>
                        <input type="text" id="posting_period" class="form-control form-control-sm" name="posting_period"
                               placeholder="" disabled
                               value="{{old('posting_period',isset($fdrInvInfo->posting_period) ? $fdrInvInfo->posting_period : '')}}"/>
                    </div>
                </div>

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">BASIC INFORMATION</legend>
                    <div class="row">
                        <label for="investment_id" class="col-md-2 col-form-label">Investment ID </label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="investment_id" class="form-control form-control-sm" name="investment_id"
                                   placeholder="Auto Generate" disabled
                                   value="{{old('investment_id',isset($fdrInvInfo->investment_id) ? $fdrInvInfo->investment_id : '')}}"/>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="investment_date" class="required col-md-2 col-form-label">Investment Date</label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="investment_date" class="form-control form-control-sm" name="investment_date"
                                   placeholder="" disabled
                                   value="{{isset($fdrInvInfo->investment_date) ? \App\Helpers\HelperClass::dateConvert($fdrInvInfo->investment_date) : ''}}"/>
                        </div>
                    </div>

                    <div class="row">
                        <label for="bank_name" class="required col-md-2 col-form-label">Bank</label>
                        <div class="col-md-5 form-group ">
                            <input type="text" id="bank_name" class="form-control form-control-sm" name="bank_name"
                                   placeholder="" disabled
                                   value="{{old('bank_name',isset($fdrInvInfo->bank_name) ? $fdrInvInfo->bank_name : '')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="branch_name" class="required col-md-2 col-form-label">Branch Name </label>
                        <div class="col-md-5 form-group ">
                            <input type="text" id="branch_name" class="form-control form-control-sm" name="branch_name"
                                   placeholder="" disabled
                                   value="{{old('branch_name',isset($fdrInvInfo->branch_name) ? $fdrInvInfo->branch_name : '')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="fdr_no" class="required col-md-2 col-form-label">FDR Number </label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="fdr_no" class="form-control form-control-sm" name="fdr_no"
                                   placeholder="" disabled
                                   value="{{old('fdr_no',isset($fdrInvInfo->fdr_no) ? $fdrInvInfo->fdr_no : '')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="investment_amount" class="required col-md-2 col-form-label">Amount </label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="investment_amount" class="form-control form-control-sm" name="investment_amount"
                                   placeholder="" disabled
                                   value="{{old('investment_amount',isset($fdrInvInfo->investment_amount) ? $fdrInvInfo->investment_amount : '')}}"/>
                        </div>
                    </div>

                    <div class="row">
                        <label for="term_period_no" class="required col-md-2 col-form-label">Term Period </label>
                        <div class="col-md-1 form-group">
                            <input type="text" id="term_period_no" class="form-control form-control-sm" name="term_period_no"
                                   placeholder="" disabled
                                   value="{{old('term_period_no',isset($fdrInvInfo->term_period_no) ? $fdrInvInfo->term_period_no : '')}}"/>
                        </div>

                        <div class="col-md-2">
                            <input type="text" id="term_period_no" class="form-control form-control-sm" name="term_period_no"
                                   placeholder="" disabled
                                   value="{{old('term_period_no',isset($fdrInvInfo->term_period) ? $fdrInvInfo->term_period : '')}}"/>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="maturity_date" class="required col-md-2 col-form-label">Maturity Date</label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="maturity_date" class="form-control form-control-sm" name="maturity_date"
                                   placeholder="" disabled
                                   value="{{isset($fdrInvInfo->maturity_date) ? \App\Helpers\HelperClass::dateConvert($fdrInvInfo->maturity_date) : ''}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="interest_rate" class="required col-md-2 col-form-label">Interest Rate </label>
                        <div class="col-md-3 form-group">
                            <input type="text" id="interest_rate" class="form-control form-control-sm" name="interest_rate"
                                   placeholder="" disabled
                                   value="{{old('interest_rate',isset($fdrInvInfo->interest_rate) ? $fdrInvInfo->interest_rate : '')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-form-label" for="investment_status_name">Investment Status</label>
                        <div class="col-md-3">
                            <input type="text" id="investment_status_name" class="form-control form-control-sm" name="investment_status_name"
                                   placeholder="" disabled
                                   value="{{old('investment_status_name',isset($fdrInvInfo->investment_status_name) ? $fdrInvInfo->investment_status_name : '')}}"/>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-2 mt-1">
                    <legend class="w-auto text-bold-600" style="font-size: 15px;">RENEWAL INFORMATION</legend>
                    <div class="row">
                        <label class="col-md-2 col-form-label" for="renewal_date">Renewal Date</label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="renewal_date" class="form-control form-control-sm" name="renewal_date"
                                   placeholder="" disabled
                                   value="{{isset($fdrInvInfo->renewal_date) ? \App\Helpers\HelperClass::dateConvert($fdrInvInfo->renewal_date) : ''}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-form-label" for="renewal_amount">Renewal Amount</label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="renewal_amount" class="form-control form-control-sm" name="renewal_amount"
                                   placeholder="" disabled
                                   value="{{isset($fdrInvInfo->renewal_amount) ? $fdrInvInfo->renewal_amount : ''}}"/>
                        </div>
                    </div>
                    {{--<div class="row">
                        <label for="renewal_term_period_no" class="col-md-2 col-form-label">Term Period </label>
                        <div class="col-md-1 form-group">
                            <input type="text" id="renewal_term_period_no" class="form-control form-control-sm" name="renewal_term_period_no"
                                   placeholder="" disabled
                                   value="{{old('renewal_term_period_no',isset($fdrInvInfo->renewal_term_period_no) ? $fdrInvInfo->renewal_term_period_no : '')}}"/>
                        </div>
                        <div class="col-md-2 ">
                            <input type="text" id="renewal_term_period_code" class="form-control form-control-sm" name="renewal_term_period_code"
                                   placeholder="" disabled
                                   value="{{old('renewal_term_period_code',isset($fdrInvInfo->renewal_term_period_code) ? $fdrInvInfo->renewal_term_period_code : '')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="renewal_term_period_days" class="required col-md-2 col-form-label">Term Period (Days) </label>
                        <div class="col-md-1 form-group ">
                            <input type="text" id="renewal_term_period_days" class="form-control form-control-sm" name="renewal_term_period_days"
                                   placeholder="" disabled
                                   value="{{old('renewal_term_period_code',isset($fdrInvInfo->renewal_term_period_days) ? $fdrInvInfo->renewal_term_period_days : '')}}"/>
                        </div>
                    </div>--}}
                    <div class="row">
                        <label class="col-md-2 col-form-label" for="renewal_maturity_date">Maturity Date</label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="renewal_maturity_date" class="form-control form-control-sm" name="renewal_maturity_date"
                                   placeholder="" disabled
                                   value="{{isset($fdrInvInfo->renewal_maturity_date) ? \App\Helpers\HelperClass::dateConvert($fdrInvInfo->renewal_maturity_date) : ''}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <label for="renewal_interest_rate" class="col-md-2 col-form-label">Interest Rate </label>
                        <div class="col-md-3 form-group ">
                            <input type="text" id="renewal_maturity_date" class="form-control form-control-sm" name="renewal_interest_rate"
                                   placeholder="" disabled
                                   value="{{old('renewal_interest_rate',isset($fdrInvInfo->renewal_interest_rate) ? $fdrInvInfo->renewal_interest_rate : '')}}"/>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-2 mt-1">
                    <legend class="w-auto text-bold-600" style="font-size: 15px;">GL ACCOUNT MAPPING</legend>
                    <div class="row">
                        <label class="required col-md-2 col-form-label" for="investment_gl_acc_id">Account ID</label>
                        <div class=" col-md-3 form-group">
                            <input type="text" id="investment_gl_acc_id" class="form-control form-control-sm" name="investment_gl_acc_id"
                                   placeholder="" disabled
                                   value="{{old('investment_gl_acc_id',isset($fdrInvInfo->investment_gl_acc_id) ? $fdrInvInfo->investment_gl_acc_id : '')}}"/>
                        </div>

                        <div class="col-md-2 pr-0">
                            <button class="btn btn-sm btn-primary searchAccount" id="searchAccount" type="button" disabled=""
                                    tabindex="-1"><i class="bx bx-search font-size-small align-top"></i><span
                                    class="align-middle ml-25" >Search</span>
                            </button>
                        </div>
                        <label class="col-md-2 col-form-label text-right-align" for="account_type">Account Type</label>
                        <div class="col-md-2">
                            <input class="form-control form-control-sm" id="account_type" name="account_type"
                                   tabindex="-1" type="text" disabled=""
                                   value="{{old('account_type',isset($fdrInvInfo->account_type) ? $fdrInvInfo->account_type : '')}}">
                        </div>

                    </div>
                    <div class="row ">
                        <label for="account_name" class="col-md-2 col-form-label">Account Name</label>
                        <div class="col-md-9">
                            <input name="account_name" class="form-control form-control-sm" id="account_name" disabled tabindex="-1"
                                   value="{{old('gl_acc_name',isset($fdrInvInfo->gl_acc_name) ? $fdrInvInfo->gl_acc_name : '')}}" >
                        </div>

                    </div>
                </fieldset>

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

        function checkFdrInvRegAuthForm() {
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
                    text: 'FDR Investment Register ' + approval_status_val,
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
                        $('#fdr-investment-reg-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkFdrInvRegAuthForm();
        });

    </script>
@endsection

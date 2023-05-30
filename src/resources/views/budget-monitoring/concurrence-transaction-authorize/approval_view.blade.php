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
        <div class="card-header d-flex justify-content-between align-items-center pb-0 mb-0">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Concurrence Transaction Authorization Detail View</span></h4></h4>
            <a href="{{route('concurrence-transaction-authorization.index',['filter'=>(isset($filter) ? $filter : '')])}}">
                <span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span>
            </a>
        </div>
        <div class="card-body pt-0">
            {{--<h4> <span class="border-bottom-secondary border-bottom-2">Budget Concurrence Transaction Authorization Detail View</span></h4>--}}
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
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_BUDGET_BOOKING_TRANS, App\Enums\WkReferenceColumn::BUDGET_BOOKING_ID, $concurrenceTranInfo->budget_booking_id, \App\Enums\WorkFlowMaster::BUDGET_MON_BUDGET_CONCURRENCE_TRANSACTION_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="bud-concurrence-tran-authorize-form" @if(isset($wkMapId)) action="{{route('concurrence-transaction-authorization.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf

                @include('budget-monitoring.common_concurrence_transaction_view')
                <input type="hidden" value="{{$mode}}" name="mode">
                <div class="row mt-2">
                    <div class="col-md-12 d-flex">
                        @if (isset($wkRefStatus) &&  $wkRefStatus == \App\Enums\ApprovalStatus::PENDING)
                            <input type="hidden" name="comment_on_decline" id="comment_on_decline"/>
                            <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                            <button type="button" class="btn btn-primary approve-reject-btn mr-1" name="authorize"
                                    value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i class="bx bx-check-double"></i><span
                                    class="align-middle ml-25"></span>Authorize
                            </button>
                            <button type="button" class="btn btn-danger approve-reject-btn mr-1" name="decline"
                                    value="{{ App\Enums\ApprovalStatus::REJECT }}"><i class="bx bx-x"></i><span
                                    class="align-middle ml-25"></span>Decline
                            </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript" >

        function checkConcurrenceTransAuthForm() {
            $('.approve-reject-btn').click(function (e) {
                e.preventDefault();

                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;

                $('#approve_reject_value').val(approval_status);

                if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else if(approval_status == '{{\App\Enums\ApprovalStatus::CANCEL}}'){
                    approval_status_val = '{{\App\Enums\ApprovalStatus::CANCEL}}';
                    swal_input_type = 'text';
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Concurrence Transaction ' + approval_status_val,
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
                        $('#bud-concurrence-tran-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkConcurrenceTransAuthForm();

            $("#ministry_approved").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->budget_approved_amt) ? $concurrenceTranInfo->budget_approved_amt : ''}}'));
            $("#utilized_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->budget_utilized_amt) ? $concurrenceTranInfo->budget_utilized_amt : ''}}'));
            $("#balance_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->budget_balance_amt) ? $concurrenceTranInfo->budget_balance_amt : ''}}'));

            $("#blocked_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->block_amount) ? $concurrenceTranInfo->block_amount : ''}}'));
            $("#unblocked_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->unblock_amount) ? $concurrenceTranInfo->unblock_amount : ''}}'));
            $("#remaining_block_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->remaining_block_amount) ? $concurrenceTranInfo->remaining_block_amount : ''}}'));
            $("#available_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->available_amt) ? $concurrenceTranInfo->available_amt : ''}}'));

            $("#est_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->estimate_amount) ? $concurrenceTranInfo->estimate_amount : ''}}'));
            $("#booking_amount").val(getCommaSeparatedValue('{{isset($concurrenceTranInfo->budget_booking_amount) ? $concurrenceTranInfo->budget_booking_amount : ''}}'));
            $("#est_amount_word").val(amountTranslate('{{isset($concurrenceTranInfo->estimate_amount) ? $concurrenceTranInfo->estimate_amount : ''}}'));
            $("#booking_amount_word").val(amountTranslate('{{isset($concurrenceTranInfo->budget_booking_amount) ? $concurrenceTranInfo->budget_booking_amount : ''}}'));

        });

    </script>
@endsection


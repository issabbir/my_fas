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
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Concurrence Transaction Detail View</span></h4></h4>
            <a href="{{route('concurrence-transaction-list.index',['filter'=>(isset($filter) ? $filter : '')])}}">
                <span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span>
            </a>
        </div>
        <div class="card-body pt-0">
            {{--<h4> <span class="border-bottom-secondary border-bottom-2">Budget Concurrence Transaction Detail View</span></h4>--}}
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @include('budget-monitoring.common_concurrence_transaction_view')

        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript" >

        $(document).ready(function () {
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


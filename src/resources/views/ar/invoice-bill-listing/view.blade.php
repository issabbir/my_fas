<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৭ PM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }

        .text-right-align {
            text-align: right;
        }

        /*.bootstrap-datetimepicker-widget table td.active, .bootstrap-datetimepicker-widget table td.active:hover {
             background-color: transparent;
             color: #727E8C;
             text-shadow: 0 0 #f3f0f0;
        }*/
    </style>

@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-header d-flex justify-content-between align-items-center p-0">
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Entry View</span></h4>
                <a href="{{route('ar-invoice-bill-listing.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>

            @if (empty($inserted_data))
                <h6 class="text-center font-weight-bold mt-2">AR: Invoice Bill Data Not Found.</h6>
            @else
                {{--Workflow steps start--}}
                {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_AR_INVOICE, App\Enums\WkReferenceColumn::AR_INVOICE_ID, $inserted_data->invoice_id, \App\Enums\WorkFlowMaster::AR_INVOICE_BILL_ENTRY_APPROVAL) !!}
                {{--Workflow steps end--}}

                @include('ar.ar-common.common_invoice_view')
            @endif
        </div>
   </div>

@endsection

@section('footer-script')
    @yield('view-script')
@endsection

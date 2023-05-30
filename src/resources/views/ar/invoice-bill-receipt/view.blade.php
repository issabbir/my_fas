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
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Collection View</span></h4>
                <a href="{{route('invoice-bill-receipt-list.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_AR_RECEIPT, App\Enums\WkReferenceColumn::AR_RECEIPT_ID, $invBillReceiptInfo->receipt_id, \App\Enums\WorkFlowMaster::AR_INVOICE_BILL_RECEIPT_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="invoice-bill-receipt-form" {{--enctype="multipart/form-data" action="{{route('invoice-bill-payment.store')}}"--}} method="post">
                @csrf

                @include('ar.ar-common.common_invoice_bill_receipt_view')

            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    @yield('view-script')
@endsection

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
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Collection Authorize Detail View</span></h4>
                <a href="{{route('invoice-bill-receipt-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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

            <form id="invoice-bill-pay-authorize-form" @if(isset($wkMapId)) action="{{route('invoice-bill-receipt-authorize.approve-reject-store',[$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                @include('ar.ar-common.common_invoice_bill_receipt_view')
                @include("ar.ar-common.common_authorizer")
            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    @yield('view-script')
    <script type="text/javascript" >
        function checkInvBillPayAuthForm() {
            $('.approve-reject-btn').click(function (e) {
                e.preventDefault();

                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;

                $('#approve_reject_value').val(approval_status);

                if (approval_status == 'A') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else if(approval_status == '{{\App\Enums\ApprovalStatus::CANCEL}}'){
                    approval_status_val = 'Cancel';
                    swal_input_type = 'text';
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'Invoice Receive ' + approval_status_val,
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
                        $('#invoice-bill-pay-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkInvBillPayAuthForm();
        });

    </script>

@endsection

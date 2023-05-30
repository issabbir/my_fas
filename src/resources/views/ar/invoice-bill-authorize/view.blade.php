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
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Entry Authorize View</span></h4>
                <a href="{{route('ar-invoice-bill-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
            </div>
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            {{--{{Session::get('m-class')}}
            @php
                die(Session::get('message'));
            @endphp--}}
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{--Workflow steps start--}}
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_AR_INVOICE, App\Enums\WkReferenceColumn::AR_INVOICE_ID, $inserted_data->invoice_id, \App\Enums\WorkFlowMaster::AR_INVOICE_BILL_ENTRY_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="invoice-bill-authorize-form"
                  @if(isset($wkMapId)) action="{{route('ar-invoice-bill-authorize.approve-reject-store',[$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}"
                  @endif method="post">
                @csrf
                @include('ar.ar-common.common_invoice_view')
                @include('ar.ar-common.common_authorizer')
            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    @yield('view-script')
    <script type="text/javascript">

        function checkInvBillAuthForm() {

            $('.approve-reject-btn').click(function (e) {
                // alert('ssssssss1111111111ss')
                e.preventDefault();
                let approval_status = $(this).val();
                let approval_status_val;
                let swal_input_type;
                $('#approve_reject_value').val(approval_status);

                if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
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
                    text: 'Invoice Bill ' + approval_status_val,
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
                        $('#invoice-bill-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }
        checkInvBillAuthForm();
    </script>
@endsection

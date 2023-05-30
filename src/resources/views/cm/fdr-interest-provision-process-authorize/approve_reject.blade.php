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
                <h4> <span class="border-bottom-secondary border-bottom-2">Interest Provision Authorize View</span></h4>
                <a href="{{route('fdr-interest-prov-process-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_CM_FDR_PROVISION_MASTER, App\Enums\WkReferenceColumn::PROVISION_MASTER_ID, $provisionMstId, \App\Enums\WorkFlowMaster::CM_FDR_INTEREST_PROVISION_PROCESS) !!}
            {{--Workflow steps end--}}

            <form id="fdr-interest-prov-proc-authorize-form" @if(isset($wkMapId)) action="{{route('fdr-interest-prov-process-authorize.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                {{--<input type="hidden" name="investment_auth_log_id" value="--}}{{--{{$fdrInvInfo->investment_auth_log_id}}--}}{{--">--}}
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Interest Provision Processing</legend>
                    <div class="row">
                            <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Interest Provision List</span></h6>
                            <div class="col-md-12 table-responsive table-scroll" id="">
                                <table class="table table-sm table-bordered table-striped" id="int_prov_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#SL No</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th>Investment date</th>
                                            <th>Fdr No</th>
                                            <th>Amount</th>
                                            <th>Interest Rate</th>
                                            <th>No of Days</th>
                                            <th>Gross Interest</th>
                                            <th>S.Tax</th>
                                            <th>E. Duty</th>
                                            <th>Net Interest</th>
                                        </tr>
                                    </thead>
                                    <tbody id="intProvList">
                                        @if(count($intProvList) > 0)
                                            @php $index=1;  @endphp
                                            @foreach ($intProvList as $value)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>{{ $value->bank_name }}</td>
                                                    <td>{{ $value->branch_name }}</td>
                                                    <td>{{ \App\Helpers\HelperClass::dateConvert($value->investment_date) }}</td>
                                                    <td>{{ $value->fdr_no }}</td>
                                                    <td class="text-right">{{ $value->investment_amount }}</td>
                                                    <td>{{ $value->interest_rate }}</td>
                                                    <td>{{ $value->provision_no_of_days }}</td>
                                                    <td class="text-right">{{ $value->provision_gross_interest }}</td>
                                                    <td class="text-right"> {{ $value->provision_source_tax }}</td>
                                                    <td class="text-right">{{ $value->provision_excise_duty }}</td>
                                                    <td class="text-right">{{ $value->provision_net_interest }}</td>
                                                </tr>
                                                @php $index++; @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <th colspan="12" class="text-center"> No Data Found</th>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <div class="row">
                        <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                        <div class="col-md-12 table-responsive table-scroll" id="">
                            <table class="table table-sm table-bordered table-striped" id="int_prov_trans_view_table">
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
                                @if(count($intProvTransViewList) > 0)
                                    @php $index=1; $totalDbtAmt = 0; $totalCrdAmt = 0;  @endphp
                                    @foreach ($intProvTransViewList as $value)
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

        function checkFdrIntProvProcAuthForm() {
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
                    text: 'FDR Interest Provision Process ' + approval_status_val,
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
                        $('#fdr-interest-prov-proc-authorize-form').submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        $(document).ready(function () {
            checkFdrIntProvProcAuthForm();
        });

    </script>
@endsection

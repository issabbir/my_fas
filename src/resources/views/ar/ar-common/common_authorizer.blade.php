<section>
    {{--<div class="row mt-1">
        @if (isset($wkRefStatus) && ($wkRefStatus == \App\Enums\ApprovalStatus::APPROVED || $wkRefStatus == \App\Enums\ApprovalStatus::REJECT))
            <div class="col-md-2"><label for="authorizer" class="required">Authorizer </label></div>
            <div class="col-md-3 form-group pl-0">
                <input type="text" id="authorizer" class="form-control form-control-sm" name="authorizer" disabled
                       value="{{$empInfo->employee->emp_name.' ('.$empInfo->employee->emp_code.')'}}"/>
            </div>

            <div class="col-md-2"><label for="comment" class="">Comment </label></div>
            <div class="col-md-3 form-group pl-0">
                <input type="text" id="comment" class="form-control form-control-sm" name="comment"
                       @if (isset($wkRefStatus) && ($wkRefStatus == \App\Enums\ApprovalStatus::APPROVED || $wkRefStatus == \App\Enums\ApprovalStatus::REJECT)) disabled
                       @endif
                       value="{{isset($wkMapInfo->reference_comment) ? $wkMapInfo->reference_comment : ''}}"/>
            </div>
        @endif
    </div>--}}

    <div class="row mt-2">
        <input type="hidden" name="comment_on_decline" id="comment_on_decline"/>
        @if (isset($wkRefStatus) &&  $wkRefStatus == \App\Enums\ApprovalStatus::PENDING)
            <div class="col-md-12 d-flex">

                <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                <button type="button" class="btn btn-sm btn-primary approve-reject-btn mr-1" name="authorize"
                        value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i
                        class="bx bx-check-double font-size-small"></i><span
                        class="align-middle ml-25"></span>Authorize
                </button>
                <button type="button" class="btn btn-sm btn-danger approve-reject-btn mr-1" name="decline"
                        value="{{ App\Enums\ApprovalStatus::REJECT }}"><i class="bx bx-x font-size-small"></i><span
                        class="align-middle ml-25"></span>Decline
                </button>

                @else
                    <div class="col-md-12 d-flex justify-content-end">

                        <input type="hidden" name="approve_reject_value" id="approve_reject_value" value=""/>
                        <button type="button" class="btn btn-sm btn-info approve-reject-btn mr-1" {{$cancelPermission}} name="cancel"
                                value="{{ App\Enums\ApprovalStatus::CANCEL }}"><i class="bx bx-x "></i><span
                                class="align-middle ml-25"></span>Cancel/Reverse
                        </button>
                        @endif
                        @if($wkMapInfo->workflow_master_id==App\Enums\WorkFlowMaster::AR_INVOICE_BILL_ENTRY_APPROVAL)
                            <a href="{{route('ar-invoice-bill-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"
                               class="btn btn-dark"><i
                                    class="bx bx-log-out"></i><span class="align-middle ml-25"></span>Back</a>
                    </div>
                    @elseif($wkMapInfo->workflow_master_id==App\Enums\WorkFlowMaster::AR_INVOICE_BILL_RECEIPT_APPROVAL)
                        <a href="{{route('invoice-bill-receipt-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"
                           class="btn btn-dark"><i
                                class="bx bx-log-out"></i><span class="align-middle ml-25"></span>Back</a>
            </div>
        @endif
    </div>
</section>

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
               {{-- <h4 class="card-title"> Add  Chart Of Accounts (COA)</h4>--}}
                <h4> <span class="border-bottom-secondary border-bottom-2">Invoice/Bill Payment Authorize Detail View</span></h4>
                <a href="{{route('invoice-bill-payment-authorize.index',['filter'=>(isset($filter) ? $filter : '')])}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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
            {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_AP_PAYMENT, App\Enums\WkReferenceColumn::PAYMENT_ID, $invBillPayInfo->payment_id, \App\Enums\WorkFlowMaster::AP_INVOICE_BILL_PAYMENT_APPROVAL) !!}
            {{--Workflow steps end--}}

            <form id="invoice-bill-pay-authorize-form" @if(isset($wkMapId)) action="{{route('invoice-bill-payment-authorize.approve-reject-store',['wkMapId'=>$wkMapId,'filter'=>(isset($filter) ? $filter : '')])}}" @endif method="post">
                @csrf
                <input type="hidden" name="invoice_id" value="{{$invBillPayInfo->payment_id}}">

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Detail View</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <h6> <span class="border-bottom-secondary border-bottom-1 text-bold-600">Transaction Reference</span></h6>
                            <div class="row">
                                <div class="col-md-4"><label for="batch_id" class="">Batch ID </label></div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->batch_id) ? $invBillPayInfo->batch_id : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="period" class="">Posting Period </label></div>
                                <div class="col-md-6 form-group ">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->trans_period_name) ? $invBillPayInfo->trans_period_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="posting_date" class="">Posting Date </label></div>
                                <div class="col-md-6 form-group ">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->trans_date) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->trans_date) : ''}}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="department" class="required">Dpt/Cost Center </label></div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->cost_center_dept_name) ? $invBillPayInfo->cost_center_dept_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="bill_sec_id" class="required">Bill Section </label></div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->bill_sec_name) ? $invBillPayInfo->bill_sec_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-3 pr-0"><label for="bill_register" class="required">Bill Register </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->bill_reg_name) ? $invBillPayInfo->bill_reg_name : ''}}" disabled/>
                                </div>
                            </div>
                        </div>
                        {{--<div class="offset-2 col-md-5">
                            <div class="row">
                                <div class="col-md-6"><label for="document_number" class="">Document Number </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->document_no) ? $invBillPayInfo->document_no : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label for="document_reference" class="">Document Reference </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->document_ref) ? $invBillPayInfo->document_ref : ''}}" disabled/>
                                </div>
                            </div>
                        </div>--}}
                        {{--<div class="offset-2 col-md-5">
                            <div class="row">
                                <div class="col-md-6"><label for="bill_sec_id" class="">Bill Section </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->bill_sec_name) ? $invBillPayInfo->bill_sec_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><label for="bill_register" class="">Bill Register </label></div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->bill_reg_name) ? $invBillPayInfo->bill_reg_name : ''}}" disabled/>
                                </div>
                            </div>
                        </div>--}}
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2"><label for="document_number" class="">Document Number </label></div>
                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" value="{{isset($invBillPayInfo->document_no) ? $invBillPayInfo->document_no : ''}}" disabled/>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-2"><label for="narration" class="required">Narration </label></div>
                        <div class="col-md-10 form-group">
                                <textarea class="form-control" id="narration" name="narration" rows="3" placeholder=""
                                          disabled>{{isset($invBillPayInfo->narration) ? $invBillPayInfo->narration : ''}}</textarea>
                        </div>
                    </div>

                    <h6> <span class="border-bottom-secondary border-bottom-1 text-bold-600">Party Leader Info</span></h6>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_id" class="">Party/Vendor ID </label></div>
                                <div class="col-md-3 form-group">
                                    <input type="text" class="form-control"  name="vendor_id" value="{{isset($invBillPayInfo->vendor_id) ? $invBillPayInfo->vendor_id : ''}}" readonly/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_name" class="">Party/Vendor Name </label></div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->vendor_name) ? $invBillPayInfo->vendor_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_category" class="">Party/Vendor Category </label></div>
                                <div class="col-md-9 form-group">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->vendor_category_name) ? $invBillPayInfo->vendor_category_name : ''}}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 offset-1 pl-3">
                            <div class="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{\App\Enums\YesNoFlag::YES}}" name="internal_bill_pay_yn"  id="internal_bill_pay_yn"
                                           {{isset($invBillPayInfo->internal_bill_pmt_yn) && ($invBillPayInfo->internal_bill_pmt_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} disabled>
                                    <label class="form-check-label" for="internal_bill_pay_yn"> Internal Bill Payment </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 pl-0">
                                    <label for="cheque_pay_type_id" class="col-form-label">Cheque Payment Type</label>
                                    <input class="form-control form-control-sm" name="cheque_pay_type_id" id="cheque_pay_type_id"
                                           value="{{isset($invBillPayInfo->cheque_pmt_type) ? $invBillPayInfo->cheque_pmt_type : ''}}" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center mt-1 mb-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_bills_payable">O/S Bills Payable(A)</label>
                                <input type="text" id="os_bills_payable" class="form-control text-right" value="{{isset($invBillPayInfo->os_bill_payable) ? $invBillPayInfo->os_bill_payable : ''}}" disabled />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_prepayments">O/S Prepayments(B)</label>
                                <input type="text" id="os_prepayments" class="form-control text-right" value="{{isset($invBillPayInfo->os_prepayment) ? $invBillPayInfo->os_prepayment : ''}}" disabled />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="os_security_deposits">O/S Security Deposits(C)</label>
                                <input type="text" id="os_security_deposits" class="form-control text-right" value="{{isset($invBillPayInfo->os_security_deposit) ? $invBillPayInfo->os_security_deposit : ''}}" disabled />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="good_for_payment">Good For Payment(A-B)</label>
                                <input type="text" id="good_for_payment" class="form-control text-right" value="{{isset($invBillPayInfo->good_for_payment) ? $invBillPayInfo->good_for_payment : ''}}" disabled />
                            </div>
                        </div>
                    </div>

                    {{--<div class="d-flex justify-content-between align-items-center p-0">
                        <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Invoice References</span></h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Y" name="internal_bill_pay_yn" id="internal_bill_pay_yn"
                                   {{ ( ($invBillPayInfo->gl_subsidiary_id != \App\Enums\Common\GlSubsidiaryParams::TAX) || ($invBillPayInfo->gl_subsidiary_id == null) ) ? 'checked' : ''}} disabled>
                            <label class="form-check-label pr-2" for="internal_bill_pay_yn"> Bill Payment </label>

                            <input class="form-check-input" type="checkbox" value="Y" name="tax_pay_yn" id="tax_pay_yn"
                                   {{isset($invBillPayInfo->gl_subsidiary_id) && ($invBillPayInfo->gl_subsidiary_id == \App\Enums\Common\GlSubsidiaryParams::TAX) ? 'checked' : ''}} disabled>
                            <label class="form-check-label" for="tax_pay_yn"> Tax Payment </label>
                        </div>
                    </div>--}}
                    <h6><span class="border-bottom-secondary border-bottom-1 text-bold-600">Invoice References</span></h6>
                    @if ( ( ($invBillPayInfo->gl_subsidiary_id != \App\Enums\Common\GlSubsidiaryParams::TAX) || ($invBillPayInfo->gl_subsidiary_id == null) ) )
                        <div class="row">
                        <div class="col-md-12 table-responsive fixed-height-scrollable">
                            <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                                <thead class="thead-light sticky-head">
                                <tr>
                                    <th>#SL No</th>
                                    <th>Document No</th>
                                    <th>Document Date</th>
                                    {{--<th>Document Ref</th>--}}
                                    <th>Invoice Type</th>
                                    <th>Invoice Amount</th>
                                    <th>Party ID</th>
                                    <th>Party Name</th>
                                    <th>Due Amount</th>
                                    <th>Payment Amount</th>
                                </tr>
                                </thead>
                                <tbody id="invRefList">
                                @if(count($invReferenceList) > 0)
                                    @php $index=1; $totalDue = 0; @endphp
                                    @foreach ($invReferenceList as $value)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $value->document_no }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
                                            {{--<td>{{ $value->document_ref }}</td>--}}
                                            <td>{{ $value->invoice_type_name }}</td>
                                            <td class="text-right">{{ $value->invoice_amount }}</td>
                                            <td class="text-right">{{ $value->vendor_id }}</td>
                                            <td class="text-right">{{ $value->vendor_name }}</td>
                                            <td class="text-right">{{ $value->payment_due }}</td>
                                            <td class="text-right">{{ $value->payment_amount }}</td>
                                        </tr>
                                        @php
                                            $index++;
                                            $totalDue += $value->payment_amount;
                                        @endphp
                                    @endforeach
                                    <tr class="font-small-3">
                                        <th colspan="8" class="text-right pr-2"> Total Selected Due Amount </th>
                                        <th id="total_due_amt" class="text-right">{{isset($totalDue) ? $totalDue : '0'}}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="9" class="text-center"> No Data Found</th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    {{-- TODO: Add this section- start : Pavel-09-05-22 --}}
                    {{--<h6> <span class="border-bottom-secondary border-bottom-1 text-bold-600">Invoice Reference (for Tax Payment)</span></h6>--}}
                    @if ( isset($invBillPayInfo->gl_subsidiary_id) && ($invBillPayInfo->gl_subsidiary_id == \App\Enums\Common\GlSubsidiaryParams::TAX) )
                        <div class="row">
                        <div class="col-md-12 table-responsive fixed-height-scrollable">
                            <table class="table table-sm table-bordered table-striped" id="inv_ref_tax_pay_table">
                                <thead class="thead-light sticky-head">
                                <tr>
                                    <th>#SL No</th>
                                    <th>Document No</th>
                                    <th>Document Date</th>
                                    {{--<th>Document Ref</th>--}}
                                    <th>Invoice Amount</th>
                                    <th>Part ID</th>
                                    <th>Party Name</th>
                                    <th>Tax Amount</th>
                                    <th>Due Amount</th>
                                    <th>Payment Amount</th>
                                </tr>
                                </thead>
                                <tbody id="invRefTaxPayList">
                                @if(count($invRefTaxPayList) > 0)
                                    @php $index=1; $totalTaxDue = 0; @endphp
                                    @foreach ($invRefTaxPayList as $value)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $value->document_no }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
                                            {{--<td>{{ $value->document_ref }}</td>--}}
                                            <td class="text-right">{{ $value->invoice_amount }}</td>
                                            <td class="text-right">{{ $value->vendor_id }}</td>
                                            <td class="text-right">{{ $value->vendor_name }}</td>
                                            <td class="text-right">{{ $value->tax_amount }}</td>
                                            <td class="text-right">{{ $value->payment_due }}</td>
                                            <td class="text-right">{{ $value->payment_amount }}</td>
                                        </tr>
                                        @php
                                            $index++;
                                            $totalTaxDue += $value->payment_amount;
                                        @endphp
                                    @endforeach
                                    <tr class="font-small-3">
                                        <th colspan="8" class="text-right pr-2"> Total Due Amount</th>
                                        <th id="total_due_tax_amt" class="text-right">{{$totalTaxDue}}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="9" class="text-center"> No Data Found</th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    {{-- TODO: Add this section- start : Pavel-09-05-22 --}}
                    <h6> <span class="border-bottom-secondary border-bottom-1 text-bold-600">Payment Adjustment:</span></h6>
                    <div class="row">
                        <div class="col-md-2"><label for="bank_id" class="required">Bank Account </label></div>
                        <div class="col-md-4 form-group pl-0">
                            <input type="text" id="bank_id" class="form-control" value="{{isset($invBillPayInfo->bank_account_name) ? $invBillPayInfo->bank_account_name : ''}}" disabled />
                        </div>
                    </div>
                    {{-- TODO: Update this section-start : Pavel-09-05-22 --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="offset-4 col-md-4 form-group pl-0 mb-0">
                                    <label for="">Amount in CCY </label>
                                </div>
                                <div class="col-md-4 form-group pl-0 mb-0">
                                    <label for="">Amount in LCY </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="bank_pay_amt_ccy" class="required">Payment Amount </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="bank_pay_amt_ccy" class="form-control text-right" value="{{isset($invBillPayInfo->payment_amount_ccy) ? $invBillPayInfo->payment_amount_ccy : ''}}" disabled />
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="bank_pay_amt_lcy" class="form-control text-right" value="{{isset($invBillPayInfo->payment_amount_lcy) ? $invBillPayInfo->payment_amount_lcy : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="adj_pre_pay_amt_ccy" class="required">Adjust Prepayments </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="adj_pre_pay_amt_ccy" class="form-control text-right" value="{{isset($invBillPayInfo->adjust_prepayments_ccy) ? $invBillPayInfo->adjust_prepayments_ccy : ''}}" disabled />
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="adj_pre_pay_amt_lcy" class="form-control text-right" value="{{isset($invBillPayInfo->adjust_prepayments_lcy) ? $invBillPayInfo->adjust_prepayments_lcy : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="fine_forfeiture_ccy" class="required">Fine/Forfeiture </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="fine_forfeiture_ccy" class="form-control text-right" value="{{isset($invBillPayInfo->fine_forfeiture_ccy) ? $invBillPayInfo->fine_forfeiture_ccy : ''}}" disabled />
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="fine_forfeiture_lcy" class="form-control text-right" value="{{isset($invBillPayInfo->fine_forfeiture_lcy) ? $invBillPayInfo->fine_forfeiture_lcy : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="bank_pay_amt_ccy" class="required">Total Amount </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="total_amount_ccy" class="form-control text-right" value="{{isset($invBillPayInfo->total_amount_ccy) ? $invBillPayInfo->total_amount_ccy : ''}}" disabled />
                                </div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="total_amount_lcy" class="form-control text-right" value="{{isset($invBillPayInfo->total_amount_lcy) ? $invBillPayInfo->total_amount_lcy : ''}}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 mt-2 ml-1">
                            <div class="row">
                                <div class="col-md-4"><label for="currency" class="">Currency</label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="currency" class="form-control" value="{{isset($invBillPayInfo->currency_code) ? $invBillPayInfo->currency_code : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="exc_rate" class="">Exchange Rate</label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" id="exc_rate" class="form-control exc_rate" value="{{isset($invBillPayInfo->exchange_rate) ? $invBillPayInfo->exchange_rate : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="cheque_no" class="required">Cheque No </label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->cheque_no) ? $invBillPayInfo->cheque_no : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label for="cheque_date" class="required">Cheque Date</label></div>
                                <div class="col-md-4 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->cheque_date) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->cheque_date)  : ''}}" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><label for="favoring" class="required">Favoring </label></div>
                        <div class="col-md-10 form-group pl-0 mb-1">
                            <textarea readonly disabled class="form-control form-control-sm" id="favoring" name="favoring" rows="2" placeholder="" maxlength="500" required>{{isset($invBillPayInfo->favoring) ? $invBillPayInfo->favoring : ''}}</textarea>
                        </div>
                    </div>
                    {{-- TODO: Update this section-end : Pavel-09-05-22 --}}
                </fieldset>

                {{--<fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Vendor Account Master</legend>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3"><label for="party_sub_ledger" >Party-Sub Ledger </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="hidden" name="party_sub_ledger" value="{{isset($invBillPayInfo->gl_subsidiary_id) ? $invBillPayInfo->gl_subsidiary_id : ''}}" />
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->gl_subsidiary_name) ? $invBillPayInfo->gl_subsidiary_name : ''}}" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_id" class="">Vendor ID </label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input type="text" class="form-control"  name="vendor_id" value="{{isset($invBillPayInfo->vendor_id) ? $invBillPayInfo->vendor_id : ''}}" readonly/>
                                </div>--}}{{--
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-info mb-1" id="vendor_search_btn">
                                        <i class="bx bx-search"></i><span class="align-middle ml-25">Search</span></button>
                                </div>--}}{{--
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_name" class="">Vendor Name </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->vendor_name) ? $invBillPayInfo->vendor_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label for="vendor_category" class="">Vendor Category </label></div>
                                <div class="col-md-9 form-group pl-0">
                                    <input type="text" class="form-control" value="{{isset($invBillPayInfo->vendor_category_name) ? $invBillPayInfo->vendor_category_name : ''}}" disabled/>
                                </div>
                            </div>
                            <div class="row text-right mt-1">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_bills_payable">O/S Bills Payable</label>
                                        <input type="text" id="os_bills_payable" class="form-control" value="{{isset($invBillPayInfo->os_bill_payable) ? $invBillPayInfo->os_bill_payable : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_advances">O/S Advances</label>
                                        <input type="text" id="os_advances" class="form-control" value="{{isset($invBillPayInfo->os_security_deposit) ? $invBillPayInfo->os_security_deposit : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="os_prepayments">O/S Prepayments</label>
                                        <input type="text" id="os_prepayments" class="form-control" value="{{isset($invBillPayInfo->os_prepayment) ? $invBillPayInfo->os_prepayment : ''}}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="good_for_payment">Good For Payment</label>
                                        <input type="text" id="good_for_payment" class="form-control" value="{{isset($invBillPayInfo->good_for_payment) ? $invBillPayInfo->good_for_payment : ''}}" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Reference</legend>
                    <div class="col-md-12 table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_ref_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                <th>#SL No</th>
                                <th>Document No</th>
                                <th>Invoice Type</th>
                                <th>Invoice Amount</th>
                                <th>Payment Amount</th>
                            </tr>
                            </thead>
                            <tbody id="invRefList">
                            @if(count($invReferenceList) > 0)
                                @php $index=1; @endphp
                                @foreach ($invReferenceList as $value)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $value->document_no }}</td>
                                        <td>{{ $value->invoice_type_name }}</td>
                                        <td>{{ $value->invoice_amount }}</td>
                                        <td>{{ $value->payable_amount }}</td>
                                    </tr>
                                    @php $index++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="5" class="text-center"> No Data Found</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </fieldset>

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Vendor Payment/Adjustment</legend>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3"><label for="bank_id" class="required">Bank Account </label></div>
                            <div class="col-md-9 form-group pl-0">
                                <input type="text" id="bank_id" class="form-control" value="{{isset($invBillPayInfo->bank_account_name) ? $invBillPayInfo->bank_account_name : ''}}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="currency" class="">Currency</label></div>
                            <div class="col-md-2 form-group pl-0">
                                <input type="text" id="currency" class="form-control" value="{{isset($invBillPayInfo->currency_code) ? $invBillPayInfo->currency_code : ''}}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="exc_rate" class="">Exchange Rate</label></div>
                            <div class="col-md-2 form-group pl-0">
                                <input type="number" id="exc_rate" class="form-control exc_rate" value="{{isset($invBillPayInfo->exchange_rate) ? $invBillPayInfo->exchange_rate : ''}}" disabled />
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="offset-3 col-md-3 form-group pl-0 mb-0">
                                <label for="">Amount in CCY </label>
                            </div>
                            <div class="col-md-3 form-group pl-0 mb-0">
                                <label for="">Amount in LCY </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="bank_pay_amt_ccy" class="required">Bank Payment </label></div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="bank_pay_amt_ccy" class="form-control" value="{{isset($invBillPayInfo->payment_amount_ccy) ? $invBillPayInfo->payment_amount_ccy : ''}}" disabled />
                            </div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="bank_pay_amt_lcy" class="form-control" value="{{isset($invBillPayInfo->payment_amount_lcy) ? $invBillPayInfo->payment_amount_lcy : ''}}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="adj_pre_pay_amt_ccy" class="required">Adjust Prepayments </label></div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="adj_pre_pay_amt_ccy" class="form-control" value="{{isset($invBillPayInfo->adjust_prepayments_ccy) ? $invBillPayInfo->adjust_prepayments_ccy : ''}}" disabled />
                            </div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="adj_pre_pay_amt_lcy" class="form-control" value="{{isset($invBillPayInfo->adjust_prepayments_lcy) ? $invBillPayInfo->adjust_prepayments_lcy : ''}}" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="pay_adj_amt_ccy" class="required">Payment/Adjustment </label></div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="pay_adj_amt_ccy" class="form-control" value="{{isset($invBillPayInfo->payment_adj_ccy) ? $invBillPayInfo->payment_adj_ccy : ''}}" disabled />
                            </div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="number" id="pay_adj_amt_lcy" class="form-control" value="{{isset($invBillPayInfo->payment_adj_lcy) ? $invBillPayInfo->payment_adj_lcy : ''}}" disabled />
                            </div>
                            --}}{{--<div class="col-md-3 pl-1">
                                <div class="custom-control custom-checkbox pl-0">
                                    <input type="checkbox"  class="custom-control-input" name="budget_head_control" id="budget_head_control"  value="{{\App\Enums\YesNoFlag::YES}}"
                                          --}}{{----}}{{-- @if (isset($coaInfo->budget_control_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled @endif
                                        {{isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}}--}}{{----}}{{-- >
                                    <label class="custom-control-label font-small-3" for="budget_head_control">Full Payment/Adjustment</label>
                                </div>
                            </div>--}}{{--
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="cheque_no" class="required">Cheque No </label></div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="text" class="form-control" value="{{isset($invBillPayInfo->cheque_no) ? $invBillPayInfo->cheque_no : ''}}" disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="cheque_date" class="required">Cheque Date</label></div>
                            <div class="col-md-3 form-group pl-0">
                                <input type="text" class="form-control" value="{{isset($invBillPayInfo->cheque_date) ? \App\Helpers\HelperClass::dateConvert($invBillPayInfo->cheque_date)  : ''}}" disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="narration" class="required">Narration </label></div>
                            <div class="col-md-9 form-group pl-0">
                                <textarea class="form-control" id="narration" rows="3" disabled >{{isset($invBillPayInfo->narration) ? $invBillPayInfo->narration : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>--}}

                @if (isset($invoice_line))
                    <fieldset class="col-md-12 border p-2">
                        <legend class="w-auto" style="font-size: 15px;">Transaction Detail
                        </legend>

                        <div class="row mt-1">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-sm table-bordered table-striped" id="ap_account_table">
                                    <thead class="thead-light sticky-head">
                                    <tr>
                                        <th width="2%" class="">Account ID</th>
                                        <th width="28%" class="">Account Name</th>
                                        <th width="10%" class="">Party ID</th>
                                        <th width="28%" class="">Party Name</th>
                                        <th width="16%" class="text-right-align">Debit</th>
                                        <th width="16%" class="text-right-align">Credit</th>
                                        {{--<th width="16%" class="text-center">Amount CCY</th>
                                        <th width="16%" class="text-center">Amount LCY</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($invoice_line as $line)
                                        <tr>
                                            <td>{{ $line->account_id }}</td>
                                            <td>{{ $line->account_name }}</td>
                                            <td>{{ $line->party_code }}</td>
                                            <td>{{ $line->party_name }}</td>
                                            <td class="text-right-align">{{ $line->debit }}</td>
                                            <td class="text-right-align">{{ $line->credit }}</td>
                                            {{--<td class="text-right-align">{{ $line->amount_ccy }}</td>
                                            <td class="text-right-align">{{ $line->amount_lcy }}</td>--}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            {{--<td></td>--}}
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    <tfoot class="thead-light">
                                        <tr>
                                            <th colspan="4" class="text-right-align">Total Amount</th>
                                            <th class="text-right-align">{{ $invoice_line[0]->total_debit }}</th>
                                            <th class="text-right-align">{{ $invoice_line[0]->total_credit }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                @endif

                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Invoice Payment Attachments</legend>
                    <div class="col-md-12 table-responsive fixed-height-scrollable">
                        <table class="table table-sm table-bordered table-striped" id="inv_pay_attach_table">
                            <thead class="thead-light sticky-head">
                            <tr>
                                <th>#SL No</th>
                                <th>Attachment Name</th>
                                <th>Attachment Type</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody id="invPayAttachList">
                            @if(count($invPaymentDocsList) > 0)
                                @php $index=1; @endphp
                                @foreach ($invPaymentDocsList as $value)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $value->doc_file_name }}</td>
                                        <td>{{ $value->doc_file_desc }}</td>
                                        <td>
                                            @if($value && $value->doc_file_name)
                                                <a  href="{{ route('invoice-bill-payment.attachment-download', [$value->doc_file_id]) }}" target="_blank"><i class="bx bx-download cursor-pointer"></i></a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @php $index++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="5" class="text-center"> No Data Found</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </fieldset>

                @include("ap.ap-common.common_authorizer")

            </form>
        </div>
    </div>
@endsection

@section('footer-script')
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
                    text: 'Invoice Payment ' + approval_status_val,
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

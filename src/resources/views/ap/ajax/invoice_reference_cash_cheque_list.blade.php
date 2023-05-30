@if(count($invRefCashChequeList) > 0)
    @php
        $index=1; $totalDue = 0;
    @endphp
    @foreach ($invRefCashChequeList as $value)
       {{-- @if(isset($value->default_select))--}}
        <tr  class="{{ isset($value->not_changeable) ? ($value->not_changeable == true) ? 'make-readonly-bg' : '' : '' }}">
            <td>
                <div class="custom-control custom-checkbox customized-checkbox d-flex justify-content-center">
                    <input {{--onclick="checkAll(this)"--}} type="checkbox"
                           @if($value->default_select == true)
                           checked
                           @endif

                           class="custom-control-input
                            @if($value->default_select == true)
                               bg-success
                            @else
                               bg-primary
                            @endif
                               inv-ref-cash-chq-check row_selector"
                           name="invoice_reference_cash_cheque[{{ $value->invoice_id }}][inv_ref_cash_chq_check]"
                           id="InvRefCashChequeSelectYN_{{ $value->invoice_id }}" value="{{ $value->invoice_id }}"/>
                    <label class="custom-control-label" for="InvRefCashChequeSelectYN_{{ $value->invoice_id }}"></label>
                    <input {{($value->order == INF) ? 'disabled' : ''}} type="hidden" value="{{$value->order}}" id="CashChequeOrder_{{ $value->invoice_id }}" name="invoice_reference_cash_cheque[{{ $value->invoice_id }}][cash_chq_check_order]"/>
                </div>
            </td>
            {{--<td>{{ $value->invoice_id }}</td>--}}
            <td>{{ $value->document_no }}</td>
            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
            {{--<td>{{ $value->invoice_type_name }}</td>--}}
            <td>{{ $value->document_ref }}</td>
            <td class="text-right">{{ $value->invoice_amount }}</td>
            <td>{{ $value->vendor_id }}</td>
            <td>{{ $value->vendor_name }}</td>
            <td class="text-right">{{ $value->payment_due }}</td>
            <td>
                <input type="text" id="inv_ref_cash_cheque_pay_amt_{{ $value->invoice_id }}"  @if(isset($value->not_changeable)) readonly @endif
                       maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       class="form-control form-control-sm text-right inv-ref-cash-cheque-pay-amt payment_amount_{{ $value->invoice_id }} {{isset($value->not_changeable) ? ' make-readonly-bg' : ''}} "
                       name="invoice_reference_cash_cheque[{{ $value->invoice_id }}][inv_ref_cash_cheque_pay_amt]"
                       value="{{ ($value->default_select == true) ? $value->payable_amount :$value->payment_due }}"
                       @if($value->default_select == false)
                       disabled
                        @endif
                />
            </td>
        </tr>
        @php $index++; @endphp
        {{--@endif--}}
        {{--@if (isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id)) {{$totalDue += $value->payment_amount}} @endif--}}
    @endforeach
     <!-- Display this <tr> when no record found while search -->
    <tr class='notfound' style="display: none">
        <th colspan="9" class="text-center">Search Data Not Found</th>
    </tr>
@else
    <tr>
        <th colspan="9" class="text-center"> No Data Found</th>
    </tr>
@endif

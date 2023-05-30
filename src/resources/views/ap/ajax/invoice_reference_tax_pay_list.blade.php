@if(count($invRefTaxPayList) > 0)
    @php
        $index=1; $totalDue = 0; $totalChecked = 0
    @endphp
    @foreach ($invRefTaxPayList as $value)
        <tr>
            <td>
                <div class="custom-control custom-checkbox customized-checkbox d-flex justify-content-center">
                    <input {{--onclick="checkAll(this)"--}} type="checkbox"
                           class="custom-control-input bg-primary inv-ref-tax-pay-check {{isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id) ? 'bg-success' : ''}} row_selector"
                        name="invoice_reference_tax[{{ $value->invoice_id }}][inv_ref_tax_pay_check]"
                        id="selectYN_{{ $value->invoice_id }}" value="{{ $value->invoice_id }}"
                           @if(isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id))
                                checked
                                @php $totalChecked++; @endphp
                           @endif/>
                    <label class="custom-control-label" for="selectYN_{{ $value->invoice_id }}"></label>
                    <input {{(isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id)) ? '' : 'disabled' }} type="hidden" value="{{(isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id)) ? 1 : $value->order }}" id="ReferenceTaxOrder_{{ $value->invoice_id }}" name="invoice_reference_tax[{{ $value->invoice_id }}][tax_order]"/>
                </div>
            </td>
            {{--<td>{{ $value->invoice_id }}</td>--}}
            <td>{{ $value->document_no }}</td>
            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
            <td>{{ $value->document_ref }}</td>
            <td class="text-right">{{ $value->invoice_amount }}</td>
            <td class="text-right">{{ $value->payment_amount }}</td>
            <td class="text-right">{{ $value->payment_due }}</td>
            <td>
                <input type="text" id="tax_pay_amt_{{ $value->invoice_id }}" maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                   class="form-control form-control-sm text-right tax-payment-amount payment_amount"
                   name="invoice_reference_tax[{{ $value->invoice_id }}][tax_payment_amt]"
                   value="{{ $value->payment_due }}"
                   {{isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id) ? '' : 'disabled'}}
                />
            </td>
        </tr>
        @php $index++; @endphp
        @if (isset($taxPayQueueInvId) && ($taxPayQueueInvId == $value->invoice_id)) {{$totalDue += $value->payment_amount}} @endif
    @endforeach
    <tr class="font-small-3">
        <th class="text-right" colspan="2">Total Selected</th>
        <th  class="text-left" id="total_checked3">{{$totalChecked}}</th>
        <th colspan="4" class="text-right pr-2"> Total Selected Due Amount</th>
        <th id="total_due_tax_amt" class="text-right">{{ $totalDue }}</th>
    </tr>
    <!-- Display this <tr> when no record found while search -->
   {{-- <tr class='notfound' style="display: none">
        <th colspan="7" class="text-center">Search Data Not Found</th>
    </tr>--}}
@else
    <tr>
        <th colspan="8" class="text-center"> No Data Found</th>
    </tr>
@endif

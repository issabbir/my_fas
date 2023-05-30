@if(count($invRefList) > 0)
    @php
        $index=1; $totalDue = 0;
    @endphp
    @foreach ($invRefList as $value)
        <tr>
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
                               inv-ref-check
{{isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id) ? 'bg-success' : ''}} row_selector"
                           name="invoice_reference[{{ $value->invoice_id }}][inv_ref_check]"
                           id="InvRefSelectYN_{{ $value->invoice_id }}" value="{{ $value->invoice_id }}"
                        {{isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id) ? 'checked' : ''}}/>
                    <label class="custom-control-label" for="InvRefSelectYN_{{ $value->invoice_id }}"></label>
                    <input {{(isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id)) ? '' : 'disabled' }} type="hidden" value="{{(isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id)) ? 1 : $value->order }}" id="ReferenceSupplyOrder_{{ $value->invoice_id }}" name="invoice_reference[{{ $value->invoice_id }}][supply_order]"/>

                </div>
            </td>
            {{--<td>{{ $value->invoice_id }}</td>--}}
            <td>{{ $value->document_no }}</td>
            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
            <td>{{ isset($value->document_ref) ? $value->document_ref : '--' }}</td>
            <td>{{ $value->invoice_type_name }}</td>
            <td class="text-right">{{ $value->invoice_amount }}</td>
            <td class="text-right">{{ $value->payable_amount }}</td>
            <td class="text-right">{{ $value->payment_due }}</td>
            <td>
                <input type="text" id="inv_ref_pay_amt_{{ $value->invoice_id }}" maxlength="17"
                       oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       class="form-control form-control-sm text-right inv-ref-pay-amt payment_amount_{{ $value->invoice_id }}"
                       name="invoice_reference[{{ $value->invoice_id }}][inv_ref_pay_amt]"
                       value="{{ ($value->default_select == true) ? $value->payable_amount :$value->payment_due }}"
                    {{isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id) ? '' : (($value->default_select == false) ? 'disabled' : '')}}
                />
            </td>
        </tr>
        @php $index++; @endphp
        @if (isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id)) {{$totalDue += $value->payment_amount}} @endif
    @endforeach
    {{--<tr class="font-small-3">
        <th colspan="8" class="text-right pr-2"> Total Selected Due Amount </th>
        <th id="total_due_amt" class="text-right">{{ $totalDue }}</th>
    </tr>--}}
    <!-- Display this <tr> when no record found while search -->
    <tr class='notfound' style="display: none">
        <th colspan="9" class="text-center">Search Data Not Found</th>
    </tr>
@else
    <tr>
        <th colspan="9" class="text-center"> No Data Found</th>
    </tr>
@endif

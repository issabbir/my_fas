@if(count($intProvList) > 0)
    @php
        $index=1; $totalDue = 0;
    @endphp
    @foreach ($intProvList as $value)
        <tr>
            <td>{{ $value->bank_name }}</td>
            <td>{{ $value->branch_name }}</td>
            <td>{{ \App\Helpers\HelperClass::dateConvert($value->investment_date) }}</td>
            <td>{{ $value->fdr_no }}</td>
            <td>{{ $value->investment_amount }}</td>
            <td>{{ $value->interest_rate }}</td>
            <td>{{ $value->provision_no_of_days }}</td>
            <td>{{ $value->provision_gross_interest }}</td>
            <td>{{ $value->provision_source_tax }}</td>
            <td>{{ $value->provision_excise_duty }}</td>
            <td>{{ $value->provision_net_interest }}</td>
            {{--<td class="text-right">{{ $value->payment_due }}</td>--}}
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
        <th colspan="11" class="text-center"> {{--&nbsp;&nbsp;--}} {{--No Data Found--}}Data available in table after load</th>
    </tr>
@endif

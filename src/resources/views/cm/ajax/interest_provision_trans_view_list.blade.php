@if (count($intProvTransViewList) > 0)
    @php
        $index=1; $totalDbtAmt = 0; $totalCrdAmt = 0;
    @endphp
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

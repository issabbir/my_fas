@php
    $totalDebit = 0;
    $totalCredit = 0;
@endphp
<table class="table table-sm table-bordered">
    <thead class="thead-dark">
        <tr>
            <th width="12%">GL Account ID</th>
            <th width="58%">GL Account Name</th>
            <th width="15%" class="text-right">Debit</th>
            <th width="15%" class="text-right">Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactionContents as $content)
            @php
                $totalDebit += $content->debit_amount;
                $totalCredit += $content->credit_amount;
            @endphp
            <tr>
                <td>{{$content->account_id}}</td>
                <td>{{$content->account_name}}</td>
                <td class="text-right">{{\App\Helpers\HelperClass::getCommaSeparatedValue($content->debit_amount)}}</td>
                <td class="text-right">{{\App\Helpers\HelperClass::getCommaSeparatedValue($content->credit_amount)}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-bordered bg-dark text-white">
        <tr>
            <td class="text-right" colspan="2">Total</td>
            <td class="text-right">{{\App\Helpers\HelperClass::getCommaSeparatedValue($totalDebit)}}</td>
            <td class="text-right">{{\App\Helpers\HelperClass::getCommaSeparatedValue($totalCredit)}}</td>
        </tr>
    </tfoot>
</table>

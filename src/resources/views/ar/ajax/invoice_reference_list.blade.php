@if(count($invRefList) > 0)
    @php
        $index=1;
    @endphp
    @foreach ($invRefList as $value)
        <tr>
            <td>
                <div class="custom-control custom-checkbox customized-checkbox d-flex justify-content-center">
                    <input {{--onclick="checkAll(this)"--}} type="checkbox" class="custom-control-input bg-primary inv-ref-check"  name="invoice_reference[{{ $value->invoice_id }}][inv_ref_check]"
                           id="selectYN_{{ $value->invoice_id }}" value="{{ $value->invoice_id }}"  />
                    <label class="custom-control-label" for="selectYN_{{ $value->invoice_id }}"></label>
                </div>
            </td>
            {{--<td>{{ $value->invoice_id }}</td>--}}
            <td>{{ $value->party_sub_ledger }}</td> {{--Add this col: Pavel-25-04-22 --}}
            <td>{{ $value->document_no }}</td>
            <td>{{ \App\Helpers\HelperClass::dateConvert($value->document_date) }}</td>
            <td>{{ $value->document_ref }}</td>
            <td class="text-right">{{ $value->invoice_amount }}</td>
            {{--<td>{{ $value->vat_amount }}</td>--}} {{--Block this col: Pavel-25-04-22--}}
            <td class="text-right">{{ $value->receipt_due }}</td>
            {{-- TODO: Add this part start: Pavel-25-04-22 --}}
            <td>
                {{--<input type="number" id="receipt_amt_{{ $value->invoice_id }}" class="form-control form-control-sm text-right receipt-amount"
                       name="invoice_reference[{{ $value->invoice_id }}][receipt_amt]" value="{{ $value->receipt_due }}" disabled />--}}
                <input type="text" id="receipt_amt_{{ $value->invoice_id }}" maxlength="17" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       class="form-control form-control-sm text-right receipt-amount"
                       name="invoice_reference[{{ $value->invoice_id }}][receipt_amt]" value="{{ $value->receipt_due }}" disabled />
            </td>
            {{-- TODO: Add this part end: Pavel-25-04-22 --}}
        </tr>
        @php
            $index++;
        @endphp
    @endforeach
    {{--<tr class="font-small-3">
        <th colspan="7" class="text-right pr-2"> Total Receipt Amount </th> --}}{{--Update this col, previous Total Selected Due Amount : Pavel-25-04-22--}}{{--
        --}}{{--<th id="total_due_amt">0</th>--}}{{-- --}}{{--Block this col: Pavel-25-04-22--}}{{--
        <th id="total_receipt_amt" class="text-right">0</th> --}}{{--Add this col: Pavel-25-04-22--}}{{--
    </tr>--}}
    {{-- TODO: Add this part start: Pavel-27-04-22 --}}
    {{--<tr class="font-small-3 table-active">
        <th colspan="1"> In Words </th>
        <th colspan="7" id="total_receipt_amt_in_words"></th>
    </tr>--}}
    {{-- TODO: Add this part end: Pavel-27-04-22 --}}
    <!-- Display this <tr> when no record found while search -->
    <tr class='notfound' style="display: none">
        <th colspan="8" class="text-center">Search Data Not Found</th>
    </tr>
@else
    <tr>
        <th colspan="8" class="text-center"> No Data Found</th>
    </tr>
@endif

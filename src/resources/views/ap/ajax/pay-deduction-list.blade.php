@if(count($deductionList) > 0)


    @php
        $index=1; $totalDue = 0;
    @endphp
    @foreach ($deductionList as $value)
        <tr>

            <td>{{ $value->gl_acc_id }}</td>
            <td>{{ $value->gl_acc_name }}</td>
            <td>
                <input type="text" id="invoice_deduction{{ $value->gl_acc_id }}" maxlength="17"
                       oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       class="form-control form-control-sm text-right pay_amt_deduction payment_amount_{{ $value->gl_acc_id }}"
                       name="invoice_deduction[{{ $value->gl_acc_id }}][pay_amt_deduction]"
                       value="" />
                <input type="hidden" name="invoice_deduction[{{ $value->gl_acc_id }}][gl_acc_id]" value="{{ $value->gl_acc_id }}">
                <input type="hidden" name="invoice_deduction[{{ $value->gl_acc_id }}][gl_acc_name]" value="{{ $value->gl_acc_name }}">
            </td>
        </tr>

    @endforeach

    <tr class='notfound' style="display: none">
        <th colspan="9" class="text-center">Search Data Not Found</th>
    </tr>
@else
    <tr>
        <th colspan="9" class="text-center"> No Data Found</th>
    </tr>
@endif


<script>


    $('.pay_amt_deduction').on('keyup', function() {
        var sum = 0;

        $('.pay_amt_deduction').each(function() {
            sum += Number($(this).val());
        });


        $('#total_checked2').text(sum.toFixed(2));
        $('#total_pay_deduction').val(sum.toFixed(2));

        let al_amt = $('#total_pay_allowance').val();
        let ded_amt = $('#total_pay_deduction').val();
        let  total_net = parseFloat(al_amt) - parseFloat(ded_amt);
        $('#total_net_payable').text(total_net.toFixed(2));
        $('#net_payable_amount').val(total_net.toFixed(2));
    });
</script>

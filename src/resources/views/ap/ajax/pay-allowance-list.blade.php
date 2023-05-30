@if(count($invRefList) > 0)


    @php
        $index=1; $totalDue = 0;
    @endphp
    @foreach ($invRefList as $value)
        <tr>

            <td>{{ $value->gl_acc_id }}</td>
            <td>{{ $value->gl_acc_name }}</td>
            <td>
                <input type="text" id="invoice_allowance_{{ $value->gl_acc_id }}" maxlength="17"
                       oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       class="form-control form-control-sm text-right pay-allowance-amt payment_amount_{{ $value->gl_acc_id }}"
                       name="invoice_allowance[{{ $value->gl_acc_id }}][pay-allowance-amt]"
                       value="" />
                <input type="hidden" name="invoice_allowance[{{ $value->gl_acc_id }}][gl_acc_id]" value="{{ $value->gl_acc_id }}">
                <input type="hidden" name="invoice_allowance[{{ $value->gl_acc_id }}][gl_acc_name]" value="{{ $value->gl_acc_name }}">
            </td>
        </tr>

        @php $index++; @endphp
        @if (isset($paymentQueueInvId) && ($paymentQueueInvId == $value->invoice_id)) {{$totalDue += $value->payment_amount}} @endif
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


    $('.pay-allowance-amt').on('keyup', function() {
        console.log("Keyup allowance hoyese");
        var sum = 0;

        $('.pay-allowance-amt').each(function() {
            sum += Number($(this).val());
        });

//  let test = Number(sum);
// alert(test)

        $('#total_checked1').text(sum.toFixed(2));
        $('#total_pay_allowance').val(sum.toFixed(2));


        let al_amt = $('#total_pay_allowance').val();
        let ded_amt = $('#total_pay_deduction').val();
        let  total_net = parseFloat(al_amt) - parseFloat(ded_amt);
        // $('#total_net_payable').text(total_net.toFixed(2));


        if(isNaN(total_net)){
            $('#total_net_payable').text(0);
            $('#net_payable_amount').val(0);

        }else{
            $('#total_net_payable').text(total_net.toFixed(2));
            $('#net_payable_amount').val(total_net.toFixed(2));
        }

    });


    // $('.pay_amt_deduction').on('keyup', function() {
    //     console.log("Keyup hoyese");
    //     var alnce_sum = 0;
    //     $('.pay-allowance-amt').each(function() {
    //         alnce_sum += Number($(this).val());
    //     });
    //
    //     var deduct_sum = 0;
    //     $('.pay_amt_deduction').each(function() {
    //         deduct_sum += Number($(this).val());
    //     });
    //     $('#total_net_payable').innerText("666");
    //     console.log(alnce_sum + deduct_sum);
    // });



</script>

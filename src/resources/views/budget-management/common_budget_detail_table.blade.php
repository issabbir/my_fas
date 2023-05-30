<style>
    /*table th:nth-child(2) {
        position: sticky;
        left: 0;
        z-index: 2;
    }*/
    table tr td:nth-child(2) {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 2;
    }

    /* table tr td:nth-child(-n+2) {
         position: -webkit-sticky;
         position: sticky;
         left: 0;
         border:2px solid green;
     }*/
</style>
<table class="table table-bordered table-sm" id="budget_details_list">

    <thead class="thead-light sticky-head-budget">
    <tr>
        <th colspan="8" class=" text-center">
            Fig.Taka in lakh
        </th>
    </tr>
    <tr>
        <th class="align-top text-center">ID</th>
        <th class="align-top text-center">Head Name</th>
        {{--
                <th>{{$budget_table_head->posting_period_name}}</th>
        --}}
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col1_next_fy_proposed_header}}</pre>
        </th>
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col2_curr_fy_revised_header}}</pre>
        </th>
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col3_curr_fy_probable_header}}</pre>
        </th>
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col4_curr_fy_concurred_header}}</pre>
        </th>
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col5_curr_fy_estd_header}}</pre>
        </th>
        <th class="align-top">
            <pre
                style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col6_last_fy_prov_header}}</pre>
        </th>
    </tr>
    </thead>
    <tbody>
    @forelse($budgets as $key=>$budget)

        <tr {{ ($budget->postable_yn == 'N') ? __('style=background:#eeeeee') : __('') }} >
            <td>
                <input type="hidden" name="budget[{{$key}}][budget_head_id]" value="{{$budget->budget_head_id}}">
                <input type="hidden" name="budget[{{$key}}][budget_detail_id]" value="{{$budget->budget_detail_id}}">
                <input type="hidden" name="budget[{{$key}}][postable_yn]" value="{{$budget->postable_yn}}">
                <span style="color:#324356">{{$budget->budget_head_id}}</span>
            </td>
            <td style="margin: 0; padding: 0">

                <pre {{ ($budget->postable_yn == 'Y') ? __('style=background:#ffffff;') : __('style=background:#eeeeee;font-weight:bold') }}>{!! $budget->budget_head_name !!}</pre>
            </td>
            <td><input type="text" {{--step="0.01"--}} maxlength="10"  oninput="maxLengthValid(this); this.value = this.value.match(/\d+\.?\d{0,4}/);"
                       {{ ($budget->postable_yn == 'N') ? __('readonly tabindex=-1') : __('') }}
                       {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('readonly tabindex=-1') : __('') }}
                       name="budget[{{$key}}][est_next_fy]"  id="budget[{{$key}}][est_next_fy]" onfocusout="checkEstDecimal({{$key}})"
                       class="estNextFyAmount form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }} {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('make-readonly bg-active text-black') : __('') }}"
                       value={{number_format($budget->est_next_fy,2, '.', '') }}></td>

            <td><input type="text" {{--step="0.01"--}} maxlength="10" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       name="budget[{{$key}}][rev_curr_fy]"
                       {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('readonly tabindex=-1') : __('') }}
                       class="form-control form-control-sm w-auto valueChangeEvent revisedAmount text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly-bg bg-active text-black') : __('') }} {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{number_format($budget->rev_curr_fy, 2, '.', '')}}"></td>

            <td><input type="text" step="0.01" maxlength="10" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       {{ ($budget->postable_yn == 'N') ? __('readonly tabindex=-1') : __('') }}
                       readonly
                       name="budget[{{$key}}][probable_amt]"
                       class="form-control form-control-sm w-auto valueChangeEvent probableAmount text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{number_format($budget->probable_amt,2, '.', '')}}"></td>

            <td><input type="text" step="0.01" maxlength="10" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       @if ($budget->postable_yn == 'N') readonly
                       @elseif (($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->curr_fy_concurred_amt_allow_yn == 'N')*/) readonly
                      @endif
                       {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('readonly tabindex=-1') : __('') }}
                       name="budget[{{$key}}][concurred_amt]"
                       class="form-control form-control-sm w-auto valueChangeEvent concurredAmount text-right-align @if ($budget->postable_yn == 'N') make-readonly bg-active text-black @elseif (($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->curr_fy_concurred_amt_allow_yn == 'N')*/) make-readonly bg-active text-black @endif {{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{number_format($budget->concurred_amt,2, '.', '')}}"></td>

            <td><input type="text" step="0.01" maxlength="10" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"

                       @if ($budget->postable_yn == 'N') readonly
                       @elseif ( ($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->curr_fy_estd_amount_allowed_yn == 'N')*/ ) readonly
                       tabindex="-1" @endif
                       {{--{{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::REVISED) ? __('readonly tabindex=-1') : __('') }}--}}
                       name="budget[{{$key}}][est_curr_fy]"
                       class="estCurrFyAmount form-control form-control-sm w-auto valueChangeEvent text-right-align @if ($budget->postable_yn == 'N') make-readonly bg-active text-black @elseif ( ($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->curr_fy_estd_amount_allowed_yn == 'N')*/ ) make-readonly bg-active text-black  @endif {{--{{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::REVISED) ? __('make-readonly bg-active text-black') : __('') }}--}}"
                       value="{{number_format($budget->est_curr_fy,2, '.', '')}}"></td>

            <td><input type="text" step="0.01" maxlength="10" oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       @if ($budget->postable_yn == 'N') readonly
                       @elseif (($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->last_fy_prov_amount_allowed_yn == 'N')*/) readonly
                       tabindex="-1" @endif
                       {{--{{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::REVISED) ? __('readonly tabindex=-1') : __('') }}--}}
                       name="budget[{{$key}}][prov_last_fy]"
                       class="provLastFyAmount form-control form-control-sm w-auto valueChangeEvent text-right-align @if ($budget->postable_yn == 'N') make-readonly bg-active text-black @elseif (($budget->postable_yn == 'N') /*&& ($budget_estimation_policy->last_fy_prov_amount_allowed_yn == 'N')*/) make-readonly bg-active text-black @endif {{--{{ ($estimationType == \App\Enums\BudgetManagement\InitializationType::REVISED) ? __('make-readonly bg-active text-black') : __('') }}--}}"
             x          value="{{number_format($budget->prov_last_fy,2, '.', '')}}"></td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No Data found</td>
        </tr>
    @endforelse
    <!-- Display this <tr> when no record found while search -->
    <tr class='notfound' style="display: none">
        <th colspan="7" class="text-center">Search Data Not Found</th>
    </tr>
    </tbody>
</table>
<script type="text/javascript">

    // const input = document.getElementById("input");
    // input.addEventListener("focusout", checkDecimal);
    //
    // function checkDecimal() {
    //     const value = input.value.trim();
    //
    //     // Check if the value is a decimal
    //     if (/^\d+(\.\d+)?$/.test(value)) {
    //         // Value is a decimal
    //         console.log(`The input value "${value}" is a decimal.`);
    //     } else {
    //         // Value is not a decimal, so append ".00" to the end
    //         input.value = `${value}.00`;
    //         console.log(`The input value "${input.value}" is not a decimal, so ".00" has been appended to the end.`);
    //     }
    // }



function checkEstDecimal(key){
    let id = document.getElementById('budget['+key+'][est_next_fy]');
    focusOut (id);
}



    $(".revisedAmount , .concurredAmount").on('keyup', function (e) {
        if ((e.which == 109) || (e.which == 189)) {
            let number = $(this).val();
            if (!nullEmptyUndefinedChecked(number)) {
                number.replace(String.fromCharCode(number.charCodeAt(0)), '')
            }
        }else{
            calculateProbableAmount(this)
        }

       /* let probableAmount = $(this).closest("tr").find(".probableAmount").val();
        console.log(probableAmount,"H1");

        let concurredAmount = $(this).closest("tr").find(".concurredAmount").val();

        console.log(concurredAmount,"H2");

        let revisedAmount = (parseFloat(nullEmptyUndefinedChecked(probableAmount) ? 0 : probableAmount) + parseFloat(nullEmptyUndefinedChecked(concurredAmount) ? 0 : concurredAmount)).toFixed(2);

        $(this).closest("tr").find(".revisedAmount").val(revisedAmount);*/
    })

    function calculateProbableAmount(selector) {
        let revisedAmount = 0;
        let concurredAmount = 0;
        if ($(selector).hasClass('revisedAmount')){
            revisedAmount = parseFloat(!nullEmptyUndefinedChecked($(selector).val()) ? $(selector).val() : 0);
            concurredAmount = parseFloat(!nullEmptyUndefinedChecked($(selector).closest("tr").find('.concurredAmount').val()) ? $(selector).closest("tr").find('.concurredAmount').val() : 0);
        }

        if ($(selector).hasClass('concurredAmount')){
            concurredAmount = parseFloat(!nullEmptyUndefinedChecked($(selector).val()) ? $(selector).val() : 0);
            revisedAmount = parseFloat(!nullEmptyUndefinedChecked($(selector).closest("tr").find('.revisedAmount').val()) ? $(selector).closest("tr").find('.revisedAmount').val() : 0 );
        }

        let probableAmount = roundToTwoDecimalWithoutRule(revisedAmount - concurredAmount);

        if (probableAmount < 0){
            $(selector).closest("tr").find('.probableAmount').addClass('bg-warning').val(probableAmount);
        }else{
            $(selector).closest("tr").find('.probableAmount').removeClass('bg-warning').val(probableAmount);
        }
    }

    /*$('.probableAmount, .concurredAmount').keyup(function (e) {
        if ((e.which == 109) || (e.which == 189)) {
            let number = $(this).val();

            if (!nullEmptyUndefinedChecked(number)) {
                $(this).val(parseFloat(number.replace(String.fromCharCode(number.charCodeAt(0)), '')));
            }
        }

        let probableAmount = $(this).closest("tr").find(".probableAmount").val();
        let concurredAmount = $(this).closest("tr").find(".concurredAmount").val();

        let revisedAmount = (parseFloat(nullEmptyUndefinedChecked(probableAmount) ? 0 : probableAmount) + parseFloat(nullEmptyUndefinedChecked(concurredAmount) ? 0 : concurredAmount)).toFixed(2);
        $(this).closest("tr").find(".revisedAmount").val(revisedAmount);
        //alert($(this).val());
        //alert(probableAmount+'=='+concurredAmount+'==='+revisedAmount);

    });*/

    //===Previous JS===//
    /*$(".probableAmount, .concurredAmount").on('input', function () {
        calculateRevisedAmount(this);
    })
    function calculateRevisedAmount(selector) {
        let probableAmount = parseFloat($(selector).val());
        let concurredAmount = parseFloat($(selector).parent().next('td').find('input[type=number]').val());
        let revisedAmount = probableAmount + concurredAmount;
        $(selector).parent().prev('td').find('input[type=number]').val(revisedAmount);
    }*/
    /*** Implementation Change: Open 2nd last 2 Columns Budget Head For Previous Budget Data Entry ***/

    /*$(".fixed-height-scrollable-4kp").on('scroll',function () {
        if ($(this).scrollTop() + $(this).innerWidth() >= $(this)[0].scrollWidth) {
            alert('End of DIV is reached!');
        }
    })*/
</script>



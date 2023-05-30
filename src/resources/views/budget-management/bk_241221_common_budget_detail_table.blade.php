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
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col1_next_fy_proposed_header}}</pre>
        </th>
        <th class="align-top">
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col2_curr_fy_revised_header}}</pre>
        </th>
        <th class="align-top">
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col3_curr_fy_probable_header}}</pre>
        </th>
        <th class="align-top">
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col4_curr_fy_concurred_header}}</pre>
        </th>
        <th class="align-top">
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col5_curr_fy_estd_header}}</pre>
        </th>
        <th class="align-top">
            <pre style="margin-bottom: 0; text-align: center; background-color: #f2f4f4">{{$budget_table_head->col6_last_fy_prov_header}}</pre>
        </th>
    </tr>
    </thead>
    <tbody>
    @forelse($budgets as $key=>$budget)
        <tr {{ ($budget->postable_yn == 'N') ? __('style=background:#eeeeee') : __('') }}>
            <td>
                <input type="hidden" name="budget[{{$key}}][budget_head_id]" value="{{$budget->budget_head_id}}">
                <input type="hidden" name="budget[{{$key}}][budget_detail_id]" value="{{$budget->budget_detail_id}}">
                <input type="hidden" name="budget[{{$key}}][postable_yn]" value="{{$budget->postable_yn}}">
                <span style="color:#324356">{{$budget->budget_head_id}}</span>
            </td>
            <td style="margin: 0; padding: 0">
                {{--<input type="hidden" name="budget[{{$key}}][budget_head_id]" value="{{$budget->budget_head_id}}">
                <input type="hidden" name="budget[{{$key}}][budget_detail_id]" value="{{$budget->budget_detail_id}}">
                <input type="hidden" name="budget[{{$key}}][postable_yn]" value="{{$budget->postable_yn}}">--}}
                <pre {{ ($budget->postable_yn == 'Y') ? __('style=background:#ffffff;') : __('style=background:#eeeeee;font-weight:bold') }}>{!! $budget->budget_head_name !!}</pre>
            </td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)"
                       {{ ($budget->postable_yn == 'N') ? __('readonly tabindex=-1') : __('') }}
                       name="budget[{{$key}}][est_next_fy]"
                       class="form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->est_next_fy}}"></td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)" tabindex="-1"
                       name="budget[{{$key}}][rev_curr_fy]" disabled
                       class="form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->rev_curr_fy}}"></td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)"
                       {{ ($budget->postable_yn == 'N') ? __('readonly tabindex=-1') : __('') }}
                       name="budget[{{$key}}][probable_amt]"
                       class="form-control form-control-sm w-auto valueChangeEvent probableAmount text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->probable_amt}}"></td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)" tabindex="-1"
                       name="budget[{{$key}}][concurred_amt]" readonly
                       class="form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->concurred_amt}}"></td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)" tabindex="-1"
                       name="budget[{{$key}}][est_curr_fy]" readonly
                       class="form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->est_curr_fy}}"></td>
            <td><input type="number" step="0.1" maxlength="10" oninput="maxLengthValid(this)"
                       {{ ($budget->postable_yn == 'N') ? __('readonly tabindex=-1') : __('') }}
                       name="budget[{{$key}}][prov_last_fy]"
                       class="form-control form-control-sm w-auto valueChangeEvent text-right-align {{ ($budget->postable_yn == 'N') ? __('make-readonly bg-active text-black') : __('') }}"
                       value="{{$budget->prov_last_fy}}"></td>
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
    $(".probableAmount").on('input', function () {
        calculateRevisedAmount(this);
    })

    function calculateRevisedAmount(selector) {
        let probableAmount = parseFloat($(selector).val());
        let concurredAmount = parseFloat($(selector).parent().next('td').find('input[type=number]').val());
        let revisedAmount = probableAmount + concurredAmount;
        $(selector).parent().prev('td').find('input[type=number]').val(revisedAmount);
    }

    /*$(".fixed-height-scrollable-4kp").on('scroll',function () {
        if ($(this).scrollTop() + $(this).innerWidth() >= $(this)[0].scrollWidth) {
            alert('End of DIV is reached!');
        }
    })*/


</script>



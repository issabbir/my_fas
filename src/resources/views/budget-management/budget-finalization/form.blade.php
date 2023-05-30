<?php
/**
 *Created by PhpStorm
 *Created at ২২/১১/২১ ৪:৫০ PM
 */
?>
<form id="budget_finalization" action="#" method="post">
    <div class="form-group row mt-1">
        <label for="search_fiscal_year" class="col-md-2 col-form-label">Financial Year</label>
        <select required name="fiscal_year" class="form-control col-md-3" id="search_fiscal_year">
            @foreach($data['financialYear'] as $year)
                <option
                    {{ ((isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id : '') == $year->fiscal_year_id) ? __('selected') : '' }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-sm table-bordered table-hover" id="budgetListTable">
                <thead class="thead-dark">
                <tr>
                    <th>Financial Year</th>
                    <th>Department/Cost Center</th>
                    <th>Initialization Period</th>
                    <th>Initialization Date</th>
                    <th class="text-center">Workflow Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-between">
            <a target="_blank" class="btn btn-outline-dark btn-md" href="{{url('/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/RPT_DETAIL_OF_ESTIMATED_BUDGET_FINAL_REPORT_CONSOLIDATED.xdo&type=pdf&filename=estimated_budget_final_report_consolidated)')}}">Consolidate Final Budget Report</a>
            <button class="btn btn-outline-dark btn-md finalizationBudget" type="button">Finalize Budget Process
            </button>
        </div>
    </div>
</form>

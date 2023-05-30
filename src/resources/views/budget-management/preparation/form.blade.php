<form id="budget_initialize" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row mt-2">
        {{--<h5 class="ml-2" style="text-decoration: underline">Department-wise Budget Initialization</h5>--}}
        <fieldset class="col-md-12 border">
            <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Initialization Parameter</legend>

            <input type="hidden" name="budget_master" id="budget_master"
                   value="{{isset($data['insertedData']) ? $data['insertedData']->budget_master_id : ''}}">
            <input type="hidden" name="submission_type" id="submission_type">

            <div class="row">
                <div class="form-group col-sm-3 mb-50">
                    <label for="fiscal_year" class="required col-form-label">Financial Year</label>
                    <select required name="fiscal_year"
                            class="form-control form-control-sm required {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                            id="fiscal_year">
                        @foreach($data['financialYear'] as $year)
                            <option
                                {{ (old('fiscal_year',(isset($data['insertedData']) ? $data['insertedData']->fiscal_year_id : '')) == $year->fiscal_year_id) ? __('selected') : '' }} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3 mb-50">
                    <label for="department" class="col-form-label required">Dept/Cost Center</label>
                    <select required name="department"
                            class="form-control form-control-sm {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                            style="width: 100%" id="department"
                            data-predpt="{{ old('department',(isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id : ''))}}">
                    </select>
                </div>
                <div class="form-group col-sm-3 mb-50">
                    <label for="estimation_type" class="col-form-label required">Initialization Type</label>
                    <select required name="estimation_type"
                            class="form-control form-control-sm {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                            style="width: 100%" id="estimation_type"
                            data-predpt="">
                        <option {{ (old('estimation_type',(isset($data['insertedData']) ? $data['insertedData']->budget_estimation_type : '')) == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? 'selected' : ''}} value="{{\App\Enums\BudgetManagement\InitializationType::INITIALIZATION}}">Initial Budget</option>
                        <option {{ (old('estimation_type',(isset($data['insertedData']) ? $data['insertedData']->budget_estimation_type : '')) == \App\Enums\BudgetManagement\InitializationType::REVISED) ? 'selected' : 'selected'}} value="{{\App\Enums\BudgetManagement\InitializationType::REVISED}}">Revised Budget</option>
                    </select>
                </div>

                <div class="form-group col-sm-3 mb-50">
                    <label for="initialization_period" class="required col-form-label">Initialization
                        Period</label>
                    <select required name="initialization_period"
                            class="form-control form-control-sm required {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                            data-preperiod="{{ old('initialization_period',(isset($data['insertedData']) ? $data['insertedData']->budget_init_period_id : ''))}}"
                            id="initialization_period">
                    </select>
                </div>
                {{--<div class="form-group col-sm-3 mb-50">
                    <label for="initialization_date_field" class="required col-form-label ">Initialization
                        Date</label>
                    <div
                        class="input-group date initialization_date {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                        id="initialization_date"
                        data-target-input="nearest">
                        <input required type="text" autocomplete="off" onkeydown="return false"
                               {{(isset($data['insertedData']) ? __('readonly') : '')}}
                               name="initialization_date"
                               id="initialization_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#initialization_date"
                               data-toggle="datetimepicker"
                               value="{{ old('initialization_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->budget_init_date) : '') }}"
                               data-predefined-date="{{ old('initialization_date', isset($data['insertedData']) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->budget_init_date) : '') }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append initialization_date" data-target="#initialization_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text ">
                                <i class="bx bxs-calendar font-size-small"></i>
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="form-group col-md-6 mb-50">
                    <label for="remarks" class="col-form-label">Remarks</label>
                    <textarea maxlength="500" name="remarks"
                              class="required form-control form-control-sm valueChangeEvent {{ isset($data['insertedData']) ? (($data['insertedData']->workflow_status_id != 0) ? 'make-readonly-bg' : '') : '' }}"
                              id="remarks" rows="1"
                              cols="50">{{ old('remarks',(isset($data['insertedData']) ? $data['insertedData']->budget_init_remarks : ''))}}</textarea>
                </div>
                <div class="form-group col-md-6" style="margin-top: 2rem!important;">
                    <button type="button" class="btn btn-outline-info btn-sm"
                            data-loadfor="{{isset($data['insertedData']) ? __('U') : 'I'}}"
                            {{isset($data['insertedData']) ? __('disabled') : ''}} id="load_budget_detail">Load
                        Budget Details
                    </button>
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-md-10 offset-2 pl-0">
                    <button type="button" class="col-md-3 btn btn-outline-info" {{isset($data['insertedData']) ? __('disabled') : ''}} id="load_budget_detail">Load Budget
                        Details
                    </button>
                </div>
            </div>--}}

        </fieldset>

        <fieldset class="border pl-2 pr-2" id="budgetDetail">
            <legend class="w-auto" style="font-size: 14px; "><strong>Budget Details</strong></legend>
            {{--<div class="row">
                <div class="offset-9 col-md-3 form-group" id="budgetSearchBox">
                    <div class="position-relative has-icon-left">
                        <input type="text" id="table_search" class="form-control form-control-sm" placeholder="Search Value"/>
                        <div class="form-control-position"><i class="bx bx-search"></i></div>
                    </div>
                </div>
            </div>--}}
            <div class="row">
                <div
                    class="col-md-12 table-responsive fixed-height-scrollable-4kp {{--{{ isset($data['insertedData']) ? (($data['insertedData']->workflow_status_id != 0) ? 'make-readonly-bg' : '') : '' }}--}}"
                    id="budget_lists">
                    {{--<table class="table table-bordered">
                        <thead class="thead-light sticky-head">
                        <tr>
                            <th>ID</th>
                            <th>Head Name</th>
                            <th>EST. Next FY</th>
                            <th>REV. Curr FY</th>
                            <th>Probable Amt</th>
                            <th>Concurred Amt</th>
                            <th>EST. Curr FY</th>
                            <th>PROV. Last FY</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>--}}
                </div>
            </div>
        </fieldset>
    </div>

    <section
        class="col-md-12 {{ isset($data['insertedData']) ? (($data['insertedData']->workflow_status_id != 0) ? 'make-readonly-bg' : '') : '' }}">
        @include('fas-common.common_file_upload')
    </section>

    <div class="row mt-1">
        <div class="col-md-12 d-flex">
            @if (isset($data['insertedData']))
                {{--0003246: Budget Estimation Training issue (UI Modification Needed)--}}
                @if (($data['insertedData']->workflow_status_id == \App\Enums\BudgetManagement\BudgetWorkflowStatus::BUDGET_INITIALIZE) || ($data['insertedData']->workflow_status_id == \App\Enums\BudgetManagement\BudgetWorkflowStatus::BUDGET_FINALIZE) )
                    <button type="button" data-submission_type="{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}"
                            {{(isset($data['insertedData']) ? __('disabled') : '')}}
                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                            class="btn btn-success mr-1" id="budgetFormDraftBtn"><i
                            class="bx bx-save"></i>Save
                    </button>
                    {{--<button type="button" class="btn btn-dark {{(isset($data['insertedData']) ? '' : __('d-none'))}}">
                        <i class="bx bx-printer"></i>Print
                    </button>--}}
                    <a target="_blank"
                       href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/RPT_DETAIL_OF_ESTIMATED_BUDGET_DRAFT_REPORT_DEPARTMENT_WISE.xdo&p_fiscal_year_id={{$data['insertedData']->fiscal_year_id}}&p_cost_center_dept_id={{$data['insertedData']->cost_center_dept_id}}&type=pdf&filename=estimated_budget_draft_department_wise"
                       class="btn btn-dark cursor-pointer">Print
                        <i class="bx bx-printer"></i>
                    </a>
                    <button type="button" data-submission_type="{{\App\Enums\BudgetManagement\SubmissionType::SUBMIT}}"
                            {{(isset($data['insertedData']) ? '' : __('disabled'))}}
                            class="btn btn-success ml-1" id="submitBudgetBtn">
                        <i class="bx bx-save"></i>Submit
                    </button>
                    <a type="button" href="{{route('preparation.index')}}"
                       class="btn btn-dark ml-1">
                        <i class="bx bx-reset"></i>Refresh
                    </a>
                @else
                    {{--<button type="button" class="btn btn-dark {{(isset($data['insertedData']) ? '' : __('d-none'))}}">
                        <i class="bx bx-printer"></i>Print
                    </button>--}}
                    <a target="_blank"
                       href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/RPT_DETAIL_OF_ESTIMATED_BUDGET_DRAFT_REPORT_DEPARTMENT_WISE.xdo&p_fiscal_year_id={{$data['insertedData']->fiscal_year_id}}&p_cost_center_dept_id={{$data['insertedData']->cost_center_dept_id}}&type=pdf&filename=estimated_budget_draft_department_wise"
                       class="btn btn-dark cursor-pointer">Print
                        <i class="bx bx-printer"></i>
                    </a>
                    <a type="button" href="{{route('preparation.index')}}"
                       class="btn btn-dark ml-1">
                        <i class="bx bx-reset"></i>Refresh
                    </a>
                @endif
            @else
                <button type="button" data-submission_type="{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}"
                        class="btn btn-success mr-1" id="budgetFormDraftBtn"><i
                        class="bx bx-save"></i>Save
                </button>
                <button type="button" class="btn btn-dark" disabled>
                    <i class="bx bx-printer"></i>Print
                </button>
                <button type="button" disabled
                        class="btn btn-success ml-1">
                    <i class="bx bx-save"></i>Submit
                </button>
            @endif

        </div>
        {{--<div class="col-md-6 ml-1">
            <h6 class="text-primary">Last Posting Batch ID
                <span
                    class="badge badge-light-primary badge-pill font-medium-2 align-middle">{{isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : ''}}</span>
            </h6>
            --}}{{--<div class="form-group row ">
                <label class="col-form-label col-md-4" for="last_batch_id">Last Posting Batch ID</label>
                <input type="text" readonly tabindex="-1" class="form-control col-md-4" id="last_batch_id" value="{{ isset($lastGlTranMst->trans_batch_id) ? $lastGlTranMst->trans_batch_id : '' }}" />
            </div>--}}{{--
        </div>--}}
    </div>
</form>


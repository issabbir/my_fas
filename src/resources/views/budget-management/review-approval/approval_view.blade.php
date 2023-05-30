@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        #progressbar li {
            width: 12.50% !important;
        }

        span.badge.badge-pill {
            font-size: x-small;
        }
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center pb-0">
            <h4 class="card-title font-weight-bold">Budget Review & Approval</h4>
            <a href="{{route('review-approval.index')}}">
                <span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span>
            </a>
        </div>
        <hr>
        <div class="card-body pt-0">
            {{--<h4><span class="border-bottom-secondary border-bottom-2">Budget Review & Approval</span></h4>--}}
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                {{--Workflow steps start--}}
                {{--{!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_BUDGET_MGT_MASTER, App\Enums\WkReferenceColumn::BUDGET_MASTER_ID, $budgetMaster->budget_master_id, \App\Enums\WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL) !!}--}}
                {!! \App\Helpers\HelperClass::workflow( \App\Enums\WkReferenceTable::FAS_BUDGET_EST_MASTER, App\Enums\WkReferenceColumn::BUDGET_MASTER_ID, $budgetMaster->budget_master_id, \App\Enums\WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL) !!}
                {{--Workflow steps end--}}
            </div>

            <form id="budget-review-approval-form" action="#" method="post">
                @csrf
                <div class="row mt-2">
                    <fieldset class="col-md-12 border">
                        <legend class="w-auto" style="font-size: 14px; font-weight: bold">Budget Initialization
                            Parameter
                        </legend>
                        <input type="hidden" name="budget_master_id"
                               value="{{isset($budgetMaster->budget_master_id) ? $budgetMaster->budget_master_id : ''}}">
                        <input type="hidden" name="initialization_period_id" value="{{isset($budgetMaster->budget_init_period_id) ? $budgetMaster->budget_init_period_id : ''}}">
                        <input type="hidden" name="wk_map_id" value="{{isset($wkMapId) ? $wkMapId : ''}}">
                        <div class="row">
                            <div class="form-group col-sm-3 mb-50">
                                <label for="fiscal_year" class="required col-form-label">Financial Year</label>
                                <input name="fiscal_year" class="form-control required make-readonly-bg"
                                       id="fiscal_year" value="{{$budgetMaster->fiscal_calendar_name}}"/>
                            </div>
                            <div class="form-group col-sm-3 mb-50">
                                <label for="department" class="col-form-label required">Dept/Cost Center</label>
                                <input name="department" class="form-control required make-readonly-bg"
                                       id="department"
                                       value="{{ucwords(strtolower($budgetMaster->cost_center_dept_name))}}"/>
                            </div>
                            <div class="form-group col-sm-3 mb-50 make-readonly">
                                <label for="estimation_type" class="col-form-label required">Initialization Type</label>
                                <select required name="estimation_type"
                                        class=" make-readonly-bg form-control form-control-sm {{(isset($data['insertedData']) ? __('make-readonly-bg') : '')}}"
                                        style="width: 100%" id="estimation_type">
                                    <option {{ ($budgetMaster->budget_estimation_type == \App\Enums\BudgetManagement\InitializationType::INITIALIZATION) ? 'selected' : ''}} value="{{\App\Enums\BudgetManagement\InitializationType::INITIALIZATION}}">Initial Budget</option>
                                    <option {{ ($budgetMaster->budget_estimation_type == \App\Enums\BudgetManagement\InitializationType::REVISED) ? 'selected' : ''}} value="{{\App\Enums\BudgetManagement\InitializationType::REVISED}}">Revised Budget</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-3 mb-50">
                                <label for="initialization_period" class="required col-form-label">Initialization
                                    Period</label>
                                <input name="initialization_period"
                                       class="form-control required make-readonly-bg"
                                       id="initialization_period"
                                       value="{{ucwords(strtolower($budgetMaster->budget_init_period_name))}}"/>
                            </div>
                            {{--<div class="form-group col-sm-3 mb-50">
                                <label for="initialization_date_field" class="required col-form-label ">Initialization
                                    Date</label>
                                <input name="initialization_date"
                                       class="form-control required make-readonly-bg"
                                       id="initialization_date"
                                       value="{{\App\Helpers\HelperClass::dateConvert($budgetMaster->budget_init_date)}}"/>
                            </div>--}}
                            <div class="form-group col-md-6 mb-50">
                                <label for="remarks" class="col-form-label">Remarks</label>
                                <textarea maxlength="500" name="remarks" rows="1" disable class=" form-control "
                                          id="remarks">{{ old('remarks',(isset($budgetMaster->budget_init_remarks) ? $budgetMaster->budget_init_remarks : 'N/A'))}}</textarea>

                            </div>
                            {{--<div class="form-group col-md-6" style="margin-top: 2rem!important;">
                                <button type="button" class="btn btn-outline-info btn-sm"
                                        data-loadfor="{{isset($data['insertedData']) ? __('U') : 'I'}}"
                                        {{isset($data['insertedData']) ? __('disabled') : ''}} id="load_budget_detail">Load
                                    Budget Details
                                </button>
                            </div>--}}
                        </div>
                        {{--<div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="fiscal_year" class="required col-md-4 col-form-label">Financial
                                        Year</label>

                                </div>
                                <div class="form-group row">
                                    <label for="department" class="col-form-label col-md-4 required">Dept/Cost
                                        Center</label>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row d-flex justify-content-end">
                                    <label for="initialization_period" class="required col-md-4 col-form-label">Initialization
                                        Period</label>

                                </div>
                                <div class="form-group row d-flex justify-content-end">
                                    <label for="initialization_date_field" class="required col-md-4 col-form-label ">Initialization
                                        Date</label>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row pr-1">
                            <label for="remarks" class="required col-md-2 col-form-label">Remarks</label>
                        </div>--}}
                    </fieldset>
                </div>
                <div class="row mt-1">
                    <fieldset class="col-md-12 border">
                        <legend class="w-auto" style="font-size: 14px; "><strong>Budget Details</strong></legend>
                        {{--<div class="row">
                            <div class="offset-9 col-md-3 form-group ">
                                <div class="position-relative has-icon-left">
                                    <input type="text" id="table_search" class="form-control"
                                           placeholder="Search Value"/>
                                    <div class="form-control-position"><i class="bx bx-search"></i></div>
                                </div>
                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-md-12 table-responsive fixed-height-scrollable-4kp">
                                <div id="budgetDetailsList"></div>
                                {{--<table class="table table-bordered" id="budget_details_list">
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
                                    <tbody id="budgetDetailsList"></tbody>
                                </table>--}}
                            </div>
                        </div>
                    </fieldset>
                </div>
                {{-- <div class="row mt-1">
                     <fieldset class="col-md-12 border">
                         <legend class="w-auto " style="font-size: 14px;"><strong> Attachments</strong></legend>
                         <div class="row">
                             <div class="col-md-12 table-responsive fixed-height-scrollable">
                                 <table class="table table-sm table-bordered table-striped" id="inv_pay_attach_table">
                                     <thead class="thead-light sticky-head">
                                     <tr>
                                         <th>#SL No</th>
                                         <th>Attachment Name</th>
                                         <th>Attachment Type</th>
                                         <th>Download</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     @if(count($budgetMasterDocsList) > 0)
                                         @php $index=1; @endphp
                                         @foreach ($budgetMasterDocsList as $value)
                                             <tr>
                                                 <td>{{ $index }}</td>
                                                 <td>{{ $value->doc_file_name }}</td>
                                                 <td>{{ $value->doc_file_desc }}</td>
                                                 <td>
                                                     @if($value && $value->doc_file_name)
                                                         <a href="{{ route('budget-mgt-download.download-budget-mgt-attachment', [$value->doc_file_id]) }}"
                                                            target="_blank"><i class="bx bx-download cursor-pointer"></i></a>
                                                     @else
                                                         N/A
                                                     @endif
                                                 </td>
                                             </tr>
                                             @php $index++; @endphp
                                         @endforeach
                                     @else
                                         <tr>
                                             <th colspan="5" class="text-center"> No Data Found</th>
                                         </tr>
                                     @endif
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </fieldset>
                 </div>--}}
                <section
                    class="col-md-12 {{ ($wkRefStatus != 0) ? 'make-readonly-bg' : ''  }}">
                    @include('fas-common.common_file_upload')
                </section>
                @if (isset($wkRefStatus) && $wkRefStatus == \App\Enums\ApprovalStatus::PENDING)
                    <div class="row mt-1">
                        <div class="col-md-12 d-flex">
                            {{--<button type="button" class="btn btn-dark"><i class="bx bx-reset"></i>Print</button>
                            <button type="submit" class="btn btn-success ml-1"><i class="bx bx-save"></i>&nbsp; Approve</button>--}}

                            <input type="hidden" name="approve_save_value" id="approve_save_value" value=""/>
                            <button type="button" class="btn btn-success approve-save-btn mr-1" name="form_save"
                                    value="{{ App\Enums\BudgetManagement\SubmissionType::SAVE }}"><i
                                    class="bx bx-save"></i><span
                                    class="align-middle ml-25"></span>Save
                            </button>
                            <a target="_blank"
                               href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/RPT_DETAIL_OF_ESTIMATED_BUDGET_DRAFT_REPORT_DEPARTMENT_WISE.xdo&p_fiscal_year_id={{$budgetMaster->fiscal_year_id}}&p_cost_center_dept_id={{$budgetMaster->cost_center_dept_id}}&type=pdf&filename=estimated_budget_draft_department_wise"
                               class="btn btn-dark cursor-pointer mr-1"><i class="bx bx-printer"></i>&nbsp; Print
                            </a>
                            <button type="button" class="btn btn-primary approve-save-btn mr-1" name="form_authorize"
                                    value="{{ App\Enums\ApprovalStatus::APPROVED }}"><i
                                    class="bx bx-check-double"></i><span
                                    class="align-middle ml-25"></span>Submit {{--Approve--}}
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function budgetDetailsList() {
            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/budget-management/ajax/review-approval-budget-details-list',
                data: $('#budget-review-approval-form').serialize(),
                success: function (data) {
                    $('#budgetDetailsList').html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function tableSearchAllColumns() {
            // Search all columns
            $('#table_search').keyup(function () {
                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('#budget_details_list tbody tr').hide();

                // Count total search result
                var len = $('#budget_details_list >tbody tr:not(.notfound) td:contains("' + search + '")').length;

                if (len > 0) {
                    // Searching text in columns and show match row
                    $('#budget_details_list tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                        $(this).closest('tr').show();
                    });
                } else {
                    $('.notfound').show();
                }

            });
            // Case-insensitive searching (Note - remove the below script for Case sensitive search )
            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });
        }

        function budgetReviewApprovalList() {
            $('#budget-review-approval-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/budget-management/review-approval-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'fiscal_calendar_name', name: 'fiscal_calendar_name'},
                    {data: 'cost_center_dept_name', name: 'cost_center_dept_name'},
                    {data: 'budget_init_date', name: 'budget_init_date'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'Action', "orderable": false},
                ]
            });
        }

        function reviewApprovalFrom() {
            /*$('#budget-review-approval-form').submit(function (e) {*/
            $(".approve-save-btn").click(function (e) {
                e.preventDefault();
                let action_url = window.location.href;
                //let form = this;
                //alert(window.location.href);

                let department_name = $("#department").val();
                let approval_status = $(this).val();
                let approval_status_val;

                $('#approve_save_value').val(approval_status);

                if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
                    approval_status_val = 'Approve Budget Data for ' + department_name + '?';
                } else {
                    approval_status_val = 'Save Budget Data for ' + department_name + '?';
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: approval_status_val,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (result) {
                    if (result.value) {
                        let request = $.ajax({
                            url: APP_URL + "/budget-management/review-approval-store",
                            //data: new FormData(form),
                            data: new FormData($("#budget-review-approval-form")[0]),
                            processData: false,
                            contentType: false,
                            dataType: "JSON",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (res.response_code == "1") {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    if (approval_status == '{{\App\Enums\BudgetManagement\SubmissionType::SAVE}}') {
                                        window.location.href = action_url;
                                    } else {
                                        let url = '{{ route('review-approval.index') }}';
                                        window.location.href = url;
                                    }
                                });
                            } else {

                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                            //Swal.fire({text:textStatus+jqXHR,type:'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
            });
        }

        $(document).ready(function () {
            budgetReviewApprovalList();
            budgetDetailsList();
            tableSearchAllColumns();
            reviewApprovalFrom();
        });

    </script>
@endsection





@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="card-title">Budget Head Setup</h4>
                    <a href="{{route('budget-head.budget-head-index')}}"><span class="badge badge-primary font-small-4"><i
                                class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
                </div>
                <!-- Table Start -->
                <div class="card-body pt-0">
                    <hr>
                    @if(Session::has('message'))
                        <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form class="form form-horizontal" id="budget_head_setup" action="#" method="post">
                        <div class="form-body {{--row d-flex justify-content-center--}}">
                            <div class="row mb-1">
                                <div class="col-md-2"><label>Head ID </label></div>
                                <div class="col-md-3">
                                    <input type="text" id="budget_head_id" class="form-control form-control-sm"
                                           name="budget_head_id"
                                           placeholder="Auto"
                                           maxlength="10"
                                           oninput="maxLengthValid(this)"
                                           value="{{old('budget_head_id',isset($headInfo->budget_head_id) ? $headInfo->budget_head_id : '')}}"
                                           disabled/>
                                </div>
                                @if(isset($headInfo->budget_head_id))
                                    @method('PUT')
                                @endif
                                <div class="col-md-2"><label class="required ">Opening Date </label></div>
                                <div class="col-md-2">
                                    <div
                                        class="input-group date opening_date make-readonly"
                                        id="opening_date" data-target-input="nearest">
                                        <input type="text" name="opening_date" id="opening_date_field" required readonly
                                               class="form-control form-control-sm datetimepicker-input opening_date"
                                               data-target="#opening_date" data-toggle="datetimepicker"
                                               value="{{old('opening_date',isset($headInfo->opening_date) ? App\Helpers\HelperClass::dateConvert($headInfo->opening_date)  : $date->cur_date)}}"
                                               {{--data-predefined-date=""--}}
                                               placeholder="DD-MM-YYYY">
                                        <div class="input-group-append opening_date" data-target="#opening_date"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar font-size-small"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="required">Head Name </label></div>
                                <div class="col-md-10">
                                    <input maxlength="200" type="text" id="head_name"
                                           class="form-control form-control-sm"
                                           name="head_name" placeholder="" required
                                           {{--@if(isset($headInfo->gl_acc_id)) readonly @endif--}}
                                           value="{{old('head_name',isset($headInfo->budget_head_name) ? $headInfo->budget_head_name : '')}}"/>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="required">Budget Type </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm"
                                        tabindex="-1"
                                        data-default="{{old('budget_type',isset($headInfo->budget_type_id) ? $headInfo->budget_type_id : '')}}"
                                        name="budget_type" id="budget_type" required>
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($budgetTypes as $value)
                                            <option value="{{$value->budget_type_id}}"
                                                {{old('budget_type',isset($headInfo->budget_type_id) && $headInfo->budget_type_id == $value->budget_type_id ? 'selected' : '')}}>{{ $value->budget_type_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="required">Budget Category </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm"
                                        data-default="{{old('category',isset($headInfo->budget_category_id) ? $headInfo->budget_category_id : '')}}"
                                        tabindex="-1" name="category" id="category" required>
                                        <option value="">&lt;Select&gt;</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="required">Budget Sub-Category </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm"
                                        data-default="{{old('category',isset($headInfo->budget_sub_category_id) ? $headInfo->budget_sub_category_id : '')}}"
                                        tabindex="-1" name="sub_category" id="sub_category" required>
                                        <option value="">&lt;Select&gt;</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-2 pr-0">
                                    <label class="required">Postable/Non-postable </label>
                                </div>
                                <div class="col-md-5">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2">
                                            <fieldset>
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input "
                                                           name="head_posting"
                                                           id="non_postable" required
                                                           onclick="checkUncheckAccPost(this)"
                                                           value="{{\App\Enums\YesNoFlag::NO}}"
                                                        {{isset($headInfo->postable_yn) && ($headInfo->postable_yn == \App\Enums\YesNoFlag::NO) ? 'checked' : ''}}/>
                                                    <label class="custom-control-label"
                                                           for="non_postable">Non-Postable</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                            <div
                                                class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                <input type="radio" class="custom-control-input" name="head_posting"
                                                       id="postable" required
                                                       onclick="checkUncheckAccPost(this)"
                                                       value="{{\App\Enums\YesNoFlag::YES}}"
                                                    {{!isset($headInfo->postable_yn) ? 'checked' : ''}}  {{--@if(isset($headInfo->gl_acc_id)) disabled @endif--}}
                                                    {{isset($headInfo->postable_yn) && ($headInfo->postable_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} />
                                                <label class="custom-control-label" for="postable">Postable</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-2">
                                    <label class="{{isset($headInfo) ?  (isset($headInfo->budget_parent_id) ? 'required' : '') : 'required'}} parent_head_id_label">Parent Head ID </label>
                                </div>
                                <div class="col-md-3">
                                    <input maxlength="10" type="text" id="selected_parent_head_id"
                                           class="form-control form-control-sm" name="parent_head_id"
                                           @if(isset($headInfo->budget_parent_id)) readonly tabindex="-1" @endif
                                           placeholder="Parent Head ID" {{isset($headInfo) ?  (isset($headInfo->budget_parent_id) ? 'required' : '') : 'required'}}
                                           onkeyup="resetInputData()"
                                           oninput="maxLengthValid(this)"
                                           value="{{old('parent_head_id',isset($headInfo->budget_parent_id) ? $headInfo->budget_parent_id : '')}}"/>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-info btn-sm btn-block"
                                            id="search_parent_head">
                                        <i class="bx bx-search font-size-small"></i><span
                                            class="align-middle ml-25">Search</span></button>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-2">
                                    <label class="{{isset($headInfo) ?  (isset($headInfo->budget_parent_id) ? 'required' : '') : 'required'}} parent_head_name_label">Parent Head Name </label>
                                </div>
                                <div class="col-md-6">
                                    <input maxlength="100" type="text" id="selected_parent_head_name"
                                           class="form-control form-control-sm" name="parent_head_name"
                                           placeholder="Parent Head Name"
                                           readonly tabindex="-1" {{isset($headInfo) ?  (isset($headInfo->budget_parent_id) ? 'required' : '') : 'required'}}
                                           value="{{old('parent_budget_name',isset($headInfo->head_parent_info->budget_head_name) ? $headInfo->head_parent_info->budget_head_name : '')}}"/>
                                </div>
                            </div>


                            <fieldset class="border pl-1 pr-1">
                                <legend class="w-auto" style="font-size: 15px;">Cost Center Mapping for Budget
                                    Estimation
                                </legend>

                                {{--{{ (!empty(\App\Helpers\HelperClass::findRoleWiseUser())) ? '' : 'test' }}--}}
                                <div class="row mb-1">
                                    <div class="col-md-2 pr-0">
                                        <label class="">Cost Center Type </label>
                                    </div>
                                    <div class="col-md-5 pl-0">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2">
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input "
                                                           name="center_type" id="department" required
                                                           onclick="enableDisableDptCluster(this)"
                                                           value="{{\App\Enums\Common\LCostCenterType::DEPARTMENT}}"
                                                        {{!isset($headInfo->cost_center_cluster_id) ? 'checked' : ''}}
                                                        {{isset($headInfo->cost_center_dept_id) ? 'checked' : ''}}/>
                                                    <label class="custom-control-label"
                                                           for="department">Department</label>
                                                </div>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input"
                                                           name="center_type"
                                                           id="cluster" required
                                                           onclick="enableDisableDptCluster(this)"
                                                           value="{{\App\Enums\Common\LCostCenterType::CLUSTER}}"
                                                        {{isset($headInfo->cost_center_cluster_id) ? 'checked' : ''}} />
                                                    <label class="custom-control-label" for="cluster">Department
                                                        Cluster</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label for="department_id" class="">Department </label>
                                    </div>
                                    <div
                                        class="col-md-6 pl-0 {{isset($headInfo) ? (isset($headInfo->cost_center_dept_id) ? '' : 'make-select2-readonly-bg' ) : '' }}">
                                        <select class="form-control form-control-sm select2 "
                                                name="department_id"
                                                id="department_id">
                                            <option value="">&lt;Select&gt;</option>
                                            @foreach($dptCostCenterList as $value)
                                                <option value="{{$value->cost_center_dept_id}}"
                                                    {{old('dept_cost_center_id',isset($headInfo->cost_center_dept_id) && $headInfo->cost_center_dept_id == $value->cost_center_dept_id ? 'selected' : '')}}>{{ $value->cost_center_dept_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label for="cluster_id" class="">Department Cluster</label>
                                    </div>
                                    <div
                                        class="col-md-6 pl-0 {{isset($headInfo) ? ( isset($headInfo->cost_center_cluster_id) ? '' : 'make-select2-readonly-bg') : 'make-select2-readonly-bg' }} ">
                                        <select class="form-control form-control-sm select2"
                                                name="cluster_id"
                                                id="cluster_id">
                                            <option value="">&lt;Select&gt;</option>
                                            @foreach($dptClusterList as $value)
                                                <option value="{{$value->cost_center_cluster_id}}"
                                                    {{old('dept_cost_center_id',isset($headInfo->cost_center_cluster_id) && $headInfo->cost_center_cluster_id == $value->cost_center_cluster_id ? 'selected' : '')}}>{{ $value->cost_center_cluster_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border pl-1 pr-1">
                                <legend class="w-auto" style="font-size: 15px;">Cost Center Mapping for Exceptional Budget
                                    Concurrence
                                </legend>

                                {{--{{ (!empty(\App\Helpers\HelperClass::findRoleWiseUser())) ? '' : 'test' }}--}}
                                <div class="row mb-1">
                                    <div class="col-md-2 pr-0">
                                    </div>
                                    <div class="col-md-5  pl-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Y"
                                                   name="booking_check"
                                                   onclick="enableDisableBookingDept(this)"
                                                   id="booking_check" {{isset($headInfo) ?  ((isset($headInfo->budget_booking_req_yn) && ($headInfo->budget_booking_req_yn == 'Y')) ? 'checked' : '') : 'checked'}} >
                                            <label class="form-check-label" for="booking_check">
                                                Budget Booking Required
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label class="booking_dept_label">Booking Department </label>
                                    </div>
                                    <div
                                        class="col-md-6 pl-0 {{isset($headInfo) ?  (isset($headInfo->budget_booking_dept_id) ? '' : 'make-select2-readonly-bg') : ''}}">
                                        <select class="form-control form-control-sm select2"
                                                name="booking_dept"
                                                id="booking_dept">
                                            <option value="">&lt;Select&gt;</option>
                                            @foreach($dptCostCenterList as $value)
                                                <option value="{{$value->cost_center_dept_id}}"
                                                    {{old('booking_dept',isset($headInfo->budget_booking_dept_id) && $headInfo->budget_booking_dept_id == $value->cost_center_dept_id ? 'selected' : '')}}>{{ $value->cost_center_dept_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border pl-1 pr-1">
                                <legend class="w-auto" style="font-size: 15px;">GL Account Mapping
                                </legend>

                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label class=" parent_acc_code_label">GL Account ID </label>
                                    </div>
                                    <div class="col-md-3 pl-0">
                                        <input maxlength="10" type="text" id="selected_parent_acc_code"
                                               class="form-control form-control-sm" name="parent_acc_code"
                                               onfocusout="addZerosInAccountId(this)"
                                               placeholder="Account ID"
                                               onkeyup="resetInputData()"
                                               oninput="maxLengthValid(this)"
                                               value="{{old('parent_acc_code',isset($headInfo->gl_acc_id) ? $headInfo->gl_acc_id : '')}}"/>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-info btn-block btn-sm"
                                                id="search_parent_acc">
                                            <i class="bx bx-search font-size-small"></i><span
                                                class="align-middle ml-25">Search</span></button>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label class=" parent_acc_name_label">GL Account Name </label>
                                    </div>
                                    <div class="col-md-6 pl-0">
                                        <input type="text" id="selected_parent_acc_name"
                                               class="form-control form-control-sm" name="parent_acc_name"
                                               placeholder="Account Name"
                                               readonly tabindex="-1"
                                               value="{{old('parent_acc_name',isset($headInfo->gl_coa) ? $headInfo->gl_coa->gl_acc_name : '')}}"/>
                                    </div>
                                </div>
                            </fieldset>

                            @if(isset($headInfo->gl_acc_id))
                                <fieldset class="border pl-1 pr-1">
                                    <legend class="w-auto" style="font-size: 15px;">Budget Head Status</legend>

                                    {{--{{ (!empty(\App\Helpers\HelperClass::findRoleWiseUser())) ? '' : 'test' }}--}}
                                    <div class="row mb-1">
                                        <div class="col-md-2 pr-0">
                                        </div>
                                        <div class="col-md-5 pl-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="Y"
                                                       onclick="checkUncheckBudgetIna(this)"
                                                       name="inactive_yn"
                                                       id="inactive_yn" {{ isset($headInfo->inactive_yn) ? (($headInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : '') : '' }}>
                                                <label class="form-check-label" for="inactive_yn">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-2"><label class="inactive_date_label">Inactive Date </label>
                                        </div>
                                        <div class="col-md-2 pl-0">
                                            <div class="input-group date inactive_date make-readonly"
                                                 id="inactive_date" data-target-input="nearest">
                                                <input type="text" name="inactive_date" id="inactive_date_field" readonly
                                                       class="form-control form-control-sm datetimepicker-input inactive_date"
                                                       @if(!isset($headInfo->inactive_date)) tabindex="-1" @endif
                                                       data-target="#inactive_date" data-toggle="datetimepicker"
                                                       value="{{ isset($headInfo->inactive_yn) ? ($headInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? App\Helpers\HelperClass::dateConvert($headInfo->inactive_date) : '' : '' }}"
                                                       data-predefined-date="{{ isset($headInfo->inactive_yn) ? (($headInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? App\Helpers\HelperClass::dateConvert($headInfo->inactive_date) : \App\Helpers\HelperClass::getCurrentDate()) :  \App\Helpers\HelperClass::getCurrentDate()}}"
                                                       placeholder="DD-MM-YYYY">
                                                <div class="input-group-append inactive_date"
                                                     data-target="#inactive_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar font-size-small"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            @endif
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-sm mr-1">@if(isset($headInfo->budget_head_id))
                                            Update @else Save @endif</button>
                                    <a href="{{route('budget-head.budget-head-index')}}" class="btn btn-dark btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Head Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <div class="mr-1 mb-1 d-inline-block">
                    <div class="modal fade text-left w-100" id="budgetHeadModal" tabindex="-1" role="dialog"
                         aria-labelledby="budgetHeadModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="budgetHeadModalLabel">Budget Head Information</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1">
                                        <fieldset class="border p-2 col-md-12">
                                            <legend class="w-auto" style="font-size: 14px; "><strong>Budget Head
                                                    Tree</strong></legend>
                                            <div class="col-md-12" id="budget_head_tree">
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i
                                            class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Budget Head Modal end -->

    <!-- Account TypeWise Coa Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <div class="mr-1 mb-1 d-inline-block">
                    <div class="modal fade text-left w-100" id="coaCodeModal" tabindex="-1" role="dialog"
                         aria-labelledby="coaCodeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="coaCodeModalLabel">Coa Code Information</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1 acc-name-sec">
                                        <fieldset class="border p-2 col-md-12">
                                            <legend class="w-auto" style="font-size: 14px; "><strong>Chart Of Accounts
                                                    Tree</strong></legend>
                                            <div class="col-md-12" id="coa_tree">
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i
                                            class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Account TypeWise Coa Modal end -->

@endsection

@section('footer-script')
    <script type="text/javascript">

        function checkUncheckAccPost(selector) {
            let postNonPostVal = $(selector).val();
            if (postNonPostVal == "{{\App\Enums\YesNoFlag::YES}}") {    //Postable
                $(".parent_head_id_label").addClass('required');
                $(".parent_head_name_label").addClass('required');
                $("#selected_parent_head_id").attr('required', 'required');

                /*Budget Booking for concurrence is not required*/
                //$(".booking_dept_label").addClass("required");
                $("#booking_check").prop("checked",true);
                $("#booking_dept").parent().removeClass('make-select2-readonly-bg');
            } else {        //Non-postable
                $(".parent_head_id_label").removeClass('required');
                $(".parent_head_name_label").removeClass('required');
                $("#selected_parent_head_id").removeAttr('required');

                /*Budget Booking for concurrence is not required*/
                $("#booking_check").prop("checked",false);
                //$(".booking_dept_label").removeClass("required");
                $("#booking_dept").select2().val("").trigger('change');
                $("#booking_dept").parent().addClass('make-select2-readonly-bg');
            }
        }

        function enableDisableDptControl(status) {
            if (status == false) {
                $("#allow_dept_cost_center_cot").prop("disabled", true);
                $("#dept_cost_center_id").prop("disabled", true);

                $("#allow_dept_cost_center_cot").prop("checked", false);
                $('#dept_cost_center_id').val('').trigger('change');
            } else {
                $("#allow_dept_cost_center_cot").prop("disabled", false);

                $("#allow_dept_cost_center_cot").prop("checked", false);
                $('#dept_cost_center_id').val('').trigger('change');
            }
        }

        function enableDisableBudgetHead(status) {
            if (status == false) {
                $("#budget_head_control").prop("disabled", true);
                $("#budget_head_id").prop("disabled", true);
                $(".budget-modal-btn").prop("disabled", true);


                $("#budget_head_control").prop("checked", false);
                $('#budget_head_id').val('');
                $('#budget_head_name').val('');
            } else {
                $("#budget_head_control").prop("disabled", false);

                $("#budget_head_control").prop("checked", false);
                $('#budget_head_id').val('');
                $('#budget_head_name').val('');
            }
        }

        $("#allow_dept_cost_center_cot").on("click", function () {
            if (this.checked) {
                $("#dept_cost_center_id").removeAttr("disabled", false);
            } else {
                $("#dept_cost_center_id").attr("disabled", true);
                $("#dept_cost_center_id").val('').trigger('change');
            }
        });
        $("#budget_head_control").on("click", function () {
            if (this.checked) {
                $("#budget_head_id").removeAttr("disabled", false);
                $(".budget-modal-btn").prop("disabled", false);
            } else {
                $("#budget_head_id").val('').attr("disabled", true);
                $(".budget-modal-btn").prop("disabled", true);
            }
        });

        function checkUncheckBudgetIna(that) {
            if ($(that).is(":checked")) {
                /*$(".inactive_date_label").addClass('required');
                $("#inactive_date_field").attr('required', 'required');*/
                datePickerTop('#inactive_date');
            } else {
                $("#inactive_date").datetimepicker('destroy');
                $("#inactive_date").children('input').val('');

                /*$(".inactive_date_label").removeClass('required');
                $("#inactive_date_field").removeAttr('required');*/
            }
        }

        /***Budget Head Tree Start***/
        function searchBudgetHeads() {
            $("#search_parent_head").on("click", function () {
                let headId = $("#selected_parent_head_id").val();
                let budgetType = $("#budget_type option:selected").val();
                let category = $("#category option:selected").val();
                let subCategory = $("#sub_category option:selected").val();

                if (!nullEmptyUndefinedChecked(headId) && !nullEmptyUndefinedChecked(budgetType) && !nullEmptyUndefinedChecked(category) && !nullEmptyUndefinedChecked(subCategory)) {
                    getBudgetHeadDetail(headId);
                } else {
                    if (nullEmptyUndefinedChecked(budgetType)) {
                        $("#budget_type").notify("Please Select Budget Type", "error");
                        $('html, body').animate({scrollTop: ($("#budget_type").offset().top - 400)}, 2000);

                    } /*else if (nullEmptyUndefinedChecked(category)) {
                        $("#category").notify("Please Select Category", "error");
                        $('html, body').animate({scrollTop: ($("#category").offset().top - 400)}, 2000);

                    } else if (nullEmptyUndefinedChecked(subCategory)) {
                        $("#sub_category").notify("Please Select Sub Category", "error");
                        $('html, body').animate({scrollTop: ($("#sub_category").offset().top - 400)}, 2000);
                    } */ else {
                        getHeadTree();
                    }

                }
            });
        }

        function getHeadTree() {
            let response = $.ajax({
                url: APP_URL + '/budget-management/ajax/budget-head-code-tree',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    type_id: $("#budget_type option:selected").val(),
                    category_id: $("#category option:selected").val(),
                    subcategory_id: $("#sub_category option:selected").val()
                }
            });
            response.done(function (e) {
                $("#budget_head_tree").html(e);
                $("#budgetHeadModal").modal('show');
            });
        }

        function getBudgetHeadDetail(budgetHeadId) {
            $.ajax({
                type: 'GET',
                url: APP_URL + '/general-ledger/ajax/budget-head-details/' + budgetHeadId,
                success: function (data) {
                    if ($.isEmptyObject(data)) {
                        $("#selected_parent_head_id").notify("Head id not found", "error");
                    } else {
                        $('#selected_parent_head_id').val(data.budget_head_id);
                        $('#selected_parent_head_name').val(data.budget_head_name);
                        $("#budgetLineModal").modal('hide');
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function budgetHeadList() {
            /*let oTable = $('#budget-head-wise-line-list').DataTable({*/
            let oTable = $('#budget-head-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/general-ledger/coa-budget-head-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "budget_head_id"},
                    {"data": "budget_category_name"},
                    {"data": "budget_head_name"},
                    {"data": "select"}
                ],

                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

            /*$('#budget_grp_id').change(function () {
                oTable.draw();
            });*/

            $("#search_budget_heads").on("click", function () {
                let budgetHeadId = $('.selected-head-id').val();

                if (!nullEmptyUndefinedChecked(budgetHeadId)) {
                    getBudgetHeadDetail(budgetHeadId);
                } else {
                    $("#budgetHeadModal").modal('show');
                }
            });
        }

        function addSelectedBudgetHead(param) {
            let headId = $(param).attr('id');
            if (headId) {
                getBudgetHeadDetail(headId);
                $("#budgetHeadModal").modal('hide');
            }
        }

        $(document).on("click", '.head_id', function (e) {
            addSelectedBudgetHead(this);
        });

        /***Budget Head Tree END***/

        /***GL Account Tree Start***/
        function accCoaList() {
            $("#search_parent_acc").on("click", function () {
                let glAccId = $("#selected_parent_acc_code").val();
                if (!nullEmptyUndefinedChecked(glAccId)) {
                    getGlAccountDetail(glAccId);
                } else {
                    getCoaTree();
                }
            });
        }

        function getCoaTree() {
            let response = $.ajax({
                url: APP_URL + '/budget-management/ajax/coa-acc-tree',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            });
            response.done(function (e) {
                $("#coa_tree").html(e);
                $("#coaCodeModal").modal('show');
            });
        }

        function getGlAccountDetail(glAccId) {
            $.ajax({
                type: 'GET',
                url: APP_URL + '/budget-management/ajax/coa-info-details/' + glAccId,
                success: function (data) {
                    if ($.isEmptyObject(data)) {
                        $("#selected_parent_acc_code").notify("Account id not found", "error");
                    } else {
                        $('#selected_parent_acc_code').val(data.gl_acc_id);
                        $('#selected_parent_acc_name').val(data.gl_acc_name);
                        $("#coaCodeModal").modal('hide');
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function addSelectedCoa(param) {
            let glAccId = $(param).attr('id');
            if (glAccId) {
                getGlAccountDetail(glAccId);
                $("#coaCodeModal").modal('hide');
            }
        }

        $(document).on("click", '.gl-coa', function (e) {
            addSelectedCoa(this);
        });

        /***GL Account Tree End***/


        function getCategories() {
            /*For edit view*/
            categoryRequest("#budget_type");

            /*For edit view*/
            function categoryRequest(selector) {
                $("#category").empty().val("");
                $("#sub_category").empty().val("");
                let type = $(selector).find(':selected').val();
                if (!nullEmptyUndefinedChecked(type)) {
                    let request = $.ajax({
                        url: APP_URL + '/budget-management/ajax/get-categories/' + type + '/' + $("#category").data('default')
                    });
                    request.done(function (data) {
                        $("#category").append(data.options);
                        subCategoryRequest("#category");
                    })
                    request.fail(function (jqxhr, text) {
                        console.log(jqxhr);
                    });
                }
            }

            $("#budget_type").on('change', function () {
                categoryRequest(this);
            });
        }

        function subCategoryRequest(selector) {
            $("#sub_category").empty().val("");
            let category = $(selector).find(':selected').val();
            if (!nullEmptyUndefinedChecked(category)) {
                let request = $.ajax({
                    url: APP_URL + '/budget-management/ajax/get-sub-categories/' + category + '/' + $("#sub_category").data('default')
                });
                request.done(function (data) {
                    $("#sub_category").append(data.options);
                })
                request.fail(function (jqxhr, text) {
                    console.log(jqxhr);
                });
            }
        }

        function getSubCategories() {
            $("#category").on('change', function () {
                subCategoryRequest(this);

            });
        }

        function enableDisableDptCluster(selector) {
            let department = $(selector).val();
            resetField(["#department_id", "#cluster_id"]);
            if (department == "{{\App\Enums\Common\LCostCenterType::DEPARTMENT}}") {
                $("#department_id").parent().removeClass('make-select2-readonly-bg');
                $("#cluster_id").parent().addClass('make-select2-readonly-bg');
            } else {
                $("#department_id").parent().addClass('make-select2-readonly-bg');
                $("#cluster_id").parent().removeClass('make-select2-readonly-bg');
            }
        }

        function enableDisableBookingDept(that) {
            if (!$(that).is(":checked")) {
                $("#booking_dept").select2().val("").trigger('change');
                //$(".booking_dept_label").removeClass("required");
                $("#booking_dept").parent().addClass('make-select2-readonly-bg');
            } else {
                //$(".booking_dept_label").addClass("required");
                $("#booking_dept").parent().removeClass('make-select2-readonly-bg');
            }
        }

        function resetInputData() {
            if (nullEmptyUndefinedChecked({{isset($coaInfo->gl_acc_id) ? $coaInfo->gl_acc_id : ''}})) {
                resetField(['#selected_parent_acc_name', '.selected-head-name']);
            } else {
                resetField(['.selected-head-name']);
            }
        }

        $("#budget_head_setup").on("submit", function (e) {
            e.preventDefault();
            let text = "Save Budget Head?";
            let id = $("#budget_head_id").val();
            if (!nullEmptyUndefinedChecked(id)) {
                text = "Update Budget Head?";
            }
            let urlPostfix = !nullEmptyUndefinedChecked(id) ? "/"+id:"";

            swal.fire({
                text: text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showLoaderOnConfirm: true,
                preConfirm: (data) => {

                    return $.ajax({
                        url: APP_URL + "/budget-management/budget-head-setup" + urlPostfix,
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    }).done(res => {
                        return res;
                    }).catch(error => {
                        swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (nullEmptyUndefinedChecked(result.dismiss)) {
                    //if true comes here

                    if (result.value.response_code == 1) {
                        Swal.fire({
                            type: 'success',
                            text: result.value.response_msg,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false
                        }).then(function () {
                            if (!nullEmptyUndefinedChecked(id)) {
                                location.reload();
                            } else {
                                window.location.href = "{{route('budget-head.budget-head-setup-index')}}";
                            }
                            //window.history.back();
                        });
                    } else {
                        swal.fire({
                            text: result.value.response_msg,
                            type: 'warning',
                        })
                    }
                }
            })
        });

        $(document).ready(function () {
            getCategories();
            getSubCategories();
            searchBudgetHeads()
            accCoaList();
            budgetHeadList();
            datePicker("#opening_date");
            //datePickerTop("#inactive_date");

        });
    </script>
@endsection

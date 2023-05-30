@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="card-title">Budget Head View</h4>
                    <a href="{{route('head-edit.headEditIndex')}}"><span class="badge badge-primary font-small-4"><i
                                class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
                </div>
                <!-- Table Start -->


                <div class="card-body pt-0">
                    <hr>
                    <form class="form form-horizontal" id="" action="#" method="post">
                        <div class="form-body {{--row d-flex justify-content-center--}}">
                            <div class="row mb-1">
                                <div class="col-md-2"><label>Head ID </label></div>
                                <div class="col-md-3">
                                    <input type="text" id="budget_head_id" class="form-control form-control-sm make-readonly-bg" name="acc_id"
                                           placeholder="Auto"
                                           maxlength="10"
                                           oninput="maxLengthValid(this)"
                                           value="{{old('budget_head_id',isset($headInfo->budget_head_id) ? $headInfo->budget_head_id : '')}}"
                                           disabled/>
                                </div>
                                <div class="col-md-2"><label class=" ">Opening Date </label></div>
                                <div class="col-md-2">
                                    <div
                                        class="input-group date opening_date @if(isset($headInfo->budget_head_id)) make-readonly @endif"
                                        id="opening_date" data-target-input="nearest">
                                        <input type="text" name="opening_date" id="opening_date_field"
                                               class="form-control form-control-sm datetimepicker-input opening_date"
                                               @if(isset($headInfo->budget_head_id)) tabindex="-1" @endif
                                               @if(isset($headInfo->budget_head_id)) readonly @endif
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
                                <div class="col-md-2"><label class="">Head Name </label></div>
                                <div class="col-md-9">
                                    <input maxlength="200" type="text" id="head_name"
                                           class="form-control form-control-sm make-readonly-bg"
                                           name="head_name" placeholder=""
                                           {{--@if(isset($headInfo->gl_acc_id)) readonly @endif--}}
                                           value="{{old('head_name',isset($headInfo->budget_head_name) ? $headInfo->budget_head_name : '')}}"/>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="">Budget Type </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm make-readonly-bg"
                                        tabindex="-1"
                                        name="budget_type" id="budget_type" >
                                        <option value="{{$headInfo->budget_type->budget_type_id}}">{{$headInfo->budget_type->budget_type_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="">Budget Category </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm make-readonly-bg"
                                        tabindex="-1" name="category" id="category" >
                                        <option value="{{$headInfo->budget_category->budget_category_id}}">{{$headInfo->budget_category->budget_category_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-2"><label class="">Budget Sub-Category </label></div>
                                <div class="col-md-3">
                                    <select
                                        class="form-control form-control-sm make-readonly-bg"
                                        tabindex="-1" name="sub_category" id="sub_category" >
                                        <option value="{{$headInfo->budget_sub_category->budget_sub_category_id}}">{{$headInfo->budget_sub_category->budget_sub_category_name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-2 pr-0">
                                    <label class="">Postable/Non-postable </label>
                                </div>
                                <div class="col-md-5">
                                    <ul class="list-unstyled mb-0 make-readonly">
                                        <li class="d-inline-block mr-2">
                                            <fieldset>
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input make-readonly-bg"
                                                           name="head_posting"
                                                           id="non_postable"
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
                                                <input type="radio" class="custom-control-input make-readonly-bg" name="head_posting"
                                                       id="postable"
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
                                    <label class=" parent_head_id_label">Parent Head ID </label>
                                </div>
                                <div class="col-md-3">
                                    <input maxlength="10" type="text" id="selected_parent_head_id"
                                           class="form-control form-control-sm make-readonly-bg" name="parent_head_id"
                                           @if(isset($headInfo->budget_parent_id)) readonly tabindex="-1" @endif
                                           onfocusout="addZerosInAccountId(this)"
                                           placeholder="Parent Head ID"
                                           onkeyup="resetInputData()"
                                           oninput="maxLengthValid(this)"
                                           value="{{old('parent_head_id',isset($headInfo->budget_parent_id) ? $headInfo->budget_parent_id : '')}}"/>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-info btn-sm btn-block make-readonly-bg"
                                            id="search_parent_head"
                                            @if(isset($headInfo->budget_parent_id)) disabled @endif >
                                        <i class="bx bx-search font-size-small"></i><span
                                            class="align-middle ml-25">Search</span></button>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-2">
                                    <label class=" parent_head_name_label">Parent Head Name </label>
                                </div>
                                <div class="col-md-6">
                                    <input maxlength="100" type="text" id="selected_parent_head_name"
                                           class="form-control form-control-sm make-readonly-bg" name="parent_head_name"
                                           placeholder="Parent Head Name"
                                           readonly tabindex="-1"
                                           value="{{old('parent_budget_name',isset($headInfo->head_parent_info->budget_head_name) ? $headInfo->head_parent_info->budget_head_name : '')}}"/>
                                </div>
                            </div>

                            <fieldset class="border pl-1 pr-1">
                                <legend class="w-auto" style="font-size: 15px;">Cost Center Mapping for Budget Estimation</legend>
                                <div class="row mb-1">
                                    <div class="col-md-2 pr-0">
                                        <label class="">Cost Center Type </label>
                                    </div>
                                    <div class="col-md-5 pl-0">
                                        <ul class="list-unstyled mb-0 make-readonly">
                                            <li class="d-inline-block mr-2">
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($headInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input "
                                                           name="center_type" id="department"
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
                                                    <input type="radio" class="custom-control-input "
                                                           name="center_type"
                                                           id="cluster"
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
                                    <div class="col-md-6 pl-0 make-select2-readonly-bg">
                                        <select class="form-control form-control-sm select2 "
                                                name="department_id"
                                                id="department_id">
                                            <option value="{{isset($headInfo->department) ? $headInfo->department->cost_center_dept_id : ''}}">{{isset($headInfo->department) ? $headInfo->department->cost_center_dept_name : ''}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label for="cluster_id" class="">Department Cluster</label>
                                    </div>
                                    <div class="col-md-6 pl-0  make-select2-readonly-bg">
                                        <select class="form-control form-control-sm select2"
                                                name="cluster_id"
                                                id="cluster_id">
                                            <option value="{{isset($headInfo->department_cluster) ? $headInfo->department_cluster->cost_center_cluster_id : ''}}">{{isset($headInfo->department_cluster) ? $headInfo->department_cluster->cost_center_cluster_name : ''}}</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border pl-1 pr-1">
                                <legend class="w-auto" style="font-size: 15px;">Cost Center Mapping for Exceptional Budget Concurrence
                                </legend>
                                <div class="row mb-1">
                                    <div class="col-md-2 pr-0">
                                    </div>
                                    <div class="col-md-5 pl-0">
                                        <div class="form-check  make-readonly">
                                            <input class="form-check-input" type="checkbox" value="Y" name="booking_check"
                                                   onclick="enableDisableBookingDept(this)"
                                                   id="booking_check" {{($headInfo->budget_booking_req_yn == 'Y') ? 'checked': ''}}>
                                            <label class="form-check-label" for="booking_check">
                                                Budget Booking Required
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label class="booking_dept_label ">Booking Department </label>
                                    </div>
                                    <div class="col-md-6 pl-0 make-select2-readonly-bg">
                                        <select class="form-control form-control-sm select2"
                                                name="booking_dept"
                                                id="booking_dept">
                                            <option value="{{ isset($headInfo->budget_booking_dept) ? $headInfo->budget_booking_dept->budget_booking_dept_id : ''}}">{{isset($headInfo->budget_booking_dept) ? $headInfo->budget_booking_dept->cost_center_dept_name : ''}}</option>
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
                                               class="form-control form-control-sm make-readonly-bg" name="parent_acc_code"
                                               @if(isset($headInfo->gl_acc_id)) readonly tabindex="-1" @endif
                                               onfocusout="addZerosInAccountId(this)"
                                               placeholder="Account ID"
                                               onkeyup="resetInputData()"
                                               oninput="maxLengthValid(this)"
                                               value="{{old('parent_acc_code',isset($headInfo->gl_acc_id) ? $headInfo->gl_acc_id : '')}}"/>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-info btn-block btn-sm"
                                                id="search_parent_acc" @if(isset($headInfo->gl_acc_id)) disabled @endif >
                                            <i class="bx bx-search font-size-small make-readonly"></i><span
                                                class="align-middle ml-25">Search</span></button>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-2">
                                        <label class=" parent_acc_name_label">GL Account Name </label>
                                    </div>
                                    <div class="col-md-6 pl-0">
                                        <input type="text" id="selected_parent_acc_name"
                                               class="form-control form-control-sm make-readonly-bg" name="parent_acc_name"
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
                                            <div class="form-check make-readonly">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       onclick="checkUncheckBudgetIna(this)"
                                                       id="inactive_yn" {{ isset($headInfo->inactive_yn) ? ($headInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : '' : 'checked' }}>
                                                <label class="form-check-label" for="inactive_yn">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-2"><label class="">Inactive Date </label></div>
                                        <div class="col-md-2 pl-0">
                                            <div class="input-group date inactive_date"
                                                 id="inactive_date" data-target-input="nearest">
                                                <input type="text" name="_date" id="inactive_date_field"
                                                       class="form-control form-control-sm datetimepicker-input inactive_date make-readonly-bg"
                                                       @if(!isset($headInfo->inactive_date)) tabindex="-1" @endif
                                                       data-target="#inactive_date" data-toggle="datetimepicker"
                                                       value="{{ isset($headInfo->inactive_yn) ? ($headInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? App\Helpers\HelperClass::dateConvert($headInfo->inactive_date) : '' : '' }}"
                                                       {{--data-predefined-date=""--}}
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
    </script>
@endsection

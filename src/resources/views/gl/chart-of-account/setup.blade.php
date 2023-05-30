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
                    <h4 class="card-title">@if(isset($coaInfo->gl_acc_id)) Edit @else Add @endif Chart Of Accounts
                        (COA)</h4>
                    <a href="{{route('coa.index')}}"><span class="badge badge-primary font-small-4"><i
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
                    <form class="form form-horizontal"
                          @if(isset($coaInfo->gl_acc_id)) action="{{route('coa.coa-setup-update',[$coaInfo->gl_acc_id])}}"
                          @else action="{{route('coa.coa-setup-store')}}" @endif
                          method="post">
                        @csrf
                        @if (isset($coaInfo->gl_acc_id))
                            @method('PUT')
                        @endif
                        <div class="form-body {{--row d-flex justify-content-center--}}">
                            {{--<div class="col-md-8">--}}
                            <div class="row mb-1">
                                <div class="col-md-2"><label>Account ID </label></div>
                                <div class="col-md-3 pl-0">
                                    <input type="text" id="acc_id" class="form-control form-control-sm" name="acc_id" placeholder="Auto"
                                           maxlength="10"
                                           oninput="maxLengthValid(this)"
                                           value="{{old('acc_id',isset($coaInfo->gl_acc_id) ? $coaInfo->gl_acc_id : '')}}"
                                           disabled/>
                                </div>
                                <div class="col-md-2"><label class="required ">Opening Date </label></div>
                                <div class="col-md-2">
                                    <div
                                        class="input-group date opening_date  make-readonly-bg"
                                        id="opening_date" data-target-input="nearest">
                                        <input type="text" name="opening_date" id="opening_date_field" required
                                               class="form-control form-control-sm datetimepicker-input opening_date"
                                               tabindex="-1"
                                               readonly
                                               data-target="#opening_date" data-toggle="datetimepicker"
                                               value="{{old('opening_date',isset($coaInfo->opening_date) ? App\Helpers\HelperClass::dateConvert($coaInfo->opening_date)  : $date->cur_date)}}"
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
                            <div class="row">
                                <div class="col-md-2"><label class="required">Account Name </label></div>
                                <div class="col-md-10 form-group pl-0">
                                    <input maxlength="100" type="text" id="acc_name" class="form-control form-control-sm"
                                           name="acc_name" placeholder="" required
                                           {{--@if(isset($coaInfo->gl_acc_id)) readonly @endif--}}
                                           value="{{old('acc_name',isset($coaInfo->gl_acc_name) ? $coaInfo->gl_acc_name : '')}}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><label class="">Account Code (Legacy) </label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input maxlength="10" type="text" id="acc_code_legacy" class="form-control form-control-sm"
                                           name="acc_code_legacy" placeholder=""
                                           {{--@if(isset($coaInfo->gl_acc_id)) readonly @endif--}}
                                           value="{{old('acc_code',isset($coaInfo->gl_acc_code) ? $coaInfo->gl_acc_code : '')}}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><label class="required">Account Type </label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <select
                                        class="form-control form-control-sm @if(isset($coaInfo->gl_acc_id)) make-readonly-bg"
                                        tabindex="-1" @else select2" @endif
                                    name="acc_type" id="acc_type" required>
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($accTypeList as $value)
                                        <option value="{{$value->gl_type_id}}"
                                            {{old('acc_type',(isset($coaInfo->gl_type_id) ? $coaInfo->gl_type_id : '' )) == $value->gl_type_id ? 'selected' : ''}}>{{ $value->gl_type_name}}
                                        </option>
                                        @endforeach
                                        </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="required">Currency </label>
                                </div>
                                <div class="col-md-3 form-group pl-0">
                                    <select
                                        class="custom-select form-control form-control-sm {{--@if(isset($coaInfo->gl_acc_id)) make-readonly-bg @else--}} select2 {{--@endif--}} "
                                        name="currency" required>
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($lCurList as $value)
                                            <option value="{{$value->currency_code}}"
                                                {{!isset($coaInfo->currency_code) && ($value->currency_code == \App\Enums\Common\Currencies::O_BD) ? 'selected' : ''}}
                                                {{old('currency',isset($coaInfo->currency_code) ? $coaInfo->currency_code : '') == $value->currency_code ? 'selected' : ''}} >{{$value->currency_code}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="required">Postable/Non-postable </label>
                                </div>
                                <div class="col-md-5 form-group pl-0">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($coaInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input " name="acc_posting"
                                                           id="non_postable" required
                                                           onclick="checkUncheckAccPost(this)"
                                                           value="{{\App\Enums\YesNoFlag::NO}}"
                                                        {{isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO) ? 'checked' : ''}}/>
                                                    <label class="custom-control-label"
                                                           for="non_postable">Non-Postable</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($coaInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input" name="acc_posting"
                                                           id="postable" required
                                                           onclick="checkUncheckAccPost(this)"
                                                           value="{{\App\Enums\YesNoFlag::YES}}"
                                                        {{!isset($coaInfo->postable_yn) ? 'checked' : ''}}  {{--@if(isset($coaInfo->gl_acc_id)) disabled @endif--}}
                                                        {{isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} />
                                                    <label class="custom-control-label" for="postable">Postable</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="required parent_acc_code_label">Parent Account Code </label>
                                        </div>
                                        <div class="col-md-3 form-group pl-0">
                                            <input maxlength="10" type="text" id="selected_parent_acc_code"
                                                   class="form-control form-control-sm" name="parent_acc_code"
                                                   @if(isset($coaInfo->gl_acc_id)) readonly tabindex="-1" @endif
                                                   onfocusout="addZerosInAccountId(this)"
                                                   placeholder="Parent Account Code" required
                                                   onkeyup="resetInputData()"
                                                   oninput="maxLengthValid(this)"
                                                   value="{{old('parent_acc_code',isset($coaInfo->gl_parent_id) ? $coaInfo->gl_parent_id : '')}}"/>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-info btn-sm btn-block mb-1"
                                                    id="search_parent_acc"
                                                    {{--data-toggle="modal" data-target="#coaCodeModal"--}} {{--style="display: none"--}}  @if(isset($coaInfo->gl_acc_id)) disabled @endif >
                                                <i class="bx bx-search font-size-small"></i><span
                                                    class="align-middle ml-25">Search</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="required parent_acc_name_label">Parent Account Name </label>
                                </div>
                                <div class="col-md-6 form-group pl-0">
                                    <input maxlength="100" type="text" id="selected_parent_acc_name"
                                           class="form-control form-control-sm" name="parent_acc_name" placeholder="Parent Account Name"
                                           readonly tabindex="-1" required
                                           value="{{old('parent_acc_name',isset($coaInfo->coa_parent_info->gl_acc_name) ? $coaInfo->coa_parent_info->gl_acc_name : '')}}"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-9 pl-0">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           name="allow_dept_cost_center_cot"
                                                           id="allow_dept_cost_center_cot"
                                                           value="{{\App\Enums\YesNoFlag::YES}}"
                                                           @if (isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled @endif
                                                        {{isset($coaInfo->cost_center_dept_control_yn) && ($coaInfo->cost_center_dept_control_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} >
                                                    <label class="custom-control-label"
                                                           for="allow_dept_cost_center_cot">Allow Department/Cost Center
                                                        Control</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="">Dept/Cost Center </label>
                                </div>
                                <div class="col-md-6 form-group pl-0">
                                    <select class="form-control form-control-sm select2 dept-cost-center" name="dept_cost_center_id"
                                            id="dept_cost_center_id"
                                            @if (isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled
                                            @elseif (isset($coaInfo->cost_center_dept_control_yn) && ($coaInfo->cost_center_dept_control_yn == \App\Enums\YesNoFlag::NO)) disabled
                                            @elseif (!isset($coaInfo)) disabled
                                        @endif >
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($dptCostCenterList as $value)
                                            <option value="{{$value->cost_center_dept_id}}"
                                                {{old('dept_cost_center_id',isset($coaInfo->cost_center_dept_id) && $coaInfo->cost_center_dept_id == $value->cost_center_dept_id ? 'selected' : '')}}>{{ $value->cost_center_dept_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-9 pl-0">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           name="budget_head_control" id="budget_head_control"
                                                           value="{{\App\Enums\YesNoFlag::YES}}"
                                                           @if (isset($coaInfo->budget_control_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                           @elseif (isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                           @elseif (!isset($coaInfo)) disabled
                                                        @endif
                                                        {{isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} >
                                                    <label class="custom-control-label" for="budget_head_control">Budget
                                                        Head Control</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="">Budget Head Line ID </label>
                                        </div>
                                        <div class="col-md-3 form-group pl-0">
                                            <input type="text" id="budget_head_id" class="form-control form-control-sm selected-head-id"
                                                   name="budget_head_id" placeholder="Budget Head Line ID"
                                                   @if (isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                   @elseif (isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                   @elseif (!isset($coaInfo)) disabled
                                                   @endif
                                                   onkeyup="resetInputData()"
                                                   {{--
                                                   * COA EDIT (problem: not shown parent name, budget  name). REF# email
                                                   * budget_head_line_id to budget_head_id
                                                   * Logic modified:04-04-2022
                                                   --}}
                                                   value="{{old('budget_head_id',isset($coaInfo->budget_head_id) ? $coaInfo->budget_head_id : '')}}"/>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-info btn-sm btn-block mb-1 budget-modal-btn"
                                                    id="search_budget_heads"
                                                    {{--data-toggle="modal" data-target="#budgetLineModal"--}} {{--style="display: none"--}}
                                                    @if (isset($coaInfo->budget_control_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                    @elseif (isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::NO)) disabled
                                                    @elseif (!isset($coaInfo)) disabled
                                                @endif >
                                                <i class="bx bx-search font-size-small"></i><span
                                                    class="align-middle ml-25">Search</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="">Budget Head Line Name </label>
                                </div>
                                <div class="col-md-6 form-group pl-0">
                                    <input type="text" id="budget_head_name" class="form-control form-control-sm selected-head-name"
                                           name="budget_head_name" placeholder="Budget Head Line Name"
                                           {{--@if (isset($coaInfo->budget_control_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO))--}} disabled
                                           {{--@endif--}}
                                           value="{{old('budget_head_name',isset($coaInfo->budget_head->budget_head_name) ? $coaInfo->budget_head->budget_head_name : '')}}"/>
                                </div>
                            </div>


                            @if(isset($coaInfo->gl_acc_id))
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-3 pl-0">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               name="acc_inactive" id="acc_inactive"
                                                               value="{{\App\Enums\YesNoFlag::YES}}"
                                                               onclick="checkUncheckAccIna()" {{isset($coaInfo->inactive_yn) && ($coaInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} >
                                                        <label class="custom-control-label" for="acc_inactive">Account
                                                            Inactive</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="required" for="acc_inactive_date">Inactive Date </label>
                                    </div>
                                    <div class="col-md-3 form-group pl-0">
                                        <div class="input-group date acc_inactive_date" id="acc_inactive_date"
                                             data-target-input="nearest">
                                            <input type="text" name="acc_inactive_date" id="acc_inactive_date_field"
                                                   class="form-control form-control-sm datetimepicker-input acc_inactive_date"
                                                   data-target="#acc_inactive_date" data-toggle="datetimepicker"
                                                   @if (isset($coaInfo->inactive_yn) && ($coaInfo->inactive_yn == \App\Enums\YesNoFlag::NO)) readonly
                                                   @endif
                                                   value="{{old('acc_inactive_date',isset($coaInfo->inactive_yn) ? (($coaInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? App\Helpers\HelperClass::dateConvert($coaInfo->inactive_date) : '') :  \App\Helpers\HelperClass::getCurrentDate())}}"
                                                   data-predefined-date="{{ isset($coaInfo->inactive_yn) ? (($coaInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? App\Helpers\HelperClass::dateConvert($coaInfo->inactive_date) : \App\Helpers\HelperClass::getCurrentDate()) :  \App\Helpers\HelperClass::getCurrentDate()}}"
                                                   placeholder="DD-MM-YYYY">
                                            <div class="input-group-append acc_inactive_date"
                                                 data-target="#acc_inactive_date" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="bx bx-calendar font-size-small"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm mr-1">@if(isset($coaInfo->gl_acc_id))
                                            Update @else Save @endif</button>
                                    <button type="reset" class="btn btn-light-secondary btn-sm">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>



    <!-- Account TypeWise Coa Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
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
                                    {{--
                                    * Remove table and add tree view. REF# Yousouf Imam
                                    * Logic modified:04-04-2022
                                    --}}
                                    <div class="row mt-1 acc-name-sec">
                                        <fieldset class="border p-2 col-md-12">
                                            <legend class="w-auto" style="font-size: 14px; "><strong>Chart Of Accounts
                                                    Tree</strong></legend>
                                            <div class="col-md-12" id="coa_tree">
                                                {{--<h6 class="mb-1"><strong>Account Name</strong></h6>--}}
                                            </div>
                                        </fieldset>
                                    </div>


                                    {{--<div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table id="acc-type-wise-coa-list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Account Name</th>
                                                    <th>Account Id</th>
                                                    <th>Account Code</th>
                                                    <th>Currency</th>
                                                    <th>Parent Id</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>--}}
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

    <!-- Budget Head Line Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="budgetLineModal" {{--tabindex="-1"--}} role="dialog"
                         aria-labelledby="budgetLineModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="budgetLineModalLabel">Budget Head Line Info</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card shadow-none">
                                        {{--<div class="row justify-content-center">
                                            <div class="col-md-2">
                                                <label class="required">Budget Head</label>
                                            </div>
                                            <div class="col-md-5 form-group pl-0">
                                                <select class="custom-select form-control form-control-sm select2" name="budget_grp_id" id="budget_grp_id" required>
                                                    <option value="" >Select One</option>
                                                    @foreach($budgetHeadList as $value)
                                                        <option value="{{$value->budget_group_id}}"
                                                            {{old('budget_grp_id',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->budget_group_id ? 'selected' : '')}}>{{ $value->budget_group_code.' ('.$value->budget_group_name.')'}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>--}}

                                        <h6><strong>Budget Head list</strong></h6>
                                        <hr class="mt-0 mb-0">

                                        <div class="table-responsive">
                                            {{--<table id="budget-head-wise-line-list" class="table table-sm w-100">--}}
                                            <table id="budget-head-list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    {{--<th>SL</th>
                                                    <th>Line Id</th>
                                                    <th>Line Code</th>
                                                    <th>Budget Head</th>
                                                    <th>Action</th>--}}
                                                    <th>SL</th>
                                                    <th>Head Id</th>
                                                    <th>Budget Category name</th>
                                                    <th>Budget Head Name</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
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
    <!-- Budget Head Line Modal end -->

@endsection

@section('footer-script')
    <script type="text/javascript">

        function checkUncheckAccPost(selector) {
            let postNonPostVal = $(selector).val();
            let accTypeId = $('#acc_type').val();
            let coaParams = "{{\App\Enums\Common\GlCoaParams::EXPENSE}}";

            if (postNonPostVal == "{{\App\Enums\YesNoFlag::YES}}") {
                enableDisableDptControl(true);

                if (accTypeId == coaParams) {
                    enableDisableBudgetHead(true);
                }

                $(".parent_acc_code_label").addClass('required');
                $(".parent_acc_name_label").addClass('required');
                $("#selected_parent_acc_code").attr('required','required');
                $("#selected_parent_acc_name").attr('required','required');
            } else {
                enableDisableDptControl(false);
                enableDisableBudgetHead(false);
                $(".parent_acc_code_label").removeClass('required');
                $(".parent_acc_name_label").removeClass('required');
                $("#selected_parent_acc_code").removeAttr('required');
                $("#selected_parent_acc_name").removeAttr('required');
            }
        }

        function accType() {
            $('#acc_type').change(function () {
                if (($(this).val() == '{{\App\Enums\Common\GlCoaParams::EXPENSE}}') && ($('#postable').prop('checked'))) {
                    enableDisableBudgetHead(true);
                } else {
                    enableDisableBudgetHead(false);
                }

                $('#selected_parent_acc_name').val('');
                $('#selected_parent_acc_code').val('');
            });
        }

        function enableDisableDptControl(status) {
            if (status == false) {
                $("#allow_dept_cost_center_cot").prop("disabled", true);
                $("#dept_cost_center_id").prop("disabled", true);

                $("#allow_dept_cost_center_cot").prop("checked", false);
                $('#dept_cost_center_id').val('').trigger('change');
            } else {
                $("#allow_dept_cost_center_cot").prop("disabled", false);
                //$("#dept_cost_center_id").prop("disabled", false);

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
                //$("#budget_head_id").prop("disabled", false);
                //$(".budget-modal-btn").prop("disabled", false);


                $("#budget_head_control").prop("checked", false);
                $('#budget_head_id').val('');
                $('#budget_head_name').val('');
            }
        }

        $("#allow_dept_cost_center_cot").on("click", function () {
            //e.preventDefault();
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
                //$("#budget_head_line_id").val('').trigger('change');
            }
        });

        function checkUncheckAccIna() {
            if (document.getElementById('acc_inactive').checked) {
                datePickerTop("#acc_inactive_date")
                //$("#acc_inactive_date_field").prop("disabled", false);
            } else {
                $('#acc_inactive_date_field').val('');
                //$("#acc_inactive_date_field").prop("disabled", true);
            }
        }


        function accTypeWiseCoa() {
            let oTable = $('#acc-type-wise-coa-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 20,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/general-ledger/coa-acc-type-wise-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.acc_type_id = $('#acc_type').val();
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "gl_acc_name"},
                    {"data": "gl_acc_id"},
                    {"data": "gl_acc_code"},
                    {"data": "currency_code"},
                    {"data": "gl_parent_id"},
                    {"data": "select"}
                ],

                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

            /*$('#acc_type').change(function (e) {
                e.preventDefault();
                $('#selected_parent_acc_name').val('');
                $('#selected_parent_acc_code').val('');
                //oTable.draw();
            });*/

            $("#search_parent_acc").on("click", function () {
                //e.preventDefault();
                let glAccId = $("#selected_parent_acc_code").val();
                let accType = $("#acc_type").val();

                if (!nullEmptyUndefinedChecked(glAccId) && !nullEmptyUndefinedChecked(accType)) {
                    //alert('KK');
                    //$("#coaCodeModal").modal('hide');
                    getGlAccountDetail(glAccId);
                } else {
                    //alert($('#acc_type option:selected').val());
                    if (!($('#acc_type option:selected').val())) {
                        //$.notify("Please Select Account Type", "error");
                        /**
                         * COA EDIT (problem: not shown parent name, budget  name). REF# email
                         * Notify was showing error message in wrong position on empty account type.
                         * Logic added:04-04-2022
                         * **/
                        //$("#selected_parent_acc_code").notify("Please Select Account Type", "error");
                        $("#acc_type").notify("Please Select Account Type", "error");
                        $('html, body').animate({scrollTop: ($("#acc_type").offset().top - 400)}, 2000);

                    } else {
                        getCoaTree();
                        //oTable.draw();
                    }

                }
            });
        }

        function getCoaTree() {
            let response = $.ajax({
                url: APP_URL + '/general-ledger/coa-acc-type-wise-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {acc_type_id: $('#acc_type').val()}
            });
            response.done(function (e) {
                $("#coa_tree").html(e);
                $("#coaCodeModal").modal('show');
            });
        }

        function getGlAccountDetail(glAccId) {
            let accType = $("#acc_type").val();
            $.ajax({
                type: 'GET',
                /*'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },*/
                url: APP_URL + '/general-ledger/ajax/coa-info-details/' + glAccId + '/' + accType,
                //data: {gl_acc_id: glAccId},
                success: function (data) {
                    if ($.isEmptyObject(data)) {
                        $("#selected_parent_acc_code").notify("Account id not found", "error");
                    } else {
                        $('#selected_parent_acc_name').val(data.gl_acc_name);
                        $("#coaCodeModal").modal('hide');
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        $(document).on("click", '.gl-coa', function (e) {
            //e.preventDefault();
            addSelectedCoa(this);
        });

        function addSelectedCoa(param) {
            let glAccId = $(param).attr('id');
            //var row = $(param).closest("tr").find("td:eq(1)").text();
            if (glAccId) {
                getGlAccountDetail(glAccId);
                $('#selected_parent_acc_code').val(glAccId);
                //$('#selected_parent_acc_name').val($(param).closest("tr").find("td:eq(1)").text());
                //$('#selected_parent_acc_code').val($(param).closest("tr").find("td:eq(2)").text());
                $("#coaCodeModal").modal('hide');
            }
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
                    //url: APP_URL + '/general-ledger/coa-budget-head-wise-line-list',
                    url: APP_URL + '/general-ledger/coa-budget-head-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    /*data: function (params) {
                        params.budget_grp_id = $('#budget_grp_id').val();
                    }*/
                },
                /*"columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "budget_head_line_id"},
                    {"data": "budget_head_line_code"},
                    {"data": "budget_head"},
                    {"data": "select"}
                ],*/
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
                //e.preventDefault();
                let budgetHeadId = $('.selected-head-id').val();

                if (!nullEmptyUndefinedChecked(budgetHeadId)) {
                    getBudgetHeadDetail(budgetHeadId);
                } else {
                    $("#budgetLineModal").modal('show');
                }
            });
        }

        function getBudgetHeadDetail(budgetHeadId) {

            $.ajax({
                type: 'GET',
                /*'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },*/
                //url: APP_URL + '/general-ledger/ajax/budget-head-line-details/' +budgetHeadLineId,
                url: APP_URL + '/general-ledger/ajax/budget-head-details/' + budgetHeadId,
                //data: {gl_acc_id: glAccId},
                success: function (data) {
                    //console.log(data);
                    if ($.isEmptyObject(data)) {
                        $(".selected-head-id").notify("Head id not found", "error");
                    } else {
                        $('.selected-head-name').val(data.budget_head_name);
                        $("#budgetLineModal").modal('hide');
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        $(document).on("click", '.budget-heads-data', function (e) {
            //e.preventDefault();
            addSelectedBudgetHead(this);
        });

        function addSelectedBudgetHead(param) {
            let headId = $(param).attr('id');
            //var row = $(param).closest("tr").find("td:eq(1)").text();
            if (headId) {
                $('.selected-head-id').val($(param).closest("tr").find("td:eq(1)").text());
                $('.selected-head-name').val($(param).closest("tr").find("td:eq(3)").text().trim());
                $("#budgetLineModal").modal('hide');
            }
        }

        /*** Previous Data populated old budget schema ***/
        /*function budgetHeadWiseLine(){
            let oTable = $('#budget-head-wise-line-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy : true,
                pageLength: 5,
                bFilter: true,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/general-ledger/coa-budget-head-wise-line-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: function (params) {
                            params.budget_grp_id = $('#budget_grp_id').val();
                        }
                    },
                    "columns": [
                        {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                        {"data": "budget_head_line_id"},
                        {"data": "budget_head_line_code"},
                        {"data": "budget_head"},
                        {"data": "select"}
                    ],

                    language: {
                        paginate: {
                            next: '<i class="bx bx-chevron-right">',
                            previous: '<i class="bx bx-chevron-left">'
                        }
                    }
                });

                $('#budget_grp_id').change(function () {
                    oTable.draw();
                });

                $("#search_budget_head_line").on("click", function () {
                    //e.preventDefault();
                    let budgetHeadLineId = $('.selected-line-id').val();

                    if(!nullEmptyUndefinedChecked(budgetHeadLineId)){
                        getBudgetHeadLineDetail(budgetHeadLineId);
                    }else{
                        $("#budgetLineModal").modal('show');
                    }
                });
            }

            function getBudgetHeadLineDetail(budgetHeadLineId){

                $.ajax({
                    type: 'GET',
                    /!*'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },*!/
                    url: APP_URL + '/general-ledger/ajax/budget-head-line-details/' +budgetHeadLineId,
                    //data: {gl_acc_id: glAccId},
                    success: function (data) {
                        //console.log(data);
                        $('.selected-line-name').val(data.budget_head);
                        $("#budgetLineModal").modal('hide');
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            }

            $(document).on("click",'.budget-head-line', function (e) {
                //e.preventDefault();
                addSelectedBudgetLine(this);
            });

            function addSelectedBudgetLine(param) {
                let headLineId = $(param).attr('id');
                //var row = $(param).closest("tr").find("td:eq(1)").text();
                if (headLineId){
                    $('.selected-line-id').val($(param).closest("tr").find("td:eq(1)").text());
                    $('.selected-line-name').val($(param).closest("tr").find("td:eq(3)").text());
                    $("#budgetLineModal").modal('hide');
                }
            }*/
        /*** Previous Data populated old budget schema ***/

        function resetInputData() {
            /**
             * COA EDIT (problem: not shown parent name, budget  name). REF# email
             * In edit mode resetting parent account name on budget head change forbid form submit.
             * Logic added:04-04-2022
             * **/
            if (nullEmptyUndefinedChecked({{isset($coaInfo->gl_acc_id) ? $coaInfo->gl_acc_id : ''}})) {
                resetField(['#selected_parent_acc_name', '.selected-head-name']);
            } else {
                resetField(['.selected-head-name']);
            }
        }

        $(document).ready(function () {
            accTypeWiseCoa();
            budgetHeadList();
            accType();
            datePicker("#opening_date");
        });


    </script>
@endsection

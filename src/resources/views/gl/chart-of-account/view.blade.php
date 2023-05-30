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
                    <h4 class="card-title">Details Chart Of Accounts
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
                                           name="acc_name" placeholder="" readonly
                                           {{--@if(isset($coaInfo->gl_acc_id)) readonly @endif--}}
                                           value="{{old('acc_name',isset($coaInfo->gl_acc_name) ? $coaInfo->gl_acc_name : '')}}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><label class="">Account Code (Legacy) </label></div>
                                <div class="col-md-3 form-group pl-0">
                                    <input maxlength="10" type="text" id="acc_code_legacy" class="form-control form-control-sm"
                                           name="acc_code_legacy" placeholder="" readonly
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
                                            {{old('acc_type',isset($coaInfo->gl_type_id) && $coaInfo->gl_type_id == $value->gl_type_id ? 'selected' : '')}}>{{ $value->gl_type_name}}
                                        </option>
                                        @endforeach
                                        </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="required">Currency </label>
                                </div>
                                <div class="col-md-3 form-group pl-0 make-select2-readonly-bg">
                                    <select
                                        class="custom-select form-control form-control-sm {{--@if(isset($coaInfo->gl_acc_id)) make-readonly-bg @else--}} select2 {{--@endif--}} "
                                        name="currency" required>
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($lCurList as $value)
                                            <option value="{{$value->currency_code}}"
                                                {{!isset($coaInfo->currency_code) && ($value->currency_code == \App\Enums\Common\Currencies::O_BD) ? 'selected' : ''}}
                                                {{old('currency',isset($coaInfo->currency_code) && $coaInfo->currency_code == $value->currency_code ? 'selected' : '')}} >{{$value->currency_code}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="required">Postable/Non-postable </label>
                                </div>
                                <div class="col-md-5 form-group pl-0 make-readonly">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div
                                                    class="custom-control custom-radio {{--@if(isset($coaInfo->gl_acc_id)) make-readonly-bg @endif--}} ">
                                                    <input type="radio" class="custom-control-input " name="acc_posting" readonly
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
                                                    <input type="radio" class="custom-control-input" name="acc_posting" readonly
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
                                                <div class="custom-control custom-checkbox make-readonly">
                                                    <input type="checkbox" class="custom-control-input"
                                                           name="allow_dept_cost_center_cot"
                                                           id="allow_dept_cost_center_cot"
                                                           value="{{\App\Enums\YesNoFlag::YES}}"
                                                           disabled
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
                                <div class="col-md-6 form-group pl-0 make-select2-readonly-bg">
                                    <select class="form-control form-control-sm select2 dept-cost-center" name="dept_cost_center_id"
                                            id="dept_cost_center_id"
                                            @if (isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO)) disabled
                                            @elseif (isset($coaInfo->cost_center_dept_control_yn) && ($coaInfo->cost_center_dept_control_yn == \App\Enums\YesNoFlag::NO)) disabled
                                            @elseif (!isset($coaInfo)) disabled
                                        @endif >
                                        <option>{{isset($coaInfo->cost_center_dep) ? $coaInfo->cost_center_dep->cost_center_dept_name : ''}}</option>

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
                                                            disabled
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
                                                    disabled
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
                                                     disabled >
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
                                                    <div class="custom-control custom-checkbox make-readonly">
                                                        <input type="checkbox" class="custom-control-input" readonly
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
                                        <div class="input-group date acc_inactive_date make-readonly" id="acc_inactive_date"
                                             data-target-input="nearest">
                                            <input type="text" name="acc_inactive_date" id="acc_inactive_date_field"
                                                   class="form-control form-control-sm datetimepicker-input acc_inactive_date"
                                                   data-target="#acc_inactive_date" data-toggle="datetimepicker"
                                                   @if (isset($coaInfo->inactive_yn) && ($coaInfo->inactive_yn == \App\Enums\YesNoFlag::NO)) disabled
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
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer-script')

@endsection

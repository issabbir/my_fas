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
                    <h4 class="card-title">Details Chart Of Accounts (COA)</h4>
                    <a href="{{route('coa.index')}}"><span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span></a>
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
                    <form class="form form-horizontal">
                        <div class="form-body row d-flex justify-content-center">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Account Type </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="acc_type" class="form-control" name="acc_type" placeholder="Account Type" disabled
                                               value="{{old('acc_code',isset($coaInfo->acc_type->gl_type_name) ? $coaInfo->acc_type->gl_type_name : '')}}" />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Account ID </label>
                                    </div>
                                    <div class="col-md-3 form-group pl-0">
                                        <input type="text" id="acc_id" class="form-control" name="acc_id" placeholder="Account ID"
                                               value="{{old('acc_id',isset($coaInfo->gl_acc_id) ? $coaInfo->gl_acc_id : '')}}" disabled />
                                    </div>

                                    <div class="col-md-2">
                                        <label>Opening Date </label>
                                    </div>
                                    <div class="col-md-4 form-group pl-0">
                                        <input type="text" id="opening_date" class="form-control" name="opening_date" placeholder="DD-MM-YYYY"
                                               value="{{old('opening_date',isset($coaInfo->opening_date) ? App\Helpers\HelperClass::dateConvert($coaInfo->opening_date)  : $date->cur_date)}}"" disabled />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Account Name </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="acc_name" class="form-control" name="acc_name" placeholder="Account Name" disabled
                                               value="{{old('acc_name',isset($coaInfo->gl_acc_name) ? $coaInfo->gl_acc_name : '')}}" />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Account Code (Legacy) </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="acc_code" class="form-control" name="acc_code" placeholder="Account Code Ref (Legacy)" disabled
                                               value="{{old('acc_code',isset($coaInfo->gl_acc_code) ? $coaInfo->gl_acc_code : '')}}" />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Currency </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="currency" class="form-control" name="currency" placeholder="Currency" readonly
                                               value="{{old('currency',isset($coaInfo->l_curr->currency_code) ? $coaInfo->l_curr->currency_code : '')}}"/>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Postable/Non-postable </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="acc_posting" id="non_postable" disabled
                                                               onclick="checkUncheckAccPost()" value="{{\App\Enums\YesNoFlag::NO}}"
                                                            {{isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::NO) ? 'checked' : ''}}/>
                                                        <label class="custom-control-label" for="non_postable">Non-Postable</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="acc_posting" id="postable" disabled
                                                               onclick="checkUncheckAccPost()" value="{{\App\Enums\YesNoFlag::YES}}"
                                                            {{!isset($coaInfo->postable_yn) ? 'checked' : ''}}
                                                            {{isset($coaInfo->postable_yn) && ($coaInfo->postable_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} />
                                                        <label class="custom-control-label" for="postable">Postable</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Parent Account Code </label>
                                            </div>
                                            <div class="col-md-6 form-group pl-0">
                                                <input type="text" id="selected_parent_acc_code" class="form-control" name="parent_acc_code" placeholder="Parent Account Code" disabled
                                                       value="{{old('parent_acc_code',isset($coaInfo->gl_parent_id) ? $coaInfo->gl_parent_id : '')}}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Parent Account Name </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="selected_parent_acc_name" class="form-control" name="parent_acc_name" placeholder="Parent Account Name" disabled
                                               value="{{old('parent_acc_name',isset($coaInfo->coa_parent_info->gl_acc_name) ? $coaInfo->coa_parent_info->gl_acc_name : '')}}"/>
                                    </div>

                                    <div class="col-md-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox"  class="custom-control-input" name="budget_head_control" id="budget_head_control" disabled
                                                            {{isset($coaInfo->budget_control_yn) && ($coaInfo->budget_control_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} >
                                                        <label class="custom-control-label" for="budget_head_control">Budget Head Control</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Budget Head Line ID </label>
                                            </div>
                                            <div class="col-md-9 form-group pl-0">
                                                <input type="text"  id="budget_head_id" class="form-control selected-head-id" name="budget_head_id" placeholder="Budget Head Line ID" disabled
                                                       {{--
                                                        * COA EDIT (problem: not shown parent name, budget  name). REF# email
                                                        * budget_head_line_id to budget_head_id
                                                        * Logic modified:04-04-2022
                                                        --}}
                                                       value="{{old('budget_head_id',isset($coaInfo->budget_head_id) ? $coaInfo->budget_head_id : '')}}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Budget Head Line Name </label>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="budget_head_name" class="form-control selected-head-name" name="budget_head_name" placeholder="Budget Head Line Name" disabled
                                               value="{{old('budget_head_name',isset($coaInfo->budget_head->budget_head_name) ? $coaInfo->budget_head->budget_head_name : '')}}" />
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2 mb-1">
                                                <fieldset>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox"  class="custom-control-input" name="acc_inactive" id="acc_inactive" disabled
                                                            {{isset($coaInfo->inactive_yn) && ($coaInfo->inactive_yn == \App\Enums\YesNoFlag::YES) ? 'checked' : ''}} >
                                                        <label class="custom-control-label" for="acc_inactive">Account Inactive</label>
                                                    </div>
                                                </fieldset>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-9 form-group pl-0">
                                        <input type="text" id="acc_inactive_date" class="form-control" name="acc_inactive_date" placeholder="Account Inactive Date" disabled
                                               value="{{old('acc_inactive_date',isset($coaInfo->inactive_date) ? App\Helpers\HelperClass::dateConvert($coaInfo->inactive_date)  : ' ')}}" />
                                    </div>
                                </div>
                            </div>

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

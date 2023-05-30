<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:২৯ AM
 */
?>
@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <?php
            /**
             *Created by PhpStorm
             *Created at ১২/৯/২১ ৯:৩০ AM
             */
            ?>
            <p class="font-weight-bold" style="text-decoration: underline;">Vendor Profile</p>
            <form id="vendorProfileForm" name="vendorProfileForm" method="post">
                @csrf
                <fieldset class="border p-2">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Basic Information</legend>
                    <div class=" row">
                        <label for="id" class="col-form-label col-md-2">ID</label>
                        <div class=" col-md-2">
                            <input type="text" class="form-control form-control-sm" id="id"
                                   value="{{old('id',isset($data['view']) ? $data['view']->vendor_id : '' )}}"
                                   readonly>
                        </div>
                    </div>
                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2" for="name">Name</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" id="name" name="name"
                                   {{ isset($data['insertedData']) ? 'readonly' : ''  }}
                                   value="{{ old('name', isset($data['view']) ? $data['view']->vendor_name : '' ) }}">
                        </div>
                    </div>
                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2" for="short_name">Short Name</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="short_name" name="short_name"
                                   {{ isset($data['view']) ? 'readonly' : ''  }}
                                   value="{{ old('short_name', isset($data['view']) ? $data['view']->vendor_short_name : '' ) }}">
                        </div>
                        <div class="col-md-7  make-readonly">
                            <div class=" row d-flex justify-content-end">
                                <label class="col-form-label col-md-4 text-left" for="opening_date_field">Opening
                                    Date</label>
                                <div class="input-group date opening_date col-md-5" id="opening_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="opening_date" readonly
                                           id="opening_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#opening_date"
                                           data-toggle="datetimepicker"
                                           value="{{ old('opening_date', isset($data['view']->opening_date) ?  \App\Helpers\HelperClass::dateConvert($data['view']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                                           data-predefined-date="{{ old('opening_date', isset($data['view']->opening_date) ? \App\Helpers\HelperClass::dateConvert($data['view']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
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
                    </div>

                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2" for="vendor_type">Vendor Type</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm {{ isset($data['view']) ? 'make-readonly' : ''  }}"
                                    id="vendor_type"
                                    name="vendor_type" {{ isset($data['view']) ? 'readonly' : ''  }}>
                                <option value="">&lt;Select&gt;</option>
                                @foreach($data['vendorType'] as $type)
                                    <option
                                        value="{{$type->vendor_type_id}}" {{ old('vendor_type', isset($data['view']) ? $data['view']->vendor_type_id : '' ) == $type->vendor_type_id ? 'Selected' : '' }}>{{$type->vendor_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7  make-readonly">
                            <div class="row  d-flex justify-content-end">
                                <label class="col-form-label col-md-4" for="vendor_category">Vendor Category</label>
                                <div class="col-md-5">
                                    <select class="form-control form-control-sm" id="vendor_category" name="vendor_category" readonly=""
                                            data-predefined="{{ isset($data['view']) ? $data['view']->vendor_category_id : '' }}">
                                        <option value="">&lt;Select&gt;</option>
                                        {{--@foreach($data['vendorCategory'] as $type)
                                            <option
                                                value="{{$type->vendor_category_id}}" {{ old('vendor_category', isset($data['insertedData']) ? $data['insertedData']->vendor_category_id : '' ) == $type->vendor_category_id ? 'Selected' : '' }}>{{$type->vendor_category_name}}</option>
                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row  make-readonly">
                        <div class="offset-9"></div>
                        <div class="form-check col-md-2 ml-1">
                            <input class="form-check-input" type="checkbox" value="Y" name="enlisted_vendor"
                                   tabindex="-1"
                                   {{ old('enlisted_vendor', isset($data['view']) ? $data['view']->enlisted_vendor_yn : '' ) == 'Y' ? 'Checked' : '' }}
                                   id="enlisted_vendor">
                            <label class="form-check-label" for="enlisted_vendor">
                                Enlisted Vendor
                            </label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border p-2 d-none" id="unique_identification">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Unique Identification</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="bin">BIN</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="bin" name="bin" readonly
                                   value="{{ old('bin', isset($data['view']) ? $data['view']->bin_no : '' ) }}">
                        </div>

                        <div class="col-md-7">
                            <div class="row  d-flex justify-content-end pr-0">
                                <label class="col-form-label col-md-4" for="vat">VAT Registration No</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" id="vat" name="vat" readonly
                                           value="{{ old('vat', isset($data['view']) ? $data['view']->vat_registration_no : '' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="tin">TIN</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="tin" name="tin" readonly
                                   value="{{ old('tin', isset($data['view']) ? $data['view']->tin_no : '' ) }}">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-2 d-none" id="dept_cost_mapping">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Dept/Cost Center Mapping</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for=""></label>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input disabled class="form-check-input" type="checkbox" value="Y"
                                       name="allow_dept_cost_center"
                                       tabindex="-1"
                                       {{ old('allow_dept_cost_center', isset($data['view']) ? $data['view']->cost_center_dept_control_yn : '' ) == 'Y' ? 'Checked' : '' }}
                                       id="allow_dept_cost_center">
                                <label class="form-check-label" for="allow_dept_cost_center">
                                    Allow Department/Cost Center Control
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="dept_cost_center">Dept/Cost Center</label>
                        <div class="col-md-10">
                            <div class=" make-select2-readonly-bg">
                                <select class="form-control form-control-sm select2" id="dept_cost_center" readonly="" name="dept_cost_center">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['department'] as $type)
                                        <option
                                            value="{{$type->cost_center_dept_id}}" {{ old('dept_cost_center', isset($data['view']) ? $data['view']->cost_center_dept_id : '' ) == $type->cost_center_dept_id ? 'Selected' : '' }}>{{$type->cost_center_dept_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border p-2 d-none" id="vendor_address">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor Address</legend>
                    <div class=" row">
                        <label class="col-form-label col-md-2" for="address_type">Address Types</label>
{{--                        <div class="col-md-3">--}}
{{--                            <select class="form-control form-control-sm" id="address_type" name="address_type">--}}
{{--                                <option value="">&lt;Select&gt;</option>--}}
{{--                                @foreach($data['addressType'] as $type)--}}
{{--                                    <option--}}
{{--                                        value="{{$type->address_type_id}}" {{ old('address_type', isset($data['view']) ? $data['view']->address_type_id : '' ) == $type->address_type_id ? 'Selected' : '' }}>{{$type->address_type_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
                    </div>
                    <div class=" row">
                        <label class="col-form-label col-md-2" for="address_1">Address Line 1</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control form-control-sm" id="address_1" name="address_1"
                                   value="{{ old('address_1', isset($data['view']) ? $data['view']->address_line1 : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="address_2">Address Line 2</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" readonly id="address_2" name="address_2"
                                   value="{{ old('address_2', isset($data['view']) ? $data['view']->address_line2 : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="city">City</label>
                        <div class="col-md-3">
                            <input type="text" readonly class="form-control form-control-sm" id="city" name="city"
                                   value="{{ old('city', isset($data['view']) ? $data['view']->city : '' ) }}">
                        </div>
                        <div class="col-md-7">
                            <div class=" row d-flex justify-content-end">
                                <label class="col-form-label col-md-3" for="state">State</label>
                                <div class="col-md-5">
                                    <input type="text" readonly class="form-control form-control-sm" id="state" name="state"
                                           value="{{ old('state', isset($data['view']) ? $data['view']->state_name : '' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="postal_code">Postal Code</label>
                        <div class="col-md-3">
                            <input type="text" readonly class="form-control form-control-sm" id="postal_code" name="postal_code"
                                   value="{{ old('postal_code', isset($data['view']) ? $data['view']->postal_code : '' ) }}">
                        </div>
                        <div class="col-md-7">
                            <div class=" row d-flex justify-content-end pr-0">
                                <label class="col-form-label col-md-3" for="country">Country</label>
                                <div class="col-md-5 make-readonly">
                                    <select readonly class=" form-control form-control-sm select2" id="country" name="country">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($data['county'] as $type)
                                            <option
                                                value="{{$type->country_id}}" {{ old('country', isset($data['view']) ? $data['view']->country : '' ) == $type->country_id ? 'Selected' : '' }}>{{$type->country}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <p class="font-weight-bold" style="text-decoration: underline;">Contact Person</p>

                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="contact_name">Name</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control form-control-sm" id="contact_name" name="contact_name"
                                   value="{{ old('contact_name', isset($data['view']) ? $data['view']->contact_person_name : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="phone">Phone No(s)</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control form-control-sm" id="phone" name="phone"
                                   value="{{ old('phone', isset($data['view']) ? $data['view']->contact_person_phone : '' ) }}">
                        </div>

                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="mobile">Mobile No(s)</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control form-control-sm" id="mobile" name="mobile"
                                   value="{{ old('mobile', isset($data['view']) ? $data['view']->contact_person_mobile : '' ) }}">
                        </div>

                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="email">Email Address</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control form-control-sm" id="email" name="email"
                                   value="{{ old('email', isset($data['view']) ? $data['view']->contact_person_email : '' ) }}">
                        </div>

                    </div>
                </fieldset>
                <fieldset class="border p-2 d-none" id="vendor_bank_info">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Bank Information</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="bank_id">Bank</label>
                        <div class="col-md-10 make-readonly">
                            <select class="form-control form-control-sm select2" id="bank_id" name="bank_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($data['bank'] as $type)
                                    <option
                                        value="{{$type->bank_code}}" {{ old('bank_id', isset($data['view']) ? $data['view']->bank_code : '' ) == $type->bank_code ? 'Selected' : '' }}>{{$type->bank_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--<div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="district_id">Bank-District</label>
                        <div class="col-md-10">
                            <select class="form-control form-control-sm select2" id="district_id" name="district_id">
                                <option value="">Select District</option>
                                @foreach($data['bankDistrict'] as $type)
                                    <option
                                        value="{{$type->district_code}}" {{ old('district_id', isset($data['insertedData']) ? $data['insertedData']->district_code : '' ) == $type->district_code ? 'Selected' : '' }}>{{$type->district_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="branch_id">Bank-Branch</label>
                        <div class="col-md-10 make-readonly">
                            <select readonly="" class="form-control form-control-sm select2" id="branch_id" name="branch_id"
                                    data-prebranch="{{old('branch_id',isset($data['insertedData']->branch_code) ? $data['insertedData']->branch_code->branch_code:'')}}">
                            </select>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="routing_number">Routing Number</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="routing_number" name="routing_number"
                                   value="{{ old('routing_number') }}" readonly>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="account_no">Account No</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="account_no" name="account_no" readonly
                                   value="{{ old('account_no',isset($data['view']) ? $data['view']->bank_account_no:'') }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="account_title">Account Title</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" id="account_title" name="account_title" readonly
                                   value="{{ old('account_title',isset($data['view']) ? $data['view']->bank_account_title : '') }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="account_type">Account Type</label>
                        <div class="col-md-3 make-readonly">
                            <select class="form-control form-control-sm" id="account_type" name="account_type">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($data['bankAccountType'] as $type)
                                    <option
                                        value="{{$type->bank_account_type_id}}" {{ old('account_type', isset($data['view']) ? $data['view']->bank_account_type_id : '' ) == $type->bank_account_type_id ? 'Selected' : '' }}>{{$type->bank_account_type_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </fieldset>
                <fieldset class="border p-2 d-none" id="payment_hold_control">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Payment Hold Control</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="hold_all_payment"></label>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input disabled class="form-check-input" type="checkbox" value="Y"
                                       name="hold_all_payment"
                                       tabindex="-1"
                                       {{ old('hold_all_payment', isset($data['view']) ? $data['view']->payment_hold_flag : '' ) == '1' ? 'Checked' : '' }}
                                       id="hold_all_payment">
                                <label class="form-check-label" for="hold_all_payment">
                                    Hold All Payments
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="hold_all_payment_reason">Payment Hold Reason</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm " id="hold_all_payment_reason" readonly
                                   value="{{ old('hold_all_payment_reason', isset($data['view']) ? $data['view']->payment_hold_reason : '') }}">
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border p-2 @if (!isset($data['view']->vendor_id)) d-none @endif">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Inactive Status</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2"></label>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input {{ isset($data['insertedData']) ? '' : __('disabled') }} class="form-check-input"
                                       type="checkbox" value="Y" name="is_inactive" tabindex="-1"
                                       {{ old('is_inactive', isset($data['view']) ? $data['view']->inactive_yn : '' ) == 'Y' ? 'Checked' : '' }}
                                       id="is_inactive">
                                <label class="form-check-label" for="is_inactive">
                                    Vendor Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class=" row  {{--make-readonly--}}">
                        <label class="col-form-label col-md-2" for="inactive_date_field">Inactive Date</label>
                        <div class="col-md-3">
                            <div class="input-group date inactive_date make-readonly-bg"
                                 id="inactive_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false"
                                       name="inactive_date" readonly
                                       id="inactive_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#inactive_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('inactive_date', isset($data['view']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['view']->inactive_date) : '') }}"
                                       data-predefined-date="{{ old('inactive_date', isset($data['view']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['view']->inactive_date) : '') }}"
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append inactive_date" data-target="#inactive_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                @if ($data['insertedData']['current_status'] != \App\Enums\ApprovalStatus::PENDING)
                    <div class="row  mt-1 pl-2">
                        <label for="authorizer" class="col-md-2 col-form-label">Authorizer </label>
                        <div class="col-md-3 ">
                            <input type="text" id="authorizer" class="form-control form-control-sm" name="authorizer" disabled
                                   value="{{$data['insertedData']->emp->employee->emp_name.' ('.$data['insertedData']->emp->employee->emp_code.')'}}"/>
                        </div>
                        <div class="col-md-2"><label for="comment" class="col-form-label">Comment </label></div>
                        <div class="col-md-3 ">
                            <input type="text" id="comment" class="form-control form-control-sm" name="comment"
                                   @if  ($data['insertedData']['current_status'] == \App\Enums\ApprovalStatus::APPROVED || $data['insertedData']['current_status'] == \App\Enums\ApprovalStatus::REJECT) disabled
                                   @endif
                                   value="{{isset($data['insertedData']['comment']) ? $data['insertedData']['comment'] : ''}}"/>
                        </div>
                    </div>
                @endif

                <div class="row  mt-1">
                    <div class="col-md-12 d-flex justify-content-end">
                        @if ($data['insertedData']['current_status'] == \App\Enums\ApprovalStatus::PENDING)
                            <div>
                                <a class="btn btn-primary btn-sm mr-25 trans-approval"
                                   href="{{route('vendor-profile-authorize.perform-authorize')}}"
                                   data-mapid="{{$data['insertedData']['workflow_mapping_id']}}"
                                   data-approval-status="{{ App\Enums\ApprovalStatus::APPROVED }}">
                                    <i class="bx bx-check-double cursor-pointer font-size-small"></i> Authorize
                                </a>

                            </div>
                        <!-- TODO: DISABLED FOR PRIMARY BASIS -->
                            <div>
                                <a class="btn btn-danger btn-sm mr-25 trans-approval"
                                   href="{{route('vendor-profile-authorize.perform-authorize')}}"
                                   data-mapid="{{$data['insertedData']['workflow_mapping_id']}}"
                                   data-approval-status="{{ App\Enums\ApprovalStatus::REJECT }}">
                                    <i class="bx bx-check-double cursor-pointer font-size-small"></i> Decline
                                </a>
                                <a class="btn btn-sm btn-dark mr-25"
                                   href="{{route('vendor-profile-authorize.index')}}">
                                    <i class="bx bx-log-out cursor-pointer font-size-small"></i> Back
                                </a>
                            </div>
                        <!-- TODO: DISABLED FOR PRIMARY BASIS -->
                        @else
                            <div>
                                <a class="btn btn-sm btn-dark mr-25"
                                   href="{{route('vendor-profile-authorize.index')}}">
                                    <i class="bx bx-log-out cursor-pointer font-size-small"></i> Back
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            datePicker('#opening_date');

            //Only For Update
            getVendorCategory($("#vendor_type :selected").val());
            //addVendorEffect();
            departmentCostCheckEnableDisable();
            enableDisablePaymentField();
            $("#vendor_type").trigger('change');
            enableDisableDateField("#is_inactive");
        });
        $(document).on("click", '.trans-approval', function (e) {
            e.preventDefault();
            let action_url = this;
            //let trans_mst_id = $(this).attr('id');
            let approval_status = $(this).data('approval-status');
            let map_id = $(this).data('mapid');
            let approval_status_val;
            let swal_input_type;

            if (approval_status == 'A') {
                approval_status_val = 'Authorize';
                swal_input_type = null;
            } else {
                approval_status_val = 'Decline';
                swal_input_type = 'text';
            }

            //alert(action_url);
            //return;
            e.preventDefault();
            swal.fire({
                title: 'Are you sure?',
                text: 'Transaction ' + approval_status_val,
                type: 'warning',
                input: swal_input_type,
                inputPlaceholder: 'Reason For Decline?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                inputValidator: (result) => {
                    return !result && 'You need to provide a comment'
                },
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'

            }).then(function (isConfirm) {
                let remark;
                if (isConfirm.value) {
                    remark = isConfirm.value;
                    action_url += '?&rem=' + remark + '&workflow_mapping_id=' + map_id + '&status=' + approval_status;
                    window.location.href = action_url;
                } else if (isConfirm.dismiss == "cancel") {
                    //return false;
                    e.preventDefault();
                }
            })
        });

        getBranchListOnBank($("#bank_id :selected").val(), setBranchListOnBank, $("#branch_id").data('prebranch'));

        $("#vendor_type").on('change', function () {
            getVendorCategory($(this).val());
            addVendorEffect();
        });

        function getVendorCategory(vendorType) {
            let request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-category-on-vendor-type',
                data: {vendorTpe: vendorType, preCategoryId: $("#vendor_category").data('predefined')},
                async: false
            });
            request.done(function (response) {
                $("#vendor_category").html(response);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            })

            if (!nullEmptyUndefinedChecked($("#vendor_category").data('predefined'))) {
                let preset = $("#vendor_category").data('predefined');
                $(document).val(preset).trigger('change');
            }
        }

        function addVendorEffect(update = false) {
            let vendorType = $("#vendor_type :selected").val();

            if (vendorType == {{ \App\Enums\Ap\VendorType::INTERNAL }}) {
                $("#dept_cost_mapping").removeClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", false);

                /*$("#bin").prop("readonly",true);
                $("#vat").prop("readonly",true);
                $("#tin").prop("readonly",true);*/
                $("#unique_identification").addClass("d-none");

                //For External Vendor
                resetField(["#bin", "#vat", "#tin"]);
                $("#payment_hold_control").addClass("d-none");
                $("#hold_all_payment").prop("disabled", true);
                $("#hold_all_payment").prop("checked", false);
                enableDisablePaymentField();

                resetAddressField(0);
                resetBankInformationField(0);
            } else if (vendorType == {{ \App\Enums\Ap\VendorType::EXTERNAL}}) {
                $("#payment_hold_control").removeClass("d-none");
                $("#hold_all_payment").prop("disabled", false);
                resetAddressField(1);
                resetBankInformationField(1);

                //For Internal Vendor
                $("#dept_cost_mapping").addClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", true);
                $("#allow_dept_cost_center").prop("checked", false);

                /*$("#bin").prop("readonly",false);
                $("#vat").prop("readonly",false);
                $("#tin").prop("readonly",false);*/
                $("#unique_identification").removeClass("d-none");

                departmentCostCheckEnableDisable();
            } else {
                //For External Vendor
                $("#payment_hold_control").addClass("d-none");
                $("#hold_all_payment").prop("disabled", true);
                $("#hold_all_payment").prop("checked", false);
                enableDisablePaymentField();

                /*$("#bin").prop("readonly",true);
                $("#vat").prop("readonly",true);
                $("#tin").prop("readonly",true);*/
                $("#unique_identification").addClass("d-none");

                //For Internal Vendor
                $("#dept_cost_mapping").addClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", true);
                $("#allow_dept_cost_center").prop("checked", false);
                departmentCostCheckEnableDisable();

                resetAddressField(0);
                resetBankInformationField(0);
            }
        }

        $("#allow_dept_cost_center").on('click', function () {
            departmentCostCheckEnableDisable();
        })
        $("#hold_all_payment").on('click', function () {
            enableDisablePaymentField();
        })
        $("#is_inactive").on('click', function () {
            enableDisableDateField(this);
        })

        function departmentCostCheckEnableDisable() {
            if ($("#allow_dept_cost_center").prop('checked')) {
                $("#dept_cost_center").parent('div').removeClass('make-readonly-bg');
            } else {
                $("#dept_cost_center").val('').trigger('change');
                $("#dept_cost_center").parent('div').addClass('make-readonly-bg');
            }
        }

        function resetAddressField(status) {
            if (status == 1) {
                $("#vendor_address").removeClass("d-none");
                $("#address_type").parent().removeClass('make-readonly');
                $("#address_1").removeAttr('readonly');
                $("#address_2").removeAttr('readonly');
                $("#city").removeAttr('readonly');
                $("#state").removeAttr('readonly');
                $("#postal_code").removeAttr('readonly');
                $("#country").parent().removeClass('make-readonly');
                $("#contact_name").removeAttr('readonly');
                $("#phone").removeAttr('readonly');
                $("#mobile").removeAttr('readonly');
                $("#email").removeAttr('readonly');
            } else {
                $("#vendor_address").addClass("d-none");
                $("#address_type").parent().addClass('make-readonly');
                $("#address_type").val("").trigger('change');
                $("#country").parent().addClass('make-readonly');
                $("#country").val("").trigger('change');

                $("#address_1").val('').attr('readonly', 'readonly');
                $("#address_2").val('').attr('readonly', 'readonly');
                $("#city").val('').attr('readonly', 'readonly');
                $("#state").val('').attr('readonly', 'readonly');
                $("#postal_code").val('').attr('readonly', 'readonly');
                $("#contact_name").val('').attr('readonly', 'readonly');
                $("#phone").val('').attr('readonly', 'readonly');
                $("#mobile").val('').attr('readonly', 'readonly');
                $("#email").val('').attr('readonly', 'readonly');
            }
        }

        function resetBankInformationField(status) {
            if (status == 1) {
                $("#vendor_bank_info").removeClass("d-none");
                $("#bank_id").parent().removeClass('make-readonly');
                $("#branch_id").parent().removeClass('make-readonly');
                $("#account_no").removeAttr('readonly');
                $("#account_title").removeAttr('readonly');
                $("#account_type").parent().removeClass('make-readonly');
            } else {
                $("#vendor_bank_info").addClass("d-none");
                $("#bank_id").parent().addClass('make-readonly');
                $("#bank_id").val("").trigger('change');
                $("#branch_id").parent().addClass('make-readonly');
                $("#branch_id").val("").trigger('change');
                $("#account_type").parent().addClass('make-readonly');
                $("#account_type").val("").trigger('change');
                $("#account_no").val('').attr('readonly', 'readonly');
                $("#account_title").val('').attr('readonly', 'readonly');
            }
        }

        function enableDisablePaymentField() {
            if ($("#hold_all_payment").prop('checked')) {
                $("#hold_all_payment_reason").removeAttr('readonly');
            } else {
                $("#hold_all_payment_reason").val('').attr('readonly', 'readonly');
            }
        }

        function enableDisableDateField(selector) {
            if ($(selector).prop('checked')) {
                $("#inactive_date").removeClass('make-readonly-bg');
                datePicker('#inactive_date');
            } else {
                $("#inactive_date_field").val('');
                $("#inactive_date").datetimepicker('destroy');
                $("#inactive_date").addClass('make-readonly-bg');
            }
        }

        $("#vendorSearchSubmit").on('click', function (e) {
            $('#vendorSearch').data("dt_params", {
                vendorType: $('#search_vendor_type :selected').val(),
                vendorCategory: $('#search_vendor_category :selected').val(),
                vendorName: $('#search_vendor_name').val(),
                vendorShortName: $('#search_vendor_short_name').val(),
            }).DataTable().draw();
        });

        $("#bank_id").on('change', function () {
            let bankId = $(this).val();
            getBranchListOnBank(bankId, setBranchListOnBank, '');
        })

        $("#branch_id").on('change', function () {
            $("#routing_number").val($("#branch_id :selected").data('routing'));
        });

        function setBranchListOnBank(response) {
            $("#branch_id").html('<option value="">Select Branch</option>')
            $("#branch_id").html(response);
            $("#routing_number").val($("#branch_id :selected").data('routing'));
        }

        function getBranchListOnBank(bankId, callback, preBranch) {
            let response = $.ajax({
                url: APP_URL + "/account-payable/ajax/get-branches-on-bank",
                data: {id: bankId, branch: preBranch}
            });

            response.done(function (d) {
                callback(d);
            });

            response.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            });
        }
    </script>
@endsection

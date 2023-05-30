<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:৩০ AM
 */
?>
<p class="font-weight-bold" style="text-decoration: underline;">Vendor Profile</p>
<form id="vendorProfileForm" name="vendorProfileForm" method="post"
      @if (isset($data['insertedData']))
      action="{{ route('vendor-profile.update',['id'=>$data['insertedData']->vendor_id]) }}">
    {{ method_field('PUT') }}
    @else
        action="{{route('vendor-profile.insert')}}">
    @endif
    @csrf
    <fieldset class="border p-1">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Basic Information</legend>
        <div class=" row">
            <label for="id" class="col-form-label col-md-2">ID</label>
            <div class=" col-md-2">
                <input type="text" class="form-control form-control-sm" id="id"
                       value="{{old('id',isset($data['insertedData']) ? $data['insertedData']->vendor_id : '' )}}"
                       readonly>
            </div>
        </div>
        <div class=" row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="name">Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" id="name" name="name" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('name', isset($data['insertedData']) ? $data['insertedData']->vendor_name : '' ) }}">
            </div>
        </div>
        <div class=" row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="short_name">Short Name</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="short_name" name="short_name" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('short_name', isset($data['insertedData']) ? $data['insertedData']->vendor_short_name : '' ) }}">
            </div>
            <div class="col-md-7  ">
                <div class=" row d-flex justify-content-end">
                    <label class="col-form-label col-md-4 text-left" for="opening_date_field">Opening Date</label>
                    <div class="input-group date opening_date col-md-5 " id="opening_date" data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false"
                               name="opening_date"
                               id="opening_date_field"
                               class="form-control form-control-sm datetimepicker-input"
                               data-target="#opening_date"
                               data-toggle="datetimepicker"
                               value="{{ old('opening_date', isset($data['insertedData']->opening_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                               data-predefined-date="{{ old('opening_date', isset($data['insertedData']->opening_date) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
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

        <div class=" row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="vendor_type">Vendor Type</label>
            <div class="col-md-3">
                <select class="form-control form-control-sm" id="vendor_type" {{ ($data['readonly'] == true) ? 'readonly' : '' }} name="vendor_type">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['vendorType'] as $type)
                        <option
                            value="{{$type->vendor_type_id}}" {{ old('vendor_type', isset($data['insertedData']) ? $data['insertedData']->vendor_type_id : '' ) == $type->vendor_type_id ? 'Selected' : '' }}>{{$type->vendor_type_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
                <div class="row  d-flex justify-content-end">
                    <label class="col-form-label col-md-4 required" for="vendor_category">Vendor Category</label>
                    <div class="col-md-5">
                        <select class="form-control form-control-sm" id="vendor_category" name="vendor_category" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                                data-predefined="{{ isset($data['insertedData']) ? $data['insertedData']->vendor_category_id : '' }}">
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
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }} mediaEnlistCheckboxArea d-none">
            <div class="offset-2"></div>
            <div class="form-check col-md-3 ml-1">
                <input class="form-check-input" type="checkbox" value="Y" name="media_org" tabindex="-1" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       {{ old('media_org', isset($data['insertedData']) ? $data['insertedData']->media_serviceable_org_yn : '' ) == 'Y' ? 'Checked' : '' }}
                       id="media_org">
                <label class="form-check-label" for="media_org">
                    Media/Serviciable Organization
                </label>
            </div>
            <div class="offset-4"></div>
            <div class="form-check col-md-2 ml-1">
                <input class="form-check-input" type="checkbox" value="Y" name="enlisted_vendor" tabindex="-1" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       {{ old('enlisted_vendor', isset($data['insertedData']) ? $data['insertedData']->enlisted_vendor_yn : '' ) == 'Y' ? 'Checked' : '' }}
                       id="enlisted_vendor">
                <label class="form-check-label" for="enlisted_vendor">
                    Enlisted Vendor
                </label>
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-1 d-none" id="unique_identification">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Unique Identification</legend>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="bin">BIN</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="bin" name="bin" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('bin', isset($data['insertedData']) ? $data['insertedData']->bin_no : '' ) }}">
            </div>

            <div class="col-md-7">
                <div class="row  d-flex justify-content-end pr-0">
                    <label class="col-form-label col-md-4" for="vat">VAT Registration No</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" id="vat" name="vat" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                               value="{{ old('vat', isset($data['insertedData']) ? $data['insertedData']->vat_registration_no : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="tin">TIN</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="tin" name="tin" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('tin', isset($data['insertedData']) ? $data['insertedData']->tin_no : '' ) }}">
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-1 d-none" id="dept_cost_mapping">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Dept/Cost Center Mapping</legend>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for=""></label>
            <div class="col-md-4">
                <div class="form-check">
                    <input disabled class="form-check-input" type="checkbox" value="Y" name="allow_dept_cost_center"
                           tabindex="-1" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                           {{ old('allow_dept_cost_center', isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_control_yn : '' ) == 'Y' ? 'Checked' : '' }}
                           id="allow_dept_cost_center">
                    <label class="form-check-label" for="allow_dept_cost_center">
                        Allow Department/Cost Center Control
                    </label>
                </div>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="dept_cost_center">Dept/Cost Center</label>
            <div class="col-md-3">
                <div class="make-select2-readonly-bg">
                    <select class="form-control form-control-sm select2" id="dept_cost_center" name="dept_cost_center">
                        <option value="">&lt;Select&gt;</option>
                        @foreach($data['department'] as $type)
                            <option
                                value="{{$type->cost_center_dept_id}}" {{ old('dept_cost_center', isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id : '' ) == $type->cost_center_dept_id ? 'Selected' : '' }}>{{$type->cost_center_dept_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-1 d-none" id="vendor_address">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor Address</legend>

        {{--OFF THIS SECTION- ADD PAVEL: 28-02-22--}}
        {{--<div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="address_type">Address Type</label>
            <div class="col-md-3 make-readonly">
                <select class="form-control form-control-sm" id="address_type" name="address_type">
                    <option value="">Select Type</option>
                    @foreach($data['addressType'] as $type)
                        <option
                            value="{{$type->address_type_id}}" {{ old('address_type', isset($data['insertedData']->vendor_address) ? $data['insertedData']->vendor_address->address_type_id : '' ) == $type->address_type_id ? 'Selected' : '' }}>{{$type->address_type_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>--}}
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="address_1">Address Line 1</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="address_1" name="address_1"
                       value="{{ old('address_1', isset($data['insertedData']) ? $data['insertedData']->address_line1 : '' ) }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="address_2">Address Line 2</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" readonly id="address_2" name="address_2"
                       value="{{ old('address_2', isset($data['insertedData']) ? $data['insertedData']->address_line2 : '' ) }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="city">City</label>
            <div class="col-md-3">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="city" name="city"
                       value="{{ old('city', isset($data['insertedData']) ? $data['insertedData']->city : '' ) }}">
            </div>
            <div class="col-md-7">
                <div class=" row d-flex justify-content-end">
                    <label class="col-form-label col-md-3" for="state">State</label>
                    <div class="col-md-5">
                        <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="state" name="state"
                               value="{{ old('state', isset($data['insertedData']) ? $data['insertedData']->state_name : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="postal_code">Postal Code</label>
            <div class="col-md-3">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="postal_code" name="postal_code"
                       value="{{ old('postal_code', isset($data['insertedData']) ? $data['insertedData']->postal_code : '' ) }}">
            </div>
            <div class="col-md-7">
                <div class=" row d-flex justify-content-end pr-0">
                    <label class="col-form-label col-md-3" for="country">Country</label>
                    <div class="col-md-5 make-readonly {{ ($data['readonly'] == true) ? 'make-select2-readonly-bg' : '' }}">
                        <select readonly class=" form-control form-control-sm select2" id="country" name="country">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($data['county'] as $type)
                                <option
                                    value="{{$type->country_id}}" {{ old('country', isset($data['insertedData']) ? $data['insertedData']->country : '' ) == $type->country_id ? 'Selected' : '' }}>{{$type->country}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <p class="font-weight-bold" style="text-decoration: underline;">Contact Person</p>

        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="contact_name">Name</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="contact_name" name="contact_name"
                       value="{{ old('contact_name', isset($data['insertedData']) ? $data['insertedData']->contact_person_name : '' ) }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="phone">Phone No(s)</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="phone" name="phone"
                       value="{{ old('phone', isset($data['insertedData']) ? $data['insertedData']->contact_person_phone : '' ) }}">
            </div>

        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="mobile">Mobile No(s)</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="mobile" name="mobile"
                       value="{{ old('mobile', isset($data['insertedData']) ? $data['insertedData']->contact_person_mobile : '' ) }}">
            </div>

        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="email">Email Address</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control form-control-sm {{ ($data['readonly'] == true) ? 'make-readonly-bg' : '' }}" id="email" name="email"
                       value="{{ old('email', isset($data['insertedData']) ? $data['insertedData']->contact_person_email : '' ) }}">
            </div>

        </div>
    </fieldset>

    {{-- TODO: Vendor's Bank Information Updated Block Date:18-10-22 --}}
    <fieldset class="border p-1 d-none" id="vendor_bank_info">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Bank Information</legend>
        <div class=" row">
            <label class="col-form-label col-md-2" for="bank_id">Bank</label>
            <div class="col-md-10 {{ ($data['readonly'] == true) ? 'make-select2-readonly-bg' : '' }}">
                <select class="form-control form-control-sm select2" id="bank_id" name="bank_id">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['bank'] as $type)
                        <option
                            value="{{$type->bank_code}}" {{ old('bank_id', isset($data['insertedData']) ? $data['insertedData']->bank_code : '' ) == $type->bank_code ? 'Selected' : '' }}>{{$type->bank_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{--<div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
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
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="branch_id">Bank-Branch</label>
            <div class="col-md-10 {{ ($data['readonly'] == true) ? 'make-select2-readonly-bg' : '' }}">
                <select readonly="" class="form-control form-control-sm select2" id="branch_id" name="branch_id"
                        data-prebranch="{{old('branch_id',isset($data['insertedData']->branch_code) ? $data['insertedData']->branch_code->branch_code:'')}}">
                </select>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="routing_number">Routing Number</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="routing_number" name="routing_number"
                       value="{{ old('routing_number') }}" readonly>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_no">Account No</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="account_no" name="account_no" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('account_no',isset($data['insertedData']) ? $data['insertedData']->bank_account_no:'') }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_title">Account Title</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" id="account_title" name="account_title" {{ ($data['readonly'] == true) ? 'readonly' : '' }}
                       value="{{ old('account_title',isset($data['insertedData']) ? $data['insertedData']->bank_account_title : '') }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_type">Account Type</label>
            <div class="col-md-3 {{--make-readonly--}}">
                <select class="form-control form-control-sm" id="account_type" name="account_type" {{ ($data['readonly'] == true) ? 'readonly' : '' }}>
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['bankAccountType'] as $type)
                        <option
                            value="{{$type->bank_account_type_id}}" {{ old('account_type', isset($data['insertedData']) ? $data['insertedData']->bank_account_type_id : '' ) == $type->bank_account_type_id ? 'Selected' : '' }}>{{$type->bank_account_type_name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </fieldset>

    {{-- TODO: Vendor's Bank Information Previous Block Date:18-10-22 --}}
    {{--<fieldset class="border p-1 d-none" id="vendor_bank_info">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Bank Information</legend>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="bank_id">Bank</label>
            <div class="col-md-10 make-readonly">
                <select class="form-control form-control-sm select2" id="bank_id" name="bank_id">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['bank'] as $type)
                        <option
                            value="{{$type->bank_code}}" {{ old('bank_id', isset($data['insertedData']) ? $data['insertedData']->bank_code : '' ) == $type->bank_code ? 'Selected' : '' }}>{{$type->bank_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        --}}{{--<div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
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
        </div>--}}{{--
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="branch_id">Bank-Branch</label>
            <div class="col-md-10 make-readonly">
                <select readonly="" class="form-control form-control-sm select2" id="branch_id" name="branch_id"
                        data-prebranch="{{old('branch_id',isset($data['insertedData']->branch_code) ? $data['insertedData']->branch_code->branch_code:'')}}">
                </select>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="routing_number">Routing Number</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="routing_number" name="routing_number"
                       value="{{ old('routing_number') }}" readonly>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_no">Account No</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="account_no" name="account_no" readonly
                       value="{{ old('account_no',isset($data['insertedData']) ? $data['insertedData']->bank_account_no:'') }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_title">Account Title</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm" id="account_title" name="account_title" readonly
                       value="{{ old('account_title',isset($data['insertedData']) ? $data['insertedData']->bank_account_title : '') }}">
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="account_type">Account Type</label>
            <div class="col-md-3 make-readonly">
                <select class="form-control form-control-sm" id="account_type" name="account_type">
                    <option value="">&lt;Select&gt;</option>
                    @foreach($data['bankAccountType'] as $type)
                        <option
                            value="{{$type->bank_account_type_id}}" {{ old('account_type', isset($data['insertedData']) ? $data['insertedData']->bank_account_type_id : '' ) == $type->bank_account_type_id ? 'Selected' : '' }}>{{$type->bank_account_type_name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </fieldset>--}}

    <fieldset class="border p-1 d-none" id="imprest_rev_cash">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Imprest/Revolving Cash</legend>
                <div class=" row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="imp_approved_limit">IMP Approved Limit</label>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm text-right-align" id="imp_approved_limit" name="imp_approved_limit"
                       value="{{ old('imp_approved_limit', isset($data['insertedData']) ? $data['insertedData']->imp_petty_cash_limit : '' ) }}">
            </div>
            <div class="col-md-7  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
                <div class=" row d-flex justify-content-end">
                    <label class="col-form-label col-md-4 text-left" for="rev_approved_limit">REV Approved Limit</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm text-right-align" id="rev_approved_limit" name="rev_approved_limit"
                               value="{{ old('rev_approved_limit', isset($data['insertedData']) ? $data['insertedData']->rev_petty_cash_limit : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
        {{--0003774: Need to disable the Imp/REV Paid column of vendor profile in AP Module.--}}
        <div class=" row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="imp_petty_cash">IMP Petty Cash Paid</label>
            <div class="col-md-3">
                <input type="text" {{isset($data['insertedData']) ? 'readonly' : ''}} class="form-control form-control-sm text-right-align {{isset($data['insertedData']) ? 'make-readonly' : ''}}" id="imp_petty_cash" name="imp_petty_cash"
                       value="{{ old('imp_petty_cash', isset($data['insertedData']) ? $data['insertedData']->imp_petty_cash_paid : '' ) }}">
            </div>
            <div class="col-md-7  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
                <div class="row  d-flex justify-content-end">
                    <label class="col-form-label col-md-4" for="rev_petty_cash">REV Petty Cash Paid</label>
                    <div class="col-md-5">
                        <input type="text" {{isset($data['insertedData']) ? 'readonly' : ''}} class="form-control form-control-sm text-right-align {{isset($data['insertedData']) ? 'make-readonly' : ''}}" id="rev_petty_cash" name="rev_petty_cash"
                               value="{{ old('rev_petty_cash', isset($data['insertedData']) ? $data['insertedData']->rev_petty_cash_paid : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="border p-1 d-none" id="payment_hold_control">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Payment Hold Control</legend>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="hold_all_payment"></label>
            <div class="col-md-3">
                <div class="form-check">
                    <input disabled class="form-check-input" type="checkbox" value="Y" name="hold_all_payment"
                           tabindex="-1"
                           {{ old('hold_all_payment', isset($data['insertedData']) ? $data['insertedData']->payment_hold_flag : '' ) == '1' ? 'Checked' : '' }}
                           id="hold_all_payment">
                    <label class="form-check-label" for="hold_all_payment">
                        Hold All Payments
                    </label>
                </div>
            </div>
        </div>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="hold_all_payment_reason">Payment Hold Reason</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm " id="hold_all_payment_reason" readonly
                       value="{{ old('hold_all_payment_reason', isset($data['insertedData']) ? $data['insertedData']->payment_hold_reason : '') }}">
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-1 @if (!isset($data['insertedData']->vendor_id)) d-none @endif">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor's Inactive Status</legend>
        <div class=" row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2"></label>
            <div class="col-md-3">
                <div class="form-check">
                    <input {{ isset($data['insertedData']) ? '' : __('disabled') }} class="form-check-input"
                           type="checkbox" value="Y" name="is_inactive" tabindex="-1"
                           {{ old('is_inactive', isset($data['insertedData']) ? $data['insertedData']->inactive_yn : '' ) == '1' ? 'Checked' : '' }}
                           id="is_inactive">
                    <label class="form-check-label" for="is_inactive">
                        Vendor Inactive
                    </label>
                </div>
            </div>
        </div>
        <div class=" row  {{--{{ ($data['readonly'] == true) ? 'make-readonly' : '' }}--}}">
            <label class="col-form-label col-md-2" for="inactive_date_field">Inactive Date</label>
            <div class="col-md-3">
                <div class="input-group date inactive_date make-readonly"
                     id="inactive_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="inactive_date" readonly
                           id="inactive_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#inactive_date"
                           data-toggle="datetimepicker"
                           value="{{ old('inactive_date', isset($data['insertedData']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->inactive_date) : '') }}"
                           data-predefined-date="{{ old('inactive_date', isset($data['insertedData']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->inactive_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
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
    <div class="row mt-1">
        <div class="col-md-5">
            @if ($data['readonly'] == false)
                <button type="submit" class="btn btn-success btn-sm" id="vendor_form"><i class="bx bx-save font-size-small"></i>

                    @if (isset($data['insertedData']))
                        Update
                    @else
                        Save
                    @endif
                </button>
            @endif

            @if (isset($data['insertedData']))
                <a href="{{ route('vendor-search.index') }}" class="btn btn-dark btn-sm">
                    <i class="bx bx-reset font-size-small"></i>Back
                </a>
            @else
                <button type="reset" class="btn btn-dark btn-sm">
                    <i class="bx bx-reset font-size-small"></i>Reset
                </button>
            @endif

        </div>
    </div>
</form>

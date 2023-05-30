<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:৩০ AM
 */
?>
<p class="font-weight-bold" style="text-decoration: underline;">Customer Profile</p>
<form id="customerProfileForm" name="customerProfileForm" method="post"
      @if (isset($data['insertedData']))
      action="{{ route('customer-profile.update',['id'=>$data['insertedData']->customer_id]) }}">
    {{ method_field('PUT') }}
    @else
        action="{{route('customer-profile.insert')}}">
    @endif
    @csrf
    <fieldset class="border p-2">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Basic Information</legend>
        <div class="form-group row">
            <label for="id" class="col-form-label col-md-2">ID</label>
            <div class="col-md-2">
                <input type="text" class="form-control" id="id"
                       value="{{old('id',isset($data['insertedData']) ? $data['insertedData']->customer_id : '' )}}"
                       readonly>
            </div>

            <div class="offset-1 col-md-7 {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
                <div class="row d-flex justify-content-end">
                    <label class="col-form-label col-md-4 text-left required" for="opening_date_field">Opening Date</label>
                    <div class="input-group date opening_date col-md-5" id="opening_date" data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false" required
                               name="opening_date"
                               id="opening_date_field"
                               class="form-control datetimepicker-input"
                               data-target="#opening_date"
                               data-toggle="datetimepicker"
                               value="{{ old('opening_date', isset($data['insertedData']->opening_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                               data-predefined-date="{{ old('opening_date', isset($data['insertedData']->opening_date) ? \App\Helpers\HelperClass::dateConvert($data['insertedData']->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append opening_date" data-target="#opening_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="customer_category">Customer Category</label>
            <div class="col-md-3">
                <select required class="form-control" id="customer_category" name="customer_category" data-predefined="{{ isset($data['insertedData']) ? $data['insertedData']->customer_category_id : '' }}">
                    <option value="">Select Category</option>
                    @foreach($data['customerCategory'] as $type)
                        <option
                            value="{{$type->customer_category_id}}" {{ old('customer_category', isset($data['insertedData']) ? $data['insertedData']->customer_category_id : '' ) == $type->customer_category_id ? 'Selected' : '' }}>{{$type->customer_category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
                <div class="row d-flex justify-content-end">
                    <label class="col-form-label col-md-4" for="shipping_agency_id">Shipping Agent ID</label>
                    <div class="col-md-5">
                        <select class="form-control make-readonly-bg" id="shipping_agency_id" name="shipping_agency_id" data-predefined="{{ isset($data['insertedData']) ? $data['insertedData']->agency_id : '' }}">
                            <option value="">Select Agent</option>
                            @foreach($data['shippingAgencies'] as $agency)
                                <option
                                    value="{{$agency->agency_id}}" {{ old('shipping_agency_id', isset($data['insertedData']) ? $data['insertedData']->agency_id : '' ) == $agency->agency_id ? 'Selected' : '' }}>{{$agency->agency_name}} ({{$agency->agency_no}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="name">Customer Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control" id="name" required name="name"
                       value="{{ old('name', isset($data['insertedData']) ? $data['insertedData']->customer_name : '' ) }}">
            </div>
        </div>
        <div class="form-group row  {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="short_name">Short Name</label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="short_name" name="short_name"
                       value="{{ old('short_name', isset($data['insertedData']) ? $data['insertedData']->customer_short_name : '' ) }}">
            </div>

            <div class="form-check offset-2">
                <input class="form-check-input" type="checkbox" value="Y" name="enlisted_customer" tabindex="-1"
                       {{ old('enlisted_customer', isset($data['insertedData']) ? $data['insertedData']->enlisted_customer_yn : '' ) == 'Y' ? 'Checked' : '' }}
                       id="enlisted_customer">
                <label class="form-check-label" for="enlisted_customer">
                    Enlisted Customer
                </label>
            </div>
        </div>
    </fieldset>
    <fieldset class="border p-2" id="unique_identification">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Unique Identification</legend>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="bin">BIN</label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="bin" name="bin"
                       value="{{ old('bin', isset($data['insertedData']) ? $data['insertedData']->bin_no : '' ) }}">
            </div>

            <div class="col-md-7">
                <div class="row  d-flex justify-content-end pr-0">
                    <label class="col-form-label col-md-4" for="vat">License No</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="license" name="license"
                               value="{{ old('vat', isset($data['insertedData']) ? $data['insertedData']->license_no : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="tin">TIN</label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="tin" name="tin"
                       value="{{ old('tin', isset($data['insertedData']) ? $data['insertedData']->tin_no : '' ) }}">
            </div>

            <div class="col-md-7">
                <div class="row  d-flex justify-content-end pr-0">
                    <label class="col-form-label col-md-4 text-left" for="license_exp_date_field">License Expiry Date</label>
                    <div class="input-group date license_exp_date col-md-5" id="license_exp_date" data-target-input="nearest">
                        <input type="text" autocomplete="off" onkeydown="return false"
                               name="license_exp_date"
                               id="license_exp_date"
                               class="form-control datetimepicker-input"
                               data-target="#license_exp_date"
                               data-toggle="datetimepicker"
                               value="{{ old('license_exp_date', isset($data['insertedData']->license_expiry_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->license_expiry_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                               placeholder="DD-MM-YYYY">
                        <div class="input-group-append license_exp_date" data-target="#license_exp_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="vat">VAT Registration No</label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="vat" name="vat"
                       value="{{ old('vat', isset($data['insertedData']) ? $data['insertedData']->vat_registration_no : '' ) }}">
            </div>

        </div>
    </fieldset>
    {{--<fieldset class="border p-2 d-none" id="dept_cost_mapping">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Dept/Cost Center Mapping</legend>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for=""></label>
            <div class="col-md-4">
                <div class="form-check">
                    <input disabled class="form-check-input" type="checkbox" value="Y" name="allow_dept_cost_center"
                           tabindex="-1"
                           {{ old('allow_dept_cost_center', isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_control_yn : '' ) == 'Y' ? 'Checked' : '' }}
                           id="allow_dept_cost_center">
                    <label class="form-check-label" for="allow_dept_cost_center">
                        Allow Department/Cost Center Control
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="dept_cost_center">Dept/Cost Center</label>
            <div class="col-md-10">
                <div class=" make-readonly-bg">
                    <select class="form-control select2" id="dept_cost_center" name="dept_cost_center">
                        <option value="">Select Type</option>
                        @foreach($data['department'] as $type)
                            <option
                                value="{{$type->department_id}}" {{ old('dept_cost_center', isset($data['insertedData']) ? $data['insertedData']->cost_center_dept_id : '' ) == $type->department_id ? 'Selected' : '' }}>{{$type->department_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </fieldset>--}}
    <fieldset class="border p-2 {{--d-none--}}" id="customer_address">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer Address</legend>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="address_type">Address Type</label>
            <div class="col-md-3 {{--make-readonly--}}">
                <select class="form-control" id="address_type" name="address_type" required>
                    <option value="">Select Type</option>
                    @foreach($data['addressType'] as $type)
                        <option
                            value="{{$type->address_type_id}}" {{ old('address_type', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->address_type_id : '' ) == $type->address_type_id ? 'Selected' : '' }}>{{$type->address_type_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="address_1">Address Line 1</label>
            <div class="col-md-10">
                <input type="text" {{--readonly--}} class="form-control" id="address_1" name="address_1" required
                       value="{{ old('address_1', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->address_line1 : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="address_2">Address Line 2</label>
            <div class="col-md-10">
                <input type="text" class="form-control" {{--readonly--}} id="address_2" name="address_2"
                       value="{{ old('address_2', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->address_line2 : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="city">City</label>
            <div class="col-md-3">
                <input type="text" {{--readonly--}} class="form-control" id="city" name="city" required
                       value="{{ old('city', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->city : '' ) }}">
            </div>
            <div class="col-md-7">
                <div class="form-group row d-flex justify-content-end">
                    <label class="col-form-label col-md-3 required" for="state">State/Division</label>
                    <div class="col-md-5">
                        <input type="text" {{--readonly--}} class="form-control" id="state" name="state" required
                               value="{{ old('state', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->state_name : '' ) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="postal_code">Postal Code</label>
            <div class="col-md-3">
                <input type="text" {{--readonly--}} class="form-control" id="postal_code" name="postal_code"
                       value="{{ old('postal_code', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->postal_code : '' ) }}">
            </div>
            <div class="col-md-7">
                <div class="form-group row d-flex justify-content-end pr-0">
                    <label class="col-form-label col-md-3 required" for="countries">Country</label>
                    <div class="col-md-5">
                        <select class="form-control select2" id="countries" name="country" required>
                            <option value="">Select Country</option>
                            @foreach($data['county'] as $type)
                                <option
                                    value="{{trim($type->country)}}" {{ (old('country', (isset($data['insertedData']) ? __($data['insertedData']->customer_address->country) : 'BANGLADESH')) == trim($type->country)) ? 'Selected' : '' }}
{{--
                                {{ !isset($data['insertedData']->customer_address->country) ? (($type->customer_address->country == 'BANGLADESH ') || ($type->customer_address->country == 'BANGLADESH') ? 'selected' : '' ) : '' }}
--}}
                                >{{$type->country}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <p class="font-weight-bold" style="text-decoration: underline;">Contact Person</p>

        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="contact_name">Name</label>
            <div class="col-md-10">
                <input type="text" {{--readonly--}} class="form-control" id="contact_name" name="contact_name" required
                       value="{{ old('contact_name', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->contact_person_name : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="designation">Designation</label>
            <div class="col-md-10">
                <input type="text" required {{--readonly--}} class="form-control" id="designation" name="designation"
                       value="{{ old('designation', isset($data['insertedData']) ? $data['insertedData']->customer_address->contact_person_designation : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="phone">Phone No(s)</label>
            <div class="col-md-10">
                <input type="text" {{--readonly--}} class="form-control" id="phone" name="phone"
                       value="{{ old('phone', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->contact_person_phone : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2 required" for="mobile">Mobile No(s)</label>
            <div class="col-md-10">
                <input type="text" {{--readonly--}} class="form-control" id="mobile" name="mobile" required
                       value="{{ old('mobile', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->contact_person_mobile : '' ) }}">
            </div>
        </div>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2" for="email">Email Address</label>
            <div class="col-md-10">
                <input type="text" {{--readonly--}} class="form-control" id="email" name="email"
                       value="{{ old('email', isset($data['insertedData']->customer_address) ? $data['insertedData']->customer_address->contact_person_email : '' ) }}">
            </div>

        </div>
    </fieldset>
    <fieldset class="border p-2 @if (!isset($data['insertedData']->customer_id)) d-none @endif">
        {{--Visible in edit mode--}}
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer's Inactive Status</legend>
        <div class="form-group row {{ ($data['readonly'] == true) ? 'make-readonly' : '' }}">
            <label class="col-form-label col-md-2"></label>
            <div class="col-md-3">
                <div class="form-check">
                    <input {{ isset($data['insertedData']) ? '' : __('disabled') }} class="form-check-input"
                           type="checkbox" value="Y" name="is_inactive" tabindex="-1"
                           {{ old('is_inactive', isset($data['insertedData']) ? $data['insertedData']->inactive_yn : '' ) == 'Y' ? 'Checked' : '' }}
                           id="is_inactive">
                    <label class="form-check-label" for="is_inactive">
                        Customer Inactive
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row  {{--{{ ($data['readonly'] == true) ? 'make-readonly' : '' }}--}}">
            <label class="col-form-label col-md-2" for="inactive_date_field">Inactive Date</label>
            <div class="col-md-3">
                <div class="input-group date inactive_date make-readonly-bg"
                     id="inactive_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false" readonly
                           name="inactive_date"
                           id="inactive_date_field"
                           class="form-control datetimepicker-input"
                           data-target="#inactive_date"
                           data-toggle="datetimepicker"
                           value="{{ old('inactive_date', isset($data['insertedData']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->inactive_date) : '') }}"
                           data-predefined-date="{{ old('inactive_date', isset($data['insertedData']->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($data['insertedData']->inactive_date) : '') }}"
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append inactive_date" data-target="#inactive_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </fieldset>
    <div class="row mt-1">
        <div class="col-md-5">
            @if ($data['readonly'] == false)
                <button type="submit" class="btn btn-success" id="customer_form"><i class="bx bx-save"></i>

                    @if (isset($data['insertedData']))
                        Update
                    @else
                        Save
                    @endif
                </button>
            @endif

            @if (isset($data['insertedData']))
                <a href="{{ route('customer-search.index') }}" class="btn btn-dark">
                    <i class="bx bx-reset"></i>Back
                </a>
            @else
                <button type="button" class="btn btn-dark" id="customerSetupReset" >
                    <i class="bx bx-reset"></i>Reset
                </button>
                <input type="reset" class="d-none" id="resetBtn">
            @endif

        </div>
    </div>
</form>

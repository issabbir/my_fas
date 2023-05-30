<?php
/**
 *Created by PhpStorm
 *Created at ২/১১/২১ ৩:৫২ PM
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
            <p class="font-weight-bold" style="text-decoration: underline;">Customer Profile Authorize View</p>
            <form id="customerProfileForm" name="customerProfileForm" method="post"
                  @if (isset($datas))
                  action="{{ route('customer-profile.update',['id'=>$datas->customer_auth_log_id]) }}">
                {{ method_field('PUT') }}
                @else
                    action="{{route('customer-profile.insert')}}">
                @endif
                @csrf
                <fieldset class="border p-1">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Basic Information</legend>
                    <div class=" row">
                        <label for="id" class="col-form-label col-md-2">ID</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm" id="id"
                                   value="{{old('id',isset($datas) ? $datas->customer_id : '' )}}"
                                   readonly>
                        </div>

                        <div class="offset-1 col-md-7 make-readonly">
                            <div class="row d-flex justify-content-end">
                                <label class="col-form-label col-md-3 text-left required" for="opening_date_field">Opening
                                    Date</label>
                                <div class="input-group date opening_date col-md-5" id="opening_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false" required readonly
                                           name="opening_date"
                                           id="opening_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#opening_date"
                                           data-toggle="datetimepicker"
                                           value="{{ old('opening_date', isset($datas->opening_date) ?  \App\Helpers\HelperClass::dateConvert($datas->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                                           data-predefined-date="{{ old('opening_date', isset($datas->opening_date) ? \App\Helpers\HelperClass::dateConvert($datas->opening_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
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
                        <label class="col-form-label col-md-2 required" for="customer_category">Customer
                            Category</label>
                        <div class="col-md-3">
                            <select readonly="" required class="form-control form-control-sm" id="customer_category"
                                    name="customer_category"
                                    data-predefined="{{ isset($datas) ? $datas->customer_category_id : '' }}">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($data['customerCategory'] as $type)
                                    <option
                                        value="{{$type->customer_category_id}}" {{ old('customer_category', isset($datas) ? $datas->customer_category_id : '' ) == $type->customer_category_id ? 'Selected' : '' }}>{{$type->customer_category_name}}</option>
                                @endforeach
                            </select>
                        </div>
{{--                        <div class="col-md-7  make-readonly">--}}
{{--                            <div class="row d-flex justify-content-end">--}}
{{--                                <label class="col-form-label col-md-3" for="shipping_agency_id">Shipping Agent--}}
{{--                                    ID</label>--}}
{{--                                <div class="col-md-5">--}}
{{--                                    <select class="form-control form-control-sm make-readonly-bg" id="shipping_agency_id"--}}
{{--                                            name="shipping_agency_id"--}}
{{--                                            data-predefined="{{ isset($datas) ? $datas->agency_id : '' }}">--}}
{{--                                        <option value="">&lt;Select&gt;</option>--}}
{{--                                        @foreach($data['shippingAgencies'] as $agency)--}}
{{--                                            <option--}}
{{--                                                value="{{$agency->agency_id}}" {{ old('shipping_agency_id', isset($datas) ? $datas->shipping_agent_id : '' ) == $agency->agency_id ? 'Selected' : '' }}>{{$agency->agency_name}}--}}
{{--                                                ({{$agency->agency_no}})--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2 required" for="name">Customer Name</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" id="name" required name="name"
                                   {{ isset($datas) ? 'readonly' : ''  }}
                                   value="{{ old('name', isset($datas) ? $datas->customer_name : '' ) }}">
                        </div>
                    </div>
                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2" for="short_name">Short Name</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="short_name" name="short_name"
                                   {{ isset($datas) ? 'readonly' : ''  }}
                                   value="{{ old('short_name', isset($datas) ? $datas->customer_short_name : '' ) }}">
                        </div>
                        <div class="col-md-7">
                            <div class="form-check offset-4" style="padding-left: 30px;">
                                <input class="form-check-input" type="checkbox" value="Y" name="enlisted_customer"
                                       tabindex="-1"
                                       {{ old('enlisted_customer', isset($datas) ? $datas->enlisted_customer_yn : '' ) == 'Y' ? 'Checked' : '' }}
                                       id="enlisted_customer">
                                <label class="form-check-label" for="enlisted_customer">
                                    Enlisted Customer
                                </label>
                            </div>
                        </div>

                    </div>
                </fieldset>
                <fieldset class="border p-1" id="unique_identification">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Unique Identification</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="bin">BIN</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="bin" name="bin" readonly
                                   value="{{ old('bin', isset($datas) ? $datas->bin_no : '' ) }}">
                        </div>

                        <div class="col-md-7">
                            <div class="row  d-flex justify-content-end pr-0">
                                <label class="col-form-label col-md-3" for="vat">License No</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" id="license" name="license" readonly
                                           value="{{ old('vat', isset($datas) ? $datas->license_no : '' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="tin">TIN</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="tin" name="tin" readonly
                                   value="{{ old('tin', isset($datas) ? $datas->tin_no : '' ) }}">
                        </div>

                        <div class="col-md-7">
                            <div class="row  d-flex justify-content-end pr-0">
                                <label class="col-form-label col-md-3 text-left" for="license_exp_date_field">License Expiry Date</label>
                                <div class="input-group date license_exp_date col-md-5" id="license_exp_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="license_exp_date" readonly
                                           id="license_exp_date"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#license_exp_date"
                                           data-toggle="datetimepicker"
                                           value="{{ old('license_exp_date', isset($datas->license_expiry_date) ?  \App\Helpers\HelperClass::dateConvert($datas->license_expiry_date) : \App\Helpers\HelperClass::getCurrentDate()) }}"
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append license_exp_date" data-target="#license_exp_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bx-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="vat">VAT Registration No</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="vat" name="vat" readonly
                                   value="{{ old('vat', isset($datas) ? $datas->vat_registration_no : '' ) }}">
                        </div>

                    </div>
                </fieldset>

                <fieldset class="border p-1 {{--d-none--}}" id="customer_address">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer Address</legend>
                    {{--<div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="address_type">Address Type</label>
                        <div class="col-md-3 --}}{{--make-readonly--}}{{--">
                            <select class="form-control form-control-sm" id="address_type" name="address_type" readonly required>
                                <option value="">Select Type</option>
                                @foreach($data['addressType'] as $type)
                                    <option
                                        value="{{$type->address_type_id}}" {{ old('address_type', isset($datas) ? $datas->customer_address->address_type_id : '' ) == $type->address_type_id ? 'Selected' : '' }}>{{$type->address_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="address_1">Address Line 1</label>
                        <div class="col-md-10">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="address_1" name="address_1"
                                   required readonly
                                   value="{{ old('address_1', isset($datas) ? $datas->address_line1 : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="address_2">Address Line 2</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control-sm" {{--readonly--}} id="address_2" name="address_2"
                                   readonly
                                   value="{{ old('address_2', isset($datas) ? $datas->address_line2 : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="city">City</label>
                        <div class="col-md-3">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="city" name="city" required
                                   readonly
                                   value="{{ old('city', isset($datas) ? $datas->city : '' ) }}">
                        </div>
                        <div class="col-md-7">
                            <div class=" row d-flex justify-content-end">
                                <label class="col-form-label col-md-3 required" for="state">State/Division</label>
                                <div class="col-md-5">
                                    <input type="text" {{--readonly--}} class="form-control form-control-sm" id="state" name="state"
                                           required readonly
                                           value="{{ old('state', isset($datas) ? $datas->state_name : '' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="postal_code">Postal Code</label>
                        <div class="col-md-3">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="postal_code" name="postal_code"
                                   required readonly
                                   value="{{ old('postal_code', isset($datas) ? $datas->postal_code : '' ) }}">
                        </div>
                        <div class="col-md-7">
                            <div class=" row d-flex justify-content-end pr-0">
                                <label class="col-form-label col-md-3 required" for="countries">Country</label>
                                <div class="col-md-5">
                                    <select class="form-control form-control-sm" id="countries" name="country" readonly required>
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($data['county'] as $type)
                                            <option
                                                value="{{$type->country_id}}" {{ (old('country', isset($datas->country) ? $datas->country : '' ) == $type->country_id) ? 'Selected' : '' }}
                                                {{ !isset($datas->country) ? (($type->country_id == '1') ? 'selected' : '' ) : '' }}
                                            >{{$type->country}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <p class="font-weight-bold" style="text-decoration: underline;">Contact Person</p>

                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="contact_name">Name</label>
                        <div class="col-md-10">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="contact_name"
                                   name="contact_name" required readonly
                                   value="{{ old('contact_name', isset($datas) ? $datas->contact_person_name : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="designation">Designation</label>
                        <div class="col-md-10">
                            <input type="text" required {{--readonly--}} class="form-control form-control-sm" id="designation"
                                   name="designation" readonly
                                   value="{{ old('designation', isset($datas) ? $datas->contact_person_designation : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="phone">Phone No(s)</label>
                        <div class="col-md-10">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="phone" name="phone" readonly
                                   value="{{ old('phone', isset($datas) ? $datas->contact_person_phone : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2 required" for="mobile">Mobile No(s)</label>
                        <div class="col-md-10">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="mobile" name="mobile" required
                                   readonly
                                   value="{{ old('mobile', isset($datas) ? $datas->contact_person_mobile : '' ) }}">
                        </div>
                    </div>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2" for="email">Email Address</label>
                        <div class="col-md-10">
                            <input type="text" {{--readonly--}} class="form-control form-control-sm" id="email" name="email" readonly
                                   value="{{ old('email', isset($datas) ? $datas->contact_person_email : '' ) }}">
                        </div>

                    </div>
                </fieldset>
                <fieldset class="border p-1 @if (!isset($datas->customer_id)) d-none @endif">
                    {{--Visible in edit mode--}}
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer's Inactive Status</legend>
                    <div class=" row make-readonly">
                        <label class="col-form-label col-md-2"></label>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input {{ isset($datas) ? '' : __('disabled') }} class="form-check-input"
                                       readonly
                                       type="checkbox" value="Y" name="is_inactive" tabindex="-1" disabled
                                       {{ old('is_inactive', isset($datas) ? $datas->inactive_yn : '' ) == 'Y' ? 'Checked' : '' }}
                                       id="is_inactive">
                                <label class="form-check-label" for="is_inactive">
                                    Customer Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class=" row  make-readonly">
                        <label class="col-form-label col-md-2" for="inactive_date_field">Inactive Date</label>
                        <div class="col-md-3">
                            <div class="input-group date inactive_date make-readonly"
                                 id="inactive_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off" onkeydown="return false" readonly
                                       name="inactive_date"
                                       id="inactive_date_field"
                                       class="form-control form-control-sm datetimepicker-input"
                                       data-target="#inactive_date"
                                       data-toggle="datetimepicker"
                                       value="{{ old('inactive_date', isset($datas->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($datas->inactive_date) : '') }}"
                                       data-predefined-date="{{ old('inactive_date', isset($datas->inactive_date) ?  \App\Helpers\HelperClass::dateConvert($datas->inactive_date) : '') }}"
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
                @if ($datas->workflow_approval_status != \App\Enums\ApprovalStatus::PENDING)
                    <div class="row  mt-1 pl-2">
                        <label for="authorizer" class="col-md-2 col-form-label">Authorizer </label>
                        <div class="col-md-3 ">
                            <input type="text" id="authorizer" class="form-control form-control-sm" name="authorizer" disabled
                                   value="{{$data['insertedData']->emp->employee->emp_name.' ('.$data['insertedData']->emp->employee->emp_code.')'}}"/>
                        </div>
                        <div class="col-md-2"><label for="comment" class="col-form-label">Comment </label></div>
                        <div class="col-md-3 ">
                            <input type="text" id="comment" class="form-control form-control-sm" name="comment"
                                   @if  ($datas->workflow_approval_status == \App\Enums\ApprovalStatus::APPROVED || $datas->workflow_approval_status == \App\Enums\ApprovalStatus::REJECT) disabled
                                   @endif
                                   value="{{isset($data['insertedData']->comment) ? $data['insertedData']->comment : ''}}"/>
                        </div>
                    </div>
                @endif

                <div class="row  mt-1">
                    <div class="col-md-12 d-flex justify-content-end">
                        @if ($datas->workflow_approval_status == \App\Enums\ApprovalStatus::PENDING)
                            <div>
                                <a class="btn btn-primary btn-sm mr-25 trans-approval"
                                   href="{{route('customer-profile-authorize.perform-authorize')}}"
                                   data-mapid="{{$data['insertedData']['workflow_mapping_id']}}"
                                   data-approval-status="{{ App\Enums\ApprovalStatus::APPROVED }}">
                                    <i class="bx bx-check-double cursor-pointer font-size-small"></i> Authorize
                                </a>

                            </div>
                        <!-- TODO: DISABLED FOR PRIMARY BASIS -->
                            <div>
                                <a class="btn btn-danger btn-sm mr-25 trans-approval"
                                   href="{{route('customer-profile-authorize.perform-authorize')}}"
                                   data-mapid="{{$data['insertedData']['workflow_mapping_id']}}"
                                   data-approval-status="{{ App\Enums\ApprovalStatus::REJECT }}">
                                    <i class="bx bx-check-double cursor-pointer font-size-small"></i> Decline
                                </a>
                                <a class="btn btn-sm btn-dark mr-25"
                                   href="{{route('customer-profile-authorize.index')}}">
                                    <i class="bx bx-log-out cursor-pointer font-size-small"></i> Back
                                </a>
                            </div>
                        <!-- TODO: DISABLED FOR PRIMARY BASIS -->
                        @else
                            <div>
                                <a class="btn btn-dark mr-25"
                                   href="{{route('customer-profile-authorize.index')}}">
                                    <i class="bx bx-log-out cursor-pointer"></i> Back
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
    <script>
        $(document).on("click", '.trans-approval', function (e) {
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
    </script>
@endsection

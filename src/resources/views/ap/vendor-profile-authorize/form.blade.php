<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="#" id="ap_vendor_search_form">
                    <fieldset class="border p-2">
                        <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor Search</legend>
                        <div class="row">
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label class="col-form-label" for="vendor_name">Name</label>--}}
{{--                                <div class="">--}}
{{--                                    <input type="text" class="form-control form-control-sm" id="vendor_name"--}}
{{--                                           name="vendor_name">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label class="col-form-label" for="vendor_short_name">Short Name</label>--}}
{{--                                <div class="">--}}
{{--                                    <input type="text" class="form-control form-control-sm" id="vendor_short_name"--}}
{{--                                           name="vendor_short_name">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group col-md-2">
                                <label class="col-form-label text-left" for="approval_status">Action Type</label>
                                <div class="">
                                    <select class="form-control form-control-sm  search-param" name="action_type" id="action_type">
                                            <option value="">All</option>
                                         @foreach(\App\Enums\ApprovalStatus::CRUD_ACTION as $key=>$value)
                                            @if ( $key != \App\Enums\ApprovalStatus::DELETE)
                                                <option value="{{$key}}"> {{ $value}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label text-left" for="approval_status">Approval
                                    Status</label>
                                <div class="">
                                    <select class="form-control form-control-sm search-param" id="approval_status"
                                            name="approval_status">
                                        @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                            <option
                                                value="{{$key}}"  > {{ $value}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12 d-flex justify-content-end">--}}
{{--                                <button type="submit" class="btn btn-primary btn-sm" id="vendorSearchSubmit"><i--}}
{{--                                        class="bx bx-search font-size-small"></i>Search--}}
{{--                                </button>--}}
{{--                                <button type="reset" class="btn btn-dark btn-sm ml-1">--}}
{{--                                    <i class="bx bx-reset font-size-small"></i>Reset--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </fieldset>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12  table-responsive">
                <table class="table table-sm table-bordered table-hover" id="vendorSearch">
                    <thead class="thead-dark">
                    <tr>
                        <th width="2%">ID</th>
                        <th width="41%">Name</th>
                        <th width="2%">Short Name</th>
                        <th width="40%">Address</th>
                        <th width="2%">Action Type</th>
                        <th width="2%">Status</th>
                        <th width="3%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

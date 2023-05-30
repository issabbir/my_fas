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
                <form action="#" id="ap_customer_search_form">
                    <fieldset class="border p-2">
                        <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer Search</legend>
                        <div class="row">
                            <div class=" col-md-3 form-group">
                                <label class="col-form-label" for="search_customer_name">Name</label>
                                <input type="text" class="form-control form-control-sm" id="search_customer_name"
                                       name="search_customer_name">
                            </div>
                            <div class=" col-md-3 form-group">
                                <label class="col-form-label" for="search_customer_short_name">Short Name</label>
                                <input type="text" class="form-control form-control-sm" id="search_customer_short_name"
                                       name="search_customer_short_name">
                            </div>
                            <div class=" col-md-3 form-group">
                                <label class="col-form-label text-left" for="search_customer_category">Customer
                                    Category</label>
                                <select class="form-control form-control-sm" id="search_customer_category"
                                        name="search_customer_category">
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach($data['customerCategory'] as $type)
                                        <option
                                            value="{{$type->customer_category_id}}" {{ old('search_customer_category', isset($data['insertedData']) ? $data['insertedData']->customer_category_id : '' ) == $type->customer_category_id ? 'Selected' : '' }}>{{$type->customer_category_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class=" col-md-2 form-group">
                                <label class="col-form-label text-left" for="search_customer_category">Approval Status</label>
                                <select class="form-control form-control-sm" id="s_approval_status"
                                        name="s_approval_status">
                                    <option value="">&lt;Select&gt;</option>
                                    <option value="P">Pending</option>
                                    <option value="A">Approved</option>
                                    <option value="D">Draft</option>
                                </select>

                            </div>


                        </div>
                        <div class="row mt-1">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-sm btn-primary" id="customerSearchSubmit"><i
                                        class="bx bx-search font-size-small"></i>Search
                                </button>
                                <button type="reset" class="btn btn-sm btn-sm btn-dark ml-1">
                                    <i class="bx bx-reset font-size-small"></i>Reset
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12  table-responsive">
                <table class="table table-sm table-bordered table-hover" id="customerSearch">
                    <thead class="thead-dark">
                    <tr>
                        <th width="3%">ID</th>
                        <th width="31%">Name</th>
                        <th width="3%">Short Name</th>
                        {{--<th>Category</th>--}}
                        <th width="40%">Address</th>
                        <th width="3%">Approval Status</th>
                        <th width="5%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

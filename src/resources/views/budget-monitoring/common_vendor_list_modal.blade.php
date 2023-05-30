<?php
/**
 *Created by PhpStorm
 *Created at ১৬/৯/২১ ৫:৩৩ PM
 */
?>
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
                <div class="modal fade text-left w-100" id="vendorListModal" tabindex="-1" role="dialog"
                     aria-labelledby="vendorListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="vendorListModalLabel">Vendor Search</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="#" id="vendor_search_form">
                                            <fieldset class="border p-2">
                                                <legend class="w-auto font-weight-bold" style="font-size: 15px">
                                                    Vendor Search
                                                </legend>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label class="col-form-label"
                                                               for="search_vendor_name">Name</label>
                                                        <input type="text" class="form-control"
                                                               id="search_vendor_name"
                                                               name="search_vendor_name">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="col-form-label"
                                                               for="search_vendor_short_name">Short Name</label>
                                                        <input type="text" class="form-control"
                                                               id="search_vendor_short_name"
                                                               name="search_vendor_short_name">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="col-form-label" for="search_vendor_type">Vendor
                                                            Type</label>
                                                        <select class="form-control" id="search_vendor_type"
                                                                name="search_vendor_type">
                                                            <option value="">&lt;Select&gt;</option>
                                                            @foreach($vendorType as $type)
                                                                <option
                                                                    value="{{$type->vendor_type_id}}">{{$type->vendor_type_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="col-form-label text-left"
                                                               for="search_vendor_category">Vendor
                                                            Category</label>
                                                        {{--<label class="col-form-label col-md-3" for="vendor_category">Vendor Category</label>--}}
                                                        <select class="form-control"
                                                                id="search_vendor_category"
                                                                name="search_vendor_category">
                                                            <option value="">&lt;Select&gt;</option>
                                                            @foreach($vendorCategory as $type)
                                                                <option
                                                                    value="{{$type->vendor_category_id}}">{{$type->vendor_category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-sm btn-primary"
                                                                id="ap_vendor_search_submit"><i
                                                                class="bx bx-search font-size-small align-middle"></i>Search
                                                        </button>
                                                        <button type="reset" class="btn btn-sm btn-dark ml-1"
                                                                id="ap_vendor_search_reset">
                                                            <i class="bx bx-reset font-size-small align-middle"></i>Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </fieldset>

                                        </form>
                                    </div>
                                </div>

                                <hr>
                                <div class="shadow-none">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover" style="width: 100% !important;"
                                               id="vendorSearch">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th style="width: 2% !important;">ID</th>
                                                <th style="width: 44% !important;">Name</th>
                                                <th style="width: 2% !important;">Short Name</th>
                                                {{--<th>Category</th>--}}
                                                <th style="width: 50% !important;">Address</th>
                                                <th style="width: 2% !important;">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
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

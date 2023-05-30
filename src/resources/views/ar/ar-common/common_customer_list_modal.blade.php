<section id="modal-sizes">
    <div class="row">
        <div class="col-12">
            <!--Modal Xl size -->
            <div class="mr-1 mb-1 d-inline-block">
                <!-- Button trigger for Extra Large  modal -->
            {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                Extra Large Modal
            </button>--}}
            <!--Extra Large Modal-->
                <div class="modal fade text-left" id="customerListModal" tabindex="-1" role="dialog"
                     aria-labelledby="customerListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="customerListModalLabel">Customer Search</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="#" id="customer_search_form">
                                            <fieldset class="border p-2">
                                                <legend class="w-auto font-weight-bold" style="font-size: 15px">
                                                    Customer Search
                                                </legend>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label class="col-form-label"
                                                               for="search_customer_name">Name</label>
                                                        <input type="text" class="form-control"
                                                               id="search_customer_name"
                                                               name="search_customer_name">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="col-form-label"
                                                               for="search_customer_short_name">Short Name</label>
                                                        <input type="text" class="form-control"
                                                               id="search_customer_short_name"
                                                               name="search_customer_short_name">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="col-form-label text-left"
                                                               for="search_customer_category">Customer
                                                            Category</label>
                                                        <select class="form-control"
                                                                id="search_customer_category"
                                                                name="search_customer_category">
                                                            <option value="">&lt;Select&gt;</option>
                                                            @foreach($customerCategory as $type)
                                                                <option
                                                                    value="{{$type->customer_category_id}}">{{$type->customer_category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-1">
                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-sm btn-primary"
                                                                id="ar_customer_search_submit">
                                                            <i class="bx bx-search font-size-small align-middle"></i>
                                                            <span class="align-middle">Search</span>
                                                        </button>
                                                        <button type="reset" class="btn btn-sm btn-dark ml-1"
                                                                id="ar_customer_search_reset">
                                                            <i class="bx bx-reset font-size-small"></i>
                                                            <span class="align-middle ml-25">Reset</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </fieldset>

                                        </form>
                                    </div>
                                </div>
                                <hr>
                                <div style="width: 100% !important;">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover" id="customerSearch">
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

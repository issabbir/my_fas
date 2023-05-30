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
                <div class="modal fade text-left w-100" id="accountListModal" tabindex="-1" role="dialog"
                     aria-labelledby="accountListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="accountListModalLabel">Account Search</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x font-size-small"></i></button>
                            </div>
                            <div class="modal-body">
                                <form action="#" id="acc_search_form">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="acc_department" class="col-md-4 col-form-label">Department</label>
                                                <input type="text" disabled name="acc_department" id="acc_department"
                                                       class="form-control form-control-sm col-md-8">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class=" form-group row">
                                                <label for="acc_type" class="col-md-5 col-form-label">Account
                                                    Type</label>
                                                <select class="form-control form-control-sm col-md-7" name="acc_type" id="acc_type"
                                                    {{--
                                                    * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID) REF# email
                                                    * Removed Required attribute.
                                                    * Logic added:04-04-2022
                                                    --}}>
                                                    <option value="">&lt;Select&gt;</option>
                                                    @foreach($coaParams as $type)
                                                        <option
                                                            value="{{$type->gl_type_id}}">{{$type->gl_type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="acc_name_code" id="acc_name_code"
                                                   class="form-control form-control-sm" placeholder="Look for Account Name or Code">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-sm btn-success acc_search" id="acc_search"><i
                                                    class="bx bx-search font-size-small align-middle"></i><span class="align-middle ml-25">Search</span>
                                            </button>
                                            <button type="reset" class="btn btn-sm btn-dark acc_reset" id="acc_modal_reset"><i
                                                    class="bx bx-reset font-size-small align-middle"></i><span class="align-middle ml-25">Reset</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="card shadow-none">
                                    <div class="table-responsive">
                                        <table id="account_list" class="table table-sm w-100">
                                            <thead class="thead-dark">
                                            <tr>
                                                {{--<th>SL</th>--}}
                                                <th>Account ID</th>
                                                <th>Account Name</th>
                                                <th>Account Code</th>
                                                {{--<th>Dept Name</th>--}}
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal"><i
                                        class="bx bx-x d-block d-sm-none font-size-small"></i>
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

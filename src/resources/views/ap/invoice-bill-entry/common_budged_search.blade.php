<?php
/**
 *Created by PhpStorm
 *Created at ২৪/১১/২১ ১:৪৩ PM
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
                <div class="modal fade text-left w-100" id="budgetListModal" tabindex="-1" role="dialog"
                     aria-labelledby="budgetListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="budgetListModalLabel">Search Budget Booking Info</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <fieldset class="border p-1">
                                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Search Criteria</legend>
                                    <form action="#" id="booking_search_form">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="s_fiscal_year" class="col-form-label required">Fiscal
                                                        Year</label>
                                                    <input type="text" name="s_fiscal_year" id="s_fiscal_year" readonly
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            {{--<div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="s_part_vendor_id" class="col-form-label required">Party/Vendor ID</label>
                                                    <input type="text" name="s_part_vendor_id" id="s_part_vendor_id" readonly
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>--}}
                                            <div class="col-md-4">
                                                <div class=" form-group">
                                                    <label for="s_department" class="col-form-label required">Budget Department</label>
                                                    <input type="text" name="s_department" id="s_department" readonly
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            {{--<div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="s_budget_head_name_code" class="col-form-label">Look for Budget Head Name or Code</label>
                                                    <input type="text" name="s_budget_head_name_code" id="s_budget_head_name_code"
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mt-1">
                                                    <button type="submit"
                                                            class="btn btn-dark btn-sm s_booking_search mt-1"
                                                            id="s_booking_search"><i class="bx bxs-search"></i>Search
                                                    </button>
                                                </div>

                                            </div>--}}
                                        </div>
                                    </form>
                                </fieldset>

                                {{--<div class="card shadow-none mt-2 budget_head_list_dv">
                                    <h6>Budget Head List</h6>
                                    <div class="table-responsive">
                                        <table id="budget_head_list" class="table table-sm w-100">
                                            <thead>
                                            <tr>
                                                <th>Head ID</th>
                                                <th>Head Name</th>
                                                <th>Sub-Category</th>
                                                <th>Category</th>
                                                <th>Budget Type</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
--}}
                                <div class="card shadow-none mt-2">
                                    <h6>Budget Booking List</h6>
                                    <div class="table-responsive">
                                        <table id="budget_booking_list" class="table table-sm w-100">
                                            <thead>
                                            <tr>
                                                <th>Head ID</th>
                                                <th>Head Name</th>
                                                <th>Category</th>
                                                <th>Budget Type</th>
                                                <th>Booking Amount</th>
                                                <th>Utilized Amount</th>
                                                <th>Available Amount</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
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


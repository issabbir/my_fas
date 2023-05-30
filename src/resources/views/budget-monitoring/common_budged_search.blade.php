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
                                <h4 class="modal-title white" id="budgetListModalLabel">Search Budget Head</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <fieldset class="border p-1">
                                    <legend class="w-auto" style="font-size: 14px; font-weight: bold">Search Criteria
                                    </legend>

                                    <form action="#" id="booking_search_form">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="s_fiscal_year" class="col-form-label required">Financial
                                                        Year</label>
                                                    <input type="text" name="s_fiscal_year" id="s_fiscal_year" readonly tabindex="-1"
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class=" form-group">
                                                    <label for="s_department" class="col-form-label required">Department/Cost
                                                        Center</label>
                                                    <input type="text" name="s_department" id="s_department" readonly tabindex="-1"
                                                           class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mt-1">
                                                    <button type="submit"
                                                            class="btn btn-dark btn-sm s_booking_search mt-1"
                                                            id="s_booking_search">{{--<i class="bx bxs-search"></i>--}}Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </fieldset>
                                <div class="card shadow-none">
                                    <div class="table-responsive">
                                        <table id="budget_head_list" class="table table-sm w-100">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Head Name</th>
                                                <th>Sub-Category</th>
                                                <th>Category</th>
                                                <th>Budget Type</th>
                                                <th>Balance</th>
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


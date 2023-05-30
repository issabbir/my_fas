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
                <div class="modal fade text-left w-100" id="poListModal" tabindex="-1" role="dialog"
                     aria-labelledby="poListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="poListModalLabel">Purchase Order Search</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="#" id="po_search_form">
                                            <fieldset class="border p-2">
                                                <legend class="w-auto font-weight-bold" style="font-size: 15px">
                                                    Search Goods Received Information
                                                </legend>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-2"
                                                           for="modal_vendor_name">Supplier/Vendor</label>
                                                    <div class="col-md-10">
                                                        <select class="form-control make-readonly-bg" readonly=""
                                                                id="modal_vendor_name"
                                                                name="modal_vendor_name">
                                                            {{--<option value="">Select Supplier/Vendor</option>
                                                            @foreach($vendors as $type)
                                                                <option
                                                                    value="{{$type->vendor_id}}">{{$type->vendor_name}}</option>
                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-2"
                                                           for="modal_purchase_order_no">Purchase Order No</label>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               id="modal_purchase_order_no"
                                                               name="modal_purchase_order_no">
                                                    </div>
                                                    <label class="col-form-label col-md-2 offset-md-2"
                                                           for="modal_invoice_no">Invoice No</label>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               id="modal_invoice_no"
                                                               name="modal_invoice_no">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-2"
                                                           for="modal_po_date_field">Purchase Order Date</label>
                                                    <div class="input-group date modal_po_date col-md-3"
                                                         id="po_date"
                                                         data-target-input="nearest">
                                                        <input type="text" autocomplete="off"
                                                               onkeydown="return false"
                                                               name="modal_po_date"
                                                               id="modal_po_date_field"
                                                               class="form-control datetimepicker-input"
                                                               data-target="#modal_po_date"
                                                               data-toggle="datetimepicker"
                                                               value=""
                                                               data-predefined-date=""
                                                               placeholder="DD-MM-YYYY">
                                                        <div class="input-group-append modal_po_date" data-target="#modal_po_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="bx bx-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label class="col-form-label col-md-2 offset-md-2"
                                                           for="modal_invoice_date_field">Invoice Date</label>
                                                    <div class="input-group date modal_po_date col-md-3"
                                                         id="invoice_date"
                                                         data-target-input="nearest">
                                                        <input type="text" autocomplete="off"
                                                               onkeydown="return false"
                                                               name="modal_invoice_date"
                                                               id="modal_invoice_date_field"
                                                               class="form-control datetimepicker-input"
                                                               data-target="#modal_invoice_date"
                                                               data-toggle="datetimepicker"
                                                               value=""
                                                               data-predefined-date=""
                                                               placeholder="DD-MM-YYYY">
                                                        <div class="input-group-append modal_invoice_date" data-target="#modal_invoice_date"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="bx bx-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="row mt-1">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary"
                                                            id="ap_po_search_submit"><i
                                                            class="bx bx-search"></i>Search
                                                    </button>
                                                    <button type="reset" class="btn btn-dark ml-1"
                                                            id="ap_po_search_reset">
                                                        <i class="bx bx-reset"></i>Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr>
                                <div class="card shadow-none">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover  w-100"
                                               id="poSearchTable">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th>SL</th>
                                                <th>PO Number</th>
                                                <th>PO Date</th>
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Invoice Amount</th>
                                                <th>Action</th>
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


<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <p class="font-weight-bold" style="text-decoration: underline;">Vendor Account Balance Inquiry</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="border p-2">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Vendor Account Master</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 required" for="ap_search_vendor_id">Vendor ID</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control" id="ap_search_vendor_id" onfocusout="addZerosInAccountId(this)"
                                   name="ap_search_vendor_id"
                                   onkeyup="resetBalanceQuery();">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" id="ap_vendor_search" type="button"
                                    tabindex="-1"><i
                                    class="bx bx-search"></i>Search
                            </button>
                            <button type="reset" class="btn btn-dark ml-1" id="ap_reset_vendor_balance_field"
                                    onclick="resetBalanceQuery();resetField(['#ap_search_vendor_id'])">
                                <i class="bx bx-reset"></i>Reset
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_search_vendor_name">Vendor Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ap_search_vendor_name" readonly
                                   name="ap_search_vendor_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_search_vendor_category">Vendor Category</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ap_search_vendor_category" readonly
                                   name="ap_search_vendor_category">
                        </div>
                    </div><div class=" row">
                        <label class="col-form-label col-md-2" for="ap_search_customer_category">Regular Posting Period</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" id="regular_posting_period" readonly
                                   name="regular_posting_period">
                        </div>
                    </div>

                    <br>
                    {{--
                    /***0002649: Balance Inquiry -- AP & AR Module****/
                    <p class="font-weight-bold" style="text-decoration: underline;">Outstanding Balance Inquiry</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="ap_bills_payable" class="col-md-4 col-form-label">Bills Payable</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" readonly
                                           name="ap_bills_payable"
                                           id="ap_bills_payable"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="provision_expenses" class="col-md-4 col-form-label">Provision for Expenses</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" readonly
                                           name="provision_expenses"
                                           id="provision_expenses"
                                           value="" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ap_security_deposits" class="col-md-4 col-form-label">Security Deposits Payable</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" readonly
                                           name="ap_security_deposits"
                                           id="ap_security_deposits"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="ap_advance" class="col-md-4 col-form-label">O/S Advance</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" name="ap_advance" readonly
                                           id="ap_advance"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ap_prepayments" class="col-md-4 col-form-label">O/S Prepayments</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" readonly
                                           name="ap_prepayments"
                                           id="ap_prepayments"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ap_imprest_revolving_cash" class="col-md-4 col-form-label">O/S Imprest/Revolving Cash</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control text-right" name="ap_imprest_revolving_cash" readonly
                                           id="ap_imprest_revolving_cash"
                                           value="">
                                </div>
                            </div>
                            --}}{{--<div class="form-group row">
                                <label for="ap_revolving_cash" class="col-md-4 col-form-label">O/S Revolving Cash</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="ap_revolving_cash" readonly
                                           id="ap_revolving_cash"
                                           value="">
                                </div>
                            </div>--}}{{--
                        </div>
                    </div>--}}
                </fieldset>
                {{--<p class="font-weight-bold mt-1" style="text-decoration: underline;">Party Sub-ledger Details</p>--}}
                <p class="font-weight-bold mt-1" style="text-decoration: underline;">Party Ledger Balance Information</p>
                <div class="row">
                    <div class="col-md-12 table-responsive ">
                        <table class="table table-sm table-bordered " id="sub_ledger_detail" >
                            <thead class="thead-dark">
                            <tr class="text-nowrap">
                                <th width="2%" class="text-left">Ledger ID</th>
                                <th width="28%" class="text-left">Party Ledger Name</th>
                                <th width="15%"class="text-right-align">Opening Balance</th>
                                <th width="15%" class="text-right-align">Debit Sum.</th>
                                <th width="15%" class="text-right-align">Credit Sum.</th>
                                <th width="15%" class="text-right-align">Current Balance</th>
                                <th width="15%"  class="text-right-align">Unauth. Debit</th>
                                <th width="15%"  class="text-right-align">Unauth. Credit </th>
                                <th width="10%" class="text-right-align">Authorized Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

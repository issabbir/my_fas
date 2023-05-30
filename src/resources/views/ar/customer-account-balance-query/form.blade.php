<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <p class="font-weight-bold" style="text-decoration: underline;">Customer Account Balance Inquiry</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="border p-2">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px">Customer Account Master</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 required" for="ap_search_customer_id">Customer ID</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control form-control-sm" id="ap_search_customer_id"
                                   onfocusout="addZerosInAccountId(this)"
                                   name="ap_search_customer_id"
                                   onkeyup="resetBalanceQuery()">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-sm btn-primary" id="ap_customer_search" type="button"
                                    tabindex="-1"><i
                                    class="bx bx-search font-size-small"></i>Search
                            </button>
                            <button type="reset" class="btn btn-sm btn-dark ml-1" id="ap_reset_customer_balance_field"
                                    onclick="resetBalanceQuery();;resetField(['#ap_search_customer_id'])">
                                <i class="bx bx-reset font-size-small"></i>Reset
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_search_customer_name">Customer Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" id="ap_search_customer_name" readonly
                                   name="ap_search_customer_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_search_customer_category">Customer
                            Category</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" id="ap_search_customer_category" readonly
                                   name="ap_search_customer_category">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2" for="ap_search_customer_category">Regular Posting Period</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" id="regular_posting_period" readonly
                                   name="regular_posting_period">
                        </div>
                    </div>
                    <br>
                    {{--
                                        /***0002649: Balance Inquiry -- AP & AR Module****/

                    <p class="font-weight-bold" style="text-decoration: underline;">Outstanding Balance Summary</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class=" row">
                                <label for="ap_bills_payable" class="col-md-4 col-form-label">Accounts
                                    Receivable</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm text-right" readonly
                                           name="ap_bills_payable"
                                           id="ap_bills_payable"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>--}}
                </fieldset>
                <p class="font-weight-bold mt-1" style="text-decoration: underline;">Party Sub-ledger Balance Information</p>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered" id="sub_ledger_detail" >
                            <thead class="thead-dark">
                            <tr class="text-nowrap">
                                <th width="5%" class="text-left">Ledger ID</th>
                                <th width="25%" class="text-left">Party Ledger Name</th>
                                <th width="12%" class="text-right-align">Opening Balance</th>
                                <th width="12%" class="text-right-align">Debit Sum.</th>
                                <th width="12%" class="text-right-align">Credit Sum.</th>
                                <th width="12%" class="text-right-align">Current Balance</th>
                                <th width="12%"  class="text-right-align">Unauth. Debit</th>
                                <th width="12%"  class="text-right-align">Unauth. Credit </th>
                                <th width="15%" class="text-right-align">Authorized Bal.</th>
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

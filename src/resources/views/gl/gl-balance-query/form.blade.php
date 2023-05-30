<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <p class="font-weight-bold" style="text-decoration: underline;">GL Balance Inquiry</p>
        </div>

        <fieldset class="border p-2">

            <div class="row">
                <div class="col-sm-12">
                    <legend class="w-auto font-weight-bold" style="font-size: 15px; text-decoration: underline;">GL Account Info</legend>
                    <div class="form-group row p-0">
                        <label class="col-form-label col-sm-2 required" for="account_id">Account ID</label>
                        <div class="col-sm-2 p-0">
                            <input name="account_id" class="form-control form-control-sm" value=""
                                   id="account_id" maxlength="10" type="number" oninput="maxLengthValid(this)"
                                   onfocusout="addZerosInAccountId(this)"
                                   onkeyup="resetAccountField();">
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-sm btn-primary searchAccount" id="searchAccount" type="button"
                                    tabindex="-1"><i class="bx bx-search font-size-small align-top"></i><span
                                    class="align-middle ml-25">Search</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row p-0">
                        <label class="col-form-label col-sm-2" for="account_name">Account Name</label>
                        <div class="col-sm-10 p-0">
                            <input type="text" class="form-control form-control-sm" id="account_name" readonly
                                   name="account_name">
                        </div>
                    </div>
                    <div class="form-group row p-0">
                        <label class="col-form-label col-sm-2 " for="account_type">Account Type</label>
                        <div class="col-sm-4 p-0">
                            <input type="text" class="form-control form-control-sm" id="account_type" readonly
                                   name="account_type">
                        </div>
                    </div>
                    <div class="form-group row p-0">
                        <label class="col-form-label col-sm-2" for="account_type">Regular Posting Period</label>
                        <div class="col-sm-4 p-0">
                            <input type="text" class="form-control form-control-sm" id="posting_period" readonly
                                   name="posting_period">
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class=" row p-0" id="show_hide" style="display: none">
                <div class="col-sm-12">
                    <p class="font-weight-bold mt-1" style="text-decoration: underline;">Balance Summary</p>

                    <div class=" form-group row">
                        <div class=" form-group col-sm-2 ">
                            <label class="col-form-label  float-right" for="account_type">Opening Balance</label>
                            <input type="text" class="form-control form-control-sm text-right" id="opening_balance" readonly
                                   name="opening_balance">
                        </div>
                        <div class=" form-group col-sm-1  mt-1 important d-flex align-items-end">
                            <input type="text" class="form-control form-control-sm text-right" id="opening_balance_type" readonly
                                   name="opening_balance_type">
                        </div>
                        <div class=" form-group col-sm-2 ">
                            <label class="col-form-label float-right" for="account_type">Debit Summation</label>
                            <input type="text" class="form-control form-control-sm text-right"  id="debit_summation" readonly
                                   name="debit_summation">
                        </div>
                        <div class="form-group col-sm-2 ">
                            <label class="col-form-label float-right" for="account_type">Credit Summation</label>

                                <input type="text" class="form-control form-control-sm text-right" id="credit_summation" readonly
                                       name="credit_summation">
                        </div>
                        <div class="form-group col-sm-2 ">
                            <label class="col-form-label float-right" for="account_type">Current Balance</label>

                            <input type="text" class="form-control form-control-sm text-right" id="account_balance" readonly
                                   name="account_balance">
                        </div>

                        <div class="form-group col-sm-1  mt-1 important d-flex align-items-end">
                            <input type="text" class="form-control form-control-sm text-right" id="account_balance_type" readonly
                                   name="account_balance_type">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5"></div>
                        <label class="col-form-label col-sm-2 text-right" for="account_type"> Debit Unauthorized </label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm text-right" id="unauth_debit_amount" readonly
                                   name="unauth_debit_amount">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5"></div>
                        <label class="col-form-label col-sm-2 text-right" for="account_type">Credit Unauthorized</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm text-right" id="unauth_credit_amount" readonly
                                   name="unauth_credit_amount">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5"></div>
                        <label class="col-form-label col-sm-2 text-right" for="account_type">Authorized Balance</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm text-right" id="authorize_balance" readonly
                                   name="authorize_balance">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" class="form-control form-control-sm text-right" id="authorize_balance_type" readonly
                                   name="authorize_balance_type">
                        </div>
                    </div>

                </div>
            </div>
        </fieldset>

    </div>
</div>

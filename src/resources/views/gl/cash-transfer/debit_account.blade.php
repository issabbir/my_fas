<fieldset class="border p-2 col-md-12">
    <legend class="w-auto" style="font-size: 12px; font-weight: bold">DEBIT ACCOUNT: TO CASH/BANK</legend>
    <div class="form-group row d_bank_acc_field make-readonly">
        <label class="col-md-2 col-form-label required" for="d_bank_acc_id">Cash/Bank Account</label>
        <div class="col-md-6 pl-0 pr-0">
            <select data-gl-acc-id="" class="form-control form-control-sm" id="d_bank_acc_id" name="d_bank_acc_id"
                    required tabindex="-1">
                <option value="">&lt;Select&gt;</option>
            </select>
        </div>
        <label class="col-md-2 col-form-label " for="d_account_balance">Account Balance</label>
        {{--<div class="col-md-2">--}}
        <div class="input-group col-md-2 pl-0 pr-0">
            <input name="d_account_balance" style="height: auto;"
                   class="form-control form-control-sm text-right-align input-value-clear" value=""
                   id="d_account_balance" tabindex="-1" readonly/>
            <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="d_account_balance_type"></span>
            </div>
        </div>
        {{--</div>--}}
    </div>
    <div class="row">
        <label class="col-md-2 col-form-label" for="d_currency">Currency</label>
        <div class="col-md-6">
            <div class="row">
                <input class="form-control form-control-sm col-md-2 input-value-clear" id="d_currency"
                       name="d_currency" type="text" readonly tabindex="-1"/>
                <span class="col-md"></span>
                <label class="col-md-3 col-form-label required" for="d_amount_ccy">Amount CCY</label>
                <input class="form-control form-control-sm col-md-4 text-right-align " tabindex="-1" readonly
                       id="d_amount_ccy" name="d_amount_ccy" type="number" maxlength="17"
                       oninput="maxLengthValid(this)" min="0" required value="0" step="0.01"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <label class="col-md-6 col-form-label" for="d_authorized_balance">Authorized Balance</label>
                {{--<div class="col-md-6">--}}
                <div class="input-group col-md-6 pl-0 pr-0">
                    <input name="d_authorized_balance" style="height: auto"
                           class="form-control form-control-sm text-right-align input-value-clear" value=""
                           id="d_authorized_balance" readonly tabindex="-1">
                    <div class="input-group-append">
                            <span class="input-group-text" style="font-size: 13px"
                                  id="d_authorized_balance_type"></span>
                    </div>
                </div>
                {{--</div>--}}
            </div>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 col-form-label" for="d_exchange_rate">Exchange Rate</label>
        <div class="col-md-6">
            <div class="form-group row">
                <input class="form-control form-control-sm col-md-2 input-value-clear" id="d_exchange_rate"
                       name="d_exchange_rate" type="number" value="0" readonly tabindex="-1"/>
                <span class="col-md"></span>
                <label class="col-md-3 col-form-label" for="d_amount_lcy">Amount LCY</label>
                <input class="form-control form-control-sm col-md-4 text-right-align input-value-clear"
                       id="d_amount_lcy" name="d_amount_lcy" type="text" readonly tabindex="-1"/>
            </div>
        </div>
    </div>
</fieldset>

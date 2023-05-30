<fieldset class="border col-md-12 p-2">
    <legend class="w-auto" style="font-size: 12px; font-weight: bold">CREDIT ACCOUNT: BY CASH/BANK</legend>
    <div class="form-group row">
        <label class="col-md-2 col-form-label required" for="c_bank_acc_id">Bank Account</label>
        <div class="col-md-6 pl-0 pr-0">
            <select class="form-control form-control-sm" id="c_bank_acc_id" name="c_bank_acc_id" required>
                <option value="">&lt;Select&gt;</option>
            </select>
        </div>
        <label class="col-md-2 col-form-label" for="c_account_balance">Account Balance</label>
        {{--<div class="col-md-2">--}}
        <div class="input-group col-md-2 pl-0 pr-0">
            <input name="c_account_balance" style="height: auto"
                   class="form-control form-control-sm text-right-align input-value-clear" value=""
                   id="c_account_balance" readonly tabindex="-1"/>
            <div class="input-group-append">
                                        <span class="input-group-text" style="font-size: 13px"
                                              id="c_account_balance_type"></span>
            </div>
        </div>
        {{--</div>--}}
    </div>
    <div class="row">
        <label class="col-md-2 col-form-label" for="c_currency">Currency</label>
        <div class="col-md-6">
            <div class="row">
                <input class="form-control form-control-sm col-md-2 input-value-clear" id="c_currency"
                       name="c_currency" type="text" readonly tabindex="-1"/>
                <span class="col-md"></span>
                <label class="col-md-3 col-form-label" for="c_amount_ccy">Amount CCY</label>
                <input class="form-control form-control-sm col-md-4 text-right-align input-value-clear"
                       id="c_amount_ccy" name="c_amount_ccy" maxlength="17"
                       oninput="maxLengthValid(this);this.value = this.value.match(/\d+\.?\d{0,2}/);"
                       type="text"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <label class="col-md-6 col-form-label" for="c_authorized_balance">Authorized Balance</label>
                {{--                    <div class="col-md-6">--}}
                <div class="input-group col-md-6 pl-0 pr-0">
                    <input name="c_authorized_balance" style="height: auto"
                           class=" form-control form-control-sm text-right-align input-value-clear"
                           value=""
                           id="c_authorized_balance" readonly tabindex="-1">
                    <div class="input-group-append">
                            <span class="input-group-text" style="font-size: 13px"
                                  id="c_authorized_balance_type"></span>
                    </div>
                </div>
                {{--                    </div>--}}
            </div>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 col-form-label" for="c_exchange_rate">Exchange Rate</label>
        <div class="col-md-6">
            <div class="row">
                <input class="form-control form-control-sm col-md-2 input-value-clear" id="c_exchange_rate"
                       name="c_exchange_rate" type="text" readonly tabindex="-1"/>
                <span class="col-md"></span>
                <label class="col-md-3 col-form-label" for="c_amount_lcy">Amount LCY</label>
                <input class="form-control form-control-sm col-md-4 text-right-align input-value-clear"
                       id="c_amount_lcy" name="c_amount_lcy" type="text" readonly tabindex="-1"/>
            </div>
        </div>
    </div>
    {{--
        CPA don't want this: Yousuf Imam 13/06/2022
    <div class="form-group row hidden" id="chequeRow">
        <div class="col-md-2">
            <label class="col-form-label" for="c_cheque_no">Cheque No</label>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-0">
                <input maxlength="10" class="form-control form-control-sm col-md-4" id="c_cheque_no" name="c_cheque_no" type="text">
                <span class="col-md"></span>
                <label class="col-md-3 col-form-label" for="c_cheque_date_field">Cheque Date</label>
                <div class="input-group date c_cheque_date col-md-4 pl-0 pr-0"
                     id="c_cheque_date"
                     data-target-input="nearest">
                    <input type="text" autocomplete="off" onkeydown="return false"
                           name="c_cheque_date"
                           id="c_cheque_date_field"
                           class="form-control form-control-sm datetimepicker-input"
                           data-target="#c_cheque_date"
                           data-toggle="datetimepicker"
                           value=""
                           data-predefined-date=""
                           placeholder="DD-MM-YYYY">
                    <div class="input-group-append c_cheque_date" data-target="#c_cheque_date"
                         data-toggle="datetimepicker">
                        <div class="input-group-text">
                            <i class="bx bxs-calendar font-size-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4 d-flex justify-content-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{\App\Enums\YesNoFlag::YES}}" name="c_without_cheque"
                       id="withoutCheque">
                <span class="form-check-label" for="withoutCheque">
                                    Without Cheque
                                </span>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <label class="col-form-label col-md-2" for="c_amount_word">In Words</label>
        <div class="col-md-6 pl-0 pr-0">
                <textarea readonly tabindex="-1" class="form-control form-control-sm"
                          id="c_amount_word"></textarea>
        </div>
    </div>
</fieldset>

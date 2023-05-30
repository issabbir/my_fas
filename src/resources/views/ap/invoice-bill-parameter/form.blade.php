<p class="font-weight-bold" style="text-decoration: underline;">Invoice/Bill Parameters</p>
<form id="invoiceBillParamForm" name="invoiceBillParamForm" method="post"
      @if (isset($data['insertedData']))
      action="{{ route('invoice-bill-parameter.edit',['id'=>$data['insertedData']->invoice_param_id]) }}">
    {{ method_field('PUT') }}
    @else
        action="{{route('invoice-bill-parameter.insert')}}">
    @endif
    @csrf
    <fieldset class="border pl-2 pr-2">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Register Parameters</legend>
        <div class="form-group row">
            <label for="parameter_id" class="col-form-label col-md-2">Parameter ID</label>
            <input type="text" class="form-control form-control-sm col-md-2" id="parameter_id" tabindex="-1"
                   value="{{old('parameter_id', isset($data['insertedData']) ? $data['insertedData']->invoice_param_id : '' )}}"
                   readonly>
        </div>

        {{-- TODO: Add Disabled condition for all field to view mode. PAVEL: 06-04-22 --}}

        <div class="form-group row">
            <label class="col-form-label col-md-2" for="note">Parameter Desc.</label>
            <input type="text" class="form-control form-control-sm col-md-9" id="note" name="note" @if (isset($viewModeYN)) disabled @endif
                   value="{{ old('note', isset($data['insertedData']) ? $data['insertedData']->invoice_param_note : '' ) }}">
        </div>
        <p class="font-weight-bold" style="text-decoration: underline;">Key Parameter</p>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="party_sub_ledger">Party Sub-Ledger</label>
            <select required class="form-control form-control-sm col-md-9" id="party_sub_ledger" name="party_sub_ledger"  @if (isset($viewModeYN)) disabled @endif >
                <option value="">&lt;Select&gt;</option>
                @foreach($data['subsidiary_type'] as $type)
                    <option
                        value="{{$type->gl_subsidiary_id}}" {{ (old('party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="invoice_type">Invoice Type</label>
            <select required class="form-control form-control-sm col-md-3" id="invoice_type" name="invoice_type"  @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['invoice_type'] as $type)
                    <option
                        value="{{$type->invoice_type_id}}" {{ (old('invoice_type', isset($data['insertedData']) ? $data['insertedData']->invoice_type_id : '' ) == $type->invoice_type_id) ? 'Selected' : '' }}>{{$type->invoice_type_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_debit_credit">Debit/Credit</label>
            <select class="form-control form-control-sm col-md-3" required id="ap_debit_credit" name="ap_debit_credit"  @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                <option
                    value="D" {{ old('ap_debit_credit', isset($data['insertedData']) ? $data['insertedData']->dr_cr_flag : '' ) == 'D' ? 'Selected' : ''  }}>
                    Debit
                </option>
                <option
                    value="C" {{ old('ap_debit_credit', isset($data['insertedData']) ? $data['insertedData']->dr_cr_flag : '' ) == 'C' ? 'Selected' : ''  }}>
                    Credit
                </option>
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 required" for="ap_vendor_type">Vendor Type</label>
            <select class="form-control form-control-sm col-md-3" id="ap_vendor_type" name="ap_vendor_type" required  @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['vendorType'] as $type)
                    <option
                        value="{{$type->vendor_type_id}}" {{ (old('ap_vendor_type', isset($data['insertedData']) ? $data['insertedData']->vendor_type_id : '' ) == $type->vendor_type_id) ? 'Selected' : '' }}>{{$type->vendor_type_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2 " for="ap_vendor_category">Vendor Category</label>
            <select class="form-control form-control-sm col-md-3 make-readonly-bg" id="ap_vendor_category" name="ap_vendor_category"  @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['vendorCategory'] as $category)
                    <option
                        value="{{$category->vendor_category_id}}" {{ (old('ap_vendor_category', isset($data['insertedData']) ? $data['insertedData']->vendor_category_id : '' ) == $category->vendor_category_id) ? 'Selected' : '' }}>{{$category->vendor_category_name}}</option>
                @endforeach
            </select>
        </div>
        {{-- TODO: ADD This part start-Pavel:18-04-22 --}}
        <div class="form-group row">
            <div class="form-check col-md-6 offset-md-2">
                <input class="form-check-input" type="checkbox" value="{{\App\Enums\YesNoFlag::YES}}" name="budget_head_required_yn"   @if (isset($viewModeYN)) disabled @endif
                {{ old('budget_head_required_yn', isset($data['insertedData']) ? $data['insertedData']->budget_head_required_yn : '' ) == \App\Enums\YesNoFlag::YES ? 'Checked' : '' }}
                id="budget_head_required_yn">
                <label class="form-check-label" for="budget_head_required_yn">Is Budget Head Required</label>
            </div>
        </div>
        {{-- TODO: ADD This part end-Pavel:18-04-22 --}}

        <div class="form-group row">
            <div class="form-check col-md-6 offset-md-2">
                <input class="form-check-input" type="checkbox" value="1" name="deduction_allowed" tabindex="-1"  @if (isset($viewModeYN)) disabled @endif
                       {{ old('deduction_allowed', isset($data['insertedData']) ? $data['insertedData']->ded_at_source_allow_flag : '' ) == '1' ? 'Checked' : '' }}
                       id="deduction_allowed">
                <label class="form-check-label" for="deduction_allowed">
                    Is Tax/VAT/Security Deposit Deduction at Source Allowed?
                </label>
            </div>
        </div>

        <p class="font-weight-bold" style="text-decoration: underline;">GL Integration for Liability Heads:</p>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="tax_account_id">Tax Payable</label>
            {{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}
            <div class="col-md-2 pl-0">
                <input readonly name="tax_account_id" class="form-control form-control-sm" @if (isset($viewModeYN)) disabled @endif
                       value="{{old('tax_account_id',isset($data['insertedData']) ? $data['insertedData']->tax_gl_acc_id : '' ) }}"
                       type="number" id="tax_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#tax_search_account_name'])">
            </div>

            <div class="col-md-2 d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary taxSearchAccount" id="{{!isset($viewModeYN ) ? 'tax_search_account' : '' }}" type="button"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::LIABILITY}}"
                        tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>

            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="tax_search_account_name" name="tax_search_account_name"
                       value="{{ old('tax_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->tax_acc)) ? $data['insertedData']->tax_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            {{--</div>--}}
        </div>

        {{--<div class="form-group row">
            <label class="col-form-label col-md-2" for="tax_search_account_name"></label>
            <input type="text" class="form-control col-md-9" id="tax_search_account_name" name="tax_search_account_name"
                   value="{{ old('tax_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->tax_acc)) ? $data['insertedData']->tax_acc->gl_acc_name : '') }}"
                   readonly>
        </div>--}}

        <div class="form-group row">
            <label class="col-form-label col-md-2" for="vat_account_id">VAT Payable</label>
            {{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}
            <div class="col-md-2 pl-0">
                <input readonly name="vat_account_id" class="form-control form-control-sm " type="number" @if (isset($viewModeYN)) disabled @endif
                       value="{{old('vat_account_id',isset($data['insertedData']) ? $data['insertedData']->vat_gl_acc_id : '' ) }}"
                       id="vat_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#vat_search_account_name'])">
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary vatSearchAccount" id="{{!isset($viewModeYN ) ? 'vat_search_account' : '' }}" type="button"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::LIABILITY}}"
                        tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="vat_search_account_name" name="vat_search_account_name"
                       value="{{ old('vat_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->vat_acc)) ? $data['insertedData']->vat_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            {{--</div>--}}
        </div>
        {{--<div class="form-group row">
            <label class="col-form-label col-md-2" for="vat_search_account_name"></label>
            <input type="text" class="form-control col-md-9" id="vat_search_account_name" name="vat_search_account_name"
                   value="{{ old('vat_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->vat_acc)) ? $data['insertedData']->vat_acc->gl_acc_name : '') }}"
                   readonly>
        </div>--}}
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="deposit_account_id">Sec Deposit Payable</label>
            {{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}
            <div class="col-md-2 pl-0">
                <input readonly name="deposit_account_id" class="form-control form-control-sm " type="number" @if (isset($viewModeYN)) disabled @endif
                       value="{{old('deposit_account_id',isset($data['insertedData']) ? $data['insertedData']->sec_gl_acc_id : '' ) }}"
                       id="deposit_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#deposit_search_account_name'])">

                {{--<div class="input-group-append">
                    <button type="button" class="input-group-text d_showAccountListModal"><i
                            class="bx bx-mouse"></i>Select
                    </button>
                </div>--}}
            </div>
            <div class="col-md-2  d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary depositSearchAccount" id="{{!isset($viewModeYN ) ? 'deposit_search_account' : '' }}"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::LIABILITY}}"
                        type="button" tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="deposit_search_account_name"
                       name="deposit_search_account_name"
                       value="{{ old('deposit_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->sec_acc)) ? $data['insertedData']->sec_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            {{--</div>--}}
        </div>
        {{--<div class="form-group row">
            <label class="col-form-label col-md-2" for="deposit_search_account_name"></label>
            <input type="text" class="form-control col-md-9" id="deposit_search_account_name"
                   name="deposit_search_account_name"
                   value="{{ old('deposit_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->sec_acc)) ? $data['insertedData']->sec_acc->gl_acc_name : '') }}"
                   readonly>
        </div>--}}

        {{--Block this sec start pavel: 06-04-22--}}
        {{--<p class="font-weight-bold" style="text-decoration: underline;">GL Integration for Income Heads:</p>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="fine_account_id">Fine/Forfeiture</label>
            --}}{{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}{{--
            <div class="col-md-2 pl-0">
                <input readonly name="fine_account_id" class="form-control form-control-sm "
                       value="{{old('fine_account_id',isset($data['insertedData']) ? $data['insertedData']->fine_gl_acc_id : '' ) }}"
                       type="number" id="fine_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#fine_search_account_name'])">
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary fineSearchAccount" id="fine_search_account" type="button"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::INCOME}}"
                        tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="fine_search_account_name" name="fine_search_account_name"
                       value="{{ old('fine_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->fine_acc)) ? $data['insertedData']->fine_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            --}}{{--</div>--}}{{--
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="psi_account_id">Preshipment Inspection (PSI)</label>
            --}}{{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}{{--
            <div class="col-md-2 pl-0">
                <input readonly name="psi_account_id" class="form-control form-control-sm " type="number"
                       value="{{old('psi_account_id',isset($data['insertedData']) ? $data['insertedData']->psi_gl_acc_id : '' ) }}"
                       id="psi_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#psi_search_account_name'])">
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary psiSearchAccount" id="psi_search_account" type="button"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::INCOME}}"
                        tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="psi_search_account_name" name="psi_search_account_name"
                       value="{{ old('psi_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->psi_acc)) ? $data['insertedData']->psi_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            --}}{{--</div>--}}{{--
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="electricity_account_id">Electricity Bill</label>
            --}}{{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}{{--
            <div class="col-md-2 pl-0">
                <input readonly name="electricity_account_id" class="form-control form-control-sm " type="number"
                       value="{{old('electricity_account_id',isset($data['insertedData']) ? $data['insertedData']->elec_gl_acc_id : '' ) }}"
                       id="electricity_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#electricity_search_account_name'])">

                --}}{{--<div class="input-group-append">
                    <button type="button" class="input-group-text d_showAccountListModal"><i
                            class="bx bx-mouse"></i>Select
                    </button>
                </div>--}}{{--
            </div>
            <div class="col-md-2  d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary electricitySearchAccount" id="electricity_search_account"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::INCOME}}"
                        type="button" tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="electricity_search_account_name"
                       name="electricity_search_account_name"
                       value="{{ old('electricity_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->elec_acc)) ? $data['insertedData']->elec_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            --}}{{--</div>--}}{{--
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="other_account_id">Other Charge</label>
            --}}{{--<div class="form-group row col-md-6 pl-0 mr-1 mb-0 pb-0">--}}{{--
            <div class="col-md-2 pl-0">
                <input readonly name="other_account_id" class="form-control form-control-sm " type="number"
                       value="{{old('other_account_id',isset($data['insertedData']) ? $data['insertedData']->others_gl_acc_id : '' ) }}"
                       id="other_account_id"
                       maxlength="10"
                       oninput="maxLengthValid(this)"
                       onfocusout="addZerosInAccountId(this)"
                       onkeyup="resetField(['#other_search_account_name'])">

                --}}{{--<div class="input-group-append">
                    <button type="button" class="input-group-text d_showAccountListModal"><i
                            class="bx bx-mouse"></i>Select
                    </button>
                </div>--}}{{--
            </div>
            <div class="col-md-2  d-flex justify-content-center">
                <button disabled class="btn btn-sm btn-primary otherSearchAccount" id="other_search_account"
                        data-gltype="{{\App\Enums\Common\GlCoaParams::INCOME}}"
                        type="button" tabindex="-1"><i
                        class="bx bx-search"></i>Search
                </button>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="other_search_account_name"
                       name="other_search_account_name"
                       value="{{ old('other_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->others_acc)) ? $data['insertedData']->others_acc->gl_acc_name : '') }}"
                       readonly>
            </div>
            --}}{{--</div>--}}{{--
        </div>--}}
        {{--Block this sec end pavel: 06-04-22--}}

        <p class="font-weight-bold" style="text-decoration: underline;">Party-Sub Ledger Integration for Contra Heads:</p>
        <div class="form-group row">
            <div class="form-check col-md-6 offset-md-2">
                <input class="form-check-input" type="checkbox" value="1" name="is_party_subLedger" tabindex="-1" @if (isset($viewModeYN)) disabled @endif
                       {{ old('is_party_subLedger', isset($data['insertedData']) ? $data['insertedData']->distrib_line_gl_sub_flag : '' ) == '1' ? 'Checked' : '' }}
                       id="is_party_subLedger">
                <label class="form-check-label" for="is_party_subLedger">
                    Is Contra Line is a Party Sub-Ledger?
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-md-2" for="contra_sub_ledger">Contra Sub-Ledger</label>
            <select class="form-control form-control-sm col-md-9 make-readonly-bg" id="contra_sub_ledger" name="contra_sub_ledger" @if (isset($viewModeYN)) disabled @endif
                    data-precontra="{{ old('contra_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->distrib_line_gl_sub_id : '' )  }}">

            </select>
        </div>

    </fieldset>
    <div class="row mt-1">
        <div class="col-md-5">
            @if (!isset($viewModeYN))
                <button type="submit" class="btn btn-success" id="vendor_form"><i class="bx bx-save"></i>
                    @if (isset($data['insertedData']))
                        Update
                    @else
                        Save
                    @endif
                </button>
            @endif
            @if (isset($data['insertedData']))
                <a href="{{ route('invoice-bill-parameter.index') }}" class="btn btn-dark">
                    <i class="bx bx-reset"></i>Cancel
                </a>
            @else
                <button type="reset" class="btn btn-dark">
                    <i class="bx bx-reset"></i>Reset
                </button>
            @endif
            {{--<button type="submit" class="btn btn-success" id=""><i class="bx bx-save"></i>Save</button>
            <button type="reset" class="btn btn-dark">
                <i class="bx bx-reset"></i>Reset
            </button>--}}
        </div>
    </div>
</form>


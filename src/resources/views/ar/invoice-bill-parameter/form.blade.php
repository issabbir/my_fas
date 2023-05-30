<?php
/**
 *Created by PhpStorm
 *Created at ৫/৯/২১ ৪:২১ PM
 */
?>
<p class="font-weight-bold" style="text-decoration: underline;">Invoice/Bill Parameters</p>
<form id="invoiceBillParamForm" name="invoiceBillParamForm" method="post"
      @if (isset($data['insertedData']))
      action="{{ route('ar-invoice-bill-parameter.edit',['id'=>$data['insertedData']->invoice_param_id]) }}">
    {{ method_field('PUT') }}
    @else
        action="{{route('ar-invoice-bill-parameter.insert')}}">
    @endif
    @csrf
    <fieldset class="border p-2">
        <legend class="w-auto font-weight-bold" style="font-size: 15px">Register Parameters</legend>
        <div class=" row">
            <label for="parameter_id" class="col-form-label col-md-2">Parameter ID</label>
            <input type="text" class="form-control form-control-sm col-md-2" id="parameter_id" tabindex="-1"
                   value="{{old('parameter_id', isset($data['insertedData']) ? $data['insertedData']->invoice_param_id : '' )}}"
                   readonly>
        </div>
        {{-- TODO: Add Disabled condition for all field to view mode. PAVEL: 06-04-22 --}}
        <div class=" row">
            <label class="col-form-label col-md-2 required" for="note">Parameter Note</label>
            <input type="text" class="form-control form-control-sm col-md-9" id="note" name="note" required maxlength="100" @if (isset($viewModeYN)) disabled @endif
                   value="{{ old('note', isset($data['insertedData']) ? $data['insertedData']->invoice_param_note : '' ) }}">
        </div>
        <p class="font-weight-bold" style="text-decoration: underline;">Key Parameter</p>
        <div class=" row">
            <label class="col-form-label col-md-2 required" for="party_sub_ledger">Party Sub-Ledger</label>
            <select required class="form-control form-control-sm col-md-9" id="party_sub_ledger" name="party_sub_ledger" @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['subsidiary_type'] as $type)
                    <option
                        value="{{$type->gl_subsidiary_id}}" {{ (old('party_sub_ledger', isset($data['insertedData']) ? $data['insertedData']->gl_subsidiary_id : '' ) == $type->gl_subsidiary_id) ? 'Selected' : '' }}>{{$type->gl_subsidiary_name}}</option>
                @endforeach
            </select>
        </div>
        <div class=" row">
            <label class="col-form-label col-md-2 required" for="transaction_type">Transaction Type</label>
            <select required class="form-control form-control-sm col-md-9" id="transaction_type" name="transaction_type" @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($data['transactionType'] as $type)
                    <option
                        value="{{$type->transaction_type_id}}" {{ (old('transaction_type', isset($data['insertedData']) ? $data['insertedData']->transaction_type_id : '' ) == $type->transaction_type_id) ? 'Selected' : '' }}>{{$type->transaction_type_name}}</option>
                @endforeach
            </select>
        </div>
        <div class=" row">
            <label class="col-form-label col-md-2 required" for="vat_account_id">VAT Account ID</label>
            <div class=" row col-md-6 pl-0 mr-1 mb-0 pb-0">
                <div class="input-group col-md-6">
                    <input name="vat_account_id" class="form-control form-control-sm " type="number" @if (isset($viewModeYN)) disabled @endif
                           value="{{old('vat_account_id',isset($data['insertedData']) ? $data['insertedData']->vat_gl_acc_id : '' ) }}"
                           id="vat_account_id"
                           maxlength="10"
                           oninput="maxLengthValid(this)"
                           onfocusout="addZerosInAccountId(this)"
                           onkeyup="resetField(['#vat_search_account_name'])">
                </div>
                <div class="col-md-5 pl-0">
                    <button class="btn btn-sm btn-primary vatSearchAccount" id="vat_search_account" type="button" @if (isset($viewModeYN)) disabled @endif
                            tabindex="-1"><i
                            class="bx bx-search font-size-small"></i>Search
                    </button>
                </div>
            </div>
        </div>
        <div class=" row">
            <label class="col-form-label col-md-2" for="vat_search_account_name"></label>
            <input type="text" class="form-control form-control-sm col-md-9" id="vat_search_account_name" name="vat_search_account_name"
                   value="{{ old('vat_search_account_name',(isset($data['insertedData']) && isset($data['insertedData']->vat_acc)) ? $data['insertedData']->vat_acc->gl_acc_name : '') }}"
                   readonly>
        </div>

    </fieldset>
    <div class="row mt-1">
        <div class="col-md-5">
            @if (!isset($viewModeYN))
                <button type="submit" class="btn btn-sm btn-success" id="customer_form"><i class="bx bx-save font-size-small"></i>
                    @if (isset($data['insertedData']))
                        Update
                    @else
                        Save
                    @endif
                </button>
            @endif
            @if (isset($data['insertedData']))
                <a href="{{ route('ar-invoice-bill-parameter.index') }}" class="btn btn-sm btn-dark">
                    <i class="bx bx-reset font-size-small"></i>Cancel
                </a>
            @else
                <button type="reset" class="btn btn-sm btn-dark">
                    <i class="bx bx-reset font-size-small"></i>Reset
                </button>
            @endif
            {{--<button type="submit" class="btn btn-success" id=""><i class="bx bx-save"></i>Save</button>
            <button type="reset" class="btn btn-dark">
                <i class="bx bx-reset"></i>Reset
            </button>--}}
        </div>
    </div>
</form>


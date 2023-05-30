<h4 ><u>Party-Sub Ledger Setup</u></h4>
@if(Session::has('message'))
    <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
         role="alert">
        {{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<form @if(isset($subsidiaryParam->gl_subsidiary_id)) action="{{route('party-sub-ledger-setup.update',[$subsidiaryParam->gl_subsidiary_id])}}" @else action="{{route('party-sub-ledger-setup.store')}}" @endif method="post">
    @csrf
    @if (isset($subsidiaryParam->gl_subsidiary_id))
        @method('PUT')
    @endif
    <fieldset class="border p-2 col-md-12 mt-2">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Party-Sub Ledger Parameters</legend>
        <div class=" row">
            <label class="col-md-3 col-form-label" for="party_sub_ledger_id">Party-Sub Ledger ID</label>
            <input class="form-control form-control-sm col-md-2" id="party_sub_ledger_id" name="party_sub_ledger_id" type="text" placeholder="Auto Generated" readonly
                   value="{{old('party_sub_ledger_id',(isset($subsidiaryParam->gl_subsidiary_id) ? $subsidiaryParam->gl_subsidiary_id : ''))}}" />
        </div>
        <div class=" row mb-0">
            <label class="col-md-3 col-form-label required" for="party_sub_ledger_name">Party-Sub Ledger Name</label>
            <div class="col-md-9  row">
                <input class="form-control form-control-sm col-md-7" id="party_sub_ledger_name" name="party_sub_ledger_name" type="text"  required @if (isset($viewModeYN)) disabled tabindex="-1" @endif
                       value="{{old('party_sub_ledger_name',(isset($subsidiaryParam->gl_subsidiary_name) ? $subsidiaryParam->gl_subsidiary_name : ''))}}" />
            </div>
        </div>
        <div class=" row">
            <label class="col-md-3 col-form-label required" for="party_sub_ledger_type">Party-Sub Ledger Type</label>
            <select class="form-control form-control-sm col-md-3" id="party_sub_ledger_type" name="party_sub_ledger_type" required @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($subLedgerType as $value)
                    <option value="{{$value->gl_subsidiary_type_id}}" data-gltype="{{$value->gl_type_id}}"
                        {{old('party_sub_ledger_type',isset($subsidiaryParam->gl_subsidiary_type_id) && $subsidiaryParam->gl_subsidiary_type_id == $value->gl_subsidiary_type_id ? 'selected' : '')}}>{{ $value->gl_subsidiary_type_name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class=" row">
            <label class="col-md-3 col-form-label required" for="sub_module_type">Sub Module Type</label>
            <select class="form-control form-control-sm col-md-3" id="sub_module_type" name="sub_module_type" required @if (isset($viewModeYN)) disabled @endif>
                <option value="">&lt;Select&gt;</option>
                @foreach($subModuleType as $value)
                    @if ( $value->module_id != \App\Enums\Common\LGlInteModules::FIN_ACC_GENE_LEDGER)
                        <option value="{{$value->module_id}}"
                            {{old('sub_module_type',isset($subsidiaryParam->module_id) && $subsidiaryParam->module_id == $value->module_id ? 'selected' : '')}}>{{ $value->module_name}}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class=" row">
            <div class="col-md-12 mb-1 mt-1">
                <h6 class="text-bold-600"><u>GL Integration</u></h6>
            </div>
            <label class="required col-md-3 col-form-label" for="account_id">Account ID</label>
            <div class=" row col-md-6 pl-0 mr-1">
                <div class="input-group col-md-5">
                    <input name="account_id" class="form-control form-control-sm"  type="number" id="account_id" maxlength="10" oninput="maxLengthValid(this)" @if (isset($viewModeYN)) disabled @endif
                       value="{{old('account_id',(isset($subsidiaryParam->gl_acc_id) ? $subsidiaryParam->gl_acc_id : ''))}}"
                       onfocusout="addZerosInAccountId(this)">
                </div>
                <div class="col-md-5">
                    <button class="btn btn-sm btn-primary searchAccount" id="searchAccount" type="button" @if (isset($viewModeYN)) disabled @endif
                            tabindex="-1"><i class="bx bx-search font-size-small"></i>Search
                    </button>
                </div>
            </div>
        </div>
        <div class=" row">
            <label for="account_name" class="col-md-3 col-form-label">Account Name</label>
            <input name="account_name" class="form-control form-control-sm col-md-5" id="account_name" tabindex="-1"  readonly
                   value="{{old('account_name',(isset($subsidiaryParam->coa_info->gl_acc_name) ? $subsidiaryParam->coa_info->gl_acc_name : ''))}}">
            @if (isset($viewModeYN))
                <a href="{{ route('party-sub-ledger-setup.index') }}" class="btn btn-sm btn-dark ml-1"><i class="bx bx-reset font-size-small"></i>Cancel</a>
            @else
                <div class="col-md-4">
                    <button class="btn btn-sm btn-success">
                        <i class="bx bx-save font-size-small"></i>{{ isset($subsidiaryParam->gl_subsidiary_id) ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" class="btn btn-sm btn-dark" id="reset_all"><i class="bx bx-reset font-size-small"></i>Reset</button>
                </div>
            @endif
        </div>

    </fieldset>
</form>
<div class=" mt-1 mb-1">
    <fieldset class="border p-2">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Party-Sub Ledger List</legend>
        <table class="table table-bordered table-sm" id="revenue_account_table" {{--style="display: none"--}}>
            <thead class="thead-dark">
            <tr>
                <th width="50%">Sub-Ledger Name</th>
                <th width="20%">Gl Account ID</th>
                <th width="30%">Action</th>
            </tr>
            </thead>
            <tbody>
            @if(count($subsidiaryParamsList) > 0)
                @foreach($subsidiaryParamsList as $value)
                    <tr>
                        <td>{{ $value->gl_subsidiary_name }}</td>
                        <td>{{ $value->gl_acc_id }}</td>
                        <td>
                            {{-- TODO: Add view button. PAVEL: 06-04-22 --}}
                            <a class="btn btn-sm btn-primary" href="{{route('party-sub-ledger-setup.edit',[$value->gl_subsidiary_id,'view'=> true])}}"><i class="bx bx-show font-size-small"></i>View</a>
                            <a class="btn btn-sm btn-info" href="{{route('party-sub-ledger-setup.edit',[$value->gl_subsidiary_id])}}"><i class="bx bx-edit font-size-small"></i>Edit</a>
                            <a class="btn btn-sm btn-danger" href="{{route('party-sub-ledger-setup.delete',[$value->gl_subsidiary_id])}}"><i class="bx bx-trash font-size-small"></i>Delete</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="3" class="text-center"> No Data Found</th>
                </tr>
            @endif
            </tbody>
        </table>
    </fieldset>
</div>


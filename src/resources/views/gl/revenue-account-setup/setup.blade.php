<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ১:১২ PM
 */
?>
<form method="post" action="#">
    {{ isset($data['insertedData']->revenue_acc_type_id) ? method_field('PUT') : '' }}
    @csrf
    <fieldset class="border p-2 col-md-12">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Revenue Account Setup</legend>
        <div class="form-group row">
            <label class="col-md-2 col-form-label required" for="account_type">Account Type</label>
            <select
                class="form-control form-control-sm col-md-4 @isset($data['insertedData']->revenue_acc_type_id) make-readonly-bg @endisset "
                id="account_type" name="account_type" required>
                <option value="">&lt;Select&gt;</option>
                @foreach($accountTypes as $type)
                    <option
                        {{ ($type->revenue_acc_type_id == old('account_type',(isset($data['insertedData']->revenue_acc_type_id) ? $data['insertedData']->revenue_acc_type_id : ''))) ? 'selected' : '' }} data-gltype="{{$type->gl_type_id}}"
                        value="{{ $type->revenue_acc_type_id }}">{{ $type->revenue_acc_type_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label required" for="account_id">Account ID</label>
            <input class="form-control form-control-sm col-md-2" id="account_id" name="account_id" type="number" required maxlength="10" oninput="maxLengthValid(this)"
                   value="{{old('account_id',(isset($data['insertedData']->gl_acc_id) ? $data['insertedData']->gl_acc_id : ''))}}"
                   onfocusout="addZerosInAccountId(this)"/>
            <div class=" col-md-2 d-flex justify-content-end pr-0">
                <button class="btn btn-sm btn-primary" id="searchAccount" type="button"><i class="bx bx-search font-size-small"></i>Search
                </button>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label required" for="account_name">Account Name</label>
            <div class="col-md-10 form-group row">
                <input class="form-control form-control-sm col-md-7"
                       id="account_name" name="account_name" type="text"
                       value="{{old('account_name',(isset($data['insertedData']->gl_acc->gl_acc_name) ? $data['insertedData']->gl_acc->gl_acc_name : ''))}}"
                       readonly tabindex="-1" required/>
                <div class="col-md-5">
                    <button class="btn btn-sm btn-success"><i
                            class="bx bx-save font-size-small"></i>{{ isset($data['insertedData']) ? 'Update' : 'Save' }}</button>

                    @isset($data['insertedData']->revenue_acc_type_id)
                        <a type="reset" href="{{route('revenue-account-setup.index')}}" class="btn btn-sm btn-dark"><i
                                class="bx bx-undo font-size-small"></i>Cancel
                        </a>
                    @else
                        <button type="button" onclick="resetAll()" class="btn btn-sm btn-dark"><i
                                class="bx bx-reset font-size-small"></i>Reset
                        </button>
                    @endisset
                </div>
            </div>
        </div>

    </fieldset>
</form>
<div class=" mt-1 mb-1">
    <fieldset class="border p-2">
        <legend class="w-auto" style="font-size: 12px; font-weight: bold">Revenue Account List</legend>
        <table class="table table-bordered table-sm" id="revenue_account_table" {{--style="display: none"--}}>
            <thead class="thead-dark">
            <tr>
                <th width="23%">Account Type</th>
                <th width="5%">Account ID</th>
                <th width="35%">Account Name</th>
                <th width="16%">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($revenueAcc as $revenue)
                <tr>
                    <td>{{ isset($revenue->revenue_type) ? $revenue->revenue_type->revenue_acc_type_name : '' }}</td>
                    <td>{{ $revenue->gl_acc_id }}</td>
                    <td>{{ $revenue->gl_acc->gl_acc_name }}</td>
                    <td>
                        <div class="row mx-0 px-0">
                            <div class="col-md-12 mx-0 px-0">
                                <a class="btn btn-sm btn-info"
                                   href="{{route('revenue-account-setup.edit',['id'=>$revenue->revenue_acc_type_id])}}"><i
                                        class="bx bx-edit font-size-small"></i>Edit</a>
                                @isset($data['insertedData']->revenue_acc_type_id)
                                    @if ($data['insertedData']->revenue_acc_type_id != $revenue->revenue_acc_type_id)
                                        <form style="display: inline" class="isConfirmOnSubmit"
                                              action="{{route('revenue-account-setup.delete',['id'=>$revenue->revenue_acc_type_id])}}"
                                              method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-sm btn-danger" type="submit"><i
                                                    class="bx bx-trash"></i>Remove
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <form style="display: inline" class="isConfirmOnSubmit"
                                          action="{{route('revenue-account-setup.delete',['id'=>$revenue->revenue_acc_type_id])}}"
                                          method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm btn-danger" type="submit"><i class="bx bx-trash font-size-small"></i>Remove
                                        </button>
                                    </form>
                                @endisset
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No Data Found</td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </fieldset>
</div>


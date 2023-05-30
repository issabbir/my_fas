@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-3">Bank Branch Setup</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form @if(isset($bankBranchInfo->branch_code)) action="{{route('bank-branch-setup.update',[$bankBranchInfo->branch_code])}}"
                @else action="{{route('bank-branch-setup.store')}}" @endif method="post">
                @csrf
                @if (isset($bankBranchInfo->branch_code))
                    @method('PUT')
                @endif
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Bank Branch Information</legend>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3"><label for="branch_id" class="">Branch ID </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="branch_id" class="form-control" name="branch_id" placeholder="Auto Generate" disabled
                                       value="{{old('branch_id',isset($bankBranchInfo->branch_code) ? $bankBranchInfo->branch_code : '')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="branch_name" class="required">Branch Name </label></div>
                            <div class="col-md-9 form-group pl-0">
                                <input type="text" id="branch_name" class="form-control" name="branch_name" placeholder=""
                                       value="{{old('branch_name',isset($bankBranchInfo->branch_name) ? $bankBranchInfo->branch_name : '')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="bank_id" class="required">Bank</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="bank_id" required id="bank_id"
                                        data-cm-bank-id="{{old('bank_id',isset($bankBranchInfo->bank_code) ? $bankBranchInfo->bank_code : '')}}">
                                    <option value="" >&lt;Select&gt;</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="bank_district_id" class="required">Bank-District</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="bank_district_id" required id="bank_district_id"
                                        data-cm-bank-dist-id="{{old('bank_district_id',isset($bankBranchInfo->district_code) ? $bankBranchInfo->district_code : '')}}">>
                                    <option value="" >&lt;Select&gt;</option>
                                </select>
                            </div>
                        </div>
                        {{--<div class="row">
                            <div class="col-md-3"><label for="branch_sl_code" class="required">Branch Code </label></div>
                            <div class="col-md-2 form-group pl-0">
                                <input type="text" id="branch_sl_code" class="form-control" name="branch_sl_code" placeholder=""
                                       value="{{old('branch_sl_code',isset($bankBranchInfo->branch_sl) ? $bankBranchInfo->branch_sl : '')}}"/>
                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-md-3"><label for="routing_number" class="required">Routing Number </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="routing_number" class="form-control" name="routing_number" placeholder=""
                                       value="{{old('routing_number',isset($bankBranchInfo->routing_no) ? $bankBranchInfo->routing_no : '')}}"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span class="align-middle ml-25">{{ (isset($chequeBooksInfo->chq_book_id) ? 'Update' : 'Save') }}</span></button>
                        <button type="reset" class="btn btn-dark"><i class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span></button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @include('cm.bank-branch-setup.list')

@endsection

@section('footer-script')
    <script type="text/javascript">

        function bankBranchList() {
            $('#bank-branch-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/bank-branch-setup-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'cm_bank_info.bank_name', name: 'cm_bank_info.bank_name'},
                    {data: 'cm_bank_district.district_name', name: 'cm_bank_district.district_name'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }


        $(document).ready(function () {
            bankBranchList();
            selectCmBankInfo('#bank_id', APP_URL + '/cash-management/ajax/cm-banks', APP_URL+'/cash-management/ajax/cm-bank/', '');
            selectCmBankDistrict('#bank_district_id', APP_URL + '/cash-management/ajax/cm-bank-districts', APP_URL+'/cash-management/ajax/cm-bank-district/', '');
        });

    </script>
@endsection

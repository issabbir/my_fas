@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-3">Bank Setup</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form @if(isset($cmBankInfo->bank_code)) action="{{route('bank-setup.update',[$cmBankInfo->bank_code])}}" @else action="{{route('bank-setup.store')}}" @endif method="post">
                @csrf
                @if (isset($cmBankInfo->bank_code))
                    @method('PUT')
                @endif
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Bank Information</legend>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-2"><label for="bank_code">ID</label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="bank_code" class="form-control" name="bank_code" placeholder="" required
                                       value="{{old('bank_code',(isset($cmBankInfo->bank_code) ? $cmBankInfo->bank_code : ''))}}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"><label class="required" for="bank_name">Name</label></div>
                            <div class="col-md-8 form-group pl-0">
                                <input type="text" id="bank_name" class="form-control" name="bank_name" placeholder="" required
                                       value="{{old('bank_name',(isset($cmBankInfo->bank_name) ? $cmBankInfo->bank_name : ''))}}"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span class="align-middle ml-25">{{ (isset($cmBankInfo->bank_code) ? 'Update' : 'Save') }}</span></button>
                        <button type="reset" class="btn btn-dark"><i class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span></button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @include('cm.bank-setup.list')

@endsection

@section('footer-script')
    <script type="text/javascript">

        function bankSetupList() {
            $('#bank-setup-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/bank-setup-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }

        $(document).ready(function () {
            bankSetupList();
        });

    </script>
@endsection

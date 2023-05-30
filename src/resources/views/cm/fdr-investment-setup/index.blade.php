@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-3">FDR Investment User Setup</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form @if(isset($fdrInvSetupInfo->investment_user_map_id)) action="{{route('fdr-investment-setup.update',[$fdrInvSetupInfo->investment_user_map_id])}}"
                @else action="{{route('fdr-investment-setup.store')}}" @endif method="post">
                @csrf
                @if (isset($fdrInvSetupInfo->investment_user_map_id))
                    @method('PUT')
                @endif
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Investment User Mapping</legend>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3"><label for="inv_type_id" class="required">Investment Type</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="inv_type_id" required id="inv_type_id">
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($invTypeList as $value)
                                        <option value="{{$value->investment_type_id}}"
                                            {{old('inv_type_id',isset($fdrInvSetupInfo->investment_type_id) && $fdrInvSetupInfo->investment_type_id == $value->investment_type_id ? 'selected' : '')}} >{{$value->investment_type_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="inv_user_id" class="required">Investment User</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="inv_user_id" required id="inv_user_id">
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($invUserList as $value)
                                        <option value="{{$value->user_id}}"
                                            {{old('inv_user_id',isset($fdrInvSetupInfo->investment_user_id) && $fdrInvSetupInfo->investment_user_id == $value->user_id ? 'selected' : '')}} >{{$value->emp_name}} ({{$value->user_name}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span class="align-middle ml-25">{{ (isset($fdrInvSetupInfo->investment_user_map_id) ? 'Update' : 'Save') }}</span></button>
                        <button type="reset" class="btn btn-dark"><i class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span></button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @include('cm.fdr-investment-setup.list')

@endsection

@section('footer-script')
    <script type="text/javascript">

        function invUserMapList() {
            $('#inv-user-map-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/fdr-investment-setup-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'investment_type_name', name: 'investment_type_name'},
                    {data: 'investment_user_name', name: 'investment_user_name'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }


        $(document).ready(function () {
            invUserMapList();
        });

    </script>
@endsection

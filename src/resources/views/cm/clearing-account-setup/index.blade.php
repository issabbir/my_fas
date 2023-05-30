@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-3">Clearing Account Setup</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form @if(isset($clgAccInfo->bank_account_id)) action="{{route('clearing-account-setup.update',[$clgAccInfo->bank_account_id])}}"
                @else action="{{route('clearing-account-setup.store')}}" @endif method="post">
                @csrf
                @if (isset($clgAccInfo->bank_account_id))
                    @method('PUT')
                @endif
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Cheque Clearing Account</legend>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3"><label for="bank_acc_id" class="required">Bank Account</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="bank_acc_id" id="bank_acc_id" @if (isset($clgAccInfo->bank_account_id)) disabled @else required @endif>
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($bankAccList as $value)
                                        <option value="{{$value->gl_acc_id}}"
                                            {{old('bank_acc_id',isset($clgAccInfo->bank_account_id) && $clgAccInfo->bank_account_id == $value->gl_acc_id ? 'selected' : '')}} >{{$value->gl_acc_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="selected_clearing_outward_gl_id" class="required">Clearing Outward Gl ID </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input maxlength="10" type="number" id="selected_clearing_outward_gl_id" class="form-control" name="clearing_outward_gl_id"
                                       onfocusout="addZerosInAccountId(this)"  placeholder="" required
                                      {{-- onkeyup="resetInputData()"--}}
                                       oninput="maxLengthValid(this)"
                                       value="{{old('clearing_outward_gl_id',isset($clgAccInfo->clearing_outward_acc_id) ? $clgAccInfo->clearing_outward_acc_id : '')}}" />
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-info btn-block mb-1 search_clearing_id"  data-gl-type="{{\App\Enums\Common\GlCoaParams::LIABILITY}}">
                                    <i class="bx bx-search"></i><span class="align-middle ml-25">Search</span></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 form-group pl-0 offset-3">
                                <input maxlength="100" type="text" id="selected_clearing_outward_gl_name" class="form-control" name="clearing_outward_gl_name" placeholder="" disabled
                                       value="{{old('clearing_outward_gl_name',isset($clgAccInfo->clg_outward->gl_acc_name) ? $clgAccInfo->clg_outward->gl_acc_name : '')}}"/>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-3"><label class="" for="selected_clearing_inward_gl_id">Clearing Inward Gl ID </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input maxlength="10" type="number" id="selected_clearing_inward_gl_id" class="form-control" name="clearing_inward_gl_id"
                                       onfocusout="addZerosInAccountId(this)"  placeholder=""
                                       {{--onkeyup="resetInputData()"--}}
                                       oninput="maxLengthValid(this)"
                                       value="{{old('clearing_inward_gl_id',isset($clgAccInfo->clearing_inward_acc_id) ? $clgAccInfo->clearing_inward_acc_id : '')}}" />
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-info btn-block mb-1 search_clearing_id"  data-gl-type="{{\App\Enums\Common\GlCoaParams::ASSET}}">
                                    <i class="bx bx-search"></i><span class="align-middle ml-25">Search</span></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-3 col-md-9 form-group pl-0">
                                <input type="text" id="selected_clearing_inward_gl_name" class="form-control" name="clearing_inward_gl_name" placeholder="" disabled
                                   value="{{old('clearing_inward_gl_name',(isset($clgAccInfo->clg_inward->gl_acc_name) ? $clgAccInfo->clg_inward->gl_acc_name : ''))}}"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success mr-1"><i class="bx bx-save"></i><span class="align-middle ml-25">{{ (isset($clgAccInfo->bank_account_id) ? 'Update' : 'Save') }}</span></button>
                        @if (isset($clgAccInfo->bank_account_id))
                            <a href="{{route('clearing-account-setup.index')}}" class="btn btn-dark"><i class="bx bx-reset"></i><span class="align-middle ml-25">Cancel</span></a>
                        @else
                            <button type="reset" class="btn btn-dark" onclick="resetField(['#bank_acc_id'])"><i class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span></button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('cm.clearing-account-setup.list')

    <!-- Account TypeWise Coa Modal start -->
    <section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="coaCodeModal" tabindex="-1" role="dialog"
                         aria-labelledby="coaCodeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="coaCodeModalLabel">Coa Code Information</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table id="gl-type-wise-coa-list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Account Name</th>
                                                    <th>Account Id</th>
                                                    <th>Account Code</th>
                                                    <th>Currency</th>
                                                    <th>Parent Id</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Account TypeWise Coa Modal end -->

@endsection

@section('footer-script')
    <script type="text/javascript">

        function clearingAccList() {
            $('#clearing-acc-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/clearing-account-setup-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'coa_info.gl_acc_name', name: 'coa_info.gl_acc_name'},
                    {data: 'clearing_outward_acc_id', name: 'clearing_outward_acc_id'},
                    {data: 'clearing_inward_acc_id', name: 'clearing_inward_acc_id'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }

        function searchClearing(){
            $(".search_clearing_id").on("click", function () {
                //e.preventDefault();
                let glTypeId = $(this).data('gl-type');
                let glAccId = $(this).parent().parent().children().children('input[type=number]').val();

                if(!nullEmptyUndefinedChecked(glAccId)){
                    getGlAccountDetail(glTypeId, glAccId, this);
                }else{
                    glTypeWiseCoaList(glTypeId);
                    $("#coaCodeModal").modal('show');
                }
            });
        }

        function getGlAccountDetail(glTypeId, glAccId, selector){

            $.ajax({
                type: 'GET',
                /*'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },*/
                url: APP_URL + '/cash-management/ajax/gl-type-acc-wise-coa',
                data: {gl_type_id: glTypeId, gl_acc_id: glAccId},
                success: function (data) {
                    if ($.isEmptyObject(data)) {
                        $(selector).parent().parent().children().children('input[type=number]').notify("Account id not found", "error");
                    }else{
                        $(selector).parent().parent().next('div').children().children('input[type=text]').val(data.gl_acc_name);
                        $("#coaCodeModal").modal('hide');
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function glTypeWiseCoaList(param){
            //alert(param);
            let oTable = $('#gl-type-wise-coa-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy : true,
                pageLength: 20,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/cash-management/ajax/gl-type-wise-coa-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.gl_type_id = param;
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "gl_acc_name"},
                    {"data": "gl_acc_id"},
                    {"data": "gl_acc_code"},
                    {"data": "currency_code"},
                    {"data": "gl_parent_id"},
                    {"data": "select"}
                ],

                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        }

        $(document).on("click",'.gl-coa', function (e) {
            //e.preventDefault();
            addSelectedCoa(this);
        });

        function addSelectedCoa(selector) {
            let glTypeId = $(selector).data('gl-type');

            if (glTypeId == {{\App\Enums\Common\GlCoaParams::LIABILITY}}){
                $('#selected_clearing_outward_gl_name').val($(selector).closest("tr").find("td:eq(1)").text());
                $('#selected_clearing_outward_gl_id').val($(selector).closest("tr").find("td:eq(2)").text());
                $("#coaCodeModal").modal('hide');
            } else {
                $('#selected_clearing_inward_gl_name').val($(selector).closest("tr").find("td:eq(1)").text());
                $('#selected_clearing_inward_gl_id').val($(selector).closest("tr").find("td:eq(2)").text());
                $("#coaCodeModal").modal('hide');
            }
        }

        function resetInputData() {
            $("#selected_clearing_outward_gl_id").on("keyup", function () {
                $("#selected_clearing_outward_gl_name").val('');
            })

            $("#selected_clearing_inward_gl_id").on("keyup", function () {
                $("#selected_clearing_inward_gl_name").val('');
            })
        }

        $(document).ready(function () {
            clearingAccList();
            searchClearing();
            resetInputData();
        });

    </script>
@endsection

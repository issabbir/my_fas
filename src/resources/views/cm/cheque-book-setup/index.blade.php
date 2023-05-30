@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-3">Cheque Book Setup</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form @if(isset($chequeBooksInfo->chq_book_id)) action="{{route('cheque-book-setup.update',[$chequeBooksInfo->chq_book_id])}}"
                @else action="{{route('cheque-book-setup.store')}}" @endif method="post">
                @csrf
                @if (isset($chequeBooksInfo->chq_book_id))
                    @method('PUT')
                @endif
                <fieldset class="border p-2 mt-2">
                    <legend class="w-auto text-bold-600" style="font-size: 14px;">Cheque Book Information</legend>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3"><label for="bank_acc_id" class="required">Bank Account</label></div>
                            <div class="col-md-9 form-group pl-0">
                                <select class="custom-select form-control select2" name="bank_acc_id" required id="bank_acc_id">
                                    <option value="" >&lt;Select&gt;</option>
                                    @foreach($bankAccList as $value)
                                        <option value="{{$value->gl_acc_id}}"
                                            {{old('bank_acc_id',isset($chequeBooksInfo->bank_gl_acc_id) && $chequeBooksInfo->bank_gl_acc_id == $value->gl_acc_id ? 'selected' : '')}} >{{$value->gl_acc_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="cheque_prefix" class="required">Cheque Prefix </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="cheque_prefix" class="form-control" name="cheque_prefix" placeholder=""
                                       value="{{old('cheque_prefix',isset($chequeBooksInfo->chq_prefix) ? $chequeBooksInfo->chq_prefix : '')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="beginning_number" class="required">Beginning Number </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="beginning_number" class="form-control" name="beginning_number" placeholder=""
                                       value="{{old('beginning_number',isset($chequeBooksInfo->chq_leaf_beg_no) ? $chequeBooksInfo->chq_leaf_beg_no : '')}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><label for="ending_number" class="required">Ending Number </label></div>
                            <div class="col-md-4 form-group pl-0">
                                <input type="text" id="ending_number" class="form-control" name="ending_number" placeholder=""
                                       value="{{old('ending_number',isset($chequeBooksInfo->chq_leaf_end_no) ? $chequeBooksInfo->chq_leaf_end_no : '')}}"/>
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


    <div class="card">
        <div class="card-header pb-0"><h4 class="card-title mb-0">Cheque Book List</h4> <hr></div>
        <div class="card-body">

            @include('cm.cheque-book-setup.list')
            @include('cm.cheque-book-setup.leaf_list')

        </div>
    </div>




@endsection

@section('footer-script')
    <script type="text/javascript">

        function chequeBookList() {
            $('#cheque-book-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/cheque-book-setup-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'coa_info.gl_acc_name', name: 'coa_info.gl_acc_name'},
                    {data: 'chq_prefix', name: 'chq_prefix'},
                    {data: 'chq_leaf_beg_no', name: 'chq_leaf_beg_no'},
                    {data: 'chq_leaf_end_no', name: 'chq_leaf_end_no'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }

        $(document).on("click", '.leaf-list', function (e) {
            e.preventDefault();

            let chq_book_id = $(this).attr('id');
            let chequeBookData = $(this).data('cheque-book-data');
            let chequeBookDataArray = chequeBookData.split('##');
            let gl_acc_id = chequeBookDataArray[0];

            $('#v_bank_acc_id').val(chequeBookDataArray[1]);
            $('#v_cheque_prefix').val(chequeBookDataArray[2]);
            $('#v_beginning_number').val(chequeBookDataArray[3]);
            $('#v_ending_number').val(chequeBookDataArray[4]);

            searchAccWiseChequeLeaf(chq_book_id,gl_acc_id);
            $('.cheque-leaf-sec').show();
            $('html, body').animate({scrollTop: $(".cheque-leaf-sec").offset().top}, 2000);
        });


        function searchAccWiseChequeLeaf(chq_book_id,gl_acc_id) {

            $('#acc-wise-cheque-leaf-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/cash-management/cheque-book-setup-leaf-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.chq_book_id = chq_book_id;
                        params.gl_acc_id = gl_acc_id;
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "chq_leaf_no"},
                    {"data": "cheque_leaf_status"}
                ]
            });
        }



        $(document).ready(function () {
            chequeBookList();
        });

    </script>
@endsection

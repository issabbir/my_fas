@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <style>
        #tree {
            list-style-type:none;
            color: #666666;
        }
        #tree [data-tree-click] {
            cursor: pointer;
            color: #999999;
            font-weight: bold;
            font-size: 1.2em;
        }
        #tree .closed [data-tree-click]{
            padding-left: 12px;
            background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
            background-repeat: no-repeat;
            background-position: 0px 6px;
            color: #FF00000 !important;
        }
        #tree .open [data-tree-click]{
            padding-left: 12px;
            background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
            background-repeat: no-repeat;
            background-position: 0px -94px;
            color: #FF00000 !important;
        }
        #tree .end {
            padding-left: 12px;
            color: #BBBBBB;
        }
        #tree .data-tree-level1 {
            margin-left: 10px;
        }
        #tree .data-tree-level2 {
            margin-left: 20px;
        }
        #tree .data-tree-level3 {
            margin-left: 30px;
        }

        /*#football {
            color: #333333;
        }
        #football .open2 {
            text-transform: uppercase;
            font-weight: bold;
        }
        #football [data-tree-branch]{
            display: block;
        }
        #football .data-tree-level1 {
            margin-left: 20px;
        }
        #football .data-tree-level2 {
            margin-left: 40px;
        }
        #football .data-tree-level3 {
            margin-left: 60px;
        }
        #football .end2.data-tree-level0, #football .end2.data-tree-level1 {
            color: #CCCCCC;
        }
        #football .end2.data-tree-level2 {
            color: #CC0000;
        }*/
        #container { margin: 150px auto; }

        .lirow{
            padding: 5px;
        }
        .lirow:hover{
            background:#8080802b;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    <div class="card-header d-flex justify-content-between align-items-center pb-0">
                        <h4 class="card-title">Chart Of Accounts (COA)</h4>
                        <a href="{{route('coa.coa-setup-index')}}" class="add-btn ml-1"><span class="font-middle-4"><i class="bx bx-plus-circle bx-md font-small-5 align-middle" aria-hidden="true"></i></span></a>
                        <a href="{{route('coa.index')}}" class="back-btn ml-1" style="display:none"><span class="font-small-4"><i class="bx bx-log-out bx-md font-small-5 align-middle"></i></span></a>
                    </div>
                </div>
                <!-- Table Start -->
                <div class="card-body pt-0">
                    <hr>
                    @if(Session::has('message'))
                        <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form method="POST" id="coa-acc-code-name-search-form">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{--<label for="acc_name_code" class="required">Account Name Or Code</label>--}}
                                    <label for="acc_name_code" class="">&nbsp;</label>
                                    <input class="form-control" id="acc_name_code" name="acc_name_code"
                                           placeholder="Look For Account Name Or Code" required/>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary mb-2 "><i
                                            class="bx bx-search"></i><span class="align-middle ml-25">Search</span>
                                    </button>
                                    <button type="button" class="btn btn-info mb-2 " id="tree_btn"><i
                                            class="bx bx-show"></i><span class="align-middle ml-25">COA Tree</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary mb-2" id="reset"><i
                                            class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-1 acc-name-sec" style="display: none">
                        <fieldset class="border p-2 col-md-12">
                            <legend class="w-auto" style="font-size: 14px; "><strong>Chart Of Accounts Tree</strong></legend>
                                <div class="col-md-12">
                                    {{--<h6 class="mb-1"><strong>Account Name</strong></h6>--}}
                                        <ul id="tree" class="pl-0">
                                            @forelse($gl_chart_list as $option)
                                                <li data-tree-branch="{{ $option->node_path }}" class="text-dark lirow">
                                                    <span data-tree-click="{{ $option->node_path }}" class="text-primary">
                                                        <small>&nbsp;{{ $option->gl_acc_name }} </small>
                                                    </span>
                                                    {{--@foreach($glTransDtl as $value)
                                                        @if ($value->count_tran > 0)  Ache @else  nai @endif
                                                    @endforeach--}}
                                                    <a target="_blank" href="{{route('coa.coa-setup-edit', [$option->gl_acc_id])}}" style="float: right"><i class="bx bx-edit cursor-pointer"></i></a>
                                                    <a target="_blank" href="{{route('coa.coa-setup-view', [$option->gl_acc_id])}}" style="float: right"><i class="bx bx-show cursor-pointer"></i></a>
                                                    {{--<a href="{{route('coa.coa-setup-edit', [$option->gl_acc_id])}}" style="float: right"><i class="bx bx-edit cursor-pointer pr-1"></i></a>--}}
                                                </li>
                                            @empty
                                                <span>Nothing found</span>
                                            @endforelse
                                        </ul>
                                </div>
                        </fieldset>
                    </div>

                    @include('gl.chart-of-account.search_list')

                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        $('#tree').dataTree();

        var oTable = $('#acc-name-code-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 20,
            bFilter: true,
            ordering: false,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + '/general-ledger/coa-acc-name-code-search-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.acc_name_code = $('#acc_name_code').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id"},
                {"data": "gl_acc_code"},
                {"data": "gl_acc_name"},
                {"data": "acc_type.gl_type_name"},
                {"data": "action"},
            ],

            language: {
                paginate: {
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">'
                }
            }
        });

        $(document).ready(function () {
            $('#coa-acc-code-name-search-form').on('submit', function (e) {
                e.preventDefault();
                $('.coa-list-sec').show();
                $('.acc-name-sec').hide();
                /**
                 * COA SEARCH (problem: No back button needed). REF# email
                 * Logic commented:04-04-2022
                 * **/
                //$('.add-btn').hide();
                //$('.back-btn').show();
                oTable.draw();
            });

            $('#reset').on('click', function () {
                $('.coa-list-sec').hide();
                $('.acc-name-sec').hide();
                /**
                 * COA SEARCH (problem: No back button needed). REF# email
                 * Logic commented:04-04-2022
                 * **/
                //$('.add-btn').show()
                //$('.back-btn').hide();
                $('#acc_name_code').val('');
                oTable.draw();

            })

            $('#tree_btn').on('click', function () {
                $('.acc-name-sec').show();
                $('.coa-list-sec').hide();
                $('#acc_name_code').val('');
                oTable.draw();
            })

        });


    </script>
@endsection

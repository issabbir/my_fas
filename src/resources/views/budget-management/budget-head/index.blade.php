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

        #container { margin: 150px auto; }

        .lirow{
            padding: 5px;
        }
        .lirow:hover{
            background:#8080802b;
        }

        .form-group {
            margin-bottom: 5px;
        }

        .text-right-align {
            text-align: right;
        }

        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    <div class="card-header d-flex justify-content-between align-items-center pb-0">
                        <h4 class="card-title ml-1">Budget Head Search/Edit</h4>
{{--                        <a href="{{route('budget-head.budget-head-setup-index')}}" class="add-btn ml-1" data-toggle="tooltip" data-placement="top" title="Budget Head Setup"><span class="font-middle-4"><i class="bx bx-plus-circle bx-md font-small-5 align-middle" aria-hidden="true"></i></span></a>--}}
                        <a href="{{route('budget-head.budget-head-index')}}" class="back-btn ml-1" style="display:none"><span class="font-small-4"><i class="bx bx-log-out bx-md font-small-5 align-middle"></i></span></a>
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
                    <form method="POST" id="budget-code-name-search-form">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="budget_name_code" class="">&nbsp;</label>
                                    <input class="form-control" id="budget_name_code" name="budget_name_code"
                                           placeholder="Look For Budget Head Name Or Code" required/>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary mb-2 "><i
                                            class="bx bx-search"></i><span class="align-middle ml-25">Search</span>
                                    </button>
                                    <button type="button" class="btn btn-info mb-2 " id="tree_btn"><i
                                            class="bx bx-show"></i><span class="align-middle ml-25">Tree View</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary mb-2" id="reset"><i
                                            class="bx bx-reset"></i><span class="align-middle ml-25">Reset</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-1 budget-name-sec" style="display: none">
                        <fieldset class="border p-2 col-md-12">
                            <legend class="w-auto" style="font-size: 14px; "><strong>Budget Head Tree View</strong></legend>
                            <div class="col-md-12">
                                <ul id="tree" class="pl-0">
                                    @forelse($budget_list as $option)
                                        <li data-tree-branch="{{ $option->node_path }}" class="text-dark lirow">
                                                    <span data-tree-click="{{ $option->node_path }}" class="text-primary">
                                                        <small>&nbsp;{{ $option->budget_head_name }} </small>
                                                    </span>
                                            <a target="_blank" href="{{route('head-edit.headEdit', [$option->budget_head_id])}}" style="float: right"><i class="bx bx-edit cursor-pointer"></i></a>
                                            <a target="_blank" href="{{route('head-edit.headView', [$option->budget_head_id]) }}" style="float: right"><i class="bx bx-show cursor-pointer"></i></a>
                                        </li>
                                    @empty
                                        <span>Nothing found</span>
                                    @endforelse
                                </ul>
                            </div>
                        </fieldset>
                    </div>

                    @include('budget-management.budget-head.head_search_list')

                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        $('#tree').dataTree();

        var oTable = $('#budget-head-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 20,
            bFilter: true,
            ordering: false,
            searching: false,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + '/budget-management/ajax/budget-head-edit-code-search-list',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.budget_name_code = $('#budget_name_code').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "budget_head_id"},
                {"data": "budget_head_name"},
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
            $('#budget-code-name-search-form').on('submit', function (e) {
                e.preventDefault();
                $('.budget-list-sec').show();
                $('.budget-name-sec').hide();
                /**
                 * COA SEARCH (problem: No back button needed). REF# email
                 * Logic commented:04-04-2022
                 * **/
                //$('.add-btn').hide();
                //$('.back-btn').show();
                oTable.draw();
            });

            $('#reset').on('click', function () {
                $('.budget-list-sec').hide();
                $('.budget-name-sec').hide();
                /**
                 * COA SEARCH (problem: No back button needed). REF# email
                 * Logic commented:04-04-2022
                 * **/
                //$('.add-btn').show()
                //$('.back-btn').hide();
                $('#budget_name_code').val('');
                oTable.draw();

            })

            $('#tree_btn').on('click', function () {
                $('.budget-name-sec').show();
                $('.budget-list-sec').hide();
                $('#budget_name_code').val('');
                oTable.draw();
            })

        });
    </script>
@endsection

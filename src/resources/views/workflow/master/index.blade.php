<?php
/**
 *Created by Sujon Chondro Shil
 *Created at ৩/৩/২১ ১১:১৬ AM
 */
?>
@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')

    <div class="card">
        <div class="card-header"><h5>Master Setup</h5></div>
        <div class="card-body border">
            <div class="row">
                <div class="col-12">
                    <form id="masterForm">
                        <div class="form-row">
                            <div class="form-group col-5">
                                <label class="col-form-label">Workflow Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group col-4 ml-1">
                                <label class="col-form-label">Status</label>
                                <div class="form-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="active_yn"
                                           id="active"
                                           value="Y"

                                    >
                                    <label class="form-check-label" for="active">
                                        Active
                                    </label>&nbsp;
                                    <input class="form-check-input"
                                           type="radio"
                                           name="active_yn"
                                           id="inactive"
                                           value="I">
                                    <label class="form-check-label" for="inactive">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="d-flex">
                                    <button class="justify-content-end mt-2 btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Master List</h5></div>
        <div class="card-body border">
            <div class="row">
                <div class="col-12">
                    <table id="workflowMasterTable" class="table table-sm dataTable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Workflow Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
                $('#workflowMasterTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    pageLength: 5,
                    bFilter: true,
                    ordering: false,
                    ajax: {
                        url: {{ route('workflow.master-datatable') }},
                        data: function (d) {
                        },
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'workflow_name', name: 'workflow_name'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'}
                    ],
                });

            //invoiceList();
        });
    </script>
@endsection

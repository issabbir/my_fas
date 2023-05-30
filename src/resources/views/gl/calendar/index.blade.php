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
        <div class="card-body border">
            <div class="row">
                <div class="d-flex justify-content-between">
                    <h4 style="text-decoration: underline">Calender Setup</h4>
                    <a href="{{route("calendar.setup")}}" class="ml-2"><i class="bx bx-plus-circle bx-md" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="calenderList" class="table table-sm dataTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Fiscal Period</th>
                                <th>Fiscal Year</th>
                                <th>Posting Period</th>
                                <th>Calendar Status</th>
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
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            calenderList();
            function calenderList() {
                var calenderTable = $('#calenderList').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    pageLength: 5,
                    bFilter: true,
                    ordering: false,
                    ajax: {
                        url: APP_URL + '/general-ledger/calendar-datatable-list',
                        data: function (d) {
                        },
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fiscal_period', name: 'fiscal_period'},
                        {data: 'fiscal_year', name: 'fiscal_year'},
                        {data: 'posting_period', name: 'posting_period'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'}
                    ],
                });
            }
            //invoiceList();
        });
    </script>
@endsection

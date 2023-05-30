<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৯ PM
 */
?>
@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    @include('ar.customer-profile-authorize.form')
@endsection

@section('footer-script')
    <script type="text/javascript">
        $("#ap_customer_search_form").on('submit', function (e) {
            e.preventDefault();
            $('#customerSearch').DataTable().draw();
        });

        function paramWiseSearchList() {
            $(".search-param").on('change', function () {
                customerTable.draw();
            });
            let customerTable = $('#customerSearch').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/account-receivable/customer-profile-authorize-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        // Retrieve dynamic parameters

                            params.approvalStatus= $('#approval_status :selected').val();
                            params.actionType= $('#action_type :selected').val();


                    }
                },
                "columns": [
                    {"data": "customer_auth_log_id"},
                    {"data": "name"},
                    {"data": "short_name"},
                    {"data": "address"},
                    {"data": "action_type"},
                    {"data": "status"},
                    {"data": "action", "orderable": false}
                ],
            });
        }
        $(document).ready(function () {
            paramWiseSearchList();

        });
    </script>
@endsection


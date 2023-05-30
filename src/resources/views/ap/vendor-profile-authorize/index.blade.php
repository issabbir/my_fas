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
    @include('ap.vendor-profile-authorize.form')
@endsection

@section('footer-script')
    <script type="text/javascript">
        $("#ap_vendor_search_form").on('submit', function (e) {
            e.preventDefault();
            $('#vendorSearch').DataTable().draw();
        });

        function paramWiseSearchList() {
            $(".search-param").on('change', function () {
                vendorTable.draw();
            });
            let vendorTable = $('#vendorSearch').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/account-payable/vendor-profile-authorize-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        // Retrieve dynamic parameters
                        var dt_params = {
                            approvalStatus: $('#approval_status :selected').val(),
                            actionType: $('#action_type').val(),
                        };
                        // Add dynamic parameters to the data object sent to the server
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }
                    }
                },
                "columns": [
                    {"data": "vendor_id"},
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


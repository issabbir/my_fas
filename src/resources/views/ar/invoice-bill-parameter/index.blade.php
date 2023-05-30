<?php
/**
 *Created by PhpStorm
 *Created at ৫/৯/২১ ৪:১৫ PM
 */
?>
@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('ar.invoice-bill-parameter.form')
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-sm dataTable" id="invoice_bill_parameters">
                        <thead class="thead-dark">
                        <tr>
                            {{--<th>SL</th>--}}
                            <th>ID</th>
                            <th>Parameter Note</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="invoice_bill_parameters_tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('gl.common_coalist_modal')

@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {

        });

        $("#vat_search_account").on("click", function () {
            let accId = $(this).parent().parent().children().children('input[type=number]').val();
            let glType = '2';
            let parentId = "#" + $(this).attr('id'); //Taking parent search button id
            if (!nullEmptyUndefinedChecked(glType)) {
                if (!nullEmptyUndefinedChecked(accId)) {
                    getAccountDetail(glType, accId, this);
                } else {
                    if ($("#acc_search_form").find("#parentId").length != 0){
                        $("#acc_search_form").find("#parentId").remove();
                    }
                    $("#acc_search_form").append('<input type="hidden" id="parentId" value="'+parentId+'"/>');
                    $("#acc_type").val(glType).addClass("make-readonly-bg");
                    $("#acc_type").prev("label").removeClass('required');
                    $("#acc_name_code").val('');

                    $('#account_list').data("dt_params", {
                        glType: glType,
                        accNameCode: $('#acc_name_code').val(),
                        selector: parentId
                    }).DataTable().draw();

                    $("#accountListModal").modal('show');
                    $(".dep-div-sec").addClass('d-none'); // ADD THIS CONDITION. PAVEL-11-04-22
                }
            } else {
                $("#account_type").notify("Select account type first.", "error");
            }
        });

        let accountTable = $('#account_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            autoWidth: false,
            ordering: false,
            /*bDestroy : true,
            pageLength: 20,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
            ajax: {
                url: APP_URL + '/ajax/coa-acc-datalist',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#account_list').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id", "width": "15%"},
                {"data": "gl_acc_name"},
                {"data": "gl_acc_code", "width": "15%"},
                {"data": "action", "orderable":false, "width": "15%"}
            ],

            /*language: {
                paginate: {
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">'
                }
            }*/
        });
        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            let parentSelector = $("#parentId").val();

            $('#account_list').data("dt_params", {
                glType: $('#acc_type :selected').val(),
                accNameCode: $('#acc_name_code').val(),
                selector: parentSelector
            }).DataTable().draw();
        });
        $("#acc_modal_reset").on('click', function () {
            $("#acc_name_code").val('');
            $('#account_list').data("dt_params", {
                glType: '',
                accNameCode: ''
            }).DataTable().draw();
        });
        function getAccountDetail(acc_type, acc_id, selector) {
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/gl-type-acc-wise-coa',
                method: 'GET',
                data: {gl_type_id: acc_type, gl_acc_id: acc_id}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $(selector).parent().parent().children().children('input[type=number]').notify("Account id not found", "error");
                    $(selector).parent().parent().parent().next('div').find('input[type=text]').val();
                } else {
                    $(selector).parent().parent().children().children('input[type=number]').val(d.gl_acc_id);
                    $(selector).parent().parent().parent().next('div').find('input[type=text]').val(d.gl_acc_name);
                }
                $("#accountListModal").modal('hide');
                //$("#accountListModal").modal("dispose");
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        $(document).on('click', '.removeInvoiceBill', function (e) {
            let selector = this;
            swal.fire({
                text: 'Delete Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value == true) {
                    let url = "{{route('ar-invoice-bill-parameter.delete', ['id' => ":_p"])}}";
                    let newString = url.replace(":_p", $(selector).data('target'));
                    window.location.href = newString;
                }
            })
        });

        let billParametersTable = $('#invoice_bill_parameters').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            /*bDestroy : true,
            pageLength: 20,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
            ajax: {
                url: APP_URL + '/account-receivable/ar-invoice-bill-parameter-datalist',
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "id"},
                {"data": "parameter"},
                {"data": "action", "orderable":false}
            ],
        });
    </script>
@endsection

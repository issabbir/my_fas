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
    @include('ar.customer-account-balance-query.form')

    @include('ar.ar-common.common_customer_list_modal')
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(" #ap_customer_search").on("click", function () {
            let customerId = $('#ap_search_customer_id').val();
            if (!nullEmptyUndefinedChecked(customerId)) {
                getCustomerDetail(customerId);
            } else {
                $("#customerListModal").modal('show');
            }
        });
        $("#customer_search_form").on('submit', function (e) {
            e.preventDefault();

            $('#customerSearch').data("dt_params", {
                customerType: $('#search_customer_type :selected').val(),
                customerCategory: $('#search_customer_category :selected').val(),
                customerName: $('#search_customer_name').val(),
                customerShortName: $('#search_customer_short_name').val(),
            }).DataTable().draw();
            //accountTable.draw();
        });

        function resetBalanceQuery()
        {
            resetField(['#ap_search_customer_name','#ap_search_customer_category','#regular_posting_period','#ap_bills_payable','#ap_prepayments','#ap_security_deposits','#ap_advance','#ap_imprest_cash','#ap_revolving_cash']);
            resetTablesDynamicRow("#sub_ledger_detail")
        }

        $(document).on("click",'.customerSelect', function (e) {
            let customer_id = $(this).data('customer');
            getCustomerDetail(customer_id);
        });
        function getCustomerDetail(customer_id) {
            var request = $.ajax({
                url: APP_URL + '/account-receivable/ajax/customer-with-outstanding-balance',
                data: {customerId: customer_id}
            });

            request.done(function (d) {console.log(d)
                if ($.isEmptyObject(d.customer)) {
                    $("#ap_search_customer_id").notify("Customer id not found", "error");
                    resetField(['#ap_search_customer_id','#ap_search_customer_name','#regular_posting_period','#ap_search_customer_category','#ap_bills_payable','#ap_prepayments','#ap_security_deposits','#ap_advance','#ap_imprest_cash','#ap_revolving_cash']);
                } else {

                    $('#ap_search_customer_id').val(d.customer.customer_id);
                    $('#ap_search_customer_name').val(d.customer.customer_name);
                    $('#ap_search_customer_category').val(d.customer.customer_category_name);
                    $('#regular_posting_period').val(d.ledger_info[0].regular_posting_period);
                    $('#ap_bills_payable').val(d.customer.os_bill_receivable);

                    $("#sub_ledger_detail >tbody").html(d.ledger);
                }
                $("#customerListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        let customerTable = $('#customerSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            scrollX:true,
            ajax: {
                url: APP_URL + '/account-receivable/ajax/customer-search-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#customerSearch').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": 'customer_id', "name": 'customer_id'},
                {"data": "name"},
                {"data": "short_name"},
                {"data": "address"},
                {"data": "action", "orderable":false}
            ],
        });
        $(document).on('shown.bs.modal', '#customerListModal', function () {
            customerTable.columns.adjust().draw();
        });
    </script>
@endsection


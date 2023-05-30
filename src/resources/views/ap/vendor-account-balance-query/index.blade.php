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
    @include('ap.vendor-account-balance-query.form')

    @include('ap.ap-common.common_vendor_list_modal')
@endsection

@section('footer-script')
    <script type="text/javascript">

        $(" #ap_vendor_search").on("click", function () {
            let vendorId = $('#ap_search_vendor_id').val();
            if (!nullEmptyUndefinedChecked(vendorId)) {
                getVendorDetail(vendorId);
            } else {
                $("#vendorListModal").modal('show');
            }
        });
        $("#vendor_search_form").on('submit', function (e) {
            e.preventDefault();

            $('#vendorSearch').data("dt_params", {
                vendorType: $('#search_vendor_type :selected').val(),
                vendorCategory: $('#search_vendor_category :selected').val(),
                vendorName: $('#search_vendor_name').val(),
                vendorShortName: $('#search_vendor_short_name').val(),
            }).DataTable().draw();
            //accountTable.draw();
        });


        function resetBalanceQuery()
        {
            resetField(['#ap_search_vendor_name','#ap_search_vendor_category','#ap_bills_payable','#ap_prepayments','#ap_security_deposits','#ap_advance','#provision_expenses','#ap_imprest_revolving_cash']);
            resetTablesDynamicRow("#sub_ledger_detail")
        }

        $(document).on("click",'.vendorSelect', function (e) {
            let vendor_id = $(this).data('vendor');
            getVendorDetail(vendor_id);
        });

        function getVendorDetail(vendor_id) {
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-with-outstanding-balance',
                data: {vendorId: vendor_id}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.vendor)) {
                    $("#ap_search_vendor_id").notify("Vendor id not found", "error");
                    resetField(['#ap_search_vendor_id','#ap_search_vendor_name','#ap_search_vendor_category','#ap_bills_payable','#ap_prepayments','#ap_security_deposits','#ap_advance','#provision_expenses','#ap_imprest_revolving_cash']);
                } else {

                    $('#ap_search_vendor_id').val(d.vendor.vendor_id);
                    $('#ap_search_vendor_name').val(d.vendor.vendor_name);
                    $('#ap_search_vendor_category').val(d.vendor.vendor_category_name);
                    $('#ap_bills_payable').val(d.vendor.bills_payable);
                    $('#provision_expenses').val(d.vendor.provisional_expense_payable);
                    $('#ap_prepayments').val(d.vendor.os_prepayment);
                    $('#ap_security_deposits').val(d.vendor.security_deposit_payable);
                    $('#ap_advance').val(d.vendor.os_advance);
                    $('#ap_imprest_revolving_cash').val(d.vendor.os_imprest_revolving_cash);
                    $('#regular_posting_period').val(d.ledger_info[0].regular_posting_period);

                    $("#sub_ledger_detail >tbody").html(d.ledger);

                    //$('#ap_revolving_cash').val('');
                }
                $("#vendorListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        let vendorTable = $('#vendorSearch').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-payable/ajax/vendor-search-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#vendorSearch').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                //{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": 'vendor_id', "name": 'vendor_id'},
                {"data": "name"},
                {"data": "short_name"},
                {"data": "address"},
                {"data": "action", "orderable":false}
            ],
        });
        $(document).on('shown.bs.modal', '#vendorListModal', function () {
            vendorTable.columns.adjust().draw();
        });
    </script>
@endsection


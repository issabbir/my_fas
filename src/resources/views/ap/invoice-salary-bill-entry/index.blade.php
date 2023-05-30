<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৮ PM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }

        .text-right-align {
            text-align: right;
        }

        /*.bootstrap-datetimepicker-widget table td.active, .bootstrap-datetimepicker-widget table td.active:hover {
             background-color: transparent;
             color: #727E8C;
             text-shadow: 0 0 #f3f0f0;
        }*/
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
    <div class="card">
        <div class="card-body">
            @include("ap.invoice-salary-bill-entry.form")
        </div>
    </div>
    @include('ap.ap-common.common_po_list_modal')
    @include('ap.ap-common.common_vendor_list_modal')
    @include('ar.ar-common.common_customer_list_modal')
    @include('ap.invoice-bill-entry.common_budged_search')
    {{--@include('gl.common_coalist_modal')--}}
    @include('ap.ap-common.common_coalist_modal')  <!---Add Where Condition- Pavel-15-02-22--->

@endsection

@section('footer-script')
    <script type="text/javascript">
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;


        var removeAddAccLineRow;
        var editAddAccAccount;


        $(document).on("change", '#emp_type_id', function (e) {

            let emp_type_id = $('#emp_type_id').val();
            invRefList(emp_type_id);
            deduction(emp_type_id);
        });


        function invRefList(emp_type_id) {
            $.ajax({
                type: 'get',
                url: APP_URL + '/account-payable/invoice-salary-pay-allowance/' + emp_type_id,
                // url: APP_URL + '/account-payable/invoice-salary-pay-allowance',
                // data: $('#invoice_salary_bill_entry_form').serialize(),
                success: function (data) {
                    $('#invRefList').html(data.html);
                    $("#total_checked1").html('');
                    // //getInvRefTotal(true, ($("#selected_pay_queue_inv_id").val()) );
                    resetField(['#total_checked1']);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function deduction(emp_type_id) {
            $.ajax({
                type: 'get',
                url: APP_URL + '/account-payable/invoice-salary-pay-deduction/' + emp_type_id,
                // data: $('#invoice_salary_bill_entry_form').serialize(),
                success: function (data) {
                    $('#deductionList').html(data.html);
                    $("#total_checked2").html('');
                    // //getInvRefTotal(true, ($("#selected_pay_queue_inv_id").val()) );
                    resetField(['#total_checked2']);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }


        /* function accountPayCheque(search) {
             // Hide all table tbody rows
             $('#inv_ref_table_search table').find('tbody >tr').hide();

             // Count total search result
             var len = $('#inv_ref_table_search table').find('tbody tr:not(.notfound) td:contains("' + search + '")').length;

             if (len > 0) {
                 // Searching text & check column in columns and show match row
                 $('#inv_ref_table_search table tbody tr').each(function () {
                     if (!nullEmptyUndefinedChecked($(this).find('td:contains("' + search + '")').text())) {
                         $(this).show();
                     } else if ($(this).find('.inv-ref-check').prop('checked')) {
                         $(this).show();
                     }
                 });
             } else {
                 $('.notfound').show();
             }
         }*/

        /*$('#table_search').keyup(function () {
            // Search Text
            var search = $(this).val();

            accountPayCheque(search);

        });*/

        function payAllowTableSearch() {
            // Search all columns
            $('#table_search').keyup(function () {

                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('#inv_ref_pay_allow_table_search table').find('tbody >tr').hide();

                // Count total search result
                var len = $('#inv_ref_pay_allow_table_search table').find('tbody tr:not(.notfound) td:contains("' + search + '")').length;
                if (len > 0) {
                    // Searching text in columns and show match row
                    $('#inv_ref_pay_allow_table_search table').find('tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                        $(this).closest('tr').show();
                    });
                } else {
                    $('.notfound').show();
                }

            })

            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });

        }

        function deductionTableSearch() {
            // Search all columns
            $('#table_search_deduction').keyup(function () {

                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('#inv_ref_deduction_table_search table').find('tbody >tr').hide();

                // Count total search result
                var len = $('#inv_ref_deduction_table_search table').find('tbody tr:not(.notfound) td:contains("' + search + '")').length;
                if (len > 0) {
                    // Searching text in columns and show match row
                    $('#inv_ref_deduction_table_search table').find('tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                        $(this).closest('tr').show();
                    });
                } else {
                    $('.notfound').show();
                }

            })

            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });

        }

        // $("#ap_party_sub_ledger").on('change', function () {
        //     let subsidiaryId = $(this).val();
        //     apSalaryInvoiceType(subsidiaryId);
        //
        //
        // })
        /* New Function*/
        function apSalaryInvoiceType(subsidiaryId) {

            $("#ap_invoice_type").val("");

            if (!nullEmptyUndefinedChecked(subsidiaryId)) {
                var request = $.ajax({
                    url: APP_URL + '/account-payable/ajax/get-invoice-types-on-subsidiary',
                    data: {subsidiaryId: subsidiaryId}
                });

                request.done(function (d) {

                    $("#ap_invoice_type").trigger('change');
                    if (!$.isEmptyObject(d)) {
                        $("#ap_invoice_type").html(d);
                    } else {
                        $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            } else {
                $("#ap_invoice_type").trigger('change');
                $("#ap_invoice_type").html('<option value="">Select Invoice Type</option>');
            }


        }


        function calculateLcy(selectors) {
            selectors.forEach(function (selector) {
                let ccy = $(selector).val();
                if ($(selector).parent().next('div').children('input[type=text]').length > 0) {
                    //For Invoice number
                    let value = !isNaN(parseFloat(ccy) * parseFloat($("#ap_exchange_rate").val())) ? (parseFloat(ccy) * parseFloat($("#ap_exchange_rate").val())).toFixed(2) : "0.00";
                    $(selector).parent().next('div').children('input[type=text]').val(value);
                } else {
                    //For TAX, VAT, Security Deposit
                    let value = !isNaN(parseFloat(ccy) * parseFloat($("#ap_exchange_rate").val())) ? (parseFloat(ccy) * parseFloat($("#ap_exchange_rate").val())).toFixed(2) : "0.00";
                    $(selector).parent().parent().parent().next('div').children('input[type=text]').val(value);
                }
            })

        }


        $(" #ap_vendor_search").on("click", function () {
            let vendorId = $('#ap_vendor_id').val();

            //$('#ap_switch_pay_vendor_search').val('{{--{{\App\Enums\YesNoFlag::NO}}--}}'); //Block this Pavel-28-08-22
            /*** Add this variable -Pavel: 23-03-22 ***/
            $('#ap_add_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}');
            $('#ar_add_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}');
            /*** Add this variable -Pavel: 07-07-22 ***/
            $("#ap_dist_vendor_search").val('{{\App\Enums\YesNoFlag::NO}}')

            if (!nullEmptyUndefinedChecked($("#ap_invoice_type").val())) {
                if (!nullEmptyUndefinedChecked(vendorId)) {
                    getVendorDetail(vendorId, setPartyLedgerVendorInfo);
                } else {
                    let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
                    if (!nullEmptyUndefinedChecked(invoiceParams)) {
                        let invoiceArray = invoiceParams.split("#");
                        /*
                        0=> D/C flag    (D=line C; C=line D)
                        1=> vendor type
                        2=> vendor category
                        3=> Source Allow Flag   (0=Tax, VAT, Security Disable; 1= Enable)
                        4=> distribution line (0 = enable)/(1=disable)
                         */
                        if (!nullEmptyUndefinedChecked(invoiceArray[1])) {
                            $("#search_vendor_type").val(invoiceArray[1]).parent('div').addClass('make-readonly');
                        } else {
                            $("#search_vendor_type").val('').parent('div').removeClass('make-readonly');
                        }

                        if (!nullEmptyUndefinedChecked(invoiceArray[2])) {
                            $("#search_vendor_category").val(invoiceArray[2]).parent('div').addClass('make-readonly');
                        } else {
                            $("#search_vendor_category").val('').parent('div').removeClass('make-readonly');
                        }
                    }
                    reloadVendorListTable();
                    $("#vendorListModal").modal('show');
                }
            } else {
                $("#ap_invoice_type").notify("Select Invoice Type First.");
                $('#ap_vendor_id').val('');
            }
        });


        function reloadVendorListTable() {
            $('#vendorSearch').data("dt_params", {
                vendorType: $('#search_vendor_type :selected').val(),
                vendorCategory: $('#search_vendor_category :selected').val(),
                vendorName: $('#search_vendor_name').val(),
                vendorShortName: $('#search_vendor_short_name').val(),
            }).DataTable().draw();
        }

        $("#vendor_search_form").on('submit', function (e) {
            e.preventDefault();
            reloadVendorListTable();
            //accountTable.draw();
        });


        $(document).on('click', '.vendorSelect', function () {

            if (($('#ap_add_vendor_search').val()) == '{{\App\Enums\YesNoFlag::YES}}') {  //Add -Pavel: 07-07-22
                getAddVendorDetail($(this).data('vendor'), $("#ap_add_party_sub_ledger"), setAddVendorInfo);
                $('#ap_add_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}')
            }else {
                getVendorDetail($(this).data('vendor'), setPartyLedgerVendorInfo);
            }
        });

        function setPartyLedgerVendorInfo(d) {
            if ($.isEmptyObject(d.vendor)) {
                $("#ap_vendor_id").notify("Vendor id not found", "error");

                emptyTaxVatPayableDropdown();
            } else {
                $('#ap_vendor_id').val(d.vendor.vendor_id);
                $('#ap_vendor_name').val(d.vendor.vendor_name);
                $('#ap_vendor_category').val(d.vendor.vendor_category.vendor_category_name);


                $("#party_name_for_tax").html(d.taxParty);
                $("#party_name_for_vat").html(d.vatParty);


            }
        }

        function getVendorDetail(vendor_id, callback) {
            let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
            let vendorType = '';
            let vendorCategory = '';
            let invoiceType = $("#ap_invoice_type :selected").val();
            let dlSourceAllowFlag = '';
            if (!nullEmptyUndefinedChecked(invoiceParams)) {
                let invoiceArray = invoiceParams.split("#");

                if (!nullEmptyUndefinedChecked(invoiceArray[1])) {
                    vendorType = invoiceArray[1];
                }
                if (!nullEmptyUndefinedChecked(invoiceArray[2])) {
                    vendorCategory = invoiceArray[2];
                }

                if (!nullEmptyUndefinedChecked(invoiceArray[3])) {
                    dlSourceAllowFlag = invoiceArray[3];
                }
            }

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-details',
                data: {
                    vendorId: vendor_id,
                    vendorType: vendorType,
                    vendorCategory: vendorCategory,
                    invoiceType: invoiceType,
                    dlSourceAllowFlag: dlSourceAllowFlag
                }
            });

            request.done(function (d) {
                callback(d);
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
                {"data": 'vendor_id', "name": 'vendor_id'},
                {"data": "name"},
                {"data": "short_name"},
                {"data": "address"},
                {"data": "action", "orderable": false}
            ],
        });


        function resetAdditionalSubLedgerPartyFields() {
            resetField([
                "#ap_add_party_sub_ledger",
                "#ap_add_vendor_id", "#ap_add_vendor_name",
                "#ap_add_vendor_category", "#ap_add_account_balance",
                "#ap_add_authorized_balance",

                "#ar_add_party_sub_ledger",
                "#ar_add_vendor_id", "#ar_add_vendor_name",
                "#ar_add_vendor_category", "#ar_add_account_balance",
                "#ar_add_authorized_balance"
            ]);
            $(".payableArea").addClass('hidden');
            $(".receivableArea").addClass('hidden');
        }


        function resetAddAccountField() {
            resetField(['#ap_add_account_name', '#ap_add_account_type', '#ap_add_amount_ccy', '#ap_add_amount_lcy', '#ap_add_amount_word']);
        }


        $(" #ap_add_vendor_search").on("click", function () {
            let vendorId = $('#ap_add_vendor_id').val();

            $('#ap_add_vendor_search').val('{{\App\Enums\YesNoFlag::YES}}');

            if (!nullEmptyUndefinedChecked(vendorId)) {
                getAddVendorDetail(vendorId, $("#ap_add_party_sub_ledger"), setAddVendorInfo);
            } else {
                let vendorParams = $("#ap_add_party_sub_ledger").find(':selected').data("partyparams");
                if (!nullEmptyUndefinedChecked(vendorParams)) {
                    let vendorParamArray = vendorParams.split("#");
                    /*
                    0=> vendor type
                    1=> vendor category
                     */
                    if (!nullEmptyUndefinedChecked(vendorParamArray[0])) {
                        $("#search_vendor_type").val(vendorParamArray[0]).addClass('make-readonly');
                    } else {
                        $("#search_vendor_type").val('').removeClass('make-readonly');
                    }

                    if (!nullEmptyUndefinedChecked(vendorParamArray[1])) {
                        $("#search_vendor_category").val(vendorParamArray[1]).addClass('make-readonly');
                    } else {
                        $("#search_vendor_category").val('').removeClass('make-readonly');
                    }
                }
                reloadVendorListTable();
                $("#vendorListModal").modal('show');
            }
        });


        function enableDisableSaveBtn() {

            switch ($("#ap_distribution_flag").val()) {
                case '1' :
                    $("#preview_btn").prop('disabled', false);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                    break;
                case '0' :
                    if (nullEmptyUndefinedChecked(totalLcy("#ap_account_table")) || nullEmptyUndefinedChecked($("#ap_invoice_amount_lcy").val()) || (totalLcy("#ap_account_table") != parseFloat($("#ap_invoice_amount_lcy").val())) || (parseFloat($("#total_debit").val()) != parseFloat($("#ap_invoice_amount_ccy").val())) && $("#ap_without_budget_info").prop("checked") == false) {
                        $("#preview_btn").prop('disabled', true);//alert('if')
                        $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
                    } else {//alert('else')
                        $("#preview_btn").prop('disabled', false);
                        $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                    }

                    break;

                default:
                    $("#preview_btn").prop('disabled', true);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            }

        }

        function resetTaxVatSecField() {
            $("#ap_invoice_amount_ccy").val("");
            $("#ap_invoice_amount_lcy").val("");

            $("#ap_tax_amount_ccy").val("").attr('readonly', 'readonly');
            $("#ap_tax_amount_lcy").val("");

            $("#ap_vat_amount_ccy").val("").attr('readonly', 'readonly');
            $("#ap_vat_amount_lcy").val("");

            $("#ap_security_deposit_amount_ccy").val("").attr('readonly', 'readonly');
            $("#ap_security_deposit_amount_lcy").val("");

            $("#ap_extra_security_deposit_amount_ccy").val("").attr('readonly', 'readonly');
            $("#ap_extra_security_deposit_amount_lcy").val("");

            $("#ap_fine_forfeiture_ccy").val("").attr('readonly', 'readonly');
            $("#ap_fine_forfeiture_lcy").val("");

            $("#ap_preshipment_ccy").val("").attr('readonly', 'readonly');
            $("#ap_preshipment_lcy").val("");

            $("#ap_electricity_bill_ccy").val("").attr('readonly', 'readonly');
            $("#ap_electricity_bill_lcy").val("");

            $("#ap_other_charge_ccy").val("").attr('readonly', 'readonly');
            $("#ap_other_charge_lcy").val("");

            $("#ap_payable_amount_ccy").val("");
            $("#ap_payable_amount_lcy").val("");

            //Tabindex
            $("#ap_tax_amount_ccy ,#ap_vat_amount_ccy, #ap_security_deposit_amount_ccy,#ap_extra_security_deposit_amount_ccy,#ap_fine_forfeiture_ccy,#ap_preshipment_ccy,#ap_electricity_bill_ccy,#ap_other_charge_ccy").attr("tabindex", "-1")

            resetField(["#ap_tax_amount_ccy_percentage",
                "#ap_vat_amount_ccy_percentage",
                "#ap_security_deposit_amount_ccy_percentage",
                "#ap_extra_security_deposit_amount_ccy_percentage",
                "#ap_vendor_id",
                "#ap_vendor_name",
                "#ap_vendor_category",
                //Block this Pavel-28-08-22
                /*"#ap_switch_pay_vendor_id",  //Add Switch pay vendor field :pavel-23-03-22
                "#ap_switch_pay_vendor_name",
                "#ap_switch_pay_vendor_category",*/
                "#search_vendor_type",
                "#search_vendor_category"])
        }

        function getAddVendorDetail(vendor_id, subLedgerSelector, callback) {
            let vendorParams = subLedgerSelector.find(':selected').data("partyparams");
            let vendorType = '';
            let vendorCategory = '';
            let dlSourceAllowFlag = '';
            if (!nullEmptyUndefinedChecked(vendorParams)) {
                let vendorArray = vendorParams.split("#");
                /*
                 0=> vendor type
                 1=> vendor category
                */
                if (!nullEmptyUndefinedChecked(vendorParams[0])) {
                    vendorType = vendorParams[0];
                }
                if (!nullEmptyUndefinedChecked(vendorParams[1])) {
                    vendorCategory = vendorParams[1];
                }
            }

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/add-vendor-details',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    glSubsidiaryId: subLedgerSelector.find(':selected').val(),
                    vendorId: vendor_id,
                }
            });

            request.done(function (d) {
                callback(d);
                $("#vendorListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        customerInfoList();

        function customerInfoList() {
            $("#ar_customer_search").on("click", function () {
                let customerId = $('#ar_add_customer_id').val();

                if (!nullEmptyUndefinedChecked(customerId)) {
                    getCustomerDetail(customerId);
                } else {
                    reloadCustomerListTable();
                    $("#customerListModal").modal('show');
                }
            });

            $("#ar_dist_customer_search").on("click", function () {
                let customerId = $('#ar_dist_customer_id').val();

                if (!nullEmptyUndefinedChecked(customerId)) {
                    getCustomerDetail(customerId, false);
                } else {
                    reloadCustomerListTable(false);
                    $("#customerListModal").modal('show');
                }
            });

            function reloadCustomerListTable(forAdd = true) {
                $('#customerSearch').data("dt_params", {
                    customerCategory: $('#search_customer_category :selected').val(),
                    customerName: $('#search_customer_name').val(),
                    customerShortName: $('#search_customer_short_name').val(),
                    forAdditionalAcc: forAdd
                }).DataTable().draw();
            }

            $("#customer_search_form").on('submit', function (e) {
                e.preventDefault();
                reloadCustomerListTable();
                //accountTable.draw();
            });

            $("#ar_reset_customer_balance_field").on("click", function () {
                resetField(['#ar_search_customer_id', '#ar_search_customer_name', '#ar_search_customer_category', '#ar_bills_receivable', '#ar_prepayments', '#ar_security_deposits', '#ar_advance', '#ar_imprest_cash', '#ar_revolving_cash']);
            });

            $(document).on('click', '.customerSelect', function () {
                getCustomerDetail($(this).data('customer'), $(this).data('foradd'));
            });

            function getCustomerDetail(customer_id, forAdd = true) {
                //let invoiceParams = $("#ar_transaction_type").find(':selected').data("invoiceparams");
                let customerType = '';
                let customerCategory = '';
                let subLedger = "";
                if (forAdd) {
                    subLedger = $("#ar_add_party_sub_ledger :selected").val();
                } else {
                    subLedger = $("#ar_dist_party_sub_ledger :selected").val();
                }
                var request = $.ajax({
                    url: APP_URL + '/general-ledger/ajax/get-party-account-details',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    data: {
                        glSubsidiaryId: subLedger,
                        customerId: customer_id,
                    }
                });

                request.done(function (d) {
                    if ($.isEmptyObject(d.party_info)) {
                        $("#ar_add_customer_id").notify("Customer id not found", "error");
                        resetField(['#ar_customer_id', '#ar_customer_name', '#ar_customer_category']);
                    } else {
                        if (forAdd) {
                            $('#ar_add_customer_id').val(d.party_info.party_id);
                            $('#ar_add_customer_name').val(d.party_info.party_name);
                            $('#ar_add_customer_category').val(d.party_info.party_category);
                            $('#ar_add_account_balance').val(d.party_info.account_balance);
                            $('#ar_add_authorized_balance').val(d.party_info.authorized_balance);

                            $("#ar_add_account_balance_type").text(d.party_info.account_balance_type);
                            $("#ar_add_authorized_balance_type").text(d.party_info.authorized_balance_type);

                        } else {
                            $('#ar_dist_customer_id').val(d.party_info.party_id);
                            $('#ar_dist_customer_name').val(d.party_info.party_name);
                            $('#ar_dist_customer_category').val(d.party_info.party_category);
                            $('#ar_dist_account_balance').val(getCommaSeparatedValue(d.party_info.account_balance));
                            $('#ar_dist_authorized_balance').val(getCommaSeparatedValue(d.party_info.authorized_balance));

                            $("#ar_dist_account_balance_type").text(d.party_info.account_balance_type);
                            $("#ar_dist_authorized_balance_type").text(d.party_info.authorized_balance_type);
                        }

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
                    {"data": 'customer_id', "name": 'customer_id'},
                    {"data": "name"},
                    {"data": "short_name"},
                    {"data": "address"},
                    {"data": "action", "orderable": false}
                ],
            });

            $(document).on('shown.bs.modal', '#customerListModal', function () {
                customerTable.columns.adjust().draw();
            });
        }

        //Reset & Disable Budget Booking Utilized :pavel-23-01-22
        function resetDisableBudgetBooking() {
            $(".budget_booking_utilized_div").addClass('d-none');
            resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']); //Add this sec Pavel-24-03-22
            //resetField(['#b_booking_id','#b_head_id','#b_head_name','#b_sub_category','#b_category','#b_type','#b_date','#b_amt','#b_available_amt']); //Block this sec Pavel-24-03-22
        }



        function enableDisablePOsearchArea(key) {
            if (key === 1) {
                $(".po_base_invoice").show(1000);
                $("#search_po").prop("disabled", false);
            } else {
                $(".po_base_invoice").hide(1000);
                $("#search_po").prop("disabled", true);
                resetField(["#ap_purchase_order_no", "#ap_purchase_order_date"])
            }
        }

        function setPaymentDueDate(selector) {
            $("#ap_payment_due_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            datePickerOnPeriod(selector, minDate, maxDate);
        }

        $("#ap_dist_vendor_search").on("click", function () {
            let vendorId = $('#ap_dist_vendor_id').val();
            $("#ap_dist_vendor_search").val('{{\App\Enums\YesNoFlag::YES}}')
            if (!nullEmptyUndefinedChecked(vendorId)) {
                getAddVendorDetail(vendorId, $("#ap_dist_party_sub_ledger"), setDistVendorInfo);
            } else {
                let vendorParams = $("#ap_dist_party_sub_ledger").find(':selected').data("partyparams");
                if (!nullEmptyUndefinedChecked(vendorParams)) {
                    let vendorParamArray = vendorParams.split("#");
                    /*
                0=> vendor type
                1=> vendor category
                 */
                    if (!nullEmptyUndefinedChecked(vendorParamArray[0])) {
                        $("#search_vendor_type").val(vendorParamArray[0]).addClass('make-readonly');
                    } else {
                        $("#search_vendor_type").val('').removeClass('make-readonly');
                    }

                    if (!nullEmptyUndefinedChecked(vendorParamArray[1])) {
                        $("#search_vendor_category").val(vendorParamArray[1]).addClass('make-readonly');
                    } else {
                        $("#search_vendor_category").val('').removeClass('make-readonly');
                    }
                }
                reloadVendorListTable();
                $("#vendorListModal").modal('show');
            }
        });

        function setDistVendorInfo(d) {
            if ($.isEmptyObject(d.party_info)) {
                $("#ap_dist_vendor_id").notify("Vendor id not found", "error");
                resetField(['#ap_dist_vendor_id', '#ap_dist_vendor_name', '#ap_dist_vendor_category', '#ap_dist_account_balance', '#ap_dist_account_balance']);
                //emptyTaxVatPayableDropdown();
            } else {
                $('#ap_dist_vendor_id').val(d.party_info.party_id);
                $('#ap_dist_vendor_name').val(d.party_info.party_name);
                $('#ap_dist_vendor_category').val(d.party_info.party_category);
                $('#ap_dist_account_balance').val(getCommaSeparatedValue(d.party_info.account_balance));
                $('#ap_dist_authorized_balance').val(getCommaSeparatedValue(d.party_info.authorized_balance));

                $("#ap_dist_account_balance_type").text(d.party_info.account_balance_type);
                $("#ap_dist_authorized_balance_type").text(d.party_info.authorized_balance_type);

            }
        }

        $(document).ready(function () {

            payAllowTableSearch();
            deductionTableSearch();

            $(".po_base_invoice").hide();

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let paymentCalendarClickCounter = 0;
            $("#ap_posting_name").val($("#period").find(':selected').data('postingname'));

            $("#period").on('change', function () {
                $("#document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }
                $("#ap_posting_name").val($(this).find(':selected').data('postingname'));

                $("#ap_payment_due_date >input").val("");
                if (paymentCalendarClickCounter > 0) {
                    $("#ap_payment_due_date").datetimepicker('destroy');
                    paymentCalendarClickCounter = 0;
                }

                setPeriodCurrentDate();
            });

            /********Added on: 06/06/2022, sujon**********/
            function setPeriodCurrentDate() {
                $("#posting_date_field").val($("#period :selected").data("currentdate"));
                $("#document_date_field").val($("#period :selected").data("currentdate"));

                $("#ap_payment_terms").trigger('change');
            }

            /********End**********/

            $("#posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });
            let postingDateClickCounter = 0;

            $("#posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                let postingDate = $("#posting_date_field").val();
                $("#ap_payment_due_date_field").val("");
                if (!nullEmptyUndefinedChecked(postingDate)) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                    } else {
                        newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                    }
                    //$("#ap_payment_due_date_field").val(newDueDate);
                    $("#ap_payment_terms").select2().trigger('change');

                    $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            $("#document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#document_date >input").val("");
                let minDate = false;
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            /*
            * Payment due date redonly, value will come from posting date and terms sum
            * */

            /*$("#ap_payment_due_date").on('click', function () {
                paymentCalendarClickCounter++;
                setPaymentDueDate(this);
            });*/

            function listBillRegister() {
                $('#bill_section').change(function (e) {
                    $("#bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');


                });
            }


            $("#invoice_salary_bill_entry_form").on("submit", function (e) {
                e.preventDefault();


                let th_fiscal_year = $("th_fiscal_year")


                let ap_party_sub_ledger = $("#ap_party_sub_ledger").val();
                let ap_invoice_type = $("#ap_invoice_type").val();
                let ap_vendor_id = $("#ap_vendor_id").val();

                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit' + '<br>' +
                        'Party/Vendor ID: ' + $("#ap_vendor_id").val() + '<br>' +
                        'Party/Vendor Name: ' + $("#ap_vendor_name").val() + '<br>',
                    type: "info",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok",
                    confirmButtonClass: "btn btn-primary",
                    cancelButtonClass: "btn btn-danger ml-1",
                    buttonsStyling: !1
                }).then(function (result) {
                    if (result.value) {
                        let request = $.ajax({
                            url: APP_URL + "/account-payable/invoice-salary-bill-entry",
                            data: new FormData($("#invoice_salary_bill_entry_form")[0]),
                            processData: false,
                            contentType: false,
                            dataType: "JSON",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (res.response_code == "1") {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_msg,
                                    showConfirmButton: true,
                                    //timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    $("#reset_form").trigger('click');

                                    let printBtn = '<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info mr-1"><i class="bx bx-printer"></i>Print Last Voucher</a>';
                                    $('#print_btn').html(printBtn);

                                    if (res.section == '{{\App\Enums\Ap\LBillSection::SALARY_SECTION_1}}' || res.section == '{{\App\Enums\Ap\LBillSection::SALARY_SECTION_2}}') {
                                        let sPrintBtn = '<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_TRANSACTION_LISTING_BATCH_WISE_OTHER_SALARY.xdo&p_posting_period_id=' + res.period + '&p_module_id=' + {{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}} +'&p_function_id=' + {{\App\Enums\Ap\ApFunType::AP_INVOICE_BILL_ENTRY}}  +'&p_document_no=' + res.o_document_no + '&type=pdf&filename=transaction_list_batch_wise_other_salary"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Other Salary</a>';
                                        $('#salary_print_btn').html(sPrintBtn);
                                    } else {
                                        $('#salary_print_btn').html("");
                                    }


                                    $("#preview_btn").prop('disabled', true);
                                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);

                                    focusOnMe("#document_number");
                                    /*let url = '{{ route('invoice-bill-entry.index') }}';
                                        window.location.href = url;*/
                                });
                            } else {

                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
                //}
            })

            $("#preview_btn").on("click", function (e) {
                e.preventDefault();

                let ap_vendor_id = $("#ap_vendor_id ").val();

                let emp_type_id = $('#emp_type_id :selected').val();
                if (nullEmptyUndefinedChecked(emp_type_id)) {
                    $("#emp_type_id").focus();
                    $('html, body').animate({scrollTop: ($("#emp_type_id").offset().top - 400)}, 2000);
                    $("#emp_type_id").notify("Select Employee Type First.", {position: 'left'});

                } if (nullEmptyUndefinedChecked(ap_vendor_id)) {
                    $("#ap_vendor_id").focus();
                    $('html, body').animate({scrollTop: ($("#ap_vendor_id").offset().top - 400)}, 2000);
                    $("#ap_vendor_id").notify("Select Vendor .", {position: 'left'});

                }

                if (ap_vendor_id && emp_type_id) {
                    var formData = $("#invoice_salary_bill_entry_form").serialize();

                    var request = $.ajax({
                        url: APP_URL + "/account-payable/invoice-salary-bill-preview",
                        method: "POST",
                        data: new FormData($("#invoice_salary_bill_entry_form")[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });

                }

                request.done(function (res) {
                    if (res.response_code == "1") {
                        //$("#preview_content").html("");
                        $("#salary_preview_content").html(res.table_content);
                        $("#salaryPreviewModal").modal('show');
                    } else {

                        Swal.fire({text: res.response_msg, type: 'error'});
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    Swal.fire({text: textStatus + jqXHR, type: 'error'});
                    //console.log(jqXHR, textStatus);
                });

            })

            listBillRegister();
            //datePicker('#ap_payment_due_date')
            datePicker('#po_date');
            datePicker('#invoice_date');


            $("#reset_form").on('click', function () {
                resetTablesDynamicRow();
                removeAllAttachments();
                $("#ap_distribution_flag").val(1);
                /*0003183: Need not to refresh the narration & party subledger for AP Module*/
                $("#ap_party_sub_ledger").trigger('change');

            })


            $("#th_fiscal_year").on('change', function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#period").html(periods);
                //setPeriodCurrentDate();
                $("#period").trigger('change');
            }

            $("#ap_add_vendor_id").on('keyup', function () {
                $("#ap_account_balance_type").text('');
                $("#ap_authorized_balance_type").text('');
                resetField(['#ap_vendor_name', '#ap_vendor_category', '#ap_account_balance', '#ap_authorized_balance']);
            });

            $("#ar_add_customer_id").on('keyup', function () {
                $("#ar_account_balance_type").text('');
                $("#ar_authorized_balance_type").text('');
                resetField(['#ar_customer_name', '#ar_customer_category', '#ar_account_balance', '#ar_authorized_balance']);
            });

            $("#ar_dist_customer_id").on('keyup', function () {
                $("#ar_dist_account_balance_type").text('');
                $("#ar_dist_authorized_balance_type").text('');
                resetField(['#ar_dist_customer_name', '#ar_dist_customer_category', '#ar_dist_account_balance', '#ar_dist_authorized_balance']);
            });

            $("#ap_dist_vendor_id").on('keyup', function () {
                $("#ap_dist_account_balance_type").text('');
                $("#ap_dist_authorized_balance_type").text('');
                resetField(['#ap_dist_vendor_name', '#ap_dist_vendor_category', '#ap_dist_account_balance', '#ap_dist_authorized_balance']);
            });

            let subsidiaryId = $('#ap_party_sub_ledger').val();
            apSalaryInvoiceType(subsidiaryId)
        });


    </script>
@endsection


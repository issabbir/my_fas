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

        .max-w-14 {
            max-width: 14% !important;
        }
        .max-w-15 {
            max-width: 15% !important;
        }
        .max-w-30 {
            max-width: 30% !important;
        }
        .max-w-12_5{
            max-width: 12.5% !important;
        }
        .w-86 {
            width: 86% !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include("cm.fdr-opening-list.form")
        </div>
    </div>
   {{-- @include('ap.ap-common.common_po_list_modal')
    @include('ap.ap-common.common_vendor_list_modal')
    @include('ar.ar-common.common_customer_list_modal')
    @include('ap.invoice-bill-entry.common_budged_search')
    @include('ap.ap-common.common_coalist_modal')--}}

@endsection

@section('footer-script')
    <script type="text/javascript">
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;

        var addAddAccLineRow;
        var removeAddAccLineRow;
        var editAddAccAccount;

        $("#ap_party_sub_ledger").on('change', function () {
            let subsidiaryId = $(this).val();
            $("#ap_invoice_type").val("");
            resetTaxVatSecField();
            enableDisablePoCheck(0);
            // $("#add_account").attr("disabled", "disabled");

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

        })
        $("#ap_payment_currency").on('change', function () {
            resetField([
                /*'#ap_invoice_amount_ccy',
                '#ap_tax_amount_ccy',
                '#ap_vat_amount_ccy',
                '#ap_security_deposit_amount_ccy',
                '#ap_payable_amount_ccy',*/
                '#ap_invoice_amount_lcy',
                '#ap_tax_amount_lcy',
                '#ap_vat_amount_lcy',
                '#ap_security_deposit_amount_lcy',
                '#ap_extra_security_deposit_amount_lcy',
                /*'#ap_fine_forfeiture_lcy',
                '#ap_preshipment_lcy',
                '#ap_electricity_bill_lcy',
                '#ap_other_charge_lcy',*/
                '#ap_total_add_amount_ccy',
                '#ap_total_add_amount_lcy',
                '#ap_payable_amount_lcy'
            ]);
            $("#ap_add_account_table >tbody >tr").remove();
            openCloseAmountRateLcy($(this).val());
        });

        /*$("#ap_exchange_rate").on('keyup', function () {
            setPayable();
        });*/

        function openCloseAmountRateLcy(currency) {
            if (nullEmptyUndefinedChecked($("#ap_invoice_amount_ccy").val())) {
                resetField(["#ap_invoice_amount_ccy",
                    "#ap_tax_amount_ccy",
                    "#ap_vat_amount_ccy",
                    "#ap_security_deposit_amount_ccy",
                    "#ap_extra_security_deposit_amount_ccy",
                    "#ap_fine_forfeiture_ccy",
                    "#ap_preshipment_ccy",
                    "#ap_electricity_bill_ccy",
                    "#ap_other_charge_ccy",
                    "#ap_payable_amount_ccy"]);
            }

            resetField(["#ap_invoice_amount_lcy",
                "#ap_tax_amount_lcy",
                "#ap_vat_amount_lcy",
                "#ap_security_deposit_amount_lcy",
                "#ap_extra_security_deposit_amount_lcy",
                "#ap_fine_forfeiture_lcy",
                "#ap_preshipment_lcy",
                "#ap_electricity_bill_lcy",
                "#ap_other_charge_lcy",
                "#ap_payable_amount_lcy"]);

            if (currency == "{{ \App\Enums\Common\Currencies::O_BD }}") {
                $("#ap_exchange_rate").val('1');
                $("#ap_exchange_rate").attr('readonly', 'readonly');

                $("#ap_exchange_rate").keyup();
                /*$("#ap_invoice_amount_lcy").attr('readonly', 'readonly');
                $("#ap_tax_amount_lcy").attr('readonly', 'readonly');
                $("#ap_vat_amount_lcy").attr('readonly', 'readonly');
                $("#ap_security_deposit_amount_lcy").attr('readonly', 'readonly');*/
                //$("#ap_payable_amount_lcy").attr('readonly', 'readonly');

            } else {
                $("#ap_exchange_rate").val('0');
                $("#ap_exchange_rate").removeAttr('readonly');

                /*$("#ap_invoice_amount_lcy").removeAttr('readonly');
                $("#ap_tax_amount_lcy").removeAttr('readonly');
                $("#ap_vat_amount_lcy").removeAttr('readonly');
                $("#ap_security_deposit_amount_lcy").removeAttr('readonly');*/
                //$("#ap_payable_amount_lcy").removeAttr('readonly');
            }
        }

        $(document).on('keyup', '#ap_invoice_amount_ccy, #ap_tax_amount_ccy, #ap_vat_amount_ccy, #ap_security_deposit_amount_ccy, #ap_extra_security_deposit_amount_ccy, #ap_fine_forfeiture_ccy, #ap_preshipment_ccy, #ap_electricity_bill_ccy, #ap_other_charge_ccy', function () {
            calculateLcy([this]);
            if ($("#ap_calculate_tax_vat").prop('checked')) {
                setCalculatedTaxVatDeposit("#ap_tax_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_vat_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_security_deposit_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_extra_security_deposit_amount_ccy_percentage");
            }

            if ($(this).attr('id') == 'ap_invoice_amount_ccy') {
                $("#ap_amount_word_ccy").val(amountTranslate($(this).val()));

                /*calculateLcy(["#ap_tax_amount_ccy", "#ap_vat_amount_ccy",
                    "#ap_security_deposit_amount_ccy",
                    "#ap_extra_security_deposit_amount_ccy",
                    "#ap_fine_forfeiture_ccy",
                    "#ap_preshipment_ccy",
                    "#ap_electricity_bill_ccy",
                    "#ap_other_charge_ccy"]);*/
            }

            setPayable("ccy");
            setPayable("lcy");
            enableDisableSaveBtn();
        });
        $(document).on('keyup', '#ap_exchange_rate', function () {
            calculateLcy(["#ap_invoice_amount_ccy"]);
            if ($("#ap_calculate_tax_vat").prop('checked')) {
                setCalculatedTaxVatDeposit("#ap_tax_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_vat_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_security_deposit_amount_ccy_percentage");
                setCalculatedTaxVatDeposit("#ap_extra_security_deposit_amount_ccy_percentage");
            } else if (!nullEmptyUndefinedChecked($("#ap_invoice_amount_lcy").val())) {
                calculateLcy(["#ap_tax_amount_ccy", "#ap_vat_amount_ccy",
                    "#ap_security_deposit_amount_ccy",
                    "#ap_extra_security_deposit_amount_ccy",
                    "#ap_fine_forfeiture_ccy",
                    "#ap_preshipment_ccy",
                    "#ap_electricity_bill_ccy",
                    "#ap_other_charge_ccy"])
            }
            setPayable("ccy");
            setPayable("lcy");
        })

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

        function setPayable(subStr) {
            let tax = 0;
            let vat = 0;
            let securityAmount = 0;
            let invoiceAmount = 0;
            let deduction = 0;
            let extraSecurityAmount = 0;
            let fineForfeiture = 0;
            let preshipment = 0;
            let electricityBill = 0;
            let otherCharge = 0;


            invoiceAmount = (nullEmptyUndefinedChecked($("#ap_invoice_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_invoice_amount_" + subStr).val()));
            tax = (nullEmptyUndefinedChecked($("#ap_tax_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_tax_amount_" + subStr).val()));
            vat = (nullEmptyUndefinedChecked($("#ap_vat_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_vat_amount_" + subStr).val()));
            securityAmount = (nullEmptyUndefinedChecked($("#ap_security_deposit_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_security_deposit_amount_" + subStr).val()));
            extraSecurityAmount = (nullEmptyUndefinedChecked($("#ap_extra_security_deposit_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_extra_security_deposit_amount_" + subStr).val()));
            /*fineForfeiture = (nullEmptyUndefinedChecked($("#ap_fine_forfeiture_" + subStr).val()) ? 0 : parseFloat($("#ap_fine_forfeiture_" + subStr).val()));
            preshipment = (nullEmptyUndefinedChecked($("#ap_preshipment_" + subStr).val()) ? 0 : parseFloat($("#ap_preshipment_" + subStr).val()));
            electricityBill = (nullEmptyUndefinedChecked($("#ap_electricity_bill_" + subStr).val()) ? 0 : parseFloat($("#ap_electricity_bill_" + subStr).val()));*/
            addAmount = (nullEmptyUndefinedChecked($("#ap_total_add_amount_" + subStr).val()) ? 0 : parseFloat($("#ap_total_add_amount_" + subStr).val()));

            deduction = tax + vat + securityAmount + extraSecurityAmount + addAmount;
            if ((invoiceAmount !== "") && (deduction < invoiceAmount)) {
                $("#ap_payable_amount_" + subStr).val((invoiceAmount - deduction).toFixed(2));
            } else {
                $("#ap_payable_amount_" + subStr).val("0");
            }
        }

        $("#ap_payment_terms").on('change', function () {
            //setPaymentDueDate($(this).find(":selected").data('termdate'));
            let postingDate = $("#posting_date >input").val();
            let paymentTerm = $("#ap_payment_terms").find(':selected').data('termdate');

            if (!nullEmptyUndefinedChecked(postingDate)) {
                if (!nullEmptyUndefinedChecked(paymentTerm)) {

                    let newData = moment(postingDate, "DD-MM-YYYY").add(paymentTerm, 'days').format("DD-MM-YYYY");
                    $("#ap_payment_due_date_field").val(newData);

                } else {
                    $("#ap_payment_due_date_field").val(postingDate);
                }
            } else {
                $("#ap_payment_terms").select2("val", "");
                if (!nullEmptyUndefinedChecked($("#period :selected").val())) {
                    $("#posting_date").focus();
                    $('html, body').animate({scrollTop: ($("#posting_date").offset().top - 400)}, 2000);
                    $("#posting_date").notify("Set posting date", "info");
                }
            }
        });

        /*
        * Vendor search starts from here
        * */
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
        /*** Add this section start -Pavel: 23-03-22 ***/
        /*$("#ap_switch_pay_vendor_search").on("click", function () {  //Block this Pavel-28-08-22
            let vendorId = $("#ap_switch_pay_vendor_id").val();
            let invoiceType = $("#ap_invoice_type").val();

            $('#ap_switch_pay_vendor_search').val('{{--{{\App\Enums\YesNoFlag::YES}}--}}');

            if (!nullEmptyUndefinedChecked(vendorId)) {
                getSwitchPaymentVendorDetail(vendorId);
            } else {

                if (invoiceType == '{{--{{\App\Enums\Ap\LApInvoiceType::SWC_ADJ_PRO_CON_SUPP}}--}}') {
                    $("#search_vendor_type").val('{{--{{\App\Enums\Ap\VendorType::EXTERNAL}}--}}').parent('div').addClass('make-readonly');
                    $("#search_vendor_category").val('{{--{{\App\Enums\Ap\LApVendorCategory::SUPP_CONT}}--}}').parent('div').addClass('make-readonly');
                } else {
                    $("#search_vendor_type").val('').parent('div').removeClass('make-readonly');
                    $("#search_vendor_category").val('').parent('div').removeClass('make-readonly');
                }
                reloadVendorListTable();
                $("#vendorListModal").modal('show');
            }
        });*/
        /*** Add this section end -Pavel: 23-03-22 ***/

        /*** Add this section start -Pavel: 07-07-22 ***/


        /*
        * Customer search starts from here
        * */


        /*
        * customer search ends here
        * */

        /*** Add this section end -Pavel: 07-07-22 ***/

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

        $("#ap_reset_vendor_balance_field").on("click", function () {
            resetField(['#ap_search_vendor_id', '#ap_search_vendor_name', '#ap_search_vendor_category', '#ap_bills_payable', '#ap_prepayments', '#ap_security_deposits', '#ap_advance', '#ap_imprest_cash', '#ap_revolving_cash']);
        });

        function emptyTaxVatPayableDropdown() {
            $("#party_name_for_tax").html('<option value="">Party Name for Tax Payable</option>');
            $("#party_name_for_vat").html('<option value="">Party Name for Vat Payable</option>');
        }

        $(document).on('click', '.vendorSelect', function () {
            /*** Add this if else condition -Pavel: 23-03-22 ***/
            /*if (($('#ap_switch_pay_vendor_search').val()) == '{{--{{\App\Enums\YesNoFlag::YES}}--}}') { //Block this Pavel-28-08-22
                getSwitchPaymentVendorDetail($(this).data('vendor'));
            } else*/
            if (($('#ap_add_vendor_search').val()) == '{{\App\Enums\YesNoFlag::YES}}') {  //Add -Pavel: 07-07-22
                getAddVendorDetail($(this).data('vendor'), $("#ap_add_party_sub_ledger"), setAddVendorInfo);
                $('#ap_add_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}')
            } else if ($("#ap_dist_vendor_search").val() == '{{\App\Enums\YesNoFlag::YES}}') {
                getAddVendorDetail($(this).data('vendor'), $("#ap_dist_party_sub_ledger"), setDistVendorInfo);
                $('#ap_dist_vendor_search').val('{{\App\Enums\YesNoFlag::NO}}')
            } else {
                getVendorDetail($(this).data('vendor'), setPartyLedgerVendorInfo);
            }
        });

        function setPartyLedgerVendorInfo(d) {
            if ($.isEmptyObject(d.vendor)) {
                $("#ap_vendor_id").notify("Vendor id not found", "error");
                resetField(['#ap_vendor_id', '#ap_vendor_name', '#ap_vendor_category', '#party_name_for_tax', '#party_name_for_vat', '#bl_bills_payable', '#bl_provision_exp', '#bl_security_dep_pay', '#bl_os_advances', '#bl_os_prepayments', '#bl_os_imp_rev']);
                enableDisablePoCheck(0);
                emptyTaxVatPayableDropdown();
            } else {
                $('#ap_vendor_id').val(d.vendor.vendor_id);
                $('#ap_vendor_name').val(d.vendor.vendor_name);
                $('#ap_vendor_category').val(d.vendor.vendor_category.vendor_category_name);
                enableDisablePoCheck(1)

                $("#party_name_for_tax").html(d.taxParty);
                $("#party_name_for_vat").html(d.vatParty);

                /*** Add this section Pavel: 21-03-22 ***/
                $('#bl_bills_payable').val(d.vendorBalance.bills_payable);
                $('#bl_provision_exp').val(d.vendorBalance.provisional_expense_payable);
                $('#bl_security_dep_pay').val(d.vendorBalance.security_deposit_payable);
                $('#bl_os_advances').val(d.vendorBalance.os_advance);
                $('#bl_os_prepayments').val(d.vendorBalance.os_prepayment);
                $('#bl_os_imp_rev').val(d.vendorBalance.os_imprest_revolving_cash);

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
                /*
                 0=> D/C flag    (D=line C; C=line D)
                 1=> vendor type
                 2=> vendor category
                 3=> Source Allow Flag   (0=Tax, VAT, Security Disable; 1= Enable)
                 4=> distribution line (0 = enable)/(1=disable)
                */
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

        /*** Add this section start -Pavel: 07-07-22 ***/

        /*** Add this section end -Pavel: 07-07-22 ***/

        /*** Add this section start -Pavel: 23-03-22 ***/
        /*function getSwitchPaymentVendorDetail(vendor_id) {                 //Block this Pavel-28-08-22
            let vendorType = '{{--{{\App\Enums\Ap\VendorType::EXTERNAL}}--}}';
            let vendorCategory = '{{--{{\App\Enums\Ap\LApVendorCategory::SUPP_CONT}}--}}';
            let invoiceType = $("#ap_invoice_type :selected").val();

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-details',
                data: {
                    vendorId: vendor_id,
                    vendorType: vendorType,
                    vendorCategory: vendorCategory,
                    invoiceType: invoiceType
                }
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.vendor)) {
                    $("#ap_switch_pay_vendor_id").notify("Payment Vendor id not found", "error");
                    resetField(['#ap_switch_pay_vendor_id', '#ap_switch_pay_vendor_name', '#ap_switch_pay_vendor_category']);
                } else {
                    $('#ap_switch_pay_vendor_id').val(d.vendor.vendor_id);
                    $('#ap_switch_pay_vendor_name').val(d.vendor.vendor_name);
                    $('#ap_switch_pay_vendor_category').val(d.vendor.vendor_category.vendor_category_name);

                }
                $("#vendorListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }*/

        /*** Add this section end -Pavel: 23-03-22 ***/

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

        $(document).on('shown.bs.modal', '#vendorListModal', function () {
            vendorTable.columns.adjust().draw();
        });

        /*
        * Vendor search ends here
        * */

        /*
        * PO search starts here
        * */
        $("#search_po").on('click', function () {
            let poNumber = $("#po_number").val();
            //for update purpose
            //let invoiceID = $("#invoice_id").val();
            $("#modal_vendor_name").html("<option value='" + $("#ap_vendor_id").val() + "'>" + $("#ap_vendor_name").val() + "</option>")
            $("#poListModal").modal('show');
        });
        let poSearchTable = $('#poSearchTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-payable/ajax/po-search-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#poSearchTable').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "po_number"},
                {"data": "po_date"},
                {"data": "invoice_no"},
                {"data": "invoice_date"},
                {"data": "invoice_amount"},
                {"data": "action", "orderable": false}
            ], createdRow: function (row, data, index) {
                $('td', row).eq(5).addClass("text-right");
            }
        });

        function reloadPoListTable() {
            $('#poSearchTable').data("dt_params", {
                vendor: $('#modal_vendor_name :selected').val(),
                purchaseOrder: $('#modal_purchase_order_no').val(),
                purchaseDate: $('#modal_po_date_field').val(),
                invoiceNo: $('#modal_invoice_no').val(),
                invoiceDate: $('#modal_invoice_date_field').val(),
            }).DataTable().draw();
        }

        $(document).on('submit', "#po_search_form", function (e) {
            e.preventDefault();
            reloadPoListTable();
            applyRemovePOresultFieldsEffect(0);
        });
        $(document).on('click', ".poSelect", function () {
            let poInfo = $(this).data('po');
            let poInfoArray = poInfo.split("#");
            /*
                0=> purchase rcv mst
                1=> po number
                2=> po date
                3=> invoice no
                4=> invoice date
                5=> invoice amount
             */
            applyRemovePOresultFieldsEffect(1)

            $("#po_master_id").val(poInfoArray[0]);
            $("#ap_purchase_order_no").val(poInfoArray[1]);
            $("#ap_purchase_order_date").val(poInfoArray[2]);
            $("#document_number").val(poInfoArray[3]);
            $("#document_date_field").val(poInfoArray[4]);
            $("#ap_invoice_amount_ccy").val(poInfoArray[5]);

            $("#poListModal").modal('hide');

            setPayable('ccy');
            setPayable('lcy');

            calculateLcy(['#ap_invoice_amount_ccy', '#ap_payable_amount_ccy']);
        })

        $("#department").on('change', function () {
            //resetField(["#b_booking_id"]); // block this sec -Pavel: 24-03-22
            resetField(["#b_head_id"]); // Add this sec -Pavel: 24-03-22
            resetBudgetField();
            resetBudgetHeadBookingTables();
            $("#budget_department").select2().val($(this).select2().val()).trigger('change');
        });

        /*
        * PO search ends here
        * */

        /*
        * Budget search starts from here
        * */

        /*
        * Budget Head search starts from here
        * */
        /*let budgetTable = $('#budget_head_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            deferLoading: 1,
            ajax: {
                url: APP_URL + '/account-payable/ajax/budget-head-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{--{{ csrf_token() }}--}}'
                },
                data: function (params) {
                    /!* params.department = $('#department :selected').val();
                     params.calendar = $('#fiscal_year :selected').val();
                     params.nameCode = $('#s_budget_head_name_code').val();*!/
                    var dt_params = $('#budget_head_list').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                {"data": 'budget_head_id', "name": 'budget_head_id'},
                {"data": "budget_head_name"},
                {"data": "sub_category"},
                {"data": "category_name"},
                {"data": "budget_type"},
                {"data": "action", "orderable": false}
            ],
        });*/
        /*** Add this sec start -Pavel: 30-03-22 ***/
        $("#ap_without_budget_info").on("click", function () {

            if ($(this).prop('checked')) {
                $("#b_booking_search").prop('disabled', true);
                resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);

            } else {
                $("#b_booking_search").prop('disabled', false);

            }

        });
        /*** Add this sec end -Pavel: 30-03-22 ***/
        $("#b_booking_search").on("click", function () {
            //let bookingId = $('#b_booking_id').val();
            let department = $('#budget_department :selected').val();
            let calendar = $('#th_fiscal_year :selected').val(); // $('#fiscal_year :selected').val();

            //let vendorId = $('#ap_vendor_id').val(); // block this sec -Pavel: 24-03-22

            resetBudgetField();
            //resetBudgetHeadBookingTables();

            // if ( !nullEmptyUndefinedChecked(department) && !nullEmptyUndefinedChecked(vendorId) ) { // block this sec -Pavel: 24-03-22
            if (!nullEmptyUndefinedChecked(department)) {
                /*if (!nullEmptyUndefinedChecked(bookingId)) {
                    getBudgetBookingDetailInfo(bookingId, department, calendar);
                } else {*/
                //$('#b_booking_id').val("") //Remove this line when open if condition // block this sec -Pavel: 24-03-22

                $('#b_head_id').val("") // Add this sec -Pavel: 24-03-22
                reloadBudgetListTable();

                $("#s_fiscal_year").val($("#th_fiscal_year :selected").text().trim()); //val($("#fiscal_year").text().trim());
                $("#s_department").val($("#budget_department :selected").text());
                //$("#s_part_vendor_id").val($("#ap_vendor_id").val());
                //$("#s_budget_head_name_code").val('');

                $("#budget_booking_list").data("dt_params", {
                    "department": $('#budget_department :selected').val(),
                    "calendar": $('#th_fiscal_year :selected').val(), //$('#fiscal_year :selected').val(),
                    "nameCode": $('#s_budget_head_name_code').val(),
                    "vendorId": $('#ap_vendor_id').val()
                }).DataTable().draw();

                $("#budgetListModal").modal('show');
                /*}*/
            } else {

                /*** Block this sec start -Pavel: 24-03-22 ***/
                /*resetField(['#b_booking_id']);

                if (nullEmptyUndefinedChecked(vendorId)) {
                    $("#ap_vendor_id").focus();
                    $('html, body').animate({scrollTop: ($("#ap_vendor_id").offset().top - 200)}, 2000);
                    $("#ap_vendor_id").notify("Please Add Vendor ID.", {position: 'right'});
                } else if ( nullEmptyUndefinedChecked(department) ){
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});
                }*/
                /*** Block this sec end -Pavel: 24-03-22 ***/

                /*** Add this sec start -Pavel: 24-03-22 ***/
                resetField(['#b_head_id']);
                if (nullEmptyUndefinedChecked(department)) {
                    $("#budget_department").focus();
                    $('html, body').animate({scrollTop: ($("#budget_department").offset().top - 400)}, 2000);
                    $("#budget_department").notify("Select Department First.", {position: 'left'});
                }
                /*** Add this sec end -Pavel: 24-03-22 ***/

            }

        })
        //This portion is ommited for vendor id introduce in invoice form
        /*$(document).on('click', '.budgetHeadSelect', function () {
            $('#budget_booking_list').data('dt_params', {
                "budget_head_id": $(this).data('headid'),
                "department": $('#department :selected').val(),
                "calendar": $('#fiscal_year :selected').val()
            }).DataTable().draw();

            $("#budget_head_list").focus();
            $("#budgetListModal .modal-body").animate({scrollTop: $('#budget_head_list').offset().top + 100}, 2000);
        });*/
        $(document).on('submit', '#booking_search_form', function (e) {
            e.preventDefault();
            //resetBudgetHeadBookingTables();

            /*$("#budget_head_list").data("dt_params", {*/
            $("#budget_booking_list").data("dt_params", {
                "department": $('#budget_department :selected').val(),
                "calendar": $('#th_fiscal_year :selected').val(), //$('#fiscal_year :selected').val(),
                "nameCode": $('#s_budget_head_name_code').val(),
                "vendorId": $('#ap_vendor_id').val()
            }).DataTable().draw();
        })
        let budgetBookingTable = $('#budget_booking_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-payable/ajax/budget-booking-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    let dt_params = $("#budget_booking_list").data('dt_params');
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            /*** Block this sec start  -Pavel: 24-03-22 ***/
            /*"columns": [
                {"data": 'booking_id', "name": 'booking_id'},
                {"data": "booking_date"},
                {"data": "budget_booking_amount"},
                {"data": "budget_head_name"},
                {"data": "budget_category_name"},
                {"data": "budget_type_name"},
                {"data": "action", "orderable": false}
            ],*/
            /*** Block this sec end  -Pavel: 24-03-22 ***/

            /*** Add this sec start  -Pavel: 24-03-22 ***/
            "columns": [
                {"data": 'budget_head_id', "name": 'budget_head_id'},
                {"data": "budget_head_name"},
                {"data": "category_name"},
                {"data": "budget_type"},
                {"data": "budget_booking_amt"},
                {"data": "budget_utilized_amt"},
                {"data": "available_amount"},
                {"data": "action", "orderable": false}
            ],
            /*** Add this sec start  -Pavel: 24-03-22 ***/
        });
        $(document).on('click', '.budgetSelect', function () {

            //let bookingId = $(this).data('bookingid'); /*** Block this sec -Pavel: 24-03-22 ***/
            let budgetHeadId = $(this).data('budget-head-id');
            /*** Add this sec -Pavel: 24-03-22 ***/
            let department = $('#budget_department :selected').val();
            let calendar = $('#th_fiscal_year :selected').val();  //$('#fiscal_year :selected').val();
            //console.log(budgetHeadId, this);
            //getBudgetBookingDetailInfo(bookingId, department, calendar) /*** Block this sec -Pavel: 24-03-22 ***/
            getBudgetBookingDetailInfo(budgetHeadId, department, calendar)
        });


        function getBudgetBookingDetailInfo(budgetHeadId, department, calendar) {
            /*var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                data: {budget_booking_id: bookingId, department: department, calendar: calendar}
            });*/

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                data: {budget_head_id: budgetHeadId, department: department, calendar: calendar}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.data)) {
                    //$("#b_booking_id").notify("Budget Booking ID Not Found", "error"); // block this sec -Pavel: 24-03-22
                    /*resetField(['#b_head_id',
                        '#b_date', '#b_amt','#b_available_amt', '#b_head_name', // block this sec -Pavel: 24-03-22
                        '#b_sub_category', '#b_category', '#b_type']);*/

                    /*** Add this sec start  -Pavel: 24-03-22 ***/
                    $("#b_head_id").notify("Budget Head ID Not Found", "error");
                    resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
                    /*** Add this sec end  -Pavel: 24-03-22 ***/

                } else {
                    //$('#b_booking_id').val(d.data.budget_booking_id); //block this sec -Pavel: 24-03-22
                    $('#b_head_id').val(d.data.budget_head_id);
                    //$('#b_date').val(d.data.budget_booking_date); // block this sec -Pavel: 24-03-22
                    $('#b_amt').val(d.data.budget_booking_amt);
                    $('#b_head_name').val(d.data.budget_head_name);
                    $('#b_sub_category').val(d.data.sub_category_name);
                    $('#b_category').val(d.data.category_name);
                    $('#b_type').val(d.data.budget_type);
                    $('#b_utilized_amt').val(d.data.budget_utilized_amt); //Add this sec -Pavel: 24-03-22
                    $('#b_available_amt').val(d.data.available_amount);
                }
                $("#budgetListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        function reloadBudgetListTable() {
            budgetBookingTable.draw();
            //budgetTable.draw();
        }

        function resetBudgetField() {
            /*resetField(['#b_head_id',
                '#b_date', '#b_amt','#b_available_amt', '#b_head_name',  //Block this sec -Pavel: 24-03-22
                '#b_sub_category', '#b_category', '#b_type']);*/

            //Add this sec -Pavel: 24-03-22
            resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt',
                '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
        }

        function resetBudgetHeadBookingTables() {
            /*$("#budget_head_list").data("dt_params", {
                "department": "",
                "calendar": "",
                "nameCode": ""
            }).DataTable().draw();*/
            $('#budget_booking_list').data('dt_params', {
                "budget_head_id": "",
                "department": "",
                "calendar": "",
                "vendorId": ""
            }).DataTable().draw();
        }


        $("#ap_calculate_tax_vat").on("click", function () {
            //resetTaxVatSecField();
            if ($(this).prop('checked')) {
                enableDisableTaxVatSecurityExSecPercentageFieldOnCheckbox(1);
            } else {
                enableDisableTaxVatSecurityExSecPercentageFieldOnCheckbox(0);
                //$("#ap_invoice_type").trigger('change');
            }
        });
        $("#ap_inclusive_tax_vat").on("click", function () {
            enableDisableTaxVatFieldOnInclusive($(this).prop('checked'));
        })

        function enableDisableTaxVatFieldOnInclusive(inclusive_status) {
            resetField([
                "#ap_tax_amount_ccy_percentage",
                "#ap_vat_amount_ccy_percentage",
                "#ap_tax_amount_ccy",
                "#ap_tax_amount_lcy",
                "#ap_vat_amount_ccy",
                "#ap_vat_amount_lcy",
            ]);

            let taxVatCalcCheckStatus = $("#ap_calculate_tax_vat").prop('checked');
            //command 1 = inclusive checked, make field disabled
            //command 0 = inclusive unchecked, make field enabled depending on taxVatCalcCheckStatus

            if ((taxVatCalcCheckStatus == false)) {
                if (inclusive_status == true) {
                    $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");

                    $("#ap_tax_amount_ccy").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy").attr("readonly", "readonly");

                    $("#party_name_for_tax").val('').addClass("make-readonly-bg");
                    $("#party_name_for_vat").val('').addClass("make-readonly-bg");
                } else {
                    $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");

                    $("#ap_tax_amount_ccy").removeAttr("readonly");
                    $("#ap_vat_amount_ccy").removeAttr("readonly");

                    $("#party_name_for_tax").removeClass("make-readonly-bg");
                    $("#party_name_for_vat").removeClass("make-readonly-bg");
                }
            } else {
                if (inclusive_status == true) {
                    $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");

                    $("#ap_tax_amount_ccy").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy").attr("readonly", "readonly");

                    $("#party_name_for_tax").val('').addClass("make-readonly-bg");
                    $("#party_name_for_vat").val('').addClass("make-readonly-bg");
                } else {
                    $("#ap_tax_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_vat_amount_ccy_percentage").removeAttr("readonly");

                    $("#ap_tax_amount_ccy").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy").attr("readonly", "readonly");

                    $("#party_name_for_tax").removeClass("make-readonly-bg");
                    $("#party_name_for_vat").removeClass("make-readonly-bg");
                }
            }

            /*** Add this section start -Pavel: 22-03-22 ***/
            if (inclusive_status == false) {
                $("#ap_calculate_tax_vat").prop('disabled', false);

                $('#party_name_for_tax').children().prop('selected', true);
                $('#party_name_for_vat').children().prop('selected', true);

            } else {
                $("#ap_calculate_tax_vat").prop('checked', false);
                $("#ap_calculate_tax_vat").prop('disabled', true);
            }
            /*** Add this section end -Pavel: 22-03-22 ***/

            setPayable("ccy");
            setPayable("lcy");
        }

        function enableDisableTaxVatSecurityExSecPercentageFieldOnCheckbox(status) {
            resetField(["#ap_tax_amount_ccy_percentage",
                "#ap_vat_amount_ccy_percentage",
                "#ap_security_deposit_amount_ccy_percentage",
                "#ap_extra_security_deposit_amount_ccy_percentage",
                "#ap_tax_amount_ccy",
                "#ap_tax_amount_lcy",
                "#ap_vat_amount_ccy",
                "#ap_vat_amount_lcy",
                "#ap_security_deposit_amount_ccy",
                "#ap_security_deposit_amount_lcy",
                "#ap_extra_security_deposit_amount_ccy",
                "#ap_extra_security_deposit_amount_lcy"
            ]);

            let inclusiveCheckStatus = $("#ap_inclusive_tax_vat").prop('checked');
            if (inclusiveCheckStatus == true) {
                $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ap_tax_amount_ccy").attr("readonly", "readonly");
                $("#ap_vat_amount_ccy").attr("readonly", "readonly");
                $("#party_name_for_tax").val('').addClass("make-readonly-bg");
                $("#party_name_for_vat").val('').addClass("make-readonly-bg");

                if (status == 0) {
                    $("#ap_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_extra_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_security_deposit_amount_ccy").removeAttr("readonly");
                    $("#ap_extra_security_deposit_amount_ccy").removeAttr("readonly");
                } else {
                    $("#ap_security_deposit_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_extra_security_deposit_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_extra_security_deposit_amount_ccy").attr("readonly", "readonly");
                    $("#ap_security_deposit_amount_ccy").attr("readonly", "readonly");
                }
            } else {
                $("#party_name_for_tax").removeClass("make-readonly-bg");
                $("#party_name_for_vat").removeClass("make-readonly-bg");

                if (status == 0) {
                    $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");
                    $("#ap_extra_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");

                    $("#ap_tax_amount_ccy").removeAttr("readonly");
                    $("#ap_vat_amount_ccy").removeAttr("readonly");
                    $("#ap_security_deposit_amount_ccy").removeAttr("readonly");
                    $("#ap_extra_security_deposit_amount_ccy").removeAttr("readonly");
                } else {
                    $("#ap_tax_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_vat_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_security_deposit_amount_ccy_percentage").removeAttr("readonly");
                    $("#ap_extra_security_deposit_amount_ccy_percentage").removeAttr("readonly");

                    $("#ap_tax_amount_ccy").attr("readonly", "readonly");
                    $("#ap_vat_amount_ccy").attr("readonly", "readonly");
                    $("#ap_extra_security_deposit_amount_ccy").attr("readonly", "readonly");
                    $("#ap_security_deposit_amount_ccy").attr("readonly", "readonly");
                }
            }

            /*** Add this section start -Pavel: 22-03-22 ***/
            if (status == 0) {
                $("#ap_inclusive_tax_vat").prop('disabled', false);
            } else {
                $("#ap_inclusive_tax_vat").prop('checked', false);
                $("#ap_inclusive_tax_vat").prop('disabled', true);
            }
            /*** Add this section end -Pavel: 22-03-22 ***/

            setPayable("ccy");
            setPayable("lcy");
        }

        function enableDisableTaxVatSecurityExSecPercentageField(status) {
            resetField(["#ap_tax_amount_ccy_percentage",
                "#ap_vat_amount_ccy_percentage",
                "#ap_security_deposit_amount_ccy_percentage",
                "#ap_extra_security_deposit_amount_ccy_percentage",
                "#ap_tax_amount_ccy",
                "#ap_tax_amount_lcy",
                "#ap_vat_amount_ccy",
                "#ap_vat_amount_lcy",
                "#ap_security_deposit_amount_ccy",
                "#ap_security_deposit_amount_lcy",
                "#ap_extra_security_deposit_amount_ccy",
                "#ap_fine_forfeiture_ccy",
                "#ap_fine_forfeiture_lcy",
                "#ap_preshipment_ccy",
                "#ap_preshipment_lcy",
                "#ap_electricity_bill_ccy",
                "#ap_electricity_bill_lcy",
                "#ap_other_charge_ccy",
                "#ap_other_charge_lcy",
                "#ap_total_add_amount_ccy",
                "#ap_total_add_amount_lcy",
                "#ap_amount_word_ccy"]);

            if (status == 0) {
                $("#ap_tax_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ap_vat_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ap_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ap_extra_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");

                /*$("#ap_tax_amount_ccy").removeAttr("readonly");
                $("#ap_vat_amount_ccy").removeAttr("readonly");
                $("#ap_security_deposit_amount_ccy").removeAttr("readonly");*/
            } else {
                $("#ap_tax_amount_ccy_percentage").removeAttr("readonly");
                $("#ap_vat_amount_ccy_percentage").removeAttr("readonly");
                $("#ap_security_deposit_amount_ccy_percentage").removeAttr("readonly");
                $("#ap_extra_security_deposit_amount_ccy_percentage").removeAttr("readonly");

                /*$("#ap_tax_amount_ccy").attr("readonly", "readonly");
                $("#ap_vat_amount_ccy").attr("readonly", "readonly");
                $("#ap_security_deposit_amount_ccy").attr("readonly", "readonly");*/
            }

            setPayable("ccy");
            setPayable("lcy");
        }

        $(document).on("keyup", "#ap_tax_amount_ccy_percentage, #ap_vat_amount_ccy_percentage, #ap_security_deposit_amount_ccy_percentage, #ap_extra_security_deposit_amount_ccy_percentage", function () {
            if (parseFloat($(this).val()) > 100) {
                $(this).val(100).notify("Enter value between 0-100", "info");
            }
            setCalculatedTaxVatDeposit(this);
        });

        function setCalculatedTaxVatDeposit(selector) {
            let percentege = $(selector).val();
            let invoiceAmountLcy = parseFloat((nullEmptyUndefinedChecked($("#ap_invoice_amount_ccy").val())) ? 0 : $("#ap_invoice_amount_ccy").val());
            let amountOnPercent = (((invoiceAmountLcy + Number.EPSILON) * percentege) / 100).toFixed(2);
            $(selector).parent().next('div').children('input[type=text]').val(amountOnPercent);
            calculateLcy(["#" + $(selector).parent().next('div').children('input[type=text]').attr('id')]);

            setPayable('ccy');
            setPayable('lcy');
        }

        function applyRemovePOresultFieldsEffect(key) {
            if (key == 1) {
                $("#po_master_id").val("");
                //$("#document_number").val("").attr('readonly', 'readonly');
                //$("#document_date_field").val("").attr('readonly', 'readonly');
                $("#document_date").addClass('make-readonly');
                $("#ap_invoice_amount_ccy").val("").attr('readonly', 'readonly');
                $("#ap_invoice_amount_lcy").val("");
                $("#ap_payable_amount_ccy").val("");
                $("#ap_payable_amount_lcy").val("");

            } else {
                $("#po_master_id").val("");
                //$("#document_number").val("").removeAttr('readonly', 'readonly');
                //$("#document_date_field").val("").removeAttr('readonly', 'readonly');
                $("#document_date").removeClass('make-readonly');
                $("#ap_invoice_amount_ccy").val("").removeAttr('readonly', 'readonly');
                $("#ap_invoice_amount_lcy").val("");
                $("#ap_payable_amount_ccy").val("");
                $("#ap_payable_amount_lcy").val("");
            }
        }

        $("#ap_hold_all_payment").on('click', function () {
            if ($(this).prop('checked')) {
                $("#ap_hold_all_payment_reason").removeAttr('readonly');
                $("#ap_hold_all_payment_reason").attr('required', 'required');
                $("#ap_hold_all_payment_reason").parent().prev('label').addClass('required');
            } else {
                $("#ap_hold_all_payment_reason").attr('readonly', 'readonly').val("");
                $("#ap_hold_all_payment_reason").removeAttr('required', 'required');
                $("#ap_hold_all_payment_reason").parent().prev('label').removeClass('required');
            }
        });

        $("#ap_add_amount_ccy").on("keyup", function () {
            let c_amount_ccy_keyup = parseFloat($(this).val());
            if (!is_negative(c_amount_ccy_keyup) && c_amount_ccy_keyup != 0) {
                let c_exchange_rate_get = parseFloat($("#ap_exchange_rate").val());
                //$('#c_amount_ccy').val(c_amount_ccy_keyup);

                if (c_amount_ccy_keyup && c_exchange_rate_get) {
                    let lcy = (c_amount_ccy_keyup * c_exchange_rate_get);
                    $('#ap_add_amount_lcy').val(lcy);
                } else {
                    $('#ap_add_amount_lcy').val('0');
                }
            } else {
                $('#ap_add_amount_ccy').val('0');
                $('#ap_add_amount_lcy').val('0');
            }
        });
        $(".additional-account-btn").on('click', function () {
            $(".additional-account-area").removeClass('d-none');
            $(".additional-account-btn").html('<i class="bx bxs-add-to-queue"></i>');
        })

        $("#ap_add_search_account").on("click", function () {
            let accId = $("#ap_add_account_id").val();
            let costCenterDpt = $('#department :selected').val(); //Add costCenterDpt Part :pavel-31-01-22
            let apInvoiceType = $('#ap_invoice_type :selected').val();
            $('#acc_type').val(''); //Add Reset Value :pavel-29-03-22
            $('#acc_name_code').val(''); //Add Reset Value :pavel-29-03-22
            $("#acc_department").val($("#department :selected").text());

            if (!nullEmptyUndefinedChecked(accId)) {
                /*** BLOCK & ADD 1,2,3,4,5 SEC.PAVEL-29-03-22, #PREVIOUS ALLOW ACC-TYPE INCOME & LIABILITY, #NOW ALLOW ALL ACC TYPE ***/

                /*** BLOCK 1 SEC START PAVEL-29-03-22 ***/
                /*getAccountDetail(accId, true, [{{--{{\App\Enums\Common\GlCoaParams::LIABILITY}}, {{\App\Enums\Common\GlCoaParams::INCOME}}--}}]);*/
                /*** BLOCK 1 SEC END PAVEL-29-03-22 ***/

                /*** ADD 2 SEC START PAVEL-29-03-22 ***/
                getAccountDetail(accId, true, [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}], setAddAccountInfo);
                /*** ADD 2 SEC END PAVEL-29-03-22 ***/
            } else {
                //Add IF Part :pavel-31-01-22
                if (nullEmptyUndefinedChecked(costCenterDpt)) {
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});


                    //$(".additional-account-modal").modal('hide');
                    $(".additional-account-area").addClass('d-none');

                }
                /**
                 * Add (Additional Account) (Problem: Invoice Type must be selected). REF# email
                 * Logic added:04-04-2022
                 * **/
                else if (nullEmptyUndefinedChecked(apInvoiceType)) {
                    $("#ap_invoice_type").focus();
                    $('html, body').animate({scrollTop: ($("#ap_invoice_type").offset().top - 400)}, 2000);
                    $("#ap_invoice_type").notify("Select Invoice Type First.", {position: 'left'});

                    //$(".additional-account-modal").modal('hide');
                    $(".additional-account-area").addClass('d-none');
                } else {
                    //$("#acc_type option[value='4']").remove();
                    //$("#acc_type option[value='3']").remove();

                    /*** BLOCK 3 SEC START PAVEL-29-03-22 ***/
                    /*$("#acc_type option[value='{{--{{\App\Enums\Common\GlCoaParams::EXPENSE}}--}}'").remove();
                    $("#acc_type option[value='{{--{{\App\Enums\Common\GlCoaParams::ASSET}}--}}'").remove();


                    if ($("#acc_type option[value='{{--{{\App\Enums\Common\GlCoaParams::INCOME}}--}}']").length == 0) {
                        $('#acc_type').append($('<option>', {
                            value: {{--{{\App\Enums\Common\GlCoaParams::INCOME}}--}},
                            text: '{{--{{\App\Enums\Common\GlCoaParams::INCOME_KEY}}--}}'
                        }));
                    }

                    if ($("#acc_type option[value='{{--{{\App\Enums\Common\GlCoaParams::LIABILITY}}--}}']").length == 0) {
                        $('#acc_type').append($('<option>', {
                            value: {{--{{\App\Enums\Common\GlCoaParams::LIABILITY}}--}},
                            text: '{{--{{\App\Enums\Common\GlCoaParams::LIABILITY_KEY}--}}}'
                        }));
                    }*/
                    /*** BLOCK 3 SEC END PAVEL-29-03-22 ***/

                    $("#forAddAcc").remove();
                    $("#allowedGL").remove();
                    $("#acc_search_form").append('<input type="hidden" name="forAddAcc" id="forAddAcc" value="true">');

                    /*** BLOCK 4 SEC START PAVEL-29-03-22 ***/
                    /*$("#acc_search_form").append('<input type="hidden" name="allowedGL" id="allowedGL" value="[{{--{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}}--}}]">');*/
                    /*** BLOCK 4 SEC END PAVEL-29-03-22 ***/

                    /*** ADD 5 SEC START PAVEL-29-03-22 ***/
                    $("#acc_search_form").append('<input type="hidden" name="allowedGL" id="allowedGL" value="[{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}]">');
                    /*** ADD 5 SEC END PAVEL-29-03-22 ***/

                    $("#accountListModal").modal('show');
                    $("#callbackVar").val('setAddAccountInfo');
                    accountTable.draw();
                    selectOptionOrdering('#acc_type'); //Add Part :pavel-07-03-22
                }
            }
        });
        $("#ap_add_cancel_account").on("click", function () {
            $("#ap_add_account_table >tbody >tr").remove();
            $("#ap_total_add_amount_ccy").val('');
            $("#ap_total_add_amount_lcy").val('');
            $(".additional-account-area").addClass('d-none');
            setPayable('ccy');
            setPayable('lcy');
            $(".additional-account-btn").html('<i class="bx bxs-add-to-queue"></i>');

        })

        $(".minimizeAdditionalArea").on("click", function () {
            let count = $("#ap_add_account_table >tbody").children("tr").length;
            if (count > 0) {
                $(".additional-account-area").addClass('d-none');
                $(".additional-account-btn").html('<i class="bx bx-show-alt"></i>');
            } else {
                $("#ap_total_add_amount_ccy").notify('You haven\'t added any accounts yet to minimize.',
                    {
                        position: "top",
                        className: 'error',
                        showDuration: 500,
                    });
            }

        })

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

        addAddAccLineRow = function (selector) {
            if (!nullEmptyUndefinedChecked($("#ap_invoice_type").find(':selected').val())) {
                let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");

                let invoiceArray = invoiceParams.split("#");
                /*
                0=> D/R flag
                1=> vendor type
                2=> vendor category
                3=> Source Allow Flag
                4=> distribution line enable/disable
                 */

                if (fieldsAreSet(['#ap_add_amount_ccy', '#ap_add_account_id', '#ap_add_account_type', '#ap_add_account_name', '#ap_add_amount_lcy'])) {
                    if ($(selector).attr('data-type') == 'A') {
                        let count = $("#ap_add_account_table >tbody").children("tr").length;
                        let partyId = "";
                        let partyName = "";
                        let partySubLedger = "";
                        let category = "";
                        let moduleId = $("#ap_add_module_id").val();
                        let accountBalance = "";
                        let authorizedBalance = "";

                        if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                            partyId = $("#ar_add_customer_id").val();
                            partyName = $("#ar_add_customer_name").val();
                            partySubLedger = $("#ar_add_party_sub_ledger").val();
                            category = $("#ar_add_customer_category").val();
                            accountBalance = $("#ar_add_account_balance").val();
                            authorizedBalance = $("#ar_add_authorized_balance").val();
                        } else if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                            partyId = $("#ap_add_vendor_id").val();
                            partyName = $("#ap_add_vendor_name").val();
                            partySubLedger = $("#ap_add_party_sub_ledger").val();
                            category = $("#ap_add_vendor_category").val();
                            accountBalance = $("#ap_add_account_balance").val();
                            authorizedBalance = $("#ap_add_authorized_balance").val();
                        }

                        let html = '<tr>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" name="addLine[' + count + '][ap_add_account_code]" id="add_account_code' + count + '" class="form-control form-control-sm" value="' + $('#ap_add_account_id').val() + '" readonly/></td>\n' +
                            '<td style="padding: 4px">' +
                            '<input tabindex="-1" name="addLine[' + count + '][ap_add_account_name]" id="add_account_name' + count + '" class="form-control form-control-sm" value="' + $('#ap_add_account_name').val() + '" readonly/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_acc_type]" id="add_account_type' + count + '" value="' + $('#ap_add_account_type').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_party_sub_ledger]" id="add_party_sub_ledger' + count + '" value="' + partySubLedger + '"/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_vendor_category]" id="add_vendor_category' + count + '" value="' + category + '"/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_account_balance]" id="add_account_balance' + count + '" value="' + accountBalance + '"/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_authorized_balance]" id="add_authorized_balance' + count + '" value="' + authorizedBalance + '"/>' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_action_type]" id="add_action_type' + count + '" value="A" />' +
                            '<input tabindex="-1" type="hidden" name="addLine[' + count + '][ap_add_module_id]" id="add_module_id' + count + '" value="' + moduleId + '" />' +

                            '</td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm" name="addLine[' + count + '][ap_add_vendor_id]" id="add_vendor_id' + count + '" value="' + partyId + '" readonly></td>' +   //Add Pavel-07-07-22
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm" name="addLine[' + count + '][ap_add_vendor_name]" id="add_vendor_name' + count + '" value="' + partyName + '" readonly></td>' +   //Add Pavel-07-07-22
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align ccy" name="addLine[' + count + '][ap_add_amount_ccy]" id="add_ccy' + count + '" value="' + $('#ap_add_amount_ccy').val() + '" readonly></td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="addLine[' + count + '][ap_add_amount_lcy]" id="add_lcy' + count + '" value="' + $('#ap_add_amount_lcy').val() + '" readonly></td>\n' +
                            '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editAddAccountBtn" onclick="editAddAccAccount(this,' + count + ')" >Edit</span>|<span id="ap_add_remove_btn' + count + '" onclick="removeAddAccLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';

                        $("#ap_add_account_table >tbody").append(html);
                        $("#ap_add_module_id").val('');
                        /* if (invoiceArray[0] == 'D') {
                             $(".ap_add_dr_cr_text").html('Credit');
                             $(".ap_add_dr_cr").val('C');
                         } else {
                             $(".ap_add_dr_cr_text").html('Debit');
                             $(".ap_add_dr_cr").val('D');
                         }*/

                    } else {
                        var lineToUpdate = $(selector).attr('data-line');
                        updateAddAccLineValue(lineToUpdate);
                    }

                    /*if (totalLcy() != parseFloat($("#ap_invoice_amount_lcy").val())) {
                        $("#ap_account_id").val('').focus();
                        $('html, body').animate({scrollTop: ($("#ap_account_id").offset().top - 400)}, 2000);
                    } else {
                        $("#invoice_bill_entry_form_submit_btn").focus();
                        $('html, body').animate({scrollTop: ($("#invoice_bill_entry_form_submit_btn").offset().top - 400)}, 2000);
                    }*/

                    resetField(['#ap_add_amount_ccy', '#ap_add_account_type', '#ap_add_amount_word', '#ap_add_account_id', '#ap_add_account_name', '#ap_add_amount_lcy']);

                    $("#ap_add_account_balance_type").text('');
                    $("#ap_add_authorized_balance_type").text('');

                    $("#ar_add_account_balance_type").text('');
                    $("#ar_add_authorized_balance_type").text('');

                    resetAdditionalSubLedgerPartyFields(); //Add Pavel 07-07-22
                    //resetAccountField();
                    //resetField(['#ap_account_id'])
                    setTotalCcy("#ap_total_add_amount_ccy", "#ap_add_account_table");
                    setTotalLcy("#ap_total_add_amount_lcy", "#ap_add_account_table");
                    setPayable('ccy');
                    setPayable('lcy');
                    enableDisableSaveBtn();
                } /*else {
                            $(selector).notify("Missing input.", "error", {position: "left"});
                        }*/
            }
        }
        removeAddAccLineRow = function (select, lineRow) {
            $("#add_action_type" + lineRow).val('D');
            $(select).closest("tr").remove();   //Removing the line instead of hide, as invoice edit is not permit
            setTotalCcy("#ap_total_add_amount_ccy", "#ap_add_account_table");
            setTotalLcy("#ap_total_add_amount_lcy", "#ap_add_account_table");
            //enableDisableSaveBtn();
            //openCloseCreditRateLcy('');
        }
        editAddAccAccount = function (selector, line) {
            $("#ap_add_remove_btn" + line).hide();
            $("#ap_add_module_id").val($("#add_module_id" + line).val());
            $("#ap_add_account_id").val($("#add_account_code" + line).val());
            getAccountDetail($("#add_account_code" + line).val(), true, [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}], addAccountInfo);

            function addAccountInfo(d) {
                $("#ap_add_account_name").val(d.bankAccountInfo.gl_acc_name);
                $("#ap_add_account_type").val(d.bankAccountInfo.gl_type_name);

                if (!nullEmptyUndefinedChecked(d.bankAccountInfo.module_id)) {
                    if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                        $(".receivableArea").addClass('hidden');
                        $("#ar_add_party_sub_ledger").html(d.sub_ledgers);

                        $(".payableArea").removeClass('hidden');
                        $("#ap_add_party_sub_ledger").html(d.sub_ledgers);
                        if (!nullEmptyUndefinedChecked($("#add_vendor_id" + line).val())) {
                            $("#ap_add_party_sub_ledger").val($("#add_party_sub_ledger" + line).val());
                            $("#ap_add_vendor_id").val($("#add_vendor_id" + line).val());
                            $("#ap_add_vendor_name").val($("#add_vendor_name" + line).val());
                            $("#ap_add_vendor_category").val($("#add_vendor_category" + line).val());
                            $("#ap_add_account_balance").val($("#add_account_balance" + line).val());
                            $("#ap_add_authorized_balance").val($("#add_authorized_balance" + line).val());
                        }

                    } else if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                        $(".payableArea").addClass('hidden');
                        $("#ap_add_party_sub_ledger").html("");

                        $(".receivableArea").removeClass('hidden');
                        $("#ar_add_party_sub_ledger").html(d.sub_ledgers);
                        if (!nullEmptyUndefinedChecked($("#add_vendor_id" + line).val())) {
                            $("#ar_add_party_sub_ledger").val($("#add_party_sub_ledger" + line).val());
                            $("#ar_add_customer_id").val($("#add_vendor_id" + line).val());
                            $("#ar_add_customer_name").val($("#add_vendor_name" + line).val());
                            $("#ar_add_customer_category").val($("#add_vendor_category" + line).val());
                            $("#ar_add_account_balance").val($("#add_account_balance" + line).val());
                            $("#ar_add_authorized_balance").val($("#add_authorized_balance" + line).val());
                        }

                    } else {
                        resetAdditionalSubLedgerPartyFields();
                    }
                }

            }

            /* $("#ap_add_account_name").val($("#add_account_name" + line).val());
             $("#ap_add_account_type").val($("#add_account_type" + line).val());*/
            $("#ap_add_amount_ccy").val($("#add_ccy" + line).val());
            $("#ap_add_amount_lcy").val($("#add_lcy" + line).val());

            /*** Add this section start -Pavel: 07-07-22 ***/
            /*if (!nullEmptyUndefinedChecked($("#add_vendor_id" + line).val())) {
                if ($("#add_module_id" + line).val() == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                    $(".receivableArea").removeClass('hidden');
                    $("#ar_add_party_sub_ledger").val($("#add_party_sub_ledger" + line).val());
                    $("#ar_add_customer_id").val($("#add_vendor_id" + line).val());
                    $("#ar_add_customer_name").val($("#add_vendor_name" + line).val());
                    $("#ar_add_customer_category").val($("#add_vendor_category" + line).val());
                    $("#ar_add_account_balance").val($("#add_account_balance" + line).val());
                    $("#ar_add_authorized_balance").val($("#add_authorized_balance" + line).val());
                } else if ($("#add_module_id" + line).val() == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                    $(".payableArea").removeClass('hidden');
                    $("#ap_add_party_sub_ledger").val($("#add_party_sub_ledger" + line).val());
                    $("#ap_add_vendor_id").val($("#add_vendor_id" + line).val());
                    $("#ap_add_vendor_name").val($("#add_vendor_name" + line).val());
                    $("#ap_add_vendor_category").val($("#add_vendor_category" + line).val());
                    $("#ap_add_account_balance").val($("#add_account_balance" + line).val());
                    $("#ap_add_authorized_balance").val($("#add_authorized_balance" + line).val());
                }
            }*/
            /*** Add this section end -Pavel: 07-07-22 ***/

            $(".editAddAccountBtn").addClass('d-none');

            var select = "#addAddAccNewLineBtn";
            $(select).html("<i class='bx bx-edit'></i>UPDATE");
            $(select).attr('data-type', 'U');
            $(select).attr('data-line', line);
            //$("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            $("#ap_add_amount_word").val(amountTranslate($("#add_ccy" + line).val()));
        }

        function updateAddAccLineValue(line) {
            $("#add_account_code" + line).val($("#ap_add_account_id").val());
            $("#add_account_name" + line).val($("#ap_add_account_name").val());
            $("#add_account_type" + line).val($("#ap_add_account_type").val());

            let partyId = "";
            let partyName = "";
            let partySubLedger = "";
            let category = "";
            if ($("#ap_add_module_id").val() == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                partyId = $("#ar_add_customer_id").val();
                partyName = $("#ar_add_customer_name").val();
                partySubLedger = $("#ar_add_party_sub_ledger").val();
                category = $("#ar_add_customer_category").val();
            } else if ($("#ap_add_module_id").val() == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                partyId = $("#ap_add_vendor_id").val();
                partyName = $("#ap_add_vendor_name").val();
                partySubLedger = $("#ap_add_party_sub_ledger").val();
                category = $("#ap_add_vendor_category").val();
            }

            $("#add_party_sub_ledger" + line).val(partySubLedger);
            $("#add_vendor_category" + line).val(category);

            $("#add_account_balance" + line).val($('#ap_add_account_balance').val());
            $("#add_authorized_balance" + line).val($('#ap_add_authorized_balance').val());

            $("#add_module_id" + line).val($("#ap_add_module_id").val());

            $("#add_vendor_id" + line).val(partyId);
            $("#add_vendor_name" + line).val(partyName);

            $("#add_ccy" + line).val($("#ap_add_amount_ccy").val());
            $("#add_lcy" + line).val($("#ap_add_amount_lcy").val());
            $(".editAddAccountBtn").removeClass('d-none');

            var select = "#addAddAccNewLineBtn";
            $(select).html("<i class='bx bx-plus-circle'></i>ADD");
            $(select).attr('data-type', 'A');
            $(select).attr('data-line', '');
            //$("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
            // enableDisableSaveBtn();
            $("#ap_add_remove_btn" + line).show();
        }

        $("#ap_add_amount_ccy").on('keyup', function () {
            $("#ap_add_amount_word").val(amountTranslate($(this).val()));
        });

        function resetAddAccountField() {
            resetField(['#ap_add_account_name', '#ap_add_account_type', '#ap_add_amount_ccy', '#ap_add_amount_lcy', '#ap_add_amount_word']);
        }

        function setAddAccountInfo(d) {
            if ($.isEmptyObject(d.bankAccountInfo)) {
                resetField(['#ap_add_account_name', '#ap_add_account_type',
                    '#ap_add_amount_ccy', '#ap_add_amount_lcy', '#ap_add_amount_word']);
                resetAdditionalSubLedgerPartyFields();
                $("#ap_add_account_id").notify("Account id not found", "error");
            } else {
                resetField(['#ap_add_account_name', '#ap_add_account_type',
                    '#ap_add_amount_ccy', '#ap_add_amount_lcy', '#ap_add_amount_word']);
                $("#ap_add_account_id").val(d.bankAccountInfo.gl_acc_id);
                $("#ap_add_account_name").val(d.bankAccountInfo.gl_acc_name);
                $("#ap_add_account_type").val(d.bankAccountInfo.gl_type_name);
                if (nullEmptyUndefinedChecked(d.bankAccountInfo.cost_center_dept_name)) {
                    $("#department_cost_center").html('');
                } else {
                    $("#department_cost_center").html('<option value="' + d.bankAccountInfo.cost_center_dept_id + '">' + d.bankAccountInfo.cost_center_dept_name + '</option>');
                }

                $("#ap_add_module_id").val(d.bankAccountInfo.module_id);
                /*** Add this section start -Pavel: 07-07-22 ***/
                if (!nullEmptyUndefinedChecked(d.bankAccountInfo.module_id)) {
                    if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {

                        $(".receivableArea").addClass('hidden');
                        $("#ar_add_party_sub_ledger").html(d.sub_ledgers);

                        $(".payableArea").removeClass('hidden');
                        $("#ap_add_party_sub_ledger").html(d.sub_ledgers);
                    } else if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                        $(".payableArea").addClass('hidden');
                        $("#ap_add_party_sub_ledger").html("");

                        $(".receivableArea").removeClass('hidden');
                        $("#ar_add_party_sub_ledger").html(d.sub_ledgers);
                    } else {
                        resetAdditionalSubLedgerPartyFields();
                    }
                }


                /*** Add this section end -Pavel: 07-07-22 ***/
            }
        }

        function setAddVendorInfo(d) {
            if ($.isEmptyObject(d.party_info)) {
                $("#ap_add_vendor_id").notify("Additional Vendor id not found", "error");
                resetField(['#ap_add_vendor_id', '#ap_add_vendor_name', '#ap_add_vendor_category', '#ap_add_account_balance', '#ap_add_account_balance']);
                //emptyTaxVatPayableDropdown();
            } else {
                $('#ap_add_vendor_id').val(d.party_info.party_id);
                $('#ap_add_vendor_name').val(d.party_info.party_name);
                $('#ap_add_vendor_category').val(d.party_info.party_category);
                $('#ap_add_account_balance').val(getCommaSeparatedValue(d.party_info.account_balance));
                $('#ap_add_authorized_balance').val(getCommaSeparatedValue(d.party_info.authorized_balance));
                $("#ap_add_account_balance_type").text(d.party_info.account_balance_type);
                $("#ap_add_authorized_balance_type").text(d.party_info.authorized_balance_type);

            }
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

        function resetAccountField() {
            resetField(['#ap_account_name', '#ap_account_type',
                '#ap_account_balance', '#ap_authorized_balance',
                '#ap_budget_head', '#ap_currency',
                '#ap_amount_ccy', '#ap_amount_lcy',
                '#ap_acc_exchange_rate', '#ap_amount_word',

                "#ap_dist_party_sub_ledger",
                "#ap_dist_vendor_id", "#ap_dist_vendor_name",
                "#ap_dist_vendor_category", "#ap_dist_account_balance",
                "#ap_dist_authorized_balance",

                "#ar_dist_party_sub_ledger",
                "#ar_dist_vendor_id", "#ar_dist_vendor_name",
                "#ar_dist_vendor_category", "#ar_dist_account_balance",
                "#ar_dist_authorized_balance",
                '#ap_dist_module_id',
            ]);
            $("#ap_account_balance_type").text('');
            $("#ap_authorized_balance_type").text('');

            $(".distVendorArea").addClass('hidden');
            $(".distCustomerArea").addClass('hidden');

        }

        function enableDisableSaveBtn() {
            //Note: If distribution flag = 1 (no distribution) do not need to match invoice amount
            /*if (($("#ap_distribution_flag").val() != '1') && (!nullEmptyUndefinedChecked($("#ap_invoice_type :selected").val()))) {
                if (nullEmptyUndefinedChecked(totalLcy("#ap_account_table")) || nullEmptyUndefinedChecked($("#ap_invoice_amount_lcy").val()) || (totalLcy("#ap_account_table") != parseFloat($("#ap_invoice_amount_lcy").val()))) {
                    $("#preview_btn").prop('disabled', true);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
                } else {
                    $("#preview_btn").prop('disabled', false);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                }
            } else {
                if ($("#ap_distribution_flag").val() == '0'){
                    $("#preview_btn").prop('disabled', false);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                }else{
                    $("#preview_btn").prop('disabled', true);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
                }

            }*/
            switch ($("#ap_distribution_flag").val()) {
                case '1' :
                    $("#preview_btn").prop('disabled', false);
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                    break;
                case '0' :
                    if (nullEmptyUndefinedChecked(totalLcy("#ap_account_table")) || nullEmptyUndefinedChecked($("#ap_invoice_amount_lcy").val()) || (totalLcy("#ap_account_table") != parseFloat($("#ap_invoice_amount_lcy").val()))) {
                        $("#preview_btn").prop('disabled', true);
                        $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
                    } else {
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

        $(document).on('change', '#ap_invoice_type', function () {
            //resetField(['#ap_purchase_order_no']);
            resetTaxVatSecField();
            enableDisablePoCheck(0);
            enableDisableTaxVatSecurityExSecPercentageField(0);
            $("#ap_calculate_tax_vat").prop('checked', false);
            $("#ap_calculate_tax_vat").prop('disabled', true);

            $("#ap_inclusive_tax_vat").prop('checked', false);
            $("#ap_inclusive_tax_vat").prop('disabled', true);

            $("#ap_add_account_table >tbody >tr").remove();


            resetDistributionArea();
            resetTablesDynamicRow("#vendorSearch");

            if (!nullEmptyUndefinedChecked($(this).find(':selected').val())) {
                let invoiceParams = $(this).find(':selected').data("invoiceparams");
                let invoiceArray = invoiceParams.split("#");
                /*
                0=> D/R flag
                1=> vendor type
                2=> vendor category
                3=> Source Allow Flag
                4=> distribution line enable/disable (1= line disable, 0= line enable)
                 D#1##0#1
                 */

                //Enable disable TAX, VAT, Source

                /**
                 * Tax, vat enable disable was dependent on Standard and Credit Memo
                 * Now it depends on Ded_at_source_flag = 1/0 [1 = enable Tax,Vat; 0 = disable Tax,Vat] -Ref: 27-03-2022
                 * **/

                if (invoiceArray[3] == '1') {

                    /** Pavel Start Block vat & tax required condition-Ref: Imam Vai D-07-06-22 **/
                    //Adding tax, vat required
                    /*$(".ap_tax_amount_ccy_label").addClass('required');
                    $("#ap_tax_amount_ccy").attr('required', 'required');

                    $(".ap_vat_amount_ccy_label").addClass('required');
                    $("#ap_vat_amount_ccy").attr('required', 'required');*/
                    /** End Block vat & tax required condition-Ref: Imam Vai D-07-06-22 **/

                    $("#party_name_for_tax").removeClass('make-readonly-bg');
                    $("#party_name_for_vat").removeClass('make-readonly-bg');


                    $("#ap_tax_amount_ccy").val("").removeAttr('readonly');
                    $("#ap_tax_amount_lcy").val("");

                    $("#ap_vat_amount_ccy").val("").removeAttr('readonly');
                    $("#ap_vat_amount_lcy").val("");

                    $("#ap_security_deposit_amount_ccy").val("").removeAttr('readonly');
                    $("#ap_security_deposit_amount_lcy").val("");

                    $("#ap_extra_security_deposit_amount_ccy").val("").removeAttr('readonly');
                    $("#ap_extra_security_deposit_amount_lcy").val("");

                    $("#ap_fine_forfeiture_ccy").val("").removeAttr('readonly');
                    $("#ap_fine_forfeiture_lcy").val("");

                    $("#ap_preshipment_ccy").val("").removeAttr('readonly');
                    $("#ap_preshipment_lcy").val("");

                    $("#ap_electricity_bill_ccy").val("").removeAttr('readonly');
                    $("#ap_electricity_bill_lcy").val("");

                    $("#ap_other_charge_ccy").val("").removeAttr('readonly');
                    $("#ap_other_charge_lcy").val("");

                    $("#ap_calculate_tax_vat").prop('disabled', false);
                    $("#ap_inclusive_tax_vat").prop('disabled', false);

                    $("#add_account").removeAttr("disabled");

                } else {

                    /** Pavel: Start Block vat & tax required condition-Ref: Imam Vai D-07-06-22 **/
                    //Removing tax, vat required
                    /*$(".ap_tax_amount_ccy_label").removeClass('required');
                    $("#ap_tax_amount_ccy").removeAttr('required', 'required');

                    $(".ap_vat_amount_ccy_label").removeClass('required');
                    $("#ap_vat_amount_ccy").removeAttr('required', 'required');*/
                    /** End Block vat & tax required condition-Ref: Imam Vai D-07-06-22 **/

                    $("#party_name_for_tax").val('').addClass('make-readonly-bg');
                    $("#party_name_for_vat").val('').addClass('make-readonly-bg');


                    $("#add_account").attr("disabled", "disabled");
                    resetTaxVatSecField();
                    $("#ap_calculate_tax_vat").prop('disabled', true);
                    $("#ap_inclusive_tax_vat").prop('disabled', true);
                    enableDisableTaxVatSecurityExSecPercentageField(0);
                }

                // Start: On request of issue No: 2668 , Date:03/10/2022, Salman
                //http://issuetracker.cnsbd.com/view.php?id=2668

                if ($("#ap_party_sub_ledger :selected").val() == 231) {
                    $("#add_account").removeAttr("disabled");
                }
                // End: On request of issue No: 2668 , Date:03/10/2022

                //Enable disable distribution area

                /**issue 0002494: Opened distribution area when gl subsidiary type id is 22(Provision) :06092022**/
                /**issue 0002494: Opened distribution area when gl subsidiary type id is 22(Provision). canceled :07092022**/
                /*
                                if ((invoiceArray[4] == '0') || ($("#ap_party_sub_ledger :selected").data('glsubsidiary') == '{{\App\Enums\Common\LGlSubsidiaryType::PROVISION}}')) {
*/
                if (invoiceArray[4] == '0') {
                    $(".distribution_line_div").removeClass('d-none');
                    $("#ap_account_id").removeAttr('readonly');
                    $("#ap_amount_ccy").removeAttr('readonly');

                    $("#ap_distribution_flag").val(invoiceArray[4]);
                    enableDisableSaveBtn();
                    //Enable Budget Booking Utilized :pavel-22-01-22
                    //$(".budget_booking_utilized_div").removeClass('d-none'); //Block this sec pavel-17-04-22
                    //resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']); //add this sec pavel-24-03-22 //Block this sec pavel-17-04-22
                    //resetField(['#b_booking_id','#b_head_id','#b_head_name','#b_sub_category','#b_category','#b_type','#b_date','#b_amt','#b_available_amt']); //Block this sec pavel-24-03-22

                } else {
                    resetDistributionArea(invoiceArray[4]);
                    //Disable Budget Booking Utilized :pavel-22-01-22
                    //resetDisableBudgetBooking(); //Block this sec pavel-17-04-22
                }

                /*** Add this section start Pavel: 17-04-22 ***/
                if (invoiceArray[5] == '{{\App\Enums\YesNoFlag::YES}}') {
                    $(".budget_booking_utilized_div").removeClass('d-none');
                    resetField(['#b_head_id', '#b_head_name', '#b_amt', '#b_available_amt', '#b_utilized_amt', '#b_head_name', '#b_sub_category', '#b_category', '#b_type']);
                } else {
                    resetDisableBudgetBooking();
                }
                /*** Add this section end Pavel: 17-04-22 ***/

                /*** Add this section start Pavel: 23-03-22 ***/
                /*if ($(this).val() == {{--{{\App\Enums\Ap\LApInvoiceType::SWC_ADJ_PRO_CON_SUPP}}--}}) {  //Block this Pavel-28-08-22
                    $(".swt_pay_party_vendor_div").removeClass('d-none');
                } else {
                    $(".swt_pay_party_vendor_div").addClass('d-none');
                }*/
                /*** Add this section end Pavel: 23-03-22 ***/

            } else {
                resetTaxVatSecField();
                setPayable("ccy")
                setPayable("lcy")

                //Disable Budget Booking Utilized :pavel-22-01-22
                resetDisableBudgetBooking();

                /*** Add this section Pavel: 23-03-22 ***/
                // $(".swt_pay_party_vendor_div").addClass('d-none'); //Block this Pavel-28-08-22
            }
        });

        $("#po_based_yn").on('click', function () {
            if ($(this).prop('checked')) {
                enableDisablePOsearchArea(1)
                applyRemovePOresultFieldsEffect(1)
            } else {
                enableDisablePOsearchArea(0)
                applyRemovePOresultFieldsEffect(0)
            }
        });

        function enableDisablePoCheck(key) {
            if (key === 1) {
                if (($("#ap_invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}})) {
                    $("#po_based_yn").prop('disabled', false);

                    if (!$("#po_based_yn").prop("checked")) {
                        //$("#po_based_yn").notify("I am enabled.", "info");
                    }
                } else {
                    $("#po_based_yn").prop("checked", false);
                    $("#po_based_yn").prop('disabled', true);
                    enableDisablePOsearchArea(0);
                    applyRemovePOresultFieldsEffect(0)
                }
            } else {
                $("#po_based_yn").prop("checked", false);
                $("#po_based_yn").prop("disabled", true);
                enableDisablePOsearchArea(0)
                applyRemovePOresultFieldsEffect(0)
            }
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

        /*** Add this section start -Pavel: 07-07-22 ***/


        /*
        Distribution Lines starts here
         */
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
                url: APP_URL + '/account-payable/ajax/invoice-acc-datalist',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.glType = $('#acc_type :selected').val();
                    params.costCenterDpt = $('#department :selected').val();
                    params.searchText = $('#acc_name_code').val();
                    params.callbackType = $('#forAddAcc').val();
                    params.callback = $('#callbackVar').val();
                    params.allowedGL = $('#allowedGL').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id", "width": "15%"},
                {"data": "gl_acc_name"},
                {"data": "gl_acc_code", "width": "15%"},
                /*{"data": "dept_name"},*/
                {"data": "action", "orderable": false, "width": "10%"}
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
            accountTable.draw();
        });
        $("#ap_search_account").on("click", function () {
            let accId = $("#ap_account_id").val();
            let costCenterDpt = $('#department :selected').val(); //Add costCenterDpt Part :pavel-31-01-22
            let apInvoiceType = $('#ap_invoice_type').val(); //Add Invoice type :pavel-07-03-22
            $('#acc_type').val(''); //Add Reset Value :pavel-29-03-22
            $('#acc_name_code').val(''); //Add Reset Value :pavel-29-03-22
            $("#acc_department").val($("#department :selected").text());
            if (!nullEmptyUndefinedChecked(accId)) {
                /*** BLOCK & ADD 1,2,3,4 SEC.PAVEL-29-03-22, #PREVIOUS ALLOW ACC-TYPE ASSET & EXPENSE, #NOW ALLOW ALL ACC TYPE ***/

                /*** BLOCK 1 SEC START PAVEL-29-03-22 ***/
                //Add IF & ELSE Part :pavel-07-03-22
                /*if ((apInvoiceType == '{{--{{\App\Enums\Ap\LApInvoiceType::MIS_ADJ_DEBIT_MEMO_JV}}--}}') || (apInvoiceType == '{{--{{\App\Enums\Ap\LApInvoiceType::MIS_ADJ_CREDIT_MEMO_JV}}--}}')) {
                    getAccountDetail(accId, false, [{{--{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}--}}]);
                } else {
                    getAccountDetail(accId, false, [{{--{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}--}}]);
                }*/
                /*** BLOCK 1 SEC END PAVEL-29-03-22 ***/

                /*** ADD 2 SEC START PAVEL-29-03-22 ***/
                getAccountDetail(accId, false, [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}], setAccountInfo);
                /*** ADD 2 SEC END PAVEL-29-03-22 ***/

            }
                /**
                 * Search COA (Problem: Account Type must be Optional, Search result to be ordered by Account ID). REF# email
                 * department validation must call before modal visible.
                 * Moved department validation one step up.
                 * Logic modified:04-04-2022
                 * **/
            //Add IF Part :pavel-31-01-22
            else if (nullEmptyUndefinedChecked(costCenterDpt)) {
                $("#department").focus();
                $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                $("#department").notify("Select Department First.", {position: 'left'});

            } else {
                /*** ADD 4 SEC START PAVEL-29-03-22 ***/
                $("#forAddAcc").remove();
                $("#allowedGL").remove();
                $("#acc_search_form").append('<input type="hidden" name="forAddAcc" id="forAddAcc" value="false">');
                $("#acc_search_form").append('<input type="hidden" name="allowedGL" id="allowedGL" value="[{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}]">');

                $("#accountListModal").modal('show');
                $("#callbackVar").val('setAccountInfo');
                accountTable.draw();
                selectOptionOrdering('#acc_type'); //Add Part :pavel-07-03-22
                /*** ADD 4 SEC END PAVEL-29-03-22 ***/
            }
        });

        function setAccountInfo(d) {
            if ($.isEmptyObject(d.bankAccountInfo)) {
                resetField(['#ap_account_name', '#ap_authorized_balance', '#ap_account_balance', '#ap_account_type', '#ap_budget_head',
                    '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);
                $("#ap_account_id").notify("Account id not found", "error");
            } else {
                resetField(['#ap_account_name', '#ap_authorized_balance', '#ap_account_balance', '#ap_account_type', '#ap_budget_head',
                    '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);
                $("#ap_account_id").val(d.bankAccountInfo.gl_acc_id);
                $("#ap_account_name").val(d.bankAccountInfo.gl_acc_name);
                $("#ap_account_type").val(d.bankAccountInfo.gl_type_name);
                $("#ap_account_balance").val(getCommaSeparatedValue(d.bankAccountInfo.account_balance));
                $("#ap_authorized_balance").val(getCommaSeparatedValue(d.bankAccountInfo.authorize_balance));
                $("#ap_budget_head").val(d.bankAccountInfo.budget_head_line_name);
                $("#ap_currency").val(d.bankAccountInfo.currency_code);
                $("#ap_acc_exchange_rate").val(d.bankAccountInfo.exchange_rate);

                $("#ap_account_balance_type").text(d.bankAccountInfo.account_balance_type);
                $("#ap_authorized_balance_type").text(d.bankAccountInfo.authorize_balance_type);


                if (nullEmptyUndefinedChecked(d.bankAccountInfo.cost_center_dept_name)) {
                    $("#department_cost_center").html('');
                } else {
                    $("#department_cost_center").html('<option value="' + d.bankAccountInfo.cost_center_dept_id + '">' + d.bankAccountInfo.cost_center_dept_name + '</option>');
                }

                $("#ap_dist_module_id").val(d.bankAccountInfo.module_id);

                if (!nullEmptyUndefinedChecked(d.bankAccountInfo.module_id)) {
                    if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {

                        $(".distCustomerArea").addClass('hidden');
                        $("#ar_dist_party_sub_ledger").html(d.sub_ledgers);

                        $(".distVendorArea").removeClass('hidden');
                        $("#ap_dist_party_sub_ledger").html(d.sub_ledgers);
                    } else if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                        $(".distVendorArea").addClass('hidden');
                        $("#ap_dist_party_sub_ledger").html("");

                        $(".distCustomerArea").removeClass('hidden');
                        $("#ar_dist_party_sub_ledger").html(d.sub_ledgers);
                    } else {
                        resetDistributionSubLedgerPartyFields();
                    }
                }
            }
        }

        getAccountDetail = function (accId, forAddAcc = true, allowedGlType = [], callback) {
            //let allowedGlType = [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::EXPENSE}},{{\App\Enums\Common\GlCoaParams::LIABILITY}}, {{\App\Enums\Common\GlCoaParams::INCOME}}]; //Add LIABILITY Part :pavel-31-01-22

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/bank-account-details-for-invoice-entry',
                data: {accId: accId, allowedGlType: allowedGlType},
            });

            request.done(function (d) {
                callback(d);
                $("#accountListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }
        $("#ap_amount_ccy").on("keyup", function () {
            let c_amount_ccy_keyup = parseFloat($(this).val());
            if (!is_negative(c_amount_ccy_keyup) && c_amount_ccy_keyup != 0) {
                let c_exchange_rate_get = parseFloat($("#ap_acc_exchange_rate").val());
                //$('#c_amount_ccy').val(c_amount_ccy_keyup);

                if (c_amount_ccy_keyup && c_exchange_rate_get) {
                    let lcy = (c_amount_ccy_keyup * c_exchange_rate_get);
                    $('#ap_amount_lcy').val(lcy);
                } else {
                    $('#ap_amount_lcy').val('0');
                }
            } else {
                $('#ap_amount_ccy').val('0');
                $('#ap_amount_lcy').val('0');
            }
        });
        $("#ap_amount_ccy").on('keyup', function () {
            $("#ap_amount_word").val(amountTranslate($(this).val()));
        });
        // "#total_lcy", "#ap_account_table"
        addLineRow = function (selector) {
            if (!nullEmptyUndefinedChecked($("#ap_invoice_type").find(':selected').val())) {
                let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");

                let invoiceArray = invoiceParams.split("#");
                /*
                0=> D/R flag
                1=> vendor type
                2=> vendor category
                3=> Source Allow Flag
                4=> distribution line enable/disable
                 */

                if (fieldsAreSet(['#ap_amount_ccy', '#ap_account_id', '#ap_account_name', '#ap_amount_lcy'])) {
                    if ($(selector).attr('data-type') == 'A') {
                        let count = $("#ap_account_table >tbody").children("tr").length;
                        let partyId = "";
                        let partyName = "";
                        let partySubLedger = "";
                        let category = "";
                        let moduleId = $("#ap_dist_module_id").val();
                        let accountBalance = "";
                        let authorizedBalance = "";

                        if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                            partyId = $("#ar_dist_customer_id").val();
                            partyName = $("#ar_dist_customer_name").val();
                            partySubLedger = $("#ar_dist_party_sub_ledger").val();
                            category = $("#ar_dist_customer_category").val();
                            accountBalance = $("#ar_dist_account_balance").val();
                            authorizedBalance = $("#ar_dist_authorized_balance").val();
                        } else if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                            partyId = $("#ap_dist_vendor_id").val();
                            partyName = $("#ap_dist_vendor_name").val();
                            partySubLedger = $("#ap_dist_party_sub_ledger").val();
                            category = $("#ap_dist_vendor_category").val();
                            accountBalance = $("#ap_dist_account_balance").val();
                            authorizedBalance = $("#ap_dist_authorized_balance").val();
                        }

                        let html = '<tr>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" name="line[' + count + '][ap_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + $('#ap_account_id').val() + '" readonly/></td>\n' +
                            '<td style="padding: 4px"><input tabindex="-1" name="line[' + count + '][ap_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + $('#ap_account_name').val() + '" readonly/>' +
                            /*'</td>\n' +
                            '      <td style="padding: 4px;"><span class="ap_dr_cr_text" style="text-align: center;"></span>' +*/
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_currency]" id="currency' + count + '" value="' + $("#ap_currency").val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dr_cr]" class="ap_dr_cr" id="ap_dr_cr' + count + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_exchange_rate]" id="exchange_rate' + count + '" value="' + $('#ap_acc_exchange_rate').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_acc_type]" id="account_type' + count + '" value="' + $('#ap_account_type').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_budget_head]" id="budget_head' + count + '" value="' + $('#ap_budget_head').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_acc_balance]" id="account_balance' + count + '" value="' + accountBalance + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_authorized_balance]" id="authorized_balance' + count + '" value="' + authorizedBalance + '"/>' +
                            /*'<input tabindex="-1" type="hidden" name="line[' + count + '][ap_narration]" id="narration' + count + '" value="' + $('#ap_narration').val() + '"/>' +*/
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_action_type]" id="action_type' + count + '" value="A" />' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dist_vendor_id]" id="ap_dist_vendor_id' + count + '" value="' + partyId + '" />' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dist_party_sub_ledger]" id="ap_dist_party_sub_ledger' + count + '" value="' + partySubLedger + '" />' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dist_module_id]" id="dist_module_id' + count + '" value="' + moduleId + '" />' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dist_vendor_category]" id="ap_dist_vendor_category' + count + '" value="' + category + '" />' +
                            '</td>\n' +
                            '<td><input name="line[' + count + '][ap_dist_vendor_id]" value="' + partyId + '" class="form-control form-control-sm" id="ap_dist_vendor_id_view' + count + '" readonly></td>' +
                            '<td><input name="line[' + count + '][ap_dist_vendor_name]" value="' + partyName + '" class="form-control form-control-sm" id="ap_dist_vendor_name_view' + count + '" readonly></td>' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align" name="line[' + count + '][ap_amount_ccy]" id="ccy' + count + '" value="' + $('#ap_amount_ccy').val() + '" readonly></td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][ap_amount_lcy]" id="lcy' + count + '" value="' + $('#ap_amount_lcy').val() + '" readonly></td>\n' +
                            '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editAccountBtn" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="ap_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#ap_account_table >tbody").append(html);
                        $("#ap_dist_module_id").val('');
                        //Setting distribution type
                        if (invoiceArray[0] == 'D') {
                            $(".ap_dr_cr_text").html('Credit');
                            $(".ap_dr_cr").val('C');
                        } else {
                            $(".ap_dr_cr_text").html('Debit');
                            $(".ap_dr_cr").val('D');
                        }

                    } else {
                        var lineToUpdate = $(selector).attr('data-line');
                        updateLineValue(lineToUpdate);
                    }

                    if (totalLcy("#ap_account_table") != parseFloat($("#ap_invoice_amount_lcy").val())) {
                        $("#ap_account_id").val('').focus();
                        $('html, body').animate({scrollTop: ($("#ap_account_id").offset().top - 400)}, 2000);
                    } else {
                        $("#invoice_bill_entry_form_submit_btn").focus();
                        $('html, body').animate({scrollTop: ($("#invoice_bill_entry_form_submit_btn").offset().top - 400)}, 2000);
                    }

                    //resetField(['#ap_account_id', '#ap_account_name', '#ap_account_type', '#ap_account_balance', '#ap_authorized_balance', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);
                    resetAccountField();
                    resetDistributionSubLedgerPartyFields()
                    resetField(['#ap_account_id'])
                    setTotalLcy("#total_lcy", "#ap_account_table");
                    enableDisableSaveBtn();
                    $("#ap_account_balance_type").text('');
                    $("#ap_authorized_balance_type").text('');

                    $("#ap_dist_account_balance_type").text('');
                    $("#ap_dist_authorized_balance_type").text('');

                    $("#ar_dist_account_balance_type").text('');
                    $("#ar_dist_authorized_balance_type").text('');

                    //openCloseCreditRateLcy('');
                } /*else {
                            $(selector).notify("Missing input.", "error", {position: "left"});
                        }*/
            } else {
                $("#ap_invoice_type").notify("Select Invoice Type First.");
            }
        }
        removeLineRow = function (select, lineRow) {
            $("#action_type" + lineRow).val('D');
            $(select).closest("tr").remove();   //Removing the line instead of hide, as invoice edit is not permit
            setTotalLcy("#total_lcy", "#ap_account_table");
            enableDisableSaveBtn();
            //openCloseCreditRateLcy('');
        }
        editAccount = function (selector, line) {
            $("#ap_remove_btn" + line).hide();
            $("#ap_dist_module_id").val($("#dist_module_id" + line).val());
            $("#ap_account_id").val($("#account_code" + line).val());
            getAccountDetail($("#account_code" + line).val(), false, [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::LIABILITY}},{{\App\Enums\Common\GlCoaParams::INCOME}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}], populateAccountInfo);


            function populateAccountInfo(d) {
                resetField(['#ap_account_name', '#ap_authorized_balance', '#ap_account_balance', '#ap_account_type', '#ap_budget_head',])
                $("#ap_account_name").val(d.bankAccountInfo.gl_acc_name);
                $("#ap_account_type").val(d.bankAccountInfo.gl_type_name);

                if (!nullEmptyUndefinedChecked(d.bankAccountInfo.module_id)) {
                    if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                        $(".distCustomerArea").addClass('hidden');
                        $("#ar_dist_party_sub_ledger").html(d.sub_ledgers);

                        $(".distVendorArea").removeClass('hidden');
                        $("#ap_dist_party_sub_ledger").html(d.sub_ledgers);
                        if (!nullEmptyUndefinedChecked($("#ap_dist_vendor_id" + line).val())) {
                            $("#ap_dist_party_sub_ledger").val($("#ap_dist_party_sub_ledger" + line).val());
                            $("#ap_dist_vendor_id").val($("#ap_dist_vendor_id" + line).val());
                            $("#ap_dist_vendor_name").val($("#ap_dist_vendor_name_view" + line).val());
                            $("#ap_dist_vendor_category").val($("#ap_dist_vendor_category" + line).val());
                            $("#ap_dist_account_balance").val(getCommaSeparatedValue($("#account_balance" + line).val()));
                            $("#ap_dist_authorized_balance").val(getCommaSeparatedValue($("#authorized_balance" + line).val()));
                        }

                    } else if (d.bankAccountInfo.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                        $(".distVendorArea").addClass('hidden');
                        $("#ap_dist_party_sub_ledger").html("");

                        $(".distCustomerArea").removeClass('hidden');
                        $("#ar_dist_party_sub_ledger").html(d.sub_ledgers);
                        if (!nullEmptyUndefinedChecked($("#ap_dist_vendor_id" + line).val())) {
                            $("#ar_dist_party_sub_ledger").val($("#ap_dist_party_sub_ledger" + line).val());
                            $("#ar_dist_customer_id").val($("#ap_dist_vendor_id" + line).val());
                            $("#ar_dist_customer_name").val($("#ap_dist_vendor_name_view" + line).val());
                            $("#ar_dist_customer_category").val($("#ap_dist_vendor_category" + line).val());
                            $("#ar_dist_account_balance").val(getCommaSeparatedValue($("#account_balance" + line).val()));
                            $("#ar_dist_authorized_balance").val(getCommaSeparatedValue($("#authorized_balance" + line).val()));
                        }

                    } else {
                        resetDistributionSubLedgerPartyFields();
                    }
                }
                $("#ap_budget_head").val(d.bankAccountInfo.budget_head_line_name);
            }

            /*$("#ap_account_name").val($("#account_name" + line).val());
            $("#ap_account_type").val($("#account_type" + line).val());
            $("#ap_account_balance").val($("#account_balance" + line).val());
            $("#ap_authorized_balance").val($("#authorized_balance" + line).val());*/


            $("#ap_currency").val($("#currency" + line).val());
            $("#ap_amount_ccy").val($("#ccy" + line).val());
            $("#ap_amount_lcy").val($("#lcy" + line).val());
            $("#ap_acc_exchange_rate").val($("#exchange_rate" + line).val());
            //$("#ap_narration").val($("#narration" + line).val());

            $(".editAccountBtn").addClass('d-none');

            //removeLineRow(selector,line);
            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-edit'></i>UPDATE");
            $(select).attr('data-type', 'U');
            $(select).attr('data-line', line);
            $("#preview_btn").prop('disabled', true);
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            $("#ap_amount_word").val(amountTranslate($("#ccy" + line).val()));
        }

        function updateLineValue(line) {
            $("#account_code" + line).val($("#ap_account_id").val());
            $("#account_name" + line).val($("#ap_account_name").val());
            $("#account_type" + line).val($("#ap_account_type").val());
            let partyId = "";
            let partyName = "";
            let partySubLedger = "";
            let category = "";
            let moduleId = $("#ap_dist_module_id").val();
            let accountBalance = "";
            let authorizedBalance = "";

            if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {
                partyId = $("#ar_dist_customer_id").val();
                partyName = $("#ar_dist_customer_name").val();
                partySubLedger = $("#ar_dist_party_sub_ledger").val();
                category = $("#ar_dist_customer_category").val();
                accountBalance = $("#ar_dist_account_balance").val();
                authorizedBalance = $("#ar_dist_authorized_balance").val();
            } else if (moduleId == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {
                partyId = $("#ap_dist_vendor_id").val();
                partyName = $("#ap_dist_vendor_name").val();
                partySubLedger = $("#ap_dist_party_sub_ledger").val();
                category = $("#ap_dist_vendor_category").val();
                accountBalance = $("#ap_dist_account_balance").val();
                authorizedBalance = $("#ap_dist_authorized_balance").val();
            }
            $("#currency" + line).val($("#ap_currency").val());
            $("#exchange_rate" + line).val($("#ap_acc_exchange_rate").val());
            $("#budget_head" + line).val($("#ap_budget_head").val());
            $("#account_balance" + line).val(accountBalance);
            $("#authorized_balance" + line).val(authorizedBalance);
            $("#ap_dist_vendor_id" + line).val(partyId);
            $("#ap_dist_party_sub_ledger" + line).val(partySubLedger);
            $("#dist_module_id" + line).val(moduleId);
            $("#ap_dist_vendor_category" + line).val(category);
            $("#ap_dist_vendor_id_view" + line).val(partyId);
            $("#ap_dist_vendor_name_view" + line).val(partyName);
            $("#ccy" + line).val($("#ap_amount_ccy").val());
            $("#lcy" + line).val($("#ap_amount_lcy").val());

            //$("#narration" + line).val($("#ap_narration").val());
            $(".editAccountBtn").removeClass('d-none');

            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-plus-circle'></i>ADD");
            $(select).attr('data-type', 'A');
            $(select).attr('data-line', '');
            $("#preview_btn").prop('disabled', false);
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
            enableDisableSaveBtn();
            $("#ap_remove_btn" + line).show();
        }

        function setTotalLcy(displaySelector, selector) {
            $(displaySelector).val(totalLcy(selector));
        }

        function setTotalCcy(displaySelector, selector) {
            $(displaySelector).val(totalCcy(selector));
        }

        function totalLcy(selector) {
            let lcy = $(selector + " >tbody >tr").find(".lcy");
            let totalLcy = 0;
            lcy.each(function () {
                if ($(this).is(":hidden") == false) {
                    if ($(this).val() != "" && $(this).val() != "0") {
                        totalLcy += parseFloat($(this).val());
                    }
                }
            });

            //return totalLcy;
            return (totalLcy.toFixed(2)); //Add Pavel:06-07-22
        }

        function totalCcy(selector) {
            let ccy = $(selector + " >tbody >tr").find(".ccy");
            let totalCcy = 0;
            ccy.each(function () {
                if ($(this).is(":hidden") == false) {
                    if ($(this).val() != "" && $(this).val() != "0") {
                        totalCcy += parseFloat($(this).val());
                    }
                }
            });

            return totalCcy;
        }

        function resetDistributionArea(distributionFlag=2) {
            $("#ap_distribution_flag").val(distributionFlag);
            //$(".distribution_line_div").addClass('make-readonly-bg');
            $(".distribution_line_div").addClass('d-none');
            $(".additional-account-area").addClass('d-none');

            $("#ap_account_id").attr('readonly', 'readonly');
            $("#ap_amount_ccy").attr('readonly', 'readonly');
            resetField(['#ap_account_id', '#ap_account_balance', '#ap_account_name', '#ap_authorized_balance', '#ap_account_type', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_acc_exchange_rate', '#ap_amount_lcy', '#ap_amount_word',])
            resetTablesDynamicRow("#ap_account_table");
            $("#total_lcy").val('');
            enableDisableSaveBtn();
        }

        function resetDistributionSubLedgerPartyFields() {
            resetField([
                "#ap_dist_party_sub_ledger",
                "#ap_dist_vendor_id", "#ap_dist_vendor_name",
                "#ap_dist_vendor_category", "#ap_dist_account_balance",
                "#ap_dist_authorized_balance",

                "#ar_dist_party_sub_ledger",
                "#ar_dist_vendor_id", "#ar_dist_vendor_name",
                "#ar_dist_vendor_category", "#ar_dist_account_balance",
                "#ar_dist_authorized_balance"
            ]);
            $(".distVendorArea").addClass('hidden');
            $(".distCustomerArea").addClass('hidden');
        }

        $("#ap_dist_party_sub_ledger").on('change', function () {
            resetField(['#ap_dist_vendor_id', '#ap_dist_vendor_name', '#ap_dist_vendor_category', '#ap_dist_account_balance', '#ap_dist_authorized_balance'])
            $("#ap_dist_vendor_search").val('{{\App\Enums\YesNoFlag::NO}}')
        });
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

            /********Added on: 06/06/2022, sujon**********/
            /*function setBillSection(){
                $("#bill_register").change(function (e) {
                    $bill_sec_id = $("#bill_register :selected").data('secid');
                    $bill_sec_name = $("#bill_register :selected").data('secname');
                    if (!nullEmptyUndefinedChecked($bill_sec_id)){
                        $("#bill_section").html("<option value='"+$bill_sec_id+"'>"+$bill_sec_name+"</option>")
                    }else{
                        $("#bill_section").html("<option value=''></option>")
                    }
                });
            }*/
            //setBillSection();
            /********End**********/


            $("#invoice_bill_entry_form").on("submit", function (e) {
                e.preventDefault();

                //Validate-Budget Booking Utilized :pavel-23-01-22
                let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
                let invoiceArray = invoiceParams.split("#");
                let budgetHeadId = $("#b_head_id").val();
                //let budgetBookingId = $("#b_booking_id").val();

                //Validate-Budget Booking Utilized Only If Part :pavel-23-01-22
                //if ( (invoiceArray[4] == '0') && nullEmptyUndefinedChecked(budgetBookingId) ) { //Block this sec Pavel-24-03-22
                if ((invoiceArray[4] == '0') && nullEmptyUndefinedChecked(budgetHeadId) && (invoiceArray[5] == 'Y') && ($("#ap_without_budget_info").prop("checked") == false)) {
                    /*$("#b_booking_id").focus();
                    $('html, body').animate({scrollTop: ($("#b_booking_id").offset().top - 200)}, 2000);  //Block this sec Pavel-24-03-22
                    $("#b_booking_id").notify("Please Add Budget Booking Info.", {position: 'bottom'});*/

                    //Add this sec Pavel-24-03-22
                    $("#b_head_id").focus();
                    $('html, body').animate({scrollTop: ($("#b_head_id").offset().top - 200)}, 2000);
                    $("#b_head_id").notify("Please Add Budget Booking Info.", {position: 'bottom'});

                } else {
                    Swal.fire({
                        title: "Are you sure?",
                        html: 'Submit' + '<br>' +
                            'Party/Vendor ID: ' + $("#ap_vendor_id").val() + '<br>' +
                            'Party/Vendor Name: ' + $("#ap_vendor_name").val() + '<br>' +
                            'Invoice Amount: ' + $("#ap_invoice_amount_lcy").val() + '<br>' +
                            'Payable Amount: ' + $("#ap_payable_amount_lcy").val() + '<br>',
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
                                url: APP_URL + "/account-payable/invoice-bill-entry",
                                data: new FormData($("#invoice_bill_entry_form")[0]),
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
                                        $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');

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
                }
            })


            $("#preview_btn").on("click", function (e) {
                e.preventDefault();

                let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
                let invoiceArray = invoiceParams.split("#");
                let budgetHeadId = $("#b_head_id").val();

                if ((invoiceArray[4] == '0') && nullEmptyUndefinedChecked(budgetHeadId) && (invoiceArray[5] == 'Y') && ($("#ap_without_budget_info").prop("checked") == false)) {
                    $("#b_head_id").focus();
                    $('html, body').animate({scrollTop: ($("#b_head_id").offset().top - 200)}, 2000);
                    $("#b_head_id").notify("Please Add Budget Booking Info.", {position: 'bottom'});

                } else {
                    var formData = $("#invoice_bill_entry_form").serialize();
                    var request = $.ajax({
                        url: APP_URL + "/account-payable/invoice-bill-preview",
                        method: "POST",
                        data: new FormData($("#invoice_bill_entry_form")[0]),
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });
                    request.done(function (res) {
                        if (res.response_code == "1") {
                            //$("#preview_content").html("");
                            $("#preview_content").html(res.table_content);
                            $("#previewModal").modal('show');
                        } else {

                            Swal.fire({text: res.response_msg, type: 'error'});
                        }
                    });

                    request.fail(function (jqXHR, textStatus) {
                        Swal.fire({text: textStatus + jqXHR, type: 'error'});
                        //console.log(jqXHR, textStatus);
                    });
                }
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

                resetField(['#bl_bills_payable', '#bl_provision_exp', '#bl_security_dep_pay', '#bl_os_advances', '#bl_os_prepayments', '#bl_os_imp_rev']);
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
        });
    </script>
@endsection


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
            @include("ap.invoice-bill-entry.form")
        </div>
    </div>
    @include('ap.ap-common.common_po_list_modal')
    @include('ap.ap-common.common_vendor_list_modal')
    @include('ap.invoice-bill-entry.common_budged_search')
    @include('gl.common_coalist_modal')
@endsection

@section('footer-script')
    <script type="text/javascript">
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;

        $("#ap_party_sub_ledger").on('change', function () {
            let subsidiaryId = $(this).val();
            $("#ap_invoice_type").val("");
            resetTaxVatSecField();
            enableDisablePoCheck(0);
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
                '#ap_fine_forfeiture_lcy',
                '#ap_preshipment_lcy',
                '#ap_electricity_bill_lcy',
                '#ap_other_charge_lcy',
                '#ap_payable_amount_lcy'
            ]);
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
            fineForfeiture = (nullEmptyUndefinedChecked($("#ap_fine_forfeiture_" + subStr).val()) ? 0 : parseFloat($("#ap_fine_forfeiture_" + subStr).val()));
            preshipment = (nullEmptyUndefinedChecked($("#ap_preshipment_" + subStr).val()) ? 0 : parseFloat($("#ap_preshipment_" + subStr).val()));
            electricityBill = (nullEmptyUndefinedChecked($("#ap_electricity_bill_" + subStr).val()) ? 0 : parseFloat($("#ap_electricity_bill_" + subStr).val()));
            otherCharge = (nullEmptyUndefinedChecked($("#ap_other_charge_" + subStr).val()) ? 0 : parseFloat($("#ap_other_charge_" + subStr).val()));

            deduction = tax + vat + securityAmount + extraSecurityAmount + fineForfeiture + preshipment + electricityBill + otherCharge;
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

                $("#posting_date").focus();
                $('html, body').animate({scrollTop: ($("#posting_date").offset().top - 400)}, 2000);
                $("#posting_date").notify("Set posting date", "info");
            }
        });

        /*
        * Vendor search starts from here
        * */
        $(" #ap_vendor_search").on("click", function () {
            let vendorId = $('#ap_vendor_id').val();
            if (!nullEmptyUndefinedChecked($("#ap_invoice_type").val())) {
                if (!nullEmptyUndefinedChecked(vendorId)) {
                    getVendorDetail(vendorId);
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

        $("#ap_reset_vendor_balance_field").on("click", function () {
            resetField(['#ap_search_vendor_id', '#ap_search_vendor_name', '#ap_search_vendor_category', '#ap_bills_payable', '#ap_prepayments', '#ap_security_deposits', '#ap_advance', '#ap_imprest_cash', '#ap_revolving_cash']);
        });
        $(document).on('click', '.vendorSelect', function () {
            getVendorDetail($(this).data('vendor'));
        });

        function getVendorDetail(vendor_id) {
            let invoiceParams = $("#ap_invoice_type").find(':selected').data("invoiceparams");
            let vendorType = '';
            let vendorCategory = '';

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
            }

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-details',
                data: {vendorId: vendor_id, vendorType: vendorType, vendorCategory: vendorCategory}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $("#ap_vendor_id").notify("Vendor id not found", "error");
                    resetField(['#ap_vendor_id', '#ap_vendor_name', '#ap_vendor_category']);
                    enableDisablePoCheck(0)
                } else {
                    $('#ap_vendor_id').val(d.vendor_id);
                    $('#ap_vendor_name').val(d.vendor_name);
                    $('#ap_vendor_category').val(d.vendor_category.vendor_category_name);
                    enableDisablePoCheck(1)
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
                {"data": "category"},
                {"data": "action", "orderable": false}
            ],
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
            resetField(["#b_booking_id"]);
            resetBudgetField();
            resetBudgetHeadBookingTables();
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
        $("#b_booking_search").on("click", function () {
            let bookingId = $('#b_booking_id').val();
            let department = $('#department :selected').val();
            let calendar = $('#fiscal_year :selected').val();
            let vendorId = $('#ap_vendor_id').val();

            resetBudgetField();
            //resetBudgetHeadBookingTables();

            if ( !nullEmptyUndefinedChecked(department) && !nullEmptyUndefinedChecked(vendorId) ) {
                /*if (!nullEmptyUndefinedChecked(bookingId)) {
                    getBudgetBookingDetailInfo(bookingId, department, calendar);
                } else {*/
                $('#b_booking_id').val("") //Remove this line when open if condition
                reloadBudgetListTable();

                $("#s_fiscal_year").val($("#fiscal_year").text().trim());
                $("#s_part_vendor_id").val($("#ap_vendor_id").val());
                $("#s_department").val($("#department :selected").text());
                $("#s_budget_head_name_code").val('');

                $("#budgetListModal").modal('show');
                /*}*/
            } else {
                resetField(['#b_booking_id']);

                if (nullEmptyUndefinedChecked(vendorId)) {
                    $("#ap_vendor_id").focus();
                    $('html, body').animate({scrollTop: ($("#ap_vendor_id").offset().top - 200)}, 2000);
                    $("#ap_vendor_id").notify("Please Add Vendor ID.", {position: 'right'});
                } else if ( nullEmptyUndefinedChecked(department) ){
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});
                }

            }
        });
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

        /*
        * Budget list search starts from here
        * */
        $(document).on('submit', '#booking_search_form', function (e) {
            e.preventDefault();
            //resetBudgetHeadBookingTables();

            /*$("#budget_head_list").data("dt_params", {*/
            $("#budget_booking_list").data("dt_params", {
                "department": $('#department :selected').val(),
                "calendar": $('#fiscal_year :selected').val(),
<<<<<<< .mine
                "vendorId": $('#ap_vendor_id').val(),
                "nameCode": $('#s_budget_head_name_code').val()
||||||| .r594
                "nameCode": $('#s_budget_head_name_code').val()
=======
                "nameCode": $('#s_budget_head_name_code').val(),
                "vendorId": $('#ap_vendor_id').val()
>>>>>>> .r595
            }).DataTable().draw();
        })
        let budgetBookingTable = $('#budget_booking_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
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
            "columns": [
                {"data": 'booking_id', "name": 'booking_id'},
                {"data": "booking_date"},
                {"data": "budget_booking_amount"},
                {"data": "budget_head_name"},
                {"data": "budget_category_name"},
                {"data": "budget_type_name"},
                {"data": "action", "orderable": false}
            ],
        });
        $(document).on('click', '.budgetSelect', function () {
            let bookingId = $(this).data('bookingid');
            let department = $('#department :selected').val();
            let calendar = $('#fiscal_year :selected').val();
            getBudgetBookingDetailInfo(bookingId, department, calendar)
        });

        /*
        * Budget list search ends here
        * */

        function getBudgetBookingDetailInfo(budgetBookingId, department, calendar) {
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/a-budget-booking-detail',
                data: {budget_booking_id: budgetBookingId, department: department, calendar: calendar}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.data)) {
                    $("#b_booking_id").notify("Budget Booking ID Not Found", "error");
                    resetField(['#b_head_id',
                        '#b_date', '#b_amt', '#b_head_name',
                        '#b_sub_category', '#b_category', '#b_type']);
                } else {
                    $('#b_booking_id').val(d.data.budget_booking_id);
                    $('#b_head_id').val(d.data.budget_head_id);
                    $('#b_date').val(d.data.budget_booking_date);
                    $('#b_amt').val(d.data.budget_booking_amount);
                    $('#b_head_name').val(d.data.budget_head_name);
                    $('#b_sub_category').val(d.data.sub_category_name);
                    $('#b_category').val(d.data.category_name);
                    $('#b_type').val(d.data.budget_type);
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
            resetField(['#b_head_id',
                '#b_date', '#b_amt', '#b_head_name',
                '#b_sub_category', '#b_category', '#b_type']);
        }

        function resetBudgetHeadBookingTables() {
            /*$("#budget_head_list").data("dt_params", {
                "department": "",
                "calendar": "",
                "vendorId": "",
                "nameCode": ""
            }).DataTable().draw();*/
            $('#budget_booking_list').data('dt_params', {
                "budget_head_id": "",
                "department": "",
                "calendar": "",
                "vendorId":""
            }).DataTable().draw();
        }

        /*
        * Budget Head search ends here
        * */


        /*
        * Budget search ends here
        * */

        $("#ap_calculate_tax_vat").on("click", function () {
            //resetTaxVatSecField();
            if ($(this).prop('checked')) {
                enableDisableTaxVatPercentageFieldOnCheckbox(1);
            } else {
                enableDisableTaxVatPercentageFieldOnCheckbox(0);

                //$("#ap_invoice_type").trigger('change');
            }
        });

        function enableDisableTaxVatPercentageFieldOnCheckbox(status) {
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

            setPayable("ccy");
            setPayable("lcy");
        }

        function enableDisableTaxVatPercentageField(status) {
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
            $(selector).parent().next('div').children('input[type=number]').val(amountOnPercent);
            calculateLcy(["#" + $(selector).parent().next('div').children('input[type=number]').attr('id')]);

            setPayable('ccy');
            setPayable('lcy');
        }

        function applyRemovePOresultFieldsEffect(key) {
            if (key == 1) {
                $("#po_master_id").val("");
                $("#document_number").val("").attr('readonly', 'readonly');
                $("#document_date_field").val("").attr('readonly', 'readonly');
                $("#document_date").addClass('make-readonly');
                $("#ap_invoice_amount_ccy").val("").attr('readonly', 'readonly');
                $("#ap_invoice_amount_lcy").val("");
                $("#ap_payable_amount_ccy").val("");
                $("#ap_payable_amount_lcy").val("");

            } else {
                $("#po_master_id").val("");
                $("#document_number").val("").removeAttr('readonly', 'readonly');
                $("#document_date_field").val("").removeAttr('readonly', 'readonly');
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


        /*
        Distribution Lines starts here
         */
        let accountTable = $('#account_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
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
                    params.searchText = $('#acc_name_code').val();
                }
            },
            "columns": [
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "gl_acc_id"},
                {"data": "gl_acc_name"},
                {"data": "gl_acc_code"},
                {"data": "action", "orderable": false}
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
            $accId = $("#ap_account_id").val();

            if (!nullEmptyUndefinedChecked($accId)) {
                getAccountDetail($accId);
            } else {
                $("#acc_type option[value='2']").remove();
                $("#acc_type option[value='3']").remove();
                $("#accountListModal").modal('show');
                accountTable.draw();
            }
        });
        getAccountDetail = function (accId) {
            let allowedGlType = [{{\App\Enums\Common\GlCoaParams::ASSET}},{{\App\Enums\Common\GlCoaParams::EXPENSE}}];

            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/bank-account-details-for-invoice-entry',
                data: {accId: accId, allowedGlType: allowedGlType},
            });

            request.done(function (d) {
                resetField(['#ap_account_name', '#ap_authorized_balance', '#ap_account_balance', '#ap_account_type', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);

                if ($.isEmptyObject(d)) {
                    $("#ap_account_id").notify("Account id not found", "error");
                } else {
                    $("#ap_account_id").val(d.gl_acc_id);
                    $("#ap_account_name").val(d.gl_acc_name);
                    $("#ap_account_type").val(d.gl_type_name);
                    $("#ap_account_balance").val(d.account_balance);
                    $("#ap_authorized_balance").val(d.authorize_balance);
                    $("#ap_budget_head").val(d.budget_head_line_name);
                    $("#ap_currency").val(d.currency_code);
                    $("#ap_acc_exchange_rate").val(d.exchange_rate);
                    if (nullEmptyUndefinedChecked(d.cost_center_dept_name)) {
                        $("#department_cost_center").html('');
                    } else {
                        $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                    }

                    //openCloseCreditRateLcy(d.currency_code);

                    $("#accountListModal").modal('hide');

                    $("#ap_amount_ccy").focus();
                    $('html, body').animate({scrollTop: ($("#ap_amount_ccy").offset().top - 400)}, 2000);
                }
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
                    if ($(selector).data('type') == 'A') {
                        let count = $("#ap_account_table >tbody").children("tr").length;

                        let html = '<tr>\n' +
                            '      <td style="padding: 4px;"><input tabindex="-1" name="line[' + count + '][ap_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + $('#ap_account_id').val() + '" readonly/></td>\n' +
                            '      <td style="padding: 4px"><input tabindex="-1" name="line[' + count + '][ap_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + $('#ap_account_name').val() + '" readonly/></td></td>\n' +
                            '      <td style="padding: 4px;"><span class="ap_dr_cr_text" style="text-align: center;"></span><input tabindex="-1" type="hidden" name="line[' + count + '][ap_currency]" id="currency' + count + '" value="' + $("#ap_currency").val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_dr_cr]" class="ap_dr_cr" id="ap_dr_cr' + count + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_exchange_rate]" id="exchange_rate' + count + '" value="' + $('#ap_acc_exchange_rate').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_acc_type]" id="account_type' + count + '" value="' + $('#ap_account_type').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_budget_head]" id="budget_head' + count + '" value="' + $('#ap_budget_head').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_acc_balance]" id="account_balance' + count + '" value="' + $('#ap_account_balance').val() + '"/>' +
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_authorized_balance]" id="authorized_balance' + count + '" value="' + $('#ap_authorized_balance').val() + '"/>' +
                            /*'<input tabindex="-1" type="hidden" name="line[' + count + '][ap_narration]" id="narration' + count + '" value="' + $('#ap_narration').val() + '"/>' +*/
                            '<input tabindex="-1" type="hidden" name="line[' + count + '][ap_action_type]" id="action_type' + count + '" value="A" />' +
                            '</td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align" name="line[' + count + '][ap_amount_ccy]" id="ccy' + count + '" value="' + $('#ap_amount_ccy').val() + '" readonly></td>\n' +
                            '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][ap_amount_lcy]" id="lcy' + count + '" value="' + $('#ap_amount_lcy').val() + '" readonly></td>\n' +
                            '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="ap_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                            '  </tr>';
                        $("#ap_account_table >tbody").append(html);

                        //Setting distribution type
                        if (invoiceArray[0] == 'D') {
                            $(".ap_dr_cr_text").html('Credit');
                            $(".ap_dr_cr").val('C');
                        } else {
                            $(".ap_dr_cr_text").html('Debit');
                            $(".ap_dr_cr").val('D');
                        }

                    } else {
                        var lineToUpdate = $(selector).data('line');
                        updateLineValue(lineToUpdate);
                    }

                    if (totalLcy() != parseFloat($("#ap_invoice_amount_lcy").val())) {
                        $("#ap_account_id").val('').focus();
                        $('html, body').animate({scrollTop: ($("#ap_account_id").offset().top - 400)}, 2000);
                    } else {
                        $("#invoice_bill_entry_form_submit_btn").focus();
                        $('html, body').animate({scrollTop: ($("#invoice_bill_entry_form_submit_btn").offset().top - 400)}, 2000);
                    }

                    //resetField(['#ap_account_id', '#ap_account_name', '#ap_account_type', '#ap_account_balance', '#ap_authorized_balance', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);
                    resetAccountField();
                    resetField(['#ap_account_id'])
                    setTotalLcy();
                    enableDisableSaveBtn();
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
            setTotalLcy();
            enableDisableSaveBtn();
            //openCloseCreditRateLcy('');
        }
        editAccount = function (selector, line) {
            $("#ap_remove_btn" + line).hide();

            $("#ap_account_id").val($("#account_code" + line).val());
            $("#ap_account_name").val($("#account_name" + line).val());
            $("#ap_account_type").val($("#account_type" + line).val());
            $("#ap_account_balance").val($("#account_balance" + line).val());
            $("#ap_authorized_balance").val($("#authorized_balance" + line).val());
            $("#ap_budget_head").val($("#budget_head" + line).val());
            $("#ap_currency").val($("#currency" + line).val());
            $("#ap_amount_ccy").val($("#ccy" + line).val());
            $("#ap_amount_lcy").val($("#lcy" + line).val());
            $("#ap_acc_exchange_rate").val($("#exchange_rate" + line).val());
            //$("#ap_narration").val($("#narration" + line).val());

            //removeLineRow(selector,line);
            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-edit'></i>UPDATE");
            $(select).data('type', 'U');
            $(select).data('line', line);
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            $("#ap_amount_word").val(amountTranslate($("#ccy" + line).val()));
        }

        function updateLineValue(line) {
            $("#account_code" + line).val($("#ap_account_id").val());
            $("#account_name" + line).val($("#ap_account_name").val());
            $("#account_type" + line).val($("#ap_account_type").val());
            $("#account_balance" + line).val($("#ap_account_balance").val());
            $("#authorized_balance" + line).val($("#ap_authorized_balance").val());
            $("#budget_head" + line).val($("#ap_budget_head").val());
            $("#currency" + line).val($("#ap_currency").val());
            $("#ccy" + line).val($("#ap_amount_ccy").val());
            $("#lcy" + line).val($("#ap_amount_lcy").val());
            $("#exchange_rate" + line).val($("#ap_acc_exchange_rate").val());
            //$("#narration" + line).val($("#ap_narration").val());

            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-plus-circle'></i>ADD");
            $(select).data('type', 'A');
            $(select).data('line', '');
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
            enableDisableSaveBtn();
            $("#ap_remove_btn" + line).show();
        }

        function setTotalLcy() {
            $("#total_lcy").val(totalLcy());
        }

        function totalLcy() {
            let lcy = $("#ap_account_table >tbody >tr").find(".lcy");
            let totalLcy = 0;
            lcy.each(function () {
                if ($(this).is(":hidden") == false) {
                    if ($(this).val() != "" && $(this).val() != "0") {
                        totalLcy += parseFloat($(this).val());
                    }
                }
            });

            return totalLcy;
        }

        $("#ap_amount_ccy").on('keyup', function () {
            $("#ap_amount_word").val(amountTranslate($(this).val()));
        });

        function resetAccountField() {
            resetField(['#ap_account_name', '#ap_account_type', '#ap_account_balance', '#ap_authorized_balance', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_amount_lcy', '#ap_acc_exchange_rate', '#ap_amount_word']);
        }

        function enableDisableSaveBtn() {
            if (($("#ap_distribution_flag").val() != '1') && ($("#ap_invoice_type").val() != "")) {
                if (nullEmptyUndefinedChecked(totalLcy()) || nullEmptyUndefinedChecked($("#ap_invoice_amount_lcy").val()) || (totalLcy() != parseFloat($("#ap_invoice_amount_lcy").val()))) {
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
                } else {
                    $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                }
            } else {
                $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
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
                "#search_vendor_type",
                "#search_vendor_category"])
        }

        function resetDistributionArea() {
            $("#ap_distribution_flag").val(1);
            //$(".distribution_line_div").addClass('make-readonly-bg');
            $(".distribution_line_div").addClass('d-none');
            $("#ap_account_id").attr('readonly', 'readonly');
            $("#ap_amount_ccy").attr('readonly', 'readonly');
            resetField(['#ap_account_id', '#ap_account_balance', '#ap_account_name', '#ap_authorized_balance', '#ap_account_type', '#ap_budget_head', '#ap_currency', '#ap_amount_ccy', '#ap_acc_exchange_rate', '#ap_amount_lcy', '#ap_amount_word',])
            resetTablesDynamicRow("#ap_account_table");
            $("#total_lcy").val('');
            enableDisableSaveBtn();
        }

        $(document).on('change', '#ap_invoice_type', function () {
            //resetField(['#ap_purchase_order_no']);
            resetTaxVatSecField();
            enableDisablePoCheck(0);
            enableDisableTaxVatPercentageField(0);
            $("#ap_calculate_tax_vat").prop('checked', false);
            $("#ap_calculate_tax_vat").prop('disabled', true);

            if (($(this).val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}})) {
                //Adding tax, vat required
                $(".ap_tax_amount_ccy_label").addClass('required');
                $("#ap_tax_amount_ccy").attr('required','required');

                $(".ap_vat_amount_ccy_label").addClass('required');
                $("#ap_vat_amount_ccy").attr('required','required');
            } else {
                //Removing tax, vat required
                $(".ap_tax_amount_ccy_label").removeClass('required');
                $("#ap_tax_amount_ccy").removeAttr('required','required');

                $(".ap_vat_amount_ccy_label").removeClass('required');
                $("#ap_vat_amount_ccy").removeAttr('required','required');
            }
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

                if (invoiceArray[3] == '1') {
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
                } else {
                    resetTaxVatSecField();
                    $("#ap_calculate_tax_vat").prop('disabled', true);
                    enableDisableTaxVatPercentageField(0);
                }

                //Enable disable distribution area
                $("#ap_distribution_flag").val(invoiceArray[4]);
                enableDisableSaveBtn();
                if (invoiceArray[4] == '0') {
                    $(".distribution_line_div").removeClass('d-none');
                    $("#ap_account_id").removeAttr('readonly');
                    $("#ap_amount_ccy").removeAttr('readonly');
                } else {
                    resetDistributionArea();
                }
            } else {
                resetTaxVatSecField();
                setPayable("ccy")
                setPayable("lcy")
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
                }
                $("#ap_posting_name").val($(this).find(':selected').data('postingname'));

                $("#ap_payment_due_date >input").val("");
                if (paymentCalendarClickCounter > 0) {
                    $("#ap_payment_due_date").datetimepicker('destroy');
                    paymentCalendarClickCounter = 0;
                }
            });

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
                $("#ap_payment_due_date_field").val("");
                if (!nullEmptyUndefinedChecked($("#posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#posting_date_field").val()).format("DD-MM-YYYY");
                    } else {
                        newDueDate = moment($("#posting_date_field").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
                    }
                    $("#ap_payment_due_date_field").val(newDueDate);
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

            $("#invoice_bill_entry_form").on("submit", function (e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit' + '<br>' +
                        'Party/Vendor ID: ' + $("#ap_vendor_id").val() + '<br>' +
                        'Party/Vendor Name: ' + $("#ap_vendor_name").val() + '<br>'+
                        'Invoice Amount: ' + $("#ap_invoice_amount_lcy").val() + '<br>' +
                        'Payable Amount: ' + $("#ap_payable_amount_lcy").val() + '<br>' ,
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
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    let url = '{{ route('invoice-bill-entry.index') }}';
                                    window.location.href = url;
                                });
                            } else {

                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                            //Swal.fire({text:textStatus+jqXHR,type:'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
            })

            listBillRegister();
            //datePicker('#ap_payment_due_date')
            datePicker('#po_date')
            datePicker('#invoice_date')
        });
    </script>
@endsection


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
            @include("ar.invoice-bill-entry.form")
        </div>
    </div>
    @include('ar.ar-common.common_po_list_modal')
    @include('ar.ar-common.common_customer_list_modal')
    @include('ar.ar-common.common_coalist_modal')
@endsection

@section('footer-script')
    <script type="text/javascript">
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;

        $("#ar_party_sub_ledger").on('change', function () {
            let subsidiaryId = $(this).val();
            $("#ar_transaction_type").val("");
            resetField(['#ar_customer_id','#ar_customer_name','#ar_customer_category'])
            //resetTaxVatSecField();
            //enableDisablePoCheck(0);
            let transactionTypeSelector = $("#ar_transaction_type");

            if (!nullEmptyUndefinedChecked(subsidiaryId)) {
                var request = $.ajax({
                    url: APP_URL + '/account-receivable/ajax/get-invoice-types-on-subsidiary',
                    data: {subsidiaryId: subsidiaryId}
                });

                request.done(function (d) {
                    transactionTypeSelector.trigger('change');
                    if (!$.isEmptyObject(d)) {
                        transactionTypeSelector.html(d);
                    } else {
                        transactionTypeSelector.html('<option value="">Select Invoice Type</option>');
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    console.log("Exception Occurred.");
                });

                if (transactionTypeSelector.data('pretransaction') != "") {
                    transactionTypeSelector.val(transactionTypeSelector.data('pretransaction'));
                }

            } else {
                transactionTypeSelector.trigger('change');
                transactionTypeSelector.html('<option value="">Select Transaction Type</option>');
            }
        })

        $("#ar_payment_currency").on('change', function () {
            resetField([
                /*'#ar_invoice_amount_ccy',
                '#ar_tax_amount_ccy',
                '#ar_vat_amount_ccy',
                '#ar_security_deposit_amount_ccy',
                '#ar_receivable_amount_ccy',*/
                '#ar_invoice_amount_lcy',
                '#ar_tax_amount_lcy',
                '#ar_vat_amount_lcy',
                '#ar_security_deposit_amount_lcy',
                '#ar_receivable_amount_lcy'
            ]);
            openCloseAmountRateLcy($(this).val());
        });
        $("#ar_exchange_rate").on('keyup', function () {
            setReceivable();
        });

        function openCloseAmountRateLcy(currency) {
            if (nullEmptyUndefinedChecked($("#ar_invoice_amount_ccy").val())) {
                $("#ar_invoice_amount_ccy").val('0');
                $("#ar_tax_amount_ccy").val('0');
                $("#ar_vat_amount_ccy").val('0');
                $("#ar_security_deposit_amount_ccy").val('0');
                $("#ar_receivable_amount_ccy").val('0');
            }

            $("#ar_invoice_amount_lcy").val('0');
            $("#ar_tax_amount_lcy").val('0');
            $("#ar_vat_amount_lcy").val('0');
            $("#ar_security_deposit_amount_lcy").val('0');
            $("#ar_receivable_amount_lcy").val('0');

            if (currency == "{{ \App\Enums\Common\Currencies::O_BD }}") {
                $("#ar_exchange_rate").val('1');
                $("#ar_exchange_rate").attr('readonly', 'readonly');

                $("#ar_exchange_rate").keyup();
            } else {
                $("#ar_exchange_rate").val('0');
                $("#ar_exchange_rate").removeAttr('readonly');
            }

            /**New requirement Imam vai: 28-07-2022**/
            $("#ar_currency").val(currency);
            $("#ar_acc_exchange_rate").val($("#ar_exchange_rate").val());
            $("#ar_amount_ccy").trigger('keyup');
        }

        $(document).on('keyup', '#ar_invoice_amount_ccy, #ar_vat_amount_ccy', function () {
            calculateLcy(this);

            if ($("#ar_calculate_tax_vat").prop('checked')) {
                setCalculatedTaxVatDeposit("#ar_vat_amount_ccy_percentage");
            }

            if ($(this).attr('id') == 'ar_invoice_amount_ccy'){
                $("#ar_amount_word_ccy").val(amountTranslate($(this).val()));
            }

            setReceivable("ccy");
            setReceivable("lcy");
            enableDisableSaveBtn();
        });

        $(document).on('keyup', '#ar_exchange_rate', function () {
            calculateLcy("#ar_invoice_amount_ccy");
            if ($("#ar_calculate_tax_vat").prop('checked')) {
                setCalculatedTaxVatDeposit("#ar_vat_amount_ccy_percentage");
            }else if (!nullEmptyUndefinedChecked($("#ar_invoice_amount_lcy").val())){
                calculateLcy("#ar_vat_amount_ccy")
            }
            setReceivable("ccy");
            setReceivable("lcy");

            /**New requirement Imam vai: 28-07-2022**/
            $("#ar_acc_exchange_rate").val($(this).val());
            $("#ar_amount_ccy").trigger('keyup');
        })

        function calculateLcy(selector) {
            let ccy = $(selector).val();
            if ($(selector).parent().next('div').children('input[type=text]').length > 0) {
                //For Invoice number
                let value = !isNaN(parseFloat(ccy) * parseFloat($("#ar_exchange_rate").val())) ? (parseFloat(ccy) * parseFloat($("#ar_exchange_rate").val())).toFixed(2) : "0.00";
                $(selector).parent().next('div').children('input[type=text]').val(value);
            } else {
                //For TAX, VAT, Security Deposit
                let value = !isNaN(parseFloat(ccy) * parseFloat($("#ar_exchange_rate").val())) ? (parseFloat(ccy) * parseFloat($("#ar_exchange_rate").val())).toFixed(2) : "0.00";
                $(selector).parent().parent().parent().next('div').children('input[type=text]').val(value);
            }
        }

        function setReceivable(subStr) {
            let vat = 0;
            let invoiceAmount = 0;
            let addtion = 0;

            invoiceAmount = (nullEmptyUndefinedChecked($("#ar_invoice_amount_" + subStr).val()) ? 0 : parseFloat($("#ar_invoice_amount_" + subStr).val()));
            vat = (nullEmptyUndefinedChecked($("#ar_vat_amount_" + subStr).val()) ? 0 : parseFloat($("#ar_vat_amount_" + subStr).val()));
            addtion = vat + invoiceAmount;
            if (!nullEmptyUndefinedChecked(addtion) && (addtion >= invoiceAmount)) {
                $("#ar_receivable_amount_" + subStr).val(addtion.toFixed(2));
            } else {
                $("#ar_receivable_amount_" + subStr).val("0");
            }
        }

        $("#ar_payment_terms").on('change', function () {
            let postingDate = $("#posting_date >input").val();
            let paymentTerm = $("#ar_payment_terms").find(':selected').data('termdate');

            if (!nullEmptyUndefinedChecked(postingDate)) {
                if (!nullEmptyUndefinedChecked(paymentTerm)) {

                    let newData = moment(postingDate, "DD-MM-YYYY").add(paymentTerm, 'days').format("DD-MM-YYYY");
                    $("#ar_payment_due_date_field").val(newData);

                } else {
                    $("#ar_payment_due_date_field").val(postingDate);
                }
            } else {
                $("#ar_payment_terms").select2("val", "");
                if (!nullEmptyUndefinedChecked($("#period :selected").val())){
                    $("#posting_date").focus();
                    $('html, body').animate({scrollTop: ($("#posting_date").offset().top - 400)}, 2000);
                    $("#posting_date").notify("Set posting date", "info");
                }
            }
        });

        /*
        * Customer search starts from here
        * */
        $("#ar_customer_search").on("click", function () {
            let customerId = $('#ar_customer_id').val();

            if (!nullEmptyUndefinedChecked(customerId)) {
                getCustomerDetail(customerId);
            } else {
                reloadCustomerListTable();
                $("#customerListModal").modal('show');
            }
        });

        function reloadCustomerListTable() {
            $('#customerSearch').data("dt_params", {
                customerCategory: $('#search_customer_category :selected').val(),
                customerName: $('#search_customer_name').val(),
                customerShortName: $('#search_customer_short_name').val(),
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
            getCustomerDetail($(this).data('customer'));
        });

        function getCustomerDetail(customer_id) {
            let invoiceParams = $("#ar_transaction_type").find(':selected').data("invoiceparams");
            let customerType = '';
            let customerCategory = '';

            var request = $.ajax({
                url: APP_URL + '/account-receivable/ajax/customer-details',
                data: {customerId: customer_id, customerType: customerType, customerCategory: customerCategory}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $("#ar_customer_id").notify("Customer id not found", "error");
                    resetField(['#ar_customer_id', '#ar_customer_name', '#ar_customer_category']);
                    enableDisablePoCheck(0)
                } else {
                    $('#ar_customer_id').val(d.customer_id);
                    $('#ar_customer_name').val(d.customer_name);
                    $('#ar_customer_category').val(d.customer_category.customer_category_name);
                    enableDisablePoCheck(1)
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
        /*
        * customer search ends here
        * */

        $("#search_po").on('click', function () {
            let poNumber = $("#po_number").val();
            //for update purpose
            //let invoiceID = $("#invoice_id").val();
            $("#modal_customer_name").html("<option value='" + $("#ar_customer_id").val() + "'>" + $("#ar_customer_name").val() + "</option>")
            $("#poListModal").modal('show');
        });

        let poSearchTable = $('#poSearchTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_URL + '/account-receivable/ajax/po-search-datalist',
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
                customer: $('#modal_customer_name :selected').val(),
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
            $("#ar_purchase_order_no").val(poInfoArray[1]);
            $("#ar_purchase_order_date").val(poInfoArray[2]);
            $("#document_number").val(poInfoArray[3]);
            $("#document_date_field").val(poInfoArray[4]);
            $("#ar_invoice_amount_ccy").val(poInfoArray[5]);

            $("#poListModal").modal('hide');

            setReceivable('ccy');
            calculateLcy('#ar_invoice_amount_ccy');
            setReceivable('lcy');
            calculateLcy('#ar_receivable_amount_ccy');
        })

        $("#ar_calculate_tax_vat").on("click", function () {
            //resetTaxVatSecField();
            if ($(this).prop('checked')) {
                enableDisableTaxVatPercentageFieldOnCheckbox(1);
            } else {
                enableDisableTaxVatPercentageFieldOnCheckbox(0);

                //$("#ar_transaction_type").trigger('change');
            }
        });

        function enableDisableTaxVatPercentageFieldOnCheckbox(status) {
            resetField(["#ar_tax_amount_ccy_percentage",
                "#ar_vat_amount_ccy_percentage",
                "#ar_security_deposit_amount_ccy_percentage",
                "#ar_tax_amount_ccy",
                "#ar_tax_amount_lcy",
                "#ar_vat_amount_ccy",
                "#ar_vat_amount_lcy",
                "#ar_security_deposit_amount_ccy",
                "#ar_security_deposit_amount_lcy"]);

            if (status == 0) {
                $("#ar_tax_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ar_vat_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ar_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");


                $("#ar_tax_amount_ccy").removeAttr("readonly");
                $("#ar_vat_amount_ccy").removeAttr("readonly");
                $("#ar_security_deposit_amount_ccy").removeAttr("readonly");
            } else {
                $("#ar_tax_amount_ccy_percentage").removeAttr("readonly");
                $("#ar_vat_amount_ccy_percentage").removeAttr("readonly");
                $("#ar_security_deposit_amount_ccy_percentage").removeAttr("readonly");


                $("#ar_tax_amount_ccy").attr("readonly", "readonly");
                $("#ar_vat_amount_ccy").attr("readonly", "readonly");
                $("#ar_security_deposit_amount_ccy").attr("readonly", "readonly");
            }

            setReceivable("ccy");
            setReceivable("lcy");
        }

        function enableDisableTaxVatPercentageField(status) {
            resetField(["#ar_tax_amount_ccy_percentage",
                "#ar_vat_amount_ccy_percentage",
                "#ar_security_deposit_amount_ccy_percentage",
                "#ar_tax_amount_ccy",
                "#ar_tax_amount_lcy",
                "#ar_vat_amount_ccy",
                "#ar_vat_amount_lcy",
                "#ar_security_deposit_amount_ccy",
                "#ar_security_deposit_amount_lcy"]);

            if (status == 0) {
                $("#ar_tax_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ar_vat_amount_ccy_percentage").attr("readonly", "readonly");
                $("#ar_security_deposit_amount_ccy_percentage").attr("readonly", "readonly");


                /*$("#ar_tax_amount_ccy").removeAttr("readonly");
                $("#ar_vat_amount_ccy").removeAttr("readonly");
                $("#ar_security_deposit_amount_ccy").removeAttr("readonly");*/
            } else {
                $("#ar_tax_amount_ccy_percentage").removeAttr("readonly");
                $("#ar_vat_amount_ccy_percentage").removeAttr("readonly");
                $("#ar_security_deposit_amount_ccy_percentage").removeAttr("readonly");


                /*$("#ar_tax_amount_ccy").attr("readonly", "readonly");
                $("#ar_vat_amount_ccy").attr("readonly", "readonly");
                $("#ar_security_deposit_amount_ccy").attr("readonly", "readonly");*/
            }

            setReceivable("ccy");
            setReceivable("lcy");
        }

        $(document).on("keyup", "#ar_vat_amount_ccy_percentage, #ar_security_deposit_amount_ccy_percentage", function () {
            if (parseFloat($(this).val()) > 100) {
                $(this).val(100).notify("Enter value between 0-100", "info");
            }
            setCalculatedTaxVatDeposit(this);
        });

        function setCalculatedTaxVatDeposit(selector) {
            let percentege = $(selector).val();
            let invoiceAmountCcy = parseFloat((nullEmptyUndefinedChecked($("#ar_invoice_amount_ccy").val())) ? 0 : $("#ar_invoice_amount_ccy").val());
            let amountOnPercent = (((invoiceAmountCcy + Number.EPSILON) * percentege) / 100).toFixed(2);
            $(selector).parent().next('div').children('input[type=text]').val(amountOnPercent);
            calculateLcy("#" + $(selector).parent().next('div').children('input[type=text]').attr('id'));

            setReceivable('ccy');
            setReceivable('lcy');
        }

        function applyRemovePOresultFieldsEffect(key) {
            if (key == 1) {
                $("#po_master_id").val("");
                $("#document_number").val("").attr('readonly', 'readonly');
                $("#document_date_field").val("").attr('readonly', 'readonly');
                $("#document_date").addClass('make-readonly');
                $("#ar_invoice_amount_ccy").val("").attr('readonly', 'readonly');
                $("#ar_invoice_amount_lcy").val("");
                $("#ar_receivable_amount_ccy").val("");
                $("#ar_receivable_amount_lcy").val("");

            } else {
                $("#po_master_id").val("");
                $("#document_number").removeAttr('readonly', 'readonly');
                $("#document_date_field").removeAttr('readonly', 'readonly');
                $("#document_date").removeClass('make-readonly');
                $("#ar_invoice_amount_ccy").val("").removeAttr('readonly', 'readonly');
                $("#ar_invoice_amount_lcy").val("");
                $("#ar_receivable_amount_ccy").val("");
                $("#ar_receivable_amount_lcy").val("");
            }
        }

        $("#ar_hold_all_payment").on('click', function () {
            if ($(this).prop('checked')) {
                $("#ar_hold_all_payment_reason").removeAttr('readonly');
                $("#ar_hold_all_payment_reason").attr('required', 'required');
                $("#ar_hold_all_payment_reason").parent().prev('label').addClass('required');
            } else {
                $("#ar_hold_all_payment_reason").attr('readonly', 'readonly').val("");
                $("#ar_hold_all_payment_reason").removeAttr('required', 'required');
                $("#ar_hold_all_payment_reason").parent().prev('label').removeClass('required');
            }
        });


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
                url: APP_URL + '/account-receivable/ajax/invoice-acc-datalist',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.glType = $('#acc_type').val();
                    params.costCenterDpt = $('#department').val(); //Add Part :pavel-31-01-22
                    params.searchText = $('#acc_name_code').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id","width":"15%"},
                {"data": "gl_acc_name"},
                {"data": "gl_acc_code","width":"15%"},
                /*{"data": "dept_name"},*/ //Add Part :pavel-31-01-22
                {"data": "action", "orderable": false,"width":"10%"}
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
        $("#ar_search_account").on("click", function () {
            let accId = $("#ar_account_id").val();
            let costCenterDpt = $('#department').val(); //Add Part :pavel-31-01-22

            if (!nullEmptyUndefinedChecked(accId)) {
                getAccountDetail(accId);
            } else {
                //Add IF Part :pavel-31-01-22
                if ( nullEmptyUndefinedChecked(costCenterDpt) ){
                    $("#department").focus();
                    $('html, body').animate({scrollTop: ($("#department").offset().top - 400)}, 2000);
                    $("#department").notify("Select Department First.", {position: 'left'});
                } else {
                    $("#acc_type option[value='1']").remove();
                    $("#acc_type option[value='4']").remove();
                    $("#acc_department").val($("#department :selected").text())
                    $("#accountListModal").modal('show');
                    accountTable.draw();
                }
            }
        });
        getAccountDetail = function (accId) {
            let allowedGlType = [{{\App\Enums\Common\GlCoaParams::INCOME}}];

            var request = $.ajax({
                url: APP_URL + '/account-receivable/ajax/account-details-for-invoice-entry',
                data: {accId: accId, allowedGlType: allowedGlType},
            });

            request.done(function (d) {
                resetField(['#ar_account_name', '#ar_authorized_balance', '#ar_account_balance', '#ar_account_type', '#ar_budget_head', '#ar_amount_ccy', '#ar_amount_lcy', '#ar_amount_word']);

                if ($.isEmptyObject(d)) {
                    $("#ar_account_id").notify("Account id not found", "error");
                } else {
                    $("#ar_account_id").val(d.gl_acc_id);
                    $("#ar_account_name").val(d.gl_acc_name);
                    $("#ar_account_type").val(d.gl_type_name);
                    $("#ar_account_balance").val(d.account_balance);
                    $("#ar_authorized_balance").val(d.authorize_balance);
                    $("#ar_budget_head").val(d.budget_head_line_name);
                    /**New requirement Imam vai: 28-07-2022
                    $("#ar_currency").val(d.currency_code);
                    $("#ar_acc_exchange_rate").val(d.exchange_rate);
                     **/

                    if (nullEmptyUndefinedChecked(d.cost_center_dept_name)) {
                        $("#department_cost_center").html('');
                    } else {
                        $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                    }

                    //openCloseCreditRateLcy(d.currency_code);

                    $("#accountListModal").modal('hide');

                    $("#ar_amount_ccy").focus();
                    $('html, body').animate({scrollTop: ($("#ar_amount_ccy").offset().top - 400)}, 2000);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }
        $("#ar_amount_ccy").on("keyup", function () {
            let c_amount_ccy_keyup = parseFloat($(this).val());
            if (!is_negative(c_amount_ccy_keyup) && c_amount_ccy_keyup != 0) {
                let c_exchange_rate_get = parseFloat($("#ar_acc_exchange_rate").val());
                //$('#c_amount_ccy').val(c_amount_ccy_keyup);

                if (c_amount_ccy_keyup && c_exchange_rate_get) {
                    let lcy = (c_amount_ccy_keyup * c_exchange_rate_get);
                    $('#ar_amount_lcy').val(lcy);
                } else {
                    $('#ar_amount_lcy').val('0');
                }
            } else {
                $('#ar_amount_ccy').val('0');
                $('#ar_amount_lcy').val('0');
            }
        });
        addLineRow = function (selector) {

            let invoiceParams = $("#ar_transaction_type").find(':selected').data("invoiceparams");

            // let invoiceArray = invoiceParams.split("#");
            /*
            0=> D/R flag
            1=> customer type
            2=> customer category
            3=> Source Allow Flag
            4=> distribution line enable/disable
             */

            if (fieldsAreSet(['#ar_amount_ccy', '#ar_account_id', '#ar_account_name', '#ar_amount_lcy'])) {
                if ($(selector).attr('data-type') == 'A') {
                    let count = $("#ar_account_table >tbody").children("tr").length;

                    let html = '<tr>\n' +
                        '      <td style="padding: 4px;"><input tabindex="-1" name="line[' + count + '][ar_account_code]" id="account_code' + count + '" class="form-control form-control-sm" value="' + $('#ar_account_id').val() + '" readonly/></td>\n' +
                        /*'      <td style="padding: 4px">' +

                        '</td></td>\n' +*/
                        '      <td style="padding: 4px;">' +
                        '<input tabindex="-1" name="line[' + count + '][ar_account_name]" id="account_name' + count + '" class="form-control form-control-sm" value="' + $('#ar_account_name').val() + '" readonly/>' +
                        /*
                                                    '<span class="ar_dr_cr_text" style="text-align: center;"></span>' +
                        */
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_currency]" id="currency' + count + '" value="' + $("#ar_currency").val() + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_dr_cr]" class="ar_dr_cr" id="ar_dr_cr' + count + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_exchange_rate]" id="exchange_rate' + count + '" value="' + $('#ar_acc_exchange_rate').val() + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_acc_type]" id="account_type' + count + '" value="' + $('#ar_account_type').val() + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_budget_head]" id="budget_head' + count + '" value="' + $('#ar_budget_head').val() + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_acc_balance]" id="account_balance' + count + '" value="' + $('#ar_account_balance').val() + '"/>' +
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_authorized_balance]" id="authorized_balance' + count + '" value="' + $('#ar_authorized_balance').val() + '"/>' +
                        /*'<input tabindex="-1" type="hidden" name="line[' + count + '][ar_narration]" id="narration' + count + '" value="' + $('#ar_narration').val() + '"/>' +*/
                        '<input tabindex="-1" type="hidden" name="line[' + count + '][ar_action_type]" id="action_type' + count + '" value="A" />' +
                        '</td>\n' +
                        '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align" name="line[' + count + '][ar_amount_ccy]" id="ccy' + count + '" value="' + $('#ar_amount_ccy').val() + '" readonly></td>\n' +
                        '<td style="padding: 4px;"><input tabindex="-1" type="text" class="form-control form-control-sm text-right-align lcy" name="line[' + count + '][ar_amount_lcy]" id="lcy' + count + '" value="' + $('#ar_amount_lcy').val() + '" readonly></td>\n' +
                        '      <td style="padding: 4px;"><span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editAccountBtn" onclick="editAccount(this,' + count + ')" >Edit</span>|<span id="ar_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span></td>\n' +
                        '  </tr>';
                    $("#ar_account_table >tbody").append(html);

                    //Setting distribution type
                    // if (invoiceArray[0] == 'D') {
                    //     $(".ar_dr_cr_text").html('Credit');
                    //     $(".ar_dr_cr").val('C');
                    // } else {
                    //     $(".ar_dr_cr_text").html('Debit');
                    //     $(".ar_dr_cr").val('D');
                    // }

                } else {
                    var lineToUpdate = $(selector).attr('data-line');
                    updateLineValue(lineToUpdate);
                }

                if (totalLcy() != parseFloat($("#ar_invoice_amount_lcy").val())) {
                    $("#ar_account_id").val('').focus();
                    $('html, body').animate({scrollTop: ($("#ar_account_id").offset().top - 400)}, 2000);
                } else {
                    $("#invoice_bill_entry_form_submit_btn").focus();
                    $('html, body').animate({scrollTop: ($("#invoice_bill_entry_form_submit_btn").offset().top - 400)}, 2000);
                }

                //resetField(['#ar_account_id', '#ar_account_name', '#ar_account_type', '#ar_account_balance', '#ar_authorized_balance', '#ar_budget_head', '#ar_currency', '#ar_amount_ccy', '#ar_amount_lcy', '#ar_acc_exchange_rate', '#ar_amount_word']);
                resetAccountField();
                resetField(['#ar_account_id'])
                setTotalLcy();
                enableDisableSaveBtn();

                $("#ar_currency").val($("#ar_payment_currency").val());
                $("#ar_acc_exchange_rate").val($("#ar_exchange_rate").val());
                //openCloseCreditRateLcy('');
            } /*else {
                            $(selector).notify("Missing input.", "error", {position: "left"});
                        }*/
        }
        removeLineRow = function (select, lineRow) {
            $("#action_type" + lineRow).val('D');
            $(select).closest("tr").remove();
            setTotalLcy();
            enableDisableSaveBtn();
            //openCloseCreditRateLcy('');
        }
        editAccount = function (selector, line) {
            $("#ar_remove_btn" + line).hide();

            $("#ar_account_id").val($("#account_code" + line).val());
            $("#ar_account_name").val($("#account_name" + line).val());
            $("#ar_account_type").val($("#account_type" + line).val());
            $("#ar_account_balance").val($("#account_balance" + line).val());
            $("#ar_authorized_balance").val($("#authorized_balance" + line).val());
            $("#ar_budget_head").val($("#budget_head" + line).val());
            $("#ar_currency").val($("#currency" + line).val());
            $("#ar_amount_ccy").val($("#ccy" + line).val());
            $("#ar_amount_lcy").val($("#lcy" + line).val());
            $("#ar_acc_exchange_rate").val($("#exchange_rate" + line).val());
            //$("#ar_narration").val($("#narration" + line).val());
            $(".editAccountBtn").addClass('d-none');
            //removeLineRow(selector,line);
            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-edit'></i>UPDATE");
            $(select).attr('data-type', 'U');
            $(select).attr('data-line', line);
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            $("#ar_amount_word").val(amountTranslate($("#ccy" + line).val()));
        }

        function updateLineValue(line) {
            $("#account_code" + line).val($("#ar_account_id").val());
            $("#account_name" + line).val($("#ar_account_name").val());
            $("#account_type" + line).val($("#ar_account_type").val());
            $("#account_balance" + line).val($("#ar_account_balance").val());
            $("#authorized_balance" + line).val($("#ar_authorized_balance").val());
            $("#budget_head" + line).val($("#ar_budget_head").val());
            $("#currency" + line).val($("#ar_currency").val());
            $("#ccy" + line).val($("#ar_amount_ccy").val());
            $("#lcy" + line).val($("#ar_amount_lcy").val());
            $("#exchange_rate" + line).val($("#ar_acc_exchange_rate").val());
            //$("#narration" + line).val($("#ar_narration").val());
            $(".editAccountBtn").removeClass('d-none');

            var select = "#addNewLineBtn";
            $(select).html("<i class='bx bx-plus-circle'></i>ADD");
            $(select).attr('data-type', 'A');
            $(select).attr('data-line', '');
            $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
            enableDisableSaveBtn();
            $("#ar_remove_btn" + line).show();
        }

        function setTotalLcy() {
            $("#total_lcy").val(totalLcy());
        }

        function totalLcy() {
            let lcy = $("#ar_account_table >tbody >tr").find(".lcy");
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

        $("#ar_amount_ccy").on('keyup', function () {
            $("#ar_amount_word").val(amountTranslate($(this).val()));
        });

        function resetAccountField() {
            resetField(['#ar_account_name', '#ar_account_type', '#ar_account_balance', '#ar_authorized_balance', '#ar_budget_head', '#ar_currency', '#ar_amount_ccy', '#ar_amount_lcy', '#ar_acc_exchange_rate', '#ar_amount_word']);
        }

        function enableDisableSaveBtn() {
            if (nullEmptyUndefinedChecked(totalLcy()) || nullEmptyUndefinedChecked($("#ar_invoice_amount_lcy").val()) || (totalLcy() != parseFloat($("#ar_invoice_amount_lcy").val()))) {
                $("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
            } else {
                $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
            }
        }

        function resetTaxVatSecField() {
            $("#ar_invoice_amount_ccy").val("");
            $("#ar_invoice_amount_lcy").val("");
            $("#ar_receivable_amount_ccy").val("");
            $("#ar_receivable_amount_lcy").val("");

            $("#ar_tax_amount_ccy").val("").attr('readonly', 'readonly');
            //$("#ar_vat_amount_ccy").val("").attr('readonly', 'readonly');
            $("#ar_security_deposit_amount_ccy").val("").attr('readonly', 'readonly');

            $("#ar_tax_amount_lcy").val("");
            $("#ar_vat_amount_ccy").val("");
            $("#ar_vat_amount_lcy").val("");
            $("#ar_security_deposit_amount_lcy").val("");

            resetField(["#ar_tax_amount_ccy_percentage", "#ar_vat_amount_ccy_percentage", "#ar_security_deposit_amount_ccy_percentage", "#ar_customer_id", "#ar_customer_name", "#ar_customer_category", "#search_customer_type", "#search_customer_category"])
        }

        function resetDistributionArea() {
            $("#ar_distribution_flag").val(1);
            //$("#ar_amount_ccy").attr('readonly', 'readonly');
            resetField(['#ar_account_id', '#ar_account_balance', '#ar_account_name', '#ar_authorized_balance', '#ar_account_type', '#ar_budget_head', '#ar_currency', '#ar_amount_ccy', '#ar_acc_exchange_rate', '#ar_amount_lcy', '#ar_amount_word',])
            resetTablesDynamicRow("#ar_account_table");
            $("#total_lcy").val('');
            enableDisableSaveBtn();
        }

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
                if (($("#ar_transaction_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}})) {
                    $("#po_based_yn").prop('disabled', false);

                    if (!$("#po_based_yn").prop("checked")) {
                        $("#po_based_yn").notify("I am enabled.", "info");
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
                resetField(["#ar_purchase_order_no", "#ar_purchase_order_date"])
            }
        }


        function setPaymentDueDate(selector) {
            $("#ar_payment_due_date >input").val("");
            let minDate = $("#period :selected").data("mindate");
            let maxDate = $("#period :selected").data("maxdate");
            let currentDate = $("#period :selected").data("currentdate");
            datePickerOnPeriod(selector, minDate, maxDate, currentDate);
        }

        $(document).ready(function () {
            $(".po_base_invoice").hide();

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let paymentCalendarClickCounter = 0;
            $("#ar_posting_name").val($("#period").find(':selected').data('postingname'));

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
                $("#ar_posting_name").val($(this).find(':selected').data('postingname'));

                $("#ar_payment_due_date >input").val("");
                if (paymentCalendarClickCounter > 0) {
                    $("#ar_payment_due_date").datetimepicker('destroy');
                    paymentCalendarClickCounter = 0;
                }

                setPeriodCurrentDate()
            });

            /********Added on: 06/06/2022, sujon**********/
            function setPeriodCurrentDate(){
                $("#posting_date_field").val($("#period :selected").data("currentdate"));
                $("#document_date_field").val($("#period :selected").data("currentdate"));

                $("#ar_payment_terms").trigger('change');
            }
            //setPeriodCurrentDate()
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
                $("#ar_payment_due_date_field").val("");
                if (!nullEmptyUndefinedChecked($("#posting_date_field").val())) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment($("#posting_date_field").val(), "YYYY-MM-DD");
                    } else {
                        newDueDate = moment($("#posting_date_field").val(), "DD-MM-YYYY");
                    }
                    //$("#ar_payment_due_date_field").val(newDueDate);
                    $("#ar_payment_terms").select2().trigger('change');
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

            /*$("#ar_payment_due_date").on('click', function () {
                paymentCalendarClickCounter++;
                setPaymentDueDate(this);
            });*/

            function listBillRegister() {
                $('#bill_section').change(function (e) {
                    $("#bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#bill_register', APP_URL + '/account-receivable/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }

            /********Added on: 06/06/2022, sujon**********/
            function setBillSection(){
                $("#bill_register").change(function (e) {
                    $bill_sec_id = $("#bill_register :selected").data('secid');
                    $bill_sec_name = $("#bill_register :selected").data('secname');
                    if (!nullEmptyUndefinedChecked($bill_sec_id)){
                        $("#bill_section").html("<option value='"+$bill_sec_id+"'>"+$bill_sec_name+"</option>")
                    }else{
                        $("#bill_section").html("<option value=''></option>")
                    }
                });
            }
            //setBillSection();
            /********End**********/

            $("#invoice_bill_entry_form").on("submit", function (e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit' + '<br>' +
                        'Customer ID: ' + $("#ar_customer_id").val() + '<br>' +
                        'Customer Name: ' + $("#ar_customer_name").val() + '<br>' +
                        'Invoice Amount: ' + $("#ar_invoice_amount_lcy").val() + '<br>' +
                        'Receivable Amount: ' + $("#ar_receivable_amount_lcy").val() + '<br>' ,
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
                            url: APP_URL + "/account-receivable/ar-invoice-bill-entry",
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
                                    $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_RECEIVABLE/RPT_AR_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');
                                    focusOnMe("#document_number");
                                    {{--let url = '{{ route('ar-invoice-bill-entry.index') }}';--}}
                                    {{--window.location.href = url;--}}
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
            //datePicker('#ar_payment_due_date')
            datePicker('#po_date')
            datePicker('#invoice_date')


            $("#reset_form").on('click',function () {
                resetTablesDynamicRow();
                removeAllAttachments();
                resetDistributionArea();
                resetTaxVatSecField();
                /*0003183: Need not to refresh the narration & party subledger for AP Module*/
                $("#ar_party_sub_ledger").trigger('change');
                //resetField(['#narration',]);
            })

            $("#th_fiscal_year").on('change',function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#period").html(periods);
                //setPeriodCurrentDate();
                $("#period").trigger('change');
            }
        });
    </script>
@endsection


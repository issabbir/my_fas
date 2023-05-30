@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('ap.invoice-bill-parameter.form')
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-sm dataTable" id="invoice_bill_parameters">
                        <thead class="thead-dark">
                        <tr>
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
            enableDisableTaxVatSec();
            enableDisableContraSubLedger();
            enableDisableVendorCategory();
        });

        $("#party_sub_ledger").on('change', function () {
            //if ((($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}}) || ($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::CREDIT_MEMO}})) && ($("#is_party_subLedger").prop('checked'))) {
                let preSelectedContra = $("#contra_sub_ledger").data("precontra");
                getContraSubLedger($('#party_sub_ledger :selected').val(), preSelectedContra, setOptions);
            //}
        });

        $("#invoice_type").on('change', function () {
            if (!($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}}) || !($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::CREDIT_MEMO}})) {
                resetField(['#tax_account_id',
                    '#vat_account_id',
                    '#deposit_account_id',
                    /*'#fine_account_id',
                    '#psi_account_id',                      //Block this ids pavel:06-04-22
                    '#electricity_account_id',
                    '#other_account_id',*/
                    '#contra_sub_ledger',
                    "#tax_search_account_name",
                    "#vat_search_account_name",
                    "#deposit_search_account_name",
                    /*"#fine_search_account_name",
                    "#psi_search_account_name",
                    "#electricity_search_account_name",     //Block this Ids pavel:06-04-22
                    "#other_search_account_name"*/
                ]);

                $("#deduction_allowed").prop('checked', false);
                $("#tax_account_id").attr('readonly', 'readonly');
                $("#tax_search_account").attr('disabled', 'disabled');

                $("#vat_account_id").attr('readonly', 'readonly');
                $("#vat_search_account").attr('disabled', 'disabled');

                $("#deposit_account_id").attr('readonly', 'readonly');
                $("#deposit_search_account").attr('disabled', 'disabled');

                /*** Block this sec start:06-04-22 ***/
                /*$("#fine_account_id").attr('readonly', 'readonly');
                $("#fine_search_account").attr('disabled', 'disabled');

                $("#psi_account_id").attr('readonly', 'readonly');
                $("#psi_search_account").attr('disabled', 'disabled');

                $("#electricity_account_id").attr('readonly', 'readonly');
                $("#electricity_search_account").attr('disabled', 'disabled');

                $("#other_account_id").attr('readonly', 'readonly');
                $("#other_search_account").attr('disabled', 'disabled');*/
                /*** Block this sec end:06-04-22 ***/

                $("#is_party_subLedger").prop('checked', false);
                $("#contra_sub_ledger").addClass('make-readonly-bg');
            }
        });

        $("#ap_vendor_type").on('change', function () {
            enableDisableVendorCategory();
            getVendorCategory($(this).val());
        });

        function enableDisableVendorCategory() {
            let vendorType = $("#ap_vendor_type :selected").val();
            let vendorCategoryObj = $("#ap_vendor_category");

            if (vendorType == {{\App\Enums\Ap\VendorType::INTERNAL}}) {
                vendorCategoryObj.removeClass("make-readonly-bg");
            } else if (vendorType == {{ \App\Enums\Ap\VendorType::EXTERNAL}}) {
                vendorCategoryObj.val("");
                vendorCategoryObj.addClass("make-readonly-bg");
            }else{
                vendorCategoryObj.val("");
                vendorCategoryObj.addClass("make-readonly-bg");
            }
        }

        function getVendorCategory(vendorType) {
            let request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-category-on-vendor-type',
                data: {vendorTpe: vendorType, preCategoryId:$("#ap_vendor_category").data('predefined')},
                async:false
            });
            request.done(function (response) {
                $("#ap_vendor_category").html(response);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            })

            if (!nullEmptyUndefinedChecked($("#vendor_category").data('predefined'))) {
                let preset = $("#vendor_category").data('predefined');
                $(document).val(preset).trigger('change');
            }
        }


        function enableDisableTaxVatSec() {
            if ($("#deduction_allowed").prop('checked')) {
                if ((($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}}) || ($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::CREDIT_MEMO}}))) {
                    $("#tax_account_id").removeAttr('readonly');
                    $("#tax_search_account").removeAttr('disabled');

                    $("#vat_account_id").removeAttr('readonly');
                    $("#vat_search_account").removeAttr('disabled');

                    $("#deposit_account_id").removeAttr('readonly');
                    $("#deposit_search_account").removeAttr('disabled');

                    /*** Block this section Pavel:06-04-22 ***/
                    /*$("#fine_account_id").removeAttr('readonly');
                    $("#fine_search_account").removeAttr('disabled');

                    $("#psi_account_id").removeAttr('readonly');
                    $("#psi_search_account").removeAttr('disabled');

                    $("#electricity_account_id").removeAttr('readonly');
                    $("#electricity_search_account").removeAttr('disabled');

                    $("#other_account_id").removeAttr('readonly');
                    $("#other_search_account").removeAttr('disabled');*/
                } else {
                    $("#deduction_allowed").prop('checked', false);
                    $("#tax_account_id").attr('readonly', 'readonly');
                    $("#tax_search_account").attr('disabled', 'disabled');

                    $("#vat_account_id").attr('readonly', 'readonly');
                    $("#vat_search_account").attr('disabled', 'disabled');

                    $("#deposit_account_id").attr('readonly', 'readonly');
                    $("#deposit_search_account").attr('disabled', 'disabled');

                    /*** Block this section start Pavel:06-04-22 ***/
                    /*$("#fine_account_id").attr('readonly', 'readonly');
                    $("#fine_search_account").attr('disabled', 'disabled');

                    $("#psi_account_id").attr('readonly', 'readonly');
                    $("#psi_search_account").attr('disabled', 'disabled');

                    $("#electricity_account_id").attr('readonly', 'readonly');
                    $("#electricity_search_account").attr('disabled', 'disabled');

                    $("#other_account_id").attr('readonly', 'readonly');
                    $("#other_search_account").attr('disabled', 'disabled');*/
                    /*** Block this section end Pavel:06-04-22 ***/

                    $("#invoice_type").focus();
                    $("#invoice_type").notify('Select standard / Credit Memo invoice type first.');
                }
            } else {
                $("#tax_account_id").attr('readonly', 'readonly');
                $("#tax_search_account").attr('disabled', 'disabled');

                $("#vat_account_id").attr('readonly', 'readonly');
                $("#vat_search_account").attr('disabled', 'disabled');

                $("#deposit_account_id").attr('readonly', 'readonly');
                $("#deposit_search_account").attr('disabled', 'disabled');

                /*** Block this section start Pavel:06-04-22 ***/
                /*$("#fine_account_id").attr('readonly', 'readonly');
                $("#fine_search_account").attr('disabled', 'disabled');

                $("#psi_account_id").attr('readonly', 'readonly');
                $("#psi_search_account").attr('disabled', 'disabled');

                $("#electricity_account_id").attr('readonly', 'readonly');
                $("#electricity_search_account").attr('disabled', 'disabled');

                $("#other_account_id").attr('readonly', 'readonly');
                $("#other_search_account").attr('disabled', 'disabled');*/
                /*** Block this section end Pavel:06-04-22 ***/
            }
        }

        $("#deduction_allowed").on('click', function () {
            resetField(['#tax_account_id',
                '#vat_account_id',
                '#deposit_account_id',
                /*'#fine_account_id',
                '#psi_account_id',              //Block this ids pavel:06-04-22
                '#electricity_account_id',
                '#other_account_id',*/
                '#contra_sub_ledger',
                "#tax_search_account_name",
                "#vat_search_account_name",
                "#deposit_search_account_name",
                /*"#fine_search_account_name",
                "#psi_search_account_name",
                "#electricity_search_account_name",     //Block this ids pavel:06-04-22
                "#other_search_account_name"*/
            ]);
            enableDisableTaxVatSec();
        });

        function enableDisableContraSubLedger() {
            if ($("#is_party_subLedger").prop('checked')) {
                //if ((($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::STANDARD}}) || ($("#invoice_type").val() == {{\App\Enums\Ap\LApInvoiceType::CREDIT_MEMO}}))) {
                    $("#contra_sub_ledger").removeClass('make-readonly-bg');
                    let preSelectedContra = $("#contra_sub_ledger").data("precontra");
                    getContraSubLedger($('#party_sub_ledger :selected').val(), preSelectedContra, setOptions);
                /*} else {
                    $("#is_party_subLedger").prop('checked', false);
                    $("#contra_sub_ledger").addClass('make-readonly-bg');

                    $("#invoice_type").focus();
                    $("#invoice_type").notify('Select standard invoice type first.');
                }*/
            } else {
                $("#contra_sub_ledger").addClass('make-readonly-bg');
            }
        }

        $("#is_party_subLedger").on('click', function () {
            resetField(["#contra_sub_ledger"]);
            enableDisableContraSubLedger();
        });

        function setOptions(response) {
            $("#contra_sub_ledger").val("").html(response);
        }

        function getContraSubLedger(selectedSubLedger, preSelectedContra, callback) {
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/contra-sub-ledgers',
                method: 'POST',
                data: {selectedLedger: selectedSubLedger, preSelectedContra: preSelectedContra},
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            request.done(function (d) {
                callback(d);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

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
                    /*params.glType = $('#acc_type :selected').val();
                    params.accNameCode = $('#acc_name_code').val();*/

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
                {"data": "gl_acc_name", },
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

        $("#account_id").on("keyup", function () {
            $("#account_name").val('');
        })

        $("#account_type").on("change", function () {
            $("#account_id").val('');
            $("#account_name").val('');
        })

        //$(" #tax_search_account, #vat_search_account, #deposit_search_account, #fine_search_account, #psi_search_account, #electricity_search_account, #other_search_account").on("click", function () {  // Block onlick GL Integration for Income Heads ids Pavel:06-04-22
        $(" #tax_search_account, #vat_search_account, #deposit_search_account").on("click", function () {
            let accId = $(this).parent().parent().children().children('input[type=number]').val();
            let glType = $(this).data('gltype');
            let parentId = "#" + $(this).attr('id'); //Taking parent search button id
            if (!nullEmptyUndefinedChecked(glType)) {
                if (!nullEmptyUndefinedChecked(accId)) {
                    getAccountDetail(glType, accId, this);
                } else {
                    //let glType = $('#account_type :selected').data('gltype');
                    if ($("#acc_search_form").find("#parentId").length != 0){
                        $("#acc_search_form").find("#parentId").remove();
                    }
                    $("#acc_search_form").append('<input type="hidden" id="parentId" value="'+parentId+'"/>');
                    //$("#acc_search_form").find('#acc_search').attr("data-selector", parentId);   //Setting inside modal search button
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
                    //accountTable.draw();
                }
            } else {
                $("#account_type").notify("Select account type first.", "error");
            }
        });

        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            //let parentSelector = $(this).find('#acc_search').data('selector');
            let parentSelector = $("#parentId").val();

            $('#account_list').data("dt_params", {
                glType: $('#acc_type :selected').val(),
                accNameCode: $('#acc_name_code').val(),
                selector: parentSelector
            }).DataTable().draw();
            //accountTable.draw();
        });
        $("#acc_modal_reset").on('click', function () {
            //$("#acc_type").val('');
            $("#acc_name_code").val('');
            $('#account_list').data("dt_params", {
                glType: '',
                accNameCode: ''
            }).DataTable().draw();
        });

        //src = 1 from modal, src = 2 from search
        function getAccountDetail(acc_type, acc_id, selector) {
            var request = $.ajax({
                url: APP_URL + '/account-payable/ajax/gl-type-acc-wise-coa',
                method: 'GET',
                data: {gl_type_id: acc_type, gl_acc_id: acc_id}
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $(selector).parent().parent().children().children('input[type=number]').notify("Account id not found", "error");
                    $(selector).parent().next('div').find('input[type=text]').val();
                } else {
                    $(selector).parent().parent().children().children('input[type=number]').val(d.gl_acc_id);
                    $(selector).parent().next('div').find('input[type=text]').val(d.gl_acc_name);
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
                    let url = "{{route('invoice-bill-parameter.delete', ['id' => ":_p"])}}";
                    let newString = url.replace(":_p", $(selector).data('target'));
                    window.location.href = newString;
                }
            })
        });

        let billParametersTable = $('#invoice_bill_parameters').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            /*bDestroy : true,
            pageLength: 20,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
            ajax: {
                url: APP_URL + '/account-payable/invoice-bill-parameter-datalist',
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "id"},
                {"data": "parameter"},
                {"data": "action"}
            ],
        });


    </script>
@endsection

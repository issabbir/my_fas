<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৯:২৯ AM
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
            @include('ap.vendor-profile.form')
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            datePicker('#opening_date');

            //Only For Update
            getVendorCategory($("#vendor_type :selected").val());
            //addVendorEffect();
            departmentCostCheckEnableDisable();
            enableDisablePaymentField();
            $("#vendor_type").trigger('change');
            enableDisableDateField("#is_inactive");

        });

        $("#vendorProfileForm").on('submit',function (e) {
            e.preventDefault();
            let nameObj = $("#name");
            let vendorTypeObj = $("#vendor_type");
            let vendorCatObj = $("#vendor_category");
            let empty = false;

            if (nullEmptyUndefinedChecked(nameObj.val())){
                empty = true;
                nameObj.notify('Enter a name first.','error');
            }

            if (nullEmptyUndefinedChecked(vendorTypeObj.val())){
                empty = true;
                vendorTypeObj.notify('Select a type.','error');
            }

            if (nullEmptyUndefinedChecked(vendorCatObj.val())){
                empty = true;
                vendorCatObj.notify('Select a type.','error');
            }

            if (empty == false){
                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit' + '<br>',
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
                            url: $("#vendorProfileForm").attr('action'),
                            data: new FormData($("#vendorProfileForm")[0]),
                            processData: false,
                            contentType: false,
                            dataType: "JSON",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (!nullEmptyUndefinedChecked(res.success)) {
                                Swal.fire({
                                    type: 'success',
                                    text: res.response_msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: false
                                }).then(function () {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({text: res.error, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                        });
                    }
                });
            }
        })


        getBranchListOnBank($("#bank_id :selected").val(), setBranchListOnBank, $("#branch_id").data('prebranch'));

        $("#vendor_type").on('change', function () {
            getVendorCategory($(this).val());
            addVendorEffect();
        });

        function getVendorCategory(vendorType) {
            let request = $.ajax({
                url: APP_URL + '/account-payable/ajax/vendor-category-on-vendor-type',
                data: {vendorTpe: vendorType, preCategoryId:$("#vendor_category").data('predefined')},
                async:false
            });
            request.done(function (response) {
                $("#vendor_category").html(response);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            })

            if (!nullEmptyUndefinedChecked($("#vendor_category").data('predefined'))) {
                let preset = $("#vendor_category").data('predefined');
                $(document).val(preset).trigger('change');
            }
        }

        function addVendorEffect(update = false) {
            let vendorType = $("#vendor_type :selected").val();

            if (vendorType == {{ \App\Enums\Ap\VendorType::INTERNAL }}) {
                $("#dept_cost_mapping").removeClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", false);

                /*$("#bin").prop("readonly",true);
                $("#vat").prop("readonly",true);
                $("#tin").prop("readonly",true);*/
                $("#unique_identification").addClass("d-none");

                //For External Vendor
                resetField(["#bin", "#vat", "#tin"]);
                $("#payment_hold_control").addClass("d-none");
                $("#hold_all_payment").prop("disabled", true);
                $("#hold_all_payment").prop("checked", false);
                enableDisablePaymentField();

                resetAddressField(0);
                resetBankInformationField(0);
            } else if (vendorType == {{ \App\Enums\Ap\VendorType::EXTERNAL}}) {
                $("#payment_hold_control").removeClass("d-none");
                $("#hold_all_payment").prop("disabled", false);
                resetAddressField(1);
                resetBankInformationField(1);

                //For Internal Vendor
                $("#dept_cost_mapping").addClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", true);
                $("#allow_dept_cost_center").prop("checked", false);

                /*$("#bin").prop("readonly",false);
                $("#vat").prop("readonly",false);
                $("#tin").prop("readonly",false);*/
                $("#unique_identification").removeClass("d-none");

                departmentCostCheckEnableDisable();
            } else {
                //For External Vendor
                $("#payment_hold_control").addClass("d-none");
                $("#hold_all_payment").prop("disabled", true);
                $("#hold_all_payment").prop("checked", false);
                enableDisablePaymentField();

                /*$("#bin").prop("readonly",true);
                $("#vat").prop("readonly",true);
                $("#tin").prop("readonly",true);*/
                $("#unique_identification").addClass("d-none");

                //For Internal Vendor
                $("#dept_cost_mapping").addClass("d-none");
                $("#allow_dept_cost_center").prop("disabled", true);
                $("#allow_dept_cost_center").prop("checked", false);
                departmentCostCheckEnableDisable();

                resetAddressField(0);
                resetBankInformationField(0);
            }
        }

        $("#allow_dept_cost_center").on('click', function () {
            departmentCostCheckEnableDisable();
        })
        $("#hold_all_payment").on('click', function () {
            enableDisablePaymentField();
        })
        $("#is_inactive").on('click', function () {
            enableDisableDateField(this);
        })

        function departmentCostCheckEnableDisable() {
            if ($("#allow_dept_cost_center").prop('checked')) {
                $("#dept_cost_center").parent('div').removeClass('make-readonly-bg');
            } else {
                $("#dept_cost_center").val('').trigger('change');
                $("#dept_cost_center").parent('div').addClass('make-readonly-bg');
            }
        }

        function resetAddressField(status) {
            if (status == 1) {
                $("#vendor_address").removeClass("d-none");
                $("#address_type").parent().removeClass('make-readonly');
                $("#address_1").removeAttr('readonly');
                $("#address_2").removeAttr('readonly');
                $("#city").removeAttr('readonly');
                $("#state").removeAttr('readonly');
                $("#postal_code").removeAttr('readonly');
                $("#country").parent().removeClass('make-readonly');
                $("#contact_name").removeAttr('readonly');
                $("#phone").removeAttr('readonly');
                $("#mobile").removeAttr('readonly');
                $("#email").removeAttr('readonly');
            } else {
                $("#vendor_address").addClass("d-none");
                $("#address_type").parent().addClass('make-readonly');
                $("#address_type").val("").trigger('change');
                $("#country").parent().addClass('make-readonly');
                $("#country").val("").trigger('change');

                $("#address_1").val('').attr('readonly', 'readonly');
                $("#address_2").val('').attr('readonly', 'readonly');
                $("#city").val('').attr('readonly', 'readonly');
                $("#state").val('').attr('readonly', 'readonly');
                $("#postal_code").val('').attr('readonly', 'readonly');
                $("#contact_name").val('').attr('readonly', 'readonly');
                $("#phone").val('').attr('readonly', 'readonly');
                $("#mobile").val('').attr('readonly', 'readonly');
                $("#email").val('').attr('readonly', 'readonly');
            }
        }

        function resetBankInformationField(status) {
            if (status == 1) {
                $("#vendor_bank_info").removeClass("d-none");
                $("#bank_id").parent().removeClass('make-readonly');
                $("#branch_id").parent().removeClass('make-readonly');
                $("#account_no").removeAttr('readonly');
                $("#account_title").removeAttr('readonly');
                $("#account_type").parent().removeClass('make-readonly');
            } else {
                $("#vendor_bank_info").addClass("d-none");
                $("#bank_id").parent().addClass('make-readonly');
                $("#bank_id").val("").trigger('change');
                $("#branch_id").parent().addClass('make-readonly');
                $("#branch_id").val("").trigger('change');
                $("#account_type").parent().addClass('make-readonly');
                $("#account_type").val("").trigger('change');
                $("#account_no").val('').attr('readonly', 'readonly');
                $("#account_title").val('').attr('readonly', 'readonly');
            }
        }

        function enableDisablePaymentField() {
            if ($("#hold_all_payment").prop('checked')) {
                $("#hold_all_payment_reason").removeAttr('readonly');
            } else {
                $("#hold_all_payment_reason").val('').attr('readonly', 'readonly');
            }
        }

        function enableDisableDateField(selector) {
            if ($(selector).prop('checked')) {
                $("#inactive_date").removeClass('make-readonly-bg');
                datePicker('#inactive_date');
            } else {
                $("#inactive_date_field").val('');
                $("#inactive_date").datetimepicker('destroy');
                $("#inactive_date").addClass('make-readonly-bg');
            }
        }

        $("#vendorSearchSubmit").on('click', function (e) {
            $('#vendorSearch').data("dt_params", {
                vendorType: $('#search_vendor_type :selected').val(),
                vendorCategory: $('#search_vendor_category :selected').val(),
                vendorName: $('#search_vendor_name').val(),
                vendorShortName: $('#search_vendor_short_name').val(),
            }).DataTable().draw();
        });

        $("#bank_id").on('change', function () {
            let bankId = $(this).val();
            getBranchListOnBank(bankId, setBranchListOnBank, '');
        })

        $("#branch_id").on('change', function () {
            $("#routing_number").val($("#branch_id :selected").data('routing'));
        });

        function setBranchListOnBank(response) {
            $("#branch_id").html('<option value="">Select Branch</option>')
            $("#branch_id").html(response);
            $("#routing_number").val($("#branch_id :selected").data('routing'));
        }

        function getBranchListOnBank(bankId, callback, preBranch) {
            let response = $.ajax({
                url: APP_URL + "/account-payable/ajax/get-branches-on-bank",
                data: {id: bankId, branch: preBranch}
            });

            response.done(function (d) {
                callback(d);
            });

            response.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            });
        }
    </script>
@endsection

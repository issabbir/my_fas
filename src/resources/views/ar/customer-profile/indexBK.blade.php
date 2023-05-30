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
            @include('ar.customer-profile.form')
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            datePicker('#opening_date');
            datePicker('#license_exp_date');

            @php
                if(isset($data['insertedData'])){
            @endphp
            addCustomerEffect(1);

            @php
                }else{
            @endphp
            addCustomerEffect();
            @php
                }
            @endphp

            enableDisableDateField("#is_inactive");
        });

        $("#customer_category").on('change',function () {
            enableDisableShippingAgent();
        });

        enableDisableShippingAgent();
        function enableDisableShippingAgent(){
            let shippingAgentSelector = $("#shipping_agency_id");
            //emptyShippingAutoFillField();
            if ($("#customer_category").children("option:selected").val() == {{\App\Enums\Common\LArCustomerCategory::CUSTOMER_CATEGORY_SHIPPING_AGENT}}){
                shippingAgentSelector.removeClass('make-readonly-bg');
                shippingAgentSelector.parent().prev('label').addClass('required');
                shippingAgentSelector.attr('required','required');
            }else{
                shippingAgentSelector.addClass('make-readonly-bg');
                shippingAgentSelector.parent().prev('label').removeClass('required');
                shippingAgentSelector.removeAttr('required');
                shippingAgentSelector.val("").trigger('change');
            }
        }

        $("#customerSetupReset").on('click',function () {
            $("#resetBtn").trigger('click');
            enableDisableShippingAgent();
        })

        $("#shipping_agency_id").on('change',function () {
            let agentId = $(this).children("option:selected").val();
            getShippingAgentDetail($(this),agentId,setShippingAutoFillField);
        });

        function emptyShippingAutoFillField(){
            resetField(["#name","#address_1","#contact_name","#designation","#mobile"]);
        }

        function setShippingAutoFillField(response) {
            $("#name").val(response.agency_name);
            $("#address_1").val(response.address);
            $("#contact_name").val(response.contact_person);
            $("#country").val(response.country_name);
            $("#designation").val(response.cp_designation);
            $("#email").val(response.cp_email);
            $("#mobile").val(response.cp_mobile_no);
            $("#phone").val(response.cp_phone_no);
            $("#city").val(response.district_city);
            $("#state").val(response.division_state);
        }

        function getShippingAgentDetail(selector, agentId, callback){
            let request = $.ajax({
                url: "{{route('ajax.get-shipping-agent-detail')}}",
                data: {agent_id:agentId}
            });

            request.done(function (response) {
                callback(response);
            });

            request.fail(function (jqXHR,text) {
                console.log("Exception occurred");
            });
        }

        function addCustomerEffect(update = false) {
            if (update) {
                resetAddressField(1);
            } else {
                resetAddressField(0);
            }
        }

        $("#is_inactive").on('click', function () {
            enableDisableDateField(this);
        })

        function resetAddressField(status) {
            if (status == 1) {
                //$("#customer_address").removeClass("d-none");
                //$("#address_type").parent().removeClass('make-readonly');
                /*$("#address_1").removeAttr('readonly');
                $("#address_2").removeAttr('readonly');
                $("#city").removeAttr('readonly');
                $("#state").removeAttr('readonly');
                $("#postal_code").removeAttr('readonly');
                $("#country").parent().removeClass('make-readonly');
                $("#contact_name").removeAttr('readonly');
                $("#phone").removeAttr('readonly');
                $("#mobile").removeAttr('readonly');
                $("#email").removeAttr('readonly');*/
            } else {
                // $("#customer_address").addClass("d-none");
                //$("#address_type").parent().addClass('make-readonly');
                $("#address_type").val("").trigger('change');
                //$("#country").parent().addClass('make-readonly');
                $("#country").val("").trigger('change');

                //$("#address_1").val('').attr('readonly', 'readonly');
                //$("#address_2").val('').attr('readonly', 'readonly');
                //$("#city").val('').attr('readonly', 'readonly');
                //$("#state").val('').attr('readonly', 'readonly');
                //$("#postal_code").val('').attr('readonly', 'readonly');
                //$("#contact_name").val('').attr('readonly', 'readonly');
                //$("#phone").val('').attr('readonly', 'readonly');
                //$("#mobile").val('').attr('readonly', 'readonly');
                //$("#email").val('').attr('readonly', 'readonly');
            }
        }

        function enableDisableDateField(selector) {
            if ($(selector).prop('checked')) {
                $("#inactive_date").removeClass('make-readonly-bg');
                $("#inactive_date_field").removeAttr('readonly');
                datePickerTop('#inactive_date');
            } else {
                $("#inactive_date_field").val('').attr('readonly','readonly');
                $("#inactive_date").datetimepicker('destroy');
                $("#inactive_date").addClass('make-readonly-bg');
            }
        }

        $("#customerSearchSubmit").on('click', function (e) {
            $('#customerSearch').data("dt_params", {
                customerType: $('#search_customer_type :selected').val(),
                customerCategory: $('#search_customer_category :selected').val(),
                customerName: $('#search_customer_name').val(),
                customerShortName: $('#search_customer_short_name').val(),
            }).DataTable().draw();
        });

        $("#customerProfileForm").on('submit',function (e) {
            e.preventDefault();
            let nameObj = $("#name");
            let customerCatObj = $("#customer_category");
            let empty = false;

            if (nullEmptyUndefinedChecked(nameObj.val())){
                empty = true;
                nameObj.notify('Enter name first.','error');
            }

            if (nullEmptyUndefinedChecked(customerCatObj.val())){
                empty = true;
                customerCatObj.notify('Select category.','error');
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
                            url: $("#customerProfileForm").attr('action'),
                            data: new FormData($("#customerProfileForm")[0]),
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

    </script>
@endsection

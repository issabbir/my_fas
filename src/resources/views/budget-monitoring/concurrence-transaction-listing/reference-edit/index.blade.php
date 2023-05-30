<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:১০ AM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Concurrence Information:</span>
            </h4>
            @include('budget-monitoring.concurrence-transaction-listing.reference-edit.form')
        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        /********Added on: 06/06/2022, sujon**********/
        function setPeriodCurrentDate() {
            let minDate = $("#transaction_period :selected").data("mindate");
            let maxDate = $("#transaction_period :selected").data("maxdate");
            let currentDate = $("#transaction_period :selected").data("currentdate");

            datePickerOnPeriod("#transaction_date", minDate, maxDate, currentDate);
            datePickerOnPeriod("#document_date", false, maxDate, currentDate);
        }

        /********End**********/
        let transactionDateClickCounter = 0;
        let transactionCalenderClickCounter = 0;

        let documentCalenderClickCounter = 0;

        $("#transaction_period").on('change', function () {
            $("#transaction_date >input").val("");
            /*if (transactionDateClickCounter > 0) {
                $("#transaction_date").datetimepicker('destroy');
                transactionDateClickCounter = 0;
            }*/
            /*$("#transaction_date >input").val("");
            if (documentCalendarClickCounter > 0) {
                $("#transaction_date").datetimepicker('destroy');
                documentCalendarClickCounter = 0;
            }*/
            destroyDependentCalenders();
            //setPeriodCurrentDate();
        });
        function destroyDependentCalenders() {
            if (transactionDateClickCounter > 0) {
                $("#transaction_date >input").val("");
                $("#transaction_date").datetimepicker('destroy');
                transactionDateClickCounter = 0;
                transactionCalenderClickCounter = 0;
            }else{
                $("#transaction_date >input").val("");
                transactionDateClickCounter = 0;
                transactionCalenderClickCounter = 0;
            }


            if (documentCalenderClickCounter > 0) {
                $("#document_date").datetimepicker('destroy');
                $("#document_date >input").val("");
                documentCalenderClickCounter = 0;
            }else{
                $("#document_date >input").val("");
                documentCalenderClickCounter = 0;
            }
        }
        $("#transaction_date").on('click', function () {
            if (checkPeriodSet()) {
                transactionDateClickCounter++;
                $("#transaction_date >input").val("");
                let minDate = $("#transaction_period :selected").data("mindate");
                let maxDate = $("#transaction_period :selected").data("maxdate");
                let currentDate = $("#transaction_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            }
        });


        $("#transaction_date").on("change.datetimepicker", function () {
            let newDueDate;
            let postingDate = $("#transaction_date_field").val();

            if (!nullEmptyUndefinedChecked(postingDate)) {
                /*if (transactionDateClickCounter == 0) {
                    newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                } else {
                    newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                }*/
                if (moment(postingDate, "DD-MM-YYYY",true).isValid()){
                    newDueDate = moment(postingDate, "DD-MM-YYYY").format("DD-MM-YYYY");
                }else{
                    newDueDate = moment(postingDate, "YYYY-MM-DD").format("DD-MM-YYYY"); //First time YYYY-MM-DD
                }

                $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
            }
            transactionDateClickCounter++;
        });

        $("#document_date").on('click', function () {
            documentCalenderClickCounter++;
            $("#document_date >input").val("");
            let minDate = false;
            let maxDate = $("#transaction_period :selected").data("maxdate");
            let currentDate = $("#transaction_period :selected").data("currentdate");
            datePickerOnPeriod(this, minDate, maxDate, currentDate);
        });

        function checkPeriodSet() {
            if (nullEmptyUndefinedChecked($("#transaction_period").val())) {
                $("#transaction_period").notify("Select Transaction Period.", {
                    position: "right",
                    className: 'info'
                });
                return false;
            }
            return true;
        }

        function checkFinancialYearSet() {
            if (nullEmptyUndefinedChecked($("#fiscal_year").val())) {
                $("#fiscal_year").notify("Select Financial Year.", {position: "right", className: 'info'});
                return false;
            }
            return true;
        }


        $('#bill_section').change(function (e) {
            $("#bill_register").val("");
            let billSectionId = $(this).val();
            selectBillRegister('#bill_register', APP_URL + '/budget-monitoring/ajax/bill-section-by-register/' + billSectionId, APP_URL + '/budget-monitoring/ajax/get-bill-register-detail/', '');
            $("#bill_register").attr('data-bill-register-id','');
        });


        $("#concurrence_form").on("submit", function (e) {
            e.preventDefault();

            swal.fire({
                text: 'Update Budget Booking Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value == true) {

                    $('<input type="hidden" name="mode" value="{{\Illuminate\Support\Facades\Crypt::encryptString('M')}}">').appendTo('#concurrence_form');
                    let data = new FormData(this);

                    let request = $.ajax({
                        url: '{{route('concurrence-transaction.update')}}',
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        }
                    });

                    request.done(function (res) {
                        if (res.response_code != "99") {
                            Swal.fire({
                                type: 'success',
                                text: res.response_msg,
                                showConfirmButton: false,
                                timer: 2000,
                                allowOutsideClick: false
                            }).then(function () {
                                let url = '{{route("concurrence-transaction-list.index",['filter'=>(isset($filter) ? $filter : '')])}}';
                                window.location.href = url;
                            });
                        } else {
                            Swal.fire({text: res.response_msg, type: 'error'});
                        }
                    });

                    request.fail(function (jqXHR, textStatus) {
                        console.log(textStatus);
                        /*swal.fire({
                            text: jqXHR.responseJSON,
                            type: 'warning',
                        })*/
                    });
                }
            })
        });

        $(document).ready(function () {
            $('#bill_section').select2().trigger('change');
            //For adjustment update purpose


            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod,$("#transaction_period").attr('data-preperiod'));

            function setPostingPeriod(periods) {
                $("#transaction_period").html(periods);
                //$("#transaction_period").trigger('change');
            }
        });
    </script>
@endsection



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

        .max-w-24 {
            max-width: 24% !important;
        }
        .max-w-30 {
            max-width: 30% !important;
        }

        .max-w-12_5 {
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
            @include("cm.fdr-opening-authorize.form")
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">

        function fiscalYearGetsPostingPeriod() {
            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod, $("#period").data('preperiod'));
            });
        }

        getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod, $("#period").data('preperiod'));

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            $("#period").trigger('change');
        }

        function rulesForInvestmentDate() {
            let postingCalendarClickCounter = 0;
            let postingDateClickCounter = 0;
            let documentCalendarClickCounter = 0;

            $("#period").on('change', function () {
                destroyPostingDateCalander();
                destroyDocumentDateCalander();
            });


            $("#posting_date").on('click', function () {
                setPostingDate();
            });

            function setPostingDate() {
                if (!nullEmptyUndefinedChecked($("#period :selected").val())) {
                    postingCalendarClickCounter++;
                    $("#posting_date >input").val("");
                    let minDate = $("#period :selected").data("mindate");
                    let maxDate = $("#period :selected").data("maxdate");
                    let currentDate = $("#period :selected").data("currentdate");
                    datePickerOnPeriod("#posting_date", minDate, maxDate, currentDate);
                } else {
                    $("#period").notify("Select posting period.")
                }
            }

            $("#posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                let postingDate = $("#posting_date_field").val();

                $("#document_date_field").val("");
                if (!nullEmptyUndefinedChecked(postingDate)) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment(postingDate, "YYYY-MM-DD").format("DD-MM-YYYY"); //First time YYYY-MM-DD
                    } else {
                        newDueDate = moment(postingDate, "DD-MM-YYYY").format("DD-MM-YYYY");
                    }

                    $("#document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });

            $("#document_date").on('click', function () {
                setDocumentDate();
            });

            function setDocumentDate() {
                if (!nullEmptyUndefinedChecked($("#period :selected").val())) {
                    documentCalendarClickCounter++;
                    $("#document_date >input").val("");
                    let minDate = $("#period :selected").data("mindate");
                    let maxDate = $("#period :selected").data("maxdate");
                    let currentDate = $("#period :selected").data("currentdate");
                    datePickerOnPeriod("#document_date", minDate, maxDate, currentDate);
                } else {
                    $("#period").notify("Select posting period.");
                }
            }

            function destroyPostingDateCalander() {
                if (postingCalendarClickCounter > 0) {
                    $("#posting_date >input").val("");
                    $("#posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }
            }

            function destroyDocumentDateCalander() {
                if (documentCalendarClickCounter > 0) {
                    $("#document_date >input").val("");
                    $("#document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                } else {
                    //For view purpose we mustn't reset the val
                    if (nullEmptyUndefinedChecked($("#opening_id").val())) {
                        $("#document_date >input").val("");
                    }
                }
            }
        }

        function getBranchListOnBank(bankId, callback, preBranch) {
            let response = $.ajax({
                url: APP_URL + "/cash-management/ajax/get-branches-on-bank",
                data: {id: bankId, branch: preBranch}
            });

            response.done(function (d) {
                callback(d);
            });

            response.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            });
        }

        function setFilterBranchListOnBank(response) {
            $("#s_branch_id").html('<option value="">Select Branch</option>')
            $("#s_branch_id").html(response);
        }

        function getFdrDetail(fdr_id, callback) {
            var request = $.ajax({
                url: APP_URL + '/cash-management/ajax/fdr-details',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    fdr_id: fdr_id,
                    investment_type: $("#investment_type :selected").val()
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

        function setFdrDebitInfo(data) {
            $("#investment_id").val(data.fdr_info.investment_id);
            if (data.status_code != 99) {
                $("#investment_date_field").val(data.fdr_info.investment_date);
                $("#bank_id").html($('<option>', {value: data.fdr_info.bank_code, text: data.fdr_info.bank_name}));
                $("#branch_id").html($('<option>', {
                    value: data.fdr_info.branch_code,
                    text: data.fdr_info.branch_name
                }));
                $("#fdr_number").val(data.fdr_info.fdr_no);
                $("#amount").val(data.fdr_info.investment_amount);
                $("#amount_word").val(data.fdr_info.investment_amount_inword);
                $("#term_period").val(data.fdr_info.term_period_no);
                let period = '';
                switch (data.fdr_info.term_period_code) {
                    case '{{\App\Enums\Common\LPeriodType::QUARTER}}':
                        period = '{{\App\Enums\Common\LPeriodType::QUARTER_TEXT}}';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::HALF_YEAR}}':
                        period = '{{\App\Enums\Common\LPeriodType::HALF_YEAR_TEXT}}';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::DAY}}':
                        period = '{{\App\Enums\Common\LPeriodType::DAY_TEXT}}';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::MONTH}}':
                        period = '{{\App\Enums\Common\LPeriodType::MONTH_TEXT}}';
                        break;
                    default:
                        period = '{{\App\Enums\Common\LPeriodType::YEAR_TEXT}}';
                        break;

                }
                $("#term_period_type").html($('<option>', {value: data.fdr_info.term_period_code, text: period}));
                $("#term_period_days").html($('<option>', {
                    value: data.fdr_info.term_period_days,
                    text: data.fdr_info.term_period_days
                }));
                $("#maturity_date_field").val(data.fdr_info.maturity_date);
                $("#interest_rate").val(data.fdr_info.interest_rate);
                $("#investment_status").html($('<option>', {
                    value: data.fdr_info.investment_status_id,
                    text: data.fdr_info.investment_status_name
                }));

                $("#db_account_id").val(data.acc_info.gl_acc_id);
                $("#db_account_name").val(data.acc_info.gl_acc_name);
                $("#db_account_type").val(data.acc_info.gl_type_name);
                $("#db_account_balance").val(data.acc_info.account_balance);
                $("#db_account_balance_type").html(data.acc_info.account_balance_type);
                $("#db_authorized_balance").val(data.acc_info.authorize_balance);
                $("#db_authorized_balance_type").html(data.acc_info.authorize_balance_type);

                $("#cr_account_name").html(data.contra_acc_list);
            } else {
                $("#investment_id").notify("No data found.");
            }
        }


        function resetFdrInfo() {
            resetField(["#investment_date_field",
                "#bank_id",
                "#branch_id",
                "#fdr_number",
                "#amount",
                "#amount_word",
                "#term_period",
                "#term_period_type",
                "#term_period_days",
                "#maturity_date_field",
                "#interest_rate",
                "#investment_status",

                "#db_account_id",
                "#db_account_name",
                "#db_account_type",
                "#db_account_balance",
                "#db_authorized_balance"
            ]);
            $("#cr_account_name").html("");
            $("#db_account_balance_type").html("");
            $("#db_authorized_balance_type").html("");

            resetCreditInfo();
        }

        function listBillRegister() {
            $('#bill_section').change(function (e) {
                $("#bill_register").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');
            });
        }

        function authorizeDecline() {
            $(".authorize_decline_btn").on('click', function (e) {
                let approval_status = $(this).data('status');
                let approval_status_val;
                let swal_input_type;

                if (approval_status == 'A') {
                    approval_status_val = 'Authorize';
                    swal_input_type = null;
                } else {
                    approval_status_val = 'Decline';
                    swal_input_type = 'text';
                }

                swal.fire({
                    title: 'Are you sure?',
                    text: 'FDR Opening' + approval_status_val,
                    type: 'warning',
                    input: swal_input_type,
                    inputPlaceholder: 'Reason For ' + approval_status_val+'?',
                    inputValidator: (result) => {
                        return !result && 'You need to provide a comment'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        let request = $.ajax({
                            url: '{{route('fdr-opening-authorize.perform')}}',
                            data: {wkMapId:'{{$workflowMapId}}',approveStatus: approval_status,comment: ((isConfirm.value !== true) ? isConfirm.value : '')},
                            type: "POST",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token()}}'
                            }
                        });

                        request.done(function (res) {
                            if (res.status_code == "1") {
                                Swal.fire({
                                    type: 'success',
                                    text: res.status_msg,
                                    showConfirmButton: true,
                                    allowOutsideClick: false
                                }).then(function () {
                                    let urlStr = '{{ route('fdr-opening-authorize.index',['filter'=>'_p']) }}';
                                    window.location.href = urlStr.replace('_p', '{{$filter}}');
                                });
                            } else {

                                Swal.fire({text: res.status_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                        });
                    } else if (isConfirm.dismiss == "cancel") {
                        return false;
                    }
                })
            })
        }


        $(document).ready(function () {
            fiscalYearGetsPostingPeriod();
            rulesForInvestmentDate();
            listBillRegister();
            selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $("#bill_section :selected").val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');
            getFdrDetail($('#investment_id').val(), setFdrDebitInfo);
            authorizeDecline();
        })
    </script>
@endsection


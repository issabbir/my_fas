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
            @include("cm.fdr-maturity.form")
        </div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h4 class="card-title mb-0">INVESTMENT TRANSACTION LISTING</h4>
            <hr>
        </div>
        <div class="card-body">
            <fieldset class="border p-2">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <div class="row">
                    <div class="col-md-3 form-group ">
                        <label for="li_investment_type" class="col-form-label">Investment Type</label>
                        <div class="make-select2-readonly-bg">
                            <select class="custom-select form-control form-control-sm select2" name="li_investment_type"
                                    id="li_investment_type">
                                @foreach($investmentTypes as $type)
                                    {{old('li_investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                    <option
                                        value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-2 form-group">
                        <label for="li_fiscal_year" class="required col-form-label">Fiscal Year</label>
                        <select required name="li_fiscal_year"
                                class="form-control form-control-sm required"
                                id="li_fiscal_year">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($fiscalYear as $year)
                                <option value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="li_period" class="required col-form-label">Posting Period</label>
                        <select required name="li_period" class="form-control form-control-sm" id="li_period">
                        </select>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label for="li_approval_status" class="col-form-label">Approval Status</label>
                        <select class="form-control form-control-sm" name="li_approval_status"
                                id="li_approval_status">
                            <option value="">&lt;Select&gt;</option>
                            <option value="P">Pending</option>
                            <option value="A">Approved</option>
                        </select>
                    </div>
                    {{--<div class="col-md-1">
                        <button class="btn btn-sm btn-primary" id="li_opening_search" style="margin-top: 33px;">Search
                        </button>
                    </div>--}}
                </div>
            </fieldset>
            <div class="table-responsive">
                <table id="maturity_list" class="table table-sm table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th width="10%">Posting Date</th>
                        <th width="18%">Bank</th>
                        <th width="22%">Branch</th>
                        <th width="15%">FDR No.</th>
                        <th width="15%">Amount</th>
                        <th width="12%" class="text-center">Auth Status</th>
                        <th width="8%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('cm.cm-common.investment_list_modal')
    @include('cm.cm-common.preview_modal')
    @include('cm.cm-common.chalan_preview_modal')
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
            let poCalendarClickCounter = 0;
            let poDateClickCounter = 0;

            $("#period").on('change', function () {
                destroyPostingDateCalander();
                destroyDocumentDateCalander();
                destroyPoDateCalander();
            });


            $("#posting_date").on('click', function () {
                setPostingDate();
            });
            $("#po_date").on('click', function () {
                setPoDate();
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

            function setPoDate() {
                if (!nullEmptyUndefinedChecked($("#period :selected").val())) {
                    poCalendarClickCounter++;
                    $("#po_date >input").val("");
                    //let minDate = $("#period :selected").data("mindate");
                    let maxDate = $("#period :selected").data("maxdate");
                    let currentDate = $("#period :selected").data("currentdate");
                    datePickerOnPeriod("#po_date", false, maxDate, currentDate);
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
                    let maxDate = $("#period :selected").data("maxdate");
                    let currentDate = $("#period :selected").data("currentdate");
                    datePickerOnPeriod("#document_date", false, maxDate, currentDate);
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

            function destroyPoDateCalander() {
                if (poCalendarClickCounter > 0) {
                    $("#po_date >input").val("");
                    $("#po_date").datetimepicker('destroy');
                    poCalendarClickCounter = 0;
                } else {
                    //For view purpose we mustn't reset the val
                    if (nullEmptyUndefinedChecked($("#opening_id").val())) {
                        $("#po_date >input").val("");
                    }
                }
            }

            //setCurrentRenewaldate();

            function setCurrentRenewaldate() {
                /*if (!nullEmptyUndefinedChecked($("#period :selected").val())) {
                    documentCalendarClickCounter++;
                    $("#document_date >input").val("");
                    let minDate = $("#period :selected").data("mindate");
                    let maxDate = $("#period :selected").data("maxdate");
                    let currentDate = $("#period :selected").data("currentdate");*/
                datePickerOnPeriod("#current_renewal_date", false, false);
                /*} else {
                    $("#period").notify("Select posting period.");
                }*/
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

        function fdrModalActions() {
            //For view page: one time call
            if (!nullEmptyUndefinedChecked($("#investment_id").val())) {
                getFdrDetail($('#investment_id').val(), setFdrMatureInfo);
            }

            $(" #fdr_search").on("click", function () {
                let fdrId = $('#investment_id').val();
                resetFdrInformation();
                resetCurrentInformation();
                resetPOInformation();
                resetContraInformation();

                if (!nullEmptyUndefinedChecked(fdrId)) {
                    getFdrDetail(fdrId, setFdrMatureInfo);
                } else {
                    reloadFdrListTable();
                    let sFiscal = $("#s_fiscal_year");
                    let sPeriod = $("#s_period");
                    let fiscal = $("#fiscal_year :selected");
                    let period = $("#period");

                    if (fieldsAreSet(["#transaction_type:Transaction type is required", "#document_date_field:Document date is required"])) {
                        sFiscal.html($('<option>', {
                            value: fiscal.val(),
                            text: fiscal.text()
                        }));
                        sPeriod.html($('<option>', {
                            value: period.find(':selected').val(),
                            text: period.find(':selected').text()
                        }));
                        $("#investmentListModal").modal('show');
                    }

                    /*if (!nullEmptyUndefinedChecked(period.val())) {
                        sFiscal.html($('<option>', {
                            value: fiscal.val(),
                            text: fiscal.text()
                        }));
                        sPeriod.html($('<option>', {
                            value: period.find(':selected').val(),
                            text: period.find(':selected').text()
                        }));
                        $("#investmentListModal").modal('show');
                    } else {
                        period.notify('Select a period.');
                    }*/

                }
            });
            let fdrList = $('#fdr_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/ajax/fdr-maturity-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.investmentType = $('#s_investment_type :selected').val();
                        params.transactionType = $('#transaction_type :selected').val();
                        params.documentDate = $('#document_date_field').val();
                        params.bankId = $('#s_bank_id :selected').val();
                        params.branchId = $('#s_branch_id :selected').val();

                    }
                },
                columns: [
                    {data: 'investment_id', name: 'investment_id'},
                    {data: 'investment_date', name: 'investment_date'},
                    /*{data: 'investment_type', name: 'investment_type'},*/
                    {data: 'fdr_no', name: 'fdr_no'},
                    {data: 'amount', name: 'amount'},
                    {data: 'interest_rate', name: 'interest_rate'},
                    {data: 'expiry_date', name: 'expiry_date'},
                    /*{data: 'auth_status', name: 'auth_status'},*/
                    {data: 'action', name: 'Action', "orderable": false},
                ], createdRow: function (row, data, index) {
                    $('td', row).eq(3).addClass("text-right");
                    $('td', row).eq(4).addClass("text-right");
                }
            });
            $(document).on('click', '.fdr_select', function () {
                getFdrDetail($(this).data('fdr'), setFdrMatureInfo);
                $("#investmentListModal").modal('hide');
            })

            function getFdrDetail(fdr_id, callback) {
                var request = $.ajax({
                    url: APP_URL + '/cash-management/ajax/fdr-maturity-details',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        fdr_id: fdr_id,
                        investment_type: $("#investment_type :selected").val(),
                        transaction_type: $("#transaction_type :selected").val(),
                        contraGl: $("#cr_account_name").data('preacc')
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

            function setFdrMatureInfo(data) {
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
                        value: data.fdr_info.term_period_type,
                        text: data.term_period
                    }));
                    $("#maturity_date_field").val(data.fdr_info.maturity_date);
                    $("#interest_rate").val(data.fdr_info.interest_rate);
                    $("#investment_status").html($('<option>', {
                        value: data.fdr_info.investment_status_id,
                        text: data.fdr_info.investment_status_name
                    }));
                    $("#last_renewal_date_field").val(data.fdr_info.last_renewal_date);
                    $("#last_renewal_amount").val(data.fdr_info.last_renewal_amount);
                    $("#last_maturity_date_field").val(data.fdr_info.last_renewal_maturity_date);
                    $("#last_interest_rate").val(data.fdr_info.last_renewal_interest_rate);

                    $("#maturity_gross_interest").val(data.fdr_info.last_mt_gross_interest_amount);
                    $("#maturity_source_tax").val(data.fdr_info.last_mt_source_tax_amount);
                    $("#maturity_excise_duty").val(data.fdr_info.last_mt_excise_duty_amount);
                    $("#maturity_net_interest").val(data.fdr_info.last_mt_net_interest_amount);

                    $("#last_pro_days").val(data.fdr_info.last_pro_no_of_days);
                    $("#last_pro_gross_interest").val(data.fdr_info.last_pro_gross_interest);
                    $("#last_pro_source_tax").val(data.fdr_info.last_pro_source_tax);
                    $("#last_pro_excise_duty").val(data.fdr_info.last_pro_excise_duty);
                    $("#last_pro_net_interest").val(data.fdr_info.last_pro_net_interest);

                    setCurrentInformation(data);

                    if ($("#investment_type :selected").val() != '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL}}' || $("#investment_type :selected").val() != '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT}}') {
                        resetPOInformation();
                        setPOAmountInformation(data);
                    }
                    $("#cr_account_name").html(data.contra_acc_list);
                    $("#cr_account_name").trigger('change');

                } else {
                    $("#investment_id").notify(data.status_message);
                }
            }


            function setPOAmountInformation(data) {
                $("#po_principal_amount").val(data.fdr_info.investment_amount);
                $("#po_interest_amount").val(data.fdr_info.last_mt_net_interest_amount);
                let totalAmount = parseFloat(data.fdr_info.investment_amount) + parseFloat(data.fdr_info.last_mt_net_interest_amount);
                $("#total_po_amount").val(totalAmount);
                $("#total_po_amount_word").val(amountTranslate(totalAmount));
            }

            $(document).on('shown.bs.modal', '#investmentListModal', function () {
                fdrList.columns.adjust().draw();
            });
            $(document).on('change', "#s_branch_id", function (e) {
                reloadFdrListTable();
            });


        }

        /**** ADD THIS FUNCTION PAVEL-03-07-23 AS PER-0004129 ***/
        function poAmtCalculation() {
            $('#po_principal_amount, #po_interest_amount').keyup(function (e) {
                e.preventDefault();

                let po_principal_amt = nullEmptyUndefinedChecked($('#po_principal_amount').val()) ? 0 : parseFloat($('#po_principal_amount').val());
                let po_interest_amt = nullEmptyUndefinedChecked($('#po_interest_amount').val()) ? 0 : parseFloat($('#po_interest_amount').val());
                let tot_po_amt = (po_principal_amt + po_interest_amt);

                $('#total_po_amount').val(tot_po_amt);
                $("#total_po_amount_word").val(amountTranslate(tot_po_amt));

            });
        }

        function reloadFdrListTable() {
            $('#fdr_list').DataTable().draw();
        }

        $("#cr_account_name").on('change', function () {
            let response = $.ajax({
                url: APP_URL + "/general-ledger/ajax/bank-account-details/" + $(this).find(':selected').val(),
            });

            response.done(function (data) {
                $("#cr_account_type").val(data.gl_type_name);
                $("#cr_account_balance").val(data.account_balance);
                $("#cr_account_balance_type").html(data.account_balance_type);
                $("#cr_authorized_balance").val(data.authorize_balance);
                $("#cr_authorized_balance_type").html(data.authorize_balance_type);
            });

            response.fail(function (jqXHR, textStatus) {
                console.log("Something went wrong.");
            });
        })

        function resetContraInformation() {
            resetField(["#cr_account_name", "#cr_account_type", "#cr_account_balance", "#cr_authorized_balance",])
        }

        function listBillRegister() {
            $('#bill_section').change(function (e) {
                $("#bill_register").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + billSectionId, '', '');
            });
        }


        function fdrMaturityTransactionLists() {
            function reloadMaturityListTable() {
                $('#maturity_list').DataTable().draw();
            }

            let maturityList = $('#maturity_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: '{{route("fdr-maturity.fdr-maturity-search-datalist")}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.investmentType = $('#li_investment_type :selected').val();
                        params.fiscalYear = $('#li_fiscal_year :selected').val();
                        params.period = $('#li_period :selected').val();
                        params.approvalStatus = $('#li_approval_status :selected').val();
                    }
                },
                columns: [
                    {data: 'transaction_date', name: 'transaction_date'},
                    {data: 'bank', name: 'bank'},
                    {data: 'branch', name: 'branch'},
                    {data: 'fdr_no', name: 'fdr_no'},
                    {data: 'amount', name: 'amount'},
                    {data: 'auth_status', name: 'auth_status'},
                    {data: 'action', name: 'Action'},
                ],
                createdRow: function (row, data, index) {
                    $('td', row).eq(4).addClass("text-right");
                }
            });

            $("#li_investment_type,#li_period, #li_approval_status").on('change', function () {
                reloadMaturityListTable();
            })
            $("#li_fiscal_year").on('change', function () {
                reloadMaturityListTable();
                getPostingPeriod($("#li_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

                function setPostingPeriod(periods) {
                    $("#li_period").html(periods);
                }
            })
        }

        function fdrPreview() {
            $("#maturity_preview").on('click', function () {
                let request = $.ajax({
                    url: '{{route('fdr-maturity.preview')}}',
                    type: 'POST',
                    data: {
                        investmentType: $("#investment_type :selected").val(),
                        investmentId: $("#investment_id").val(),
                        transactionType: $("#transaction_type :selected").val(),
                        principalAmt: $("#po_principal_amount").val(),
                        interestAmt: $("#po_interest_amount").val(),
                        contraGl: $("#cr_account_name :selected").val()
                    },
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    }
                });

                request.done(function (data) {
                    if (data.status_code == "1") {
                        $("#fdr_preview_content").html(data.content);
                        $("#previewModal").modal("show");
                    } else {
                        $("#fdr_preview").notify(data.content);
                    }

                })
                request.fail(function (d) {
                    Swal.fire({text: d.content, type: 'error'});
                })
            })
        }

        function chalanPreview() {
            $("#chalan_preview").on('click', function () {
                $(".c_who_brought").html('').html('Accountant/Cash');
                $(".c_whom_paid").html('').html($("#bank_id").text());
                $(".c_fdr_no").html('').html($("#fdr_number").val());
                $(".c_fdr_date").html('').html($("#investment_date_field").val());
                $(".c_pay_order_no").html('').html($("#po_number").val());
                $(".c_pay_order_date").html('').html($("#po_date_field").val());
                $(".c_amount").html('').html(getCommaSeparatedValue($("#po_interest_amount").val()));

                $("#chalanModal").modal("show");
            })
        }

        $("#transaction_type").on('change', function () {
            resetFdrInformation();
            resetCurrentInformation();
            openCloseCurrentInformation();
            resetPOInformation();
            openClosePOSection();
            resetContraInformation();
            enableDisableSaveBtn();
        })

        function resetFdrInformation() {
            resetField(["#bank_id"
                , "#branch_id"
                , "#fdr_number"
                , "#amount"
                , "#amount_word"
                , "#investment_status"
                , "#investment_date_field"
                , "#term_period"
                , "#term_period_type"
                , "#term_period_days"
                , "#maturity_date_field"
                , "#interest_rate"
                , "#last_renewal_date_field"
                , "#last_renewal_amount"
                , "#last_maturity_date_field"
                , "#last_interest_rate"
                , "#maturity_gross_interest"
                , "#maturity_source_tax"
                , "#maturity_excise_duty"
                , "#maturity_net_interest"
                , "#last_pro_days"
                , "#last_pro_gross_interest"
                , "#last_pro_source_tax"
                , "#last_pro_excise_duty"
                , "#last_pro_net_interest"
            ])
        }

        function openClosePOSection() {
            let transType = $("#transaction_type :selected").val();
            let investmentTypeSel = $("#investment_type");
            let billSecSel = $("#bill_section");
            /* IN PENSION SECTION FOR AUTO RENEWAL 1:
                 IF TRANSACTION TYPE IN ( 1-RENEWAL OR 2-RENEWAL WITH SPLIT) THEN "P.O. INFORMATION" PANEL WILL BE INVISIBLE.*/
            if (billSecSel.find(":selected").val() == '{{\App\Enums\Ap\LBillSection::PENSION_SECTION}}' && investmentTypeSel.find(":selected").data("autorenfl") == '{{\App\Enums\Common\LFdrAutoRenewalFlag::auto_renewal_on}}') {
                if (nullEmptyUndefinedChecked(transType) || (transType == '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL}}') || (transType == '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT}}')) {
                    $("#po_section").addClass('d-none');
                } else {
                    $("#po_section").removeClass('d-none');
                }
                $("#po_number").attr("required", false);
                $("#po_date_field").attr("required", false);
                $('label[for="po_number"]').removeClass("required");
                $('label[for="po_date_field"]').removeClass("required");

            } else {
                $("#po_section").removeClass('d-none');
                if (billSecSel.find(":selected").val() == '{{\App\Enums\Ap\LBillSection::PENSION_SECTION}}') {
                    $("#po_number").attr("required", false);
                    $("#po_date_field").attr("required", false);
                    $('label[for="po_number"]').removeClass("required");
                    $('label[for="po_date_field"]').removeClass("required");
                } else {
                    $("#po_number").attr("required", true);
                    $("#po_date_field").attr("required", true);
                    $('label[for="po_number"]').addClass("required");
                    $('label[for="po_date_field"]').addClass("required");
                }
            }

            if (transType == '{{\App\Enums\Common\LFdrMaturityTransType::INVESTMENT_RECOVERY}}' || nullEmptyUndefinedChecked(transType)){
                //$("#po_principal_amount").addClass("make-readonly-bg");
                //$("#po_principal_amount").attr("readonly","readonly");

                $("#po_interest_amount").addClass("make-readonly-bg");
                $("#po_interest_amount").attr("readonly","readonly");
            }else{

                //$("#po_principal_amount").removeClass("make-readonly-bg");
                //$("#po_principal_amount").removeAttr("readonly","readonly");

                $("#po_interest_amount").removeClass("make-readonly-bg");
                $("#po_interest_amount").removeAttr("readonly","readonly");

            }

            /**** ADD THIS CONDITION PAVEL-03-07-23 AS PER-0004129 ***/
            if (transType == '{{\App\Enums\Common\LFdrMaturityTransType::ENCASHED}}' || nullEmptyUndefinedChecked(transType)){
                $("#po_principal_amount").removeClass("make-readonly-bg");
                $("#po_principal_amount").removeAttr("readonly","readonly");
            }else{
                $("#po_principal_amount").addClass("make-readonly-bg");
                $("#po_principal_amount").attr("readonly","readonly");
            }

        }

        function resetPOInformation() {
            resetField(["#po_number", "#po_date_field", "#po_principal_amount", "#po_interest_amount", "#total_po_amount", "#total_po_amount_word"]);
        }

        function resetCurrentInformation() {
            resetField(["#current_renewal_date_field", "#current_renewal_amount", "#current_maturity_date_field", "#current_interest_rate"]);
        }

        function setCurrentInformation(data) {
            resetCurrentInformation();

            //As per Yousuf Imam decision all current renewal information will come from backend.
            /*if (!nullEmptyUndefinedChecked(data.fdr_info.renewal_date)) {
                $("#current_renewal_date_field").val(data.fdr_info.renewal_date)
                $("#current_interest_rate").val(data.fdr_info.renewal_interest_rate)
                autoSetRenewMaturityDate(data.fdr_info.renewal_date);
            } else {
                $("#current_renewal_date_field").val(data.fdr_info.maturity_date)
                $("#current_interest_rate").val(data.fdr_info.interest_rate)
                autoSetRenewMaturityDate(data.fdr_info.maturity_date);
            }
            autoSetRenewalAmount();*/

            $("#current_maturity_date_field").val(data.fdr_info.curr_renewal_maturity_date)
            $("#current_renewal_date_field").val(data.fdr_info.curr_renewal_date)
            $("#current_interest_rate").val(data.fdr_info.curr_renewal_interest_rate)
            $("#current_renewal_amount").val(data.fdr_info.curr_renewal_amount)

        }

        calculateNetInterest();
        function calculateNetInterest() {
            $("#maturity_source_tax, #maturity_excise_duty").on('keyup',function () {
                let interest = parseFloat($("#maturity_gross_interest").val())-(parseFloat($("#maturity_source_tax").val())+parseFloat($("#maturity_excise_duty").val()));
                $("#maturity_net_interest, #po_interest_amount").val(interest);
                if(interest < 0){
                    $("#maturity_net_interest, #po_interest_amount").css('background-color','yellow');
                }else{
                    $("#po_interest_amount").css('background-color','#FFF');
                    $("#maturity_net_interest").css('background-color','#F2F4F4');
                }

                let totalAmount = parseFloat($("#po_principal_amount").val()) + parseFloat($("#po_interest_amount").val());
                $("#total_po_amount").val(totalAmount);
                $("#total_po_amount_word").val(amountTranslate(totalAmount));
            })
        }


        function autoSetRenewalAmount() {
            let autoRenewalFlg = $("#investment_type :selected").data("autorenfl");
            let cRenewAmountSel = $("#current_renewal_amount");
            let lRenewAmountSel = $("#last_renewal_amount");
            let matuNetIntrSel = $("#maturity_net_interest");
            let billSectionSel = $("#bill_section");
            let fdrAmountSel = $("#amount");
            //Auto renewal flag only considered in pension section
            if (billSectionSel.find(":selected").val() == '{{\App\Enums\Ap\LBillSection::CASH_SECTION}}') {
                if (autoRenewalFlg == '{{\App\Enums\Common\LFdrAutoRenewalFlag::auto_renewal_on}}') {
                    if (nullEmptyUndefinedChecked(lRenewAmountSel.val())) {
                        //For First year complete
                        cRenewAmountSel.val(parseFloat(fdrAmountSel.val()));
                    } else {
                        //From Second year
                        cRenewAmountSel.val(parseFloat(lRenewAmountSel.val()) + parseFloat(matuNetIntrSel.val()))
                    }
                } else {
                    cRenewAmountSel.val(parseFloat(fdrAmountSel.val()));
                }
            } else {
                //For Pension section
                if (autoRenewalFlg == '{{\App\Enums\Common\LFdrAutoRenewalFlag::auto_renewal_on}}') {
                    if (nullEmptyUndefinedChecked(lRenewAmountSel.val())) {
                        //For First year complete
                        cRenewAmountSel.val(parseFloat(fdrAmountSel.val()) + parseFloat(matuNetIntrSel.val()));
                    } else {
                        //From Second year
                        cRenewAmountSel.val(parseFloat(lRenewAmountSel.val()) + parseFloat(matuNetIntrSel.val()))
                    }
                } else {
                    //Right now this situation is not considered in CPA
                    //cRenewAmountSel.val(parseFloat(fdrAmountSel.val()) + parseFloat(matuNetIntrSel.val()));
                    Swal.fire({text: "Auto renewal is off in pension section!!", type: 'error'});

                }
            }
        }

        function autoSetRenewMaturityDate(maturityDate) {
            let renewalDate = maturityDate;
            let termPeriod = $("#term_period").val();
            let periodType = $("#term_period_type :selected").val();
            let period;

            if (!nullEmptyUndefinedChecked(renewalDate)) {
                switch (periodType) {
                    case '{{\App\Enums\Common\LPeriodType::DAY}}':
                        period = 'days';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::MONTH}}':
                        period = 'month';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::QUARTER}}':
                        termPeriod = termPeriod * 3;
                        period = 'month';
                        break;
                    case '{{\App\Enums\Common\LPeriodType::HALF_YEAR}}':
                        termPeriod = termPeriod * 6;
                        period = 'month';
                        break;
                    default:
                        period = 'year';
                        break;
                }

                if (!nullEmptyUndefinedChecked(termPeriod)) {
                    let newData = moment(renewalDate, "DD-MM-YYYY").add(termPeriod, period).format("DD-MM-YYYY");
                    $("#current_maturity_date_field").val(newData);
                }
            }
        }

        function resetSplitInformation() {

        }

        function openCloseCurrentInformation() {
            if ($("#transaction_type :selected").val() == '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL}}') {
                $("#crInformation").removeClass('d-none');
                $("#crsInformation").addClass('d-none');
            } else if ($("#transaction_type :selected").val() == '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT}}') {
                $("#crInformation").removeClass('d-none');
                $("#crsInformation").removeClass('d-none');
            } else {
                $("#crInformation").addClass('d-none');
                $("#crsInformation").addClass('d-none');
            }
        }

        $("#new_amount").on('keyup', function () {
            let newAmount = $(this).val();
            if (!nullEmptyUndefinedChecked(newAmount)) {
                $("#new_amount_word").val(amountTranslate(removeCommaFromValue($(this).val())));
                //$(this).val(getCommaSeparatedValue($(this).val()));
                $(this).val($(this).val());
            }
        })

        function splitFdr() {
            addLineRow = function (selector) {
                if (fieldsAreSet([/*'#investment_id','#amount',*/'#current_renewal_date_field', '#current_maturity_date_field', '#current_interest_rate', '#new_fdr_no', '#new_amount'])) {
                    if ((parseFloat(removeCommaFromValue($("#total_fdr_amount").val())) + parseFloat(removeCommaFromValue($("#new_amount").val()))) > removeCommaFromValue($("#amount").val())) {
                        $("#new_amount").notify("Total renewal amount can't be greater then FDR amount.");
                    } else {
                        if ($(selector).attr('data-type') == 'A') {
                            let count = $("#splitted_fdr_table >tbody").children("tr").length;
                            let investmentDate = $("#current_renewal_date >input").val();
                            let fdrNo = $("#new_fdr_no").val();
                            let amount = $("#new_amount").val();
                            let interestRate = $("#current_interest_rate").val();
                            let expiryDate = $("#current_maturity_date >input").val();

                            let html = '<tr>\n' +
                                '<td style="padding: 4px">' +
                                '<input tabindex="-1" type="text" name="line[' + count + '][split_renewal_date]" id="split_renewal_date' + count + '" class="form-control form-control-sm" value="' + investmentDate + '" readonly/>' + '' +
                                '</td>\n' +
                                '<td>' +
                                '<input tabindex="-1" type="text" class="form-control form-control-sm" name="line[' + count + '][split_fdr_no]" id="split_fdr_no' + count + '" value="' + fdrNo + '" readonly />' +
                                '</td>' +
                                '<td style="padding: 4px;">' +
                                '<input tabindex="-1" type="text" class="form-control form-control-sm text-right-align split_fdr_amount" name="line[' + count + '][split_fdr_amount]" id="split_fdr_amount' + count + '" value="' + amount + '" readonly>' +
                                '</td>\n' +
                                '<td>' +
                                '<input tabindex="-1" type="text" readonly class="form-control form-control-sm" name="line[' + count + '][split_interest_rate]" id="split_interest_rate' + count + '" value="' + interestRate + '"/>' +
                                '</td>' +
                                '<td>' +
                                '<input tabindex="-1" readonly class="form-control form-control-sm" name="line[' + count + '][split_expiry_date]" id="split_expiry_date' + count + '" value="' + expiryDate + '"/>' +
                                '</td>' +
                                '<td style="padding: 4px;">' +
                                '<span style="text-decoration: underline" id="line' + count + '" class="cursor-pointer danger editFDRbtn" onclick="editFDR(this,' + count + ')" >Edit</span>|<span id="fdr_remove_btn' + count + '" onclick="removeLineRow(this,' + count + ')"><i class="bx bx-trash cursor-pointer"></i></span>' +
                                '</td>\n' +
                                '</tr>';
                            $("#splitted_fdr_table >tbody").append(html);
                        } else {
                            var lineToUpdate = $(selector).attr('data-line');
                            updateLineValue(lineToUpdate);
                        }
                        resetSplitMasterFields();
                        setTotalAmount("#total_fdr_amount", "#splitted_fdr_table");

                        if (totalFdr("#splitted_fdr_table") != parseFloat(removeCommaFromValue($("#amount").val()))) {
                            $("#new_fdr_no").val('').focus();
                            $('html, body').animate({scrollTop: ($("#new_fdr_no").offset().top - 400)}, 2000);
                        } else {
                            $("#fdr_maturity_submit_btn").focus();
                            $('html, body').animate({scrollTop: ($("#fdr_maturity_submit_btn").offset().top - 400)}, 2000);
                        }

                        enableDisableSaveBtn();
                    }
                }
            }

            function resetSplitMasterFields() {
                resetField(["#new_fdr_no", "#new_amount", "#new_amount_word"]);
            }

            removeLineRow = function (select, lineRow) {
                $("#action_type" + lineRow).val('D');
                $(select).closest("tr").remove();   //Removing the line instead of hide, as invoice edit is not permit
                setTotalAmount("#total_fdr_amount", "#splitted_fdr_table");
                enableDisableSaveBtn();
            }
            editFDR = function (selector, line) {
                $("#fdr_remove_btn" + line).hide();
                resetField(['#new_fdr_no', '#new_amount', '#new_amount_word'])
                $("#new_fdr_no").val($("#split_fdr_no" + line).val());
                $("#new_amount").val($("#split_fdr_amount" + line).val());
                $("#new_amount_word").val(amountTranslate(removeCommaFromValue($("#split_fdr_amount" + line).val())));

                $(".editFDRbtn").addClass('d-none');
                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-edit'></i>UPDATE");
                $(select).attr('data-type', 'U');
                $(select).attr('data-line', line);
                $("#maturity_preview").prop('disabled', true);
                $("#chalan_preview").prop('disabled', true);
                $("#fdr_maturity_submit_btn").prop('disabled', true);
            }

            function updateLineValue(line) {
                let count = $("#splitted_fdr_table >tbody").children("tr").length;
                let investmentDate = $("#current_renewal_date >input").val();
                let fdrNo = $("#new_fdr_no").val();
                let amount = $("#new_amount").val();
                let interestRate = $("#current_interest_rate").val();
                let expiryDate = $("#current_maturity_date >input").val();


                $("#split_renewal_date" + line).val(investmentDate);
                $("#split_fdr_no" + line).val(fdrNo);
                $("#split_fdr_amount" + line).val(amount);
                $("#split_interest_rate" + line).val(interestRate);
                $("#split_expiry_date" + line).val(expiryDate);

                $(".editFDRbtn").removeClass('d-none');

                var select = "#addNewLineBtn";
                $(select).html("<i class='bx bx-plus-circle'></i>ADD");
                $(select).attr('data-type', 'A');
                $(select).attr('data-line', '');
                $("#fdr_remove_btn" + line).show();

                $("#preview_btn").prop('disabled', false);
                $("#invoice_bill_entry_form_submit_btn").prop('disabled', false);
                enableDisableSaveBtn();
            }
        }

        function enableDisableSaveBtn() {
            if ($('#transaction_type :selected').val() == '{{\App\Enums\Common\LFdrMaturityTransType::RENEWAL_AND_SPLIT}}') {
                if (nullEmptyUndefinedChecked(totalFdr("#splitted_fdr_table")) || nullEmptyUndefinedChecked($("#amount").val()) || (totalFdr("#splitted_fdr_table") != parseFloat(removeCommaFromValue($("#amount").val())))) {
                    $("#maturity_preview").prop('disabled', true);
                    $("#chalan_preview").prop('disabled', true);
                    $("#fdr_maturity_submit_btn").prop('disabled', true);
                } else {
                    $("#maturity_preview").prop('disabled', false);
                    $("#chalan_preview").prop('disabled', false);
                    $("#fdr_maturity_submit_btn").prop('disabled', false);
                }
            } else {
                $("#maturity_preview").prop('disabled', false);
                $("#chalan_preview").prop('disabled', false);
                $("#fdr_maturity_submit_btn").prop('disabled', false);
            }
        }

        function setTotalAmount(displaySelector, selector) {
            //$(displaySelector).val(getCommaSeparatedValue(totalFdr(selector)));
            $(displaySelector).val(totalFdr(selector));
        }

        function totalFdr(selector) {
            let fdrAmounts = $(selector + " >tbody >tr").find(".split_fdr_amount");
            let totalFdr = 0;
            fdrAmounts.each(function () {
                if ($(this).is(":hidden") == false) {
                    let amount = removeCommaFromValue($(this).val());
                    if (!nullEmptyUndefinedChecked(amount) && amount != "0") {
                        totalFdr += parseFloat(amount);
                    }
                }
            });

            return (totalFdr.toFixed(2));
        }

        function storeMaturity() {
            $("#fdr_maturity_form").on('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit?' + '<br>',
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
                            url: APP_URL + '/cash-management/fdr-maturity',
                            data: new FormData($("#fdr_maturity_form")[0]),
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
                                    allowOutsideClick: false
                                }).then(function () {
                                    //location.reload();
                                    $("#reset_form").trigger('click');
                                    $("#print_btn").removeClass("d-none");
                                    $('#voucher_print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CM_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.posting_period_id + '&p_trans_batch_id=' + res.batch_id + '&type=pdf&filename=transaction_detail"  class="cursor-pointer btn btn-sm btn-info mr-1"><i class="bx bx-printer font-size-small"></i>Voucher Print</a>');
                                    $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/RPT_CHALLAN?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CHALLAN.xdo&p_fiscal_year_id=' + res.fiscal_year_id + '&p_document_no=' + res.document_no + '&p_login_user_id=' + res.user_id + '&type=pdf&filename=maturity_chalan_report"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer font-size-small"></i>Print Last Chalan</a>');

                                    //$("#maturity_preview").prop('disabled', true);
                                    //$("#fdr_maturity_submit_btn").prop('disabled', true);

                                });
                            } else {

                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: textStatus + jqXHR, type: 'error'});
                        });
                    }
                });
                /*}*/
            })
        }

        function resetForm() {
            $("#reset_form").on('click', function () {
                $("#transaction_type").trigger('change');
                resetField(["#investment_id"]);
                enableDisableSaveBtn();
                /*
                                resetField(["#document_number", "#department", "#bill_section", "#narration"]);

                                 $("#bill_section").trigger('change');
 resetFdrInformation();
                 resetCurrentInformation();
                 openCloseCurrentInformation();
                 resetPOInformation();
                 openClosePOSection();
                 resetContraInformation();
                 enableDisableSaveBtn();
                 resetContraInformation();*/
                //$("#print_btn").removeClass("d-none");
            })
        }

        $(document).ready(function () {
            fiscalYearGetsPostingPeriod();
            rulesForInvestmentDate();
            fdrPreview();
            chalanPreview();
            fdrModalActions();
            poAmtCalculation();
            $("#s_bank_id").on('change', function () {
                let bankId = $(this).val();
                getBranchListOnBank(bankId, setFilterBranchListOnBank, '');
                reloadFdrListTable();
            })
            selectCmBankInfo('#s_bank_id', APP_URL + '/cash-management/ajax/cm-banks');
            //selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $("#bill_section :selected").val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');
            fdrMaturityTransactionLists();
            splitFdr();
            storeMaturity();
            resetForm();
        })
    </script>
@endsection


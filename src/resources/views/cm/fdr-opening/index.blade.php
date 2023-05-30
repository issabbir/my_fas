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
            @include("cm.fdr-opening.form")
        </div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h4 class="card-title mb-0">INVESTMENT LISTING</h4>
            <hr>
        </div>
        <div class="card-body">
            <fieldset class="border p-2">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <div class="row">
                    <div class="col-md-3 form-group ">
                        <label for="li_investment_type" class="col-form-label">Investment Type</label>
                        <select class="form-control form-control-sm make-readonly-bg" name="li_investment_type"
                                id="li_investment_type">
                            @foreach($investmentTypes as $type)
                                <option {{old('s_investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                  value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                            @endforeach
                        </select>
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
                <table id="opening_list" class="table table-sm table-striped table-hover">
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

        function fdrModalActions() {
            //For view page: one time call
            if (!nullEmptyUndefinedChecked($("#investment_id").val())) {
                getFdrDetail($('#investment_id').val(), setFdrDebitInfo);
            }

            $(" #fdr_search").on("click", function () {
                let fdrId = $('#investment_id').val();
                resetFdrInfo();

                if (!nullEmptyUndefinedChecked(fdrId)) {
                    getFdrDetail(fdrId, setFdrDebitInfo);
                } else {
                    reloadFdrListTable();
                    let sFiscal = $("#s_fiscal_year");
                    let sPeriod = $("#s_period");
                    let fiscal = $("#fiscal_year :selected");
                    let period = $("#period");
                    if (!nullEmptyUndefinedChecked(period.val())) {
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
                    }

                }
            });
            let fdrList = $('#fdr_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/ajax/fdr-search-datalist',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.investmentType = $('#s_investment_type :selected').val();
                        params.fiscalYear = $('#s_fiscal_year :selected').val();
                        params.period = $('#s_period :selected').val();
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
                getFdrDetail($(this).data('fdr'), setFdrDebitInfo);
                $("#investmentListModal").modal('hide');
            })

            function getFdrDetail(fdr_id, callback) {
                var request = $.ajax({
                    url: APP_URL + '/cash-management/ajax/fdr-details',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        fdr_id: fdr_id,
                        investment_type: $("#investment_type :selected").val(),
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

                    //For view purpose only.
                    //if (!nullEmptyUndefinedChecked($("#opening_id").val())) {
                        $("#cr_account_name").trigger('change');
                    //}
                } else {
                    $("#investment_id").notify("No data found.");
                }
            }

            $(document).on('shown.bs.modal', '#investmentListModal', function () {
                fdrList.columns.adjust().draw();
            });
            $(document).on('change', "#s_branch_id", function (e) {
                reloadFdrListTable();
            });
        }
        function reloadFdrListTable() {
            $('#fdr_list').DataTable().draw();
        }

        $("#cr_account_name").on('change', function () {
            resetCreditInfo();
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

        function resetCreditInfo() {
            resetField([
                "#cr_account_type",
                "#cr_account_balance",
                "#cr_authorized_balance"
            ]);
            $("#cr_account_balance_type").html("");
            $("#cr_authorized_balance_type").html("");
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

        function storeOpening() {
            $("#fdr_opening_form").on('submit', function (e) {
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
                            url: APP_URL + '/cash-management/fdr-opening',
                            data: new FormData($("#fdr_opening_form")[0]),
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

                                    location.reload();
                                });
                            } else {
                                Swal.fire({text: res.response_msg, type: 'error'});
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            Swal.fire({text: 'Server Error|'+jqXHR, type: 'error'});
                            //console.log(jqXHR, textStatus);
                        });
                    }
                });
                /*}*/
            })
        }

        function fdrOpeningTransactionLists() {
            function reloadOpeningListTable() {
                $('#opening_list').DataTable().draw();
            }

            let openingList = $('#opening_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/fdr-opening-search-datalist',
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
            /*$(document).on('click', '.fdr_select', function () {
                getFdrDetail($(this).data('fdr'), setFdrDebitInfo);
                $("#investmentListModal").modal('hide');
            })*/
            $("#li_investment_type,#li_period, #li_approval_status").on('change', function () {
                reloadOpeningListTable();
            })
            $("#li_fiscal_year").on('change', function () {
                reloadOpeningListTable();
                getPostingPeriod($("#li_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

                function setPostingPeriod(periods) {
                    $("#li_period").html(periods);
                }
            })
        }

        function fdrPreview() {
            $("#fdr_preview").on('click', function () {
                let request = $.ajax({
                    url: '{{route('fdr-opening.preview')}}',
                    type: 'POST',
                    data: {
                        investmentId: $("#investment_id").val(),
                        contraGl: $("#cr_account_name :selected").val()
                    },
                    headers:{
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    }
                });
                /*$.post('{{route('fdr-opening.preview')}}', {
                    investmentId: $("#investment_id").val(),
                    contraGl: $("#cr_account_name :selected").val(),
                },{
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    }
                });*/
                request.done(function (data) {
                    if (data.status_code == "1"){
                        $("#fdr_preview_content").html(data.content);
                        $("#previewModal").modal("show");
                    }else{
                        $("#fdr_preview").notify(data.content);
                    }

                })
                request.fail(function (jqXHR) {
                    console.log(jqXHR);
                })
            })
        }

        function resetForm(){
            $("#reset_form").on('click', function(){
                $("#fiscal_year").trigger('change');
                resetField(["#document_number","#department","#bill_section","#narration"]);
                $("#bill_section").trigger('change');
                resetFdrInfo();
                resetCreditInfo();
                $("#print_btn").removeClass("d-none");
            })
        }

        $(document).ready(function () {
            fiscalYearGetsPostingPeriod();
            rulesForInvestmentDate();
            //listBillRegister();
            storeOpening();
            fdrPreview();
            fdrModalActions();

            $("#s_bank_id").on('change', function () {
                let bankId = $(this).val();
                getBranchListOnBank(bankId, setFilterBranchListOnBank, '');
                reloadFdrListTable();
            })
            selectCmBankInfo('#s_bank_id', APP_URL + '/cash-management/ajax/cm-banks');
            //selectBillRegister('#bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $("#bill_section :selected").val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');
            fdrOpeningTransactionLists();
        })
    </script>
@endsection


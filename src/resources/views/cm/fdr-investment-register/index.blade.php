@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
    <style rel="stylesheet">
        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }

        .max-w-15 {
            max-width: 15% !important;
        }
    </style>
@endsection

@section('content')
    @include('cm.fdr-investment-register.form')
    <div class="card">
        <div class="card-header pb-0"><h4 class="card-title mb-0">INVESTMENT LISTING</h4>
            <hr>
        </div>
        <div class="card-body">
            <fieldset class="border p-2">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <div class="row">
                    <div class="col-md-3 form-group make-select2-readonly-bg">
                        <label for="s_investment_type" class="col-form-label">Investment Type</label>
                        <select class="custom-select form-control form-control-sm select2" name="s_investment_type"
                                id="s_investment_type">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($investmentTypes as $type)
                                <option value="{{$type->investment_type_id}}"
                                    {{old('s_investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                >
                                    {{$type->investment_type_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label for="s_bank_id" class="col-form-label">Bank</label>
                        <select class=" form-control form-control-sm " name="s_bank_id"
                                id="s_bank_id">
                            <option value="">&lt;Select&gt;</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label for="s_branch_id" class="col-form-label">Branch</label>
                        <select class="custom-select form-control form-control-sm select2" name="s_branch_id"
                                id="s_branch_id">
                            <option value="">&lt;Select&gt;</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group ">
                        <label for="s_approval_status" class="col-form-label">Approval Status</label>
                        <select class="custom-select form-control form-control-sm select2" name="s_approval_status"
                                id="s_approval_status">
                            <option value="">&lt;Select&gt;</option>
                            <option value="P">Pending</option>
                            <option value="A">Approved</option>
                            <option value="D">Draft</option>
                        </select>
                    </div>
                    {{--<div class="col-md-1">
                        <button class="btn btn-sm btn-primary" id="investment_search" style="margin-top: 33px;">Search
                        </button>
                    </div>--}}
                </div>
            </fieldset>
            <div class="table-responsive">
                <table id="investment-list" class="table table-sm table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th width="15%">Investment Date</th>
                        {{--<th width="35%">Investment Type</th>--}}
                        <th width="15%">FDR No</th>
                        <th width="15%" class="text-right">Amount</th>
                        <th width="15%">Interest Rate</th>
                        <th width="15%">Maturity Date</th>
                        <th width="15%">Investment Status</th>
                        <th width="15%" class="text-center">Auth Status</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('cm.fdr-investment-register.coa_list_modal')
@endsection

@section('footer-script')
    <script type="text/javascript">
        function getInvestmentList() {
            $("#s_bank_id, #s_branch_id, #s_approval_status").on('change', function () {
                $('#investment-list').DataTable().draw();
            })
        }

        function investmentList() {
            $('#investment-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/fdr-register-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (param) {
                        param.investment_type = $('#s_investment_type :selected').val();
                        param.bank_id = $('#s_bank_id :selected').val();
                        param.branch_id = $('#s_branch_id :selected').val();
                        param.approval_status = $('#s_approval_status').val();
                    }
                },
                columns: [
                    {data: 'investment_date', name: 'investment_date'},
                    /*{data: 'investment_type', name: 'investment_type'},*/
                    {data: 'fdr_no', name: 'fdr_no'},
                    {data: 'amount', name: 'amount'},
                    {data: 'interest_rate', name: 'interest_rate'},
                    {data: 'maturity_date', name: 'maturity_date'},
                    {data: 'investment_status_name', name: 'investment_status_name'},
                    {data: 'auth_status', name: 'auth_status'},
                    {data: 'action', name: 'Action', "orderable": false},
                ], createdRow: function (row, data, index) {
                    $('td', row).eq(2).addClass("text-right");
                    $('td', row).eq(3).addClass("text-center");
                    $('td', row).eq(5).addClass("text-center");
                }
            });
        }

        function fiscalYearGetsPostingPeriod() {
            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-fdr-register-period")}}', setPostingPeriod);
            });
        }

        getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-fdr-register-period")}}', setPostingPeriod, $("#posting_period").data('preperiod'));

        function setPostingPeriod(periods) {
            $("#posting_period").html(periods);
            //setPeriodCurrentDate();
            $("#posting_period").trigger('change');
        }

        function rulesForInvestmentDate() {
            let investmentCalendarClickCounter = 0;
            let investmentDateClickCounter = 0;
            let maturityCalendarClickCounter = 0;

            //For update purpose
            //$("#posting_period").trigger('change');
            $("#posting_period").on('change', function () {
                if (investmentCalendarClickCounter > 0) {
                    $("#investment_date >input").val("");
                    $("#investment_date").datetimepicker('destroy');
                    investmentCalendarClickCounter = 0;
                }
                //setInvestmentDate();
                destryMeturityDateCalander();
                //setMaturityDate();
            });


            $("#investment_date").on('click', function () {
                setInvestmentDate();
            });
            function setInvestmentDate(){

                if (!nullEmptyUndefinedChecked($("#posting_period :selected").val())){
                    investmentCalendarClickCounter++;
                    $("#investment_date >input").val("");
                    let minDate = $("#posting_period :selected").data("mindate");
                    let maxDate = $("#posting_period :selected").data("maxdate");
                    let currentDate = $("#posting_period :selected").data("currentdate");
                    datePickerOnPeriod("#investment_date", minDate, maxDate, currentDate);
                }else{
                    $("#posting_period").notify("Select posting period.")
                }
            }

            {{--$("#investment_date").on("change.datetimepicker", function () {--}}

            {{--    let newDueDate;--}}
            {{--    let investMentDate = $("#investment_date_field").val();--}}
            {{--    let termPeriod = $("#term_period").val();--}}
            {{--    let termPeriodDays = $("#term_period_days :selected").val();--}}
            {{--    let period;--}}

            {{--    $("#maturity_date_field").val("");--}}
            {{--    if (!nullEmptyUndefinedChecked(investMentDate)) {--}}
            {{--        /*switch ($("#term_period_type :selected").val()) {--}}
            {{--            case '{{\App\Enums\Common\LPeriodType::DAY}}':--}}
            {{--                period = 'days';--}}
            {{--                break;--}}
            {{--            case '{{\App\Enums\Common\LPeriodType::MONTH}}':--}}
            {{--                period = 'month';--}}
            {{--                break;--}}
            {{--            case '{{\App\Enums\Common\LPeriodType::QUARTER}}':--}}
            {{--                termPeriod = termPeriod + 3;--}}
            {{--                period = 'month';--}}
            {{--                break;--}}
            {{--            case '{{\App\Enums\Common\LPeriodType::HALF_YEAR}}':--}}
            {{--                termPeriod = termPeriod + 6;--}}
            {{--                period = 'month';--}}
            {{--                break;--}}
            {{--            default:--}}
            {{--                period = 'year';--}}
            {{--                break;--}}
            {{--        }*/--}}
            {{--        if (investmentDateClickCounter == 0) {--}}
            {{--            newDueDate = moment(investMentDate, "DD-MM-YYYY").add(termPeriodDays , 'days').add(1 , 'days') .format("DD-MM-YYYY"); //First time YYYY-MM-DD--}}

            {{--        } else {--}}
            {{--            newDueDate = moment(investMentDate, "DD-MM-YYYY").add(termPeriodDays, 'days').add(1 , 'days').format("DD-MM-YYYY"); //First time DD-MM-YYYY--}}
            {{--        }--}}

            {{--        $("#maturity_date >input").val(newDueDate.format("DD-MM-YYYY"));--}}
            {{--    }--}}
            {{--    investmentDateClickCounter++;--}}
            {{--});--}}


            $("#investment_date").on("change.datetimepicker", function () {

                let investMentDate = $("#investment_date_field").val();
                let termPeriodDays = $("#term_period_days :selected").val();
                maturityDate(investMentDate,termPeriodDays);
            });
            $("#term_period_days").on("change", function () {

                let investMentDate = $("#investment_date_field").val();
                let termPeriodDays = $("#term_period_days :selected").val();
                maturityDate(investMentDate,termPeriodDays);
            });


            function maturityDate(investMentDate,termPeriodDays){
                $("#maturity_date_field").val("");
                $.ajax({
                    url: "{{route('fdr-register.fdr-maturity-date')}}",
                    type: "POST",
                    data: {
                        investMentDate: investMentDate,termPeriodDays:termPeriodDays,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {

                        $("#maturity_date >input").val(result.maturity_date);


                    }
                });
            }


            // $("#term_period_days").on('change', function () {
            //     destryMeturityDateCalander();
            //     if (!nullEmptyUndefinedChecked($("#investment_date_field").val())){
            //         let investMentDate = $("#investment_date_field").val();
            //         let termPeriodDays = $("#term_period_days :selected").val();
            //         newDueDate = moment(investMentDate, "DD-MM-YYYY").add(termPeriodDays, 'days').add(1 , 'days').format("DD-MM-YYYY"); //First time YYYY-MM-DD
            //         $("#maturity_date >input").val(newDueDate.format("DD-MM-YYYY"));
            //     }
            // });

            $("#maturity_date").on('click', function () {
                setMaturityDate();
            });

            function setMaturityDate(){
                if (!nullEmptyUndefinedChecked($("#posting_period :selected").val())) {
                    maturityCalendarClickCounter++;
                    $("#maturity_date >input").val("");
                    let minDate = $("#posting_period :selected").data("mindate");
                    let maxDate = $("#posting_period :selected").data("maxdate");
                    let currentDate = $("#posting_period :selected").data("currentdate");
                    datePickerOnPeriod("#maturity_date", minDate, maxDate, currentDate);
                } else {
                    $("#posting_period").notify("Select posting period.");
                }
            }

            $("#term_period").on('keyup', function () {
                autoSetMaturityDate();
            })

            function destryMeturityDateCalander() {
                if (maturityCalendarClickCounter > 0) {
                    $("#maturity_date >input").val("");
                    $("#maturity_date").datetimepicker('destroy');
                    maturityCalendarClickCounter = 0;
                }
            }

            function autoSetMaturityDate() {
                let investMentDate = $("#investment_date_field").val();
                let termPeriod = $("#term_period").val();
                let periodType = $("#term_period_type :selected").val();
                let period;

                destryMeturityDateCalander();

                if (!nullEmptyUndefinedChecked(investMentDate)) {
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
                        let newData = moment(investMentDate, "DD-MM-YYYY").add(termPeriod, period).format("DD-MM-YYYY");
                        $("#maturity_date_field").val(newData);
                    } else {
                        $("#maturity_date_field").val(investMentDate);
                    }
                }
            }

            $("#term_period_type").on('change', function () {
                autoSetMaturityDate();
            });
        }

        $("#bank_id").on('change', function () {
            let bankId = $(this).val();
            getBranchListOnBank(bankId, setBranchListOnBank, '');
        })
        $("#s_bank_id").on('change', function () {
            let bankId = $(this).val();
            getBranchListOnBank(bankId, setFilterBranchListOnBank, '');
        })

        function setBranchListOnBank(response) {
            $("#branch_id").html('<option value="">Select Branch</option>')
            $("#branch_id").html(response);
        }

        function setFilterBranchListOnBank(response) {
            $("#s_branch_id").html('<option value="">Select Branch</option>')
            $("#s_branch_id").html(response);
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

        function addComma() {
            $("#amount").on('keyup', function () {
                let tAmount = removeCommaFromValue($(this).val());
                $("#amount_word").val( amountTranslate(tAmount));
                if (tAmount.length < $(this).attr('maxlength') && !nullEmptyUndefinedChecked(tAmount)) {
                    $(this).val(getCommaSeparatedValue($(this).val()));
                } else {
                    return false;
                }
            })
        }

        let accountTable = $('#account_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            autoWidth: false,
            ordering: false,
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
                {"data": "gl_acc_id", "width": "15%"},
                {"data": "gl_acc_name"},
                {"data": "gl_acc_code", "width": "15%"},
                {"data": "action", "orderable": false, "width": "10%"}
            ],
        });
        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            accountTable.draw();
        });
        $("#searchAccount").on("click", function () {
            let accId = $("#account_id").val();
            $('#account_type').val('');
            $("#account_name").val('');
            if (!nullEmptyUndefinedChecked(accId)) {
                getAccountDetail(accId);
            } else {
                $("#accountListModal").modal('show');
                accountTable.draw();
            }
        });

        let getAccountDetail = function (accId, callback) {
            let allowedGlType = [{{\App\Enums\Common\GlCoaParams::ASSET}}];

            var request = $.ajax({
                //url: APP_URL + '/general-ledger/ajax/get-account-details',
                url: APP_URL + '/cash-management/ajax/gl-type-acc-wise-coa',
                data: {gl_acc_id: accId, gl_type_id: allowedGlType},
                /*method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }*/
            });

            request.done(function (d) {
                $("#accountListModal").modal('hide');
                resetField(['#account_name', '#account_type']);
                if (!nullEmptyUndefinedChecked(d.gl_acc_id)){
                    $("#account_id").val(d.gl_acc_id);
                    $("#account_name").val(d.gl_acc_name);
                    $("#account_type").val(d.acc_type.gl_type_name);
                }else{
                    $("#account_id").notify('Not found / Not allowed');
                }


            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        function storeInvestment() {
            $("#investment_register").on('submit', function (e) {
                e.preventDefault();
                /*if (($("#term_period_days").val() != '365') || ($("#term_period_days").val() != '360'))
                {
                    $("#term_period_days").notify("Term period days either can be 365 or 360.");
                }else{*/
                Swal.fire({
                    title: "Are you sure?",
                    html: 'Submit' + '<br>' +
                        'Investment Type: ' + $("#investment_type :selected").text() + '<br>' +
                        'Investment Date: ' + $("#investment_date_field").val() + '<br>' +
                        'Bank: ' + $("#bank_id :selected").text() + '<br>' +
                        'Branch: ' + $("#branch_id :selected").text() + '<br>' +
                        'Fdr Number: ' + $("#fdr_number").val() + '<br>' +
                        'Amount: ' + $("#amount").val() + '<br>' +
                        'Maturity Date: ' + $("#maturity_date_field").val() + '<br>' +
                        'Interest Rate: ' + $("#interest_rate").val() + '<br>',
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
                        let actionUrl;
                        let investmentId = $("#investment_id").val();
                        if (nullEmptyUndefinedChecked(investmentId)) {
                            actionUrl = APP_URL + '/cash-management/fdr-register';
                        } else {
                            actionUrl = APP_URL + '/cash-management/fdr-register/' + investmentId;
                        }

                        let request = $.ajax({
                            url: actionUrl,
                            data: new FormData($("#investment_register")[0]),
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
                                    if (!nullEmptyUndefinedChecked(investmentId)) {
                                        window.location.href = '{{ route('fdr-register.index') }}';
                                    } else {
                                        location.reload();
                                    }


                                    //$("#reset_form").trigger('click');
                                    //$('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/TRANSACTION_LIST_BATCH_WISE?xdo=/~weblogic/FAS_NEW/ACCOUNTS_PAYABLE/RPT_AP_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=transaction_list_batch_wise"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i>Print Last Voucher</a>');

                                    //$("#preview_btn").prop('disabled', true);
                                    //$("#invoice_bill_entry_form_submit_btn").prop('disabled', true);
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
                /*}*/
            })
        }

        $(document).ready(function () {
            getInvestmentList();
            investmentList();
            addComma();
            fiscalYearGetsPostingPeriod();
            rulesForInvestmentDate();
            storeInvestment();

            function getBranches() {
                if (!nullEmptyUndefinedChecked($("#branch_id").data('prebranch'))) {
                    getBranchListOnBank($("#bank_id").data('cm-bank-id'), setBranchListOnBank, $("#branch_id").data('prebranch'));
                }
            }

            function getFilterBranches() {
                //getBranchListOnBank($("#s_bank_id").data('cm-bank-id'), setBranchListOnBank, '');
            }

            selectCmBankInfo('#bank_id', APP_URL + '/cash-management/ajax/cm-banks', APP_URL + '/cash-management/ajax/cm-bank/', getBranches);
            selectCmBankInfo('#s_bank_id', APP_URL + '/cash-management/ajax/cm-banks', APP_URL + '/cash-management/ajax/cm-bank/', '');

            //update + view
            if (!nullEmptyUndefinedChecked($("#amount").val())){
                $("#amount_word").val(amountTranslate(removeCommaFromValue($("#amount").val())));
            }
        });

    </script>
@endsection

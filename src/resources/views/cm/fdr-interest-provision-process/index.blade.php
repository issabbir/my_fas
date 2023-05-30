@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <h4> <span class="border-bottom-secondary border-bottom-2">Interest Provision Process</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <fieldset class="border p-2 mt-2">
                <legend class="w-auto text-bold-600" style="font-size: 14px;">Interest Provision Processing</legend>
                <form  id="interest-provision-search-form" action="#" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group make-select2-readonly-bg">
                                <label for="inv_type_id" class="required">Investment Type</label>
                                <select name="inv_type_id" class="custom-select form-control form-control-sm required select2 " id="inv_type_id">
                                    {{--<option value="" >&lt;Select&gt;</option>--}}
                                    @foreach($invTypeList as $value)
                                        <option value="{{$value->investment_type_id}}"
                                            {{old('inv_type_id', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $value->investment_type_id ? 'selected' : ''}}
                                            {{--{{isset($filterData) ? (($value->investment_type_id == $filterData[0]) ? 'selected' : '') : ''}}--}} >{{$value->investment_type_name}}
                                        </option>
                                    @endforeach
                                </select><span id="yyy"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group {{ isset($provMstId) ? 'make-select2-readonly-bg' : ''}}">
                                <label for="fiscal_year"  class="required">Fiscal Year</label>
                                <select name="fiscal_year" class="custom-select form-control form-control-sm required select2" id="fiscal_year">
                                    {{--<option value="" >&lt;Select&gt;</option>--}}
                                    @foreach($fiscalYear as $value)
                                        <option value="{{$value->fiscal_year_id}}"
                                            {{isset($fiscalYearId) && $value->fiscal_year_id == $fiscalYearId ? 'selected' : ''}} >{{$value->fiscal_year_name}}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="posting_period" class="required">Year End Period</label>
                                <select name="posting_period" class="custom-select form-control form-control-sm make-readonly-bg required" id="posting_period">
                                    {{--<option value="" >&lt;Select&gt;</option>--}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-start">
                            <div class="mt-2">
                                {{--<input type="text" class="d-none" name="int_prov_search_btn_val" id="int_prov_search_btn_val" value="{{ isset($provMstId) ? \App\Enums\YesNoFlag::YES : ''}}" />--}}
                                <button type="button" class="btn btn-primary" id="interest_provision_search_btn" value="{{ isset($provMstId) ? \App\Enums\YesNoFlag::NO : ''}}" {{ isset($provMstId) ? 'disabled' : ''}}><i class="bx bx-search"></i><span class="align-middle ml-25">Load Provision Details</span></button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Interest Provision List</span></h6>
                        <div class="col-md-12 text-center" style="display:none;" id="loading-image">
                            <strong class="text-secondary">Loading...</strong>
                            <div class="spinner-border ml-auto text-secondary" role="status" aria-hidden="true"></div>
                        </div>
                        <div class="col-md-12 table-responsive {{--fixed-height-scrollable--}} table-scroll" id="int_prov_table_search">
                            <table class="table table-sm table-bordered table-striped" id="int_prov_table">
                                <thead class="thead-light {{--sticky-head--}}">
                                <tr>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Investment date</th>
                                    <th>Fdr No</th>
                                    <th>Amount</th>
                                    <th>Interest Rate</th>
                                    <th>No of Days</th>
                                    <th>Gross Interest</th>
                                    <th>S.Tax</th>
                                    <th>E. Duty</th>
                                    <th>Net Interest</th>
                                </tr>
                                </thead>
                                <tbody id="intProvList"></tbody>
                                <tfoot class="thead-light {{--thead-light sticky-head-foot--}}">
                                <tr class="font-small-3 ">
                                    {{--<th class="text-right" colspan="2">Total Selected</th>
                                    <th class="text-left" id="total_checked1"></th>
                                    <th colspan="7" class="text-right pr-2"> Total Selected Due Amount</th>
                                    <th id="total_due_amt" class="text-right"></th>--}}
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- START== ADD THIS SECTION  PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT -->
                    <input class="d-none" type="text" name="prov_mst_id" id="prov_mst_id" value="{{ isset($provMstId) ? $provMstId : ''}}">
                    @if (isset($provMstId))
                        <div class="row">
                            <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                            <div class="col-md-12 table-responsive table-scroll" id="">
                                <table class="table table-sm table-bordered table-striped" id="int_prov_trans_view_table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>#SL No</th>
                                        <th>Gl Account ID</th>
                                        <th>GL Account Name</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Credit</th>
                                    </tr>
                                    </thead>
                                    <tbody id="intProvTransViewList"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <!-- END== ADD THIS SECTION  PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT -->

                    <!-- Start Block this section  pavel-09-01-23 as per YOUSUF IMAM Vai REQUIREMENT -->
                    {{--<div class="row d-none" id="int_prov_trans_view_sec">
                        <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                        <div class="col-md-12 table-responsive table-scroll" id="">
                            <table class="table table-sm table-bordered table-striped" id="int_prov_trans_view_table">
                                <thead class="thead-light">
                                <tr>
                                    <th>#SL No</th>
                                    <th>Gl Account ID</th>
                                    <th>GL Account Name</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                </tr>
                                </thead>
                                <tbody id="intProvTransViewList"></tbody>
                            </table>
                        </div>
                    </div>--}}
                    <!-- End Block this section  pavel-09-01-23 as per YOUSUF IMAM Vai REQUIREMENT -->

                    <div class="row mt-2">
                        <div class="col-md-12 d-flex">
                            {{--Sujon-CR--}}
                            <button class="btn btn-dark mr-1 {{--d-none--}}" type="button" id="get_preview" {{ isset($approvalStatus) ? 'disabled' : ''}}><i class="bx bx-show"></i><span class="align-middle ml-25">Trans. Preview</span></button>
                            {{--<a class="btn btn-primary mr-1 --}}{{--d-none--}}{{--" id="report_print" target="_blank" href=""><i class="bx bx-printer"></i><span class="align-middle ml-25">Report Print</span></a>--}}
                            <button type="button" class="btn btn-success save-submit-btn mr-1" id="save_btn" data-btn="{{ isset($provMstId) ? \App\Enums\YesNoFlag::YES : ''}}"  value="{{ App\Enums\ActionType::SUBMIT}}" {{ (isset($approvalStatus) &&  $approvalStatus != \App\Enums\ApprovalStatusView::DRAFT) ? 'disabled' : ''}}><i class="bx bx-save"></i><span class="align-middle ml-25">Save</span></button>
                            {{--@if (isset($provMstId))
                                <a target="_blank" href="{{request()->root()}}/report/render/CM_FDR_STATEMENT_ACCRUED_INTEREST?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CM_FDR_STATEMENT_ACCRUED_INTEREST.xdo&p_investment_type_id={{$invTypeId}}&p_fiscal_year_id={{$fiscalYearId}}&type=pdf&filename=cm_fdr_statement_accrued_interest" class="cursor-pointer btn btn-info mr-1"><i class="bx bx-printer"></i><span class="align-middle ml-25">Trans Print</span></a>
                            @endif--}}
                            <div id="print_btn"></div>
                            {{--<button type="button" class="btn btn-success save-submit-btn mr-1" id="submit_btn"  value="{{ App\Enums\ActionType::SUBMIT }}" {{ (isset($approvalStatus) &&  $approvalStatus == \App\Enums\ApprovalStatusView::DRAFT) ? '' : 'disabled'}}><i class="bx bx-save"></i><span class="align-middle ml-25">Submit</span></button>--}}
                        </div>
                    </div>

                    <!-- Transaction View Modal start== ADD THIS SECTION  PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT -->
                    <section id="modal-sizes">
                        <div class="row">
                            <div class="col-12">
                                <!--Modal Xl size -->
                                <div class="mr-1 mb-1 d-inline-block">
                                    <!-- Button trigger for Extra Large  modal -->
                                {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                                    Extra Large Modal
                                </button>--}}
                                <!--Extra Large Modal -->
                                    <div class="modal fade text-left w-100" id="transViewModal" tabindex="-1" role="dialog"
                                         aria-labelledby="transViewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h4 class="modal-title white" id="transViewModalLabel">Interest Provision Transaction List</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card shadow-none">
                                                        <div class="row">
                                                            <h6 class="mt-1 mb-1"><span class="border-bottom-secondary border-bottom-2 text-bold-600">Transaction View</span></h6>
                                                            <div class="col-md-12 table-responsive table-scroll" id="">
                                                                <table class="table table-sm table-bordered table-striped" id="int_prov_trans_view_table">
                                                                    <thead class="thead-light">
                                                                    <tr>
                                                                        <th>#SL No</th>
                                                                        <th>Gl Account ID</th>
                                                                        <th>GL Account Name</th>
                                                                        <th class="text-right">Debit</th>
                                                                        <th class="text-right">Credit</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="intProvTransViewList"></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i
                                                            class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Transaction View Modal end== ADD THIS SECTION  PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT -->
                </form>
            </fieldset>
        </div>
    </div>

    @include('cm.fdr-interest-provision-process.list')

@endsection

@section('footer-script')
    <script type="text/javascript">

        function fiscalYear(){
            $("#fiscal_year").on('change',function () {
                getPostingPeriod($("#fiscal_year :selected").val(),'{{route("ajax.get-year-end-posting-period")}}', setPostingPeriod);  //Route Call General Leader
            });
        }

        function setPostingPeriod(periods) {
            $("#posting_period").html(periods);
            $("#posting_period option:first").remove(); //Remove first option
            //setPeriodCurrentDate();
            $("#posting_period").trigger('change');
        }

        function intProvSearch (){
            $("#interest_provision_search_btn").on("click", function () {
                //e.preventDefault();
                $('#interest_provision_search_btn').val('{{\App\Enums\YesNoFlag::YES}}');
                intProvList();
                intProvTransViewList();
            });
        }

        function intProvList() {

            //$('.inv_ref_div').addClass('d-none');
            //$('.inv_ref_tax_div').removeClass('d-none');
            //let data = new FormData($("#interest-provision-search-form")[0]);
            //data.append( 'action_type', action_type );

            let int_prov_search_btn_val = $('#interest_provision_search_btn').val();

            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/cash-management/ajax/interest-provision-list',
                //data: $('#interest-provision-search-form').serialize() ,
                data: $('#interest-provision-search-form').serialize() + "&int_prov_search_btn_val="+int_prov_search_btn_val,

                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function (data) {
                    //$("#fiscal_year").notify(data.statusMessage, {position: 'bottom'});
                    $('#intProvList').html(data.html);
                    $("#loading-image").hide();

                    if ( (data.statusCode != 1) && (data.statusCode != '')) {
                        swal.fire({ title: 'Sorry...',text: data.statusMessage,type: 'warning',});
                        //Sujon-CR
                        /*$("#get_preview").addClass("d-none");
                        $("#report_print").addClass("d-none");
                        $("#print_btn").html("");*/

                    } else if  ( ($('#int_prov_table >tbody >tr').length) -1 > 0 ) {
                        $("#print_btn").removeClass("d-none");
                        $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/CM_FDR_STATEMENT_ACCRUED_INTEREST?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CM_FDR_STATEMENT_ACCRUED_INTEREST.xdo&p_investment_type_id=' + $('#inv_type_id :selected').val() + '&p_fiscal_year_id=' + $('#fiscal_year :selected').val() + '&type=pdf&filename=cm_fdr_statement_accrued_interest" class="cursor-pointer btn btn-info mr-1"><i class="bx bx-printer"></i><span class="align-middle ml-25">Trans Print</span></a>');
                    } else {
                        $("#print_btn").addClass("d-none");
                    }
                    /*else  {
                        //Sujon-CR
                        if (data.statusCode == 1){
                            //alert('2');
                            //$("#get_preview").removeClass("d-none");
                            //$("#report_print").removeClass("d-none");
                        }
                    }*/

                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function intProvTransViewList(param=null) {
            let btn_edit_val = $('#save_btn').data('btn');

            if(param == "{{ \App\Enums\YesNoFlag::YES}}") {
                param = "{{ \App\Enums\YesNoFlag::YES}}";
            } else if ( btn_edit_val =="{{ \App\Enums\YesNoFlag::YES}}") {
                param = "{{ \App\Enums\YesNoFlag::YES}}";
            }
            //alert($('#prov_mst_id').val());

            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/cash-management/ajax/interest-provision-trans-view-list',

                data: $('#interest-provision-search-form').serialize() + "&param_val="+ param,

                success: function (data) {
                    //console.log(data.intProvTransViewList);
                    if  (!nullEmptyUndefinedChecked(data.intProvTransViewList)) {
                        //$('#int_prov_trans_view_sec').removeClass('d-none'); //BLOCK THIS PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT

                        <!-- ADD IF & ELSE CONDITION PAVEL-09-01-23 AS PER YOUSUF IMAM VAI REQUIREMENT -->
                        if (nullEmptyUndefinedChecked($('#prov_mst_id').val())){
                            $('#intProvTransViewList').html(data.html);
                            $("#transViewModal").modal('show');
                        } else{
                            $('#intProvTransViewList').html(data.html);
                        }
                    } else {
                        $('#int_prov_trans_view_sec').addClass('d-none');
                        $('#intProvTransViewList').html(data.html);
                    }
                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        function checkIntProvProcForm() {
            $('.save-submit-btn').click(function (e) {
                e.preventDefault();

                let action_type = $(this).val();
                let action_type_val;
                let intProvTableCount = ($('#int_prov_table >tbody >tr').length) - 1;
                //alert(intProvTableCount + '===' + action_type);

                if ( action_type == "{{ App\Enums\ActionType::SAVE}}" ) {
                    action_type_val = 'Save Data For Draft Mode';
                }  else {
                    action_type_val = 'Final Submit';
                }
                //Sujon-CR
                if (intProvTableCount <= 0) {
                    swal.fire({
                        title: 'Sorry...',
                        text: 'Interest Provision List Not Found.',
                        type: 'warning',
                    });
                } else {
                    swal.fire({
                        title: 'Are you sure?',
                        text: 'Interest Provision ' + action_type_val,
                        type: 'warning',

                        /*input: swal_input_type,
                        inputPlaceholder: 'Reason For ' + approval_status_val+'?',
                        inputValidator: (result) => {
                            return !result && 'You need to provide a comment'
                        },*/

                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save it!'
                    }).then(function (isConfirm) {
                        if (isConfirm.value) {
                            //form.submit();
                            //$("#comment_on_decline").val( (isConfirm.value !== true) ? isConfirm.value : '' );
                            //$('#invoice-bill-pay-authorize-form').submit();


                            let data = new FormData($("#interest-provision-search-form")[0]);
                            data.append( 'action_type', action_type );

                            let request = $.ajax({
                                url: APP_URL + "/cash-management/fdr-interest-provision-process",
                                data: data, //new FormData($("#interest-provision-search-form")[0]),
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

                                        if (action_type == "{{ App\Enums\ActionType::SAVE}}") {
                                            $("#interest_provision_search_btn").prop("disabled", true);
                                            $("#submit_btn").prop("disabled", false);
                                            $("#fiscal_year").parent().addClass("make-select2-readonly-bg");
                                            $('#int_prov_trans_view_sec').removeClass('d-none');

                                            $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/CM_FDR_STATEMENT_ACCRUED_INTEREST?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CM_FDR_STATEMENT_ACCRUED_INTEREST.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.o_batch + '&type=pdf&filename=cm_fdr_statement_accrued_interest" class="cursor-pointer btn btn-info mr-1"><i class="bx bx-printer"></i><span class="align-middle ml-25">Trans Print</span></a>');

                                            //Sujon-CR
                                            // intProvTransViewList("{{--{{ \App\Enums\YesNoFlag::YES}}--}}");

                                        } else {
                                            //Sujon-CR
                                            //$('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/CM_FDR_STATEMENT_ACCRUED_INTEREST?xdo=/~weblogic/FAS_NEW/CASH_MANAGEMENT/RPT_CM_FDR_STATEMENT_ACCRUED_INTEREST.xdo&p_posting_period_id=' + res.period + '&p_trans_batch_id=' + res.batch + '&type=pdf&filename=cm_fdr_statement_accrued_interest" class="cursor-pointer btn btn-info mr-1"><i class="bx bx-printer"></i><span class="align-middle ml-25">Trans Print</span></a>');

                                            /*resetTablesDynamicRow('#int_prov_table');
                                            $("#intProvList").html('<tr>\n' +
                                                '        <th colspan="11" class="text-center"> No Data Found</th>\n' +
                                                '    </tr>');

                                            resetTablesDynamicRow('#int_prov_trans_view_table');*/
                                            $("#interest_provision_search_btn").val("");
                                            intProvList();
                                            intProvTransViewList();
                                            paramWiseSearchList();

                                            $("#int_prov_trans_view_sec").addClass('d-none');
                                            //$("#get_preview").addClass("d-none");
                                            //$("#report_print").addClass("d-none");

                                            // window.location.href = "{{ route('fdr-interest-provision-process.index') }}";
                                        }
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


                        } else if (isConfirm.dismiss == "cancel") {
                            //return false;
                            e.preventDefault();
                        }
                    })
                }

            });
        }

        function getTransactionPreviews() {
            $("#get_preview").on('click',function () {
                let intProvTableCount = ($('#int_prov_table >tbody >tr').length) - 1;

                if (intProvTableCount <= 0) {
                    swal.fire({ title: 'Sorry...',text: 'Interest Provision List Not Found.',type: 'warning'});
                } else {
                    intProvTransViewList("{{ \App\Enums\YesNoFlag::YES}}");
                }
            })
        }

        function paramWiseSearchList(){
            $(".search-param").on('change', function () {
                oTable.draw();
            });

            let oTable = $('#interest-prov-hist-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/cash-management/fdr-interest-provision-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.inv_type_id = $('#s_inv_type_id :selected').val();
                    }
                },
                "columns": [
                    {"data": "fiscal_year"},
                    {"data": "investment_type_name"},
                    {"data": "status"},
                    {"data": "action", "orderable": false},
                ]
            });
        }

        $(document).ready(function () {
            fiscalYear();
            intProvSearch ();
            intProvList();
            intProvTransViewList();
            checkIntProvProcForm();
            paramWiseSearchList();
            getTransactionPreviews();

            getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-year-end-posting-period")}}', setPostingPeriod, $("#posting_period").data('preperiod')); //Route Call General Leader
        });
    </script>
@endsection

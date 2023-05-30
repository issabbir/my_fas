@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    {{--<style type="text/css" rel="stylesheet">
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
    </style>--}}
@endsection
@section('content')
    <div class="card">
        <div class="card-header bg-dark text-white p-75">Search Transaction Authorize</div>
        <div class="card-body border">
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="POST" id="transaction-search-form">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="th_fiscal_year" class="">Fiscal Year</label>
                        <select name="th_fiscal_year"
                                class="form-control form-control-sm required select2 search-param"
                                id="th_fiscal_year">
                            @foreach($fiscalYear as $year)
                              {{--  <option value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>--}}
                                <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="period" class="">Posting Period</label>
                        <select class="form-control form-control-sm select2 search-param" id="period" name="period"
                                required>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="bill_sec_id" class="">Bill Section</label>
                        <select name="bill_sec_id" class="form-control form-control-sm select2 search-param"
                                id="bill_sec_id">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($lBillSecList as $value)
                                {{--<option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>--}}
                                <option {{isset($filterData) ? (($value->bill_sec_id == $filterData[2]) ? 'selected' : '') : ''}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="bill_reg_id" class="">Bill Register</label>
                        <select class="form-control form-control-sm select2 search-param" id="bill_reg_id"
                                name="bill_reg_id">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="trans_mst_approval_status" class="">Approval Status</label>
                        <select name="trans_mst_approval_status" class="form-control form-control-sm select2 search-param"
                                id="trans_mst_approval_status">
                            <option value="">&lt;Select&gt;</option>
                        @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                <option {{isset($filterData) ? (($key == $filterData[4]) ? 'selected' : '') : ''}} value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} >{{ $value}}</option>
                            {{--<option value="{{$key}}">{{ $value}}</option>--}}
                            @endforeach
                        </select>
                    </div>
                </div>

                {{--TODO: Block-Start Pavel-14-07-22--}}
                {{--<div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fun_type_id" class="">Function Type</label>
                            <select class="custom-select form-control form-control-sm select2" id="fun_type_id" name="fun_type_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($cashTranFunTypeList as $value)
                                    <option value="{{$value->function_id}}">{{ $value->function_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="period" class="required">Posting Period</label>
                            <select class="custom-select form-control form-control-sm select2" id="period" name="period" required>
                                <option value="">&lt;Select&gt;</option>
                                @foreach($postPeriodList as $post)
                                    <option
                                        {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }}
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                        data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                        data-postingname="{{ $post->posting_period_name}}"
                                        value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="posting_date" class="">Posting Date</label>
                            <div class="input-group date posting_date" id="posting_date" data-target-input="nearest">
                                <input type="text" autocomplete="off" name="posting_date" id="posting_date_field"
                                       class="form-control form-control-sm datetimepicker-input posting_date"
                                       data-target="#posting_date" data-toggle="datetimepicker"
                                       value=""
                                       data-predefined-date=""
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append posting_date" data-target="#posting_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bxs-calendar font-size-small"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted form-text"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="posting_batch_id" class="">Posting Batch Id</label>
                            <input class="form-control form-control-sm" id="posting_batch_id" name="posting_batch_id"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dpt_id" class="">Department</label>
                            <select class="custom-select form-control form-control-sm select2" id="dpt_id" name="dpt_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($dptList as $value)
                                    <option value="{{$value->cost_center_dept_id}}">{{ $value->cost_center_dept_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bill_sec_id" class="">Bill Section</label>
                            <select class="custom-select form-control form-control-sm select2" id="bill_sec_id" name="bill_sec_id">
                                <option value="">&lt;Select&gt;</option>
                                --}}{{--@foreach($lBillSecList as $value)
                                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                @endforeach--}}{{--
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dpt_id" class="">Bill Register</label>
                            <select class="form-control form-control-sm select2" id="bill_reg_id" name="bill_reg_id">
                                <option value="">&lt;Select&gt;</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trans_mst_approval_status" class="">Approval Status</label>
                            <select class="form-control form-control-sm" name="trans_mst_approval_status"
                                    id="trans_mst_approval_status">
                                <option value="">&lt;Select&gt;</option>
                                @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                    --}}{{--@if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)--}}{{--
                                    <option
                                        value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} > {{ $value}} </option>
                                    --}}{{--@endif--}}{{--
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary mb-2 mr-1"><i class="bx bx-search font-size-small"></i><span
                                class="align-middle ">Search</span></button>
                        <button type="button" class="btn btn-sm btn-secondary mb-2" id="reset"><i class="bx bx-reset font-size-small"></i><span
                                class="align-middle">Reset</span></button>
                        <button type="reset" class="btn btn-sm btn-secondary mb-2 d-none" id="resetMain"></button>
                    </div>
                </div>--}}
                {{--TODO: Block-End Pavel-14-07-22--}}
            </form>

            @include('gl.transaction-authorize.master_list')

            @include('gl.transaction-authorize.detail_list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).on("click", ".approve-reject-btn", function (e) {
            //e.preventDefault();


            e.preventDefault();
            let approval_status = 'A';
            let data_map = $(this).data('map');
            let mapDataArray = data_map.split('##');
            let filter = '';
            let swal_input_type;
            $('#approve_reject_value').val(approval_status);

            swal.fire({
                title: 'Are you sure?',
                text: 'Transaction Authorize',
                type: 'warning',
                input: swal_input_type,

                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it!'
            }).then(function (isConfirm) {
                if (isConfirm.value) {

                    $.ajax({
                        url: APP_URL + '/general-ledger/transaction-authorize-approval-by-list',
                        data: {wk_map_id: mapDataArray[0],wk_mst_id:mapDataArray[1],ref_tbl:mapDataArray[2], ref_status: approval_status},
                        dataType: "JSON",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token()}}'
                        },
                        success: function (result) {
                            if (result.response_code == 1) {
                                Swal.fire({
                                    type: 'success',
                                    text: result.response['o_status_message'],
                                    showConfirmButton: true,
                                    allowOutsideClick: false
                                }).then(function (isConfirm) {
                                    if (isConfirm.value) {
                                        $('#transaction-mst-search-list').DataTable().ajax.reload();
                                    }

                                });
                            } else {

                                Swal.fire({text: result.response['o_status_message'], type: 'error'});
                            }
                        }
                    });

                }
            })

        });
        /** Add Block-Start Pavel-13-07-22 **/
        function fiscalYear(){
            $("#th_fiscal_year").on('change', function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod); //Route Call General Leader
            });
        }

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            //setPeriodCurrentDate();
            $("#period").trigger('change');
        }

        function searchParamWiseList(){
            $(".search-param").on('change', function () {
                oTable.draw();
            });
        }
        /** Add Block-End Pavel-13-07-22 **/

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                //$("#bill_reg_id").select2("destroy"); //Block-Pavel: 14-07-22
                //$("#bill_reg_id").html("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }

        let oTable = $('#transaction-mst-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 5,
            bFilter: true,
            ordering: false,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + '/general-ledger/transaction-authorize-mst-search-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    /*params.fun_type_id = $('#fun_type_id').val();*/
                    params.fiscalYear = $('#th_fiscal_year :selected').val();
                    params.period = $('#period').val();
                    params.bill_sec_id = $('#bill_sec_id :selected').val();
                    params.bill_reg_id = $('#bill_reg_id :selected').val();
                    /*params.dpt_id = $('#dpt_id').val();
                    params.posting_date_field = $('#posting_date_field').val();
                    params.posting_batch_id = $('#posting_batch_id').val();*/
                    params.trans_mst_approval_status = $('#trans_mst_approval_status :selected').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "function_name"},
                {"data": "document_no"},
                {"data": "document_date"},
                {"data": "document_ref"},
                /*{"data": "trans_date"},*/
                {"data": "debit_sum"},
                {"data": "credit_sum"},
                /*{"data": "bill_sec_name"},
                {"data": "bill_reg_name"},*/
                /*{"data": "mst-view", "className": "text-center"},
                {"data": "view", "className": "text-center"},*/
                {"data": "status", "className": "text-center"},
                {"data": "action", "className": "text-center"}
            ],
            "columnDefs": [
                {targets: 4, className: 'text-right-align'},
                {targets: 5, className: 'text-right-align'},
            ]
        });

        $(document).on("click", '.trans-mst', function (e) {
            e.preventDefault();
            $('.trans-dtl-sec').show();
            $('#authorize_step_sec').show();
            searchTransMstByDtl(this);
            returnTransactionMsg(this);
            $('html, body').animate({scrollTop: $(".trans-dtl-sec").offset().top}, 2000);

        });

        function searchTransMstByDtl(transMstId) {
            let trans_mst_id = $(transMstId).attr('id');

            $('#transaction-mst-by-dtl-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                //pageLength: 5,
                bFilter: true,
                ordering: false,
                //lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                lengthMenu: [[-1], ['All']],
                ajax: {
                    url: APP_URL + '/general-ledger/transaction-authorize-mst-by-dtl-search-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.trans_mst_id = trans_mst_id;
                    }
                },
                "columns": [
                    /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "trans_batch_id"},*/
                    {"data": "gl_acc_id"},
                    {"data": "gl_acc_name"},
                    {"data": "party_id"},
                    {"data": "party_name"},
                    {"data": "budget_head_name"},
                    {"data": "debit_amount", "class": "text-right-align"},
                    {"data": "credit_amount", "class": "text-right-align"},
                    /*{"data": "cheque_no"},
                    {"data": "cheque_date"},
                    {"data": "challan_no"},
                    {"data": "challan_date"},*/
                    /* {"data": "narration"}*/
                ],
                columnDefs: [
                    {width: '10%', targets: 0},
                    {width: '5%', targets: 1},
                    {width: '15%', targets: 2},
                    {width: '15%', targets: 3},
                    {width: '20%', targets: 4},
                    {width: '10%', targets: 5},
                    {width: '10%', targets: 6},
                ],
                footerCallback: function( tfoot, data, start, end, display ) {
                    var api = this.api();
                    $(api.column(5).footer()).html(
                        api.column(5).data().reduce(function (a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0)
                    );
                    $(api.column(6).footer()).html(
                        api.column(6).data().reduce(function (a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0)
                    );
                }
            });
        }

        function returnTransactionMsg(transMstId) {
            let trans_mst_id = $(transMstId).attr('id');
            let transactionData = $(transMstId).data('transaction-data');
            //let transactionData =$(transMstId).attr('data-transaction-data');
            let transactionDataArray = transactionData.split('##');
            //console.log(transactionData);
            $.ajax({
                type: 'GET',
                /*'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },*/
                url: APP_URL + '/general-ledger/ajax/gl-transaction-mst-details',
                data: {trans_mst_id: trans_mst_id},
                success: function (data) {


                    if (data.workflow_approval_status != 'R') {
                        $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/GENERAL_LEDGER/RPT_TRANSACTION_LIST_BATCH_WISE.xdo&p_posting_period_id='+data.trans_period_id+'&p_trans_batch_id='+data.trans_batch_id+'&type=pdf&filename=transaction_detail"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i></a>');
                    } else {
                        $('#print_btn').html('');
                    }

                    $("#authorize_step_sec").html(data.authStep);

                    /*$('#mst_details_left_sec').html('<div class="row">' +
                        '<div class="col-md-5 "><b>Batch Id :</b></div><div class="col-md-7">' + data.trans_batch_id + '</div>' +
                        '<div class="col-md-5 "><b>Document Date :</b></div><div class="col-md-7">' + convertDate(data.document_date) + '</div>' +
                        '<div class="col-md-5 "><b>Posting Date :</b></div><div class="col-md-7">' + convertDate(data.trans_date) + '</div>' +
                        '<div class="col-md-5 "><b>Document Number :</b></div><div class="col-md-7">' + data.document_no + '</div>' +
                        '<div class="col-md-5 "><b>Document Reference :</b></div><div class="col-md-7">' + data.document_ref + '</div>' +
                        '</div>');
                    $('#mst_details_right_sec').html('<div class="row">' +
                        '<div class="col-md-6 "><b>Function Type :</b></div><div class="col-md-6">' + data.fun_type.function_name + '</div>' +
                        '<div class="col-md-6 "><b>Department/Cost Center :</b></div><div class="col-md-6">' + data.dept.cost_center_dept_name + '</div>' +
                        '<div class="col-md-6 "><b>Bill Section :</b></div><div class="col-md-6">' + data.bill_sec.bill_sec_name + '</div>' +
                        '<div class="col-md-6 "><b>Bill Register :</b></div><div class="col-md-6">' + data.bill_reg.bill_reg_name + '</div>' +
                        '</div>');
                    $('#mst_details_narration_sec').html('<div class="row">' +
                        '<div class="col-md-2 "><b>Narration :</b></div><div class="col-md-8 ml-1"><textarea class="form-control form-control-sm" disabled>' + data.narration + '</textarea></div>' +
                        '</div>');*/
                    let documentRef = (data.document_ref == null) ? '' : data.document_ref;

                    /*$('#mst_details_left_sec').html('<div class="row">' +
                        '<div class="col-md-4 "><b>Batch Id :</b></div><div class="col-md-7">' + data.trans_batch_id + '</div>' +
                        '<div class="col-md-4 "><b>Posting Date :</b></div><div class="col-md-7">' + convertDate(data.trans_date) + '</div>' +
                        '<div class="col-md-4 "><b>Document Date :</b></div><div class="col-md-7">' + convertDate(data.document_date) + '</div>' +
                        '<div class="col-md-4 "><b>Document Number :</b></div><div class="col-md-7">' + data.document_no + '</div>' +
                        '</div>');
                    $('#mst_details_right_sec').html('<div class="row">' +
                        '<div class="col-md-5 "><b>Function Type :</b></div><div class="col-md-6 pl-0 ml-0">' + data.fun_type.function_name + '</div>' +
                        '<div class="col-md-5 "><b>Department/Cost Center :</b></div><div class="col-md-6 pl-0 ml-0">' + data.dept.cost_center_dept_name + '</div>' +
                        '<div class="col-md-5 "><b>Bill Section :</b></div><div class="col-md-6 pl-0 ml-0">' + data.bill_sec.bill_sec_name + '</div>' +
                        '<div class="col-md-5 "><b>Bill Register :</b></div><div class="col-md-6 pl-0 ml-0">' + data.bill_reg.bill_reg_name + '</div>' +
                        '</div>');
                    $('#mst_details_narration_sec').html(
                        '<div class="row">' +
                        '<div class="col-md-2 "><b>Document Reference :</b></div><div class="col-md-10">' + documentRef + '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-2 "><b>Narration :</b></div><div class="col-md-10"><textarea class="form-control form-control-sm" disabled>' + data.narration + '</textarea></div>' +
                        '</div>'
                    );*/

                    $('#mst_details_left_sec').html('<div class="row">' +
                        '<div class="col-md-12">' +
                        '<table class="table table-sm table-borderless table p-0">' +
                        '<tbody>' +
                        '<tr><td width="35%">Batch Id</td><td width="3%">:</td><td>' + data.trans_batch_id + '</td></tr>' +
                        '<tr><td>Posting Date</td><td width="3%">:</td><td>' + convertDate(data.trans_date) + '</td></tr>' +
                        '<tr><td>Document Date</td><td width="3%">:</td><td>' + convertDate(data.document_date) + '</td></tr>' +
                        '<tr><td>Document Number</td><td width="3%">:</td><td>' + data.document_no + '</td></tr>' +
                        '</tbody>' +
                        '</table>' +
                        '</div> ' +
                        /*'<div class="col-md-5 "><b>Batch Id :</b></div><div class="col-md-7">' + data.trans_batch_id + '</div>' +
                        '<div class="col-md-5 "><b>Posting Date :</b></div><div class="col-md-7">' + convertDate(data.trans_date) + '</div>' +
                        '<div class="col-md-5 "><b>Document Date :</b></div><div class="col-md-7">' + convertDate(data.document_date) + '</div>' +
                        '<div class="col-md-5 "><b>Document Number :</b></div><div class="col-md-7">' + data.document_no + '</div>' +*/
                        '</div>');
                    $('#mst_details_right_sec').html('<div class="row">' +
                        '<div class="col-md-12">' +
                        '<table class="table table-sm table-borderless table p-0">' +
                        '<tbody>' +
                        '<tr><td width="36%">Function Type</td><td width="3%">:</td><td>' + data.fun_type.function_name + '</td></tr>' +
                        '<tr><td>Dept./Cost Center</td><td width="3%">:</td><td>' + data.dept.cost_center_dept_name + '</td></tr>' +
                        '<tr><td>Bill Section</td><td width="3%">:</td><td>' + data.bill_sec.bill_sec_name + '</td></tr>' +
                        '<tr><td>Bill Register</td><td width="3%">:</td><td>' + data.bill_reg.bill_reg_name + '</td></tr>' +
                        '</tbody>' +
                        '</table>' +
                        '</div> ' +
                        /*'<div class="col-md-5 "><b>Function Type :</b></div><div class="col-md-6 pl-0 ml-0">' + data.fun_type.function_name + '</div>' +
                        '<div class="col-md-5 "><b>Department/Cost Center :</b></div><div class="col-md-6 pl-0 ml-0">' + data.dept.cost_center_dept_name + '</div>' +
                        '<div class="col-md-5 "><b>Bill Section :</b></div><div class="col-md-6 pl-0 ml-0">' + data.bill_sec.bill_sec_name + '</div>' +
                        '<div class="col-md-5 "><b>Bill Register :</b></div><div class="col-md-6 pl-0 ml-0">' + data.bill_reg.bill_reg_name + '</div>' +*/

                        '</div>');
                    $('#mst_details_narration_sec').html(
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<table class="table table-sm table-borderless table p-0">' +
                        '<tbody>' +
                        '<tr><td width="17%">Document Reference</td><td width="3%">:</td><td>' + documentRef + '</td></tr>' +
                        '<tr><td>Narration</td><td width="3%">:</td><td><textarea class="form-control form-control-sm" disabled>' + data.narration + '</textarea></td></tr>' +
                        '</tbody>' +
                        '</table>' +
                        '</div> ' +
                        /*
                        '<div class="col-md-5 "><b>Document Reference :</b></div><div class="col-md-10">' + documentRef + '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-2"><b>Narration :</b></div><div class="col-md-7"><textarea class="form-control form-control-sm" disabled>' + data.narration + '</textarea></div>' +
                        */
                        '</div>'
                    );

                    let authorizeButtons = '<a  class="btn btn-primary mr-25 trans-approval" data-approval-status="{{ App\Enums\ApprovalStatus::APPROVED }}" ' +
                        'href="' + APP_URL + '/general-ledger/transaction-authorize-approval/' + transactionDataArray[0] +
                        '?wk_mst_id=' + transactionDataArray[1] +
                        '&ref_tbl=' + transactionDataArray[2] +
                        '&ref_status={{ App\Enums\ApprovalStatus::APPROVED }}' +
                        '&filter='+transactionDataArray[4]+'">'+
                        '<i class="bx bx-check-double cursor-pointer"></i> Authorize</a>';

                    let declineButtons = '<a  class="btn btn-danger trans-approval" data-approval-status="{{ App\Enums\ApprovalStatus::REJECT }}" ' +
                        'href="' + APP_URL + '/general-ledger/transaction-authorize-approval/' + transactionDataArray[0] +
                        '?wk_mst_id=' + transactionDataArray[1] +
                        '&ref_tbl=' + transactionDataArray[2] +
                        '&ref_status={{ App\Enums\ApprovalStatus::REJECT }}' +
                        '&filter='+transactionDataArray[4]+'">'+
                        '<i class="bx bx-x cursor-pointer"></i> Decline</a>';

                    let cancelButtons = '<a '+data.cancel_permission+' class="btn btn-sm btn-info trans-approval" data-approval-status="{{ App\Enums\ApprovalStatus::CANCEL }}" ' +
                        'href="' + APP_URL + '/general-ledger/transaction-authorize-approval/' + transactionDataArray[0] +
                        '?wk_mst_id=' + transactionDataArray[1] +
                        '&ref_tbl=' + transactionDataArray[2] +
                        '&ref_status={{ App\Enums\ApprovalStatus::CANCEL }}' +
                        '&trans_mst_id='+trans_mst_id+
                        '&filter='+transactionDataArray[4]+'">'+
                        '<i class="cursor-pointer text-small"></i> Cancel/Reverse</a>';

                    if (transactionDataArray[3] == 'A') {
                        /**Block this section as per IMAM VAI direction **/
                        /*$('.approval-user').html('<h6 class="text-primary font-medium-2">Last Approval User: <span class="text-dark font-medium-1 align-middle">' + data.approval_status.emp_info.emp_name + ' (' + data.approval_status.emp_info.emp_code + ' )' + '</span></h6>');
                        if (data.approval_status.reference_comment != "N/A"){
                            $('.approval-comment').html('<h6 class="text-primary font-medium-2">Last Approval Comments: <span class="text-dark font-medium-1 align-middle">' + data.approval_status.reference_comment + '</span></h6>');
                        }*/

                        $('#approvedButtons').html('');
                        $('#rejectButtons').html('');
                        $('#cancelButtons').html(cancelButtons);
                    } else if (transactionDataArray[3] == 'R') {
                        /**Block this section as per IMAM VAI direction **/
                        /*$('.approval-user').html('<h6 class="text-primary font-medium-2">Last Rejected User: <span class="text-dark font-medium-1 align-middle">' + data.approval_status.emp_info.emp_name + ' (' + data.approval_status.emp_info.emp_code + ' )' + '</span></h6>');
                        if (data.approval_status.reference_comment != "N/A"){
                            $('.approval-comment').html('<h6 class="text-primary font-medium-2">Last Rejected Comments: <span class="text-dark font-medium-1 align-middle">' + data.approval_status.reference_comment + '</span></h6>');
                        }*/
                        $('#approvedButtons').html('');
                        $('#rejectButtons').html('');
                        $('#cancelButtons').html('');
                        /*} else if ((transactionDataArray[3] == 'P') && (!nullEmptyUndefinedChecked(data.approval_status.emp_info))) {*/
                    } else if ((transactionDataArray[3] == 'P') ) {
                        //alert(data.workflow_approval_status );
                        $('.approval-user').html('');
                        $('.approval-comment').html('');
                        $('#approvedButtons').html(authorizeButtons);
                        $('#rejectButtons').html(declineButtons);
                        $('#cancelButtons').html('');
                    }

                    let attachmentHtml = '';

                    if (data.attachments.length != 0) {
                        $.each(data.attachments, function (key, value) {
                            let urlStr = '{{route("transaction.download-attachment",["trans_doc_file_id"=>"p_"])}}';
                            let fileUrl = urlStr.replace("p_", value['trans_doc_file_id']);

                            attachmentHtml += '<div class="row mt-1 rowCounter">' +
                                '                <div class="col-md-6">' +
                                '                    <div class="custom-file b-form-file form-group">' +
                                '                        <a class="pl-1 form-control form-control-sm" href="' + fileUrl + '" target="_blank"><i class="bx bx-download">' + value['trans_doc_file_name'] + '</i></a>' +
                                '                    </div>' +
                                '                </div>' +
                                '                <div class="col-md-6 mb-1"><input class="form-control form-control-sm" readonly type="text" value="' + value['trans_doc_file_desc'] + '"/></div>' +
                                '               </div>';
                        });
                    } else {
                        attachmentHtml += '<span>No attachment attached.</span>'
                    }
                    $('#attachmentsHtml').html(attachmentHtml);

                },
                error: function (data) {
                    alert('error');
                }
            });
        }

        $(document).on("click", '.trans-approval', function (e) {
            let action_url = this;
            //let trans_mst_id = $(this).attr('id');
            let approval_status = $(this).data('approval-status');
            let approval_status_val;
            let swal_input_type;

            if (approval_status == '{{\App\Enums\ApprovalStatus::APPROVED}}') {
                approval_status_val = 'Authorize';
                swal_input_type = null;
            } else if(approval_status == '{{\App\Enums\ApprovalStatus::CANCEL}}'){
                approval_status_val = 'Cancel';
                swal_input_type = 'text';
            } else {
                approval_status_val = 'Decline';
                swal_input_type = 'text';
            }

            //alert(action_url);
            //return;
            e.preventDefault();
            swal.fire({
                title: 'Are you sure?',
                text: 'Transaction ' + approval_status_val,
                type: 'warning',
                input: swal_input_type,
                inputPlaceholder: 'Reason For ' + approval_status_val+'?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                inputValidator: (result) => {
                    return !result && 'You need to provide a comment'
                },
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'

            }).then(function (isConfirm) {
                let remark;
                if (isConfirm.value) {
                    //window.location.href = action_url;
                    //console.log(isConfirm.value);
                    remark = isConfirm.value;
                    action_url += '&rem=' + remark;
                    window.location.href = action_url;
                } /*else if (isConfirm.value == true) {
                    remark = null;
                    console.log(isConfirm.value);
                    action_url+='&rem='+remark;
                    window.location.href = action_url;
                }*/ else if (isConfirm.dismiss == "cancel") {
                    //return false;
                    e.preventDefault();
                }
            })
        });

        $(document).ready(function () {
            listBillRegister();
            fiscalYear();
            searchParamWiseList();
            getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod,{{isset($filterData) ? $filterData[1] : ''}}); //Route Call General Leader

            /** Block-Start Pavel-14-07-22 **/
                /*$('#transaction-search-form').on('submit', function (e) {
                e.preventDefault();
                $('.trans-dtl-sec').hide();
                $('#authorize_step_sec').hide();
                oTable.draw();
            });

            $('#reset').on('click', function () {
                $("#fun_type_id").val('').trigger('change');
                //$("#period").val('').trigger('change');
                $("#bill_sec_id").val('').trigger('change');
                $("#bill_reg_id").val('').trigger('change');
                $("#dpt_id").val('').trigger('change');
                $('#resetMain').click();
                $('.trans-dtl-sec').hide();
                $('#authorize_step_sec').hide();
                oTable.draw();
            });

            $('#fun_type_id').change(function (e) {
                let funTypeId = $(this).val();

                $("#bill_sec_id").html("");
                $("#bill_reg_id").select2("destroy");
                $("#bill_reg_id").html("");
                $("#bill_reg_id").select2();
                getBillSectionOnFunction(funTypeId, "#bill_sec_id");
            });


            //datePicker("#posting_date");
            let postingCalendarClickCounter = 0;
            $("#posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#posting_date >input").val("");
                let minDate = $("#period :selected").data("mindate");
                let maxDate = $("#period :selected").data("maxdate");
                let currentDate = $("#period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate,currentDate);
            });
            searchTransMstByDtl();
            //invPayApproval();*/
            /** Block-End Pavel-14-07-22 **/
        });
    </script>
@endsection

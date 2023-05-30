@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <style rel="stylesheet">
        .debit_sum {
            text-align: right;
        }

        .credit_sum {
            text-align: right;
        }

        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="card-header bg-dark text-white p-75">Search Voucher</div>
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
            <form method="POST" id="reverse-journal-search-form">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fun_type_id" class="">Function Type</label>
                            <select class="custom-select form-control select2" id="fun_type_id" name="fun_type_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($cashTranFunTypeList as $value)
                                    <option value="{{$value->function_id}}">{{ $value->function_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="period" class="required">Posting Period</label>
                            <select class="custom-select form-control select2" id="period" name="period" required>
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
                                <input type="text" name="posting_date" id="posting_date_field"
                                       class="form-control datetimepicker-input posting_date"
                                       data-target="#posting_date" data-toggle="datetimepicker"
                                       value=""
                                       data-predefined-date=""
                                       placeholder="DD-MM-YYYY">
                                <div class="input-group-append posting_date" data-target="#posting_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted form-text"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="posting_batch_id" class="">Posting Batch Id</label>
                            <input class="form-control" id="posting_batch_id" name="posting_batch_id"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="dpt_id" class="">Department</label>
                            <select class="custom-select form-control select2" id="dpt_id" name="dpt_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($dptList as $value)
                                    <option value="{{$value->cost_center_dept_id}}">{{ $value->cost_center_dept_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="bill_sec_id" class="">Bill Section</label>
                            <select class="custom-select form-control select2" id="bill_sec_id" name="bill_sec_id">
                                <option value="">&lt;Select&gt;</option>
                                @foreach($lBillSecList as $value)
                                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="dpt_id" class="">Bill Register</label>
                            <select class="form-control" id="bill_reg_id" name="bill_reg_id">
                                <option value="">&lt;Select&gt;</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end pl-0 ">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mb-2 "><i class="bx bx-search"></i><span
                                    class="align-middle ">Search</span></button>
                            <button type="button" class="btn btn-secondary mb-2" id="reset"><i
                                    class="bx bx-reset"></i><span class="align-middle">Reset</span></button>
                            <button type="reset" class="btn btn-secondary mb-2 d-none" id="resetMain"></button>
                        </div>
                    </div>
                </div>
            </form>

            @include('gl.reverse-journal.master_list')

            @include('gl.reverse-journal.detail_list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function listBillRegister() {
            $('#bill_sec_id').change(function (e) {
                e.preventDefault();
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');

            });
        }

        var oTable = $('#reverse-journal-mst-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 5,
            bFilter: true,
            ordering: false,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + '/general-ledger/reverse-journal-mst-search-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.fun_type_id = $('#fun_type_id').val();
                    params.period = $('#period').val();
                    params.bill_sec_id = $('#bill_sec_id').val();
                    params.bill_reg_id = $('#bill_reg_id').val();
                    params.dpt_id = $('#dpt_id').val();
                    params.posting_date_field = $('#posting_date_field').val();
                    params.posting_batch_id = $('#posting_batch_id').val();
                }
            },
            "columns": [
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "trans_batch_id"},
                {"data": "function_name"},
                {"data": "trans_date"},
                {"data": "document_no"},
                {"data": "debit_sum"},
                {"data": "credit_sum"},
                {"data": "status"},
                {"data": "action"},

                /** Previous Query data**/
                /* {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                 {"data": "trans_batch_id"},
                 {"data": "fun_type.function_name"},
                 {"data": "trans_date"},
                 {"data": "document_no"},
                 {"data": "debit_sum"},
                 {"data": "credit_sum"},
                 {"data": "status"},
                 {"data": "action"},*/
            ],
            "columnDefs": [
                {targets: 5, className: 'text-right-align'},
                {targets: 6, className: 'text-right-align'},
            ]
        });

        $(document).on("click", '.trans-mst', function (e) {
            e.preventDefault();
            $('.trans-dtl-sec').show();
            searchTransMstByDtl(this);
            returnTransactionMsg(this);
            $('html, body').animate({scrollTop: $(".trans-dtl-sec").offset().top}, 2000);
        });

        function searchTransMstByDtl(transMstId) {
            let trans_mst_id = $(transMstId).attr('id');

            $('#reverse-journal-mst-by-dtl-search-list').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                pageLength: 5,
                bFilter: true,
                ordering: false,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                ajax: {
                    url: APP_URL + '/general-ledger/reverse-journal-mst-by-dtl-search-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.trans_mst_id = trans_mst_id;
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "trans_batch_id"},
                    {"data": "gl_acc_id"},
                    {"data": "gl_acc_name"},
                    {"data": "budget_head_name"},
                    {"data": "debit_amount"},
                    {"data": "credit_amount"},
                    /*{"data": "cheque_no"},
                    {"data": "cheque_date"},
                    {"data": "challan_no"},
                    {"data": "challan_date"},*/
                    /* {"data": "narration"}*/
                ],
                "columnDefs": [
                    {targets: 5, className: 'text-right-align'},
                    {targets: 6, className: 'text-right-align'},
                ]
            });
        }

        function returnTransactionMsg(transMstId) {
            let trans_mst_id = $(transMstId).attr('id');
            //console.log(transactionData);
            $.ajax({
                type: 'GET',
                /*'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },*/
                url: APP_URL + '/general-ledger/ajax/gl-transaction-mst-details',
                data: {trans_mst_id: trans_mst_id},
                success: function (data) {
                    //console.log(data);
                    $('#print_btn').html('<a target="_blank" href="{{request()->root()}}/report/render/rpt_transaction_list?xdo=/~weblogic/FAS_NEW/GENERAL_LEDGER/RPT_TRANSACTION_LIST.xdo&p_posting_period_id='+data.trans_period_id+'&p_trans_batch_id='+data.trans_batch_id+'&type=pdf&filename=transaction_detail"  class="cursor-pointer btn btn-sm btn-info"><i class="bx bx-printer"></i></a>');
                    $('#mst_details_left_sec').html('<div class="row">' +
                        '<div class="col-md-5 "><b>Batch Id :</b></div><div class="col-md-7">' + data.trans_batch_id + '</div>' +
                        '<div class="col-md-5 "><b>Document Date :</b></div><div class="col-md-7">' + convertDate(data.document_date) + '</div>' +
                        '<div class="col-md-5 "><b>Posting Date :</b></div><div class="col-md-7">' + convertDate(data.trans_date) + '</div>' +
                        '<div class="col-md-5 "><b>Document Number :</b></div><div class="col-md-7">' + data.document_no + '</div>' +
                        '<div class="col-md-5 "><b>Document Reference :</b></div><div class="col-md-7">' + data.document_ref + '</div>' +
                        '</div>');
                    $('#mst_details_right_sec').html('<div class="row">' +
                        '<div class="col-md-6 "><b>Function Type :</b></div><div class="col-md-6">' + data.fun_type.function_name + '</div>' +
                        '<div class="col-md-6 "><b>Department/Cost Center :</b></div><div class="col-md-6">' + data.dept.department_name + '</div>' +
                        '<div class="col-md-6 "><b>Bill Section :</b></div><div class="col-md-6">' + data.bill_sec.bill_sec_name + '</div>' +
                        '<div class="col-md-6 "><b>Bill Register :</b></div><div class="col-md-6">' + data.bill_reg.bill_reg_name + '</div>' +
                        '</div>');
                    $('#mst_details_narration_sec').html('<div class="row">' +
                        '<div class="col-md-2 "><b>Narration :</b></div><div class="col-md-8 ml-1"><textarea class="form-control" disabled>' + data.narration + '</textarea></div>' +
                        '</div>');
                    $('.transMasterId').val(data.trans_master_id);
                    $('.transPeriodId').val(data.trans_period_id);

                    let attachmentHtml = '';
                    if (data.attachments.length != 0) {
                        $.each(data.attachments, function (key, value) {
                            let urlStr = '{{route("reverse-journal.attachment-download",["trans_doc_file_id"=>"p_"])}}';
                            let fileUrl = urlStr.replace("p_", value['trans_doc_file_id']);

                            attachmentHtml += '<div class="row mt-1 rowCounter">' +
                                '                <div class="col-md-6">' +
                                '                    <div class="custom-file b-form-file form-group">' +
                                '                        <a class="pl-1 form-control" href="' + fileUrl + '" target="_blank"><i class="bx bx-download">' + value['trans_doc_file_name'] + '</i></a>' +
                                '                    </div>' +
                                '                </div>' +
                                '                <div class="col-md-6 mb-1"><input class="form-control" readonly type="text" value="' + value['trans_doc_file_desc'] + '"/></div>' +

                                '              </div>';
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

        $("#reverseJournal").on('submit',function (e) {
            e.preventDefault();
            let form = this;
            swal.fire({
                text: 'Reverse Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result)=>{
                if (result.value == true){
                    form.submit();
                }
            });
        });

        $(document).ready(function () {
            $('#reverse-journal-search-form').on('submit', function (e) {
                e.preventDefault();
                $('.trans-dtl-sec').hide();
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
                oTable.draw();
            });

            listBillRegister();
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
            //searchTransMstByDtl();
        });
    </script>
@endsection

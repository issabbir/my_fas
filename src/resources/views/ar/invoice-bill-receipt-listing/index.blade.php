@extends('layouts.default')

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
        <div class="card-header bg-dark text-white p-75">Search Invoice Bill Collection</div>
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
            <form method="POST" id="invoice-bill-receipt-search-form">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="th_fiscal_year" class="">Fiscal Year</label>
                        <select name="th_fiscal_year"
                                class="form-control form-control-sm required select2 search-param"
                                id="th_fiscal_year">
                            @foreach($fiscalYear as $year)
                                <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="period" class="">Posting Period</label>
                        <select class="form-control form-control-sm select2 search-param" id="period" name="period" required>
                            {{--@foreach($data['postingDate'] as $post)
                                <option
                                    {{  ((old('period') ==  $post->posting_period_id) || ($post->posting_period_status == 'O')) ? "selected" : "" }} data-mindate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_beg_date)}}"
                                    data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($post->posting_period_end_date)}}"
                                    data-currentdate="{{ \App\Helpers\HelperClass::dateConvert($post->current_posting_date)}}"
                                    data-postingname="{{ $post->posting_period_name}}"
                                    value="{{$post->posting_period_id}}">{{ $post->posting_period_name}}
                                </option>
                            @endforeach--}}
                        </select>
                    </div>
                    {{-- Start Add Block Pavel-08-06-22/09-06-22 --}}
                    <div class="form-group col-md-2">
                        <label for="bill_section" class="">Bill Section</label>
                        <select name="bill_section" class="form-control form-control-sm select2 search-param"
                                id="bill_section">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($data['billSecs'] as $value)
                                <option {{isset($filterData) ? (($value->bill_sec_id == $filterData[2]) ? 'selected' : '') : ''}} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="bill_reg_id" class="">Bill Register</label>
                        <select class="form-control form-control-sm select2 search-param" id="bill_reg_id" name="bill_reg_id">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="approval_status" class="">Approval Status</label>
                        <select name="approval_status" class="form-control form-control-sm select2 search-param"
                                id="approval_status">
                            {{--<option value="">Select Status</option>--}}
                            <option value="">&lt;Select&gt;</option>
                            @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                               {{-- <option value="{{$key}}">{{ $value}}</option>--}}
                                <option value="{{$key}}"  >{{ $value}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- End Add Block Pavel-08-06-22/09-06-22 --}}
                </div>
            </form>

            @include('ar.invoice-bill-receipt-listing.list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">
        var oTable = $('#invoice-bill-receipt-search-list').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 5,
            bFilter: true,
            ordering: false,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + '/account-receivable/invoice-bill-receipt-search-list',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    var dt_params = {
                        fiscalYear : $('#th_fiscal_year :selected').val(),
                        period: $('#period :selected').val(),
                        approval_status: $('#approval_status :selected').val(),
                        bill_section: $('#bill_section :selected').val(),
                        bill_reg_id: $('#bill_reg_id :selected').val()
                    };
                    // Add dynamic parameters to the data object sent to the server
                    if (dt_params) {
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "batch_id"},
                {"data": "instrument_type_name"},
                {"data": "instrument_no"},
                {"data": "instrument_date"},
                {"data": "document_no"},
                {"data": "customer_name"},
                {"data": "receipt_amount"},
                {"data": "status"},
                {"data": "action", "orderable": false},
            ],
            "columnDefs": [
                {targets: 5, className: 'text-right-align'},
            ]
        });

        $(document).ready(function () {
            $("#period").on('change', function () {
                reloadDataTable();
            });

            $('#bill_section').select2().change(function (e) {
                $("#bill_reg_id").val("");
                let billSectionId = $(this).val();
                selectBillRegister('#bill_reg_id', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
                reloadDataTable();
            });

            $('#bill_reg_id').select2().on('change', function () {
                reloadDataTable();
            })

            $("#approval_status").on('change',function () {
                reloadDataTable();
            })

            function reloadDataTable() {
                oTable.draw();
            }

            $("#th_fiscal_year").on('change',function () {
                getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            getPostingPeriod($("#th_fiscal_year :selected").val(),'{{route("ajax.get-current-posting-period")}}', setPostingPeriod,{{isset($filterData) ? $filterData[1] : ''}});
            function setPostingPeriod(periods) {
                $("#period").html(periods);
                //setPeriodCurrentDate();
                $("#period").trigger('change');
            }
        });
    </script>
@endsection

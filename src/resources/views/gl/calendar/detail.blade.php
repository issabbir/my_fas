@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="card-title">Selected Calendar Details</h4>
                    <a href="{{route('calendar.index')}}">
                        <span class="badge badge-primary font-small-4"><i class="bx bx-log-out font-small-3 align-middle"></i> Back</span>
                    </a>
                </div>
                <!-- Table Start -->
                <div class="card-body pt-0">
                    <hr>
                    @if(Session::has('message'))
                        <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fiscal_period" class="required">Fiscal Period</label>
                                <input class="form-control" id="fiscal_period" name="fiscal_period" disabled
                                       value="{{old('fiscal_period',isset($calMst->fiscal_period_id) ? $calMst->fiscal_period->fiscal_period_nm : '')}}"/>
                                <div class="text-muted form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_start" class="">Year Start</label>
                                <input class="form-control" id="year_start" name="year_start" disabled
                                       value="{{old('year_start',isset($calMst->fiscal_beg_year) ? $calMst->fiscal_beg_year : '')}}"/>
                                <div class="text-muted form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_end" class="">Year End</label>
                                <input class="form-control" id="year_end" name="year_end" disabled
                                       value="{{old('year_end',isset($calMst->fiscal_end_year) ? $calMst->fiscal_end_year : '')}}"/>
                                <div class="text-muted form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cal_status" class="">Calender Status</label>
                                <input class="form-control" id="cal_status" name="cal_status" disabled
                                   value="@if (isset($calMst->calendar_status) && ($calMst->calendar_status == \App\Enums\Gl\CalendarStatus::OPENED)) {{\App\Enums\Gl\CalendarStatusView::OPENED}}
                                            @elseif (isset($calMst->calendar_status) && ($calMst->calendar_status == \App\Enums\Gl\CalendarStatus::CLOSED)){{\App\Enums\Gl\CalendarStatusView::CLOSED}}
                                            @elseif (isset($calMst->calendar_status) && ($calMst->calendar_status == \App\Enums\Gl\CalendarStatus::INACTIVE)){{\App\Enums\Gl\CalendarStatusView::INACTIVE}}
                                            @elseif (isset($calMst->calendar_status) && ($calMst->calendar_status == \App\Enums\Gl\CalendarStatus::OPENED_SPECIAL)){{\App\Enums\Gl\CalendarStatusView::OPENED_SPECIAL}}
                                          @endif"
                                       {{--value="{{old('cal_status',isset($calMst->calendar_status) && ($calMst->calendar_status == \App\Enums\Gl\CalendarStatus::CLOSED) ? \App\Enums\Gl\CalendarStatusView::CLOSED : '')}}"--}} />
                                <div class="text-muted form-text"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-9">
                            <h6 class="mb-0"><strong>Calendar Detail list</strong></h6>
                        </div>
                        <div class="col-md-3 form-group ">
                            <div class="position-relative has-icon-left">
                                <input type="text" id="table_search" class="form-control" placeholder="Search Value"/>
                                <div class="form-control-position"><i class="bx bx-search"></i></div>
                            </div>
                        </div>
                        <div class="col-md-12 table-responsive {{--fixed-height-scrollable--}}">
                            <table class="table table-sm table-bordered table-striped">
                                <thead class="thead-light {{--sticky-head--}}">
                                <tr>
                                    <th>#Sl</th>
                                    <th>Posting Period</th>
                                    <th>Beg. Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($calDtl) > 0)
                                    @php
                                        $index=1;
                                    @endphp
                                    @foreach ($calDtl as $value)
                                        <tr>
                                            <td>{{ $index }}<input type="hidden" name="calendar_detail_id[{{ $value->calendar_detail_id }}]" value="{{$value->calendar_detail_id}}"></td>
                                            <td class="posting-period">{{ $value->posting_period_display_name }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_beg_date)  }}</td>
                                            <td>{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_end_date) }}</td>
                                            <td class="curr-period-status">@if ($value->posting_period_status == \App\Enums\Gl\CalendarStatus::INACTIVE) {{\App\Enums\Gl\CalendarStatusView::INACTIVE}} @elseif ($value->posting_period_status == \App\Enums\Gl\CalendarStatus::OPENED) {{\App\Enums\Gl\CalendarStatusView::OPENED}} @elseif ($value->posting_period_status == \App\Enums\Gl\CalendarStatus::CLOSED) {{\App\Enums\Gl\CalendarStatusView::CLOSED}} @else {{\App\Enums\Gl\CalendarStatusView::OPENED_SPECIAL}} @endif</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm @if( ($value->posting_period_status == \App\Enums\Gl\CalendarStatus::CLOSED) /*||  ($calMst->calendar_status != \App\Enums\Gl\CalendarStatus::OPENED)*/ ) disabled @else cal-dtl @endif"
                                                        id="{{$value->calendar_detail_id}}">{{--<span class="badge badge-primary badge-pill mt-25 mb-25 font">Select</span>--}}
                                                    Setting
                                                </button>
                                            </td>
                                        </tr>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                    <!-- Display this <tr> when no record found while search -->
                                    <tr class='notfound' style="display: none">
                                        <th colspan="7" class="text-center">Search Data Not Found</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="7" class="text-center"> No Data Found</th>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form method="POST" class="cal_dtl_form mt-3" id="gl-cal-dtl-form"
                          @if(isset($calMst->calendar_id)) style="display:none;"
                          action="{{route('calendar.detail-store',['cal_id' => $calMst->calendar_id])}}" @endif>
                        @csrf
                        <div class="row border ">
                            <div class="col-md-12 mt-1">
                                <h6 class="mb-2"><strong>Selected Posting Period</strong></h6>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="selected_posting_period">Posting Period</label>
                                    <input class="form-control" id="selected_posting_period" name="selected_posting_period" disabled/>
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="selected_period_status" class="">Current Status</label>
                                    <input class="form-control" id="selected_period_status" name="selected_period_status" disabled/>
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pos_period_status" class="required">Change Status</label>
                                    <select class="form-control" id="status_options" name="pos_period_status" required>
                                        <option value="">&lt;Select&gt;</option>
                                        {{--
                                        @foreach(\App\Enums\Gl\CalendarStatus::STATUS as $key=>$value)
                                            @if ( $key != \App\Enums\Gl\CalendarStatus::INACTIVE)
                                                <option value="{{$key}}">{{ $value}}</option>
                                            @endif
                                        @endforeach--}}
                                    </select>
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                            <input type="hidden" id="selected_cal_dtl_id" name="selected_cal_dtl_id"/>
                            <div class="col-md-5">
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-repost"></i><span class="align-middle ml-25">Execute Status Change</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function glCalDtlForm() {
            $('.cal-dtl').click(function (e) {
                e.preventDefault();
                let calDtlId = $(this).attr('id');

                let preUrl = '{{route("calendar.status-list-on-detail",["detailId"=>"_p"])}}';
                let url = preUrl.replace("_p", calDtlId);
                let response = $.ajax({
                    url: url,
                    async: false
                });
                response.done(function (d) {
                    $("#status_options").empty().append(new Option('Select One', ''));
                    if (d.status == '200') {
                        $.each(d.data, function ($key, $value) {
                            if ($value["period_status_code"] != null){
                                $("#status_options").append(new Option($value["period_status_name"], $value["period_status_code"]));
                            }
                        });
                    }else if(d.status == '500'){
                        console.log(d.msg);
                    }
                });

                response.fail(function (e) {
                    console.log(e);
                });

                //var row = $(this).closest("tr").find(".nr").text();    // Find the row
                if (calDtlId) {
                    $('.cal_dtl_form').show();
                    $('html, body').animate({scrollTop: $(".cal_dtl_form").offset().top}, 2000);

                    $('#selected_cal_dtl_id').val(calDtlId);
                    $('#selected_posting_period').val($(this).closest("tr").find(".posting-period").text());
                    $('#selected_period_status').val($(this).closest("tr").find(".curr-period-status").text());

                } else {
                    $('.cal_dtl_form').hide();
                }
            });
        }

        function checkGlCalDtlForm() {
            $('#gl-cal-dtl-form').submit(function (e) {
                var form = this;
                e.preventDefault();
                swal.fire({
                    title: 'Are you sure?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        form.submit();
                    } else if (isConfirm.dismiss == "cancel") {
                        //return false;
                        e.preventDefault();
                    }
                })
            });
        }

        function tableSearchAllColumns() {
            // Search all columns
            $('#table_search').keyup(function () {
                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('table tbody tr').hide();

                // Count total search result
                var len = $('table tbody tr:not(.notfound) td:contains("' + search + '")').length;

                if (len > 0) {
                    // Searching text in columns and show match row
                    $('table tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                        $(this).closest('tr').show();
                    });
                } else {
                    $('.notfound').show();
                }

            });
            // Case-insensitive searching (Note - remove the below script for Case sensitive search )
            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });
        }

        $(document).ready(function () {
            glCalDtlForm();
            checkGlCalDtlForm();
            tableSearchAllColumns();
        });


    </script>
@endsection

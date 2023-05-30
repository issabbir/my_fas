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
                    <form method="POST" id="gl-periods-form" @if(isset($calMst->calendar_id))
                        action="{{route('calendar.detail-store',['cal_id' => $calMst->calendar_id])}}" @endif>
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fiscal_period" class="required">Fiscal Period</label>
                                    <input class="form-control"  id="fiscal_period" name="fiscal_period" disabled
                                           value="{{old('fiscal_period',isset($calMst->fiscal_period_id) ? $calMst->fiscal_period->fiscal_period_nm : '')}}" />
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_start" class="">Year Start</label>
                                    <input class="form-control"  id="year_start" name="year_start" disabled
                                           value="{{old('year_start',isset($calMst->fiscal_beg_year) ? $calMst->fiscal_beg_year : '')}}" />
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_end" class="">Year End</label>
                                    <input class="form-control"  id="year_end" name="year_end" disabled
                                           value="{{old('year_end',isset($calMst->fiscal_end_year) ? $calMst->fiscal_end_year : '')}}" />
                                    <div class="text-muted form-text"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                           {{-- <div class="col-md-12 text-center" style="display:none;" id="loading-image">
                                <strong class="text-secondary">Loading...</strong>
                                <div class="spinner-border ml-auto text-secondary" role="status" aria-hidden="true"></div>
                            </div>--}}
                            <div class="col-md-9">
                                <h6 class="mb-0"><strong>Calendar Detail list</strong></h6>
                            </div>
                            <div class="col-md-3 form-group ">
                                <div class="position-relative has-icon-left">
                                    <input type="text" id="table_search" class="form-control"  placeholder="Search Value" />
                                    <div class="form-control-position"><i class="bx bx-search"></i></div>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive fixed-height-scrollable">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead class="thead-light sticky-head">
                                    <tr>
                                        <th>#Sl</th>
                                        <th>Posting Period</th>
                                        <th>Beg. Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody {{--id="glPeriodsList"--}}>
                                        @if(count($calDtl) > 0)
                                            @php
                                                $index=1;
                                            @endphp
                                            @foreach ($calDtl as $value)
                                                <tr>
                                                    <td>{{ $index }} <input type="hidden" name="calendar_detail_id[]" value="{{$value->calendar_detail_id}}"></td>
                                                    <td>{{ $value->posting_period_display_name }}</td>
                                                    <td>{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_beg_date)  }}</td>
                                                    <td>{{ \App\Helpers\HelperClass::dateConvert($value->posting_period_end_date) }}</td>
                                                    <td>
                                                        <select class="form-control select-period-status
                                                            @if($value->posting_period_status == 'C') make-readonly-bg
                                                            {{--@elseif($value->posting_period_status == 'I' && $value->previous_month_status == 'I') make-readonly-bg --}}
                                                            @elseif( ($value->posting_period_status == 'I' && $value->previous_month_status == 'I') || $value->previous_month_status == 'O') make-readonly-bg
                                                            @endif"  name="calendar_detail_status[]">
                                                            {{--<option value="">Select One</option>--}}
                                                            {{--@foreach(\App\Enums\Gl\CalendarStatus::STATUS as $key=>$value)
                                                                <option value="{{$key}}">{{ $value}}</option>
                                                            @endforeach--}}
                                                            {{--@foreach($perStatus as $status)--}}
                                                            @foreach (\App\Enums\Gl\CalendarStatus::STATUS as $key=>$status)
                                                                @if ($value->posting_period_status == 'C' && $key == 'C')
                                                                    <option value="{{$key}}"
                                                                        {{old('period_status',isset($value->posting_period_status) && $value->posting_period_status == $key  ? 'selected' : '')}}>
                                                                        {{$status}}
                                                                    </option>
                                                                @elseif ($value->posting_period_status == 'O' && $key != 'I')
                                                                    <option value="{{$key}}"
                                                                        {{old('period_status',isset($value->posting_period_status) && $value->posting_period_status == $key  ? 'selected' : '')}}>
                                                                        {{$status}}
                                                                    </option>
                                                                {{-- TODO: INFUTURE USE FOR THIS CONDITION
                                                                @elseif ($value->posting_period_status == 'I' && $key == 'I' && ($value->previous_month_status == 'O' || $value->previous_month_status == 'C'))
                                                                    <option value="{{$key}}"
                                                                        {{old('period_status',isset($value->posting_period_status) && $value->posting_period_status == $key  ? 'selected' : '')}}>
                                                                        {{$status}}
                                                                    </option>
                                                                TODO: INFUTURE USE FOR THIS CONDITION  --}}
                                                                @elseif($value->posting_period_status == 'I')
                                                                    <option value="{{$key}}"
                                                                        {{old('period_status',isset($value->posting_period_status) && $value->posting_period_status == $key  ? 'selected' : '')}} >
                                                                        {{$status}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>

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

                        <div class="row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                                {{--<button type="reset" class="btn btn-light-secondary mb-1">Reset</button>--}}
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

        /*function searchGlPeriods() {
            $('.target-value').change(function (e) {
                e.preventDefault();
                glPeriodsList();

            });
        }*/

        /*function glPeriodsList() {
            $.ajax({
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: APP_URL + '/general-ledger/periods-search-list',
                data: $('#gl-periods-form').serialize(),
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function (data) {
                    $('#glPeriodsList').html(data.html);
                    $("#loading-image").hide();
                },
                error: function (data) {
                    alert('error');
                }
            });
        }*/

        function checkGlPeriodsForm(){
            $('#gl-periods-form').submit(function(e){
                var form = this;
                e.preventDefault();
                if(selectPeriodsStatus()) {
                    swal.fire({
                        title: 'Are you sure?',
                        text: 'You want to update list of data',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save it!'
                    }).then(function(isConfirm) {
                        if (isConfirm.value == true) {
                            form.submit();

                        } else if(isConfirm.dismiss == "cancel") {
                            //return false;
                            e.preventDefault();
                        }
                    })
                }
                else {
                    Swal.fire("At least one row selected for status", "", "info");
                    e.preventDefault();
                }
            });
        }

        function selectPeriodsStatus() {
            var flag = false;
            $('.select-period-status :selected').each(function() {
                if($(this).val().length > 0) {
                    flag = true;
                    return false;
                }
            });
            return flag;
        }

        function tableSearchAllColumns(){
            // Search all columns
            $('#table_search').keyup(function(){
                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('table tbody tr').hide();

                // Count total search result
                var len = $('table tbody tr:not(.notfound) td:contains("'+search+'")').length;

                if(len > 0){
                    // Searching text in columns and show match row
                    $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
                        $(this).closest('tr').show();
                    });
                }else{
                    $('.notfound').show();
                }

            });

            /*// Search on name column only
            $('#txt_name').keyup(function(){
                // Search Text
                var search = $(this).val();

                // Hide all table tbody rows
                $('table tbody tr').hide();

                // Count total search result
                var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("'+search+'")').length;

                if(len > 0){
                    // Searching text in columns and show match row
                    $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
                        $(this).closest('tr').show();
                    });
                }else{
                    $('.notfound').show();
                }

            });*/

            // Case-insensitive searching (Note - remove the below script for Case sensitive search )
            $.expr[":"].contains = $.expr.createPseudo(function(arg) {
                return function( elem ) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });
        }

        $(document).ready(function () {
            //searchGlPeriods();
            //glPeriodsList();
            checkGlPeriodsForm();
            tableSearchAllColumns();
        });


    </script>
@endsection

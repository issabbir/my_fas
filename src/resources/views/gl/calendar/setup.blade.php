<?php
/**
 *Created by Sujon Chondro Shil
 *Created at ৩/৩/২১ ১১:১৬ AM
 */
?>
@extends('layouts.default')

@section('title')

@endsection

@section('header-style')

@endsection
@section('content')
    <div class="card">
        <div class="card-body border">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex ">
                        <h4 class="flex-grow-1" style="text-decoration: underline;">New Calender Setup</h4>
                        <a href="{{route("calendar.default-setup")}}" class="btn btn-light-info btn-sm">Default Properties</a>
                        <a href="{{route("calendar.index")}}" class="btn btn-primary btn-sm ml-1"><i class="bx bx-log-out font-size-small"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <form method="post" action="{{route("calendar.store")}}">
                            @csrf
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <label for="fiscalYearPeriod" class="col-form-label">Fiscal Year Period</label>
                                <select class="form-control form-control-sm make-readonly" name="fiscalYearPeriod" readonly>
                                    @foreach($data['fiscalPeriod'] as $period)
                                        <option value="{{$period->fiscal_period_id}}" @if(old('fiscalYearPeriod',isset($data['defaultProperty']) ? $data['defaultProperty']->fiscal_period_id:'') == $period->fiscal_period_id) selected @endif>{{ $period->fiscal_period_nm }}</option>
                                    @endforeach
                                </select>
                                <span></span>
                            </div>
                            <div class="col-md-3">
                                <label for="dateFrom" class="col-form-label required">Year Start</label>
                                <div class="input-group date year_start"
                                     id="year_start"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="yearStart"
                                           id="yearStart"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#year_start"
                                           data-toggle="datetimepicker"
                                           value="{{ old('yearStart', isset($data['insertedData']->dateFrom) ?  $data['insertedData']->dateFrom : '') }}"
                                           {{--data-predefined-date="{{ old('yearStart', isset($data['insertedData']->dateFrom) ?  $data['insertedData']->dateFrom : '') }}"--}}
                                           placeholder="YYYY">
                                    <div class="input-group-append year_start" data-target="#year_start"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bx-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('yearStart')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="dateFrom" class="col-form-label required">Year End</label>
                                <div class="input-group date year_end"
                                     id="year_end"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="yearEnd"
                                           id="yearEnd"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#year_end"
                                           data-toggle="datetimepicker"
                                           value="{{ old('yearEnd', isset($data['insertedData']->dateFrom) ? $data['insertedData']->dateFrom: '') }}"
                                           data-predefined-date="{{ old('yearEnd', isset($data['insertedData']->dateFrom) ? $data['insertedData']->dateFrom: '') }}"
                                           placeholder="YYYY">
                                    <div class="input-group-append year_end" data-target="#year_end"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bx-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('yearEnd')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <label for="postingCalendarPeriod" class="col-form-label">Posting Calendar Period</label>
                                <select class="form-control form-control-sm make-readonly" name="postingCalendarPeriod" readonly>
                                    @foreach($data['periodType'] as $type)
                                        <option value="{{$type->period_type_code}}" @if(old('postingCalendarPeriod',isset($data['defaultProperty']) ? $data['defaultProperty']->posting_period_code:'') == $type->period_type_code) selected @endif>{{ $type->period_type_nm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <label class="col-form-label"></label>
                                <div class="d-flex justify-content-between mt-1">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="bx bx-save font-size-small"></i>Save</button>
                                    <button type="reset" class="btn btn-dark btn-sm "><i class="bx bx-reset font-size-small"></i>Refresh</button>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            yearPicker("#year_start");
            yearPicker("#year_end");
        });

    </script>
@endsection

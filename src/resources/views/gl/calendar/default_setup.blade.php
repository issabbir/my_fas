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
                <div class="d-flex justify-content-between">
                    <h4 style="text-decoration: underline">Calender Default Properties</h4>
                    <a href="{{route("calendar.setup")}}" class="btn btn-primary btn-sm ml-1"><i class="bx bx-log-out font-size-small"></i></a>
                </div>
            </div>
        </div>
        <div class="row pt-0">
            <div class="col-md-12">
                <form method="post" action="{{route("calendar.default-store")}}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="fiscalYearPeriod" class="col-form-label">Fiscal Year Period</label>
                        <select class="form-control form-control-sm" name="fiscalYearPeriod" id="fiscalYearPeriod">
                            @foreach($data['fiscalPeriod'] as $period)
                                <option value="{{$period->fiscal_period_id}}" @if(old('fiscalYearPeriod',isset($data['defaultProperty']) ? $data['defaultProperty']->fiscal_period_id:'') == $period->fiscal_period_id) selected @endif>{{ $period->fiscal_period_nm }}</option>
                            @endforeach
                        </select>
                        @error('fiscalYearPeriod')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-1"></div>
                </div>
                    <div class="row pt-1">
                        <div class="col-md-3">
                            <label for="postingCalendarPeriod" class="col-form-label">Posting Calendar Period</label>
                            <select class="form-control form-control-sm" name="postingCalendarPeriod" id="postingCalendarPeriod">
                                @foreach($data['periodType'] as $type)
                                    <option value="{{$type->period_type_code}}" @if(old('postingCalendarPeriod',isset($data['defaultProperty']) ? $data['defaultProperty']->posting_period_code:'') == $type->period_type_code) selected @endif>{{ $type->period_type_nm }}</option>
                                @endforeach
                            </select>
                            @error('postingCalendarPeriod')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                {{--<div class="row pt-1">
                    <div class="col-md-4">
                        <label for="maxYear" class="col-form-label">Max. Fiscal Year Parallel Allowed</label>
                    </div>
                    <div class="col-md-1">
                        <input type="number" class="form-control" min="1" name="maxYear" id="maxYear" value="{{old('maxYear',isset($data['defaultProperty']) ? $data['defaultProperty']->max_fiscal_year_open_allow:1)}}">
                        @error('maxYear')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>--}}
                <div class="row pt-1">
                    <div class="col-md-3">
                        <label for="maxPeriod" class="col-form-label">Max. Posting Period Parallel Allowed</label>
                    </div>
                    <div class="col-md-1">
                        <input type="number" class="form-control form-control-sm" min="1" name="maxPeriod" id="maxPeriod" value="{{old('maxPeriod',isset($data['defaultProperty']) ? $data['defaultProperty']->max_posting_prd_open_allow:1)}}">
                        @error('maxPeriod')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-10 d-flex justify-content-start">
                                <button type="submit" class="btn btn-success btn-sm mt-2"><i class="bx bx-save font-size-small"></i>Save</button>
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
    $(document).ready(function () {
        yearPicker("#year_start");
        yearPicker("#year_end");
    });

</script>
@endsection

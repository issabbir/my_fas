<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৮ PM
 */
?>
<div class="card">
    <div class="card-body">
        <h5 style="text-decoration: underline">Invoice/Bill Listing</h5>
        <div class="row">
            <fieldset class="border col-md-12">
                <legend class="w-auto" style="font-size: 15px;">Search Criteria
                </legend>
                <div class="col-md-12">
                    <form method="POST" id="invoice-bill-search-form">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="th_fiscal_year" class="">Fiscal Year</label>
                                <select name="th_fiscal_year"
                                        class="form-control form-control-sm required search-param"
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
                                    <option value="">&lt;Select&gt;</option>
                                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                       {{-- <option value="{{$key}}">{{ $value}}</option>--}}
                                        <option  value="{{$key}}"  >{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- End Add Block Pavel-08-06-22/09-06-22 --}}
                        </div>

                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>

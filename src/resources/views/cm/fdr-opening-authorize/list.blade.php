<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৪:৫৮ PM
 */
?>
@extends("layouts.default")

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

        .max-w-14 {
            max-width: 14% !important;
        }

        .max-w-15 {
            max-width: 15% !important;
        }

        .max-w-30 {
            max-width: 30% !important;
        }

        .max-w-12_5 {
            max-width: 12.5% !important;
        }

        .w-86 {
            width: 86% !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-dark text-white p-75">FDR Opening Trans Authorize</div>
        {{--<div class="card-header pb-0"><h4 class="card-title mb-0">INVESTMENT LISTING</h4>
            <hr>
        </div>--}}
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
            <fieldset class="border p-2">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                <div class="row">
                    <div class="col-md-3 form-group ">
                        <label for="investment_type" class="col-form-label">Investment Type</label>
                        <select class="custom-select form-control form-control-sm select2" name="investment_type"
                                id="investment_type">
                            @foreach($investmentTypes as $type)
                                <option {{isset($filterData) ? (($type->investment_type_id == $filterData[0]) ? 'selected' : '') : ''}} value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="fiscal_year" class="required col-form-label">Fiscal Year</label>
                        <select required name="fiscal_year"
                                class="form-control form-control-sm required"
                                id="fiscal_year">
                            <option value="">&lt;Select&gt;</option>
                            @foreach($fiscalYear as $year)
                                <option {{isset($filterData) ? (($year->fiscal_year_id == $filterData[1]) ? 'selected' : '') : ''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="period" class="required col-form-label">Posting Period</label>
                        <select required name="period" class="form-control form-control-sm" id="period">
                        </select>
                    </div>
                    <div class="col-md-3 form-group ">
                        <label for="approval_status" class="col-form-label">Approval Status</label>
                        <select class="form-control form-control-sm" name="approval_status"
                                id="approval_status">
                            <option value="">&lt;Select&gt;</option>
                            @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                                <option {{isset($filterData) ? (($key == $filterData[3]) ? 'selected' : '') : ''}} value="{{$key}}" {{ ($key == \App\Enums\ApprovalStatus::PENDING) ? 'selected' : ''}} >{{ $value}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="col-md-1">
                        <button class="btn btn-sm btn-primary" id="opening_search" style="margin-top: 33px;">Search
                        </button>
                    </div>--}}
                </div>
            </fieldset>
            <fieldset class="border p-1 mt-1 mb-1">
                <legend class="w-auto text-bold-600" style="font-size: 15px;">Opening Trans List</legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive mt-1">
                            <table id="opening_list" class="table table-sm table-striped table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th width="10%">Posting Date</th>
                                    <th width="18%">Bank</th>
                                    <th width="22%">Branch</th>
                                    <th width="15%">FDR No.</th>
                                    <th width="15%">Amount</th>
                                    <th width="12%" class="text-center">Auth Status</th>
                                    <th width="8%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        function fdrOpeningTransactionLists() {
            function reloadOpeningListTable() {
                $('#opening_list').DataTable().draw();
            }

            let openingList = $('#opening_list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/cash-management/fdr-opening-authorize-list',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        params.investmentType = $('#investment_type :selected').val();
                        params.fiscalYear = $('#fiscal_year :selected').val();
                        params.period = $('#period :selected').val();
                        params.approvalStatus = $('#approval_status :selected').val();
                    }
                },
                columns: [
                    {data: 'transaction_date', name: 'transaction_date'},
                    {data: 'bank', name: 'bank'},
                    {data: 'branch', name: 'branch'},
                    {data: 'fdr_no', name: 'fdr_no'},
                    {data: 'amount', name: 'amount'},
                    {data: 'auth_status', name: 'auth_status'},
                    {data: 'action', name: 'Action'},
                ],
                createdRow: function (row, data, index) {
                    $('td', row).eq(4).addClass("text-right");
                }
            });
            /*$(document).on('click', '.fdr_select', function () {
                getFdrDetail($(this).data('fdr'), setFdrDebitInfo);
                $("#investmentListModal").modal('hide');
            })*/
            $("#investment_type,#period, #approval_status").on('change', function () {
                reloadOpeningListTable();
            })
            $("#fiscal_year").on('change', function () {
                reloadOpeningListTable();
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

                function setPostingPeriod(periods) {
                    $("#period").html(periods);
                }
            })
        }
        function fiscalYearGetsPostingPeriod() {
            $("#fiscal_year").on('change', function () {
                getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
        }

        getPostingPeriod($("#fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod, {{isset($filterData) ? $filterData[2] : ''}});

        function setPostingPeriod(periods) {
            $("#period").html(periods);
            $("#period").trigger('change');
        }
        $(document).ready(function () {
            fiscalYearGetsPostingPeriod();
            fdrOpeningTransactionLists()
        })
    </script>
@endsection


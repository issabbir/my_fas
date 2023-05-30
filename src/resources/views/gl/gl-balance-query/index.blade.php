<?php
/**
 *Created by PhpStorm
 *Created at ১৫/৯/২১ ১:০৯ PM
 */
?>
@extends('layouts.default')

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    @include('gl.gl-balance-query.form')

    <div class="modal fade text-left w-100" id="accountListModal" tabindex="-1" role="dialog"
         aria-labelledby="accountListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title white" id="accountListModalLabel">Account Search</h4>
                    <button type="button" class="close btn btn-sm" data-dismiss="modal"
                            aria-label="Close"><i
                            class="bx bx-x font-size-small"></i></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="acc_search_form">
                        <div class="row">
                            <div class="col-md-4">
                                <div class=" form-group row">
                                    <label for="acc_type" class="col-md-5 col-form-label">Account
                                        Type</label>
                                    <select class="form-control form-control-sm col-md-7"
                                            name="acc_type"
                                            id="acc_type">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($accountType as $type)
                                            <option
                                                value="{{$type->gl_type_id}}">{{$type->gl_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="acc_name_code" id="acc_name_code"
                                       class="form-control form-control-sm"
                                       placeholder="Look for Account Name or Code">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-sm btn-success acc_search"><i
                                        class="bx bx-search font-size-small align-middle"></i><span
                                        class="align-middle ml-25">Search</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark acc_reset"
                                        id="acc_modal_reset"><i
                                        class="bx bx-reset font-size-small align-middle"></i><span
                                        class="align-middle ml-25">Reset</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="card shadow-none">
                        <div class="table-responsive">
                            <table id="account_list" class="table table-sm w-100">
                                <thead>
                                <tr>
                                    {{--<th>SL</th>--}}
                                    <th>Account ID</th>
                                    <th>Account Name</th>
                                    <th>Account Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal"><i
                            class="bx bx-x d-block d-sm-none font-size-small"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $("#searchAccount").on("click", function () {
            $accId = $("#account_id").val();
            if ($accId != "") {
                getAccountDetail($accId);
            } else {
                $("#accountListModal").modal('show');
                accountTable.draw();
            }
        });

        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            accountTable.draw();
        });

        resetAccountField = function () {
            $('#account_balance_type').text('');
            $('#authorized_balance_type').text('');
            resetField(['#module_id', '#account_name', '#account_type','#posting_period', '#account_balance', '#opening_balance',
                '#opening_balance_type', '#debit_summation', '#credit_summation',
                '#unauth_debit_amount', '#unauth_credit_amount', '#authorize_balance', '#authorize_balance_type']);
            $('#show_hide').hide();
        }

        let accountTable = $('#account_list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            /*bDestroy : true,
            pageLength: 20,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],*/
            ajax: {
                url: APP_URL + '/general-ledger/journal-acc-datalist',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    params.glType = $('#acc_type :selected').val();
                    params.accNameCode = $('#acc_name_code').val();
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id", "class": "25"},   // ADD THIS TWO ROW CLASS. PAVEL-11-04-22
                {"data": "gl_acc_name", "class": "w-50"},
                {"data": "gl_acc_code"},
                {"data": "action"}
            ],

            /*language: {
                paginate: {
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">'
                }
            }*/
        });

        getAccountDetail = function (accId) {
            var request = $.ajax({
                url: APP_URL + '/general-ledger/ajax/get-account-details',
                method: 'POST',
                data: {accId: accId},
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            request.done(function (d) {
                resetField(['#account_name', '#account_type','#posting_period', '#account_balance', '#authorized_balance', '#budget_head', '#currency', '#exchange_rate']);

                if ($.isEmptyObject(d.account_info)) {
                    $("#account_id").notify("Account id not found", "error");
                } else {
                    console.log('sssss',d.account_info)
                    $("#account_id").val(d.account_info.gl_acc_id);
                    $("#account_name").val(d.account_info.gl_acc_name);
                    $("#account_type").val(d.account_info.gl_type_name);
                    $("#posting_period").val(d.account_info.regular_posting_period);

                    $("#opening_balance").val(getCommaSeparatedValue(d.account_info.opening_balance));
                    $("#opening_balance_type").val(d.account_info.opening_balance_type);
                    $("#debit_summation").val(getCommaSeparatedValue(d.account_info.debit_summation));
                    $("#credit_summation").val(getCommaSeparatedValue(d.account_info.credit_summation));
                    $("#unauth_debit_amount").val(getCommaSeparatedValue(d.account_info.unauth_debit_amount));
                    $("#unauth_credit_amount").val(getCommaSeparatedValue(d.account_info.unauth_credit_amount));
                    $("#authorize_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                    $("#authorize_balance_type").val(d.account_info.authorize_balance_type);
                    $("#account_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                    $("#account_balance_type").val(d.account_info.account_balance_type);

                    $("#accountListModal").modal('hide');
                    $('#show_hide').show();
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }
    </script>
@endsection


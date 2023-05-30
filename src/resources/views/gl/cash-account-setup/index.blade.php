<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ১০:৫৮ AM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include("gl.cash-account-setup.setup")
        </div>
    </div>
    @include('gl.common_coalist_modal')
    {{--<section id="modal-sizes">
        <div class="row">
            <div class="col-12">
                <!--Modal Xl size -->
                <div class="mr-1 mb-1 d-inline-block">
                    <!-- Button trigger for Extra Large  modal -->
                --}}{{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                    Extra Large Modal
                </button>--}}{{--

                <!--Extra Large Modal -->
                    <div class="modal fade text-left w-100" id="accountListModal" tabindex="-1" role="dialog"
                         aria-labelledby="accountListModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title white" id="accountListModalLabel">Account Search</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="#" id="acc_search_form">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class=" form-group row">
                                                    <label for="acc_type" class="col-md-5 col-form-label required">Account
                                                        Type</label>
                                                    <select class="form-control col-md-7" name="acc_type" id="acc_type"
                                                            required>
                                                        @foreach($coaParams as $type)
                                                            <option value="{{$type->gl_type_id}}">{{$type->gl_type_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="acc_name_code" id="acc_name_code"
                                                       class="form-control" placeholder="Look for Account Name or Code">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success acc_search"><i class="bx bx-search"></i>Search</button>
                                                <button type="button" class="btn btn-dark acc_reset" id="acc_modal_reset"><i class="bx bx-reset"></i>Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="card shadow-none">
                                        <div class="table-responsive">
                                            <table id="account_list" class="table table-sm w-100">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
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
                                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i
                                            class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}

@endsection

@section('footer-script')
    <script type="text/javascript">
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
                //url: APP_URL + '/general-ledger/acc-datalist',
                url: APP_URL + '/general-ledger/ajax/acc-datalist',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (params) {
                    /*params.glType = $('#acc_type :selected').val();
                    params.accNameCode = $('#acc_name_code').val();*/

                    // Retrieve dynamic parameters
                    var dt_params = $('#account_list').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if(dt_params){
                        $.extend(params, dt_params);
                    }
                }
            },
            "columns": [
                /*{"data": 'DT_RowIndex', "name": 'DT_RowIndex'},*/
                {"data": "gl_acc_id", "class":"w-25" },   // ADD THIS TWO ROW CLASS. PAVEL-11-04-22
                {"data": "gl_acc_name", "class":"w-50" },
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
        $("#account_id").on("keyup",function () {
            $("#account_name").val('');
        })
        $("#account_type").on("change",function () {
            $("#account_id").val('');
            $("#account_name").val('');
        })
        function addLineRow(btn) {
            if (fieldsAreSet(['#account_type','#account_id','#account_name'])){
                $("#cash_account_table").show();
                var accountType=[], accountId=[];

                $('#cash_account_table tbody tr').each(function () {
                    if ($(this).is(":visible")){
                        accountType.push($(this).children("td:nth-child(1)").find("input[type='hidden']").val());
                        accountId.push($(this).children("td:nth-child(2)").find("input[type='text']").val());

                    }
                });

                if(jQuery.inArray($('#account_type :selected').val(), accountType) !== -1){
                    $("#account_type").notify("Account type already added","error");
                    return;
                }
                if(jQuery.inArray($('#account_id').val(), accountId) !== -1){
                    $("#account_id").notify("Account id already added","error");
                    return;
                }

                let selector = $("#cash_account_table >tbody");
                let count = selector.children("tr").length;
                /*let lastRow = $('#cash_account_table tbody tr:last').find('input[type="hidden"]').val();
                if (lastRow != undefined){
                    count = parseInt(count) + parseInt(lastRow);
                }*/
                let html = '<tr>\n' +
                    '      <td style="padding: 4px;"><input type="hidden" name="account_line[' + count + '][account_type]" id="line_account_type' + count + '" class="form-control" value="' + $('#account_type :selected').val() + '" readonly/><input readonly type="text"  id="account_code' + count + '" class="form-control" value="' + $('#account_type :selected').text() + '"/></td>\n' +
                    '      <td style="padding: 4px"><input type="text" name="account_line[' + count + '][account_id]" id="line_account_id' + count + '" class="form-control" value="' + $('#account_id').val() + '" readonly/></td></td>\n' +
                    '      <td style="padding: 4px;"><input type="text" class="form-control" name="account_line[' + count + '][account_name]" id="line_account_name' + count + '" value="' + $('#account_name').val() + '" readonly/>' +
                    '      <td style="padding: 4px;"><input type="hidden" value="' + count + '" class="row_count"> <input type="hidden" name="account_line[' + count + '][action_type]" id="action_type' + count + '" value="A">  <span style="text-decoration: underline" onclick="removeLineRow(this,' + count + ')" class="primary cursor-pointer">Remove</span></td>\n' +
                    '  </tr>';
                selector.append(html);

                resetSetupField(['#account_type','#account_id','#account_name']);
            }
        }
       /* function fields_are_set() {
            let val1 = $("#account_type :selected").val();
            let val2 = $("#account_name").val();
            let val3 = $("#account_id").val();
            return !((val1.length == 0) || (val2.length == 0) || (val3.length == 0));
        }*/



        function removeLineRow(select, lineRow) {
            $("#action_type"+lineRow).val("D");
            $(select).closest("tr").hide();
        }

        function resetAll(){
            resetField(['#account_type','#account_id','#account_name']);
            //$("#cash_account_table >tbody >tr").remove();
        }

        $("#searchAccount").on("click", function () {
            let accId = $("#account_id").val();
            let glType = $("#account_type :selected").data('gltype');
            if (!nullEmptyUndefinedChecked(glType)){
                if (!nullEmptyUndefinedChecked(accId)){
                    getAccountDetail(glType,accId);
                }else{
                    $("#accountListModal").modal('show');
                    //let glType = $('#account_type :selected').data('gltype');
                    $("#acc_type").val(glType).addClass("make-readonly-bg");
                    $("#acc_type").prev("label").removeClass('required');

                    $('#account_list').data("dt_params",{
                        glType : glType,
                        accNameCode : $('#acc_name_code').val()
                    }).DataTable().draw();
                    //accountTable.draw();

                    $(".dep-div-sec").addClass('d-none'); // ADD THIS CONDITION. PAVEL-11-04-22
                }
            }else{
                $("#account_type").notify("Select account type first.","error");
            }

        });

        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            $('#account_list').data("dt_params",{
                glType : $('#account_type :selected').data('gltype'),
                accNameCode : $('#acc_name_code').val()
            }).DataTable().draw();
            //accountTable.draw();
        });
        $("#acc_modal_reset").on('click',function () {
            //$("#acc_type").val('');
            $("#acc_name_code").val('');
            $('#account_list').data("dt_params",{
                glType : '',
                accNameCode : ''
            }).DataTable().draw();
        });

        //src = 1 from modal, src = 2 from search
        function getAccountDetail(glType,acc_id) {

            var request = $.ajax({
                url: APP_URL + '/general-ledger/ajax/cash-account-details',
                method: 'POST',
                data: {glTypeId: glType,accountId:acc_id},
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            request.done(function (d) {
                if ($.isEmptyObject(d.coa)) {
                    $("#account_id").notify("Account id not found", "error");
                    $("#account_name").val("");
                }else{
                    $("#account_name").val(d.coa[0].gl_acc_name);
                    $("#account_id").val(d.coa[0].gl_acc_id);
                }
                $("#accountListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        /*function isLineAdded(){
            let visibleLineCounter = 0;
            $('#cash_account_table tbody tr').each(function () {
                if ($(this).is(':visible')){
                    visibleLineCounter++;
                }
            });

            return visibleLineCounter > 0;
        }*/

        $("#cash_account_setup_form").on("submit", function (e) {
            e.preventDefault();
            if (isLineAdded('#cash_account_table')){
                let request = $.ajax({
                    url: APP_URL + "/general-ledger/cash-account-setup",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token()}}'
                    }
                });

                request.done(function (res) {
                    if (res.response_code != "99") {
                        Swal.fire({
                            type: 'success',
                            text: res.response_msg,
                            showConfirmButton: true,
                            //timer: 2000,
                            allowOutsideClick: false
                        }).then(function () {
                            location.reload();
                            //window.location.href = url;
                            //window.history.back();
                        });
                    } else {
                        Swal.fire({text: res.response_msg, type: 'error'});
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(jqXHR);
                });
            }else{
                Swal.fire({
                    type: 'error',
                    text: 'No account added.',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                });
            }

        });

        $(document).ready(function () {

        });
    </script>
@endsection


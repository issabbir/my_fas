<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ১:১২ PM
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
            @include("gl.party-sub-ledger-setup.setup")
        </div>
    </div>

    @include("gl.common_coalist_modal")
@endsection

@section('footer-script')
    <script type="text/javascript">
        let accountTable = $('#account_list').DataTable({
            processing: true,
            serverSide: true,
                searching: true,
            ordering:false,
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
                {"data": "gl_acc_id", "class":"25" },   // ADD THIS TWO ROW CLASS. PAVEL-11-04-22
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

        $("#searchAccount").on("click", function () {
            let accId = $("#account_id").val();
            let glType = $("#party_sub_ledger_type :selected").data('gltype');
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
                $("#party_sub_ledger_type").notify("Select Party-Sub Ledger Type First.","error");
            }
        });

        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            $('#account_list').data("dt_params",{
                glType : $('#party_sub_ledger_type :selected').data('gltype'),
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
                url: APP_URL + '/general-ledger/ajax/coa-details',
                method: 'POST',
                data: {glTypeId: glType,accountId:acc_id},
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            request.done(function (d) {
                if ($.isEmptyObject(d)) {
                    $("#account_id").notify("Account id not found", "error");
                    $("#account_name").val("");
                }else{
                    $("#account_name").val(d.gl_acc_name);
                    $("#account_id").val(d.gl_acc_id);
                }
                $("#accountListModal").modal('hide');
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }

        $("#party_sub_ledger_type").on("change",function () {
            $("#account_id").val('');
            $("#account_name").val('');
        })

        $("#account_id").on("keyup",function () {
            $("#account_name").val('');
        })

        $("#reset_all").on("click", function () {
            $("#party_sub_ledger_name").val('');
            $("#party_sub_ledger_type").val('');
            $("#sub_module_type").val('');
            $("#account_id").val('');
            $("#account_name").val('');
        });

        $(document).ready(function () { });
    </script>
@endsection

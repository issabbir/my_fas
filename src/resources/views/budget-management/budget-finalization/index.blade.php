<?php
/**
 *Created by PhpStorm
 *Created at ২২/১১/২১ ৪:৫০ PM
 */
?>
@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        #progressbar li {
            width: 12.50% !important;
        }

        span.badge.badge-pill {
            font-size: x-small;
        }

        .fixed-height-scrollable {
            max-height: 609px;
            display: block;
            overflow: auto;
        }
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Finalization</span></h4>
            @include('budget-management.budget-finalization.form')
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#search_fiscal_year").on('change', function () {
                /*$('#budgetListTable').data('dt_params', {
                    calendar_id: $("#search_fiscal_year :selected").val()
                }).DataTable().draw();*/
                /*budgetTable.data('dt_params', {
                    calendar_id: $("#search_fiscal_year :selected").val()
                }).DataTable().draw();*/
                budgetTable.draw();
            });
            let budgetTable = $('#budgetListTable').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: '{{route('preparation.budget-datalist')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (params) {
                        // Retrieve dynamic parameters
                        params.calendar_id = $("#search_fiscal_year :selected").val();
                        /*var dt_params = $('#budgetListTable').data('dt_params');
                        // Add dynamic parameters to the data object sent to the server
                        if (dt_params) {
                            $.extend(params, dt_params);
                        }*/
                    }
                },
                "columns": [
                    {data: "fiscal_calendar_name", name: "fiscal_calendar_name"},
                    {data: 'cost_center_dept_name', "name": 'cost_center_dept_name'},
                    {data: 'budget_init_period_name', "name": 'budget_init_period_name'},
                    {data: "budget_init_date", name: "budget_init_date"},
                    {data: "workflow_status_name", name: "workflow_status_name"}
                ],
            });

            $(".finalizationBudget").on('click',function () {
                $("#budget_finalization").submit();
            });

            $("#budget_finalization").on("submit", function (e) {
                e.preventDefault();
                if ($("#budgetListTable tbody tr").length > 0) {
                    swal.fire({
                        text: 'Finalization, Confirm?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value == true) {
                            let request = $.ajax({
                                url: APP_URL + "/budget-management/finalization",
                                data: new FormData(this),
                                processData: false,
                                contentType: false,
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
                                        showConfirmButton: false,
                                        timer: 2000,
                                        allowOutsideClick: false
                                    }).then(function () {
                                        $("#search_fiscal_year").trigger('change');
                                    });
                                } else {
                                    Swal.fire({text: res.response_msg, type: 'error'});
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                console.log(textStatus);
                                /*swal.fire({
                                    text: jqXHR.responseJSON,
                                    type: 'warning',
                                })*/
                            });
                        }
                    })
                } else {
                    Swal.fire({
                        type: 'warning',
                        text: "Budget heads are empty.",
                        showConfirmButton: false,
                        timer: 2000,
                        allowOutsideClick: false
                    })
                }
            });
        });
    </script>
@endsection


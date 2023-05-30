@extends('layouts.default')

@section('title')
@endsection

@section('header-style')

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4><span class="border-bottom-secondary border-bottom-2">Budget Review & Approval</span></h4>
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show mt-2"
                     role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @include('budget-management.review-approval.list')

        </div>
    </div>

@endsection

@section('footer-script')
    <script type="text/javascript">

        function budgetReviewApprovalList() {
            $('#budget-review-approval-list').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: APP_URL + '/budget-management/review-approval-datatable-list',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {data: 'fiscal_calendar_name', name: 'fiscal_calendar_name'},
                    {data: 'cost_center_dept_name', name: 'cost_center_dept_name'},
                    {data: "budget_init_period_name", name: "budget_init_period_name"},
                    {data: 'budget_init_date', name: 'budget_init_date'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'Action', "orderable":false},
                ]
            });
        }

        $(document).ready(function () {
            budgetReviewApprovalList();
        });

    </script>
@endsection


{{--Authorize Step viewer--}}
<section class="" id="authorize_step_sec"></section>
{{--Authorize Step end--}}

<fieldset class="border pl-1 pr-1 mb-1 trans-dtl-sec"  style="display: none">
    <legend class="w-auto text-bold-600" style="font-size: 15px;">Detail View</legend>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end mt-0" id="print_btn"></div>
        <div class="col-md-6" id="mst_details_left_sec"></div>
        <div class="col-md-6" id="mst_details_right_sec"></div>
        <div class="col-md-12" id="mst_details_narration_sec"></div>
        <div class="col-md-12"><hr>
            <div class="table-responsive">
                <table id="transaction-mst-by-dtl-search-list" class="table table-sm datatable mdl-data-table dataTable">
                    <thead class="thead-dark">
                    <tr>
                        {{--<th>SL</th>
                        <th>Batch Id</th>--}}
                        <th>Account Id</th>
                        <th>Account Name</th>
                        <th>Party ID</th>
                        <th>Party Name</th>
                        <th>Budget Head</th>
                        <th class="text-right-align pr-0">Debit</th>
                        <th class="text-right-align pr-0">Credit</th>
                        {{--<th class="text-center">Cheque No</th>
                        <th class="text-center">Cheque Date</th>
                        <th class="text-center">challan no</th>
                        <th class="text-center">challan Date</th>--}}
                        {{--<th>Narration</th>--}}
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot class="thead-dark">
                    <tr>
                        <th colspan="5" class="text-right-align">Total</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div><hr>
        </div>
        <div class="col-md-6">
            <span class="approval-user"></span>
            <span class="approval-comment"></span>
        </div>
    </div>
    <section>
        @include("gl.common_file_download")
    </section>
    <div class="row mt-1 mb-1">
        <div class="col-md-6">
            <span id="approvedButtons"></span>
            <span id="rejectButtons"></span>
        </div>
        {{--TODO: Show cancel button for cancel_reverse_user role--}}
        <div class="col-md-6 d-flex justify-content-end">
            <span id="cancelButtons"></span>
        </div>
    </div>
</fieldset>

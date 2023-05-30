<fieldset class="border p-1 mt-1 mb-1 trans-dtl-sec"  style="display: none">
    <legend class="w-auto text-bold-600" style="font-size: 15px;">Detail View</legend>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end mt-0" id="print_btn"></div>
        <div class="col-md-6" id="mst_details_left_sec"></div>
        <div class="col-md-6" id="mst_details_right_sec"></div>
        <div class="col-md-12" id="mst_details_narration_sec"></div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="reverse-journal-mst-by-dtl-search-list" class="table table-sm datatable mdl-data-table dataTable table-hover">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Batch Id</th>
                        <th>Account Id</th>
                        <th>Account Name</th>
                        <th>Budget Head</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit</th>
                        {{--<th class="text-center">Cheque No</th>
                        <th class="text-center">Cheque Date</th>
                        <th class="text-center">challan no</th>
                        <th class="text-center">challan Date</th>--}}
                        {{--<th>Narration</th>--}}
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <section>
        @include("gl.common_file_download")
    </section>
    <section>
        <div class="d-flex justify-content-end">
            <form id="reverseJournal" method="post" action="{{route('reverse-journal.reverse')}}">
                @csrf
                <input type="hidden" name="trans_master_id" class="transMasterId" value="">
                <input type="hidden" name="trans_period_id" class="transPeriodId" value="">
                <button type="submit" class="reverseJournal btn btn-dark">Reverse Journal</button>
            </form>
        </div>
    </section>
</fieldset>


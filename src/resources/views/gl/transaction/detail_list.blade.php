{{--Authorize Step viewer--}}
<section class="" id="authorize_step_sec"></section>
{{--Authorize Step end--}}

<div class="border p-1 mb-1 mt-1 trans-dtl-sec" style="display: none">
    <div class="form-group row">
        <div class="col-md-5">
            <h5 style="text-decoration: underline; float: left">Detail View</h5>
            <input class="form-check-input ml-1" type="checkbox" value="" id="chnTransRef"
                {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::GL_MODULE_ID, '', '' )) ) ? 'disabled' : '' }} >
            <label class="form-check-label font-small-3 ml-3" for="chnTransRef">Change Trans Reference</label>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end mt-0" id="print_btn"></div>
        </div>
    </div>

    {{--<div class="editDocumentRef d-none"></div>--}}
    <input type="hidden" name="trans_master_id" id="trans_master_id">
    <div class="row">
        <div class="col-md-6 viewDocumentRef" id="mst_details_left_sec"></div>
        <div class="col-md-6 viewDocumentRef" id="mst_details_right_sec"></div>
        <div class="col-md-12 viewDocumentRef" id="mst_details_narration_sec"></div>

        <div class="col-md-12">
            <div class="table-responsive">
                <table id="transaction-mst-by-dtl-search-list"
                       class="table table-sm datatable mdl-data-table dataTable table-hover">
                    <thead class="thead-dark">
                    <tr>
                        {{--<th>SL</th>--}}
                        {{--<th>Batch Id</th>--}}
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
            </div>
        </div>
    </div>
    <section>
        @include("gl.common_file_download")
    </section>
</div>

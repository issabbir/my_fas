<fieldset class="border p-1 mt-3 mb-1 cheque-leaf-sec"  style="display: none">
    <legend class="w-auto text-bold-600" style="font-size: 15px;">Cheque Leaf List</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="v_bank_acc_id" class="">Bank Account</label>
                <input class="form-control" id="v_bank_acc_id" name="v_bank_acc_id" disabled="" >
                <div class="text-muted form-text"></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="v_cheque_prefix" class="">Cheque Prefix</label>
                <input class="form-control" id="v_cheque_prefix" name="v_cheque_prefix" disabled="" >
                <div class="text-muted form-text"></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="v_beginning_number" class="">Beginning Number</label>
                <input class="form-control" id="v_beginning_number" name="v_beginning_number" disabled="">
                <div class="text-muted form-text"></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="v_ending_number" class="">Ending Number</label>
                <input class="form-control" id="v_ending_number" name="v_ending_number" disabled="" >
                <div class="text-muted form-text"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="acc-wise-cheque-leaf-search-list" class="table table-sm datatable mdl-data-table dataTable table-hover">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Cheque Leaf No</th>
                        <th>Used/Unused</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>

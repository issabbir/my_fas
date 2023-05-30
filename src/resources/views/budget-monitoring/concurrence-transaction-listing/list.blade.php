<fieldset class="border p-1 mb-1">
    <legend class="w-auto text-bold-600" style="font-size: 15px;">Master List</legend>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="fiscal_year_id" class="col-form-label">Financial Year</label>
            <select class=" form-control form-control-sm select2" id="fiscal_year_id"
                    name="fiscal_year_id" required>
                @foreach($CurrentFinancialYearList as $value)
                    <option
                        {{isset($filterData) ? (($value->fiscal_year_id == $filterData[0]) ? 'selected' : '') : ''}}
                        value="{{$value->fiscal_year_id}}">{{ $value->fiscal_year_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="bill_sec_id" class="col-form-label">Bill Section</label>
            <select name="bill_sec_id" class="form-control form-control-sm select2 "
                    id="bill_sec_id">
                <option value="">&lt;Select&gt;</option>
                @foreach($lBillSecList as $value)
                    <option value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">

            <label for="auth_function_type" class="col-form-label col-md-5">Function Type</label>
            <select name="function_type" class="form-control form-control-sm select2 search-param"
                    id="function_type">
                @foreach(\App\Enums\ApprovalStatus::AUTHORIZE_FUN_TYPE as $key=>$value)
                    <option
                        value="{{$key}}" {{isset($filterData) ? (($key == $filterData[1]) ? 'selected' : '') : (($key == \App\Enums\ApprovalStatus::MAKE) ? 'selected' : '')}}
                        {{ ($key == \App\Enums\ApprovalStatus::MAKE) ? 'selected' : ''}} >
                        {{ $value}}</option>
                @endforeach
            </select>
        </div>

            <div class="col-md-3">

                <label for="approval_status" class="col-form-label col-md-5">Approval Status</label>

                <select class="form-control form-control-sm" name="approval_status" id="approval_status">
                    @foreach(\App\Enums\ApprovalStatus::APPROVAL_STATUS as $key=>$value)
                        <option
                            value="{{$key}}"> {{ $value}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="bud-concurrence-tran-mst-search-list"
                           class="table table-sm table-striped table-bordered table-hover ">
                        <thead class="thead-dark">
                        <tr>
                            <th>Document Date</th>
                            <th>Document No</th>
                            <th>Department</th>
                            <th>Budget Head</th>
                            <th>Tender Type</th>
                            <th>Est. Amount</th>
                            <th>Booking Amount</th>
                            <th>Approval Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
</fieldset>

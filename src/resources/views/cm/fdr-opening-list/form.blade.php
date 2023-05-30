<div class="card">
    <div class="card-header pb-0">
        <div class="row">
            <div class="col-md-4">
                <h4 class="card-title mb-0" style="text-decoration: underline">FDR INFORMATION SEARCH</h4>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <label for="investment_type" class="col-md-3 col-form-label">Investment Type</label>
                    <div class="make-select2-readonly-bg col-md-5">
                        <select class="custom-select form-control form-control-sm select2" name="investment_type"
                                readonly="" {{isset($mode) ? (($mode[1] == 'v') ? 'readonly': '') : ''}}
                                required id="investment_type">
                            @foreach($investmentTypes as $type)
                                <option
                                    {{old('investment_type',isset($investmentInfo) ? (($investmentInfo->investment_type_id == $type->investment_type_id) ? 'selected' : '') : '')}} value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <fieldset class="border p-2">
            <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="th_fiscal_year" class="required col-form-label">Fiscal Year</label>
                    <select required name="th_fiscal_year"
                            class="form-control form-control-sm required"
                            id="th_fiscal_year">
                        @foreach($fiscalYear as $year)
                            <option
                                {{--{{($year->default_year_flag == '1') ? 'selected' : ''}}--}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="period" class="col-form-label">Posting Period</label>
                    <select required name="period" class="form-control form-control-sm" id="period">
                    </select>
                </div>
                <div class="col-md-3 form-group ">
                    <label for="s_bank_id" class="col-form-label">Bank</label>
                    <select class="custom-select form-control form-control-sm select2" name="s_bank_id"
                            id="s_bank_id">
                        <option value="">&lt;Select&gt;</option>
                    </select>
                </div>
                <div class="col-md-4 form-group ">
                    <label for="s_branch_id" class="col-form-label">Branch</label>
                    <select class="custom-select form-control form-control-sm select2" name="s_branch_id"
                            id="s_branch_id">
                        <option value="">&lt;Select&gt;</option>
                    </select>
                </div>
                {{--<div class="col-md-2 form-group ">
                    <label for="s_approval_status" class="col-form-label">Approval Status</label>
                    <select class="custom-select form-control form-control-sm select2" name="s_approval_status"
                            id="s_approval_status">
                        <option value="">&lt;Select&gt;</option>
                        <option value="P">Pending</option>
                        <option value="A">Approved</option>
                    </select>
                </div>--}}
                <div class="col-md-1">
                    <button class="btn btn-sm btn-primary" id="investment_search" style="margin-top: 33px;">Search
                    </button>
                </div>
            </div>
        </fieldset>
        <div class="mt-2">
            <div class="table-responsive">
                <table id="investment-list" class="table table-sm table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th width="15%">Investment ID</th>
                        <th width="15%">Investment Date</th>
                        <th width="15%">FDR No</th>
                        <th width="15%" class="text-right">Amount</th>
                        <th width="15%">Interest Rate</th>
                        <th width="15%">Expiry Date</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

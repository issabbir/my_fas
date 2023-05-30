<section id="modal-sizes">
    <div class="row">
        <div class="col-12">
            <!--Modal Xl size -->
            <div class="mr-1 mb-1 d-inline-block">
                <!-- Button trigger for Extra Large  modal -->
            {{--<button type="button" class="btn btn-outline-warning show-btn" data-toggle="modal" data-target="#xlarge" style="display: none">
                Extra Large Modal
            </button>--}}

            <!--Extra Large Modal -->
                <div class="modal fade text-left w-100" id="investmentListModal"  role="dialog"
                     aria-labelledby="investmentListModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title white" id="investmentListModalLabel">FDR Information Search</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                                        class="bx bx-x font-size-small"></i></button>
                            </div>
                            <div class="modal-body">
                                <form action="#" id="acc_search_form">
                                    <fieldset class="border pl-2 pr-2">
                                        <legend class="w-auto text-bold-600" style="font-size: 15px;">Search Criteria</legend>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="s_investment_type" class="col-form-label">Investment Type</label>
                                                <select class="form-control form-control-sm make-readonly-bg" name="s_investment_type"
                                                        id="s_investment_type">
                                                    <option value="">&lt;Select&gt;</option>
                                                    @foreach($investmentTypes as $type)
                                                        <option
                                                            {{old('investment_type', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $type->investment_type_id ? 'selected' : ''}}
                                                            value="{{$type->investment_type_id}}">{{$type->investment_type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="s_fiscal_year" class="col-form-label">Fiscal Year</label>
                                                <select required name="s_fiscal_year"
                                                        class="form-control form-control-sm make-readonly-bg"
                                                        id="s_fiscal_year">
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="s_period" class="col-form-label">Posting Period</label>
                                                <select required name="s_period" class="form-control form-control-sm make-readonly-bg" id="s_period">
                                                </select>
                                            </div>

                                            <div class="col-md-3 form-group ">
                                                <label for="s_bank_id" class="col-form-label">Bank</label>
                                                <select class="custom-select form-control form-control-sm select2" name="s_bank_id"
                                                        id="s_bank_id">
                                                    <option value="">&lt;Select&gt;</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group ">
                                                <label for="s_branch_id" class="col-form-label">Branch</label>
                                                <select class="custom-select form-control form-control-sm select2" name="s_branch_id"
                                                        id="s_branch_id">
                                                    <option value="">&lt;Select&gt;</option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="mt-2">
                                        <div class="table-responsive">
                                            <table id="fdr_list" class="table table-sm table-striped table-hover">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th width="15%">Investment ID</th>
                                                    <th width="15%">Investment Date</th>
                                                    <th width="15%">FDR No</th>
                                                    <th width="15%" class="text-right">Amount</th>
                                                    <th width="15%" class="text-right">Interest Rate</th>
                                                    <th width="15%">Maturity Date</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal"><i
                                        class="bx bx-x d-block d-sm-none font-size-small"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

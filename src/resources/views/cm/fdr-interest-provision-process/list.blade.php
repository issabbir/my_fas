<div class="card">
    <div class="card-body">
        <h5> <span class="border-bottom-secondary border-bottom-2">Interest Provision History</span></h5>
        <div class="form-group row mt-2">
            <label for="s_inv_type_id" class="required col-md-2 col-form-label">Investment Type</label>
            <select required name="s_inv_type_id" class="form-control form-control-sm col-md-3 make-readonly-bg search-param" id="s_inv_type_id">
                <option value="" >&lt;Select&gt;</option>
                @foreach($invTypeList as $value)
                    <option value="{{$value->investment_type_id}}"
                        {{old('inv_type_id', \App\Helpers\HelperClass::getUserCurrentInvestType(\Illuminate\Support\Facades\Auth::id())) == $value->investment_type_id ? 'selected' : ''}}>
                        {{$value->investment_type_name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="interest-prov-hist-search-list" class="table table-sm table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th>Fiscal Year</th>
                            <th>Investment Type</th>
                            <th>Auth Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

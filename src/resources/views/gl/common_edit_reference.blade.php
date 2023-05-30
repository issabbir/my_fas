@extends("layouts.default")

@section('title')
@endsection

@section('header-style')
    <style type="text/css" rel="stylesheet">
        .form-group {
            margin-bottom: 5px;
        }

        .text-right-align {
            text-align: right;
        }


        .select2-container--classic .select2-selection--single, .select2-container--default .select2-selection--single {
            min-height: calc(1.1em + .94rem + 3.7px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 22px;
        }
    </style>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="referenceForm" action="#" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="trans_master_id" id="trans_master_id" val
                       value="{{$glTransMstInfo->trans_master_id}}">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h5 style="text-decoration: underline; float: left">Receipt Voucher</h5>
                        {{--<input class="form-check-input ml-1" type="checkbox" value="" id="chnTransRef"
                            {{ ( empty(\App\Helpers\HelperClass::findRolePermission(\App\Enums\ModuleInfo::AP_MODULE_ID, \App\Enums\WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE,\App\Enums\RolePermissionsKey::CAN_EDIT_AP_INVOICE_MAKE )) ) ? 'disabled' : '' }} >
                        <label class="form-check-label font-small-3 ml-3" for="chnTransRef">
                            Change Trans Reference
                        </label>--}}
                    </div>
                </div>
                <div class="viewDocumentRef d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row ">
                                <label for="function_type" class="required col-md-4 col-form-label">Function Type</label>

                                <select required name="function_type" class="form-control form-control-sm col-md-6 make-readonly-bg"
                                        id="function_type">
                                    @foreach($funcType as $type)
                                        <option
                                            {{  ($glTransMstInfo->function_id ==  $type->function_id) ? "selected" : "" }} value="{{$type->function_id}}">{{ $type->function_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="th_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                                <select required name="th_fiscal_year"
                                        class="form-control form-control-sm col-md-4 required make-readonly-bg"
                                        id="th_fiscal_year">
                                    @foreach($fiscalYear as $year)
                                        <option {{  old('th_fiscal_year',$glTransMstInfo->function_id) ==  $type->function_id ? "selected" : "" }}
                                                value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="period" class="required col-md-4 col-form-label">Posting Period</label>
                                <select required name="period" class="form-control form-control-sm col-md-4 make-readonly-bg" id="period">
                                    <option value="{{$glTransMstInfo->trans_period_id}}">{{$glTransMstInfo->trans_period_name}}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="posting_date_field" class="required col-md-4 col-form-label ">Posting
                                    Date</label>
                                <div class="input-group date posting_date col-md-4 pl-0 pr-0 make-readonly-bg"
                                     id="posting_date"
                                     data-target-input="nearest">
                                    <input required type="text" autocomplete="off" onkeydown="return false"
                                           name="posting_date" tabindex="-1" readonly
                                           id="posting_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#posting_date"
                                           data-toggle="datetimepicker"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_date) }}"
                                           data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_date) }}"
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append posting_date" data-target="#posting_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="document_date_field" class="col-md-4 col-form-label">Document Date</label>
                                <div class="input-group date document_date col-md-4 pl-0 pr-0 make-readonly-bg"
                                     id="document_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="document_date" tabindex="-1" readonly
                                           id="document_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           data-target="#document_date"
                                           data-toggle="datetimepicker"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->document_date) }}"
                                           data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->document_date) }}"
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append document_date" data-target="#document_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row d-flex justify-content-end">
                                <label for="department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                                <div class="col-md-5">
                                    <select required name="department" class="form-control form-control-sm  make-readonly-bg"
                                            id="department">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($department as $dpt)
                                            <option
                                                {{  $glTransMstInfo->department_id ==  $dpt->cost_center_dept_id ? "selected" : "" }} value="{{$dpt->cost_center_dept_id}}">{{ $dpt->cost_center_dept_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                                <div class="col-md-5">
                                    <select required name="bill_section" class="form-control form-control-sm make-readonly-bg"
                                            id="bill_section">
                                            <option
                                                value="{{$glTransMstInfo->bill_sec_id}}">{{ $glTransMstInfo->bill_sec_name}}
                                            </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                                <div class="col-md-5">
                                    <select required name="bill_register" class="form-control form-control-sm make-readonly-bg"
                                            id="v_bill_register">
                                        <option
                                           value="{{$glTransMstInfo->bill_reg_id}}">{{ $glTransMstInfo->bill_reg_name}}
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="document_number" class="col-md-2 col-form-label">Document Number</label>
                        <input maxlength="50" type="text" class="form-control form-control-sm col-md-3 make-readonly-bg"
                               name="document_number" oninput="this.value = this.value.toUpperCase()"
                               id="document_number"
                               value="{{$glTransMstInfo->document_no}}">

                        <label for="document_reference" class="col-md-2 col-form-label ml-5">Document Reference</label>
                        <input maxlength="200" type="text" class="form-control form-control-sm col-md-4 ml-1 make-readonly-bg"
                               id="document_reference"
                               name="document_reference"
                               value="{{$glTransMstInfo->document_ref}}">
                    </div>
                    <div class="form-group row pr-1">
                        <label for="narration" class="required col-md-2 col-form-label">Narration</label>
                        <textarea maxlength="500" required name="narration"
                                  class="required form-control form-control-sm col-md-10 make-readonly-bg"
                                  id="narration">{{$glTransMstInfo->narration}}</textarea>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12 d-flex">
                            <button type="submit" disabled class="btn btn-sm btn-success mr-1" id="receiveFormSubmitBtn"><i
                                    class="bx bxs-save font-size-small"></i><span class="align-middle ml-25">Update</span>
                            </button>
                            <a href="{{route('transaction.index')}}" type="button" id="reset_form" class="btn btn-sm btn-dark"><i
                                    class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Back</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="editDocumentRef">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="edt_function_type" class="required col-md-4 col-form-label">Function Type</label>

                                <select required name="edt_function_type" class="form-control form-control-sm col-md-6"
                                        id="edt_function_type">
                                    @foreach($funcType as $type)
                                        <option
                                            {{  $glTransMstInfo->function_id ==  $type->function_id ? "selected" : "" }} value="{{$type->function_id}}">{{ $type->function_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="edt_fiscal_year" class="required col-sm-4 col-form-label">Fiscal Year</label>
                                <select required name="edt_fiscal_year"
                                        class="form-control form-control-sm col-md-4 required"
                                        id="edt_fiscal_year">
                                    @foreach($fiscalYear as $year)
                                        <option {{$glTransMstInfo->fiscal_year_id == $year->fiscal_year_id ? 'selected':''}} value="{{$year->fiscal_year_id}}">{{$year->fiscal_year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="edt_period" class="required col-md-4 col-form-label">Posting Period</label>
                                <select required name="edt_period" class="form-control form-control-sm col-md-4" id="edt_period">
                                    <option
                                        data-mindate="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_period_beg_date) }}"
                                        data-maxdate="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_period_end_date) }}"
                                        value="{{ $glTransMstInfo->trans_period_id}}">{{ $glTransMstInfo->trans_period_name }}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="edt_posting_date_field" class="required col-md-4 col-form-label ">Posting
                                    Date</label>
                                <div class="input-group date posting_date col-md-4 pl-0 pr-0"
                                     id="edt_posting_date"
                                     data-target-input="nearest">
                                    <input required type="text" autocomplete="off" onkeydown="return false"
                                           name="edt_posting_date" tabindex="-1"
                                           id="edt_posting_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_date) }}"
                                           data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->trans_date) }}"
                                           data-target="#edt_posting_date"
                                           data-toggle="datetimepicker"
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append edt_posting_date" data-target="#edt_posting_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edt_document_date_field" class="col-md-4 col-form-label">Document Date</label>
                                <div class="input-group date edt_document_date col-md-4 pl-0 pr-0"
                                     id="edt_document_date"
                                     data-target-input="nearest">
                                    <input type="text" autocomplete="off" onkeydown="return false"
                                           name="edt_document_date" tabindex="-1"
                                           id="edt_document_date_field"
                                           class="form-control form-control-sm datetimepicker-input"
                                           value="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->document_date) }}"
                                           data-predefined-date="{{ \App\Helpers\HelperClass::dateConvert($glTransMstInfo->document_date) }}"
                                           data-target="#edt_document_date"
                                           data-toggle="datetimepicker"
                                           placeholder="DD-MM-YYYY">
                                    <div class="input-group-append edt_document_date" data-target="#edt_document_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="bx bxs-calendar font-size-small"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row d-flex justify-content-end">
                                <label for="edt_department" class="col-form-label col-md-4 required">Dept/Cost Center</label>
                                <div class="col-md-5">
                                    <select required name="edt_department" class="form-control form-control-sm select2"
                                            id="edt_department">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($department as $dpt)
                                            <option
                                                value="{{$dpt->cost_center_dept_id}}">{{ $dpt->cost_center_dept_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="edt_bill_section" class="required col-md-4 col-form-label">Bill Section</label>
                                <div class="col-md-5">
                                    <select required name="edt_bill_section" class="form-control form-control-sm select2"
                                            id="edt_bill_section">
                                        <option value="">&lt;Select&gt;</option>
                                        @foreach($billSecs as $value)
                                            <option
                                                {{  $glTransMstInfo->bill_sec_id ==  $value->bill_sec_id ? "selected" : "" }} value="{{$value->bill_sec_id}}">{{ $value->bill_sec_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-flex justify-content-end">
                                <label for="edt_bill_register" class="required col-md-4 col-form-label">Bill Register</label>
                                <div class="col-md-5">
                                    <select required name="edt_bill_register"
                                            data-bill-register-id="{{$glTransMstInfo->bill_reg_id}}"
                                            class="form-control form-control-sm select2"
                                            id="edt_bill_register">
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="edt_document_number" class="col-md-2 col-form-label">Document Number</label>
                        <input maxlength="50" type="text" class="form-control form-control-sm col-md-3"
                               name="edt_document_number" oninput="this.value = this.value.toUpperCase()"
                               id="edt_document_number"
                               value="{{$glTransMstInfo->document_no}}">

                        <label for="edt_document_reference" class="col-md-2 col-form-label ml-5">Document Reference</label>
                        <input maxlength="200" type="text" class="form-control form-control-sm col-md-4 ml-1"
                               id="edt_document_reference"
                               name="edt_document_reference"
                               value="{{$glTransMstInfo->document_ref}}">
                    </div>
                    <div class="form-group row pr-1">
                        <label for="edt_narration" class="required col-md-2 col-form-label">Narration</label>
                        <textarea maxlength="500" required name="edt_narration"
                                  class="required form-control form-control-sm col-md-10 "
                                  id="edt_narration">{{$glTransMstInfo->narration}}</textarea>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12 d-flex">
                            <button type="button" class="btn btn-sm btn-success mr-1" id="updateReference"><i
                                    class="bx bxs-save font-size-small"></i><span class="align-middle ml-25">Update</span>
                            </button>
                            <a href="{{route('transaction.index')}}" type="button" id="reset_form" class="btn btn-sm btn-dark"><i
                                    class="bx bx-reset font-size-small"></i><span class="align-middle ml-25">Back</span>
                            </a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        var resetCreditAccountField;
        var resetDebitAccountField;
        var addLineRow;
        var removeLineRow;
        var editAccount;
        var getAccountDetail;
        var enableDisableSaveBtn;
        var resetPayableReceivableFields;

        $(document).ready(function () {
            $('#edt_function_type').trigger('change')
            $("#edt_bill_section").select2().val('{{$glTransMstInfo->bill_sec_id}}').css('width:', '100%');
            $("#edt_bill_section").select2().trigger('change');
            selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');
            $("#edt_department").select2().val('{{$glTransMstInfo->department_id}}').trigger('change');

            /**** when change checkbox inside this page**/
            /*$("#chnTransRef").on('change', function () {
                if ($(this).is(":checked")) {
                    $(".viewDocumentRef").addClass('d-none');
                    $(".editDocumentRef").removeClass('d-none');

                    //$("#edt_bill_register").select2().val('{{isset($inserted_data->bill_reg_id) ? $inserted_data->bill_reg_id : ''}}').trigger('change');
                    //$("#edt_bill_section").html('<option value="'+$("#edt_bill_register :selected").data('secid')+'">'+$("#edt_bill_register :selected").data('secname')+'</option>');
                    $("#edt_bill_section").select2().val('{{$glTransMstInfo->bill_sec_id}}').css('width:', '100%');
                    $("#edt_bill_section").select2().trigger('change');
                    selectBillRegister('#edt_bill_register', APP_URL + '/account-payable/ajax/bill-section-by-register/' + $('#edt_bill_section :selected').val(), APP_URL + '/account-payable/ajax/get-bill-register-detail/', '');

                    $("#edt_department").select2().val('{{$glTransMstInfo->department_id}}').trigger('change');

                    $("#updateReference").removeClass('d-none');

                } else {
                    $(".viewDocumentRef").removeClass('d-none');
                    $(".editDocumentRef").addClass('d-none');
                    $("#updateReference").addClass('d-none');
                }
            });*/
            /**** when change checkbox inside this page**/

            let documentCalendarClickCounter = 0;
            let postingCalendarClickCounter = 0;
            let chalanCalendarClickCounter = 0;
            $("#edt_period").on('change', function () {
                $("#edt_document_date >input").val("");
                if (documentCalendarClickCounter > 0) {
                    $("#edt_document_date").datetimepicker('destroy');
                    documentCalendarClickCounter = 0;
                }

                $("#edt_posting_date >input").val("");
                if (postingCalendarClickCounter > 0) {
                    $("#edt_posting_date").datetimepicker('destroy');
                    postingCalendarClickCounter = 0;
                    postingDateClickCounter = 0;
                }

                $("#d_chalan_date >input").val("");
                if (chalanCalendarClickCounter > 0) {
                    $("#d_chalan_date").datetimepicker('destroy');
                    chalanCalendarClickCounter = 0;
                }

                setPeriodCurrentDate();
            });

            /********Added on: 06/06/2022, sujon**********/
            function setPeriodCurrentDate() {
                $("#edt_posting_date_field").val($("#edt_period :selected").data("currentdate"));
                $("#edt_document_date_field").val($("#edt_period :selected").data("currentdate"));
            }

            //setPeriodCurrentDate()
            /********End**********/

            $("#edt_document_date").on('click', function () {
                documentCalendarClickCounter++;
                $("#edt_document_date >input").val("");
                let minDate = false;
                let maxDate = $("#edt_period :selected").data("maxdate");
                let currentDate = $("#edt_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            $("#edt_posting_date").on('click', function () {
                postingCalendarClickCounter++;
                $("#edt_posting_date >input").val("");
                let minDate = $("#edt_period :selected").data("mindate");
                let maxDate = $("#edt_period :selected").data("maxdate");
                let currentDate = $("#edt_period :selected").data("currentdate");
                datePickerOnPeriod(this, minDate, maxDate, currentDate);
            });

            let postingDateClickCounter = 0;
            $("#edt_posting_date").on("change.datetimepicker", function () {
                let newDueDate;
                let postingDate = $("#edt_posting_date_field").val();

                if (!nullEmptyUndefinedChecked(postingDate)) {
                    if (postingDateClickCounter == 0) {
                        newDueDate = moment(postingDate, "YYYY-MM-DD"); //First time YYYY-MM-DD
                    } else {
                        newDueDate = moment(postingDate, "DD-MM-YYYY"); //First time DD-MM-YYYY
                    }
                    $("#edt_document_date >input").val(newDueDate.format("DD-MM-YYYY"));
                }
                postingDateClickCounter++;
            });


            function listBillRegister() {
                $('#edt_bill_section').change(function (e) {
                    $("#edt_bill_register").val("");
                    let billSectionId = $(this).val();
                    selectBillRegister('#edt_bill_register', APP_URL + '/general-ledger/ajax/bill-section-by-register/' + billSectionId, '', '');
                });
            }
            listBillRegister();


            $('#edt_function_type').change(function (e) {
                let funTypeId = $(this).val();

                $("#edt_bill_section").html("");
                $("#edt_bill_register").select2("destroy");
                $("#edt_bill_register").html("");
                $("#edt_bill_register").select2();
                getBillSectionOnFunction(funTypeId, "#edt_bill_section");
            });

            $("#edt_fiscal_year").on('change', function () {
                getPostingPeriod($("#edt_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
            });
            //getPostingPeriod($("#th_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);

            function setPostingPeriod(periods) {
                $("#edt_period").html(periods);
                //setPeriodCurrentDate();
                $("#edt_period").trigger('change');
            }

            $("#referenceForm").on('submit',function (e) {
                e.preventDefault();

                let transMasterId = $("#trans_master_id").val();
                let transPeriod = $("#edt_period :selected").val();
                let transDate = $("#edt_posting_date_field").val();
                let documentDate = $("#edt_document_date_field").val();
                let documentNumber = $("#edt_document_number").val();
                let documentRef = $("#edt_document_reference").val();
                let documentNarration = $("#edt_narration").val();
                let department = $("#edt_department :selected").val();
                let billSection = $("#edt_bill_section :selected").val();
                let billRegister = $("#edt_bill_register :selected").val();

                let request = $.ajax({
                    url: '{{route('transaction.update')}}',
                    data: {
                        transMasterId, transPeriod, transDate, documentDate, documentNumber, documentRef,
                        documentNarration, department, billSection, billRegister
                    },
                    dataType: "JSON",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token()}}'
                    }
                });

                request.done(function (res) {
                    if (res.response_code == "1") {
                        Swal.fire({
                            type: 'success',
                            text: res.response_message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false
                        }).then(function () {
                            let urlStr = '{{ route('transaction.edit',['id'=>'_p']) }}';
                            window.location.href = urlStr.replace('_p', transMasterId);
                        });
                    } else {
                        Swal.fire({text: res.response_message, type: 'error'});
                    }
                });

                request.fail(function (jqXHR, textStatus) {
                    Swal.fire({text: textStatus + jqXHR, type: 'error'});
                    //console.log(jqXHR, textStatus);
                });
            })

            $("#updateReference").on('click', () => {
                swal.fire({
                    title: 'Are you sure?',
                    type: 'info',
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok",
                    confirmButtonClass: "btn btn-primary",
                    cancelButtonClass: "btn btn-danger ml-1",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {
                        $("#referenceForm").submit();
                    }
                });
            });

            function defaultPeriods() {
                let defaultPeriod = $("#edt_period :selected").val();
                let defaultPostingDate = $("#edt_posting_date_field").val();
                let defaultDocumentDate = $("#edt_document_date_field").val();
                getPostingPeriod($("#edt_fiscal_year :selected").val(), '{{route("ajax.get-current-posting-period")}}', setPostingPeriod);
                function setPostingPeriod(periods) {    //Over writing setPostingPeriod
                    $("#edt_period").html(periods);
                    $("#edt_period").val(defaultPeriod).trigger('change');

                    $("#edt_posting_date_field").val(defaultPostingDate);
                    $("#edt_document_date_field").val(defaultDocumentDate);
                }
            }
            defaultPeriods();
        });
    </script>
@endsection

@php
    $debit = 0;
    $credit = 0;
@endphp
<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless table p-0">
                <tr>
                    <td width="24%%">Posting Date</td>
                    <td width="3%">:</td>
                    <td>{{$data['posting_date']}}</td>
                </tr>
                <tr>
                    <td>Document Date</td>
                    <td>:</td>
                    <td>{{$data['document_date']}}</td>
                </tr>
                <tr>
                    <td>Document No</td>
                    <td>:</td>
                    <td>{{$data['document_no']}}</td>
                </tr>
                <tr>
                    <td>Document Ref</td>
                    <td>:</td>
                    <td>{{$data['document_reference']}}</td>
                </tr>

            </table>
           {{-- <ul style="list-style: none" class="ml-0 pl-0 mb-0">
               <li>Posting Date : {{$data['posting_date']}}</li>
               <li>Document Date: {{$data['document_date']}}</li>
               <li>Document No : {{$data['document_no']}}</li>
            </ul>--}}
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless table p-0">
                <tr>
                    <td width="28%%">Dept/Cost Center</td>
                    <td width="3%">:</td>
                    <td>{{$data['department']}}</td>
                </tr>
                <tr>
                    <td>Bill Register</td>
                    <td>:</td>
                    <td>{{$data['register']}}</td>
                </tr>
                <tr>
                    <td>Bill Section</td>
                    <td>:</td>
                    <td>{{$data['section']}}</td>
                </tr>
                <tr>
                    <td>Employee Type</td>
                    <td>:</td>
                    <td>{{$data['lFasEmployeeType']}}</td>
                </tr>
            </table>
            {{--<ul style="list-style: none" class="ml-0 pl-0 mb-0">
                <li>Dept/Cost Center : {{$data['department']}}</li>
                <li>Bill Register : {{$data['register']}}</li>
                <li>Bill Section : {{$data['section']}}</li>
            </ul>--}}
        </div>
    </div>
</div>
<div class="col-md-12">
    <table style="width: 100% !important;">
        <tr>
            <td width="10.5%">Party ID</td>
            <td width="1%">:</td>
            <td width="75">{{$data['party_id']}}</td>
        </tr>
        <tr>
            <td width="10.5%">Party Name</td>
            <td width="1%">:</td>
            <td width="75%">{{$data['party_name']}}</td>
        </tr>
        <tr>
            <td width="10.5%">Narration</td>
            <td width="1%">:</td>
            <td width="75%">{{$data['narration']}}</td>
        </tr>
    </table>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Account ID</th>
                    <th>Account Name</th>
                    <th>Debit</th>
                    <th>Credit</th>

                </tr>
                </thead>
                <tbody>
                {{--Distribution always Debit side--}}
                @if(isset($indv_allowance_amount))
                    @foreach($indv_allowance_amount as $line)
                        @php
                            $debit += $line["pay-allowance-amt"];
                        @endphp
                        <tr>
                            <td>{{ $line["gl_acc_id"] }}</td>
                            <td>{{ $line["gl_acc_name"] }}</td>
                            <td class="text-right-align">{{ $line["pay-allowance-amt"] }}</td>
                            <td class="text-right-align"></td>  {{--Credit--}}
                        </tr>
                    @endforeach
                @endif

                {{--Additonal always Credit side--}}
                @if(isset($invoice_deduction_amount))
                    @foreach($invoice_deduction_amount as $line)
                        @php
                            $credit += $line["pay_amt_deduction"];
                        @endphp
                        <tr>
                            <td>{{ $line["gl_acc_id"] }}</td>
                            <td>{{ $line["gl_acc_name"] }}</td>
                            <td class="text-right-align"></td> {{--Debit--}}
                            <td class="text-right-align">{{ $line["pay_amt_deduction"] }}</td>
                        </tr>
                    @endforeach
                @endif

                {{--Coming from function call--}}
                @foreach($responses as $res)
                    @php
                        $debit += $res->{"debit"};
                        $credit += $res->{"credit"};
                    @endphp
                    <tr>
                        <td>{{ $res->{"account id"} }}</td>
                        <td>{{ $res->{"account name"} }}</td>
                        <td class="text-right-align">{{ \App\Helpers\HelperClass::getCommaSeparatedValue($res->{"debit"} )}}</td>
                        <td class="text-right-align">{{ \App\Helpers\HelperClass::getCommaSeparatedValue($res->{"credit"} )}}</td>
                    </tr>
                @endforeach


                </tbody>
                <tfoot class="bg-dark text-white">
                <tr>
                    <td colspan="2" class="text-right-align">Total</td>
                    <td class="text-right-align">{{\App\Helpers\HelperClass::getCommaSeparatedValue($debit)}}</td>
                    <td class="text-right-align">{{\App\Helpers\HelperClass::getCommaSeparatedValue($credit)}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">
    <ul style="list-style: none" class="ml-0 pl-0">
        @php
        $amountWord = \Illuminate\Support\Facades\DB::selectOne('select pmis.F_WORDS(:p_amount) as amount from dual',['p_amount'=>$debit]);
        @endphp
        <li>Inward : {{$amountWord->amount}}</li>
    </ul>
</div>

@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <a href="{{route('payroll')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger rounded btn-sm" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
        <div>  
            <a href="#" onclick="verifyPayroll(this.href);return false;" class="btn btn-inverse-success btn-sm mr-2 rounded verifyBtn" data-toggle="tooltip" data-placement="bottom" title="Click to verify" style="font-weight: bold"><i class="mdi mdi-check" style="vertical-align: middle;"></i> <span>Verify</span></a>
                <!-- @if(date("Y-m")<=date("Y-m",strtotime($month)))
                    @if($verified)
                    <a href="#" onclick="verifyPayroll(this.href);return false;" class="btn btn-inverse-secondary btn-sm mr-2 rounded verifyBtn" data-toggle="tooltip" data-placement="bottom" title="Click to verify" style="font-weight: bold"><i class="mdi mdi-check" style="vertical-align: middle;"></i> <span>Verified</span></a>
                    @else -->
                    <!-- @endif
                @else
                    <a class="btn btn-inverse-success btn-sm mr-2 rounded verifyBtn disabled" data-toggle="tooltip" data-placement="bottom" title="Click to verify" style="font-weight: bold"><i class="mdi mdi-check" style="vertical-align: middle;"></i> <span>Verify</span></a>
                @endif -->
            <div class="btn-group">
                <a href="{{route('payroll.verify_payroll_prev',['id'=>Crypt::encryptString($structure->employee),'month'=>$month])}}" class="btn btn-outline-secondary btn-sm d-flex items-center justify-center" data-toggle="tooltip" data-placement="bottom" title="Previous"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Prev</span></a>
                <a href="#" onclick="printDiv('DivIdToPrint');" class="btn btn-outline-dark btn-sm d-flex items-center justify-center" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
                <a href="{{route('salary-structure.modify_salary_structure',['month'=>date('Y-m',strtotime($month)),'id'=>Crypt::encryptString($structure->id),'route'=>Request::url()])}}" class="btn btn-outline-info btn-sm d-flex items-center justify-center" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                <a href="{{route('payroll.verify_payroll_next',['id'=>Crypt::encryptString($structure->employee),'month'=>$month])}}" class="btn btn-outline-secondary btn-sm d-flex items-center justify-center" data-toggle="tooltip" data-placement="bottom" title="Next"><span>Next</span><i class="mdi mdi-chevron-right" style="font-size:15px"></i></a>
            </div>
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route('payroll.verify',['id'=>Crypt::encryptString($structure->employee),'month'=>$month])}}" id="payrollVerify" method="POST">
    @csrf
    <div id="DivIdToPrint">
        <h3 class="text-center text-uppercase">NITTTR-Chennai</h3>
        <h4 class="text-center">Salary Structure Details</h4>
        <table class="table table-bordered mt-2" id="employeesTable">
            <th class="text-center  bg-gray-200">Employee</th>
            <th class="text-center  bg-gray-200">Department</th>
            <th class="text-center  bg-gray-200">Designation</th>
            <tr>
                <td class="text-center">{{strtoupper($structure->employee)}}-{{ucwords($structure->empname)}}</td>
                <td class="text-center">{{$structure->dept}}</td>
                <td class="text-center">{{$structure->desg}}</td>
            </tr>
        </table>

        <table class="table table-bordered mt-4" id="employeesTable">
            <tbody>
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">Allowances</th>
                </tr>
                    <tr>
                        <th class="text-left" colspan="4">Basic Salary</th>
                        <td class="text-center">{{number_format($structure->basic_salary)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">DA Allowance({{$structure->da_perc}}%)</th>
                        <td class="text-center">{{number_format($structure->da)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">HRA Allowance(27%)</th>
                        <td class="text-center">{{number_format($structure->hra)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">Transport Allowance</th>
                        <td class="text-center">{{number_format($structure->transport)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">Misc. Allowance</th>
                        <td class="text-center">{{number_format($structure->misc)}}</td>
                    </tr>
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">Additional Allowances</th>
                </tr>
                @php
                    $allArr = json_decode($structure->allowances);   
                @endphp
                @if($allArr)
                    @foreach($allArr as $key=>$value)
                        <tr>
                            <th class="text-left" colspan="4">
                                @foreach($allowances as $allowance)
                                    @if($allowance->id == $key)
                                        {{$allowance->allowance_type_name}}
                                    @endif
                                @endforeach
                            </th>
                            <td class="text-center">{{number_format($value)}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                <tr>
                        <th colspan="5" class="text-center bg-gray-200">---------</th>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">Gross Salary</th>
                        <td class="text-center">{{number_format($structure->gross_salary)}}</td>
                    </tr>
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">Deductions</th>
                </tr>
                    <tr>
                        <th class="text-left" colspan="4">Provident Fund</th>
                        <td class="text-center">{{number_format($structure->pf)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">NPS-Employee</th>
                        <td class="text-center">{{number_format($structure->npse)}}</td>
                    </tr>
                    @php
                        $nps_da_arr =  0;
                        $nps_arr    = 0;
                    @endphp
                    @if($da_arrear && $structure->pf_nps_cat=="nps")
                    <tr>
                        <th class="text-left" colspan="4">NPS (DA Arrear)</th>
                        @php
                            $nps_da_arr =  $payrolls->sum('da_arrear');
                            $nps_arr    = $nps_da_arr*(10/100);
                            if($structure->nps_da_arrear==0){
                                $structure->net_salary = $structure->net_salary-$nps_arr;
                            }
                        @endphp
                        <td class="text-center">{{number_format($nps_arr)}}</td>
                    </tr>
                    @endif
                    <input type="hidden" id="nps_da_arrear" name="nps_da_arrear" value="{{$nps_arr}}">
                    <tr>
                        <th class="text-left" colspan="4">Income Tax</th>
                        <td class="text-center">{{number_format($structure->it)}}</td>
                    </tr>
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">Loans & Advances</th>
                </tr>
                @php
                    $laArr = json_decode($structure->la);   
                @endphp
                @if($laArr)
                    @foreach($laArr as $key=>$value)
                        <tr>
                            <th class="text-left" colspan="4">
                                @foreach($la as $item)
                                    @if($item->id == $key)
                                        {{$item->da_types}}
                                    @endif
                                @endforeach
                            </th>
                            <td class="text-center">{{number_format($value)}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">Additional Deductions</th>
                </tr>
                @php
                    $dedArr = json_decode($structure->deductions);   
                @endphp
                @if($dedArr)
                    @foreach($dedArr as $key=>$value)
                        <tr>
                            <th class="text-left" colspan="4">
                                @foreach($deductions as $deduction)
                                    @if($deduction->id == $key)
                                        {{$deduction->deduction_type_name}}
                                    @endif
                                @endforeach
                            </th>
                            <td class="text-center">{{number_format($value)}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                    <tr>
                        <th colspan="5" class="text-center bg-gray-200">---------</th>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">Total Deduction</th>
                        <td class="text-center">{{number_format($structure->gross_salary-($structure->net_salary))}}</td>
                    </tr>
                    <tr>
                        <th class="text-left" colspan="4">Net Salary</th>
                        <td class="text-center">{{number_format($structure->net_salary)}}</td>
                        <input type="hidden" id="net_salary" name="net_salary" value="{{$structure->net_salary}}">
                    </tr>
                @php
                    $total_da = 0;
                    $total_tda = 0;
                @endphp
                
                @if($da_arrear)
                    <tr>
                        <th colspan="5" class="text-center  bg-gray-200">DA Arrears</th>
                    </tr>
                    <tr>
                        <th class="text-center">Month</th>
                        <th class="text-center">Basic Pay</th>
                        <th class="text-center">DA Due</th>
                        <th class="text-center">DA Drawn</th>
                        <th class="text-center">DA Balance</th>
                    </tr>
                <input type="hidden" id="prev_da" name="prev_da" value="{{$prev_da}}">
                @foreach($payrolls as $payroll)
                    @php
                        $total_da = $total_da+$payroll->da_arrear;
                    @endphp
                    <input type="hidden" id="da_month" name="da_month[]" value="{{$payroll->month}}">
                    <input type="hidden" id="da_basic_salary" name="da_basic_salary[]" value="{{$payroll->basic_salary}}">
                    <input type="hidden" id="da_due" name="da_due[]" value="{{$payroll->da_due}}">
                    <input type="hidden" id="da_drawn" name="da_drawn[]" value="{{$payroll->da}}">
                    <input type="hidden" id="da_arrear" name="da_arrear[]" value="{{$payroll->da_arrear}}">
                    <tr>
                        <td class="text-left">{{date("F-Y",strtotime($payroll->month))}}</td>
                        <td class="text-center">{{number_format($payroll->basic_salary)}}</td>
                        <td class="text-center">{{number_format($payroll->da_due)}}</td>
                        <td class="text-center">{{number_format($payroll->da)}}</td>
                        <td class="text-center">{{number_format($payroll->da_arrear)}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="4" class="text-right">Total DA balance</td>
                        <td class="text-center">{{$total_da}}</td>
                    </tr>
                <!-- DA Arrear end -->
                <!-- TDA calculation -->
                <tr>
                    <th colspan="5" class="text-center  bg-gray-200">TDA Arrears</th>
                </tr>
                <tr>
                    <th class="text-center">Month</th>
                    <th class="text-center">Basic Pay</th>
                    <th class="text-center">TDA Due</th>
                    <th class="text-center">TDA Drawn</th>
                    <th class="text-center">TDA Balance</th>
                </tr>
                @foreach($payrolls as $payroll)
                @php
                    $total_tda = $total_tda+$structure->tda_arrear;
                @endphp
                <input type="hidden" id="tda_due" name="tda_due[]" value="{{$structure->tda_due}}">
                <input type="hidden" id="tda_drawn" name="tda_drawn[]" value="{{$structure->tda_drawn}}">
                <input type="hidden" id="tda_arrear" name="tda_arrear[]" value="{{$structure->tda_arrear}}">
                    <tr>
                        <td class="text-left">{{date("F-Y",strtotime($payroll->month))}}</td>
                        <td class="text-center">{{number_format($payroll->basic_salary)}}</td>
                        <td class="text-center">{{number_format($structure->tda_due)}}</td>
                        <td class="text-center">{{number_format($structure->tda_drawn)}}</td>
                        <td class="text-center">{{number_format($structure->tda_arrear)}}</td>
                    </tr>
                @endforeach
                <input type="hidden" id="total_tda" name="total_tda" value="{{$total_tda}}">
                <tr>
                    <td colspan="4" class="text-right">Total TDA balance</td>
                    <td class="text-center">{{$total_tda}}</td>
                </tr>
                <!-- TDA calcuation end -->
                    <tr>
                        <th class="text-left" colspan="4">Total Salary</th>
                        <th class="text-center">{{number_format($structure->net_salary+$total_da+$total_tda)}}</th>
                    </tr>
                @endif
                <input type="hidden" id="total_salary" name="total_salary" value="{{$structure->net_salary+$total_da+$total_tda}}">
            </tbody>
        </table>
</div>
</form>
</div>
<script src="{{asset('js/payroll.js')}}"></script>
<script>
    $(document).ready(()=>{
        // const month = new Date(localStorage.getItem("nitttr_payroll_month"));
        // const currMonth = new Date();
        // if( (month.getFullYear()==currMonth.getFullYear() && month.getMonth()<currMonth.getMonth()) || (month.getFullYear()<currMonth.getFullYear()) ){
        //     console.log(month,currMonth);
        //     $(".verifyBtn").removeAttr("href");
        //     $(".verifyBtn").addClass("disabled");
        // }
    })
    function verifyPayroll(url,empname){
        $.confirm({
            title: 'Verify this Payroll!',
            content: 'Are you sure you want to verify?',
            type: 'green',
            typeAnimated: true,
            buttons: {
                confirm:{
                    btnClass: 'btn btn-success',
                    action:function(){
                        // $month = localStorage.getItem("nitttr_payroll_month");
                        // if($month){
                        //     console.log(url);
                        //     let urlNew = new URL(url.toString());
                        //     urlNew.searchParams.append('month', $month);
                        //     window.location.href = urlNew
                        // }
                        // window.location.href = url
                        $("#payrollVerify").submit();
                    }
                },
                cancel:{
                    btnClass: 'btn btn-dark',
                    action:function(){
                        return true;
                        $(".spinner-body").fadeOut();
                    }
                },
            }
        });
    }
</script>
@endsection
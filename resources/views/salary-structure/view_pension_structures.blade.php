@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <a href="{{route('salary-structure')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger rounded btn-sm" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
        <div>
            <a href="#" onclick="printDiv('DivIdToPrint');" class="btn btn-dark btn-sm mr-2 rounded" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
            <a href="{{route('salary-structure.modify_pension_structure',['id'=>Crypt::encryptString($structure->id)])}}" class="btn btn-info btn-sm rounded" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div id="DivIdToPrint">
        <h3 class="text-center text-uppercase">NITTTR-Chennai</h3>
        <h4 class="text-center">Pension Structure Details</h4>
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
                    <th colspan="2" class="text-center  bg-gray-200">Allowances</th>
                </tr>
                    <tr>
                        <th class="text-left">Basic Pension</th>
                        <td class="text-center">{{number_format($structure->basic_salary)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Additional Pension</th>
                        <td class="text-center">{{number_format($structure->addtl_pension)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">DA Allowance({{$structure->da_perc}}%)</th>
                        <td class="text-center">{{number_format($structure->da)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Medical Allowance</th>
                        <td class="text-center">{{number_format($structure->medic_allow)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Misc. Allowance</th>
                        <td class="text-center">{{number_format($structure->misc)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Less Commutation</th>
                        <td class="text-center">{{number_format($structure->less_comm)}}</td>
                    </tr>
                <tr>
                    <th colspan="2" class="text-center  bg-gray-200">Additional Allowances</th>
                </tr>
                @php
                    $allArr = json_decode($structure->allowances);   
                @endphp
                @if($allArr)
                    @foreach($allArr as $key=>$value)
                        <tr>
                            <th class="text-left">
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
                        <td colspan="2" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                <tr>
                    <th class="text-left">Gross Total</th>
                    <td class="text-center">{{number_format($structure->gross_salary)}}</td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center  bg-gray-200">Deductions</th>
                </tr>
                    <tr>
                        <th class="text-left">Misc. Recovery</th>
                        <td class="text-center">{{number_format($structure->misc_rec)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">IRG</th>
                        <td class="text-center">{{number_format($structure->irg)}}</td>
                    </tr>
                    <!-- <tr>
                        <th class="text-left">NPS-Employer (Deducted from Employer)</th>
                        <td class="text-center">{{number_format($structure->npser)}}</td>
                    </tr> -->
                    <tr>
                        <th class="text-left">Income Tax</th>
                        <td class="text-center">{{number_format($structure->it)}}</td>
                    </tr>
                <tr>
                    <th colspan="2" class="text-center  bg-gray-200">Loans & Advances</th>
                </tr>
                @php
                    $laArr = json_decode($structure->la);   
                @endphp
                @if($laArr)
                    @foreach($laArr as $key=>$value)
                        <tr>
                            <th class="text-left">
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
                        <td colspan="2" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                <tr>
                    <th colspan="2" class="text-center  bg-gray-200">Additional Deductions</th>
                </tr>
                @php
                    $dedArr = json_decode($structure->deductions);   
                @endphp
                @if($dedArr)
                    @foreach($dedArr as $key=>$value)
                        <tr>
                            <th class="text-left">
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
                        <td colspan="2" class="text-center bg-gray-200">---------</td>
                    </tr>
                @endif
                    <tr>
                        <th colspan="2" class="text-center bg-gray-200">---------</th>
                    </tr>
                    <tr>
                        <th class="text-left">Total Deduction</th>
                        <td class="text-center">{{number_format($structure->gross_salary-$structure->net_salary)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Net Pension</th>
                        <td class="text-center">{{number_format($structure->net_salary)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Narration</th>
                        <td class="text-center">{{$structure->narration}}</td>
                    </tr>
            </tbody>
        </table>
</div>

</div>

@endsection
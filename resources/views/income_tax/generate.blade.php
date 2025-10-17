@extends('layouts.app')

@section('content')
<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>

@php
function nps_govt($da,$salary){
    return round(0.10*($salary+(($da/100)*$salary)));
}
function nps_employer($da,$salary){
    return round(0.14*($salary+(($da/100)*$salary)));
}
@endphp

<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <a href="{{route('income_tax')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger rounded btn-sm" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
        <div>
            <a href="{{route('income_tax.generate_prev',['empid'=>Crypt::encryptString($empDetails->empid),'dept'=>Crypt::encryptString($empDetails->deptid)])}}" class="btn btn-outline-dark btn-sm rounded d-inline-flex gap-4 align-items-center justify-space-between" data-toggle="tooltip" data-placement="bottom" title="Previous Employee"><i class="mdi mdi-chevron-left" style="font-size:15px"></i> Prev</a>
            <a href="#" onclick="printDiv('DivIdToPrint');" class="btn btn-dark btn-sm mr-2 ml-2 rounded" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
            <a href="{{route('income_tax.generate_next',['empid'=>Crypt::encryptString($empDetails->empid),'dept'=>Crypt::encryptString($empDetails->deptid)])}}" class="btn btn-outline-dark btn-sm rounded d-inline-flex gap-4 align-items-center justify-space-between" data-toggle="tooltip" data-placement="bottom" title="Next Employee">Next<i class="mdi mdi-chevron-right" style="font-size:15px"></i></a>
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div id="DivIdToPrint" class="mt-4">
        <h3 class="text-center text-uppercase">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING AND RESEARCH</h3>
        <p class="text-center">Taramani, Chennai - 600 113</p>
        <h4 class="text-center">PARTICULARS FOR ASSESSING INCOME TAX FOR THE YEAR {{date("Y")}}-{{date("Y",strtotime("+1 year"))}}</h4>
        <table class="table table-bordered mt-2" id="employeesTable">
            <th class="text-center  bg-gray-200">Employee ID</th>
            <th class="text-center  bg-gray-200">Permanent Account Number</th>
            <tr>
                <td class="text-center">{{strtoupper($empDetails->empid)}}</td>
                <td class="text-center">{{strtoupper($empDetails->emppanno)}}</td>
            </tr>
           
        </table>
    
        <form action="{{route('income_tax.update_income_tax',['empid'=>Crypt::encryptString($empDetails->empid),'dept'=>$empDetails->deptid])}}" id="incometaxForm" method="post">
            @csrf
            <table class="table table-bordered mt-4" id="employeesTable">
                <tbody>
                        <tr>
                            <td class="text-left">1</td>
                            <td class="text-left" colspan="2">Name in BLOCK letters</td>
                            <td class="text-center">
                                {{$empDetails->empname}}
                                <input type="hidden" id="age" name="age" value="{{date_diff(date_create($empDetails->empdob), date_create('today'))->y}}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td class="text-left" colspan="2">Date of Birth</td>
                            <td class="text-center">{{$empDetails->empdob}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">2</td>
                            <td colspan="2" class="text-left">Designation</td>
                            <td class="text-center">{{$empDetails->designation}}-{{ucwords($empDetails->desg_description)}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td colspan="2" class="text-left">Whether Sr Citizen/Very Sr Citizen</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left">3</td>
                            <td colspan="2" class="text-left">Residential address</td>
                            <td class="text-center">{{$empDetails->empaddress}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">4</td>
                            <td colspan="2" class="text-left">House owned or living in rented house</td>
                            <td class="text-center">{{($empDetails->quarters=="yes")?"Staff Quarters":""}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">5</td>
                            <td colspan="3" class="alert alert-warning">If living in rented house, rent paid per month (latest rent receipt to be attached)</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="2" class="text-left" style="font-weight:bold;">Select Regime</td>
                            <td class="text-center">
                                <select name="regime" id="regime" class="form-control form-control-sm">
                                    <option value="new">New Regime</option>
                                    <option value="old">Old Regime</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">6</td>
                            <td colspan="3" style="font-weight:bold">Commutation of Gross Income</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td colspan="2" class="text-left">Salary ({{date("01-03-Y")}} to {{date("d-m-Y",strtotime(date("01-03-Y")."+1 year -1 day"))}})</td>
                            <td class="text-center p-0">
                                <input type="number" id="salary" name="salary" class="form-control form-control-sm rounded-0 border-0 number_input text-right add_income" value="{{$salaryDetails->annual_gross}}" placeholder="Salary">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(b)</td>
                            <td colspan="2" class="text-left">Pay Arrears</td>
                            <td class="text-center p-0">
                                <input type="number" id="arrears" name="arrears" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Pay Arrears">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(c)</td>
                            <td colspan="2" class="text-left">Children Education Allowance</td>
                            <td class="text-center p-0">
                                <input type="number" id="child_edu" name="child_edu" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Children Education Allowance">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(d)</td>
                            <td colspan="2" class="text-left">Encashment of EL for LTC</td>
                            <td class="text-center p-0">
                                <input type="number" id="enc_of_el" name="enc_of_el" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Encashment of EL for LTC">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(e)</td>
                            <td colspan="2" class="text-left">Honororium / Remuneration</td>
                            <td class="text-center p-0">
                                <input type="number" id="remuneration" name="remuneration" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Honororium / Remuneration">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(f)</td>
                            <td colspan="2" class="text-left">NPS Employer Contribution(14%)</td>
                            <td class="text-center p-0"> 
                                <input type="number" id="npser" name="npser" class="form-control form-control-sm rounded-0 border-0 number_input add_income text-right" value="{{nps_employer($da->da,$salaryDetails->basic_salary*12)}}" placeholder="NPS Employer Contribution(14%)">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(g)</td>
                            <td colspan="2" class="text-left">Income/Loss from house property ({{$da->da}})</td>
                            <td class="text-center p-0">
                                <input type="number" id="house_property" name="house_property" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Income/Loss from house property">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(h)</td>
                            <td colspan="2" class="text-left">Other income (if any)</td>
                            <td class="text-center p-0">
                                <input type="number" id="other_income" name="other_income" class="form-control form-control-sm rounded-0 border-0 number_input add_income" placeholder="Other income (if any)">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="p-0">
                                <input type="number" id="gross_income" name="gross_income" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="" value="0">
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left order_sno">(+)</td>
                            <td colspan="3" style="font-weight:bold">Value of residential accommodation</td>
                        </tr>
                        @php
                            $rapTotal = 0;
                        @endphp
                        @if(count($rap)>0)
                            @foreach($rap as $ra)
                                <tr>
                                    <td class="text-left pl-4"></td>
                                    <td class="text-left">
                                        <table style="width:100%">
                                            <td class="border-0 p-0 pr-2">BP-{{$ra["basic_pay"]}}</td>
                                            <td class="border-0 p-0 pr-2">DA-{{$ra["da"]}}%</td>
                                            <td class="border-0 p-0 pr-2">({{date("M-y",strtotime($ra["from"]))}} to {{date("M-y",strtotime($ra["to"]))}})</td>
                                            <td class="border-0 p-0 pr-2">{{$ra["rap"]}}%</td>
                                        </table>
                                    @php
                                        $date1 = new DateTime($ra["from"]);
                                        $date2 = new DateTime($ra["to"]);

                                        $interval = $date1->diff($date2);

                                        $months = ($interval->y * 12) + $interval->m;
                                        $rap_value = (($ra["basic_pay"]*($ra["da"]/100)+$ra["basic_pay"])*$months)*($ra["rap"]/100);
                                        $rapTotal = $rapTotal+$rap_value;
                                    @endphp
                                    <td class="text-right p-0">
                                        <input type="number" id="rap_value" name="rap_value[]" class="form-control rap_value form-control-sm rounded-0 border-0 number_input text-right" value="{{round($rap_value)}}" placeholder="RAP Value">
                                    </td>
                                </tr>
                            @endforeach
                             <!-- hidden values for DB -->
                             <input type="hidden" id="rap_data" name="rap_data" value="{{json_encode($rap)}}">
                        @else
                            <tr>
                                <td class="text-left pl-4"></td>
                                <td class="text-left"></td>
                                <td class="text-right p-0">
                                    <input type="number" id="rap_value" name="rap_value[]" class="form-control rap_value form-control-sm rounded-0 border-0 number_input text-right" value="0" placeholder="RAP Value">
                                </td>
                            </tr>
                            <!-- hidden values for DB -->
                            <input type="hidden" id="rap_data" name="rap_data" value="">
                        @endif
                        <tr>
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">Total</td>
                                <td class="text-center p-0"> 
                                    <input type="number" id="rap_total" name="rap_total" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="{{round($rapTotal)}}" placeholder="RA Total">
                                </td>
                            </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="p-0">
                                <input type="number" id="gross_income_rap" name="gross_income_rap" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: License fee paid during the year</td>
                            <td class="text-center p-0">
                                <input type="number" id="license_fee" name="license_fee" class="form-control form-control-sm rounded-0 border-0 number_input less_1" placeholder="License fee paid during the year">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: Govt. contribution to NPS u/s.80-CCD(2)</td>
                            <td class="text-center p-0">
                                <input type="number" id="govt_nps" name="govt_nps" class="form-control form-control-sm rounded-0 border-0 number_input less_1 text-right" value="{{nps_govt($da->da,$salaryDetails->basic_salary*12)}}" placeholder="Govt. contribution to NPS u/s.80-CCD(2)">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: Standard deduction</td>
                            <td class="text-center p-0">
                                <input type="number" id="standard_deduction" name="standard_deduction" class="form-control form-control-sm rounded-0 border-0 number_input less_1 text-right" placeholder="Standard deduction" value="50000">
                            </td>
                        </tr>

                        <!-- old regime -->
                            <tr class="old_regime" style="display:none">
                                <td class="text-left order_sno">7</td>
                                <td colspan="3" style="font-weight:bold">LESS: Amount of HRA exempted</td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4">(a)</td>
                                <td class="text-left">Actual HRA received for the year</td>
                                <td class="text-center p-0">
                                    <input type="number" id="hra_received" name="hra_received" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="{{$salaryDetails->annual_hra}}" placeholder="Actual HRA received for the year">
                                </td>
                                <td class="text-center p-0 border-0"> </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4">(b)</td>
                                <td class="text-left" colspan="2">Actual expenditure incurred on Rent</td>
                                <td class="text-center p-0 border-0"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(i) Rent paid per annum</td>
                                <td class="text-center p-0">
                                    <input type="number" id="rent_paid" name="rent_paid" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="0" placeholder="Rent paid per annum">
                                </td>
                                <td class="text-center p-0 border-0"> </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(ii) 10% of the Basic Pay + DA of the year</td>
                                <td class="text-center p-0">
                                    <input type="number" id="rent_calc" name="rent_calc" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="{{($salaryDetails->annual_da*0.10)}}" placeholder="10% of the Basic Pay + DA of the year">
                                </td>
                                <td class="text-center p-0 border-0"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" style="font-weight:bold">Balance</td>
                                <td class="text-center p-0">
                                    <input type="number" id="hra_balance" name="hra_balance" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Balance">
                                </td>
                                <td class="text-center p-0">
                                    <input type="number" id="hra_exempted" name="hra_exempted" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Amount of HRA exempted">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left order_sno">8</td>
                                <td class="text-left" colspan="2">Less: Professional Tax</td>
                                <td class="text-center p-0"> 
                                    <input type="number" id="prefessional_tax" name="prefessional_tax" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Professional Tax">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">
                                    <input type="number" id="balance_after_pt" name="balance_after_pt" class="form-control form-control-sm rounded-0 border-0 number_input text-right" readonly value="" placeholder="Balance">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left order_sno">9</td>
                                <td class="text-left">(a) Premia for Medical Insurance under GI. Ins.Corp of India (U/S 80 D)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="premia_insurance" name="premia_insurance" class="form-control form-control-sm rounded-0 less_3 border-0 number_input text-right" value="" placeholder="Insurance">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.50000/-</td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(b) Payment of interest towards loan availed from a recognized <br> institution for acquisition / construction of a self occupied residential house <br> (The date of occupation may be initmated)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="payment_interest" name="payment_interest" class="form-control form-control-sm rounded-0 less_3 border-0 number_input text-right" value="" placeholder="Loan Interest">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.200000/-</td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(c)  Deduction in r/o interest on loan for higher eduction</td>
                                <td class="text-center p-0">
                                    <input type="number" id="higher_education" name="higher_education" class="form-control form-control-sm rounded-0 less_3 border-0 number_input text-right" value="" placeholder="Loan for higher education">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(d)  Deduction in case of a person with disability</td>
                                <td class="text-center p-0">
                                    <input type="number" id="disability_deduction" name="disability_deduction" class="form-control form-control-sm rounded-0 less_3 border-0 number_input text-right" value="" placeholder="Deduction for disability">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.125000/-</td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(e)  Any Other</td>
                                <td class="text-center p-0">
                                    <input type="number" id="other_deduction" name="other_deduction" class="form-control form-control-sm rounded-0 border-0 less_3 number_input text-right" value="" placeholder="Others">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">Total</td>
                                <td class="text-center p-0"> 
                                    <input type="number" id="total_deduction_1" name="total_deduction_1" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Total Deductions">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">
                                    <input type="number" id="deduction_balance_1" name="deduction_balance_1" class="form-control form-control-sm rounded-0 border-0 number_input text-right" readonly value="" placeholder="Balance">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left order_sno">10</td>
                                <td class="text-left" style="font-weight:bold"  colspan="2">Less: Eligible amount for deductions (u/s 80-C & 80-CCC)</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.150000/-</td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(a) Payment towards LIC Pension fund(u/s 80-CC) Subject to a maximum of Rs.10,000/-</td>
                                <td class="text-center p-0">
                                    <input type="number" id="lic_pf" name="lic_pf" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="LIC Pension fund">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(b) Subscription towards GPF / CPF / NPS</td>
                                <td class="text-center p-0">
                                    <input type="number" id="subscription_gpf" name="subscription_gpf" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Subscription towards GPF / CPF / NPS">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(c) LIC Premium (SSS & Pvt.) <br>(10% of sum assured)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="lic_premium" name="lic_premium" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="LIC Premium (SSS & Pvt.)">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(d) PLI premium</td>
                                <td class="text-center p-0">
                                    <input type="number" id="pli_premium" name="pli_premium" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="PLI premium">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(e) Group Savings Linked Insurance Scheme (GSLIS)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="gslis" name="gslis" class="form-control form-control-sm rounded-0 border-0 savings_deduction number_input text-right" value="" placeholder="Group Savings">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(f) ULIP of LIC / UTI</td>
                                <td class="text-center p-0">
                                    <input type="number" id="ulip" name="ulip" class="form-control form-control-sm rounded-0 border-0 savings_deduction number_input text-right" value="" placeholder="ULIP of LIC / UTI">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(g) NSC (VIII issue purchased during the year)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="nsc" name="nsc" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="NSC">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(h) Post Office (10/15 years) CTD</td>
                                <td class="text-center p-0">
                                    <input type="number" id="post_office" name="post_office" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Post Office">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(i) 15 years Public Provident Fund</td>
                                <td class="text-center p-0">
                                    <input type="number" id="public_pf" name="public_pf" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="15 years Public Provident Fund">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(j) Govt. approved spl. Securities</td>
                                <td class="text-center p-0">
                                    <input type="number" id="spl_secu" name="spl_secu" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Govt. approved spl. Securities<">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(k) Interest accrued on NSC (VI & VIII) <br>(from issues purchased in previous financial years)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="interest_nsc" name="interest_nsc" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Interest accrued on NSC (VI & VIII)">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(l) Repayment made towards the cost of purchase / construction <br> of a new residential house property towards the loan taken <br> from a recognized institution for the purpose</td>
                                <td class="text-center p-0">
                                    <input type="number" id="repayment_cost" name="repayment_cost" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Repayment">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(m) Tuition fees for two child</td>
                                <td class="text-center p-0">
                                    <input type="number" id="tuition_fees" name="tuition_fees" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Tuition fees for two child">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(n) Fixed Desposit at SBI or Nationalised Bank (Minimum 5 years)</td>
                                <td class="text-center p-0">
                                    <input type="number" id="fixed_deposit" name="fixed_deposit" class="form-control form-control-sm rounded-0 savings_deduction border-0 number_input text-right" value="" placeholder="Fixed Desposit">
                                </td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">Total Savings</td>
                                <td class="text-center p-0"> 
                                    <input type="number" id="total_savings" name="total_savings" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Total Savings">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">(Maximum eligbile amount - 1.5 Lakh Only)</td>
                                <td class="text-center p-0"> 
                                    <input type="number" id="eligible_deduction" name="eligible_deduction" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Eligible Amount">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">
                                    <input type="number" id="deduction_balance_2" name="deduction_balance_2" class="form-control form-control-sm rounded-0 border-0 number_input text-right" readonly value="" placeholder="Balance">
                                </td>
                            </tr>
                            <tr class="old_regime" style="display:none">
                                <td class="text-left order_sno">11</td>
                                <td class="text-left" colspan="2">NPS additional contribution u/s.80CCD(1B)</td>
                                <td class="p-0">
                                    <input type="number" id="nps_add" name="nps_add" class="form-control form-control-sm rounded-0 border-0 text-right" placeholder="NPS Addl. Contribution-Rs.50000" value="0">
                                </td>
                            </tr>
                        <!-- old regime end -->
                        <tr>
                            <td class="text-left order_sno">7</td>
                            <td class="text-left" colspan="2">Taxable Income</td>
                            <td class="p-0">
                                <input type="number" id="total_amount" name="total_amount" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">8</td>
                            <td class="text-left" colspan="2">Taxable Income(Rounded off to nearest rupees)</td>
                            <td class="text-center p-0">
                                <input type="number" id="income_tax_round" name="income_tax_round" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="" placeholder="Income Tax(Rounded off to nearest rupees)">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">9</td>
                            <td class="text-left" colspan="2">Income Tax</td>
                            <td class="p-0">
                                <input type="number" id="income_tax" name="income_tax" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="Income Tax" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">10</td>
                            <td class="text-left" colspan="2">Less: Tax Rebate*(U/S 87-A)</td>
                            <td class="text-center p-0">
                                <input type="number" id="tax_rebate" name="tax_rebate" class="form-control form-control-sm rounded-0 border-0 number_input text-right" placeholder="Tax Rebate*(U/S 87-A)">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">11</td>
                            <td class="text-left" colspan="2">Net Income Tax</td>
                            <td class="p-0">
                                <input type="number" id="net_income_tax" name="net_income_tax" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="Net Income Tax" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">12</td>
                            <td class="text-left" colspan="2">Health & Education Cess @4%</td>
                            <td class="text-center p-0">
                                <input type="number" id="health_cess" name="health_cess" class="form-control form-control-sm rounded-0 border-0 number_input text-right" placeholder="Health & Education Cess @4%">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">13</td>
                            <td class="text-left" colspan="2">Net amount to be deducted</td>
                            <td class="p-0">
                                <input type="number" id="amt_to_be_deducted" name="amt_to_be_deducted" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="Net amount to be deducted" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">14</td>
                            <td class="text-left" colspan="2">IT already deducted at source upto</td>
                            <td class="text-center p-0">
                                <input type="number" id="already_deducted" name="already_deducted" class="form-control form-control-sm rounded-0 border-0 number_input text-right" value="{{$it_deducted}}" placeholder="IT already deducted at source upto">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">15</td>
                            <td class="text-left" colspan="2">Balance amount to be deducted/paid</td>
                            <td class="p-0">
                                <input type="number" id="balance_to_be_deducted" name="balance_to_be_deducted" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="alert alert-warning">
                                <b>Note:</b> Certificates/Proof/Documentary Evidence should be produced for verification
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="pt-4 border-0"></td>
                            <td class="text-right pt-4 border-0"><b>Signature of the staff member</b></td>
                        </tr>
                        <tr>
                            <td><b>Date:</b></td>
                            <td colspan="2">Nov-{{date("Y")}}</td>
                            <td class="p-0">
                                <input type="number" id="nov_month" name="nov_month" class="form-control form-control-sm rounded-0 border-0 text-right it_monthly_deduct" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Dec-{{date("Y")}}</td>
                            <td class="p-0">
                                <input type="number" id="dec_month" name="dec_month" class="form-control form-control-sm rounded-0 border-0 text-right it_monthly_deduct" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Jan-{{date("Y",strtotime("+1 year"))}}</td>
                            <td class="p-0">
                                <input type="number" id="jan_month" name="jan_month" class="form-control form-control-sm rounded-0 border-0 text-right it_monthly_deduct" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Feb-{{date("Y",strtotime("+1 year"))}}</td>
                            <td class="p-0">
                                <input type="number" id="feb_month" name="feb_month" class="form-control form-control-sm rounded-0 border-0 text-right it_monthly_deduct" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="p-0">
                                <input type="number" id="total_month_deduction" name="total_month_deduction" class="form-control form-control-sm rounded-0 border-0 text-right" readonly placeholder="Balance amount to be deducted/paid" value="0">
                            </td>
                        </tr>
                </tbody>
            </table>
            <table class="table table-bordered mt-4" id="employeesTable">
                <tbody>
                        <tr>
                            <td class="text-left" colspan="5">For Office Use</td>
                        </tr>
                        <tr>
                            <td class="pt-4 text-center border-0"><b>ASO</b></td>
                            <td class="pt-4 text-center border-0"><b>SO.Gr.II</b></td>
                            <td class="pt-4 text-center border-0"><b>Consult(F&IA)</b></td>
                            <td class="pt-4 text-center border-0"><b>Accounts Officer</b></td>
                            <td class="pt-4 text-center border-0"><b>Sr. Admin. Officer</b></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="pt-4 text-right">
                                <button class="btn btn-success rounded btn-sm" type="button" id="updateBtn" data-toggle="tooltip" data-placement="bottom" title="Click to update" style="font-weight: bold"><i class="mdi mdi-check" style="vertical-align: middle;"></i> <span>Update</span></button>
                            </td>
                        </tr>
                </tbody>
            </table>
        </form>
</div>
</div>
<script src="{{asset('js/payroll.js')}}"></script>
<script>
    $(document).ready((e)=>{
        
        $(".number_input").focusout((e)=>{
            if($(e.target).val().length>0){
                $(e.target).addClass("text-right");
            }else{
                $(e.target).removeClass("text-right");
            }
        })

    })
</script>

@endsection
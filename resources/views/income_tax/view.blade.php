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

<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <a href="{{route('income_tax')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger rounded btn-sm" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
        <div>
            <a href="#" onclick="printDiv('DivIdToPrint');" class="btn btn-dark btn-sm mr-2 ml-2 rounded" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
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
        <h4 class="text-center">PARTICULARS FOR ASSESSING INCOME TAX FOR THE YEAR {{date("Y",strtotime($incomeTax->year."01-01"))}}-{{date("Y",strtotime($incomeTax->year."01-01 +1 year"))}}</h4>
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
                            <td class="text-left"></td>
                            <td class="text-left" colspan="2">Name in BLOCK letters</td>
                            <td class="text-center">
                                {{$empDetails->empname}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td class="text-left" colspan="2">Age</td>
                            <td class="text-center">
                                {{$incomeTax->age}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td class="text-left" colspan="2">Date of Birth</td>
                            <td class="text-center">{{$empDetails->empdob}}</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="2" class="text-left">Designation</td>
                            <td class="text-center">{{$empDetails->designation}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td colspan="2" class="text-left">Whether Sr Citizen/Very Sr Citizen</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="2" class="text-left">Residential address</td>
                            <td class="text-center">{{$empDetails->empaddress}}</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="2" class="text-left">House owned or living in rented house</td>
                            <td class="text-center">{{($empDetails->quarters=="yes")?"Staff Quarters":""}}</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="2" class="text-left" style="font-weight:bold;">Regime</td>
                            <td class="text-center">{{$incomeTax->regime}}</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td colspan="3" style="font-weight:bold">Commutation of Gross Income</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(a)</td>
                            <td colspan="2" class="text-left">Salary ({{date("01-03-Y",strtotime($incomeTax->year."01-01"))}} to {{date("d-m-Y",strtotime(date("01-03-Y",strtotime($incomeTax->year."01-01"))."+1 year -1 day"))}})</td>
                            <td class="text-center p-0">{{$incomeTax->salary}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(b)</td>
                            <td colspan="2" class="text-left">Pay Arrears</td>
                            <td class="text-center p-0">{{$incomeTax->arrears}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(c)</td>
                            <td colspan="2" class="text-left">Children Education Allowance</td>
                            <td class="text-center p-0">{{$incomeTax->child_edu}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(d)</td>
                            <td colspan="2" class="text-left">Encashment of EL for LTC</td>
                            <td class="text-center p-0">{{$incomeTax->enc_of_el}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(e)</td>
                            <td colspan="2" class="text-left">Honororium / Remuneration</td>
                            <td class="text-center p-0">{{$incomeTax->remuneration}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(f)</td>
                            <td colspan="2" class="text-left">NPS Employer Contribution(14%)</td>
                            <td class="text-center p-0">{{$incomeTax->npser}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(g)</td>
                            <td colspan="2" class="text-left">Income/Loss from house property</td>
                            <td class="text-center p-0">{{$incomeTax->house_property}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4">(h)</td>
                            <td colspan="2" class="text-left">Other income (if any)</td>
                            <td class="text-center p-0">{{$incomeTax->other_income}}</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center">{{$incomeTax->gross_income}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">(+)</td>
                            <td colspan="3" style="font-weight:bold">Value of residential accommodation</td>
                        </tr>
                        @php
                            $rap = json_decode($incomeTax->rap_data);
                            $rap_value = json_decode($incomeTax->rap_value);
                        @endphp
                        @if(count($rap)>0)
                            @foreach($rap as $key=>$ra)
                                <tr>
                                    <td class="text-left"></td>
                                    <td class="text-left">
                                        <table style="width:100%">
                                            <td class="border-0 p-0 pr-2">BP-{{$ra->basic_pay}}</td>
                                            <td class="border-0 p-0 pr-2">DA-{{$ra->da}}%</td>
                                            <td class="border-0 p-0 pr-2">({{date("M-y",strtotime($ra->from))}} to {{date("M-y",strtotime($ra->to))}})</td>
                                            <td class="border-0 p-0 pr-2">{{$ra->rap}}%</td>
                                        </table>
                                    <td class="text-right ">
                                        {{$rap_value[$key]}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-left"></td>
                                <td class="text-left"></td>
                                <td class="text-right ">
                                    0
                                </td>
                            </tr>
                        @endif
                        <tr>
                                <td class="text-left"></td>
                                <td class="text-left" colspan="2">Total</td>
                                <td class="text-center "> 
                                    {{$incomeTax->rap_total}}
                                </td>
                            </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center">
                                {{$incomeTax->gross_income_rap}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: License fee paid during the year</td>
                            <td class="text-center p-0">{{$incomeTax->license_fee}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: Govt. contribution to NPS u/s.80-CCD(2)</td>
                            <td class="text-center p-0">{{$incomeTax->govt_nps}}</td>
                        </tr>
                        <tr>
                            <td class="text-left pl-4"></td>
                            <td colspan="2" class="text-left">Less: Standard deduction</td>
                            <td class="text-center p-0">{{$incomeTax->standard_deduction}}</td>
                        </tr>

                        <!-- old regime -->
                        @if($incomeTax->regime=="old")
                            <tr class="old_regime">
                                <td class="text-left order_sno"></td>
                                <td colspan="3" style="font-weight:bold">LESS: Amount of HRA exempted</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4">(a)</td>
                                <td class="text-left">Actual HRA received for the year</td>
                                <td class="text-center p-0">{{$incomeTax->salary}}</td>
                                <td class="text-center p-0 border-0"> </td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4">(b)</td>
                                <td class="text-left" colspan="2">Actual expenditure incurred on Rent</td>
                                <td class="text-center p-0 border-0"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(i) Rent paid per annum</td>
                                <td class="text-center p-0">{{$incomeTax->rent_paid}}</td>
                                <td class="text-center p-0 border-0"> </td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(ii) 10% of the Basic Pay + DA of the year</td>
                                <td class="text-center p-0">{{$incomeTax->rent_calc}}</td>
                                <td class="text-center p-0 border-0"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" style="font-weight:bold">Balance</td>
                                <td class="text-center p-0">{{$incomeTax->hra_balance}}</td>
                                <td class="text-center p-0">{{$incomeTax->hra_exempted}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left order_sno"></td>
                                <td class="text-left" colspan="2">Less: Professional Tax</td>
                                <td class="text-center p-0">{{$incomeTax->prefessional_tax}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">{{$incomeTax->balance_after_pt}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left order_sno">9</td>
                                <td class="text-left">(a) Premia for Medical Insurance under GI. Ins.Corp of India (U/S 80 D)</td>
                                <td class="text-center p-0">{{$incomeTax->premia_insurance}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.50000/-</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(b) Payment of interest towards loan availed from a recognized <br> institution for acquisition / construction of a self occupied residential house <br> (The date of occupation may be initmated)</td>
                                <td class="text-center p-0">{{$incomeTax->payment_interest}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.200000/-</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(c)  Deduction in r/o interest on loan for higher eduction</td>
                                <td class="text-center p-0">{{$incomeTax->higher_education}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(d)  Deduction in case of a person with disability</td>
                                <td class="text-center p-0">{{$incomeTax->disability_deduction}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.125000/-</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(e)  Any Other</td>
                                <td class="text-center p-0">{{$incomeTax->other_deduction}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">Total</td>
                                <td class="text-center p-0">{{$incomeTax->total_deduction_1}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">{{$incomeTax->deduction_balance_1}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left order_sno"></td>
                                <td class="text-left" style="font-weight:bold"  colspan="2">Less: Eligible amount for deductions (u/s 80-C & 80-CCC)</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;">Maximum limit Rs.150000/-</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(a) Payment towards LIC Pension fund(u/s 80-CC) Subject to a maximum of Rs.10,000/-</td>
                                <td class="text-center p-0">{{$incomeTax->lic_pf}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(b) Subscription towards GPF / CPF / NPS</td>
                                <td class="text-center p-0">{{$incomeTax->subscription_gpf}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(c) LIC Premium (SSS & Pvt.) <br>(10% of sum assured)</td>
                                <td class="text-center p-0">{{$incomeTax->lic_premium}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(d) PLI premium</td>
                                <td class="text-center p-0">{{$incomeTax->pli_premium}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(e) Group Savings Linked Insurance Scheme (GSLIS)</td>
                                <td class="text-center p-0">{{$incomeTax->gslis}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(f) ULIP of LIC / UTI</td>
                                <td class="text-center p-0">{{$incomeTax->ulip}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(g) NSC (VIII issue purchased during the year)</td>
                                <td class="text-center p-0">{{$incomeTax->nsc}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(h) Post Office (10/15 years) CTD</td>
                                <td class="text-center p-0">{{$incomeTax->post_office}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(i) 15 years Public Provident Fund</td>
                                <td class="text-center p-0">{{$incomeTax->public_pf}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(j) Govt. approved spl. Securities</td>
                                <td class="text-center p-0">{{$incomeTax->spl_secu}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(k) Interest accrued on NSC (VI & VIII) <br>(from issues purchased in previous financial years)</td>
                                <td class="text-center p-0">{{$incomeTax->interest_nsc}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(l) Repayment made towards the cost of purchase / construction <br> of a new residential house property towards the loan taken <br> from a recognized institution for the purpose</td>
                                <td class="text-center p-0">{{$incomeTax->repayment_cost}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(m) Tuition fees for two child</td>
                                <td class="text-center p-0">{{$incomeTax->tuition_fees}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left">(n) Fixed Desposit at SBI or Nationalised Bank (Minimum 5 years)</td>
                                <td class="text-center p-0">{{$incomeTax->fixed_deposit}}</td>
                                <td class="text-center p-0 border-0" style="font-style:italic;"></td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">Total Savings</td>
                                <td class="text-center p-0">{{$incomeTax->total_savings}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left pl-4"></td>
                                <td class="text-left" colspan="2">(Maximum eligbile amount - 1.5 Lakh Only)</td>
                                <td class="text-center p-0">{{$incomeTax->eligible_deduction}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left" colspan="3"></td>
                                </td>
                                <td class="text-center p-0">{{$incomeTax->deduction_balance_2}}</td>
                            </tr>
                            <tr class="old_regime">
                                <td class="text-left order_sno"></td>
                                <td class="text-left" colspan="2">NPS additional contribution u/s.80CCD(1B)</td>
                                <td class="text-center p-0">{{$incomeTax->nps_add}}</td>
                            </tr>
                        @endif
                        <!-- old regime end -->
                        <tr>
                            <td class="text-left order_sno"></td>
                            <td class="text-left" colspan="2">Taxable Income</td>
                            <td class="text-center p-0">{{$incomeTax->total_amount}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno"></td>
                            <td class="text-left" colspan="2">Taxable Income(Rounded off to nearest rupees)</td>
                            <td class="text-center p-0">{{$incomeTax->income_tax_round}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">9</td>
                            <td class="text-left" colspan="2">Income Tax</td>
                            <td class="text-center p-0">{{$incomeTax->income_tax}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">10</td>
                            <td class="text-left" colspan="2">Less: Tax Rebate*(U/S 87-A)</td>
                            <td class="text-center p-0">{{$incomeTax->tax_rebate}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">11</td>
                            <td class="text-left" colspan="2">Net Income Tax</td>
                            <td class="text-center p-0">{{$incomeTax->net_income_tax}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">12</td>
                            <td class="text-left" colspan="2">Health & Education Cess @4%</td>
                            <td class="text-center p-0">{{$incomeTax->health_cess}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">13</td>
                            <td class="text-left" colspan="2">Net amount to be deducted</td>
                            <td class="text-center p-0">{{$incomeTax->amt_to_be_deducted}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">14</td>
                            <td class="text-left" colspan="2">IT already deducted at source upto</td>
                            <td class="text-center p-0">{{$incomeTax->already_deducted}}</td>
                        </tr>
                        <tr>
                            <td class="text-left order_sno">15</td>
                            <td class="text-left" colspan="2">Balance amount to be deducted/paid</td>
                            <td class="text-center p-0">{{$incomeTax->balance_to_be_deducted}}</td>
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
                            <td class="text-center p-0">{{$incomeTax->nov_month}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Dec-{{date("Y")}}</td>
                            <td class="text-center p-0">{{$incomeTax->dec_month}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Jan-{{date("Y",strtotime("+1 year"))}}</td>
                            <td class="text-center p-0">{{$incomeTax->jan_month}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">Feb-{{date("Y",strtotime("+1 year"))}}</td>
                            <td class="text-center p-0">{{$incomeTax->feb_month}}</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-center p-0">{{$incomeTax->total_month_deduction}}</td>
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
                </tbody>
            </table>
        </form>
</div>
</div>
<!-- <script src="{{asset('js/payroll.js')}}"></script> -->
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Ledger</title>
</head>
<style>
    @page {
        size: a3 landscape;
    }
    body{
        font-size:10px;
    }
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 5px;
        border:1px solid black;
    }
    .text-right{
        text-align:right;
    }
    table th{
        font-size:10px;
    }
    .fw-bold{
        font-weight:600;
    }
    .text-center{
        text-align:center;
    }
    .text-uppercase{
        text-transform:uppercase;
    }
    .page_break { page-break-before: always; }
</style>
<body>
    @foreach($ledger as $led)
    <p class="text-right">{{date('d/m/Y')}}</p>
    <div class="text-center">
        <p class="fw-bold">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING & RESEARCH</p>
        <P class="fw-bold">TARAMANI, CHENNAI</P>
        <p>***************************************************************************</p>
        <p class="fw-bold text-uppercase">PAY LEDGER UPTO THE MONTH OF {{date("F Y",strtotime($month))}}</p>
    </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Employee Name</td>
                        <td>: {{$led->emp->empname}}</td>

                        <td class="fw-bold">Pay Range</td>
                        <td type="string">: {{$led->emp->emppayscale}}</td>

                        <td class="fw-bold">Quarters No</td>
                        <td type="string">: {{($led->emp->quarters=="yes")?$led->emp->quartersno:''}}</td>

                        <td class="fw-bold">Date of Birth</td>
                        <td>: {{date("d/m/Y",strtotime($led->emp->empdob))}}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Designation</td>
                        <td>: {{$led->emp->designation_name}}</td>

                        <td class="fw-bold">Rate of Pay</td>
                        <td type="string">: {{$led->emp->emppay}}</td>

                        <td class="fw-bold">Date Occupied</td>
                        <td>: {{($led->emp->quarters=="yes")?$led->emp->doccupied:''}}</td>

                        <td class="fw-bold">Joined Date</td>
                        <td>: {{$led->emp->empdoj?date("d/m/Y",strtotime($led->emp->empdoj)):"-"}}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Department</td>
                        <td>: {{$led->emp->department_name}}</td>

                        <td class="fw-bold">PF/NPS Number</td>
                        <td type="string">: {{$led->emp->gpfno."/".$led->emp->npsno}}</td>

                        <td class="fw-bold">Date Vacated</td>
                        @if($led->emp->dovacated && $led->emp->dovacated!="1970-01-01")
                        <td>: {{($led->emp->quarters=="yes")?$led->emp->dovacated:''}}</td>
                        @else
                        <td>: -</td>
                        @endif

                        <td class="fw-bold">IT-PAN</td>
                        <td type="string">: {{$led->emp->emppanno}}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Reference No</td>
                        <td type="string">: {{$led->emp->empid}}</td>
                        
                        <td colspan="6"></td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="table-responsive mt-2">
            <table class="table table-hover">
                <thead class="bg-primary text-light">
                @foreach($columns as $column)
                    <th class="text-uppercase">{{str_replace("_"," ",$column)}}</th>
                @endforeach
                </thead>
                <tbody>
                    @php
                        $tot_allowance = array_fill_keys($allowances, 0);
                        $tot_deduction = array_fill_keys($deductions, 0);
                        $tot_la = [];
                        $da_col_1 = count($allowances)+3;
                        $da_col_2 = count($deductions)+4;
                    @endphp
                    @foreach($led as $ledg)
                        @if($ledg->prev_da)
                            <tr>
                                <td>{{date("M Y",strtotime($ledg->month))}}</td>
                                <td>DA Arrears</td>
                                <td class="text-right">{{array_sum(json_decode($ledg->da_arrear))+$ledg->total_tda}}</td>
                                <td colspan="{{$da_col_1}}" class="text-uppercase">DA ARREARS FROM {{date("M'y",strtotime(json_decode($ledg->da_month)[0]))}} to {{date("M'y",strtotime(json_decode($ledg->da_month)[count(json_decode($ledg->da_month))-1]))}}({{$ledg->prev_da."% to ".$ledg->da_perc."%"}})</td>
                                <td class="text-right">{{array_sum(json_decode($ledg->da_arrear))+$ledg->total_tda}}</td>
                                <td colspan="{{$da_col_2}}"></td>
                                <td class="text-right">{{array_sum(json_decode($ledg->da_arrear))+$ledg->total_tda}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{date("M Y",strtotime($ledg->month))}}</td>
                            <td class="text-right">{{number_format($ledg->basic_salary)}}</td>
                            <td class="text-right">{{number_format($ledg->da)}}</td>
                            <td class="text-right">{{number_format($ledg->hra)}}</td>
                            <td class="text-right">{{number_format($ledg->transport)}}</td>
                            <td class="text-right">{{number_format($ledg->misc)}}</td>
                            <!-- allowances -->
                            @foreach($allowances as $all)
                                <td class="text-right">
                                    @if(isset($ledg->all_ded_la[$all]))
                                        {{number_format($ledg->all_ded_la[$all])}}
                                        @php
                                            $tot_allowance[$all] += $ledg->all_ded_la[$all];
                                        @endphp
                                    @else
                                        0
                                        @php
                                            $tot_allowance[$all] += 0;
                                        @endphp
                                    @endif
                                </td>
                            @endforeach
                            <!-- allowances -->
                            <td class="text-right">{{number_format($ledg->gross_salary)}}</td>
                            <td class="text-right">{{number_format($ledg->pf)}}</td>
                            <td class="text-right">{{number_format($ledg->npse+$ledg->nps_da_arrear)}}</td>
                            <td class="text-right">{{number_format($ledg->it)}}</td>
                            <!-- deductions -->
                            @foreach($deductions as $ded)
                                <td class="text-right">
                                    @if(isset($ledg->all_ded_la[$ded]))
                                        {{number_format($ledg->all_ded_la[$ded])}}
                                        @php
                                            $tot_deduction[$ded] += $ledg->all_ded_la[$ded];
                                        @endphp
                                    @else
                                        0
                                        @php
                                            $tot_deduction[$ded] += 0;
                                        @endphp
                                    @endif
                                </td>
                            @endforeach
                            <!-- deductions -->
                            <td class="text-right">{{number_format(($ledg->gross_salary+($ledg->total_salary-$ledg->net_salary))-$ledg->total_salary)}}</td>
                            <td class="text-right">{{number_format($ledg->net_salary)}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <td style="font-weight:bold">Total</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->basic_salary)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format(($led->totals->total_salary-$led->totals->net_salary)+$led->totals->da)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->hra)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->transport)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->misc)}}</td>
                    <!-- allowances -->
                    @foreach($allowances as $key=>$value)
                        <td class="text-right" style="font-weight:bold">
                            @if(array_key_exists($value, $tot_allowance))
                                {{number_format($tot_allowance[$value])}}
                            @else
                                0
                            @endif
                        </td>
                                
                    @endforeach
                    <!-- allowances -->

                    <td class="text-right" style="font-weight:bold">{{number_format(($led->totals->total_salary-$led->totals->net_salary)+$led->totals->gross_salary)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->pf)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->npse+$led->totals->nps_da_arrear)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->it)}}</td>

                    <!-- deductions -->
                    @foreach($deductions as $key=>$value)
                        <td class="text-right" style="font-weight:bold">
                            @if(array_key_exists($value, $tot_deduction))
                                {{number_format($tot_deduction[$value])}}
                            @else
                                0
                            @endif
                        </td>
                                
                    @endforeach
                    <!-- deductions -->
                    <td class="text-right" style="font-weight:bold">{{number_format(($led->totals->gross_salary+($led->totals->total_salary-$led->totals->net_salary))-$led->totals->total_salary)}}</td>
                    <td class="text-right" style="font-weight:bold">{{number_format($led->totals->total_salary)}}</td>
                </tfoot>
            </table>
        </div>
        <div class="page_break"></div>
    @endforeach
</body>
</html>

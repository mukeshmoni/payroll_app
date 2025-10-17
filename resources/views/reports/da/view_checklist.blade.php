<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DA Arrear Checklist</title>
</head>
<style>
    @page {
        size: landscape;
    }
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 5px;
        border:none;
        border-bottom:1px dashed black;
        border-top:1px dashed black;
    }
    .text-right{
        text-align:right;
    }
    table th{
        font-size:14px;
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
</style>
<body>
    <p style="text-align:right">DATE: {{date('d-m-Y')}}</p>
    <div class="text-center">
        <p class="fw-bold">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING & RESEARCH</p>
        <P class="fw-bold">TARAMANI, CHENNAI</P>
        <p class="fw-bold text-uppercase">ARREARS OF DA FROM {{$month}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
        <p>***************************************************************************</p>
    </div>
    <table class="table">
        @php
            $tot_da_due =[];
            $tot_da_drawn =[];
            $tot_da_arrear =[];
            $tot_tda_due =[];
            $tot_tda_drawn =[];
            $tot_tda_arrear =[];
            $tot_net_payable =[];
        @endphp
       @foreach($data as $emp)
            <tr>
                <td colspan="4"  style="text-transform:uppercase; @if($loop->iteration>1) border-top:1px solid white @endif">
                    NAME : {{$emp->empname}}
                    <br>
                    EMPID: {{$emp->employee}}
                </td>
                <td colspan="6" style="text-transform:uppercase; @if($loop->iteration>1) border-top:1px solid white @endif">
                    DESIGNATION : {{$emp->desg}}
                </td>
            </tr>
            <tr>
                <td>MONTH</td>
                <td>BASIC PAY</td>
                <td>DA DUE</td>
                <td>DA DRAWN</td>
                <td>DA BLNCE</td>
                <td>TP ALNCE</td>
                <td>TDA DUE</td>
                <td>TDA DRAWN</td>
                <td>TDA BLNCE</td>
                <td>NET PAYABLE</td>
            </tr>
            @php
                $da_month = json_decode($emp->da_month);
                $da_basic_salary = json_decode($emp->da_basic_salary);
                $da_due = json_decode($emp->da_due);
                $da_drawn = json_decode($emp->da_drawn);
                $da_arrear = json_decode($emp->da_arrear);
                if($emp->total_tda==0){
                    $tda_due = 0;
                    $tda_drawn = 0;
                    $tda_arrear = 0;
                }else{
                    $tda_due = json_decode($emp->tda_due);
                    $tda_drawn = json_decode($emp->tda_drawn);
                    $tda_arrear = json_decode($emp->tda_arrear);
                }
                $net_payable = 0;
            @endphp
            @foreach($da_month as $key=>$value)
                <tr>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif; text-transform:uppercase">{{date('F',strtotime($value))}}</td>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_basic_salary[$key])}}</td>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_due[$key])}}</td>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_drawn[$key])}}</td>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_arrear[$key])}}</td>
                    <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($emp->slab)}}</td>
                    @if($emp->total_tda==0)
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($emp->tda_due)}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($emp->tda_drawn)}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($emp->tda_arrear)}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_arrear[$key]+$emp->tda_arrear)}}</td>
                    @else
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($tda_due[$key])}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($tda_drawn[$key])}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($tda_arrear[$key])}}</td>
                        <td style="@if($loop->iteration>1)border-top:1px solid white @endif;">{{number_format($da_arrear[$key]+$tda_arrear[$key])}}</td>
                    @endif
                    @php
                        if($emp->total_tda==0){
                            $tda_due = $tda_due+$emp->tda_due;
                            $tda_drawn = $tda_drawn+$emp->tda_drawn;
                            $tda_arrear = $tda_arrear+$emp->tda_arrear;
                            $net_payable = $net_payable+($da_arrear[$key]+$emp->tda_arrear);
                        }
                    @endphp
                </tr>
            @endforeach
            <tr>
                @php
                    if($emp->total_tda!=0){
                        $tda_due = array_sum($tda_due);
                        $tda_drawn = array_sum($tda_drawn);
                        $tda_arrear = array_sum($tda_arrear);
                        $net_payable = array_sum($da_arrear)+$tda_arrear;
                    }
                @endphp
                <td colspan="2"></td>
                <td>{{number_format(array_sum($da_due))}}</td>
                <td>{{number_format(array_sum($da_drawn))}}</td>
                <td>{{number_format(array_sum($da_arrear))}}</td>
                <td>0.00</td>
                <td>{{number_format($tda_due)}}</td>
                <td>{{number_format($tda_drawn)}}</td>
                <td>{{number_format($tda_arrear)}}</td>
                <td>{{number_format($net_payable)}}</td>
                @php
                    array_push($tot_da_due,array_sum($da_due));
                    array_push($tot_da_drawn,array_sum($da_drawn));
                    array_push($tot_da_arrear,array_sum($da_arrear));
                    array_push($tot_tda_due,$tda_due);
                    array_push($tot_tda_drawn,$tda_drawn);
                    array_push($tot_tda_arrear,$tda_arrear);
                    array_push($tot_net_payable,$net_payable);
                @endphp
            </tr>
            <tr>
                <td colspan="10" style="border:none"></td>
            </tr>
       @endforeach
       <tr>
            <td colspan="10" style="border:none"></td>
        </tr>
        <tr>
            <td colspan="2">TOTAL</td>
            <td>{{number_format(array_sum($tot_da_due))}}</td>
            <td>{{number_format(array_sum($tot_da_drawn))}}</td>
            <td>{{number_format(array_sum($tot_da_arrear))}}</td>
            <td>0.00</td>
            <td>{{number_format(array_sum($tot_tda_due))}}</td>
            <td>{{number_format(array_sum($tot_tda_drawn))}}</td>
            <td>{{number_format(array_sum($tot_tda_arrear))}}</td>
            <td>{{number_format(array_sum($tot_net_payable))}}</td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DA Arrear Aquittance</title>
</head>
<style>
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
        <p class="fw-bold text-uppercase">AQUITTANCE FOR PAYMENT OF DA ARREARS FROM {{$month}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
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
        <tr>
            <td>EMP ID</td>
            <td>NAME</td>
            <td>DESIGNATION</td>
            <td style="text-align:right">
                NET PAYABLE
                <br>
                Rs.
            </td>
        </tr>
       @foreach($data as $emp)
            <tr>
                <td style="border-bottom:1px solid white;">
                    {{$emp->employee}}
                </td>
                <td style="border-bottom:1px solid white;text-transform:uppercase;">
                    {{$emp->empname}}
                </td>
                <td style="border-bottom:1px solid white;text-transform:uppercase;">
                    {{$emp->desg}}
                </td>
                @php
                    $da_month = json_decode($emp->da_month);
                    $da_arrear = json_decode($emp->da_arrear);

                    if($emp->total_tda==0){
                        $tda_arrear = 0;
                    }else{
                        $tda_arrear = json_decode($emp->tda_arrear);
                    }
                    $net_payable = 0;
                @endphp
                @foreach($da_month as $key=>$value)
                        @php
                            if($emp->total_tda==0){
                                $tda_arrear = $tda_arrear+$emp->tda_arrear;
                                $net_payable = $net_payable+($da_arrear[$key]+$emp->tda_arrear);
                            }
                        @endphp
                @endforeach
                @php
                    if($emp->total_tda!=0){
                        $tda_arrear = array_sum($tda_arrear);
                        $net_payable = array_sum($da_arrear)+$tda_arrear;
                    }
                    array_push($tot_net_payable,$net_payable);
                @endphp
                <td style="text-align:right;border-bottom:1px solid white;">{{number_format($net_payable)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" style="border:none"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td style="text-align:right">{{number_format(array_sum($tot_net_payable))}}</td>
        </tr>
    </table>
</body>
</html>

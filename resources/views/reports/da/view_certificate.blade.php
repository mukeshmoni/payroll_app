<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DA Arrear Certificate</title>
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
        <p class="fw-bold text-uppercase">ARREARS OF DA FROM {{$month}}</p>
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
            <td>RS.</td>
            <td>CHEQUE NO: </td>
            <td>CERTIFICATE</td>
        </tr>
       @foreach($data as $emp)
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
        @endforeach
        <tr>
            <td colspan="2" style="width:400px">
                NET PAYABLE : {{number_format(array_sum($tot_net_payable))}}
            </td>
            <td>
                <ol>
                    <li>Certified that the Dearness Allowance drawn in this bill are Central Government Rules sanctioned from time to time</li>
                    <li>Certified that the amount claimed now in this bill was not drawn previously</li>
                    <li>Certified that the DA claimed now in this bill is covered by proper sansaction</li>
                </ol>
                <p style="margin-left:40px">Passed for Rs.</p>
            </td>
        </tr>
    </table>
    <div id="footer" style="margin-top:400px">
        <table class="table">
            <tr>
                <td style="border:1px solid white;">Pay Bill Clerk</td>
                <td style="border:1px solid white;">Office Supdt</td>
                <td style="border:1px solid white;">Accountant</td>
                <td style="border:1px solid white;">Consult. Admin.</td>
                <td style="border:1px solid white;">Accounts Officer</td>
                <td style="border:1px solid white;">Director</td>
            </tr>
            <tr>
                <td colspan="3" style="border:none"></td>
            </tr>
            <tr>
                <td colspan="3" style="border:none"></td>
            </tr>
            <tr>
                <td style="border:1px solid white;">Chennai - 600 113</td>
                <td style="border:1px solid white;">DATE: </td>
            </tr>
        </table>
    </div>
</body>
</html>

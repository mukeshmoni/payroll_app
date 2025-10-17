<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate</title>
</head>
<style>
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 2px;
        border:none;
        font-size:13px;
        /* border-bottom:1px dashed black;
        border-top:1px dashed black; */
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
        ************************************************************************************
        <p class="fw-bold text-uppercase">PAYBILL FOR THE MONTH OF {{date('F Y',strtotime($month))}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
    </div>
    <table class="table">
        <tr>
            <td>EARNINGS:</td>
            <td style="text-align:center">RS.</td>
            <!-- <td>CHEQUE NO: </td> -->
            <td style="text-align:center">CERTIFICATE</td>
        </tr>
        <tr>
            <td style="width:180px;">PAY</td>
            <td style="text-align:right;">{{number_format($ledger->basic_salary,2)}}</td>
            <td rowspan="20" style="vertical-align: top;">
                <ol>
                    <li>Certified that the allowances drawn in this bill are Central Government Rules sanctioned from time to time</li>
                    <li>Certified that the amount claimed now in this bill was not drawn previously</li>
                    <li>Certified that the pay claimed now in this bill is covered by proper sansaction</li>
                    <li>Certified that the individuals for whom House Rent Allowance claimed in this bill, have not been provided with any other accomodation</li>
                </ol>
                <!-- <p style="margin-left:40px">Passed for Rs.</p> -->
            </td>
        </tr>
        <tr>
            <td>Dearner Allowance</td>
            <td style="text-align:right">{{number_format($ledger->da,2)}}</td>
        </tr>
        <tr>
            <td>House Rent Allowance</td>
            <td style="text-align:right">{{number_format($ledger->hra,2)}}</td>
        </tr>
        <tr>
            <td>Transport Allowance</td>
            <td style="text-align:right">{{number_format($ledger->transport,2)}}</td>
        </tr>
        @foreach($allowances_list as $key=>$value)
            <tr>
                <td style="text-transform:capitalize">{{$value}}</td>
                <td style="text-align:right;">
                    @if(array_key_exists($value, $allowances))
                        {{number_format($allowances[$value],2)}}
                    @else
                        0.00
                    @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <td>DA Arrears</td>
            <td style="text-align:right">{{number_format(($ledger->total_salary-$ledger->net_salary),2)}}</td>
        </tr>
        <tr>
            <td>Misc.</td>
            <td style="text-align:right">{{number_format($ledger->misc,2)}}</td>
        </tr>
        <tr>
            <td>Gross Total</td>
            <td style="text-align:right;border-top:1px dashed black;border-bottom:1px dashed black">{{number_format($ledger->gross_salary+($ledger->total_salary-$ledger->net_salary),2)}}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">DEDUCTIONS:</td>
        </tr>
        <tr>
            <td>Income Tax</td>
            <td style="text-align:right">{{number_format($ledger->it,2)}}</td>
        </tr>
        <tr>
            <td>PF Subscription</td>
            <td style="text-align:right">{{number_format($ledger->pf,2)}}</td>
        </tr>
        <tr>
            <td>N P S Subscription</td>
            <td style="text-align:right">{{number_format($ledger->npse+$ledger->nps_da_arrear,2)}}</td>
        </tr>
        @foreach($deductions_list as $ded)
            <tr>
                <td style="text-transform:capitalize">{{$ded}}</td>
                <td style="text-align:right;">
                    @if(isset($deductions[$ded]))
                        {{number_format($deductions[$ded],2)}}
                    @else
                        0
                    @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <td>Deduction Total</td>
            <td style="text-align:right;border-top:1px dashed black;border-bottom:1px dashed black">{{number_format(($ledger->gross_salary-$ledger->net_salary),2)}}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>NET PAYABLE</td>
            <td style="text-align:right;border-top:1px dashed black;border-bottom:1px dashed black">{{number_format(($ledger->total_salary),2)}}</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    
    <div id="footer">
        <table class="table">
            <tr>
                <td style="border:1px solid white;width:90px;">SSA(A2)</td>
                <td style="border:1px solid white;">SO GR.II(Accts)</td>
                <td style="border:1px solid white;">Consultants(Accts)</td>
                <td style="border:1px solid white;">SO GR.II(Admin)</td>
                <td style="border:1px solid white;">Sr.Admin.Officer</td>
                <td style="border:1px solid white;width:100px;text-right;">Director</td>
            </tr>
            <tr>
                <td colspan="6" style="border:none"></td>
            </tr>
            <tr>
                <td colspan="6" style="border:none"></td>
            </tr>
            <!-- <tr>
                <td style="border:1px solid white;width:130px;">Chennai - 600 113</td>
                <td style="border:1px solid white;">DATE: </td>
                <td colspan="4"></td>
            </tr> -->
        </table>
    </div>
</body>
</html>

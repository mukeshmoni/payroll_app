<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Tax Report</title>
</head>
<style>
    /* @page {
        size: landscape;
    } */
    body{
        font-size:10px;
    }
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 5px;
        border:0px solid black;
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
</style>
<body>
    <div class="text-center">
        <p class="">FORM NUMBER 21 (See Rule 32)</p>
        <P class="text-uppercase">MONTHLY RETURN OF INCOME CHARGABLE UNDER THE HEAD 'SALARIES' PAID AND TAX DEDUCTED AT SOURCE THERE ON FOR THE MONTH OF {{date('M Y',strtotime($month))}}</P>
        <p class="text-uppercase">({{$category}} STAFF)</p>
        <table class="table">
            <tbody style="">
                <tr>
                    <td rowspan="3">
                        Name & Address of the Employer <br>
                        DIRECTOR.
                        NATIONAL INSIT OF TECHNICAL TEACHERS TRAINING & RESEARCH, <br>
                        [SOUTHERN REGION],TARAMANI P.O., <br>
                        CHENNAI - 600 113.
                    </td>
                    <td>
                        <!-- Date on which the tax was deducted : -->
                    </td>
                    <td>
                        PAN No. : AAIAN7785Q 
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- Date of Remittance : -->
                    </td>
                    <td>
                        TAN No. : CHEN14590C
                    </td>
                </tr>
                <tr>
                    <td>Name of the bank in which remitted : SBI.</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <thead style="border-bottom:1px dashed black;">
                <th colspan="7" style="border-top:1px dashed black"></th>
                <tr>
                    <td class="text-center" style="font-size:10px" rowspan="3">SL.NO</td>
                    <td class="text-center" style="font-size:10px" rowspan="3">NAME OF THE EMPLOYEE</td>
                    <td class="text-center" style="font-size:10px" rowspan="3">IT-PAN</td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size:10px" colspan="2">SALARIES PAID OR DUE DURING THE MONTH (IN Rs.)</td>
                    <td class="text-center" style="font-size:10px" colspan="2">INCOME TAX (IN Rs.)</td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size:10px">PAY AND ALLOWANCES</td>
                    <td class="text-center" style="font-size:10px">OTHER INCOME CHARGEABLE UNDER THE HEAD 'SALARIES'</td>
                    <td class="text-center" style="font-size:10px">DURING THE MONTH</td>
                    <td class="text-center" style="font-size:10px">UPTO INCLUDING THE MONTH</td>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$emp->empname}}</td>
                        <td>{{$emp->emppanno}}</td>
                        <td class="text-right">{{number_format($emp->gross_salary,2)}}</td>
                        <td></td>
                        <td class="text-right">{{number_format($emp->it,2)}}</td>
                        <td class="text-right">{{number_format($emp->tot_it,2)}}</td>
                    </tr>
                @endforeach
                <tfoot style="border-top:1px dashed black;border-bottom:1px dashed black;">
                        <td colspan="5" class="text-right">TOTAL</td>
                        <td class="text-right">{{number_format($employees->sum('it'),2)}}</td>
                        <td class="text-right">{{number_format($employees->sum('tot_it'),2)}}</td>
                </tfoot>
            </tbody>
        </table>
        <p class="text-left">I, The DIRECTOR, being the officer responsible for paying the above salaries etc., do hereby declare that the particulars are correct.</p>
        <br>
        <br>
        <p class="text-right">DIRECTOR</p>
    </div>
    
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PF Report</title>
</head>
<style>
    body{
        font-size:12px;
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
        font-size:12px;
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
        <p class="fw-bold">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING & RESEARCH</p>
        <P class="fw-bold">TARAMANI, CHENNAI</P>
        <p>***************************************************************************</p>
        <p class="fw-bold text-uppercase">PF SUBSCRIPTION/RECOVERY REPORT FOR THE MONTH OF {{date("F Y",strtotime($month))}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
    </div>
    <table class="table">
        <thead class="bg-light">
            <th>S.No</th>
            <th>EMP ID</th>
            <th>GPF NO</th>
            <th>EMPLOYEE NAME</th>
            <th>DESIGNATION</th>
            <th>SALARY</th>
            <th>SUBSCRPN</th>
            <th>RECOVERY</th>
            <th>INSTALLMENT NO</th>
        </thead>
        @if(count($employees)>0)
            <tbody>
                @foreach($employees as $emp)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$emp->employee}}</td>
                        <td>{{$emp->gpfno}}</td>
                        <td>{{$emp->empname}}</td>
                        <td>{{$emp->desg}}</td>
                        <td class="text-right">{{number_format($emp->total_salary)}}</td>
                        <td class="text-right">{{number_format($emp->pf)}}</td>
                        <td class="text-right">{{number_format($emp->recovery)}}</td>
                        <td class="text-right">{{$emp->installment}}/{{$emp->tenure}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td colspan="5" class="text-right" style="font-weight:600;">TOTAL</td>
                <td class="text-right" style="font-weight:600;">{{number_format($employees->sum("total_salary"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($employees->sum("pf"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sum_recovery)}}</td>
                <td class="text-right" style="font-weight:600;">-</td>
            </tfoot>
            @else
            <tbody>
                <tr>
                    <td colspan="7" class="text-center">Select Category to view report</td>
                </tr>
            </tbody>
            @endif
    </table>
</body>
</html>

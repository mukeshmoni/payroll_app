<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Aquittance</title>
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
        border:1px solid black;
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
    <div class="text-center">
        <p class="fw-bold">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING & RESEARCH</p>
        <P class="fw-bold">TARAMANI, CHENNAI</P>
        <p>***************************************************************************</p>
        <p class="fw-bold text-uppercase">AQUITTANCE OF SALARY FOR THE MONTH OF {{date("F Y",strtotime($month))}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
    </div>
    <table class="table">
        <thead class="bg-light">
            <th>S.No</th>
            <th>Emp ID</th>
            <th>Name of the Employee</th>
            <th>Designation</th>
            <th>Basic Pay</th>
            <th>Gross Pay</th>
            <th>Total Dedn.</th>
            <th>Net Pay</th>
            <th>DA</th>
            <th>Total Salary</th>
        </thead>
        @if(count($payrolls)>0)
            <tbody>
                @foreach($payrolls as $payroll)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$payroll->employee}}</td>
                        <td>{{$payroll->empname}}</td>
                        <td>{{$payroll->desg}}</td>
                        <td class="text-right">{{number_format($payroll->basic_salary)}}</td>
                        <td class="text-right">{{number_format($payroll->gross_salary+($payroll->total_salary-$payroll->net_salary))}}</td>
                        <td class="text-right">{{number_format(($payroll->gross_salary+($payroll->total_salary-$payroll->net_salary))-$payroll->total_salary)}}</td>
                        <td class="text-right">{{number_format($payroll->net_salary)}}</td>
                        <td class="text-right">{{number_format($payroll->total_salary-$payroll->net_salary)}}</td>
                        <td class="text-right">{{number_format($payroll->total_salary)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td colspan="4" class="text-right" style="font-weight:600;">TOTAL</td>
                <td class="text-right" style="font-weight:600;">{{number_format($payrolls->sum("basic_salary"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sums["gross_salary"])}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sums["gross_salary"]-$sums["net_salary"])}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($payrolls->sum("net_salary"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sums["total_salary"]-$sums['net_salary'])}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sums["total_salary"])}}</td>
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

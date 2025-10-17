<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Society Report</title>
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
    <div class="text-left">
        <p>DATE {{date('d/m/Y')}}</p>
    </div>
    <div class="text-center">
        <p class="fw-bold text-uppercase">NITTTR EMPLOYEES CO-OP. THRIFT AND CREDIT SOCIETY LIMITED</p>
        <p class="fw-bold text-uppercase">RECOVERY OF THRIFT & LOAN AMOUNT FOR THE MONTH OF {{date("F Y",strtotime($month))}}</p>
        <p>----------------------------------------------------------------------------------------------------</p>
        <!-- <p class="fw-bold">NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING & RESEARCH</p>
        <P class="fw-bold">TARAMANI, CHENNAI</P>
        <p>***************************************************************************</p>
        <p class="fw-bold text-uppercase">PF SUBSCRIPTION/RECOVERY REPORT FOR THE MONTH OF {{date("F Y",strtotime($month))}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p> -->
    </div>
    <table class="table">
        <thead class="bg-light">
            <th>S.No</th>
            <th>EMP ID</th>
            <th>EMPLOYEE NAME</th>
            <th>DESIGNATION</th>
            <th>SOCIETY RECOVERY</th>
        </thead>
        @if(count($loans)>0)
            <tbody>
                @foreach($loans as $loan)
                    @if($loan)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$loan->employee}}</td>
                        <td>{{$loan->empname}}</td>
                        <td>{{$loan->desg_name}}</td>
                        <td class="text-right">{{number_format($loan->recovery)}}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td colspan="4" class="text-right" style="font-weight:600;">TOTAL</td>
                <td class="text-right" style="font-weight:600;">{{number_format($sum_recovery)}}</td>
            </tfoot>
            @else
            <tbody>
                <tr>
                    <td colspan="7" class="text-center">No Details to view report</td>
                </tr>
            </tbody>
            @endif
    </table>
</body>
</html>

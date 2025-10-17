<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NPS Report</title>
</head>
<style>
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
        <p class="fw-bold text-uppercase">NPS SUBSCRIPTION RECOVERY REPORT FOR THE MONTH OF {{date("F Y",strtotime($month))}}</p>
        <p class="fw-bold text-uppercase" style="font-size:14px">({{$category}} Staff)</p>
    </div>
    <table class="table">
        <thead class="bg-light">
            <th>S.No</th>
            <th>EMP ID</th>
            <th>EMPLOYEE NAME</th>
            <th>DESIGNATION</th>
            <th>SALARY</th>
            <th>SUBSN. AMOUNT</th>
            <th>EMPYR. SUBSN.</th>
        </thead>
        @if(count($employees)>0)
            <tbody>
                @foreach($employees as $emp)
                    @php
                        $da_arrear = 0;
                        $npse = 0;
                        $enpse = 0;
                        if($emp->prev_da){
                            $da_arrear = json_decode($emp->da_arrear);
                            $da_arrear = array_sum($da_arrear);
                            if($emp->npse!=0){
                                $npse = number_format(($da_arrear)*(10/100));
                                $emp->npse = $emp->npse+$npse;
                            }
                        }
                        if($emp->npse!=0){
                            $enpse = ($emp->basic_salary+$emp->da+$da_arrear)*(14/100);
                        }
                        $emp->npser = $enpse;
                        $emp->da_arrear = $da_arrear;
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$emp->employee}}</td>
                        <td>{{$emp->empname}}</td>
                        <td>{{$emp->desg}}</td>
                        <td class="text-right">{{number_format($emp->basic_salary+$emp->da+$da_arrear+$emp->total_tda)}}</td>
                        <td class="text-right">{{number_format($emp->npse)}}</td>
                        <td class="text-right">{{number_format($enpse)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td colspan="4" class="text-right" style="font-weight:600;">TOTAL</td>
                <td class="text-right" style="font-weight:600;">{{number_format($employees->sum("basic_salary")+$employees->sum("da")+$employees->sum("da_arrear")+$employees->sum("total_tda"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($employees->sum("npse"))}}</td>
                <td class="text-right" style="font-weight:600;">{{number_format($employees->sum("npser"))}}</td>
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

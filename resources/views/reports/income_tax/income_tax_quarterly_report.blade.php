<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Tax Quarterly Report</title>
</head>
<style>
    @page {
        size: landscape;
    }
    body{
        font-size:8px;
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
        font-size:8px;
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
    <div class="text-center">
    <table class="table">
            <thead>
                <tr>
                    <td class="text-center" style="font-size:12px;font-weight:bold;border:0px solid white" colspan="6">TAN: CHEN05217C <br> Details of tax deducted and paid to the credit of Central Government</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold;border:0px solid white" colspan="5">Please do not Cut/Copy/Paste [it may cause incosistency in data]</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:12px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:12px;font-weight:bold;border:0px solid white"></td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Sl.No</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">TDS(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Surcharge(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Education Cess(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Higher <br> Education <br> Cess(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Interest(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Other(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Fee(Rs.)</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Cheque/DD No.</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">BSR Code</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Date of which tax deposited</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Transfer Voucher/Challan No.</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Whether TDS deposited by book entry?</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">Minor Head</td>
                </tr>
                    <td class="text-center" style="font-size:12px;font-weight:bold">301</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">302</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">303</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">304</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">305</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">306</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">307</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold"></td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">308</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">309</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">310</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">311</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold">312</td>
                    <td class="text-center" style="font-size:12px;font-weight:bold"></td>
                </tr>
            </thead>
            <tbody>
            @foreach($incomeTaxes as $it)
                        <tr>
                            <td class="text-center" style="font-size:12px;">{{$loop->iteration}}</td>
                            <td class="text-center" style="font-size:12px;">{{$it->sum('it')}}</td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                            <td class="text-center" style="font-size:12px;"></td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <br>
        <p style="text-align:right;margin-top:10px;font-size:14px;">SR. ADMINISTRATIVE OFFICER</p>
        <div class="page_break"></div>
        <table class="table">
            <thead>
            <tr>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white" colspan="7">Please do not Cut/Copy/Paste [it may cause incosistency in data]</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Leave it blank</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold;border:0px solid white"></td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Sl.No</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Employee ID</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">PAN No</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Name of Employee</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Date of Payment / Credit</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Taxable amount on <br> which tax deducted <br>Rs.</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">TDS</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Surcharge</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Education Cess</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Higher <br> Education <br> Cess</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Total Tax Deducted <br>(319+320+321) Rs.</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Total Tax Deposited Rs.</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Date of deduction</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Challan Detail <br>[Sr No(BSR,Date,Challan No.)]</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">Reason for non- <br> deduction/lower<br>-deduction</td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size:8px;font-weight:bold">313</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">314</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">315</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">316</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">317</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">318</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">319</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">320</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">321</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">322</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">323</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">324</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">325</td>
                    <td class="text-center" style="font-size:8px;font-weight:bold"></td>
                    <td class="text-center" style="font-size:8px;font-weight:bold">329</td>
                </tr>
            </thead>
            <tbody>
            @foreach($incomeTaxes as $it)
                    @foreach($it as $data)
                        <tr>
                            <td class="text-center" style="font-size:8px;">{{$loop->iteration}}</td>
                            <td class="text-center" style="font-size:8px;">{{$data->employee}}</td>
                            <td class="text-center text-uppercase" style="font-size:8px;">{{$data->emppanno}}</td>
                            <td class="text-center text-uppercase" style="font-size:8px;">{{$data->empname}}</td>
                            <td class="text-center" style="font-size:8px;">{{date("d-m-Y",strtotime($data->date_of_payment))}}</td>
                            <td class="text-center" style="font-size:8px;">{{$data->gross_salary}}</td>
                            <td class="text-center" style="font-size:8px;">{{$data->it}}</td>
                            <td class="text-center" style="font-size:8px;"></td>
                            <td class="text-center" style="font-size:8px;"></td>
                            <td class="text-center" style="font-size:8px;"></td>
                            <td class="text-center" style="font-size:8px;"></td>
                            <td class="text-center" style="font-size:8px;">{{$data->it}}</td>
                            <td class="text-center" style="font-size:8px;">{{date("d-m-Y",strtotime($data->date_of_payment))}}</td>
                            <td class="text-center" style="font-size:8px;"></td>
                            <td class="text-center" style="font-size:8px;"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="15" style="border:0px solid white"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</body>
</html>

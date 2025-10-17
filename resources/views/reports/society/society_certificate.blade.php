<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Society Certificate</title>
</head>
<style>
    .table{
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 5px;
        border:none;
    }
    .text-right{
        text-align:right;
    }
    .text-left{
        text-align:left;
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
@php
function numberToWords($number) {
    $words = array(
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );

    $tens = array(
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    );

    $suffixes = array(
        10000000 => 'crore',
        100000 => 'lakh',
        1000 => 'thousand',
        100 => 'hundred'
    );

    $result = '';

    foreach ($suffixes as $suffix => $suffixText) {
        if ($number >= $suffix) {
            $divisor = floor($number / $suffix);
            $result .= numberToWords($divisor) . ' ' . $suffixText;
            $number %= $suffix;
            if ($number > 0) {
                $result .= ' ';
            }
        }
    }

    if ($number < 20) {
        $result .= $words[$number];
    } else {
        $result .= $tens[floor($number / 10)];
        $result .= ($number % 10 !== 0 ? '-' . $words[$number % 10] : '');
    }

    return $result;
}
@endphp
<body>
    <div class="text-center">
        <p class="fw-bold">PAY CERTIFICATE OF THE APPLICANT</p>
        <P class="text-left">Certified that {{strtoupper($emp_payslip->empname)}} as {{strtoupper($emp_payslip->desg)}} in the office of NITTTR CHENNAI-113 is drawing the following pay and allowances as on date. The deductions are furnished</P>
        <P class="text-left">And also undertaken to recover the dues as and when demanded by the society</P>
        <P class="text-left">The details of Pay and allowances drawn by the individual are furnished below:</P>

        <table class="table">
            <tr>
                <td style="vertical-align: top;" colspan="2">
                    <table class="table">
                        <thead style="border-top:1px dashed black;border-bottom:1px dashed black">
                            <th>Earnings</th>
                            <th class="text-right">Rs.</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BASIC-PAY</td>
                                <td class="text-right">{{number_format($emp_payslip->basic_salary,2)}}</td>
                            </tr>
                            <tr>
                                <td>DA</td>
                                <td class="text-right">{{number_format($emp_payslip->da,2)}}</td>
                            </tr>
                            <tr>
                                <td>HRA</td>
                                <td class="text-right">{{number_format($emp_payslip->hra,2)}}</td>
                            </tr>
                            <tr>
                                <td>TRANSP-ALNCE</td>
                                <td class="text-right">{{number_format($emp_payslip->transport,2)}}</td>
                            </tr>
                            @php
                                $allArr = json_decode($emp_payslip->allowances);   
                            @endphp
                            @foreach($allArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($allowances as $allowance)
                                            @if($allowance->id == $key)
                                                {{$allowance->allowance_type_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top;" colspan="2">
                    <table class="table">
                        <thead style="border-top:1px dashed black;border-bottom:1px dashed black">
                            <th>Deductions</th>
                            <th class="text-right">Rs.</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INCOME TAX</td>
                                <td class="text-right">{{number_format($emp_payslip->it,2)}}</td>
                            </tr>
                            <tr>
                                <td>PF-SUBSN</td>
                                <td class="text-right">{{number_format($emp_payslip->pf,2)}}</td>
                            </tr>
                            <tr>
                                <td>NPS</td>
                                <td class="text-right">{{number_format($emp_payslip->npse,2)}}</td>
                            </tr>
                            @php
                                $dedArr = json_decode($emp_payslip->deductions);   
                            @endphp
                            @foreach($dedArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($deductions as $deduction)
                                            @if($deduction->id == $key)
                                                {{$deduction->deduction_type_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach

                            @php
                                $laArr = json_decode($emp_payslip->la);   
                            @endphp
                            @foreach($laArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($la_emp as $item)
                                            @if($item->id == $key)
                                                {{$item->da_types}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tfoot style="border-top:1px dashed">
                <tr>
                    <td>GROSS PAY :</td>
                    <td class="text-right">{{number_format($emp_payslip->gross_salary,2)}}</td>
                    <td>TOTAL DEDUCTION :</td>
                    <td class="text-right">{{number_format($emp_payslip->gross_salary-$emp_payslip->total_salary,2)}}</td>
                </tr>
            </tfoot>
        </table>
        <p class="text-left">NET PAY =>Rs. <b>{{number_format($emp_payslip->total_salary,2)}}</b></p>
        <p class="text-left text-uppercase">{{numberToWords($emp_payslip->total_salary)}}</p>
    </div>
    <div class="text-center">
        <p class="fw-bold">PAY CERTIFICATE OF THE SURETY</p>
        <P class="text-left">Certified that {{strtoupper($surety_payslip->empname)}} employed as {{strtoupper($surety_payslip->desg)}} in the office of NITTTR CHENNAI-113 is drawing the following pay and allowances as on date. The details of the deductions are also</P>
        <table class="table">
            <tr>
                <td style="vertical-align: top;" colspan="2">
                    <table class="table">
                        <thead style="border-top:1px dashed black;border-bottom:1px dashed black">
                            <th>Earnings</th>
                            <th class="text-right">Rs.</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BASIC-PAY</td>
                                <td class="text-right">{{number_format($surety_payslip->basic_salary,2)}}</td>
                            </tr>
                            <tr>
                                <td>DA</td>
                                <td class="text-right">{{number_format($surety_payslip->da,2)}}</td>
                            </tr>
                            <tr>
                                <td>HRA</td>
                                <td class="text-right">{{number_format($surety_payslip->hra,2)}}</td>
                            </tr>
                            <tr>
                                <td>TRANSP-ALNCE</td>
                                <td class="text-right">{{number_format($surety_payslip->transport,2)}}</td>
                            </tr>
                            @php
                                $allArr = json_decode($surety_payslip->allowances);   
                            @endphp
                            @foreach($allArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($allowances as $allowance)
                                            @if($allowance->id == $key)
                                                {{$allowance->allowance_type_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top;" colspan="2">
                    <table class="table">
                        <thead style="border-top:1px dashed black;border-bottom:1px dashed black">
                            <th>Deductions</th>
                            <th class="text-right">Rs.</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INCOME TAX</td>
                                <td class="text-right">{{number_format($surety_payslip->it,2)}}</td>
                            </tr>
                            <tr>
                                <td>PF-SUBSN</td>
                                <td class="text-right">{{number_format($surety_payslip->pf,2)}}</td>
                            </tr>
                            <tr>
                                <td>NPS</td>
                                <td class="text-right">{{number_format($surety_payslip->npse,2)}}</td>
                            </tr>
                            @php
                                $dedArr = json_decode($surety_payslip->deductions);   
                            @endphp
                            @foreach($dedArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($deductions as $deduction)
                                            @if($deduction->id == $key)
                                                {{$deduction->deduction_type_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach

                            @php
                                $laArr = json_decode($surety_payslip->la);   
                            @endphp
                            @foreach($laArr as $key=>$value)
                                <tr>
                                    <td class="text-uppercase">
                                        @foreach($la_emp as $item)
                                            @if($item->id == $key)
                                                {{$item->da_types}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{number_format($value,2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tfoot style="border-top:1px dashed">
                <tr>
                    <td>GROSS PAY :</td>
                    <td class="text-right">{{number_format($surety_payslip->gross_salary,2)}}</td>
                    <td>TOTAL DEDUCTION :</td>
                    <td class="text-right">{{number_format($surety_payslip->gross_salary-$surety_payslip->total_salary,2)}}</td>
                </tr>
            </tfoot>
        </table>
        <p class="text-left">NET PAY =>Rs. <b>{{number_format($surety_payslip->total_salary,2)}}</b></p>
        <p class="text-left text-uppercase">{{numberToWords($surety_payslip->total_salary)}}</p>
    </div>
    
</body>
</html>

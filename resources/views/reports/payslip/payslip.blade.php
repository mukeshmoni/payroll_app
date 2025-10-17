<style>
    body {
        padding: 10px;
        margin: auto;
        font-family: 'Libre Franklin', sans-serif;
        font-size: 14px;
        line-height: 20px;
        color: #122136;

    }

    p {
        padding: 0;
        margin: 5px 0px 5px 0px;
    }

    h2 {
        font-size: 18px;
        text-transform: uppercase;
        font-weight: bold;
        color: #122136;
        padding: 15px 0px 0px 0px;
    }

    h3 {
        font-size: 16px;
        font-weight: bold;
        color: #122136;
        padding: 20px 0px 5px 0px;
        font-family: Arial, sans-serif;
    }

    h4 {
        font-size: 14px;
        font-weight: bold;
        color: #122136;
        padding: 10px 0px 5px 0px;
        font-family: Arial, sans-serif;
    }

    table.table-border {
        width: 100%;
        margin: 0px 0px 5px 0px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .pb-10 {
        padding-bottom: 10px;
    }

    .pb-20 {
        padding-bottom: 20px;
    }

    .p-5 {
        padding: 5px;
    }

    table.table-border,
    table.table-border th,
    table.table-border td {
        border: 1px solid #122136;
        border-collapse: collapse;
        font-size: 11px;
    }

    table.table-border tr th {
        background-color: #122136;
        color: #ffffff;
        font-weight: bold;
        text-transform: uppercase;
    }

    table.table-border th,
    table.table-border td {
        padding: 5px
    }

    table.table-border tr:nth-child(even) {
        background: #F7F7F7;
    }

    table.table-border tr:nth-child(odd) {
        background: #FFF;
    }

    .f-600 {
        font-weight: 600;
    }

    .bg-grey {
        background: #eee;
    }

    .bg-white {
        background: #FFF;
    }

    .text-caps {
        text-transform: uppercase;
    }
</style>
@php
function convertToWords($number) {
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '', '1' => 'one', '2' => 'two', '3' => 'three',
        '4' => 'four', '5' => 'five', '6' => 'six', '7' => 'seven',
        '8' => 'eight', '9' => 'nine', '10' => 'ten', '11' => 'eleven',
        '12' => 'twelve', '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy', '80' => 'eighty', '90' => 'ninety'
    );
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred :
                $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }
    }
    $str = array_reverse($str);
    $result = implode('', $str);

    if ($point) {
        $result .= "and " . $words[$point / 10] . " " . $words[$point = $point % 10] . " paise";
    }

    return $result;
}
@endphp
<div class="d-flex gap-10" style="text-align:center"><img src="{{ public_path('images/logo.png') }}" width="80px"
        height="80px" class="mr-2" alt="logo" /></div>
<h4 style="text-align: center;padding:0px;"><b>NATIONAL INSTITUTE OF TECHNICAL TEACHERS TRAINING AND RESEARCH (NITTTR), <br> MINISTRY OF EDUCATION, GOVT OF INDIA, TARAMANI, CHENNAI-600113.<b></h4>
<!-- <p style="text-align: center;">*****************</p> -->
<table cellpadding="0" cellspacing="0" width="100%" class="pb-20">
    <tr class="bg-grey">
        <td class="text-caps" style="text-align:center"><b>PAYSLIP FOR THE MONTH OF {{ date("F-Y",strtotime($month)) }}<b></td>
    </tr>
</table>
<?php //if (request()->ticket_list) {
// dd($structure);
?>
<table class="table-border">
    <tr>
        <td class="bg-grey"><b>NAME</b></td>
        <td colspan="3" style="font-size: 13px;" class="text-caps"><b>{{ $structure['empname'] }}</b></td>
    </tr>
    <tr>
        <td class="bg-grey"><b><?php echo 'DESIGNATION'; ?><b> </td>
        <td class="bg-white text-caps">{{ $structure['desg'] }}</b> </td>
        <td class="bg-grey"><b><?php echo 'DEPARTMENT'; ?><b> </td>
        <td class="bg-white text-caps">{{ $structure['dept'] }}</b> </td>

    </tr>
    <tr>

        <td class="bg-grey"><b><?php echo 'EMP.ID.NO'; ?></b> </td>
        <td class="bg-white">{{ $structure['employee'] }}</td>
        <td class="bg-grey"><b><?php echo 'PAN NO'; ?></b> </td>
        <td class="bg-white text-caps">{{ $structure['emppanno'] }} </td>
    </tr>
    <tr>
        <td class="bg-grey"><b><?php echo 'DOB'; ?><b> </td>
        <td class="bg-white">{{ date("d-m-Y",strtotime($structure['empdob'])) }}</b> </td>
        <td class="bg-grey"><b><?php echo 'DOJ'; ?></b> </td>
        <td class="bg-white">{{ date("d-m-Y",strtotime($structure['empdoj'])) }} </td>
    </tr>
    <tr>
        <td class="bg-grey"><b><?php echo 'PAY LEVEL'; ?><b> </td>
        <td class="bg-white">{{ $structure['payscallvl'] }}</b> </td>
        <td class="bg-grey"><b><?php echo 'GPF/NPS NO'; ?><b> </td>
        @if($structure['npsno'])
        <td class="bg-white">NPS-{{ $structure['npsno'] }}</b> </td>
        @elseif($structure['gpfno'])
        <td class="bg-white">GPF-{{ $structure['gpfno'] }}</b> </td>
        @else
        <td class="bg-white">-</b> </td>
        @endif
    </tr>
</table>
<br />
<table class="table-border" style="padding:0px;">
    <tr>
        <td colspan="2" style="vertical-align:top;padding:0px;margin:0px;">
            <table class="table-border" style="padding:0px;margin:0px;border:0px solid #fff;">
                <tr>
                    <th>Earnings</th>
                    <th>Amount (Rs.)</th>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td style="text-align:right">{{ number_format($structure->basic_salary) }}</td>
                </tr>
                <tr>
                    <td>DA Allowance({{ $structure->da_perc }}%)</td>
                    <td style="text-align:right">{{ number_format($structure->da) }}</td>
                </tr>
                <tr>
                    <td>HRA Allowance(27%)</td>
                    <td style="text-align:right">{{ number_format($structure->hra) }}</td>
                </tr>
                <tr>
                    <td>Transport Allowance</td>
                    <td style="text-align:right">{{ number_format($structure->transport) }}</td>
                </tr>
                <tr>  
                    <td>Misc. Allowance</td>
                    <td style="text-align:right">{{ number_format($structure->misc) }}</td>
                </tr>
                @php
                    $tr_allow = 5;
                    $tr_ded = 3;
                    // allowances 
                    $allArr = json_decode($structure->allowances);
                    $additional_allowance = 0;
                    if ($allArr){
                        foreach ($allArr as $key => $value){
                            $tr_allow++;
                            $additional_allowance+=$value;
                        }
                    }

                   // loans and advances 
                    $laArr = json_decode($structure->la);
                    $total_loan = 0;
                    if($laArr){
                        foreach($laArr as $key => $value){
                            $tr_ded++;
                            $total_loan=$total_loan+$value;
                        }
                    }

                    // deductions 
                    $dedArr = json_decode($structure->deductions);
                    $additional_deduction = 0;
                    if($dedArr){
                        foreach ($dedArr as $key => $value){
                            $tr_ded++;
                            $additional_deduction+=$value;
                        }
                    }
                    //dd($tr_allow,$tr_ded);
                @endphp
                @if($additional_allowance>0)
                @foreach($allArr as $key=>$value)
                    <tr>
                        <td>
                            @foreach($allowances as $allowance)
                                @if($allowance->id == $key)
                                    {{$allowance->allowance_type_name}}
                                @endif
                            @endforeach
                        </td>
                        <td style="text-align:right">{{number_format($value)}}</td>
                    </tr>
                @endforeach
                @endif
                @if($structure->da_arrear)
                    @php
                        $total_da = 0;
                        $da_arrear = json_decode($structure->da_arrear);
                        $tr_allow++;
                    @endphp
                    @foreach($da_arrear as $da)
                        @php
                            $total_da = $total_da+$da;
                        @endphp
                    @endforeach
                    <tr> 
                        <td>DA Allowance (Arrear)</td>
                        <td style="text-align:right">{{ number_format($total_da+$structure->total_tda) }}</td>
                    </tr>
                @endif
                @if($tr_allow<$tr_ded)
                    @php
                        $count = $tr_ded-$tr_allow;
                    @endphp
                    @for($i=0;$i<$count;$i++)
                    <tr> 
                        <td></td>
                        <td style="text-align:right">-</td>
                    </tr>
                    @endfor
                @endif
            </table>
        </td>
        <td colspan="2" style="vertical-align:top;padding:0px;margin:0px;">
            <table class="table-border" style="padding:0px;margin:0px;border:0px solid #fff;">
                <tr>
                    <th>Deductions</th>
                    <th>Amount (Rs.)</th>
                </tr>
                <tr> 
                    <td>Provident fund</td>
                    <td style="text-align:right">{{ number_format($structure->pf) }}</td>
                </tr>
                <tr>
                    <td>NPS-Employee</td>
                    <td style="text-align:right">{{ number_format($structure->npse+$structure->nps_da_arrear) }}</td>
                </tr>
                <tr>
                    <td>Income Tax</td>
                    <td style="text-align:right">{{ number_format($structure->it) }}</td>
                </tr>
                @if($total_loan>0)
                    @foreach($laArr as $key=>$value)
                        <tr>
                            <td>
                                @foreach($la as $item)
                                    @if($item->id == $key)
                                        {{$item->da_types}}
                                    @endif
                                @endforeach
                            </td>
                            <td style="text-align:right">{{number_format($value)}}</td>
                        </tr>
                    @endforeach
                @endif
                @if($additional_deduction>0)
                    @foreach($dedArr as $key=>$value)
                        <tr>
                            <td>
                                @foreach($deductions as $deduction)
                                    @if($deduction->id == $key)
                                        {{$deduction->deduction_type_name}}
                                    @endif
                                @endforeach
                            </td>
                            <td style="text-align:right">{{number_format($value)}}</td>
                        </tr>
                    @endforeach
                @endif
                @if($tr_ded<$tr_allow)
                    @php
                        $count = $tr_allow-$tr_ded;
                    @endphp
                    @for($i=0;$i<$count;$i++)
                    <tr> 
                        <td></td>
                        <td style="text-align:right">-</td>
                    </tr>
                    @endfor
                @endif
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><strong>Gross Salary</strong></td>
        <td style="text-align:right" colspan="2">{{ number_format($structure->gross_salary+($structure->total_salary-$structure->net_salary)) }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Total Deductions</strong></td>
        <td style="text-align:right" colspan="2">{{ number_format(($structure->gross_salary+($structure->total_salary-$structure->net_salary))-$structure->total_salary) }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>NET PAY</strong></td>
        <td style="text-align:right" colspan="2">{{ number_format($structure->total_salary) }}</td>
    </tr>
</table>
 @if($structure->narration)
    <p style="text-transform:italic">{{$structure->narration}}</p>
 @endif

 <p style="text-weight:bold">(Rupees {{convertToWords($structure->total_salary)}} Only)</p>
 <p style="text-align:center">**** This is a system generated payslip ****</p>
<?php //}
?>

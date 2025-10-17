@extends('layouts.app')

@section('content')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <script src="
https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js
"></script>
    <div class="employees-list shadow-md p-2 rounded bg-white">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">Pay Ledger</h4>
            <div>
                @if($month && $empid)
                    <a href="{{route('reports.exportLedger',['month'=>$month,'id'=>$empid])}}" class="btn btn-dark rounded" target="_blank">Export PDF</a>
                @else
                    <button type="button" class="btn btn-dark " target="_blank">Export PDF</button>
                @endif
                <button type="button" onclick="export_excel()" class="btn btn-success rounded">Export Excel</button>
            </div>
        </div>
        @if (session()->has('status'))
            @if (session('status'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @else
                <div class="alert alert-danger">{{ session('message') }}</div>
            @endif
        @endif
        <form action="{{ route('reports.getLedger') }}" method="POST" id="getLedger">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="month">Select month to view ledger</label>
                        <div class="input-group mb-3">
                            <input type="text" id="month" name="month" class="form-control" aria-describedby="basic-addon2"
                                value="{{ date('F-Y',strtotime($month)) }}" readonly required placeholder="MM/YY">
                            <label class="input-group-text m-0" id="basic-addon2" for="month"><i class="icon-clock menu-icon"
                                    style="font-size:16px"></i></label>
                        </div>
                        
                </div>
                <div class="form-group col-md-4">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select name="employee" id="employee" class="form-control @error('employee') is-invalid @enderror" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{$employee->empid}}" @if($empid== $employee->empid)? selected :''@endif>{{$employee->empid."-".$employee->empname}}</option>
                        @endforeach
                    </select>
                    <div class="alert text-danger errorTxt" style="display: none" id="employee_err"></div>
                    @error('employee')
                        <div class="alert text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 flex align-items-center">
                    <label for=""></label>
                    <button type="submit" class="btn btn-primary">Get Ledger</button>
                </div>
            </div>
        </form>
        <div class="aquitanceReport mt-2">
            @if($empDet)
            <table class="table payledger d-none">
                <tr>
                    <th>Employee Name</th>
                    <td>: {{$empDet->empname}}</td>
                    
                    <th>Pay Range</th>
                    <td type="string">: {{$empDet->emppayscale}}</td>
                    
                    <th>Quarters No</th>
                    <td type="string">: {{($empDet->quarters=="yes")?$empDet->quartersno:''}}</td>

                    <th>Date of Birth</th>
                    <td>: {{date("d/m/Y",strtotime($empDet->empdob))}}</td>
                </tr>
                <tr>
                    <th>Designation</th>
                    <td>: {{$empDet->designation_name}}</td>
                    
                    <th>Rate of Pay</th>
                    <td type="string">: {{$empDet->emppay}}</td>

                    <th>Date Occupied</th>
                    <td>: {{($empDet->quarters=="yes")?$empDet->doccupied:''}}</td>

                    <th>Joined Date</th>
                    <td>: {{$empDet->empdoj?date("d/m/Y",strtotime($empDet->empdoj)):'-'}}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>: {{$empDet->department_name}}</td>

                    <th>PF/NPS Number</th>
                    <td type="string">: {{$empDet->gpfno."/".$empDet->npsno}}</td>

                    <th>Date Vacated</th>
                    @if($empDet->dovacated!="1970-01-01")
                    <td>: {{($empDet->quarters=="yes")?$empDet->dovacated:''}}</td>
                    @else
                    <td>: -</td>
                    @endif

                    <th>IT-PAN</th>
                    <td>: {{$empDet->emppanno}}</td>
                </tr>
                <tr>
                    <th>Reference No</th>
                    <td type="string">: {{$empDet->empid}}</td>
                </tr>
                <tr class="bg-primary text-light">
                    @foreach($columns as $column)
                        <th class="text-uppercase">{{str_replace("_"," ",$column)}}</th>
                    @endforeach
                </tr>
                <tbody>
                @php
                    $tot_allowance = array_fill_keys($allowances, 0);
                    $tot_deduction = array_fill_keys($deductions, 0);
                    $tot_la = [];
                    $da_col_1 = count($allowances)+3;
                    $da_col_2 = count($deductions)+4;
                @endphp
                @foreach($ledger as $led)
                    @if($led->prev_da)
                        <tr>
                            <td>{{date("M Y",strtotime($led->month))}}</td>
                            <td>DA Arrears</td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                            <td colspan="{{$da_col_1}}" class="text-uppercase">DA ARREARS FROM {{date("M'y",strtotime(json_decode($led->da_month)[0]))}} to {{date("M'y",strtotime(json_decode($led->da_month)[count(json_decode($led->da_month))-1]))}}({{$led->prev_da."% to ".$led->da_perc."%"}})</td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                            <td colspan="{{$da_col_2}}"></td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{date("M Y",strtotime($led->month))}}</td>
                        <td class="text-right">{{number_format($led->basic_salary)}}</td>
                        <td class="text-right">{{number_format($led->da)}}</td>
                        <td class="text-right">{{number_format($led->hra)}}</td>
                        <td class="text-right">{{number_format($led->transport)}}</td>
                        <td class="text-right">{{number_format($led->misc)}}</td>
                        <!-- allowances -->
                        @foreach($allowances as $all)
                            <td class="text-right">
                                @if(isset($led->all_ded_la[$all]))
                                    {{number_format($led->all_ded_la[$all])}}
                                    @php
                                        $tot_allowance[$all] += $led->all_ded_la[$all];
                                    @endphp
                                @else
                                    0
                                    @php
                                        $tot_allowance[$all] += 0;
                                    @endphp
                                @endif
                            </td>
                        @endforeach
                        <!-- allowances -->
                        <td class="text-right">{{number_format($led->gross_salary)}}</td>
                        <td class="text-right">{{number_format($led->pf)}}</td>
                        <td class="text-right">{{number_format($led->npse+$led->nps_da_arrear)}}</td>
                        <td class="text-right">{{number_format($led->it)}}</td>
                        <!-- deductions -->
                        @foreach($deductions as $ded)
                            <td class="text-right">
                                @if(isset($led->all_ded_la[$ded]))
                                    {{number_format($led->all_ded_la[$ded])}}
                                    @php
                                        $tot_deduction[$ded] += $led->all_ded_la[$ded];
                                    @endphp
                                @else
                                    0
                                    @php
                                        $tot_deduction[$ded] += 0;
                                    @endphp
                                @endif
                            </td>
                        @endforeach
                        <!-- deductions -->
                        <td class="text-right">{{number_format(($led->gross_salary+($led->total_salary-$led->net_salary))-$led->total_salary)}}</td>
                        <td class="text-right">{{number_format($led->net_salary)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td style="font-weight:bold">Total</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->basic_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format(($totals->total_salary-$totals->net_salary)+$totals->da)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->hra)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->transport)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->misc)}}</td>
                <!-- allowances -->
                @foreach($allowances as $key=>$value)
                    <td class="text-right" style="font-weight:bold">
                        @if(array_key_exists($value, $tot_allowance))
                            {{number_format($tot_allowance[$value])}}
                        @else
                            0
                        @endif
                    </td>
                            
                @endforeach
                <!-- allowances -->

                <td class="text-right" style="font-weight:bold">{{number_format(($totals->total_salary-$totals->net_salary)+$totals->gross_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->pf)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->npse+$totals->nps_da_arrear)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->it)}}</td>

                <!-- deductions -->
                @foreach($deductions as $key=>$value)
                    <td class="text-right" style="font-weight:bold">
                        @if(array_key_exists($value, $tot_deduction))
                            {{number_format($tot_deduction[$value])}}
                        @else
                            0
                        @endif
                    </td>
                            
                @endforeach
                <!-- deductions -->
                <td class="text-right" style="font-weight:bold">{{number_format(($totals->gross_salary+($totals->total_salary-$totals->net_salary))-$totals->total_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->total_salary)}}</td>
            </tfoot>
            </table>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <table class="table table-borderless">
                            <tr>
                                <td>Employee Name</td>
                                <td>: {{$empDet->empname}}</td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td>: {{$empDet->designation_name}}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>: {{$empDet->department_name}}</td>
                            </tr>
                            <tr>
                                <td>Reference No</td>
                                <td type="string">: {{$empDet->empid}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <table class="table table-borderless">
                            <tr>
                                <td>Pay Range</td>
                                <td type="string">: {{$empDet->emppayscale}}</td>
                            </tr>
                            <tr>
                                <td>Rate of Pay</td>
                                <td type="string">: {{$empDet->emppay}}</td>
                            </tr>
                            <tr>
                                <td>PF/NPS Number</td>
                                <td type="string">: {{$empDet->gpfno."/".$empDet->npsno}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <table class="table table-borderless">
                            <tr>
                                <td>Quarters No</td>
                                <td type="string">: {{($empDet->quarters=="yes")?$empDet->quartersno:''}}</td>
                            </tr>
                            <tr>
                                <td>Date Occupied</td>
                                <td>: {{($empDet->quarters=="yes")?$empDet->doccupied:''}}</td>
                            </tr>
                            <tr>
                                <td>Date Vacated</td>
                                <td>: {{($empDet->quarters=="yes")?$empDet->dovacated:''}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <table class="table table-borderless">
                            <tr>
                                <td>Date of Birth</td>
                                <td>: {{date("d/m/Y",strtotime($empDet->empdob))}}</td>
                            </tr>
                            <tr>
                                <td>Joined Date</td>
                                <td>: {{date("d/m/Y",strtotime($empDet->empdoj))}}</td>
                            </tr>
                            <tr>
                                <td>IT-PAN</td>
                                <td type="string">: {{$empDet->emppanno}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-hover">
                        <thead class="bg-primary text-light">
                        @foreach($columns as $column)
                            <th class="text-uppercase">{{str_replace("_"," ",$column)}}</th>
                        @endforeach
                        </thead>
                        <tbody>
                @php
                    $tot_allowance = array_fill_keys($allowances, 0);
                    $tot_deduction = array_fill_keys($deductions, 0);
                    $tot_la = [];
                    $da_col_1 = count($allowances)+3;
                    $da_col_2 = count($deductions)+4;
                @endphp
                @foreach($ledger as $led)
                    @if($led->prev_da)
                        <tr>
                            <td>{{date("M Y",strtotime($led->month))}}</td>
                            <td>DA Arrears</td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                            <td colspan="{{$da_col_1}}" class="text-uppercase">DA ARREARS FROM {{date("M'y",strtotime(json_decode($led->da_month)[0]))}} to {{date("M'y",strtotime(json_decode($led->da_month)[count(json_decode($led->da_month))-1]))}}({{$led->prev_da."% to ".$led->da_perc."%"}})</td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                            <td colspan="{{$da_col_2}}"></td>
                            <td class="text-right">{{array_sum(json_decode($led->da_arrear))+$led->total_tda}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{date("M Y",strtotime($led->month))}}</td>
                        <td class="text-right">{{number_format($led->basic_salary)}}</td>
                        <td class="text-right">{{number_format($led->da)}}</td>
                        <td class="text-right">{{number_format($led->hra)}}</td>
                        <td class="text-right">{{number_format($led->transport)}}</td>
                        <td class="text-right">{{number_format($led->misc)}}</td>
                        <!-- allowances -->
                        @foreach($allowances as $all)
                            <td class="text-right">
                                @if(isset($led->all_ded_la[$all]))
                                    {{number_format($led->all_ded_la[$all])}}
                                    @php
                                        $tot_allowance[$all] += $led->all_ded_la[$all];
                                    @endphp
                                @else
                                    0
                                    @php
                                        $tot_allowance[$all] += 0;
                                    @endphp
                                @endif
                            </td>
                        @endforeach
                        <!-- allowances -->
                        <td class="text-right">{{number_format($led->gross_salary)}}</td>
                        <td class="text-right">{{number_format($led->pf)}}</td>
                        <td class="text-right">{{number_format($led->npse+$led->nps_da_arrear)}}</td>
                        <td class="text-right">{{number_format($led->it)}}</td>
                        <!-- deductions -->
                        @foreach($deductions as $ded)
                            <td class="text-right">
                                @if(isset($led->all_ded_la[$ded]))
                                    {{number_format($led->all_ded_la[$ded])}}
                                    @php
                                        $tot_deduction[$ded] += $led->all_ded_la[$ded];
                                    @endphp
                                @else
                                    0
                                    @php
                                        $tot_deduction[$ded] += 0;
                                    @endphp
                                @endif
                            </td>
                        @endforeach
                        <!-- deductions -->
                        <td class="text-right">{{number_format(($led->gross_salary+($led->total_salary-$led->net_salary))-$led->total_salary)}}</td>
                        <td class="text-right">{{number_format($led->net_salary)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <td style="font-weight:bold">Total</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->basic_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format(($totals->total_salary-$totals->net_salary)+$totals->da)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->hra)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->transport)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->misc)}}</td>
                <!-- allowances -->
                @foreach($allowances as $key=>$value)
                    <td class="text-right" style="font-weight:bold">
                        @if(array_key_exists($value, $tot_allowance))
                            {{number_format($tot_allowance[$value])}}
                        @else
                            0
                        @endif
                    </td>
                            
                @endforeach
                <!-- allowances -->

                <td class="text-right" style="font-weight:bold">{{number_format(($totals->total_salary-$totals->net_salary)+$totals->gross_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->pf)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->npse+$totals->nps_da_arrear)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->it)}}</td>

                <!-- deductions -->
                @foreach($deductions as $key=>$value)
                    <td class="text-right" style="font-weight:bold">
                        @if(array_key_exists($value, $tot_deduction))
                            {{number_format($tot_deduction[$value])}}
                        @else
                            0
                        @endif
                    </td>
                            
                @endforeach
                <!-- deductions -->
                <td class="text-right" style="font-weight:bold">{{number_format(($totals->gross_salary+($totals->total_salary-$totals->net_salary))-$totals->total_salary)}}</td>
                <td class="text-right" style="font-weight:bold">{{number_format($totals->total_salary)}}</td>
            </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
    <div class="employees-list shadow-md p-2 rounded bg-white mt-4">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">Consolidated Pay Ledger</h4>
        </div>
        <form action="{{ route('reports.export_consolidatedLedger') }}" target="_blank" method="POST" id="get_consolidatedLedger">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="pay_month">Select pay_month to view ledger</label>
                        <div class="input-group mb-3">
                            <input type="text" id="pay_month" name="pay_month" class="form-control" aria-describedby="basic-addon2"
                                value="{{date('F-Y')}}" readonly required placeholder="MM/YY">
                            <label class="input-group-text m-0" id="basic-addon2" for="pay_month"><i class="icon-clock menu-icon"
                                    style="font-size:16px"></i></label>
                        </div>
                        
                </div>
                <div class="form-group col-md-4">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-control form-control-sm">
                        <option value="teaching">Teaching</option>
                        <option value="non-teaching">Non-Teaching</option>
                    </select>
                    <div class="alert text-danger errorTxt" style="display: none" id="category_err"></div>
                    @error('category')
                        <div class="alert text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 flex align-items-center">
                    <label for=""></label>
                    <button type="submit" class="btn btn-dark">Generate Ledger</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/payroll.js') }}"></script>
    <script>
        $(document).ready((e) => {

            $('#employee').select2();
            // let table = new DataTable('#myTable');
            $("#month").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM-yy',
                onClose: function(dateText, inst) {
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                    $month = localStorage.setItem("nitttr_payroll_month", $(this).val());

                    // $("#getMonth").submit();

                }
            });
            $("#pay_month").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM-yy',
                onClose: function(dateText, inst) {
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                    $month = localStorage.setItem("nitttr_payroll_month", $(this).val());

                    // $("#getMonth").submit();

                }
            });
            $month = $("#month").val();
            $month = localStorage.setItem("nitttr_payroll_month", $month);
            // $("#teachingTable,#non-teachingTable").dataTable({
            //     lengthMenu: [10, 25, 50,100,500],
            //     responsive: true,
            //     ordering: false
            // });

        })
        function export_excel(){
            Table2Excel.extend((cell, cellText) => {
                return $(cell).attr('type') == 'string' ? {
                    t: 's',
                    v: cellText
                } : null;
            });
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll(".payledger"));
        }
    </script>
@endsection

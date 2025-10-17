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
            <h4 class="m-0">Salary Aquittance</h4>
            <div>
                <a href="{{route('reports.export_salary_aquitance',['month'=>$month,'category'=>$category])}}" target="_blank" class="btn btn-dark rounded">Export PDF</a>
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
        <form action="{{ route('reports.getSalary_aquitance') }}" method="POST" id="getMonth">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="month">Select month to view report</label>
                        <div class="input-group mb-3">
                            <input type="text" id="month" name="month" class="form-control" aria-describedby="basic-addon2"
                                value="{{ date('F-Y',strtotime($month)) }}" readonly required placeholder="MM/YY">
                            <label class="input-group-text m-0" id="basic-addon2" for="month"><i class="icon-clock menu-icon"
                                    style="font-size:16px"></i></label>
                        </div>
                        
                </div>
                <div class="form-group col-md-4">
                    <label for="month">Select Staff Category</label>
                    <div class="mb-3">
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select Staff Category</option>
                            <option value="teaching" {{($category=="teaching")?"selected":''}}>Teaching Staff</option>
                            <option value="non-teaching" {{($category=="non-teaching")?"selected":''}}>Non-Teaching Staff</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4 flex align-items-center">
                    <label for=""></label>
                    <button type="submit" class="btn btn-primary">Get Report</button>
                </div>
            </div>
        </form>
        <div class="aquitanceReport mt-2">
            <table class="table aquittance">
                <thead class="bg-light">
                    <th>Emp ID</th>
                    <th>Name of the Employee</th>
                    <th>Designation</th>
                    <th class="text-right">Part Basic</th>
                    <th class="text-right">Gross Pay</th>
                    <th class="text-right">Total Deduction</th>
                    <th class="text-right">Net Pay</th>
                    <th class="text-right">DA Arrear</th>
                    <th class="text-right">Total Salary</th>
                </thead>
                @if(count($payrolls)>0)
                    <tbody>
                        @foreach($payrolls as $payroll)
                            <tr>
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
                        <td colspan="3" class="text-right" style="font-weight:600;">TOTAL</td>
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
        </div>
    </div>
    <script src="{{ asset('js/payroll.js') }}"></script>
    <script>
        $(document).ready((e) => {
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
            
            $month = $("#month").val();
            $month = localStorage.setItem("nitttr_payroll_month", $month);
            $("#teachingTable,#non-teachingTable").dataTable({
                lengthMenu: [10, 25, 50,100,500],
                responsive: true,
                ordering: false
            });

        })

        function export_excel(){
            Table2Excel.extend((cell, cellText) => {
                return $(cell).attr('type') == 'string' ? {
                    t: 's',
                    v: cellText
                } : null;
            });
            var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll(".aquittance"));
        }
    </script>
@endsection

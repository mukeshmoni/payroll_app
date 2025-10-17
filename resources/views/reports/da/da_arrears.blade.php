@extends('layouts.app')

@section('content')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <div class="employees-list shadow-md p-2 rounded bg-white">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">DA Arrears</h4>
        </div>
        @if (session()->has('status'))
            @if (session('status'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @else
                <div class="alert alert-danger">{{ session('message') }}</div>
            @endif
        @endif
        <form action="{{route('reports.get_da_arrear_report')}}" target="_blank" method="POST" id="getLedger">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="month">Select Year</label>
                    <div class="input-group mb-2">
                        <select name="year" id="year" class="form-control">
                            @for($i=date('Y');$i>=1996;$i--)
                                <option value="{{$i}}" {{($i==date('Y'))?'selected':''}}>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                        
                </div>
                <div class="form-group col-md-3">
                    <label for="month">Select Month</label>
                    <div class="input-group mb-2">
                        <select name="month" id="month" class="form-control form-control-sm">
                            <option value="1">January to March</option>
                            <option value="2">July to October</option>
                        </select>
                    </div>
                        
                </div>
                <div class="form-group col-md-3">
                    <label for="category">Select category</label>
                    <div class="input-group mb-2">
                        <select name="category" id="category" class="form-control form-control-sm">
                            <option value="teaching">Teaching</option>
                            <option value="non-teaching">Non-Teaching</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="category">Select Report</label>
                    <div class="input-group mb-2">
                        <select name="report" id="report" class="form-control form-control-sm">
                            <option value="checklist">DA Arrear Checklist</option>
                            <option value="aquittance">DA Arrear Aquittance</option>
                            <option value="certificate">DA Arrear Certificate</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group flex align-items-center">
                <label for=""></label>
                <button type="submit" class="btn btn-md rounded btn-primary">Get Report</button>
            </div>
        </form>
    </div>
    <script>
        $(document).ready((e) => {
            $('#year').select2();

            $("#month").datepicker({
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
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
    </script>
@endsection

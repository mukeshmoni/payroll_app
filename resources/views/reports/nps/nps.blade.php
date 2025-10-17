@extends('layouts.app')

@section('content')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <div class="employees-list shadow-md p-2 rounded bg-white">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">NPS Report</h4>
        </div>
        @if (session()->has('status'))
            @if (session('status'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @else
                <div class="alert alert-danger">{{ session('message') }}</div>
            @endif
        @endif
        <form action="{{route('reports.get_nps_report')}}" target="_blank" method="POST" id="getLedger">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="month">Select month to view report</label>
                        <div class="input-group mb-3">
                            <input type="text" id="month" name="month" class="form-control" aria-describedby="basic-addon2"
                                value="{{ date('F-Y') }}" readonly required placeholder="MM/YY">
                            <label class="input-group-text m-0" id="basic-addon2" for="month"><i class="icon-clock menu-icon"
                                    style="font-size:16px"></i></label>
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
                <div class="form-group col-md-4 flex align-items-center">
                    <label for=""></label>
                    <button type="submit" class="btn btn-primary">Get Report</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready((e) => {
            $('#year').select2();

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
    </script>
@endsection

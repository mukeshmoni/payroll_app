@extends('layouts.app')

@section('content')
<style>
      .ui-datepicker-calendar {
            display: none;
        }
</style>
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Income Tax List</h4>
        <div>
            <a href="{{route('employees.export_employees')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <!-- <a href="{{route('salary-structure.add_salary_structure')}}" class="btn btn-primary "><i class="icon-plus mr-2"></i>Add Salary Structure</a> -->
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    
    <table class="table table-stripped display nowrap" id="employeesTable" style="width:100%">
        <thead>
            <th class="text-left"></th>
            <th class="text-left">S.No</th>
            <th class="text-left">Department</th>
            <th class="text-left">Employees</th>
            <th class="text-left">Actions</th>
        </thead>
        <tbody>
            @foreach($departments as $department)
                <tr>
                    <td class="text-center" style="cursor:pointer;" data-id="{{$department->id}}"><i class="ti-arrow-circle-right menu-icon drill_down_dept" data-id="{{$department->id}}"></i></td>
                    <td>{{$loop->iteration}}</td>
                    <td class="text-capitalize">{{$department->department}}</td>
                    <td><span style="font-weight:bold">{{$department->employee_count}}</span></td>
                    <td><a class="btn btn-sm btn-success" href="{{route('income_tax.generate',['dept'=>Crypt::encryptString($department->id)])}}">Generate IT</a></td>
                </tr>
                <tr class="{{$department->id}} bg-inverse-primary" style="display:none">
                    <td style="font-weight:600"></td>
                    <td style="font-weight:600">Employee ID</td>
                    <td style="font-weight:600">Employee Name</td>
                    <td style="font-weight:600">Designation</td>
                    <td style="font-weight:600">Status</td>
                </tr>
                @foreach($employees as $emp)
                    @if($emp->department == $department->id)
                        <tr class="{{$department->id}} bg-light" style="display:none">
                            <td class="text-center"></td>
                            <td>{{$emp->empid}}</td>
                            <td>{{$emp->empname}}</td>
                            <td>{{$emp->designation}}</td>
                            <td>
                                <a href="{{route('income_tax.generate_emp',['empid'=>Crypt::encryptString($emp->empid),'dept'=>Crypt::encryptString($department->id)])}}" class="btn btn-sm btn-inverse-success" data-toggle="tooltip" data-placement="bottom" title="Generate IT"><i class="ti-settings menu-icon"></i></a>
                                <a href="{{route('income_tax.view_emp',['empid'=>Crypt::encryptString($emp->empid),'dept'=>Crypt::encryptString($department->id)])}}" class="btn btn-sm btn-inverse-dark" data-toggle="tooltip" data-placement="bottom" title="View IT Details"><i class="icon-eye menu-icon"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
<script src="{{asset('js/payroll.js')}}"></script>
<script>
    // function delConfirm(url,empname){
    //     $.confirm({
    //             title: 'Delete Structure!',
    //             content: 'Are you sure you want to delete?',
    //             type: 'red',
    //             typeAnimated: true,
    //             buttons: {
    //                 confirm:{
    //                     btnClass: 'btn btn-danger',
    //                     action:function(){
    //                         window.location.href = url
    //                     }
    //                 },
    //                 cancel:{
    //                     btnClass: 'btn btn-dark',
    //                     action:function(){
    //                         return true;
    //                         $(".spinner-body").fadeOut();
    //                     }
    //                 },
    //             }
    //         });
    // }
    $(document).ready((e)=>{
        $("#month").datepicker({  
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM-yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $month = localStorage.setItem("nitttr_payroll_month",$(this).val());

                $("#getMonth").submit();

            }   
        }); 
        $month = $("#month").val();
        $month = localStorage.setItem("nitttr_payroll_month",$month);
        $("#employeesTable").dataTable({
            responsive: true,
            ordering: false
        });

    })
</script>
@endsection
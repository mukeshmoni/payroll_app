@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Salary Structure List</h4>
        <div class="btn-group">
            <a href="{{route('employees.export_employees',['from'=>'salary'])}}" class="btn btn-outline-dark btn-sm "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('salary-structure.consolidated_salary_structure')}}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-account-multiple mr-2"></i><span>Consolidated Structure</span></a>
            <a href="{{route('salary-structure.add_salary_structure')}}" class="btn btn-primary btn-sm "><i class="icon-plus mr-2"></i>Add Salary Structure</a>
        </div>
    </div>
    @if (session()->has("status") && session('from')=='salary')
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <table class="table table-hover table-stripped display nowrap" id="employeesTable" style="width:100%">
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Employee</th>
            <th class="text-center">Department</th>
            <th class="text-center">Designation</th>
            <th class="text-center">Basic Salary</th>
            <th class="text-center">DA</th>
            <th class="text-center">HRA</th>
            <th class="text-center">Transport</th>
            <th class="text-center">Misc.</th>
            <th class="text-center">PF</th>
            <th class="text-center">NPS-Employee</th>
            <!-- <th class="text-center">NPS-Employer</th> -->
            <th class="text-center">Net Salary</th>
            <th class="text-center">IT</th>
            <th class="text-center">Created at</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @foreach($structures as $structure)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$structure->employee}}-{{$structure->empname}}</td>
                <td class="text-center">{{$structure->dept}}</td>
                <td class="text-center">{{$structure->desg}}</td>
                <td class="text-center">{{number_format($structure->basic_salary)}}</td>
                <td class="text-center">{{number_format($structure->da)}}</td>
                <td class="text-center">{{number_format($structure->hra)}}</td>
                <td class="text-center">{{number_format($structure->transport)}}</td>
                <td class="text-center">{{number_format($structure->misc)}}</td>
                <td class="text-center">{{number_format($structure->pf)}}</td>
                <td class="text-center">{{number_format($structure->npse)}}</td>
                <!-- <td class="text-center">{{number_format($structure->npser)}}</td> -->
                <td class="text-center">{{number_format($structure->net_salary)}}</td>
                <td class="text-center">{{number_format($structure->it)}}</td>
                <td class="text-center">{{date("d-M-Y",strtotime($structure->created_at))}}</td>
                <td>
                    <a href="{{route('salary-structure.modify_salary_structure',['id'=>Crypt::encryptString($structure->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                    <a href="{{route('salary-structure.delete_salary_structure',['id'=>Crypt::encryptString($structure->id)])}}" onclick="delConfirm(this.href);return false;" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="mdi mdi-delete"></i></a>
                    <a href="{{route('salary-structure.view_salary_structure',['id'=>Crypt::encryptString($structure->id)])}}" class="btn btn-dark" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="employees-list shadow-md p-2 mt-4 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Pension Structure List</h4>
        <div class="btn-group">
            <a href="{{route('employees.export_employees',['from'=>'pension'])}}" class="btn btn-outline-dark btn-sm"><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('salary-structure.add_salary_structure')}}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-account-multiple mr-2"></i><span>Consolidated Structure</span></a>
            <a href="{{route('salary-structure.add_pension_structure')}}" class="btn btn-primary btn-sm"><i class="icon-plus mr-2"></i>Add Pension Structure</a>
        </div>
    </div>
    @if (session()->has("status") && session('from')=='pension')
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <table class="table table-hover table-stripped display nowrap" id="pensionsTable" style="width:100%">
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Employee</th>
            <th class="text-center">Department</th>
            <th class="text-center">Designation</th>
            <th class="text-center">Basic Pension</th>
            <th class="text-center">Addtl. Pension</th>
            <th class="text-center">DA</th>
            <th class="text-center">Medic.</th>
            <th class="text-center">Misc.</th>
            <th class="text-center">Less. Comm.</th>
            <th class="text-center">Rec.</th>
            <th class="text-center">IRG.</th>
            <th class="text-center">IT</th>
            <th class="text-center">Net Pension</th>
            <th class="text-center">Created at</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @foreach($p_structures as $structure)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$structure->employee}}-{{$structure->empname}}</td>
                <td class="text-center">{{$structure->dept}}</td>
                <td class="text-center">{{$structure->desg}}</td>
                <td class="text-center">{{number_format($structure->basic_salary)}}</td>
                <td class="text-center">{{number_format($structure->addtl_pension)}}</td>
                <td class="text-center">{{number_format($structure->da)}}</td>
                <td class="text-center">{{number_format($structure->medic_allow)}}</td>
                <td class="text-center">{{number_format($structure->misc)}}</td>
                <td class="text-center">{{number_format($structure->less_comm)}}</td>
                <td class="text-center">{{number_format($structure->misc_rec)}}</td>
                <td class="text-center">{{number_format($structure->irg)}}</td>
                <td class="text-center">{{number_format($structure->it)}}</td>
                <td class="text-center">{{number_format($structure->net_salary)}}</td>
                <td class="text-center">{{date("d-M-Y",strtotime($structure->created_at))}}</td>
                <td>
                    <a href="{{route('salary-structure.modify_pension_structure',['id'=>Crypt::encryptString($structure->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                    <a href="{{route('salary-structure.delete_salary_structure',['id'=>Crypt::encryptString($structure->id)])}}" onclick="delConfirm(this.href);return false;" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="mdi mdi-delete"></i></a>
                    <a href="{{route('salary-structure.view_salary_structure',['id'=>Crypt::encryptString($structure->id)])}}" class="btn btn-dark" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    function delConfirm(url,empname){
        $.confirm({
                title: 'Delete Structure!',
                content: 'Are you sure you want to delete?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    confirm:{
                        btnClass: 'btn btn-danger',
                        action:function(){
                            window.location.href = url
                        }
                    },
                    cancel:{
                        btnClass: 'btn btn-dark',
                        action:function(){
                            return true;
                            $(".spinner-body").fadeOut();
                        }
                    },
                }
            });
    }
    $(document).ready((e)=>{
        $("#employeesTable").dataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 3 },
                { responsivePriority: 3, targets: 11 },
                { responsivePriority: 5, targets: -1 },
            ]
        });
        $("#pensionsTable").dataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 3 },
                { responsivePriority: 3, targets: 13 },
                { responsivePriority: 5, targets: -1 },
            ]
        });

    })
</script>
@endsection
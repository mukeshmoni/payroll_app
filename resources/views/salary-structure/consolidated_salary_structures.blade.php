@extends('layouts.app')

@section('content')

<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Salary Structure List</h4>
        <div class="btn-group">
            <a href="{{route('salary-structure')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
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
            @foreach($allowances as $allowance)
            <th class="text-center">{{$allowance->allowance_name}}</th>
            @endforeach
            <th class="text-center">Gross Salary</th>
            <th class="text-center">PF</th>
            <th class="text-center">NPS-Employee</th>
            <th class="text-center">IT</th>
            @foreach($loans_advances as $la)
            <th class="text-center">{{$la->da_types}}</th>
            @endforeach
            @foreach($deductions as $deduction)
            <th class="text-center">{{$deduction->deduction_name}}</th>
            @endforeach
            <th class="text-center">Total Deduction</th>
            <th class="text-center">Net Salary</th>
        </thead>
        <tbody>
            @foreach($structures as $structure)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$structure->employee}}-{{$structure->empname}}</td>
                <td class="text-center">{{$structure->dept}}</td>
                <td class="text-center">{{$structure->desg}}</td>
                <td class="text-center basic_salary">
                    <span class="inline-span">{{number_format($structure->basic_salary)}}</span>
                    <div class="inline-edit" style="display:none">
                        <div class="d-flex align-center gap-4 ">
                            <input type="number" name="basic_salary" id="basic_salary" class="form-control-sm text-right" style="width:150px;" value="{{$structure->basic_salary}}">
                            <button type="button"><i class="mdi mdi-check-circle text-success" style="font-size:1.5rem"></i></button>
                            <button type="button" class="close-inline-edit"><i class="mdi mdi-close-circle text-danger" style="font-size:1.5rem"></i></button>
                        </div>
                    </div>
                </td>
                <td class="text-center">{{number_format($structure->da)}}</td>
                <td class="text-center">{{number_format($structure->hra)}}</td>
                <td class="text-center">{{number_format($structure->transport)}}</td>
                <td class="text-center">{{number_format($structure->misc)}}</td>
                @foreach($allowances as $allowance)
                <td class="text-center">0</td>
                @endforeach
                <td class="text-center">{{number_format($structure->gross_salary)}}</td>
                <td class="text-center">{{number_format($structure->pf)}}</td>
                <td class="text-center">{{number_format($structure->npse)}}</td>
                <td class="text-center">{{number_format($structure->it)}}</td>
                @foreach($loans_advances as $la)
                <td class="text-center">0</td>
                @endforeach
                @foreach($deductions as $deduction)
                <td class="text-center">0</td>
                @endforeach
                <td class="text-center">{{number_format($structure->gross_salary-$structure->net_salary)}}</td>
                <td class="text-center">{{number_format($structure->net_salary)}}</td>
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
            scrollX: true,
            bAutoWidth: false
        });
        
        $(".inline-span").click((e)=>{
            $row = $(e.target).parents("tr");
            $inline_edit = $row.find(".inline-edit");
            $row.find(".inline-span").hide();
            $inline_edit.fadeIn();
            $value = $inline_edit.find("input").val();
            $inline_edit.find("input").focus().val("").val($value);

        })

        $(".close-inline-edit").click((e)=>{
            $row = $(e.target).parents("tr");
            $inline_edit = $row.find(".inline-edit");
            $inline_edit.hide();
            $row.find(".inline-span").show();
        })
    })
</script>
@endsection
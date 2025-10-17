@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Employees List</h4>
        <div>
            <a href="{{route('employees.order')}}" class="btn btn-info btn-sm rounded flex align-items-center justify-center" data-toggle="tooltip" data-placement="bottom" title="Make Order"><i class="mdi mdi-file-tree"></i></a>
            <a href="#" class="btn btn-dark btn-sm rounded" onclick="$('#uploadEmployees').modal('show')"><i class="icon-upload mr-2"></i>Upload Data</a>
            <a href="{{route('employees.export_employees')}}" class="btn btn-dark btn-sm rounded"><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('employees.add_employee')}}" class="btn btn-primary btn-sm rounded"><i class="icon-plus mr-2"></i>Add Employee</a>
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
            <script>
                sessionStorage.removeItem("saved_employee_details");
            </script>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <table class="table table-hover table-stripped display nowrap" id="employeesTable" style="width:100%">
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Emp ID</th>
            <th class="text-center">Name</th>
            <th class="text-center">Contact</th>
            <th class="text-center">Designation</th>
            <th class="text-center">Category</th>
            <th class="text-center">DOJ</th>
            <th class="text-center">Created at</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$employee->empid}}</td>
                    <td class="text-capitalize">{{$employee->empname}}</td>
                    <td class="text-center">{{$employee->empcontact}}</td>
                    <td class="text-center">{{$employee->desg_name}}</td>
                    <td class="text-center">{{$employee->category}}</td>
                    <td class="text-center">{{$employee->empdoj?date("d-M-Y",strtotime($employee->empdoj)):'-'}}</td>
                    <td class="text-center">{{date("d-M-Y",strtotime($employee->created_at))}}</td>
                    <td>
                        <a href="{{route('employees.modify_employee',['id'=>Crypt::encryptString($employee->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                        <a href="{{route('employees.delete_employee',['id'=>Crypt::encryptString($employee->id)])}}" onclick="delConfirm(this.href);return false;" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="mdi mdi-delete"></i></a>
                        <a href="{{route('employees.view_employee',['id'=>Crypt::encryptString($employee->id)])}}" class="btn btn-dark" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal" tabindex="-1" id="uploadEmployees">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Employees</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{route('employees.import_employees')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="file" name="employees" id="employees" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#uploadEmployees').modal('hide')">Close</button>
        <button type="submit" class="btn btn-primary">Upload</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
    function delConfirm(url,empname){
        $.confirm({
                title: 'Delete Employee!',
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
                { responsivePriority: 3, targets: 1 },
                { responsivePriority: 1, targets: 2 },
                { responsivePriority: 2, targets: -1 }
            ]
        });

    })
</script>
@endsection
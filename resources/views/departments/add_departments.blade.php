@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Departments</h4>
        <a href="{{route('departments')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("departments.store_departments")}}" method="POST" id="departmentsForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Departments Name <span class="text-danger">*</span></label>
                        <input type="text" id="departments" name="departments" class="form-control @error('departments') is-invalid @enderror" placeholder="Departments Name" value="{{ old('departments') }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="departments_err"></div>
                        @error('departments')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!--
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Departments Description <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="desg_description" name="desg_description" class="form-control" placeholder="Description Name">
                        <div class="alert text-danger" style="display: none" id="desg_description_err"></div>
                    </div>
                </div>
-->
            </div>

            <div class="text-left">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
        </div>
    </form>
    {{-- <hr> --}}
    <div class="employees-list p-2 rounded">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">Departments List</h4>
            <div>
                <a href="{{route('departments.export_departments')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            </div>
        </div>
        
        
        <table class="table" id="dess" id="dess">
            <thead>
                <th>S.No</th>           
                <th>Departments</th>
               <!-- <th>Description</th> -->
                <th>Created at</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$department->department}}</td>
                       <!-- <td>{{$department->desg_department}}</td> -->
                        <td>{{date("Y-m-d",strtotime($department->created_at))}}</td>
                        <td>
                            <a href="{{route('departments.modify_departments',['id'=>Crypt::encryptString($department->id)])}}" class="btn btn-info"><i class="mdi mdi-pencil"></i></a>
                            {{-- <a href="{{route('departments.delete_departments',['id'=>Crypt::encryptString($departments->id)])}}" class="btn btn-danger">Delete</a> --}}
                            <a href="{{route('departments.delete_departments',['id'=>Crypt::encryptString($department->id)])}}" onclick="delconfirm(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
    <script src="{{asset('js/departments.js')}}">
       
    </script>
    <script> setTimeout(function(){
    $(".alert").fadeOut(400);
}, 10000) </script>
    <script>
        function delconfirm(url) {
            $.confirm({
                title: 'Delete Department!',
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
    </script>


    <script>
jQuery(document).ready((e)=>{
    $('#dess').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 2 }
            ]
        });
} );
</script>
</div>
@endsection
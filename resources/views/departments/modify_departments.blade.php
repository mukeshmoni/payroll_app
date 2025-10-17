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
    <form action="{{route("departments.update_departments",['id'=>Crypt::encryptString($departments->id)])}}" method="POST" id="departmentsForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Departments Name <span class="text-danger">*</span></label>
                        <input type="text" id="departments" name="departments" class="form-control @error('departments') is-invalid @enderror" placeholder="Departments Name" value="{{ $departments->department }}" required>
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
                        <input type="text" id="desg_description" name="desg_description" class="form-control" placeholder="Description Name" value="{{$departments->desg_department}}">
                        <div class="alert text-danger" style="display: none" id="desg_description_err"></div>
                    </div>
                </div>
                -->
            </div>

            <div class="text-left">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Update</button>
            </div>
        </div>
    </form>
   
    <script src="{{asset('js/departments.js')}}">
       
    </script>

</div>
@endsection
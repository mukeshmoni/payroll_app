@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Designations</h4>
        <a href="{{route('designations')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("designations.update_designation",['id'=>Crypt::encryptString($designation->id)])}}" method="POST" id="designationForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" id="designation" name="designation" class="form-control @error('designation') is-invalid @enderror" placeholder="Designation Name" value="{{ $designation->designation }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="designation_err"></div>
                        @error('designation')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!--
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Designation Description <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="desg_description" name="desg_description" class="form-control" placeholder="Description Name" value="{{$designation->desg_description}}">
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
    
    <script src="{{asset('js/designations.js')}}"></script>
</div>
@endsection
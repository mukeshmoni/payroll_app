@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Update Pay Levels and Slab</h4>
        <a href="{{route('departments')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("paylevels.update_paylevel",['id'=>Crypt::encryptString($paylevel->id)])}}" method="POST" id="paylevelsForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Pay Level <span class="text-danger">*</span></label>
                        <input type="text" id="paylevel" name="paylevel" class="form-control @error('paylevel') is-invalid @enderror " placeholder="Pay Level" value="{{ old('paylevel')?old('paylevel'):$paylevel->paylevel }}" required>
                        <div class="alert  text-danger text-capitalize errorTxt" style="display: none" id="paylevel_err"></div>
                        @error('paylevel')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Slab Amount <span class="text-danger">*</span></label>
                        <input type="number" id="slab" name="slab" class="form-control @error('slab') is-invalid @enderror " placeholder="Slab Amount" value="{{ old('slab')?old('slab'):$paylevel->slab }}" required>
                        <div class="alert text-danger text-capitalize errorTxt" style="display: none" id="slab_err"></div>
                        @error('slab')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Update</button>
            </div>
        </div>
    </form>
   
    <script src="{{asset('js/paylevels.js')}}">
       
    </script>
    <script> 
        setTimeout(function(){
        $(".alert").fadeOut(400);
    }, 10000)   
    </script>
</div>
@endsection
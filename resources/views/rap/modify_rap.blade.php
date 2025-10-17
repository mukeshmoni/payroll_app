@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Modify Residential Accomodation Percentage</h4>
        <a href="{{route('rap.add_rap')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("rap.update_rap",['id'=>Crypt::encryptString($rap->id)])}}"" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
                
            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="amt">RAP % : <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="rap" name="rap" placeholder="RAP" value="{{ old('rap')?old('rap'):$rap->rap_perc }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="rap_err"></div>
                        @error('rap')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from">Month-Year : <span class="text-danger">*</span></label>
                     
                        <input type="month" min="{{date('Y',strtotime(date("Y")." -5 years"))}}-01" max="{{ date('Y') }}-12" class="form-control" id="from" name="from" placeholder="Month-Year" value="{{ old('from')?old('from'):$rap->from }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="from_err"></div>
                        @error('from')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Remark <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Remarks If Any" value="{{ old('remark')?old('remark'):$rap->remark }}">
                        <div class="alert text-danger" style="display: none" id="remark_err"></div>
                    </div>
                </div>           
                
            </div>    
                   
            <div class="row">
                
                
            </div>     
            <input type="hidden" class="form-control" id="dval" name="dval">
            <input type="hidden" class="form-control" id="aval" name="aval">
            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Update</button>
            </div>
        </div>
    </form>
    {{-- <hr> --}}
   
   <script src="{{asset('js/rap.js')}}"></script>
    <script> 
    //Only allow numeric
$('#da').keypress(function (e) { 
    var charCode = (e.which) ? e.which : event.keyCode 
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
}); 

    //For Employee Name with TExt box code
    $(function () {
    $("#empid").selectize();
    });
    </script>


</div>
@endsection
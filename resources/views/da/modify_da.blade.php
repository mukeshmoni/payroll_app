@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Modify DA</h4>
        <a href="{{route('da')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("da.update_da",['id'=>Crypt::encryptString($da->id)])}}"" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
                
            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="amt">DA % : <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="da" name="da" placeholder="DA" value="{{ old('da')?old('da'):$da->da }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="da_err"></div>
                        @error('da')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year">Month-Year : <span class="text-danger">*</span></label>
                     
                        <input type="month" min="2022-01" max="{{ date('Y') }}-12" class="form-control" id="year" name="year" placeholder="Month-Year" value="{{ old('year')?old('year'):$da->year }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="year_err"></div>
                        @error('year')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
<!--
                <div class="col-md-4">
                    <div class="form-group da">
                        <label for="damonth">Effect From Month: <span class="text-danger">*</span></label>
                        <select name="month" id="month" class="form-control lea" required>
                            <option value="">---Select Month---</option>
                            @for($iM =1;$iM<=12;$iM++) 
                            <option value="{{ date("M", strtotime("$iM/12/10")) }}" @if(old('month')==$da->month || $da->month == date("M", strtotime("$iM/12/10"))) selected @endif> {{ date("M", strtotime("$iM/12/10")); }} </option>
                            @endfor 

                           

                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="month_err"></div>
                        @error('month')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group da">
                        <label for="dayear">Year: <span class="text-danger">*</span></label>
                        <select name="year" id="year" class="form-control lea" required>
                            <option value="">---Select Year---</option>
                            {{ $last= date('Y')-12 }}
                            {{ $now = date('Y') }}
                            @for ($i = $now; $i >= $last; $i--)                           
                            <option value="{{ $i }}" @if(old('year')==$da->year || $da->year ==  $i ) selected @endif> {{ $i }} </option>
                            @endfor 
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="year_err"></div>
                        @error('year')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


-->

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Remark <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Remarks If Any" value="{{ old('remark')?old('remark'):$da->remark }}">
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
   
   <script src="{{asset('js/da.js')}}"></script>
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
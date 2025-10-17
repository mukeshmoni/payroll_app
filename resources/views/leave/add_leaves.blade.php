@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Leave Request</h4>
        <a href="{{route('leaves')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("leaves.store_leaves")}}" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-gray-700">Select Employee</label>
                        <select id="empid" name="empid" class="block w-full mt-1 rounded-md" required>
                            <option value="">---Select Employee ---</option>
                             @foreach ($employees as $employee)                            
                                <option value="{{ $employee->empid }}" @if(old('empid')==$employee->empid) selected @endif>{{ $employee->empname }} - {{ $employee->empid }}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="empid_err"></div>
                        @error('empid')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>        
            </div>    
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leavetypes">Leave Type:</label>
                        <select name="leavetype" id="leavetype" class="form-control" required>
                            <option value="">---Select Type Of Leave---</option>
                            <option value="el" @if(old('leavetype')=='el') selected @endif>Earned Leave</option>
                            <option value="ml" @if(old('leavetype')=='ml') selected @endif>Medical Leave</option>
                            <option value="cl" @if(old('leavetype')=='cl') selected @endif>Casual Leave</option>   
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="leavetype_err"></div>
                        @error('leavetype')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="startdt">Start Date:</label>
                        <input type="date" class="form-control dtclass" id="startdt" name="startdt" placeholder="" value="{{ old('startdt') }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="startdt_err"></div>
                        @error('startdt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="enddt">End Date:</label>
                        <input type="date" class="form-control dtclass" id="enddt" name="enddt" placeholder="" value="{{ old('enddt') }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="enddt_err"></div>
                        @error('enddt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>    
                   
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Remark <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Remarks If Any" value="{{ old('remark') }}">
                        <div class="alert text-danger" style="display: none" id="remark_err"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div id="fh">
                            <label for="days">Full/Half Day:</label>
                            <select name="days" id="days" class="form-control" required>
                                <option value="">---Select ---</option>
                                <option value="1" @if(old('days')=='1') selected @endif>Full Day</option>
                                <option value="0.5" @if(old('days')=='0.5') selected @endif>Half Day</option>
                            </select>
                            <div class="alert text-danger text-capitalize" style="display: none" id="days_err"></div>
                            @error('days')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                        <input type="hidden" class="form-control" id="mdays" name="mdays" placeholder="">
                    </div>
                </div>
            </div>     

            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
        </div>
    </form>
    {{-- <hr> --}}
   
   <script src="{{asset('js/attendance.js')}}"></script>
    <script> 
/*
    setTimeout(function(){
        $(".alert").fadeOut(400);
        }, 10000) 
*/
//Delete function confirm

//For Days calculation
    // $('#fh').hide();
    $(".dtclass").change((e)=>{
      
        let start_date = new Date($("#startdt").val());
        let end_date = new Date($("#enddt").val());
        //alert(start_date);
        var milli_secs = start_date.getTime() - end_date.getTime();
             
            // Convert the milli seconds to Days 
            var days = milli_secs / (1000 * 3600 * 24);
            //document.getElementById("ans").innerHTML = Math.round(Math.abs(days));
            let day = Math.round(Math.abs(days));
            //alert(day);

            if(day == 0){
                $('#fh').show();
                $('#mdays').val("");
                $("#days").attr("required", true);
            } else {
                $('#fh').hide();
                $("#days").removeAttr("required");
            }

            if(day != 0){
                $('#mdays').val(day+1);
            }
            else{
                $('#mdays').val("1");
            }
    })

    //For Employee Name with TExt box code
    $(function () {
    $("#empid").selectize();
    });
    </script>


</div>
@endsection
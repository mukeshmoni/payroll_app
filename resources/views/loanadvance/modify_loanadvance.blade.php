@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Modify Loan/Advance</h4>
        <a href="{{route('loanadvance')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("loanadvance.update_loanadvance",['id'=>Crypt::encryptString($la->id)])}}"" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-gray-700">Select Employee <span class="text-danger">*</span></label>
                        <select id="empid" name="empid" class="block w-full mt-1 rounded-md" required>
                            <option value="">---Select Employee ---</option>
                             @foreach ($employees as $employee)                            
                                <option value="{{ $employee->empid }}" @if(old('empid')==$employee->empid || $la->empid == $employee->empid) selected @endif>{{ $employee->empname }} - {{ $employee->empid }}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="empid_err"></div>
                        @error('empid')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>       
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-gray-700">Select Surety <span class="text-secondary">(If required)</span></label>
                        <select id="surety" name="surety" class="block w-full mt-1 rounded-md">
                            <option value="">---Select Employee ---</option>
                             @foreach ($employees as $employee)                            
                                <option value="{{ $employee->empid }}" @if(old('surety')==$employee->empid || $la->surety == $employee->empid) selected @endif>{{ $employee->empname }} - {{ $employee->empid }}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="surety_err"></div>
                        @error('surety')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>        
            </div>    
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group da">
                        <label for="la">Loan/Advance: <span class="text-danger">*</span></label>
                        <select name="la" id="la" class="form-control lea" required>
                            <option value="">---Select Loan/Advance---</option>
                            <option value="loan" @if(old('la')=='loan'|| $la->loans_advances == 'loan') selected @endif>Loan</option>
                            <option value="advance" @if(old('la')=='advance'|| $la->loans_advances == 'advance') selected @endif>Advance</option>                           
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="la_err"></div>
                        @error('la')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 ded">
                    <div class="form-group">
                        <label class="text-gray-700">Select Deduction <span class="text-danger">*</span></label>
                        <select id="ded" name="ded" class="form-control da_types" required>
                            <option value="">---Select Deduction ---</option>
                             @foreach ($deduction as $ded)                            
                                <option value="{{ $ded->deduction_type_name }}" @if(old('type')==$ded->deduction_type_name || $la->da_types == $ded->deduction_type_name) selected @endif>{{ $ded->deduction_name }}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="ded_err"></div>
                        @error('ded')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>    

                <div class="col-md-4 alw">
                    <div class="form-group">
                        <label class="text-gray-700">Select Allowance </label>
                        <select id="alw" name="alw" class="form-control da_types" required>
                            <option value="">Advance</option>
                             @foreach ($allowance as $alw)                            
                                <option value="{{ $alw->allowance_type_name }}" @if(old('type')==$alw->allowance_type_name || $la->da_types == $alw->allowance_type_name) selected @endif>{{ $alw->allowance_name }}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="alw_err"></div>
                        @error('alw')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>  
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tamt">Total Amount:</label>
                        <input type="number" class="form-control" id="tamt" name="tamt" placeholder="Total Amount (Optional)" value="{{ old('tamt')?old('tamt'):$la->totamt }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="tamt_err"></div>
                        @error('tamt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tenure">Tenure (Only Months): <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="tenure" name="tenure" placeholder="Tenure (Only Month)" value="{{ old('tenure')?old('tenure'):$la->tenure }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="tenure_err"></div>
                        @error('tenure')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="amt">Monthly Recovery: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amt" name="amt" placeholder="Amount" value="{{ old('amt')?old('amt'):$la->amt }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="amt_err"></div>
                        @error('amt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="startdt">Start Date: <span class="text-danger">*</span></label>
                        <input type="date" class="form-control dtclass" id="startdt" name="startdt" placeholder="" value="{{ old('startdt')?old('startdt'):$la->startdt }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="startdt_err"></div>
                        @error('startdt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="adj_instal_no">Adj. installment No:</label>
                        <input type="number" class="form-control" id="adj_instal_no" name="adj_instal_no" placeholder="Adj. Instal. No" value="{{ old('adj_instal_no')?old('adj_instal_no'):$la->adj_instal_no }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="adj_instal_no_err"></div>
                        @error('adj_instal_no')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="adj_instal_amt">Adj. installment Amt:</label>
                        <input type="number" class="form-control" id="adj_instal_amt" name="adj_instal_amt" placeholder="Adj. Instal. Amt" value="{{ old('adj_instal_amt')?old('adj_instal_amt'):$la->adj_instal_amt }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="adj_instal_amt_err"></div>
                        @error('adj_instal_amt')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>    
                   
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Remark <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Remarks If Any" value="{{ old('remark')?old('remark'):$la->remark }}">
                        <div class="alert text-danger" style="display: none" id="remark_err"></div>
                    </div>
                </div>
                
            </div>     
            <input type="hidden" class="form-control" id="dval" name="dval">
            <input type="hidden" class="form-control" id="aval" name="aval">
            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
        </div>
    </form>
    {{-- <hr> --}}
   
   <script src="{{asset('js/loanadvance.js')}}"></script>
    <script> 
//Only allow numeric
$('#amt').keypress(function (e) { 
    var charCode = (e.which) ? e.which : event.keyCode 
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
}); 
$('#tamt').keypress(function (e) { 
    var charCode = (e.which) ? e.which : event.keyCode 
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
}); 
$('#tenure').keypress(function (e) { 
    var charCode = (e.which) ? e.which : event.keyCode 
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
}); 
$(document).ready((e)=>{
    $("#amt,#tenure").keyup((e)=>{
        let amt = $("#amt").val();
        let tenure = $("#tenure").val();
        $("#tamt").val(amt*tenure);
    })
})
//For Disable Enable Deduction/Allowance basedon choosing Loan/Advance
    $('.alw').hide();
    //$("#ded").prop("disabled", true);
    let la = $("#la").val(); 
    if(la == 'loan'){
        $('.ded').show();
        $('.alw').hide();
        $('#aval').val("1");
        $('#dval').val("");
    }
    else{
        $('.alw').show();
        $("#alw").prop("disabled", true);
        $('.ded').hide();
        $('#dval').val("1");
        $('#aval').val("");
    }
    $(".lea").change((e)=>{
      
        let la = $("#la").val(); 
            if(la == 'loan'){
                $(".da_types").prop("disabled", false);
               $('.ded').show();
               $("#ded").prop("disabled", false);
               $('.alw').hide();
               $('#aval').val("1");
               $("#alw").removeAttr("required");
               $("#ded").attr("required", true);
               $('#dval').val("");
               // $("#days").attr("required", true);
            } else if(la == "advance"){
                $(".da_types").prop("disabled", false);
                $('.ded').hide();
                $("#ded").prop("disabled", true);
                $("#alw").prop("disabled", true);
                $('.alw').show();
                $('#dval').val("1");
                $("#ded").removeAttr("required");
                $("#alw").attr("required", true);
                $('#aval').val("");
               // $("#days").removeAttr("required");
            }else{
                $(".da_types").attr("required");
                $(".da_types").prop("disabled", true);
            }

          
    })

    //For Employee Name with TExt box code
    $(function () {
    $("#empid").selectize();
    });
    </script>


</div>
@endsection
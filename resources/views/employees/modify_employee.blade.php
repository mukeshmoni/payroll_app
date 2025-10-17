@extends('layouts.app')

@section('content')
@php
$errs=[];
if($errors->count()){
    $err = $errors->messages();
    foreach ($err as $key => $value) {
        array_push($errs,$key);
    }
}
@endphp
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Modify Employee</h4>
        <a href="{{route('employees')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div class="form-header">
        <div class="form-sections d-flex justify-content-center align-items-center">
            <div class="sections section-1 active">1</div>
            <div class="sections section-2">2</div>
            <div class="sections section-3">3</div>
            <div class="sections section-4">4</div>
        </div>
        <span class="hr-line"></span>
    </div>
    <form action="{{route('employees.modify_employee',['id'=>Crypt::encryptString($employee->id)])}}" method="POST" id="EmployeeForm" autocomplete>
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('empname'))?old('empname'):$employee->empname }}" id="empname" name="empname" class="form-control @error('empname') is-invalid @enderror" placeholder="Employee Name" >
                        <div class="alert text-danger" style="display: none" id="empname_err"></div>
                        @error('empname')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('fathername'))?old('fathername'):$employee->fathername }}" id="fathername" name="fathername" class="form-control @error('fathername') is-invalid @enderror" placeholder="Father's Name" >
                        <div class="alert text-danger" style="display: none" id="fathername_err"></div>
                        @error('fathername')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('mothername'))?old('mothername'):$employee->mothername }}" id="mothername" name="mothername" class="form-control @error('mothername') is-invalid @enderror" placeholder="Mother's Name" >
                        <div class="alert text-danger" style="display: none" id="mothername_err"></div>
                        @error('mothername')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" value="{{ (old('empdob'))?old('empdob'):$employee->empdob }}" id="empdob" name="empdob" class="form-control @error('empdob') is-invalid @enderror" placeholder="Date of Birth" >
                        <div class="alert text-danger" style="display: none" id="empdob_err"></div>
                        @error('empdob')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="empgender" id="empgender" class="form-control @error('empgender') is-invalid @enderror" >
                            <option value="">Select Gender</option>
                            <option value="male" {{(old('empgender')=='male'||strtolower($employee->empgender)=='male')?'selected':''}}>Male</option>
                            <option value="female" {{(old('empgender')=='female'||strtolower($employee->empgender)=='female')?'selected':''}}>Female</option>
                            <option value="transgender" {{(old('empgender')=='transgender'||strtolower($employee->empgender)=='transgender')?'selected':''}}>Transgender</option>
                            <option value="not" {{(old('empgender')=='not'||strtolower($employee->empgender)=='not')?'selected':''}}>Prefer not to answer</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="empgender_err"></div>
                        @error('empgender')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                        <select name="maritalstatus" id="maritalstatus" class="form-control @error('maritalstatus') is-invalid @enderror" >
                            <option value="">Select Marital Status</option>
                            <option value="single" {{(old('maritalstatus')=='single'||strtolower($employee->maritalstatus)=='single' || strtolower($employee->maritalstatus)=='unmarried' )?'selected':''}}>Single</option>
                            <option value="married" {{(old('maritalstatus')=='married'||strtolower($employee->maritalstatus)=='married')?'selected':''}}>Married</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="maritalstatus_err"></div>
                        @error('maritalstatus')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="number" value="{{ (old('empcontact'))?old('empcontact'):$employee->empcontact }}" id="empcontact" name="empcontact" class="form-control @error('empcontact') is-invalid @enderror" placeholder="Contact Number" >
                        <div class="alert text-danger" style="display: none" id="empcontact_err"></div>
                        @error('empcontact')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('emppanno'))?old('emppanno'):$employee->emppanno }}" id="emppanno" name="emppanno" class="form-control @error('emppanno') is-invalid @enderror" placeholder="PAN Number" >
                        <div class="alert text-danger" style="display: none" id="emppanno_err"></div>
                        @error('emppanno')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('empaadhaarno'))?old('empaadhaarno'):$employee->empaadhaarno }}" id="empaadhaarno" name="empaadhaarno" class="form-control @error('empaadhaarno') is-invalid @enderror" placeholder="Aadhaar Number" >
                        <div class="alert text-danger" style="display: none" id="empaadhaarno_err"></div>
                        @error('empaadhaarno')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" value="{{ (old('empemail'))?old('empemail'):$employee->empemail }}" id="empemail" name="empemail" class="form-control @error('empemail') is-invalid @enderror" placeholder="Email">
                        <div class="alert text-danger" style="display: none" id="empemail_err"></div>
                        @error('empemail')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="address" value="{{ (old('empaddress'))?old('empaddress'):$employee->empaddress }}" id="empaddress" name="empaddress" class="form-control @error('empaddress') is-invalid @enderror" placeholder="Address" >
                        <div class="alert text-danger" style="display: none" id="empaddress_err"></div>
                        @error('empaddress')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select name="empstate" id="empstate" class="form-control @error('empstate') is-invalid @enderror">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}" {{(old('empstate')==$state->id||$employee->empstate==$state->id)?'selected':''}}>{{$state->name}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="empstate_err"></div>
                        @error('empstate')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <select name="empcity" id="empcity" class="form-control @error('empcity') is-invalid @enderror">
                            <option value="">Select City</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="empcity_err"></div>
                        @error('empcity')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pincode <span class="text-danger">*</span></label>
                        <input type="number" value="{{ (old('pincode'))?old('pincode'):$employee->pincode }}" id="pincode" name="pincode" class="form-control @error('pincode') is-invalid @enderror" placeholder="Pincode" >
                        <div class="alert text-danger" style="display: none" id="pincode_err"></div>
                        @error('pincode')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn btn-primary saveNext" id="section-1-button" type="button">Save & Next</button>
            </div>
        </div>
        <div class="section-2-form section-forms" style="display: none">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" value="{{$employee->empid}}" readonly id="empid" name="empid" class="form-control @error('empid') is-invalid @enderror" placeholder="Employee ID" >
                        <div class="alert text-danger" style="display: none" id="empid_err"></div>
                        @error('empid')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Joining <span class="text-danger">*</span></label>
                        <input type="date" value="{{ (old('empdoj'))?old('empdoj'):$employee->empdoj }}" id="empdoj" name="empdoj" class="form-control @error('empdoj') is-invalid @enderror" placeholder="Date of Joining" >
                        <div class="alert text-danger" style="display: none" id="empdoj_err"></div>
                        @error('empdoj')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Retirement <span class="text-secondary">(optional)</span></label>
                        <input type="date" id="empdor" name="empdor" value="{{ (old('empdor'))?old('empdor'):$employee->empdor }}" class="form-control" placeholder="Date of Retirement">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <select name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" >
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{$designation->id}}" {{(old('designation')==$designation->id||$employee->designation==$designation->id)?'selected':''}}>{{$designation->designation}} - {{$designation->desg_description}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="designation_err"></div>
                        @error('designation')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" id="department" class="form-control @error('department') is-invalid @enderror" >
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{$department->id}}" {{(old('department')==$department->id||$employee->department==$department->id)?'selected':''}}>{{$department->department}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="department_err"></div>
                        @error('department')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Employee Category <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" >
                            <option value="">Select Category</option>
                            <option value="teaching" {{(old('category')=="teaching"||strtolower($employee->category)=="teaching")?'selected':''}}>Teaching</option>
                            <option value="non-teaching" {{(old('category')=="non-teaching"||strtolower($employee->category)=="non-teaching")?'selected':''}}>Non-Teaching</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="category_err"></div>
                        @error('category')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" value="{{ (old('bankname'))?old('bankname'):$employee->bankname }}" id="bankname" name="bankname" class="form-control @error('bankname') is-invalid @enderror" placeholder="Bank Name" >
                        <div class="alert text-danger" style="display: none" id="bankname_err"></div>
                        @error('bankname')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input type="number" value="{{ (old('empaccno'))?old('empaccno'):$employee->empaccno }}" id="empaccno" name="empaccno" class="form-control @error('empaccno') is-invalid @enderror" placeholder="Account Number" >
                        <div class="alert text-danger" style="display: none" id="empaccno_err"></div>
                        @error('empaccno')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Center <span class="text-danger">*</span></label>
                        <select name="center" id="center" class="form-control @error('center') is-invalid @enderror text-capitalize" >
                            <option value="">Select Center</option>
                            @foreach($centers as $center)
                            <option value="{{$center->id}}" {{(old('center')==$center->id||$employee->center==$center->id)?'selected':''}}>{{$center->centername}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="center_err"></div>
                        @error('center')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">GPF Number <span class="text-secondary">(optional)</span></label>
                        <input type="number" value="{{ (old('gpfno'))?old('gpfno'):$employee->gpfno }}" id="gpfno" name="gpfno" class="form-control" placeholder="GPF Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">NPS Number <span class="text-secondary">(optional)</span></label>
                        <input type="number" value="{{ (old('npsno'))?old('npsno'):$employee->npsno }}" id="npsno" name="npsno" class="form-control" placeholder="NPS Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">PF/NPS Category <span class="text-danger">*</span></label>
                        <select name="pf_nps_cat" id="pf_nps_cat" class="form-control @error('pf_nps_cat') is-invalid @enderror" >
                            <option value="">Select Category</option>
                            <option value="pf" {{(old('pf_nps_cat')=="pf"||$employee->pf_nps_cat=="pf")?'selected':''}}>PF</option>
                            <option value="nps" {{(old('pf_nps_cat')=="nps"||$employee->pf_nps_cat=="nps")?'selected':''}}>NPS</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="pf_nps_cat_err"></div>
                        @error('pf_nps_cat')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn btn-light mr-2 goBack" type="button">Go Back</button>
                <button class="btn btn-primary saveNext" id="section-2-button" type="button">Save & Next</button>
            </div>
        </div>
        <div class="section-3-form section-forms" style="display: none">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Had Previous Experience? <span class="text-danger">*</span></label>
                        <select name="prev_exp" id="prev_exp" class="form-control @error('prev_exp') is-invalid @enderror" >
                            <option value="">Select Yes/No</option>
                            <option value="yes" {{(old('prev_exp')=="yes"||$employee->prev_exp=="yes")?'selected':''}}>Yes</option>
                            <option value="no" {{(old('prev_exp')=="no"||$employee->prev_exp=="no")?'selected':''}}>No</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="prev_exp_err"></div>
                        @error('prev_exp')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Previous Org. Name <span class="text-secondary">(If any)</span></label>
                        <input type="text" value="{{old('prevorgname')?old('prevorgname'):$employee->prevorgname}}" id="prevorgname" name="prevorgname" class="form-control @error('prevorgname') is-invalid @enderror" placeholder="Previous Organisation Name">
                        <div class="alert text-danger" style="display: none" id="prevorgname_err"></div>
                        @error('prevorgname')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Total income recieved till DOJ <span class="text-secondary">(Optional)</span></label>
                        <input type="number" value="{{ (old('totincomerec'))?old('totincomerec'):$employee->totincomerec }}" id="totincomerec" name="totincomerec" class="form-control" placeholder="Income Amount">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Total income tax recovered till DOJ <span class="text-secondary">(Optional)</span></label>
                        <input type="number" value="{{ (old('totincometax'))?old('totincometax'):$employee->totincometax }}" id="totincometax" name="totincometax" class="form-control" placeholder="Income Tax Amount">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Medical Examination <span class="text-secondary">(Optional)</span></label>
                        <input type="date" value="{{ (old('domedicalexam'))?old('domedicalexam'):$employee->domedicalexam }}" id="domedicalexam" name="domedicalexam" class="form-control" placeholder="Date of Medical Examination">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pay <span class="text-secondary">(Optional)</span></label>
                        <input type="number" value="{{ (old('emppay'))?old('emppay'):$employee->emppay }}" id="emppay" name="emppay" class="form-control" placeholder="Pay" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pay Scale <span class="text-secondary">(Optional)</span></label>
                        <input type="number" value="{{ (old('emppayscale'))?old('emppayscale'):$employee->emppayscale }}" id="emppayscale" name="emppayscale" class="form-control" placeholder="Pay Scale" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pay Scale Level <span class="text-secondary">(Optional)</span></label>
                        <select name="payscallvl" id="payscallvl" class="form-control">
                            <option value="">Select PayScale Level</option>
                            @foreach($paylevels as $level)
                                <option value="{{$level->id}}" {{(old('payscallvl')==$level->id || $employee->payscallvl == $level->id)? "selected" :""}}>{{$level->paylevel}}</option>
                            @endforeach
                        </select>
                        <!-- <input type="number" value="{{ (old('payscallvl'))?old('payscallvl'):$employee->payscallvl }}" id="payscallvl" name="payscallvl" class="form-control" placeholder="Pay Scale Level"> -->
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn btn-light mr-2 goBack" type="button">Go Back</button>
                <button class="btn btn-primary saveNext" id="section-3-button" type="button">Save & Next</button>
            </div>
        </div>
        <div class="section-4-form section-forms" style="display: none">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Staying in Quarters? <span class="text-danger">*</span></label>
                        <select name="quarters" id="quarters" class="form-control @error('quarters') is-invalid @enderror" >
                            <option value="">Select Yes/No</option>
                            <option value="yes" {{(old('quarters')=="yes"||$employee->quarters=="yes")?'selected':''}}>Yes</option>
                            <option value="no" {{(old('quarters')=="no"||$employee->quarters=="no")?'selected':''}}>No</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="quarters_err"></div>
                        @error('quarters')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Quarters No</label>
                        <input type="text" value="{{ (old('quartersno'))?old('quartersno'):$employee->quartersno }}" id="quartersno" name="quartersno" class="form-control @error('quartersno') is-invalid @enderror" placeholder="Quarters No">
                        <div class="alert text-danger" style="display: none" id="quartersno_err"></div>
                        @error('quartersno')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Occupied</label>
                        <input type="date" value="{{ (old('doccupied'))?old('doccupied'):$employee->doccupied }}" id="doccupied" name="doccupied" class="form-control @error('doccupied') is-invalid @enderror" placeholder="Date of Occupied">
                        <div class="alert text-danger" style="display: none" id="doccupied_err"></div>
                        @error('doccupied')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date of Vacated <span class="text-secondary">(Optional)</span></label>
                        <input type="date" value="{{ (old('dovacated'))?old('dovacated'):$employee->dovacated }}" id="dovacated" name="dovacated" class="form-control" placeholder="Date of Vacated">
                        <div class="alert text-danger" style="display: none" id="dovacated_err"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Eligible for HRA <span class="text-danger">*</span></label>
                        <select name="eligiblehra" id="eligiblehra" class="form-control @error('eligiblehra') is-invalid @enderror" >
                            <option value="">Select Yes/No</option>
                            <option value="yes" {{(old('eligiblehra')=="yes"||$employee->eligiblehra=="yes")?'selected':''}}>Yes</option>
                            <option value="no" {{(old('eligiblehra')=="no"||$employee->eligiblehra=="no")?'selected':''}}>No</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="eligiblehra_err"></div>
                        @error('eligiblehra')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Physically Handicapped <span class="text-danger">*</span></label>
                        <select name="handicap" id="handicap" class="form-control @error('handicap') is-invalid @enderror" >
                            <option value="">Select Yes/No</option>
                            <option value="yes" {{(old('handicap')=="yes"||$employee->handicap=="yes")?'selected':''}}>Yes</option>
                            <option value="no" {{(old('handicap')=="no"||$employee->handicap=="no")?'selected':''}}>No</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="handicap_err"></div>
                        @error('handicap')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pensioner or NOP <span class="text-danger">*</span></label>
                        <select name="prnop" id="prnop" class="form-control @error('prnop') is-invalid @enderror" >
                            <option value="">Select Type</option>
                            <option value="pensioner" {{(old('prnop')=="pensioner"||$employee->prnop=="pensioner")?'selected':''}}>Pensioner</option>
                            <option value="nop" {{(old('prnop')=="nop"||$employee->prnop=="nop")?'selected':''}}>NOP</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="prnop_err"></div>
                        @error('prnop')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row justify-end">
                <div class="col-md-4">
                    <div class="form-group" id="pen_cat_div">
                        <label class="form-label">Pension Category</label>
                        <select name="pen_cat" id="pen_cat" class="form-control @error('pen_cat') is-invalid @enderror" >
                            <option value="">Select Category</option>
                            <option value="sp" {{(old('pen_cat')=="sp"||$employee->pen_cat=="sp")?'selected':''}}>Single Pensioner</option>
                            <option value="fp" {{(old('pen_cat')=="fp"||$employee->pen_cat=="fp")?'selected':''}}>Family Pensioner</option>
                        </select>
                        <div class="alert text-danger" style="display: none" id="pen_cat_err"></div>
                        @error('pen_cat')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn btn-light mr-2 goBack" type="button">Go Back</button>
                <button class="btn btn-primary saveNext">Update</button>
            </div>
        </div>
    </form>
    <script src="{{asset('js/employees.js')}}"></script>
    <script>
        $(document).ready((e)=>{
            
            let cities = {{ Js::from($cities) }};
            let errorArr = {{ Js::from($errs) }};

            $("#empstate").change((e)=>{
                const citiesOpt = getCities(e,cities);
                if(citiesOpt!=""){
                    $("#empcity").html("<option value=''>Select City</option>"+citiesOpt);
                    let city = {{Js::from(old('empcity'))}}
                    let city_mod = {{Js::from($employee->empcity)}}
                    if(city!="" && city!=null){
                        $("#empcity").val(city);
                    }else{
                        $("#empcity").val(city_mod);
                    }
                }
            })

            $("#empstate").change();

            if(errorArr.length>0){
                changeSectionToshowErrors(errorArr);
            }

        })
    </script>
</div>
@endsection
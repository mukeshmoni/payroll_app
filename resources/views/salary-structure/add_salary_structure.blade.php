@extends('layouts.app')

@section('content')
<div class="salary-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Salary Structure</h4>
        <a href="{{route('salary-structure')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route('salary-structure.add_salary_structure')}}" method="POST" id="SalaryForm" autocomplete>
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="row">
                <div class="col-md-3 d-none">
                    <div class="form-group">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" id="department" class="form-control @error('department') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                            <option value="{{$dept->id}}" {{(old('departments')==$dept->id)?'selected':''}}>{{$dept->department}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger errorTxt" style="display: none" id="department_err"></div>
                        @error('department')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 d-none">
                    <div class="form-group">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <select name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $desg)
                            <option value="{{$desg->id}}" {{(old('designation')==$desg->id)?'selected':''}}>{{$desg->designation}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger errorTxt" style="display: none" id="designation_err"></div>
                        @error('designation')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="teaching">Teaching Staffs</option>
                            <option value="non-teaching">Non Teaching Staffs</option>
                        </select>
                        <div class="alert text-danger errorTxt" style="display: none" id="category_err"></div>
                        @error('category')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee" id="employee" class="form-control @error('employee') is-invalid @enderror" required>
                            <option value="">Select Employee</option>
                        </select>
                        <div class="alert text-danger errorTxt" style="display: none" id="employee_err"></div>
                        @error('employee')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('basicsalary')}}" id="basicsalary" name="basicsalary" class="form-control @error('basicsalary') is-invalid @enderror" placeholder="Basic Salary" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="basicsalary_err"></div>
                        @error('basicsalary')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- <hr> --}}
            <div class="mb-2" style="font-weight: bold">Allowances:</div>
            @if($arrear_da>0)
                <div class="alert alert-warning">DA Arrear {{$arrear_da}}% will be added for {{$arrear_month}} months</div>
            @endif
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">DA ({{$da}}%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('da')}}" id="da" name="da" class="form-control @error('da') is-invalid @enderror" placeholder="Dearness Allowances" required>
                            <input type="hidden" id="da_perc" name="da_perc" value="{{$da}}" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="da_err"></div>
                        @error('da')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">HRA (<span id="hra_perc_text"></span>%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('hra')}}" id="hra" name="hra" class="form-control @error('hra') is-invalid @enderror" placeholder="House Rent Allowances" required>
                            <input type="hidden" id="hra_perc" name="hra_perc" value="0" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="hra_err"></div>
                        @error('hra')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Slab Amount<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('slab')?old('slab'):0}}" id="slab" name="slab" class="form-control @error('slab') is-invalid @enderror"  placeholder="Slab Amount" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="slab_err"></div>
                        @error('slab')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Transport Allowance <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('transport')}}" id="transport" name="transport" class="form-control @error('transport') is-invalid @enderror" placeholder="Transport Allowances" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="transport_err"></div>
                        @error('transport')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row justify-between">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Misc. Allowance <span class="text-secondary">(Optional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('misc')?old('misc'):0}}" id="misc" name="misc" class="form-control @error('misc') is-invalid @enderror" placeholder="Misc. Allowances">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="misc_err"></div>
                        @error('misc')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Gross Salary <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('gross_salary')?old('gross_salary'):0}}" id="gross_salary" readonly name="gross_salary" class="form-control @error('gross_salary') is-invalid @enderror" placeholder="Gross Salary">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="gross_salary_err"></div>
                        @error('gross_salary')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="container-fluid">
                @error('allowance_type')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                @error('allowance_amount')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                <div class="alert text-danger errorTxt" style="display: none" id="allowance_type_err"></div>
                <div class="alert text-danger errorTxt" style="display: none" id="allowance_amount_err"></div>
                <div class="row" id="add_allowance"></div>
            </div>
            <div class="add_allowance_head text-center">
                <button type="button" class="btn btn-outline-light btn-sm rounded border shadow-sm mt-4" id="allowance_add_btn" style="width: 50%">
                    <i class="mdi mdi-plus" style="vertical-align: middle;"></i>Add Allowance
                </button>
            </div>
            <hr class="m-4">
            <div class="container-fluid">
                @error('la_type')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                @error('la_amount')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                <div class="alert text-danger errorTxt" style="display: none" id="la_type_err"></div>
                <div class="alert text-danger errorTxt" style="display: none" id="la_amount_err"></div>
                <div class="row" id="add_loans_advance"></div>
            </div>
            <div class="mb-2" style="font-weight: bold">Deductions:</div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Provident Fund <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('pf')}}" id="pf" name="pf" class="form-control @error('pf') is-invalid @enderror" placeholder="Provident Fund" required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="pf_err"></div>
                        @error('pf')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">NPS-Employee <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('npse')}}" id="npse" name="npse" class="form-control @error('npse') is-invalid @enderror" placeholder="NPS-Employee">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="npse_err"></div>
                        @error('npse')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 d-none">
                    <div class="form-group">
                        <label class="form-label">NPS-Employer <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('npser')}}" id="npser" name="npser" class="form-control @error('npser') is-invalid @enderror" placeholder="NPS-Employer">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="npser_err"></div>
                        @error('npser')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Income Tax(TDS) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('it')}}" id="it" name="it" class="form-control @error('it') is-invalid @enderror" placeholder="Income Tax">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="it_err"></div>
                        @error('it')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Total Deduction <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('total_deduction')}}" id="total_deduction" name="total_deduction" class="form-control @error('total_deduction') is-invalid @enderror" placeholder="Total Deduction" readonly required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="total_deduction_err"></div>
                        @error('total_deduction')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">EPS <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('eps')}}" id="eps" name="eps" class="form-control @error('eps') is-invalid @enderror" placeholder="EPS" required>
                        </div>
                        <div class="alert text-danger" style="display: none" id="eps_err"></div>
                        @error('eps')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Employer Provident Fund <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('epf')}}" id="epf" name="epf" class="form-control @error('epf') is-invalid @enderror" placeholder="Employer Provident Fund" required>
                        </div>
                        <div class="alert text-danger" style="display: none" id="epf_err"></div>
                        @error('epf')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}
            </div>
            <div class="row justify-end">
                <div class="form-group col-md-9">
                    <label for="narration">Narration:</label>
                    <input type="text" class="form-control" name="narration" id="narration" value="{{old('narration')}}">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Net Salary <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('net_salary')}}" id="net_salary" name="net_salary" class="form-control @error('net_salary') is-invalid @enderror" placeholder="Net Salary" readonly required>
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="net_salary_err"></div>
                        @error('net_salary')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                @error('deduction_type')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                @error('deduction_amount')
                    <div class="alert text-danger">{{ $message }}</div>
                @enderror
                <div class="alert text-danger errorTxt" style="display: none" id="deduction_type_err"></div>
                <div class="alert text-danger errorTxt" style="display: none" id="deduction_amount_err"></div>
                <div class="row" id="add_deductions"></div>
            </div>
            <div class="add_deductions_head text-center">
                <button type="button" class="btn btn-outline-light btn-sm rounded border shadow-sm mt-4" id="deduction_add_btn" style="width: 50%">
                    <i class="mdi mdi-plus" style="vertical-align: middle;"></i>Add Deduction
                </button>
            </div>
            
            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
           
        
    </form>
    <script>
        const $allowances = {{ Js::from($allowances) }};
        const $deductions = {{ Js::from($deductions) }};
        const $da = {{ Js::from($da) }};
        const $modFlag = false;
        $nps = true;
        
    </script>
    <script src="{{asset('js/salaryStructure.js')}}"></script>

    <script>
        $(document).ready(()=>{
            $('#employee').select2();
            // $('#designation').select2();
        })
    </script>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="salary-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Pension Structure</h4>
        <a href="{{route('salary-structure')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route('salary-structure.add_pension_structure')}}" method="POST" id="SalaryForm" autocomplete>
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
                        <label class="form-label">Basic Pension <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('basicsalary')}}" id="basicsalary" name="basicsalary" class="form-control @error('basicsalary') is-invalid @enderror" placeholder="Basic Pension" required>
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
                        <label class="form-label">Additional Pension</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('addtl_pension')}}" id="addtl_pension" name="addtl_pension" class="form-control @error('addtl_pension') is-invalid @enderror" placeholder="Additional Pension">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="addtl_pension_err"></div>
                        @error('addtl_pension')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
                        <label class="form-label">Medical Allowance <span class="text-secondary">(Optional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('medic_allow')?old('medic_allow'):0}}" id="medic_allow" name="medic_allow" class="form-control @error('medic_allow') is-invalid @enderror" placeholder="Medical Allowances">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="medic_allow_err"></div>
                        @error('medic_allow')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
            <div class="row justify-end">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Less. Commutation</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('less_comm')}}" id="less_comm" name="less_comm" class="form-control @error('less_comm') is-invalid @enderror" placeholder="Less. Commutation">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="less_comm_err"></div>
                        @error('less_comm')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Gross Total <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('gross_salary')?old('gross_salary'):0}}" id="gross_salary" readonly name="gross_salary" class="form-control @error('gross_salary') is-invalid @enderror" placeholder="Gross Total">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="gross_salary_err"></div>
                        @error('gross_salary')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
                        <label class="form-label">Misc. Recovery</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('misc_rec')}}" id="misc_rec" name="misc_rec" class="form-control @error('misc_rec') is-invalid @enderror" placeholder="Misc. Recovery">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="misc_rec_err"></div>
                        @error('misc_rec')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">IRG</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('irg')}}" id="irg" name="irg" class="form-control @error('irg') is-invalid @enderror" placeholder="IRG">
                        </div>
                        <div class="alert text-danger errorTxt" style="display: none" id="irg_err"></div>
                        @error('irg')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Income Tax</label>
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
            </div>
            <div class="row justify-end">
                <div class="form-group col-md-9">
                    <label for="narration">Narration:</label>
                    <input type="text" class="form-control" name="narration" id="narration" value="{{old('narration')}}">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Net Pension <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" value="{{old('net_salary')}}" id="net_salary" name="net_salary" class="form-control @error('net_salary') is-invalid @enderror" placeholder="Net Pension" readonly required>
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
    <script src="{{asset('js/p_salaryStructure.js')}}"></script>
    <script>
        $(document).ready(()=>{
            $('#employee').select2();
            // $('#designation').select2();
        })
    </script>
</div>
@endsection
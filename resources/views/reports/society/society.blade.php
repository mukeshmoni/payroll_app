@extends('layouts.app')

@section('content')
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<div class="employees-list p-2 rounded bg-white mb-2">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Society Report</h4>
        <div>
            <!-- <a href="{{route('loanadvance.export_loan_advance_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a> -->
            <!-- <a href="{{route('loanadvance.add_loanadvance')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Loans & Advances</a> -->
        </div>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <div class="employees-list p-2 rounded">
        <form action="{{route('reports.get_society_report')}}" target="_blank" method="POST" id="getLedger">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="month">Select month to view report</label>
                        <div class="input-group mb-3">
                            <input type="text" id="month" name="month" class="form-control" aria-describedby="basic-addon2"
                                value="{{ date('F-Y') }}" readonly required placeholder="MM/YY">
                            <label class="input-group-text m-0" id="basic-addon2" for="month"><i class="icon-clock menu-icon"
                                    style="font-size:16px"></i></label>
                        </div>
                        
                </div>
                <div class="form-group col-md-4">
                    <label for="category">Select category</label>
                    <div class="input-group mb-2">
                        <select name="category" id="category" class="form-control form-control-sm">
                            <option value="teaching">Teaching</option>
                            <option value="non-teaching">Non-Teaching</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4 flex align-items-center">
                    <label for=""></label>
                    <button type="submit" class="btn btn-primary btn-md rounded">Get Report</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Society Certificate</h4>
        <div>
            <!-- <a href="{{route('loanadvance.export_loan_advance_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a> -->
            <!-- <a href="{{route('loanadvance.add_loanadvance')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Loans & Advances</a> -->
        </div>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <div class="employees-list p-2 rounded">
        <form action="{{route('reports.get_society_certificate')}}" method="POST" id="attendanceForm" target="_blank">
            @csrf
            <div class="section-1-form section-forms">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-gray-700">Select Employee <span class="text-danger">*</span></label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-gray-700">Select Surety <span class="text-secondary">(If required)</span></label>
                            <select id="surety" name="surety" class="block w-full mt-1 rounded-md">
                                <option value="">---Select Surety ---</option>
                                @foreach ($employees as $employee)                            
                                    <option value="{{ $employee->empid }}" @if(old('empid')==$employee->empid) selected @endif>{{ $employee->empname }} - {{ $employee->empid }}</option>
                                @endforeach
                            </select>
                            <div class="alert text-danger text-capitalize" style="display: none" id="surety_err"></div>
                            @error('surety')
                                <div class="alert text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>      
                    <div class="col-md-4 flex align-items-center">
                        <button class="btn btn-primary btn-md rounded saveNext" id="submitForm" type="submit">Generate</button>
                    </div> 
                </div>    
            </div>   
        </form> 
    </div>
</div>
<script>
    $(document).ready((e) => {
        $("#month").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM-yy',
            onClose: function(dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $month = localStorage.setItem("nitttr_payroll_month", $(this).val());

                // $("#getMonth").submit();

            }
        });
        $month = $("#month").val();
        $month = localStorage.setItem("nitttr_payroll_month", $month);
    });
    $(function () {
        $("#empid").selectize();
        $("#surety").selectize();
    });
</script>
@endsection
@extends('layouts.app')
<style>
    .dropdown-menu {
        position: relative;
        z-index: 1050; /* Higher than most elements */
    }
</style>
@section('content')
<div class="br-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">New Cheque Register Entry</h4>
        <a href="{{route('cheque_register')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif

    <form action="{{route('cheque_register.store')}}" method="POST" id="billRegisterForm">
        @csrf
        <div class="section-1-form section-forms p-2">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="hidded" id="updateFlag" name="updateFlag" value="{{$cheque->cheque_no}}">
                        <label class="form-label">Cheque No. <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_no" name="cheque_no" class="form-control @error('cheque_no') is-invalid @enderror" placeholder="Cheque Number" value="{{ old('cheque_no')??$cheque->cheque_no }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="cheque_no_err"></div>
                        @error('cheque_no')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Bank-Acc.No. <span class="text-danger">*</span></label>
                        <select name="acc_no" id="acc_no" class="form-control @error('acc_no') is-invalid @enderror" required>
                            <option value="">Select Bank-Acc.No</option>
                            <option value="SBI-888" {{(old('acc_no')=="SBI-888" || $cheque->bank_acc_no=="SBI-888")?'selected':''}}>State Bank of India - A/C No.888</option>
                            <option value="SBI-130" {{(old('acc_no')=="SBI-130" || $cheque->bank_acc_no=="SBI-130")?'selected':''}}>State Bank of India - A/C No.130</option>
                            <option value="NITTT OWP A/c 10548" {{(old('acc_no')=="NITTT OWP A/c 10548" || $cheque->bank_acc_no=="NITTT OWP A/c 10548")?'selected':''}}>NITTT OWP A/c 10548</option>
                            <option value="GPF A/c-2193" {{(old('acc_no')=="GPF A/c-2193" || $cheque->bank_acc_no=="GPF A/c-2193")?'selected':''}}>GPF A/c - 2193</option>
                            <option value="Others" {{(old('acc_no')=="Others" || $cheque->bank_acc_no=="Others")?'selected':''}}>Others</option>
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="acc_no_err"></div>
                        @error('acc_no')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                        <select name="payment_mode" id="payment_mode" class="form-control @error('payment_mode') is-invalid @enderror" required>
                            <option value="">Select Mode</option>
                            <option value="NEFT" {{(old('payment_mode')=="NEFT" || $cheque->payment_mode=="NEFT")?'selected':''}}>NEFT</option>
                            <option value="RTGS" {{(old('payment_mode')=="RTGS" || $cheque->payment_mode=="RTGS")?'selected':''}}>RTGS</option>
                            <option value="CBS" {{(old('payment_mode')=="CBS" || $cheque->payment_mode=="CBS")?'selected':''}}>CBS</option>
                            <option value="DD" {{(old('payment_mode')=="DD" || $cheque->payment_mode=="DD")?'selected':''}}>DD</option>
                            <option value="Transfer" {{(old('payment_mode')=="Transfer" || $cheque->payment_mode=="Transfer")?'selected':''}}>Transfer</option>
                        </select>
                        <div class="alert text-danger text-capitalize" style="display: none" id="payment_mode_err"></div>
                        @error('payment_mode')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" id="cheque_date" name="cheque_date" class="form-control @error('cheque_date') is-invalid @enderror" placeholder="Date" value="{{ old('cheque_date')?old('cheque_date'):$cheque->date }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="cheque_date_err"></div>
                        @error('cheque_date')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">BR. No.</label>
                        <input type="number" id="br_id" name="br_id" class="form-control @error('br_id') is-invalid @enderror" placeholder="Bill Register Number" value="{{ old('br_id')}}">
                        <div class="alert text-danger text-capitalize" style="display: none" id="br_id_err"></div>
                        @error('br_id')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label font-italic text-gray-400">Click to fetch details of the BR</label>
                        <button type="button" class="btn btn-dark rounded d-block p-0 px-4" id="fetchBtn"><span style="height:40px;display:flex;align-items:center">Fetch</span></button>
                    </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Cheque Amount</label>
                        <input type="number" id="cheque_amount" name="cheque_amount" class="form-control @error('cheque_amount') is-invalid @enderror" readonly placeholder="Cheque Amount" value="{{ old('cheque_amount')??$cheque->total_amount}}">
                    </div>
                </div>
            </div>
            <hr>
            <div class="alert text-danger text-capitalize alert-danger rounded" style="display: none" id="form_err"></div>
            <div class="alert text-success text-capitalize alert-success rounded" style="display: none" id="form_success"></div>
            
            <div class="justify-content-center" id="loader" style="display: none;">
                <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" style="width:50px;height:50px;display: block;margin: 0 auto;" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve"><path fill="#000" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" /></path></svg>
            </div>
            <div class="table-responsive" id="br_list">
                @foreach($brDet as $data)
                <table class="table table-bordered" id="br_table">
                    <thead class="bg-inverse-secondary text-dark">
                        <th class="text-center">BR. No</th>
                        <th class="text-center">VR. No</th>
                        <th class="text-center">Particulars</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Head of Acc.</th>
                        <th class="text-center">Tot Amt.</th>
                        <th class="text-center">Actions</th>
                        <!-- <th class="text-center">Add Deductions</th> -->
                    </thead>
                    <tbody id="br_table_body">
                        <tr class="br_row">
                            <td style="width: 150px;">
                                <input type="number" id="br_no" name="br_no[]" class="form-control br_no" readonly placeholder="BR. No" value="{{$data[0]->br_no}}">
                            </td>
                            <td style="width: 150px;">
                                <input type="number" id="vr_no" name="vr_no[]" class="form-control vr_no" readonly placeholder="VR. No" value="{{$data[0]->vr_no}}">
                            </td>
                            <td>
                                <input type="text" id="particulars" name="particulars[]" class="form-control particulars" placeholder="Particulars" value="{{$data[0]->particulars}}">
                            </td>
                            <td style="width: 150px;">
                                <input type="number" id="amount" name="amount[]" class="form-control text-right amount" placeholder="Amount" value="{{$data[0]->gross_amount}}">
                            </td>
                            <td style="width: 150px;">
                                <select name="head_acc[]" id="head_acc" class="form-control head_acc">
                                    <option value="">Select Head</option>
                                    <option value="OH-31" {{($data[0]->head_of_acc=="OH-31")?'selected':''}}>OH-31</option>
                                    <option value="OH-35" {{($data[0]->head_of_acc=="OH-35")?'selected':''}}>OH-35</option>
                                    <option value="OH-36" {{($data[0]->head_of_acc=="OH-36")?'selected':''}}>OH-36</option>
                                </select>
                            </td>
                            <td style="width: 150px;">
                                <input type="number" id="total_amount" name="total_amount[]" class="form-control text-right total_amount" readonly placeholder="Total Amount">
                            </td>
                            <td style="width: 100px;">
                                <!-- <div class="d-flex justify-center align-items-center addEmployee">
                                    <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-info text-light shadow-md cursor-pointer">
                                        <i class="mdi mdi-account-plus m-0 p-0" style="font-size:18px"></i>
                                    </span>
                                </div> -->
                                <!-- <div class="btn-group">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false" onclick="$('#defaultDropdown').dropdown('toggle')">
                                        
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                                        <li><a class="dropdown-item" href="#">Add Employee</a></li>
                                        <li><a class="dropdown-item" href="#">Add Deduction</a></li>
                                        <li><a class="dropdown-item" href="#">Delete</a></li>
                                    </ul>
                                </div> -->
                                <a class="nav-link dropdown-toggle text-gray-700" href="#" data-toggle="dropdown" id="profileDropdown">
                                    <i class="mdi mdi-dots-vertical m-0 p-0" style="font-size:18px"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown p-2" aria-labelledby="profileDropdown">
                                    <a href="#" class="text-decoration-none d-block p-2 text-gray-800 addEmployee d-flex gap-4 align-items-center"><i class="mdi mdi-account-plus m-0 p-0" style="font-size:18px"></i>  <span>List Employee</span></a>
                                    <a href="#" class="text-decoration-none d-block p-2 text-gray-800 addDeduction d-flex gap-4 align-items-center"><i class="mdi mdi-percent m-0 p-0" style="font-size:18px"></i>  <span>Add Deduction</span></a>
                                    <a href="#" class="text-decoration-none d-block p-2 text-danger removeTable d-flex gap-4 align-items-center"><i class="mdi mdi-minus-circle m-0 p-0" style="font-size:18px"></i> <span>Delete</span></a>
                                </div>
                            </td>
                            <!-- <td style="width: 100px;">
                                <div class="d-flex justify-center align-items-center addDeduction">
                                    <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-info text-light shadow-md cursor-pointer">
                                        <i class="mdi mdi-percent m-0 p-0" style="font-size:18px"></i>
                                    </span>
                                </div>
                                
                            </td> -->
                        </tr>
                        @foreach($data as $ded)
                            @if($ded->empid)
                                <tr class="deduction_tr" data-id='{{$ded->br_no}}'>
                                    <td style="width: 150px;" colspan="2">
                                        <input type="hidden" id="employee_br_no" name="employee_br_no[]" class="form-control" readonly placeholder="BR. No">
                                        <input type="hidden" id="employee_vr_no" name="employee_vr_no[]" class="form-control" readonly placeholder="VR. No">
                                            <label class="form-label font-italic d-block text-gray-400">Select Employee</label>
                                            <input type="text" id="employee" name="employee[]" class="form-control form-control-sm employee" placeholder="Enter Employee Name" value="{{$ded->empid}}">
                                    </td>
                                    <td style="padding:0px">
                                        <table style="width:100%;">
                                            <tr>
                                                <td style="border:0px solid white;">
                                                    <label class="form-label font-italic text-gray-400">Select Deduction Type</label>
                                                    <select name="deduction_type[]" id="deduction_type" class="form-control form-control-sm">
                                                        <option value="">Select Type</option>
                                                        <option value='TDS' {{$ded->deduction_type=="TDS"?'selected':''}}>TDS on IT %</option>
                                                        <option value='GST' {{$ded->deduction_type=="GST"?'selected':''}}>GST %</option>
                                                    </select>
                                                </td>
                                                <td style="border:0px solid white;">
                                                    <label class="form-label font-italic text-gray-400">Enter Deduction %</label>
                                                    <input type="number" id="deduction_perc" name="deduction_perc[]" class="form-control form-control-sm deductionInput deduction_perc" placeholder="Enter %" value="{{$ded->deduction_perc}}">
                                                </td>
                                                <td style="border:0px solid white;">
                                                    <label class="form-label font-italic text-gray-400">Enter CESS % (If required)</label>
                                                    <input type="number" id="cess_perc" name="cess_perc[]" class="form-control form-control-sm deductionInput cess_perc" placeholder="Enter %" value="{{$ded->cess_perc}}">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Amount</label>
                                        <input type="number" id="deduction_tot_amount" name="deduction_tot_amount[]" class="form-control text-right form-control-sm deductionInput deduction_tot_amount" placeholder="" value="{{$ded->amount_wo_deduction}}">
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Deducted Amount</label>
                                        <input type="number" id="deduction_deducted_amount" name="deduction_deducted_amount[]" class="form-control text-right form-control-sm deductionInput deduction_deducted_amount" placeholder="" value="{{$ded->deducted_amount}}">
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Net Amount</label> 
                                        <input type="number" id="deduction_net_amount" name="deduction_net_amount[]" class="form-control text-right form-control-sm deductionInput deduction_net_amount" placeholder="" value="{{$ded->net_amount}}">
                                    </td>
                                    <td>
                                        <div class="d-flex justify-center align-items-center removeRow">
                                            <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-danger text-light shadow-md cursor-pointer">
                                                <i class="mdi mdi-minus m-0 p-0" style="font-size:18px"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr class="deduction_tr" data-id='{{$ded->br_no}}'>
                                    <td style="width: 150px;" colspan="2">
                                        <input type="hidden" id="decution_br_no" name="decution_br_no[]" class="form-control" readonly placeholder="BR. No">
                                        <input type="hidden" id="decution_vr_no" name="decution_vr_no[]" class="form-control" readonly placeholder="VR. No">
                                    </td>
                                    <td style="padding:0px">
                                        <table style="width:100%;">
                                            <tr>
                                                <td style="border:0px solid white">
                                                    <label class="form-label font-italic text-gray-400">Select Deduction Type</label>
                                                    <select name="deduction_type[]" id="deduction_type" class="form-control form-control-sm">
                                                        <option value="">Select Type</option>
                                                        <option value='TDS' {{$ded->deduction_type=="TDS"?'selected':''}}>TDS on IT %</option>
                                                        <option value='GST' {{$ded->deduction_type=="GST"?'selected':''}}>GST %</option>
                                                    </select>
                                                </td>
                                                <td style="border:0px solid white;">
                                                    <label class="form-label font-italic text-gray-400">Enter Deduction %</label>
                                                    <input type="number" id="deduction_perc" name="deduction_perc[]" class="form-control form-control-sm deductionInput deduction_perc" placeholder="Enter %" value="{{$ded->deduction_perc}}">
                                                </td>
                                                <td style="border:0px solid white;">
                                                    <label class="form-label font-italic text-gray-400">Enter CESS % (If required)</label>
                                                    <input type="number" id="cess_perc" name="cess_perc[]" class="form-control form-control-sm deductionInput cess_perc" placeholder="Enter %" value="{{$ded->cess_perc}}">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Amount</label>
                                        <input type="number" id="deduction_tot_amount" name="deduction_tot_amount[]" class="form-control text-right form-control-sm deductionInput deduction_tot_amount" placeholder="" value="{{$ded->amount_wo_deduction}}">
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Deducted Amount</label>
                                        <input type="number" id="deduction_deducted_amount" name="deduction_deducted_amount[]" class="form-control text-right form-control-sm deductionInput deduction_deducted_amount" placeholder="" value="{{$ded->deducted_amount}}">
                                    </td>
                                    <td>
                                        <label class="form-label font-italic text-gray-400">Net Amount</label>
                                        <input type="number" id="deduction_net_amount" name="deduction_net_amount[]" class="form-control text-right form-control-sm deductionInput deduction_net_amount" placeholder="" value="{{$ded->net_amount}}">
                                    </td>
                                    <td>
                                        <div class="d-flex justify-center align-items-center removeRow">
                                            <span class="d-flex justify-center align-items-center rounded-full h-10 w-10 bg-danger text-light shadow-md cursor-pointer">
                                                <i class="mdi mdi-minus m-0 p-0" style="font-size:18px"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
            <div class="text-right mt-4">
                <button class="btn btn-primary saveNext" id="brSubmitForm" type="submit">Submit Register</button>
            </div>
        </div>
    </form>

    <script src="{{asset('js/chequeRegister.js')}}">
    </script>
    <script>
        $(document).ready((e)=>{
            $data = {!! json_encode($br_data) !!};
            console.log($data);
            $data.forEach(br_data => {
                formData[br_data.id]=br_data;
                formData[br_data.id].deductions=[];
                formData[br_data.id].employees=[];
            });
            $(".amount").change();
            $(".deduction_tot_amount").keyup();
            console.log(formData);
        })
    </script>
</div>
@endsection

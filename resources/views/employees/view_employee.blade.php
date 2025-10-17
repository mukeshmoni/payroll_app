@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <a href="{{route('employees')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger rounded btn-sm" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
        <div>
            <a href="#" onclick="printDiv('DivIdToPrint');" class="btn btn-dark btn-sm mr-2 rounded" data-toggle="tooltip" data-placement="bottom" title="Print"><i class="mdi mdi-printer"></i></a>
            <a href="{{route('employees.modify_employee',['id'=>Crypt::encryptString($employee->id)])}}" class="btn btn-info btn-sm rounded" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div id="DivIdToPrint">
        <h3 class="text-center text-uppercase">NITTTR-Chennai</h3>
        <h4 class="text-center">Employee Details</h4>
        <table class="table table-hover table-bordered mt-2" id="employeesTable">
            <th class="text-center">Employee ID</th>
            <th class="text-center">Employe Name</th>
            <th class="text-center">Contact</th>
            <th class="text-center">Email</th>
            <tr>
                <td class="text-center">{{$employee->empid}}</td>
                <td class="text-capitalize text-center">{{$employee->empname}}</td>
                <td class="text-center">{{$employee->empcontact}}</td>
                <td class="text-center">{{$employee->empemail}}</td>
            </tr>
        </table>

        <table class="table table-hover table-bordered mt-4" id="employeesTable">
            <tbody>
                <tr>
                    <th colspan="2" class="text-center">Personal Details</th>
                </tr>
                    <tr>
                        <th class="text-left">Father's Name</th>
                        <td class="text-capitalize text-center">{{$employee->fathername}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Mother's Name</th>
                        <td class="text-capitalize text-center">{{$employee->mothername}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Date of Birth</th>
                        <td class="text-capitalize text-center">{{$employee->empdob?date("d-M-Y",strtotime($employee->empdob)):'-'}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Sex</th>
                        <td class="text-capitalize text-center">{{$employee->empgender}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Marital Status</th>
                        <td class="text-capitalize text-center">{{$employee->maritalstatus}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">PAN Number</th>
                        <td class="text-capitalize text-center">{{$employee->emppanno}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Aadhaar Number</th>
                        <td class="text-capitalize text-center">{{$employee->empaadhaarno}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Address</th>
                        <td class="text-capitalize text-center">{{$employee->empaddress}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">State</th>
                        <td class="text-capitalize text-center">{{$employee->state}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">City</th>
                        <td class="text-capitalize text-center">{{$employee->city}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Pincode</th>
                        <td class="text-capitalize text-center">{{$employee->pincode}}</td>
                    </tr>
                    
                <tr>
                    <th colspan="2" class="text-center">Official Details</th>
                </tr>
                    <tr>
                        <th class="text-left">Employee Category</th>
                        <td class="text-capitalize text-center">{{$employee->category}} Staff</td>
                    </tr>
                    <tr>
                        <th class="text-left">Designation</th>
                        <td class="text-capitalize text-center">{{$employee->desg_name}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Department</th>
                        <td class="text-capitalize text-center">{{$employee->dept_name}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Center</th>
                        <td class="text-capitalize text-center">{{$employee->centername}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Date of Joining</th>
                        <td class="text-capitalize text-center">{{$employee->empdoj?date("d-M-Y",strtotime($employee->empdoj)):'-'}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Date of Retirement</th>
                        <td class="text-capitalize text-center">{{($employee->empdor)?date("d-M-Y",strtotime($employee->empdor)):"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Bank Name</th>
                        <td class="text-capitalize text-center">{{$employee->bankname}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Bank Account Number</th>
                        <td class="text-capitalize text-center">{{$employee->empaccno}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">GPF Number</th>
                        <td class="text-capitalize text-center">{{($employee->gpfno)?$employee->gpfno:"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">NPS Number</th>
                        <td class="text-capitalize text-center">{{($employee->npsno)?$employee->npsno:"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">PF/NPS Category</th>
                        <td class="text-uppercase text-center">{{$employee->pf_nps_cat}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Have previous experience?</th>
                        <td class="text-capitalize text-center">{{$employee->prev_exp}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Previous Org. Name</th>
                        <td class="text-capitalize text-center">{{($employee->prevorgname)?$employee->prevorgname:"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Total income received till DOJ</th>
                        <td class="text-capitalize text-center"><span>&#8377;</span>{{number_format($employee->totincomerec)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Total income tax covered till DOJ</th>
                        <td class="text-capitalize text-center"><span>&#8377;</span>{{number_format($employee->totincometax)}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Date of Medical examination</th>
                        <td class="text-capitalize text-center">{{($employee->domedicalexam)?date("d-M-Y",strtotime($employee->domedicalexam)):"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Pay</th>
                        <td class="text-capitalize text-center">{{($employee->emppay)?$employee->emppay:"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Pay Scale</th>
                        <td class="text-capitalize text-center">{{($employee->emppayscale)?$employee->emppayscale:"-"}}</td>
                    </tr>
                    <tr>
                        <th class="text-left">Pay Scale Level</th>
                        <td class="text-capitalize text-center">{{($employee->paylevel)?$employee->paylevel:"-"}}</td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center">Quarters Details</th>
                    </tr>
                        <tr>
                            <th class="text-left">Staying in Quarters?</th>
                            <td class="text-capitalize text-center">{{$employee->quarters}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Quarters Number</th>
                            <td class="text-capitalize text-center">{{($employee->quartersno)?$employee->quartersno:"-"}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Date of Occupied</th>
                            <td class="text-capitalize text-center">{{($employee->doccupied)?date("d-M-Y",strtotime($employee->doccupied)):"-"}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Date of Vacated</th>
                            <td class="text-capitalize text-center">{{($employee->dovacated)?date("d-M-Y",strtotime($employee->dovacated)):"-"}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Eligible for HRA?</th>
                            <td class="text-capitalize text-center">{{$employee->eligiblehra}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Physically Handicap?</th>
                            <td class="text-capitalize text-center">{{$employee->handicap}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Pensionar or NOP</th>
                            <td class="text-capitalize text-center">{{$employee->prnop}}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Pension Category</th>
                            <td class="text-capitalize text-center">
                                @if($employee->pen_cat=="sp")
                                    Single Pensioner
                                @elseif($employee->pen_cat=="fp")
                                    Family Pensioner
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
            
            </tbody>
        </table>
</div>

</div>

@endsection
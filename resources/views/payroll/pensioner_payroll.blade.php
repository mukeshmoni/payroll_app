@extends('layouts.app')

@section('content')
<style>
      .ui-datepicker-calendar {
            display: none;
        }
</style>
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Pensioner Payroll List</h4>
        <div>
            <!-- <a href="{{route('employees.export_employees')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a> -->
            <!-- <a href="{{route('salary-structure.add_salary_structure')}}" class="btn btn-primary "><i class="icon-plus mr-2"></i>Add Salary Structure</a> -->
        </div>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div class="form-group col-md-4">
        <label for="month">Select month to generate Payroll</label>
        <form action="{{route('pensioner_payroll.getMonth_payroll')}}" method="POST" id="getMonth">
            @csrf
            <div class="input-group mb-3">
                <input type="text" id="month" name="month" class="form-control" aria-describedby="basic-addon2" value="{{$month}}" readonly required placeholder="MM/YY">
                <label class="input-group-text m-0" id="basic-addon2" for="month"><i class="icon-clock menu-icon" style="font-size:16px"></i></label>
            </div>
        </form>
    </div>
    
    <table class="table table-stripped display nowrap" style="width:100%">
        <thead>
            <th class="text-left"></th>
            <th class="text-left">S.No</th>
            <th class="text-left">Category</th>
            <th class="text-left">Verified</th>
            <th class="text-left">Actions</th>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td class="text-center" style="cursor:pointer;" data-id="{{$category->category}}"><i class="ti-arrow-circle-right menu-icon drill_down_dept" data-id="{{$category->category}}"></i></td>
                <td>{{$loop->iteration}}</td>
                <td class="text-capitalize">{{$category->category}} Staffs</td>
                <td>{{$payrollCount[$category->category]}}/<span style="font-weight:bold">{{$category->employee_count}}</span></td>
                <td><a href="{{route('payroll.verify_department_payroll',['id'=>Crypt::encryptString($category->category),'month'=>Crypt::encryptString(date('Y-m',strtotime($month)))])}}" onclick="verifyPayroll(this.href);return false;" class="btn btn-sm btn-success">Generate Payroll</a></td>
                <!-- @if(date("Y-m")<=date("Y-m",strtotime($month)) && $payrollCount[$category->category]>0) -->
                <!-- @else
                    <td><a class="btn btn-sm btn-success disabled">Generate Payroll</a></td>
                @endif -->
            </tr>
            <tr class="{{$category->category}} bg-inverse-primary categories" style="display:none">
                <td colspan="5">
                    <table class="table table-stripped display nowrap" style="width:100%" id="{{$category->category}}Table">
                        <thead >
                            <th style="font-weight:600"></th>
                            <th style="font-weight:600">Employee ID</th>
                            <th style="font-weight:600">Employee Name</th>
                            <th style="font-weight:600">Designation</th>
                            <th style="font-weight:600">Options</th>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                                @if(strtolower($emp->category) == strtolower($category->category))
                                    <tr class="bg-light">
                                        <td class="text-center">
                                            @if(in_array($emp->empid, $verifiedEmployees))
                                                <span class="text-success"><i class="icon-check menu-icon" style="font-weight:bold"></i></span>
                                            @else
                                                <span class="text-danger"><i class="ti-close menu-icon"></i></span>
                                            @endif
                                        </td>
                                        <td>{{$emp->empid}}</td>
                                        <td>{{$emp->empname}}</td>
                                        <td>{{$emp->designation}}</td>
                                        <td>
                                            <a href="{{route('pensioner_payroll.verify_payroll',['id'=>Crypt::encryptString($emp->empid),'month'=>$month])}}" class="btn btn-sm btn-inverse-success" data-toggle="tooltip" data-placement="bottom" title="View Salary Structure"><i class="icon-eye menu-icon"></i></a>
                                            @if(in_array($emp->empid, $verifiedEmployees))
                                                <a href="{{ route('pensioner_payroll.getpayslip', ['id' => Crypt::encryptString($emp->empid), 'month' => $month]) }}" class="btn btn-sm btn-inverse-dark" data-toggle="tooltip" data-placement="bottom" title="Download Payslip"><i class="icon-file menu-icon"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="{{asset('js/payroll.js')}}"></script>
<script>
    // function delConfirm(url,empname){
    //     $.confirm({
    //             title: 'Delete Structure!',
    //             content: 'Are you sure you want to delete?',
    //             type: 'red',
    //             typeAnimated: true,
    //             buttons: {
    //                 confirm:{
    //                     btnClass: 'btn btn-danger',
    //                     action:function(){
    //                         window.location.href = url
    //                     }
    //                 },
    //                 cancel:{
    //                     btnClass: 'btn btn-dark',
    //                     action:function(){
    //                         return true;
    //                         $(".spinner-body").fadeOut();
    //                     }
    //                 },
    //             }
    //         });
    // }
    $(document).ready((e)=>{
        $("#month").datepicker({  
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM-yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $month = localStorage.setItem("nitttr_payroll_month",$(this).val());

                $("#getMonth").submit();

            }   
        }); 
        $month = $("#month").val();
        $month = localStorage.setItem("nitttr_payroll_month",$month);
        $("#teachingTable,#non-teachingTable").dataTable({
            lengthMenu: [10, 25, 50,100,500],
            responsive: true,
            ordering: false
        });

        // $("input[type=search]").on("focus",()=>{
        //     $(".categories").slideDown();
        // })
    })
    function verifyPayroll(url,empname){
        $.confirm({
            title: 'Generate Payroll for this Category!',
            content: 'Are you sure you want to verify?',
            type: 'green',
            typeAnimated: true,
            buttons: {
                confirm:{
                    btnClass: 'btn btn-success',
                    action:function(){
                        // $month = localStorage.getItem("nitttr_payroll_month");
                        // if($month){
                        //     console.log(url);
                        //     let urlNew = new URL(url.toString());
                        //     urlNew.searchParams.append('month', $month);
                        //     window.location.href = urlNew
                        // }
                        window.location.href = url
                        // $("#payrollVerify").submit();
                    }
                },
                cancel:{
                    btnClass: 'btn btn-dark',
                    action:function(){
                        return true;
                        $(".spinner-body").fadeOut();
                    }
                },
            }
        });
    }
</script>
@endsection
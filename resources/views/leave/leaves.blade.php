@extends('layouts.app')
@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Leave Requests</h4>
        <div>
            <a href="{{route('leaves.export_leave_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('leaves.add_leaves')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Leave</a>
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
        <table class="table" id="dess" id="dess">
            <thead>
                <th class="text-center">S.No</th>           
                <th class="text-center">Emp ID</th>
                <th class="text-center">Name</th>
                <th class="text-center">Leave Type</th>
                <th class="text-center">Start Date</th>
                <th class="text-center">End Date</th>
                <th class="text-center">Full/Half Day</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$leave->empid}}</td>
                        <td class="text-center">{{$leave->empname}}</td>
                        <td class="text-center text-uppercase">{{$leave->leavetype}}</td>
                        <td class="text-center">{{date("Y-m-d",strtotime($leave->startdt))}}</td>
                        <td class="text-center">{{date("Y-m-d",strtotime($leave->enddt))}}</td>
                        <td class="text-center">@if($leave->days=="0.5") Half Day @else Full Day @endif</td>
                        <td class="text-center">
                           <a href="{{route('leaves.modify_leaves',['id'=>Crypt::encryptString($leave->id)])}}" class="btn btn-info"><i class="mdi mdi-pencil"></i></a>
                            {{-- <a href="{{route('attendance.delete_attendance',['id'=>Crypt::encryptString($attendance->id)])}}" class="btn btn-danger">Delete</a> --}}
                            <a href="{{route('leaves.delete_leaves',['id'=>Crypt::encryptString($leave->id)])}}" onclick="delconfirm(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
   <script src="{{asset('js/attendance.js')}}"></script>
    <script> 
/*
    setTimeout(function(){
        $(".alert").fadeOut(400);
        }, 10000) 
*/
//Delete function confirm
function delconfirm(url) {
            $.confirm({
                title: 'Delete This Leave Request!',
                content: 'Are you sure you want to delete?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    confirm:{
                        btnClass: 'btn btn-danger',
                        action:function(){
                            window.location.href = url
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
// //For Days calculation
//     $('#fh').hide();
//     $(".dtclass").change((e)=>{
      
//         let start_date = new Date($("#startdt").val());
//         let end_date = new Date($("#enddt").val());
//         //alert(start_date);
//         var milli_secs = start_date.getTime() - end_date.getTime();
             
//             // Convert the milli seconds to Days 
//             var days = milli_secs / (1000 * 3600 * 24);
//             //document.getElementById("ans").innerHTML = Math.round(Math.abs(days));
//             let day = Math.round(Math.abs(days));
//             //alert(day);

//             if(day == 0){
//                 $('#fh').show();
//                 $('#mdays').val("");
//                 $("#days").attr("required", true);
//             } else {
//                 $('#fh').hide();
//                 $("#days").removeAttr("required");
//             }

//             if(day != 0){
//                 $('#mdays').val(day+1);
//             }
//             else{
//                 $('#mdays').val("1");
//             }
//     })

    //Data Table code
    jQuery(document).ready((e)=>{
    $('#dess').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 2 }
            ]
        });
    } );

    //For Employee Name with TExt box code
    // $(function () {
    // $("#empid").selectize();
    // });
    </script>


</div>
@endsection
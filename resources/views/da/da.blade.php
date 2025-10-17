@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">DA</h4>
        <div>
            <a href="{{route('da.export_da_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('da.add_da')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add DA</a>
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
                <th class="text-center">DA</th>
                <th class="text-center">Month</th>
                <th class="text-center">Year</th>               
                <th class="text-center">Remark</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody>
                @foreach($da as $dad)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$dad->da}}</td>
                        <td class="text-center text-uppercase">{{$dad->month}}</td>
                        <td class="text-center text-uppercase">{{$dad->year}}</td>                      
                        <td class="text-center">{{$dad->remark}}</td>
                        <td class="text-center">
                           <a href="{{route('da.modify_da',['id'=>Crypt::encryptString($dad->id)])}}" class="btn btn-info"><i class="mdi mdi-pencil"></i></a>
                            {{-- <a href="{{route('da.delete_da',['id'=>Crypt::encryptString($dad->id)])}}" class="btn btn-danger">Delete</a> --}}
                            <a href="{{route('da.delete_da',['id'=>Crypt::encryptString($dad->id)])}}" onclick="delconfirm(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
   <script src="{{asset('js/attendance.js')}}"></script>
    <script> 

//Delete function confirm
function delconfirm(url) {
            $.confirm({
                title: 'Delete This Entry!',
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

    </script>


</div>
@endsection
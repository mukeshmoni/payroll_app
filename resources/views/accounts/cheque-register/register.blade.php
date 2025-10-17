@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Cheque Register Lists</h4>
        <div>
            <a href="#" class="btn btn-dark " id="exportData"><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('cheque_register.register_entry')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Register Entry</a>
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
                <th class="text-center">Date</th>
                <th class="text-center">Cheque No</th>
                <th class="text-center">Bank</th>
                <th class="text-center">Payment Mode</th>               
                <th class="text-center">Cheque Amount</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody>
               @foreach($entries as $entry)
                    <tr data-no="{{$entry[0]->cheque_no}}" class="cursor-pointer">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{date("d-m-Y",strtotime($entry[0]->date))}}</td>
                        <td class="text-center text-uppercase">{{$entry[0]->cheque_no}}</td>
                        <td class="text-center">{{$entry[0]->bank_acc_no}}</td>
                        <td class="text-center">{{$entry[0]->payment_mode}}</td>
                        <td class="text-center">{{number_format($entry[0]->total_amount)}}</td>
                        <td class="text-center">
                            <a href="{{route('cheque_register.edit_cheque_register',['id'=>Crypt::encryptString($entry[0]->cheque_no)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                            <a href="{{route('cheque_register.delete_cheque_register',['id'=>Crypt::encryptString($entry[0]->cheque_no)])}}" onclick="deleteRecord(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
               @endforeach
            </tbody>
        </table>
    </div>
   
   <script src="{{asset('js/attendance.js')}}"></script>
    <script> 

    //Data Table code
    jQuery(document).ready((e)=>{
        $table = $('#dess').DataTable();

        $('#dess tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
        });
        
        $('#exportData').click(function () {
            cheques = [];
            $("table tr.selected").each((index,ele)=>{
                cheques.push($(ele).attr('data-no'));
            })
            console.log(cheques);
        });
    } );

    function deleteRecord(url) {
        $.confirm({
            title: 'Are you sure you want to delete Cheque Register!',
            content: 'You cannot undo this action',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    btnClass: 'btn btn-danger',
                    action: function() {
                        window.location.href = url
                    }
                },
                cancel: {
                    btnClass: 'btn btn-dark',
                    action: function() {
                        return true;
                        $(".spinner-body").fadeOut();
                    }
                },
            }
        });
    }

    </script>


</div>
@endsection
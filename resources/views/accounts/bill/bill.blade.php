@extends('layouts.app')

@section('content')
<div class="allowance-type-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Bill Register List</h4>
        <div>
            <a href="{{route('br.add_bill_register')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Create Bill Register</a>
        </div>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <table class="table table-hover table-stripped" id="billRegisterTable">
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Bill Date</th>
            <th class="text-center">Particulars</th>
            <th class="text-center">Amount (Rs.)</th>
            <th class="text-center">Name of Clerk</th>
            <th class="text-center">Received From</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @foreach($bill_register as $register)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{date("d-M-Y",strtotime($register->bill_date))}}</td>
                <td class="text-capitalize">{{$register->particulars}}</td>
                <td class="text-center">{{$register->amount}}</td>
                <td class="text-center">{{$register->name_of_clerk}}</td>
                <td class="text-center">{{$register->received_from}}</td>
                <td class="text-center">{{$register->vr_status}}</td>
                <td>
                    <a href="{{route('br.edit_bill_register',['id'=>Crypt::encryptString($register->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                    <a href="{{route('br.delete_bill_register',['id'=>Crypt::encryptString($register->id)])}}" onclick="deleteRecord(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready((e) => {
        $("#billRegisterTable").dataTable();
    })

    function deleteRecord(url) {
        $.confirm({
            title: 'Delete Bill Register!',
            content: 'Are you sure you want to delete?',
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
@endsection

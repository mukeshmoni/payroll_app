@extends('layouts.app')

@section('content')
<div class="deduction-type-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Deduction List</h4>
        <div>
            <a href="{{route('deduction.export_deduction_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('deduction.create_deduction_category')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Deduction</a>
        </div>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <table class="table table-hover table-stripped" id="deductionCategoryTable">
        <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Deduction Name</th>
            <th class="text-center">Deduction Category</th>
            <th class="text-center">Mode</th>
            <th class="text-center">Value</th>
            <th class="text-center">Created at</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$category->deduction_name}}</td>
                <td class="text-capitalize">{{$category->deduction_type_name}}</td>
                <td class="text-center">{{$mode[$category->mode]}}</td>
                <td class="text-center">{{$category->mode_value}}</td>
                <td class="text-center">{{date("d-M-Y",strtotime($category->created_at))}}</td>
                <td>
                    <a href="{{route('deduction.edit_deduction_category',['id'=>Crypt::encryptString($category->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                    <a href="{{route('deduction.delete_deduction_category',['id'=>Crypt::encryptString($category->id)])}}" onclick="deleteRecord(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready((e) => {
        $("#deductionCategoryTable").dataTable();
    })

    function deleteRecord(url) {
        $.confirm({
            title: 'Delete Deduction Category!',
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
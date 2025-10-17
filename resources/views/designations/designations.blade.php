@extends('layouts.app')

@section('content')
<div class="employees-list shadow-md p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Designations List</h4>
        <a href="{{route('designations.add_designations')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Designations</a>
    </div>
    <table class="table">
        <thead>
            <th>S.No</th>           
            <th>Designation</th>
            <th>Description</th>
            <th>Created at</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach($designations as $designation)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$designation->designation}}</td>
                    <td>{{$designation->desg_description}}</td>
                    <td>{{date("Y-m-d",strtotime($designation->created_at))}}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
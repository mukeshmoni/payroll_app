@extends('layouts.app')
@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h3 class="m-0">Payslips</h3>
        <!-- <div>
            <a href="{{route('leaves.export_leave_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('leaves.add_leaves')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Leave</a>
        </div> -->
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <div class="p-4 m-2">
        <div class="row">
            @foreach($payslips as $pay)
                <div class="col-md-2 mb-4">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <div class="text-center font-medium">
                                <span class="d-block text-secondary" style="font-size:40px">
                                    <i class="icon-paper menu-icon"></i>
                                </span>
                                <span class="d-block mt-4">
                                    {{date('M-Y',strtotime($pay->month))}}
                                </span>
                            </div>
                        </div>
                        <div class="card-body border-top p-2 bg-dark text-light hover:bg-primary">
                            <a href="{{ route('payroll.getpayslip', ['id' => Crypt::encryptString($pay->employee), 'month' => $pay->month]) }}" class="text-light text-decoration-none" target="_blank">
                                <div class="text-center">
                                    View
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
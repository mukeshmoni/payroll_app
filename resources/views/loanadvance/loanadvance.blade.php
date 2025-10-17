@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Loans Advances</h4>
        <div>
            <a href="{{route('loanadvance.export_loan_advance_list')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            <a href="{{route('loanadvance.add_loanadvance')}}" class="btn btn-primary"><i class="icon-plus mr-2"></i>Add Loans & Advances</a>
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
                <th class="text-center">Designation</th>
                <th class="text-center">Tot. Loan Amt.</th>
                <th class="text-center">Monthly Rec.</th>
                <th class="text-center">Balance Amt</th>
                <th class="text-center">Instal. No.</th>
                <th class="text-center">Adj. Instal. No.</th>
                <!-- <th class="text-center">Loan/Advance</th>
                <th class="text-center">Types</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Start Date</th>
                <th class="text-center">Tenure</th>
                <th class="text-center">Remark</th> -->
                <th class="text-center">Actions</th>
            </thead>
            <tbody>
                @foreach($la as $lad)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$lad->empid}}</td>
                    <td class="text-center">{{$lad->empname}}</td>
                    <td class="text-center">{{$lad->desg_name}}</td>
                    <td class="text-center">{{number_format($lad->totamt)}}</td>
                    <td class="text-center">{{number_format($lad->recovery)}}</td>
                    @if($lad->installment>0)
                    <td class="text-center">{{number_format(($lad->tenure-$lad->installment)*$lad->recovery)}}</td>
                    @else
                    <td class="text-center">{{number_format($lad->totamt)}}</td>
                    @endif
                    <td class="text-center">{{$lad->installment."/".$lad->tenure}}</td>
                    <td class="text-center">{{$lad->adj_instal_no}}</td>
                    <!-- <td class="text-center text-uppercase">{{$lad->loans_advances}}</td>
                    <td class="text-center">{{$lad->da_types}}</td>
                    <td class="text-center">{{$lad->amt}}</td>
                    <td class="text-center">{{date("Y-m-d",strtotime($lad->startdt))}}</td>
                    <td class="text-center">{{$lad->tenure}}</td>
                    <td class="text-center">{{$lad->remark}}</td> -->
                    <td class="text-center">
                        <a href="{{route('loanadvance.modify_loanadvance',['id'=>Crypt::encryptString($lad->id)])}}" class="btn btn-info"><i class="mdi mdi-pencil"></i></a>
                        {{-- <a href="{{route('loanadvance.delete_loanadvance',['id'=>Crypt::encryptString($lad->id)])}}" class="btn btn-danger">Delete</a> --}}
                        <a href="{{route('loanadvance.delete_loanadvance',['id'=>Crypt::encryptString($lad->id)])}}" onclick="delconfirm(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
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


        //Data Table code
        jQuery(document).ready((e) => {
            $('#dess').DataTable({
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 1
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                    {
                        responsivePriority: 3,
                        targets: 2
                    }
                ]
            });
        });
    </script>


</div>
@endsection
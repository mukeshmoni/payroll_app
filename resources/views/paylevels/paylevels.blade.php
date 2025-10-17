@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Pay Levels and Slab</h4>
        <a href="{{route('departments')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("paylevels.store_paylevels")}}" method="POST" id="paylevelsForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Pay Level <span class="text-danger">*</span></label>
                        <input type="text" id="paylevel" name="paylevel" class="form-control @error('paylevel') is-invalid @enderror " placeholder="Pay Level" value="{{ old('paylevel') }}" required>
                        <div class="alert  text-danger text-capitalize errorTxt" style="display: none" id="paylevel_err"></div>
                        @error('paylevel')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Slab Amount <span class="text-danger">*</span></label>
                        <input type="number" id="slab" name="slab" class="form-control @error('slab') is-invalid @enderror " placeholder="Slab Amount" value="{{ old('slab') }}" required>
                        <div class="alert text-danger text-capitalize errorTxt" style="display: none" id="slab_err"></div>
                        @error('slab')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
        </div>
    </form>
    <hr>
    <div class="employees-list p-2 rounded">
        <div class="d-flex justify-content-between align-items-center p-2 rounded">
            <h4 class="m-0">Pay Level and Slab List</h4>
            <div>
                <a href="{{route('paylevels.export_paylevels')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            </div>
        </div>
        
        
        <table class="table" id="dess" id="dess">
            <thead>
                <th>S.No</th>           
                <th>Pay Level</th>
                <th>Slab Amount</th>
                <th>Created at</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($paylevels as $paylevel)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$paylevel->paylevel}}</td>
                        <td>{{$paylevel->slab}}</td>
                        <td>{{date("Y-m-d",strtotime($paylevel->created_at))}}</td>
                        <td>
                            <a href="{{route('paylevels.modify_paylevel',['id'=>Crypt::encryptString($paylevel->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                            <a href="{{route('paylevels.delete_paylevel',['id'=>Crypt::encryptString($paylevel->id)])}}" onclick="delConfirm(this.href);return false;" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
    <script src="{{asset('js/paylevels.js')}}">
       
    </script>
    <script> setTimeout(function(){
        $(".alert").fadeOut(400);
    }, 10000) </script>
    <script>
        function delConfirm(url) {
            $.confirm({
                title: 'Delete Paylevel!',
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
    </script>


    <script>
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
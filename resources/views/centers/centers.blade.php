@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Centers</h4>
        <a href="{{route('departments')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route('centers.store_center')}}" method="POST" id="centersForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Center Name <span class="text-danger">*</span></label>
                        <input type="text" id="center" name="center" class="form-control @error('center') is-invalid @enderror " placeholder="Center Name" value="{{ old('center') }}" required>
                        <div class="alert  text-danger text-capitalize errorTxt" style="display: none" id="center_err"></div>
                        @error('center')
                            <span class="invalid-feedback mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">HRA % <span class="text-danger">*</span></label>
                        <input type="number" id="hra_perc" name="hra_perc" class="form-control @error('hra_perc') is-invalid @enderror " placeholder="HRA %" value="{{ old('hra_perc') }}" required>
                        <div class="alert  text-danger text-capitalize errorTxt" style="display: none" id="hra_perc_err"></div>
                        @error('hra_perc')
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
            <h4 class="m-0">Centers List</h4>
            <div>
                <a href="{{route('centers.export_centers')}}" class="btn btn-dark "><i class="icon-file mr-2"></i>Export Excel</a>
            </div>
        </div>
        
        
        <table class="table" id="dess" id="dess">
            <thead>
                <th>S.No</th>           
                <th>Center Name</th>
                <th>HRA %</th>
                <th>Created at</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($centers as $center)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="text-capitalize">{{$center->centername}}</td>
                        <td class="text-capitalize">{{$center->hra}}%</td>
                        <td>{{date("Y-m-d",strtotime($center->created_at))}}</td>
                        <td>
                            <a href="{{route('centers.modify_center',['id'=>Crypt::encryptString($center->id)])}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="mdi mdi-pencil"></i></a>
                            <a href="{{route('centers.delete_center',['id'=>Crypt::encryptString($center->id)])}}" onclick="delConfirm(this.href);return false;" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
    <script src="{{asset('js/centers.js')}}">
       
    </script>
    <script> setTimeout(function(){
        $(".alert").fadeOut(400);
    }, 10000) </script>
    <script>
        function delConfirm(url) {
            $.confirm({
                title: 'Delete Center!',
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
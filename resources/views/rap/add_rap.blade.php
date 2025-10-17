@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Residential Accomodation Percentage</h4>
       <!-- <a href="{{route('rap.add_rap')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a> -->
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route("rap.store_rap")}}" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
                
            <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="rap">RAP % : <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="rap" name="rap" placeholder="RAP" value="{{ old('rap') }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="rap_err"></div>
                        @error('rap')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from">Month-Year : <span class="text-danger">*</span></label>
                     
                        <input type="month" min="<?=date('Y',strtotime(date("Y")." -5 years"))?>-01" max="{{ date('Y') }}-12" class="form-control" id="from" name="from" placeholder="Month-Year" value="{{ old('from') }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="from_err"></div>
                        @error('from')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Remark <span class="text-secondary">(Optional)</span></label>
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Remarks If Any" value="{{ old('remark') }}">
                        <div class="alert text-danger" style="display: none" id="remark_err"></div>
                    </div>
                </div>                        
                
            </div>    
                   
            <div class="row">
               
                
            </div>     
            <input type="hidden" class="form-control" id="dval" name="dval">
            <input type="hidden" class="form-control" id="aval" name="aval">
            <div class="text-right">
                <button class="btn btn-primary saveNext" id="submitForm" type="button">Submit</button>
            </div>
        </div>
    </form>
    {{-- <hr> --}}   {{-- <hr> --}}
    <div class="employees-list p-2 rounded">
        <table class="table" id="dess" id="dess">
            <thead>
                <th class="text-center">S.No</th>           
                <th class="text-center">RAP</th>               
                <th class="text-center">Month (From-To)</th>     
                    
                <th class="text-center">Remark</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody>
                @foreach($rap as $dad)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$dad->rap_perc}}</td>                       
                        <td class="text-center "><label style="color: green;">{{ date('M, Y', strtotime($dad->from)); }} </label> -  @if($dad->to!='') <label style="color: red;"> {{ date('M, Y', strtotime($dad->to));}} </label> @else  Till Now @endif</td>   
                                           
                        <td class="text-center">{{$dad->remark}}</td>
                     
                        <td class="text-center">
                           <a href="{{route('rap.modify_rap',['id'=>Crypt::encryptString($dad->id)])}}" class="btn btn-info"><i class="mdi mdi-pencil"></i></a>
                            {{-- <a href="{{route('rap.delete_rap',['id'=>Crypt::encryptString($dad->id)])}}" class="btn btn-danger">Delete</a> --}}
                            <a href="{{route('rap.delete_rap',['id'=>Crypt::encryptString($dad->id)])}}" onclick="delconfirm(this.href);return false;" class="btn btn-danger"><i class="mdi mdi-delete"></i></a> 
                        </td>
                    
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
 <script src="{{asset('js/rap.js')}}"></script>
    <script> 
    //Only allow numeric
$('#rap').keypress(function (e) { 
    var charCode = (e.which) ? e.which : event.keyCode 
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
}); 

    //For Employee Name with TExt box code
    $(function () {
    $("#empid").selectize();
    });
    </script>
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
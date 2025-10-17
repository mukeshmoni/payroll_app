@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Employee Order</h4>
       <!-- <a href="{{route('rap.add_rap')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a> -->
    </div>
    @if (session()->has("status"))
        @if (session('status'))
            <div class="alert alert-success">{{session("message")}}</div>
        @else
            <div class="alert alert-danger">{{session("message")}}</div>
        @endif
    @endif
    <form action="{{route('employees.order')}}" method="POST" id="attendanceForm">
        @csrf
        <div class="section-1-form section-forms">
                
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category">Category: <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="teaching" {{old('category')=='teaching'?'selected':''}}>Teaching</option>
                            <option value="non-teaching" {{old('category')=='non-teaching'?'selected':''}}>Non-Teaching</option>
                        </select>
                        <!-- <input type="number" class="form-control" id="category" name="category" placeholder="Employee ID" value="{{ old('category') }}" required autofocus> -->

                        <div class="alert text-danger text-capitalize" style="display: none" id="category_err"></div>
                        @error('category')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="empid">Employee ID: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="empid" name="empid" placeholder="Employee ID" value="{{ old('empid') }}" required autofocus>

                        <div class="alert text-danger text-capitalize" style="display: none" id="empid_err"></div>
                        @error('empid')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="order">Order : <span class="text-danger">*</span></label>
                     
                        <input type="number" class="form-control" id="order" name="order" placeholder="Order" value="{{ old('order') }}" required>

                        <div class="alert text-danger text-capitalize" style="display: none" id="order_err"></div>
                        @error('order')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 flex align-items-center">
                    <div>
                        <button class="btn btn-primary saveNext" id="submitForm" type="submit">Submit</button>
                    </div>
                </div>
            </div>    
        </div>
    </form>

</div>
@endsection
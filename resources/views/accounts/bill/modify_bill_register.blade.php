@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Modify Bill Register</h4>
        <a href="{{route('br.bill_list')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <form action="{{route('br.update_bill_register',['id'=>Crypt::encryptString($bill_register->id)])}}" method="POST" id="billRegisterForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" id="bill_date" name="bill_date" class="form-control @error('bill_date') is-invalid @enderror" placeholder="Bill Date" value="{{ (old('bill_date'))?old('bill_date'):$bill_register->bill_date }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="bill_date_err"></div>
                        @error('bill_date')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Particulars <span class="text-danger">*</span></label>
                        <input type="text" id="particulars" name="particulars" class="form-control @error('particulars') is-invalid @enderror" placeholder="Particulars" value="{{ (old('particulars'))?old('particulars'):$bill_register->particulars }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="particulars_err"></div>
                        @error('particulars')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount (Rs.) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Amount" value="{{ (old('amount'))?old('amount'):$bill_register->amount }}" required>
                        </div>
                        <div class="alert text-danger text-capitalize" style="display: none" id="amount_err"></div>
                        @error('amount')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Name of Clerk <span class="text-danger">*</span></label>
                        <input type="text" id="name_of_clerk" name="name_of_clerk" class="form-control @error('name_of_clerk') is-invalid @enderror" placeholder="Name of Clerk" value="{{ (old('name_of_clerk'))?old('name_of_clerk'):$bill_register->name_of_clerk }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="name_of_clerk_err"></div>
                        @error('name_of_clerk')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Received From <span class="text-danger">*</span></label>
                        <input type="text" id="received_from" name="received_from" class="form-control @error('received_from') is-invalid @enderror" placeholder="Received From" value="{{ (old('received_from'))?old('received_from'):$bill_register->received_from }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="received_from_err"></div>
                        @error('received_from')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary saveNext" id="brSubmitForm" type="button">Update</button>
            </div>
        </div>
    </form>

    <script src="{{asset('js/billRegister.js')}}">

    </script>

</div>
@endsection

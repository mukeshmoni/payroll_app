@extends('layouts.app')

@section('content')
<div class="employees-list p-2 rounded bg-white">
    <div class="d-flex justify-content-between align-items-center p-2 rounded">
        <h4 class="m-0">Add Deduction</h4>
        <a href="{{route('deduction.deduction_category_list')}}" class="btn btn-light d-flex gap-10 fw-500 text-danger" style="font-weight: bold"><i class="mdi mdi-chevron-left" style="font-size:15px"></i><span>Back</span></a>
    </div>
    @if (session()->has("status"))
    @if (session('status'))
    <div class="alert alert-success">{{session("message")}}</div>
    @else
    <div class="alert alert-danger">{{session("message")}}</div>
    @endif
    @endif
    <form action="{{route('deduction.add_deduction_category')}}" method="POST" id="deductionCategoryForm">
        @csrf
        <div class="section-1-form section-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Deduction Name <span class="text-danger">*</span></label>
                        <input type="text" id="deduction_name" name="deduction_name" class="form-control @error('deduction_name') is-invalid @enderror" placeholder="Deduction Name" value="{{ old('deduction_name') }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="deduction_name_err"></div>
                        @error('Deduction_name')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Deduction Type Name <span class="text-danger">*</span></label>
                        <input type="text" id="deduction_type_name" name="deduction_type_name" class="form-control @error('deduction_type_name') is-invalid @enderror" placeholder="Allowance Type Name" value="{{ old('deduction_type_name') }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="deduction_type_name_err"></div>
                        @error('deduction_type_name')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Mode <span class="text-danger">*</span></label>
                        <select name="mode" id="mode" class="form-control @error('mode') is-invalid @enderror">
                            <option value="">Select mode</option>
                            @foreach($mode as $key => $mode)
                            <option value="{{$key}}" {{old('mode')==$key?'selected':''}}>{{$mode}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="mode_err"></div>
                        @error('mode')
                        <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="mode_value">Mode value(Amount/percentage) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">&#8377;</span>
                            <input type="number" id="mode_value" name="mode_value" class="form-control @error('mode_value') is-invalid @enderror" placeholder="Mode Value" value="{{ old('mode_value') }}" required>
                        </div>
                        <div class="alert text-danger text-capitalize" style="display: none" id="mode_value_err"></div>
                        @error('mode_value')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Taxability </label>
                        <select name="taxability" id="taxability" class="form-control @error('taxability') is-invalid @enderror">
                            <option value="">Select Taxability</option>
                            @foreach($tax_mode as $key => $tax)
                            <option value="{{$key}}" {{old('taxability')==$key?'selected':''}}>{{$tax}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="taxability_err"></div>
                        @error('taxability')
                        <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Tax percentage</label>
                        <input type="text" id="tax_amount" name="tax_amount" class="form-control @error('tax_amount') is-invalid @enderror" placeholder="Tax Percentage" value="{{ old('tax_amount') }}" required>
                        <div class="alert text-danger text-capitalize" style="display: none" id="tax_amount_err"></div>
                        @error('tax_amount')
                        <span class="invalid-feedback mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Frequency </label>
                        <select name="frequency" id="frequency" class="form-control @error('frequency') is-invalid @enderror">
                            <option value="">Select Frequency</option>
                            @foreach($frequency as $key => $frequency)
                            <option value="{{$key}}" {{old('frequency')==$key?'selected':''}}>{{$frequency}}</option>
                            @endforeach
                        </select>
                        <div class="alert text-danger" style="display: none" id="frequency_err"></div>
                        @error('frequency')
                        <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Comments</label>
                        <input type="text" id="comments" name="comments" class="form-control" placeholder="Comments">
                        <div class="alert text-danger" style="display: none" id="comments_err"></div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary saveNext" id="categorySubmitForm" type="button">Add</button>
            </div>
        </div>
    </form>

    <script src="{{asset('js/deduction.js')}}">

    </script>
    <script>
        $(document).ready(function() {
           // Get references to the mode and mode_value elements
           var $modeSelect = $('#mode');
            var $modeValueLabel = $("#basic-addon1");

            // Add change event listener to the mode select element
            $modeSelect.on('change', function() {
                var selectedValue = $(this).val();

                // Check the selected mode value and update the label accordingly
                if (selectedValue === '1') {
                    $modeValueLabel.html('&#8377;');
                } else if (selectedValue === '2') {
                    $modeValueLabel.html('%');
                } else {
                    $modeValueLabel.html('&#8377;');
                }
            });

            // Trigger the change event initially to set the initial label
            if (selectedValue != "") {
                $modeSelect.trigger('change');
            }
        });
        setTimeout(function() {
            $(".alert").fadeOut(400);
        }, 10000)
    </script>

</div>
@endsection
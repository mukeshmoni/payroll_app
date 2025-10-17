@extends('layouts.app')

@section('content')

      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="row">
            <div class="col-12 col-xl-8 mb-2 mb-xl-0">
              <h3 class="font-weight-bold m-0">Welcome {{Auth::user()->name}}</h3>
            </div>
          </div>
        </div>
      </div>
      @if(!Auth::user()->hasRole('employee'))
        <div class="row">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Gross Pay (Teaching)</p>
                    <p class="fs-30 mb-2">4006</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Deduction (Teaching)</p>
                    <p class="fs-30 mb-2">61344</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Net Pay (Teaching)</p>
                    <p class="fs-30 mb-2">34040</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Number of NOP Employees</p>
                    <p class="fs-30 mb-2">47033</p>
                </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Gross Pay (Non-Teaching)</p>
                    <p class="fs-30 mb-2">4006</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Deduction (Non-Teaching)</p>
                    <p class="fs-30 mb-2">61344</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Monthly Net Pay (Non-Teaching)</p>
                    <p class="fs-30 mb-2">34040</p>
                </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger" style="border-radius:10px;">
                <div class="card-body">
                    <p class="mb-2">Number of Pensioners</p>
                    <p class="fs-30 mb-2">47033</p>
                </div>
                </div>
            </div>
        </div>
      @endif
@endsection
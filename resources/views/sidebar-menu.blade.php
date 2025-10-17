<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('dashboard')}}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <hr>
    @if(Auth::user()->hasRole(['superadmin','pm']))
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="mdi mdi-file-excel menu-icon"></i>
        <span class="menu-title">Reports</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('income_tax')}}">Income Tax</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Income Tax(Old)</a></li> -->
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.salary_aquitance')}}">Salary Acquit.</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.salary_certificate')}}">Salary Cert.</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.da_arrears')}}">DA Arrears</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('payroll.getMonth_payslip')}}">Pay Slip</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.payledger')}}">Pay Ledger</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.nps')}}">NPS</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.society')}}">Society Cert.</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.pf')}}">Report PF</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.hba')}}">Report HBA</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('reports.income_tax')}}">Report Income Tax</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Pension Acquit.</a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Pension Ledger</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#payroll" aria-expanded="false" aria-controls="payroll">
        <i class="mdi mdi-file-excel menu-icon"></i>
        <span class="menu-title">Payroll</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="payroll">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('payroll')}}">Emp. Payroll</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('pensioner_payroll')}}">Pens. Payroll</a></li>
        </ul>
      </div>
    </li>
    <!-- <li class="nav-item">
          <a class="nav-link" href="{{route('payroll')}}">
            <i class="icon-paper menu-icon"></i>
            <span class="menu-title">Payroll</span>
          </a>
        </li> -->
    <li class="nav-item">
      <a class="nav-link" href="{{route('salary-structure')}}">
        <i class="icon-columns menu-icon"></i>
        <span class="menu-title">Salary Structure</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('leaves')}}">
        <i class="mdi mdi-calendar-clock menu-icon"></i>
        <span class="menu-title">Leave Requests</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('loanadvance')}}">
        <i class="mdi mdi-cash-multiple menu-icon"></i>
        <span class="menu-title">Loans Advances</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('employees')}}">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">Employees</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('allowance.allowance_category_list')}}">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Allowances</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('deduction.deduction_category_list')}}">
        <i class="icon-pie-graph menu-icon"></i>
        <span class="menu-title">Deductions</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('paylevels')}}">
        <i class="mdi mdi-stairs menu-icon"></i>
        <span class="menu-title">Pay Level & Slab</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('designations')}}">
        <i class="mdi mdi-clipboard-account menu-icon"></i>
        <span class="menu-title">Designations</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('departments')}}">
        <i class="mdi mdi-file-tree menu-icon"></i>
        <span class="menu-title">Departments</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('da.add_da')}}">
        <i class="mdi mdi-cash-multiple menu-icon"></i>
        <span class="menu-title">DA</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('rap.add_rap')}}">
        <i class="mdi mdi-cash-multiple menu-icon"></i>
        <span class="menu-title">RAP</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('centers')}}">
        <i class="mdi mdi-cash-multiple menu-icon"></i>
        <span class="menu-title">Centers</span>
      </a>
    </li>
    <hr>
    @endif
    @if(Auth::user()->hasRole(['superadmin','accountant']))
    <li class="nav-item">
      <a class="nav-link" href="{{route('br.bill_list')}}">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Bill Register</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('audit_register')}}">
        <i class="mdi mdi-calculator menu-icon"></i>
        <span class="menu-title">Audit Register</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('cheque_register')}}">
        <i class="mdi mdi-cash-multiple menu-icon"></i>
        <span class="menu-title">Cheque Register</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('remittance_register')}}">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Remittance Register</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('receipt_register')}}">
        <i class="mdi mdi-receipt menu-icon"></i>
        <span class="menu-title">Receipt</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('return_advance')}}">
        <i class="mdi mdi-keyboard-return menu-icon"></i>
        <span class="menu-title">Refund Advance</span>
      </a>
    </li>
    <hr>
    @endif
    @if(Auth::user()->hasRole('employee'))
    <li class="nav-item">
      <a class="nav-link" href="{{route('employee.payslip')}}">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">My PaySlip</span>
      </a>
    </li>
    @endif
    {{--<li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="icon-columns menu-icon"></i>
          <span class="menu-title">Form elements</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Charts</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
          <i class="icon-grid-2 menu-icon"></i>
          <span class="menu-title">Tables</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="tables">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <i class="icon-contract menu-icon"></i>
          <span class="menu-title">Icons</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">User Pages</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
          <i class="icon-ban menu-icon"></i>
          <span class="menu-title">Error pages</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="error">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pages/documentation/documentation.html">
          <i class="icon-paper menu-icon"></i>
          <span class="menu-title">Documentation</span>
        </a>
      </li> --}}
  </ul>
</nav>
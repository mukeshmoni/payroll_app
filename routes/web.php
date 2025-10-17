<?php

use App\Http\Controllers\CentersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LoanAdvanceController;
use App\Http\Controllers\DAController;
use App\Http\Controllers\RAPController;
use App\Http\Controllers\IncomeTaxController;
use App\Http\Controllers\PaylevelsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\DebuggerController;
use App\Http\Controllers\Accountant\BillRegisterController;
use App\Http\Controllers\Accountant\AuditRegisterController;
use App\Http\Controllers\Accountant\ChequeRegisterController;
use App\Http\Controllers\Accountant\RemittanceRegisterController;
use App\Http\Controllers\Accountant\ReceiptRegisterController;
use App\Http\Controllers\Accountant\ReturnAdvanceController;
use App\Http\Controllers\PensionerPayrollController;
use Illuminate\Support\Facades\Crypt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});
// Route::get('/create_employee_login',[LoginController::class,'index']);
Route::get('/fix_payledger/{empid}',[DebuggerController::class,'fix_total_salary']);
Route::get('/pension_flag_update',[DebuggerController::class,'add_pensioner_flag']);
Route::get('/fix_tda_arrear/{empid}',[DebuggerController::class,'fix_tda_arrear']);

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    Route::group(['middleware' => ['role:superadmin']], function () {
        //
    });

    //for pm
    Route::group(['middleware' => ['role:pm|superadmin']], function () {
        // Route::get('/designations',[DesignationController::class,'index'])->name('designations');
        Route::get('/departments',[DepartmentController::class,'index'])->name('departments');
        // Route::get('/deductions',[DeductionController::class,'index'])->name('deductions');
        // Route::get('/allowances',[AllowanceController::class,'index'])->name('allowances');
        Route::get('/payroll',[PayrollController::class,'index'])->name('payroll');
        Route::get('/payroll/verify_payroll_next/{id}/{month}',[PayrollController::class,'verify_payroll_next'])->name('payroll.verify_payroll_next');
        Route::get('/payroll/verify_payroll_prev/{id}/{month}',[PayrollController::class,'verify_payroll_prev'])->name('payroll.verify_payroll_prev');
        Route::get('/payroll/verify_payroll/{id}/{month}',[PayrollController::class,'verify_payroll'])->name('payroll.verify_payroll');
        Route::get('/payroll/verify_department_payroll/{id}/{month}',[PayrollController::class,'verify_department_payroll'])->name('payroll.verify_department_payroll');
        Route::post('/payroll/verify/{id}/{month}',[PayrollController::class,'verify'])->name('payroll.verify');

        Route::post('/payroll',[PayrollController::class,'index'])->name('payroll.getMonth_payroll');


        //pensioner payroll
        Route::get('/pensioner_payroll',[PensionerPayrollController::class,'index'])->name('pensioner_payroll');
        Route::post('/pensioner_payroll',[PensionerPayrollController::class,'index'])->name('pensioner_payroll.getMonth_payroll');
        Route::get('/pensioner_payroll/verify_payroll/{id}/{month}',[PensionerPayrollController::class,'verify_payroll'])->name('pensioner_payroll.verify_payroll');
        Route::get('/pensioner_payroll/verify_payroll_next/{id}/{month}',[PensionerPayrollController::class,'verify_payroll_next'])->name('pensioner_payroll.verify_payroll_next');
        Route::get('/pensioner_payroll/verify_payroll_prev/{id}/{month}',[PensionerPayrollController::class,'verify_payroll_prev'])->name('pensioner_payroll.verify_payroll_prev');
        Route::get('/pensioner_payroll/get_pay_slip/{id}/{month}',[PayrollController::class,'getMonthlyPayslip'])->name('pensioner_payroll.getpayslip');
        Route::post('/pensioner_payroll/verify/{id}/{month}',[PensionerPayrollController::class,'verify'])->name('pensioner_payroll.verify');

        
        //income tax
        Route::get('/income_tax',[IncomeTaxController::class,'index'])->name('income_tax');
        Route::get('/income_tax/generate_department/{dept}',[IncomeTaxController::class,'generate_dept'])->name('income_tax.generate');
        Route::get('/income_tax/generate_employee/{empid}/{dept}',[IncomeTaxController::class,'generate_emp'])->name('income_tax.generate_emp');
        Route::get('/income_tax/view_employee/{empid}/{dept}',[IncomeTaxController::class,'view'])->name('income_tax.view_emp');
        Route::get('/income_tax/generate_next/{empid}/{dept}',[IncomeTaxController::class,'generate_next'])->name('income_tax.generate_next');
        Route::get('/income_tax/generate_prev/{empid}/{dept}',[IncomeTaxController::class,'generate_prev'])->name('income_tax.generate_prev');
        Route::post('/income_tax/update_income_tax/{empid}/{dept}',[IncomeTaxController::class,'update_income_tax'])->name('income_tax.update_income_tax');

        //employees
        Route::get('/employees',[EmployeeController::class,'index'])->name('employees');
        Route::get('/employees/add_employee',[EmployeeController::class,'create'])->name('employees.add_employee');
        Route::post('/employees/add_employee',[EmployeeController::class,'store'])->name('employees.add_employee');
        Route::get('/employees/modify_employee/{id}',[EmployeeController::class,'modify'])->name('employees.modify_employee');
        Route::post('/employees/modify_employee/{id}',[EmployeeController::class,'update'])->name('employees.modify_employee');
        Route::get('/employees/delete_employee/{id}',[EmployeeController::class,'delete'])->name('employees.delete_employee');
        Route::get('/employees/view_employee/{id}',[EmployeeController::class,'view'])->name('employees.view_employee');
        Route::get('/employees/export_employees',[EmployeeController::class,'export'])->name('employees.export_employees');
        Route::post('/employees/import_employees',[EmployeeController::class,'import'])->name('employees.import_employees');

        //Designations
        Route::get('/designations/add_designations',[DesignationController::class,'create'])->name('designations');
        // Route::get('/designations/add_designations',[DesignationController::class,'create'])->name('designations.add_designations');
        Route::post('/designations/store_designations',[DesignationController::class,'store'])->name('designations.store_designation');
        Route::post('/designations/update_designations/{id}',[DesignationController::class,'update'])->name('designations.update_designation');
        Route::get('/designations/modify_designations/{id}',[DesignationController::class,'modify'])->name('designations.modify_designation');
        Route::get('/designations/delete_designations/{id}',[DesignationController::class,'delete'])->name('designations.delete_designation');
        Route::get('/designations/export_designations',[DesignationController::class,'export'])->name('designations.export_designations');

        //Departments
        Route::get('/departments/add_departments',[DepartmentController::class,'create'])->name('departments');
        Route::post('/departments/store_departments',[DepartmentController::class,'store'])->name('departments.store_departments');
        Route::post('/departments/update_departments/{id}',[DepartmentController::class,'update'])->name('departments.update_departments');
        Route::get('/departments/modify_departments/{id}',[DepartmentController::class,'modify'])->name('departments.modify_departments');
        Route::get('/departments/delete_departments/{id}',[DepartmentController::class,'delete'])->name('departments.delete_departments');
        Route::get('/departments/export_departments',[DepartmentController::class,'export'])->name('departments.export_departments');

        //paylevel and Slab
        Route::get('/paylevels',[PaylevelsController::class,'index'])->name('paylevels');
        Route::post('/paylevels/store_paylevels',[PaylevelsController::class,'store'])->name('paylevels.store_paylevels');
        Route::get('/paylevels/modify_paylevel/{id}',[PaylevelsController::class,'modify'])->name('paylevels.modify_paylevel');
        Route::post('/paylevels/update_paylevel/{id}',[PaylevelsController::class,'update'])->name('paylevels.update_paylevel');
        Route::get('/paylevels/delete_paylevel/{id}',[PaylevelsController::class,'delete'])->name('paylevels.delete_paylevel');
        Route::get('/paylevels/export_paylevels',[PaylevelsController::class,'export'])->name('paylevels.export_paylevels');

        //centers
        Route::prefix('/centers')->group(function () {
            Route::get('/',[CentersController::class,'index'])->name('centers');
            Route::post('/store_center',[CentersController::class,'store'])->name('centers.store_center');
            Route::get('/modify_center/{id}',[CentersController::class,'modify'])->name('centers.modify_center');
            Route::post('/update_center/{id}',[CentersController::class,'update'])->name('centers.update_center');
            Route::get('/delete_center/{id}',[CentersController::class,'delete'])->name('centers.delete_center');
            Route::get('/export_centers',[CentersController::class,'export'])->name('centers.export_centers');
        });

        //allowance module
        Route::group(['prefix' => '/allowance','controller' => AllowanceController::class,], function () {
            Route::get('/', 'allowanceCategoryList')->name('allowance.allowance_category_list');
            Route::get('/create', 'createAllowanceCategory')->name('allowance.create_allowance_category');
            Route::post('/create', 'addAllowanceCategory')->name('allowance.add_allowance_category');
            Route::get('/edit/{id}', 'editAllowanceCategory')->name('allowance.edit_allowance_category');
            Route::post('/edit/{id}', 'addAllowanceCategory')->name('allowance.update_allowance_category');
            Route::get('/delete/{id}', 'deleteAllowanceCategory')->name('allowance.delete_allowance_category');
            Route::get('/export_list','export')->name('allowance.export_allowance_list');
        });


        //deduction module
        Route::group([
            'prefix' => '/deduction',
            'controller' => DeductionController::class,
        ], function () {
            Route::get('/', 'deductionCategoryList')->name('deduction.deduction_category_list');
            Route::get('/create', 'createDeductionCategory')->name('deduction.create_deduction_category');
            Route::post('/create', 'addDeductionCategory')->name('deduction.add_deduction_category');
            Route::get('/edit/{id}', 'editDeductionCategory')->name('deduction.edit_deduction_category');
            Route::post('/edit/{id}', 'addDeductionCategory')->name('deduction.update_deduction_category');
            Route::get('/delete/{id}', 'deleteDeductionCategory')->name('deduction.delete_deduction_category');
            Route::get('/export_list','export')->name('deduction.export_deduction_list');
        });

        //Attendance
        Route::get('leaves',[LeaveController::class,'index'])->name('leaves');
        Route::get('/leaves/add_leaves',[LeaveController::class,'create'])->name('leaves.add_leaves');       
        Route::post('/leaves/store_leaves',[LeaveController::class,'store'])->name('leaves.store_leaves');
        Route::post('/leaves/update_leaves/{id}',[LeaveController::class,'update'])->name('leaves.update_leaves');
        Route::get('/leaves/modify_leaves/{id}',[LeaveController::class,'modify'])->name('leaves.modify_leaves');
        Route::get('/leaves/delete_leaves/{id}',[LeaveController::class,'delete'])->name('leaves.delete_leaves');
        Route::get('/leaves/export_leave_list',[LeaveController::class,'export'])->name('leaves.export_leave_list');

        //Loan Advance
        Route::get('loanadvance',[LoanAdvanceController::class,'index'])->name('loanadvance');
        Route::get('/loanadvance/add_loanadvance',[LoanAdvanceController::class,'create'])->name('loanadvance.add_loanadvance');       
        Route::post('/loanadvance/store_loanadvance',[LoanAdvanceController::class,'store'])->name('loanadvance.store_loanadvance');
        Route::post('/loanadvance/update_loanadvance/{id}',[LoanAdvanceController::class,'update'])->name('loanadvance.update_loanadvance');
        Route::get('/loanadvance/modify_loanadvance/{id}',[LoanAdvanceController::class,'modify'])->name('loanadvance.modify_loanadvance');
        Route::get('/loanadvance/delete_loanadvance/{id}',[LoanAdvanceController::class,'delete'])->name('loanadvance.delete_loanadvance');
        Route::get('/loanadvance/export_loan_advance_list',[LoanAdvanceController::class,'export'])->name('loanadvance.export_loan_advance_list');

        //salary-structure
        Route::get('/salary-structure',[SalaryController::class,'index'])->name('salary-structure');
        Route::get('/salary-structure/getEmployees',[SalaryController::class,'getEmployees']);
        Route::get('/salary-structure/getLoanDetails',[SalaryController::class,'getLoanDetails']);
        Route::get('/salary-structure/modify_salary_structure/getEmployees',[SalaryController::class,'getEmployees']);
        Route::get('/salary-structure/modify_salary_structure/getLoanDetails',[SalaryController::class,'getLoanDetails']);
        Route::get('/salary-structure/add_salary_structure',[SalaryController::class,'create'])->name('salary-structure.add_salary_structure');
        Route::post('/salary-structure/add_salary_structure',[SalaryController::class,'store'])->name('salary-structure.add_salary_structure');
        Route::get('/salary-structure/modify_salary_structure/{month}/{id}/{route}',[SalaryController::class,'modify'])->name('salary-structure.modify_salary_structure');
        Route::get('/salary-structure/modify_salary_structure/{id}',[SalaryController::class,'modify'])->name('salary-structure.modify_salary_structure');
        Route::post('/salary-structure/modify_salary_structure/{id}',[SalaryController::class,'update'])->name('salary-structure.modify_salary_structure');
        Route::get('/salary-structure/delete_salary_structure/{id}',[SalaryController::class,'delete'])->name('salary-structure.delete_salary_structure');
        Route::get('/salary-structure/view_salary_structure/{id}',[SalaryController::class,'view'])->name('salary-structure.view_salary_structure');
        Route::get('/salary-structure/consolidated_salary_structure',[SalaryController::class,'consolidated'])->name('salary-structure.consolidated_salary_structure');

        //pension
        Route::get('/salary-structure/add_pension_structure',[SalaryController::class,'add_pension'])->name('salary-structure.add_pension_structure');
        Route::post('/salary-structure/add_pension_structure',[SalaryController::class,'store_pension'])->name('salary-structure.add_pension_structure');
        Route::get('/salary-structure/pension/getEmployees',[SalaryController::class,'getEmployees']);
        Route::get('/salary-structure/modify_pension_structure/{month}/{id}/{route}',[SalaryController::class,'modify'])->name('salary-structure.modify_pension_structure');
        Route::get('/salary-structure/modify_pension_structure/{id}',[SalaryController::class,'modify'])->name('salary-structure.modify_pension_structure');
        Route::get('/salary-structure/modify_pension_structure/getEmployees',[SalaryController::class,'getEmployees']);

        //DA       
        Route::get('da',[DAController::class,'index'])->name('da');
        Route::get('/da/add_da',[DAController::class,'create'])->name('da.add_da');       
        Route::post('/da/store_da',[DAController::class,'store'])->name('da.store_da');
        Route::post('/da/update_da/{id}',[DAController::class,'update'])->name('da.update_da');
        Route::get('/da/modify_da/{id}',[DAController::class,'modify'])->name('da.modify_da');
        Route::get('/da/delete_da/{id}',[DAController::class,'delete'])->name('da.delete_da');
        Route::get('/da/export_da_list',[DAController::class,'export'])->name('da.export_da_list');

        //RAP       
        Route::get('rap',[RAPController::class,'index'])->name('rap');
        Route::get('/rap/add_rap',[RAPController::class,'create'])->name('rap.add_rap');       
        Route::post('/rap/store_rap',[RAPController::class,'store'])->name('rap.store_rap');
        Route::post('/rap/update_rap/{id}',[RAPController::class,'update'])->name('rap.update_rap');
        Route::get('/rap/modify_rap/{id}',[RAPController::class,'modify'])->name('rap.modify_rap');
        Route::get('/rap/delete_rap/{id}',[RAPController::class,'delete'])->name('rap.delete_rap');
        Route::get('/rap/export_rap_list',[RAPController::class,'export'])->name('rap.export_rap_list');

        Route::get('/reports/salary_aquitance',[ReportsController::class,'salary_aquitance'])->name('reports.salary_aquitance');
        Route::post('/reports/salary_aquitance/',[ReportsController::class,'salary_aquitance'])->name('reports.getSalary_aquitance');
        Route::get('/reports/salary_aquitance/{month}/{category}',[ReportsController::class,'export_salary_aquitance'])->name('reports.export_salary_aquitance');

        Route::get('/reports/payledger',[ReportsController::class,'payledger'])->name('reports.payledger');
        Route::post('/reports/payledger',[ReportsController::class,'payledger'])->name('reports.getLedger');
        Route::get('/reports/export_ledger/{month}/{id}',[ReportsController::class,'export_ledger'])->name('reports.exportLedger');
        Route::post('/reports/export_consolidatedledge',[ReportsController::class,'export_consolidatedledger'])->name('reports.export_consolidatedLedger');


        Route::get('/reports/nps',[ReportsController::class,'nps'])->name('reports.nps');
        Route::post('/reports/nps',[ReportsController::class,'nps_report'])->name('reports.get_nps_report');

        Route::get('/reports/society',[ReportsController::class,'society'])->name('reports.society');
        Route::post('/reports/get_society_certificate',[ReportsController::class,'get_society_certificate'])->name('reports.get_society_certificate');
        Route::post('/reports/get_society_report',[ReportsController::class,'get_society_report'])->name('reports.get_society_report');

        Route::get('/reports/pf',[ReportsController::class,'pf'])->name('reports.pf');
        Route::post('/reports/pf',[ReportsController::class,'pf_report'])->name('reports.get_pf_report');

        Route::get('/reports/hba',[ReportsController::class,'hba'])->name('reports.hba');

        Route::get('/reports/income_tax',[ReportsController::class,'income_tax'])->name('reports.income_tax');
        Route::post('/reports/income_tax_report',[ReportsController::class,'get_it_report'])->name('reports.get_it_report');

        Route::post('/reports/income_tax_report_quarterly',[ReportsController::class,'get_it_report_quarterly'])->name('reports.get_it_report_quarterly');

        Route::get('/reports/da_arrears',[ReportsController::class,'da_arrears'])->name('reports.da_arrears');
        Route::post('/reports/get_da_arrear_report',[ReportsController::class,'get_da_arrear_report'])->name('reports.get_da_arrear_report');
        Route::get('/reports/da_arrear_acquittance',[ReportsController::class,'da_arrear_acquittance'])->name('reports.da_arrear_acquittance');

        Route::get('/reports/salary_certificate',[ReportsController::class,'salary_certificate'])->name('reports.salary_certificate');
        Route::post('/reports/get_salary_certificate',[ReportsController::class,'get_salary_certificate'])->name('reports.get_salary_certificate');

        //employees order
        Route::get('/employees/order',[EmployeeController::class,'order'])->name('employees.order');
        Route::post('/employees/order',[EmployeeController::class,'order_update'])->name('employees.order');
    });

    //for accountant
    Route::group(['middleware' => ['role:accountant|superadmin']], function () {
        //bill register
        Route::group(['prefix' => '/bill_register','controller' => BillRegisterController::class,], function () {
            Route::get('/', 'billList')->name('br.bill_list');
            Route::get('/create', 'createBillRegister')->name('br.create_bill_register');
            Route::post('/create', 'addBillRegister')->name('br.add_bill_register');
            Route::get('/edit/{id}', 'editBillRegister')->name('br.edit_bill_register');
            Route::post('/edit/{id}', 'addBillRegister')->name('br.update_bill_register');
            Route::get('/delete/{id}', 'deleteBillRegister')->name('br.delete_bill_register');
        });
        Route::group(["prefix"=>"/cheque_register","controller"=>ChequeRegisterController::class,],function(){
            Route::get('/','index')->name('cheque_register');
            Route::get('/register_entry','create')->name('cheque_register.register_entry');
            Route::post('/getBrDetails','fetch')->name('cheque_register.fetch');
            Route::post('/deleteVR','delete')->name('cheque_register.delete');
            Route::post('/register_entry/store','store')->name('cheque_register.store');
            Route::get('/edit/{id}', 'editChequeRegister')->name('cheque_register.edit_cheque_register');
            Route::get('/delete/{id}', 'deleteChequeRegister')->name('cheque_register.delete_cheque_register');
        });
        Route::get('/audit_register',[AuditRegisterController::class,'index'])->name('audit_register');
        Route::get('/remittance_register',[RemittanceRegisterController::class,'index'])->name('remittance_register');
        Route::get('/receipt_register',[ReceiptRegisterController::class,'index'])->name('receipt_register');
        Route::get('/return_advance',[ReturnAdvanceController::class,'index'])->name('return_advance');
    });

    //report
    Route::get('/payslip',[PayrollController::class,'payslipList'])->name('payroll.getMonth_payslip');
    Route::post('/payslip',[PayrollController::class,'payslipList'])->name('payroll.getMonth_payslip');
    Route::get('/payslip/get_pay_slip/{id}/{month}',[PayrollController::class,'getMonthlyPayslip'])->name('payroll.getpayslip');

    // employee login
    Route::group(['middleware' => ['role:employee']], function () {
        Route::get('/employee/payslip',[EmployeeController::class,'payslip'])->name('employee.payslip');
    });
});

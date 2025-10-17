<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\departments;
use App\Models\designations;
use App\Models\SalaryStructure;
use App\Models\Employees;
use App\Models\payroll;
use App\Models\payroll_final;
use App\Models\da;
use App\Models\AllowanceCategory;
use App\Models\DeductionCategory;
use App\Models\loans_advances;
use PDF;

class PayrollController extends Controller
{
    public function index(Request $request){
        if(isset($request->month)){
            $month = $request->month;
            $monthTemp = date("Y-m",strtotime($month));
        }
        elseif(session()->has("month")){
            $month = session('month');
            $monthTemp = date("Y-m",strtotime($month));
        }else{
            $month = date("F-Y");
            $monthTemp = date("Y-m");
        }
        
        
        // $departments = departments::leftJoin('employees', 'departments.id', '=', 'employees.department')
        //             ->select('departments.department',"departments.id", \DB::raw('COUNT(employees.id) as employee_count'))
        //             ->where("departments.status",0)
        //             ->groupBy('departments.id', 'departments.department')
        //             ->get();

        // $payrollCount = payroll::join('departments', 'payroll.department', '=', 'departments.id')
        //             ->where('payroll.month', '=', $monthTemp) // Replace $desiredMonth with the month you want to filter
        //             ->groupBy('departments.id')
        //             ->select('departments.id', \DB::raw('count(payroll.employee) as verified_count'))
        //             ->pluck('verified_count', 'departments.id')
        //             ->all();
        //         // If you want to ensure that all departments are present in the result with count 0 if no entries are found, you can do:
        //         $Tempdepartments = departments::pluck('id');
        //         foreach ($Tempdepartments as $department) {
        //             if (!isset($payrollCount[$department])) {
        //                 $payrollCount[$department] = 0;
        //             }
        //         }

        $categories = SalaryStructure::select('category', \DB::raw('COUNT(salary_structure.id) as employee_count'))
                    ->where("status",0)
                    ->where("pensioner",0)
                    ->groupBy('category')
                    ->get();

        $payrollCount = payroll::join('salary_structure', 'payroll.employee', '=', 'salary_structure.employee')
                    ->where('payroll.month', '=', $monthTemp) // Replace $desiredMonth with the month you want to filter
                    ->where("salary_structure.pensioner",0)
                    ->groupBy('salary_structure.category')
                    ->select('salary_structure.category', \DB::raw('count(payroll.employee) as verified_count'))
                    ->pluck('verified_count', 'salary_structure.category')
                    ->all();
                // If you want to ensure that all employees are present in the result with count 0 if no entries are found, you can do:
                $TempCategories = SalaryStructure::where("pensioner",0)->pluck('category');
                foreach ($TempCategories as $category) {
                    $tempCate = strtolower($category);
                    if (!isset($payrollCount[$tempCate])) {
                        $payrollCount[$tempCate] = 0;
                    }
                }
        // dd($payrollCount);
        $employees = SalaryStructure::join("designation","designation.id","=","salary_structure.designation")
                                ->join("employees","employees.empid","=","salary_structure.employee")
                                ->select("employees.*","designation.*")
                                ->where("salary_structure.status",0)
                                ->where("salary_structure.pensioner",0)
                                ->orderBy("salary_structure.employee","ASC")
                                ->get();

        $verifiedEmployees = payroll::where('month', '=', $monthTemp)->where("pensioner",0)
                            ->pluck('employee')
                            ->toArray();
        return view("payroll.payroll",["categories"=>$categories,"employees"=>$employees,"month"=>$month,"payrollCount"=>$payrollCount,"verifiedEmployees"=>$verifiedEmployees]);
    }
    public function getMonthPayroll(Request $request){
        $month = $request->month;
        $monthTemp = date("Y-m",strtotime($month));
        // $departments = departments::leftJoin('employees', 'departments.id', '=', 'employees.department')
        //             ->select('departments.department',"departments.id", \DB::raw('COUNT(employees.id) as employee_count'))
        //             ->where("departments.status",0)
        //             ->groupBy('departments.id', 'departments.department')
        //             ->get();

        // $payrollCount = payroll::join('departments', 'payroll.department', '=', 'departments.id')
        //             ->where('payroll.month', '=', $monthTemp) // Replace $desiredMonth with the month you want to filter
        //             ->groupBy('departments.id')
        //             ->select('departments.id', \DB::raw('count(payroll.employee) as verified_count'))
        //             ->pluck('verified_count', 'departments.id')
        //             ->all();
        //         // If you want to ensure that all departments are present in the result with count 0 if no entries are found, you can do:
        //         $Tempdepartments = departments::pluck('id');
        //         foreach ($Tempdepartments as $department) {
        //             if (!isset($payrollCount[$department])) {
        //                 $payrollCount[$department] = 0;
        //             }
        //         }
        
        $categories = employees::select('category', \DB::raw('COUNT(employees.id) as employee_count'))
                    ->where("status",0)
                    ->where("prnop",'nop')
                    ->groupBy('category')
                    ->get();

        $payrollCount = payroll::join('employees', 'payroll.employee', '=', 'employees.empid')
                    ->where('payroll.month', '=', $monthTemp) // Replace $desiredMonth with the month you want to filter
                    ->where("employees.prnop",'nop')
                    ->groupBy('employees.category')
                    ->select('employees.category', \DB::raw('count(payroll.employee) as verified_count'))
                    ->pluck('verified_count', 'employees.category')
                    ->all();
                // If you want to ensure that all employees are present in the result with count 0 if no entries are found, you can do:
                $TempCategories = employees::pluck('category');
                foreach ($TempCategories as $category) {
                    $tempCate = strtolower($category);
                    if (!isset($payrollCount[$tempCate])) {
                        $payrollCount[$tempCate] = 0;
                    }
                }

        $employees = Employees::join("designation","designation.id","=","employees.designation")
                                ->select("employees.*","designation.*")
                                ->where("employees.status",0)
                                ->where("employees.prnop",'nop')
                                ->get();

        $verifiedEmployees = payroll::where('month', '=', $monthTemp)->where("pensioner",0)
                            ->pluck('employee')
                            ->toArray();
        return view("payroll.payroll",["categories"=>$categories,"employees"=>$employees,"month"=>$month,"payrollCount"=>$payrollCount,"verifiedEmployees"=>$verifiedEmployees]);
    }
    public function verify_payroll(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = $request->month;
        // dd($id);
        $Mstart = date("Y-m-01",strtotime($month));
        $Mend = date("Y-m-t",strtotime($month));

        if(!SalaryStructure::where("employee",$id)->where("status",0)->exists()){
            return back()->with(["status"=>false,"message"=>"Please create salary structure for this employee to view payroll"]);
        }

        $curr_month = date("Y-m");
        $sel_month = date("Y-m",strtotime($month));

        if($curr_month == $sel_month){
            $structure = SalaryStructure::leftJoin("employees","employees.empid","=","salary_structure.employee")
                        ->leftJoin("departments","departments.id","=","salary_structure.department")
                        ->leftJoin("designation","designation.id","=","salary_structure.designation")
                        ->select("salary_structure.*","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc","employees.empname","employees.category","employees.pf_nps_cat")
                        ->where(["salary_structure.status"=>0,"salary_structure.employee"=>$id])
                        ->first();
        }else{
            $structure = payroll::leftJoin("employees","employees.empid","=","payroll.employee")
                        ->leftJoin("departments","departments.id","=","payroll.department")
                        ->leftJoin("designation","designation.id","=","payroll.designation")
                        ->select("payroll.*","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc","employees.empname","employees.category","employees.pf_nps_cat")
                        ->where(["payroll.month"=>$sel_month,"payroll.employee"=>$id])
                        ->first();
        }

        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $la = loans_advances::where("empid",$structure->employee)->where("status",0)->get();
        //DA arrear check
        $da_arrear = false;
        $prevDA_perc = null;
	    $payrolls = false;
        if(da::whereBetween('created_at', [$Mstart, $Mend])->where("status",0)->exists()){
            $da = da::where("status",0)->orderBy("year","ASC")->get();
            $months = $da->pluck("year")->toArray();
            
            $DA_month = $da->whereBetween('created_at', [$Mstart, $Mend])->first()->year;
            $currDA_perc = $da->whereBetween('created_at', [$Mstart, $Mend])->first()->da;
            $from_d = date("Y-m",strtotime($DA_month));
            $to_d = date("Y-m",strtotime($month));
            $interval = date_diff(date_create($from_d),date_create($to_d))->m;
            if($interval>0){
                //previous da perc
                if(array_search($DA_month, $months)>0){
                    $prevMonth = $months[array_search($DA_month, $months)-1];
                    $prevDA_perc = $da->where("year",$prevMonth)->first()->da;
        
                    $DA_diff=$currDA_perc-$prevDA_perc;
                    if($DA_diff>0){
                        $da_arrear=true;
                        //get basic pay,da allowance for between months
                        $payrolls = payroll_final::where(["employee"=>$id])->where("month",">=",$from_d)->where("month","<",$to_d)->orderBy("id","ASC")->get();
                        foreach($payrolls as $payroll){
                            $basic_pay = $payroll->basic_salary;
                            $prevDAallwnse = $payroll->da;
                            $currDAallwnse = round($basic_pay*($currDA_perc/100));
                            $daArrear = $currDAallwnse-$prevDAallwnse;
                            $payroll->da_due = $currDAallwnse;
                            $payroll->da_arrear = $daArrear;
                        }
                        if($structure->transport!=0){
                            $slab = $structure->slab;
                        }else{
                            $slab = 0;
                        }
            
                        $structure->slab = $slab;
            
                        $tda_due = round($slab*($structure->da_perc/100));
                        $tda_drawn = round($slab*($prevDA_perc/100));
                        $tda_arrear = round($tda_due-$tda_drawn);
            
                        $structure->tda_due = $tda_due;
                        $structure->tda_drawn = $tda_drawn;
                        $structure->tda_arrear = $tda_arrear;
                    }else{
                        //set da arrear as false
                        $payrolls=false;
                        $da_arrear = false;
                    }
                    
                }
            }

        }
        //check if already verified
        $formattedM = date("Y-m",strtotime($month));
        if(payroll::where('employee',$id)->where('month',$formattedM)->where("status",0)->exists()){
            $verifiedFlag = true;
        }else{
            $verifiedFlag = false;
        }
        //DA arrear check end
        return view("payroll.view_payroll",["structure"=>$structure,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la,"back"=>"payroll","month"=>$month,"da_arrear"=>$da_arrear,"payrolls"=>$payrolls,"prev_da"=>$prevDA_perc,"verified"=>$verifiedFlag]);
    }

    public function verify_payroll_next(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = $request->month;

        $Mstart = date("Y-m-01",strtotime($month));
        $Mend = date("Y-m-t",strtotime($month));

        $curr_emp = Employees::where("empid",$id)->first();

        $employees = SalaryStructure::where("status",0)->where("category",$curr_emp->category)->where("pensioner",0)->orderBy("employee","ASC")->pluck('employee')->toArray();    
        $index = array_search($id, $employees);
       
        if($index==count($employees)-1){
            return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
        }else{
            $next_empid = $index+1;
            if(isset($employees[$next_empid])){
                $empid = $employees[$next_empid];
            }else{
                return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
            }
        }  
        $request->id = Crypt::encryptString($empid);

        return $this->verify_payroll($request);
    }

    public function verify_payroll_prev(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = $request->month;

        $Mstart = date("Y-m-01",strtotime($month));
        $Mend = date("Y-m-t",strtotime($month));

        $curr_emp = Employees::where("empid",$id)->first();

        $employees = SalaryStructure::where("status",0)->where("category",$curr_emp->category)->where("pensioner",0)->orderBy("employee","ASC")->pluck('employee')->toArray();    
        $index = array_search($id, $employees);
        if($index==0){
            return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
        }else{
            $next_empid = $index-1;
            if(isset($employees[$next_empid])){
                $empid = $employees[$next_empid];
            }else{
                return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
            }
        }  
        $request->id = Crypt::encryptString($empid);

        return $this->verify_payroll($request);
    }

    public function verify_department_payroll(Request $request){
        $deptid = Crypt::decryptString($request->id);
        $month = Crypt::decryptString($request->month);
        
        $monthFull = date("F-Y",strtotime($month));
        // $department = departments::where("id",$deptid)->first();
        $department = $deptid;
        // $payrolls = payroll::where(["department"=>$deptid,"month"=>$month,"status"=>"0"])->get();
        $payrolls = payroll::where(["emp_category"=>$deptid,"month"=>$month])->get();

        try {
           
            foreach($payrolls as $payroll){
                // if(payroll_final::where(["department"=>$deptid,"month"=>$month,"employee"=>$payroll->employee,"status"=>"0"])->exists()){
                //     payroll_final::where(["department"=>$deptid,"month"=>$month,"employee"=>$payroll->employee,"status"=>"0"])->delete();
                // }
                if(payroll_final::where(["emp_category"=>$deptid,"month"=>$month,"employee"=>$payroll->employee])->exists()){
                    payroll_final::where(["emp_category"=>$deptid,"month"=>$month,"employee"=>$payroll->employee])->delete();
                }

                $payroll_final = new payroll_final;
                $payroll_final->month = $month;
                $payroll_final->department = $payroll->department;
                $payroll_final->designation = $payroll->designation;
                $payroll_final->basic_salary = $payroll->basic_salary;
                $payroll_final->employee = $payroll->employee;
                $payroll_final->da_perc = $payroll->da_perc;
                $payroll_final->hra_perc = $payroll->hra_perc;
                $payroll_final->da = $payroll->da;
                $payroll_final->hra = $payroll->hra;
                $payroll_final->transport = $payroll->transport;
                $payroll_final->misc = $payroll->misc;
                $payroll_final->allowances = $payroll->allowances;
                $payroll_final->gross_salary = $payroll->gross_salary;
                $payroll_final->deductions = $payroll->deductions;
                $payroll_final->la = $payroll->la;
                $payroll_final->pf = $payroll->pf;
                $payroll_final->npse = $payroll->npse;
                $payroll_final->npser = $payroll->npser;
                $payroll_final->nps_da_arrear = $payroll->nps_da_arrear;
                $payroll_final->net_salary = $payroll->net_salary;
                $payroll_final->it = $payroll->it;
                $payroll_final->slab = $payroll->slab;
                $payroll_final->prev_da = $payroll->prev_da;
                $payroll_final->da_month = $payroll->da_month;
                $payroll_final->da_basic_salary = $payroll->da_basic_salary;
                $payroll_final->da_due = $payroll->da_due;
                $payroll_final->da_drawn = $payroll->da_drawn;
                $payroll_final->da_arrear = $payroll->da_arrear;
                $payroll_final->tda_due = $payroll->tda_due;
                $payroll_final->tda_drawn = $payroll->tda_drawn;
                $payroll_final->tda_arrear = $payroll->tda_arrear;
                $payroll_final->total_tda = $payroll->total_tda;
                $payroll_final->total_salary = $payroll->total_salary;
                $payroll_final->emp_category = $payroll->emp_category;
                $payroll_final->narration = $payroll->narration;
                $payroll_final->created_by = Auth::user()->id;
                $payroll_final->save();
            }
            return redirect()->route("payroll")->with(["status"=>true,"message"=>"Payroll Generated successfully! for ".ucwords($department)." Staffs","month"=>$monthFull]);
        } catch (Exception $e) {
            //throw $th;
            return back()->with(["status"=>false,"message"=>"Can't verify payroll right now!"]);
        }
        // return view("payroll.view_department_payroll");
    }

    public function verify(Request $request){
        $empid = Crypt::decryptString($request->id);
        $monthFull = $request->month;
        $month = date("Y-m",strtotime($monthFull));
        
        if(!$month){
            return redirect()->route("payroll")->with(["status"=>false,"message"=>"Select Month to verify payroll"]);
        }
        if(!SalaryStructure::where("employee",$empid)->where("status",0)->exists()){
            return back()->with(["status"=>false,"message"=>"Please update salary structure for this employee to view payroll"]);
        }
        $salary = SalaryStructure::where("employee",$empid)->where("status",0)->first();

        if(payroll::where("employee",$empid)->where("status",0)->exists()){

            $old = payroll::where("employee",$empid)->where("status",0)->first();

            // if($old->month==$month){
                $del = payroll::where("employee",$empid)->where("month",$month)->delete();
            // }else{

                $del = payroll::where("employee",$empid)->where("status",0)->update([
                    "status"=>1
                ]);
            // }
        }

        $category = Employees::where("empid",$empid)->first()->category;
        //getting the record want to copy
        $article = SalaryStructure::find($salary->id);

        $payroll = new payroll;
        $payroll->month = $month;
        $payroll->department = $article->department;
        $payroll->designation = $article->designation;
        $payroll->basic_salary = $article->basic_salary;
        $payroll->employee = $article->employee;
        $payroll->da_perc = $article->da_perc;
        $payroll->hra_perc = $article->hra_perc;
        $payroll->da = $article->da;
        $payroll->hra = $article->hra;
        $payroll->transport = $article->transport;
        $payroll->misc = $article->misc;
        $payroll->allowances = $article->allowances;
        $payroll->gross_salary = $article->gross_salary;
        $payroll->deductions = $article->deductions;
        $payroll->la = $article->la;
        $payroll->pf = $article->pf;
        $payroll->npse = $article->npse;
        $payroll->npser = $article->npser;
        $payroll->net_salary = $request->net_salary;
        $payroll->it = $article->it;
        $payroll->slab = $article->slab;
        if(isset($request->da_month)){
            $payroll->prev_da = $request->prev_da;
            $payroll->da_month = json_encode($request->da_month);
            $payroll->da_basic_salary = json_encode($request->da_basic_salary);
            $payroll->da_due = json_encode($request->da_due);
            $payroll->da_drawn = json_encode($request->da_drawn);
            $payroll->da_arrear = json_encode($request->da_arrear);
            $payroll->tda_due = json_encode($request->tda_due);
            $payroll->tda_drawn = json_encode($request->tda_drawn);
            $payroll->tda_arrear = json_encode($request->tda_arrear);
            $payroll->total_tda = $request->total_tda;
            $payroll->nps_da_arrear = $request->nps_da_arrear;
        }
        $payroll->total_salary = $request->total_salary;
        $payroll->emp_category = $category;
        $payroll->narration = $article->narration;
        $payroll->created_by = Auth::user()->id;
        if($payroll->save()){
            return back()->with(["status"=>true,"message"=>"Payroll verified successfully!","month"=>$monthFull]);
        }else{
            return back()->with(["status"=>false,"message"=>"Can't verify payroll right now!"]);
        }
    }

    public function payslipList(Request $request){
        if(isset($request->month)){
            $month = $request->month;
            $monthTemp = date("Y-m",strtotime($month));
        }
        elseif(session()->has("month")){
            $month = session('month');
            $monthTemp = date("Y-m",strtotime($month));
        }else{
            $month = date("F-Y");
            $monthTemp = date("Y-m");
        }
      
        // $departments = departments::leftJoin('employees', 'departments.id', '=', 'employees.department')
        //             ->select('departments.department',"departments.id", \DB::raw('COUNT(employees.id) as employee_count'))
        //             ->where("departments.status",0)
        //             ->groupBy('departments.id', 'departments.department')
        //             ->get();
        $categories = employees::select('category', \DB::raw('COUNT(employees.id) as employee_count'))
                    ->where("status",0)
                    ->groupBy('category')
                    ->get();

        $payrollCount = payroll_final::where('month', '=', $monthTemp) // Replace $desiredMonth with the month you want to filter
                    ->groupBy('emp_category')
                    ->select('emp_category', \DB::raw('count(employee) as verified_count'))
                    ->pluck('verified_count', 'emp_category')
                    ->all();

        // If you want to ensure that all departments are present in the result with count 0 if no entries are found, you can do:
        $TempCategories = employees::pluck('category');
        foreach ($TempCategories as $category) {
            $tempCate = strtolower($category);
            if (!isset($payrollCount[$tempCate])) {
                $payrollCount[$tempCate] = 0;
            }
        }
                
        
        $employees = payroll_final::leftJoin("designation","designation.id","=","payroll_final.designation")
                                ->leftJoin("employees","employees.empid","=","payroll_final.employee")
                                ->select("employees.*","designation.*")
                                ->where(["payroll_final.month"=>$monthTemp])
                                ->get();
        // dd($employees);
        $verifiedEmployees = payroll_final::where('month', '=', $monthTemp)
                            ->pluck('employee')
                            ->toArray();

        return view("reports.payslip.payslip-list",["categories"=>$categories,"employees"=>$employees,"month"=>$month,"payrollCount"=>$payrollCount,"verifiedEmployees"=>$verifiedEmployees]);
    }
    public function getMonthlyPayslip(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = $request->month;
        $modMonth = date("Y-m",strtotime($month));
        if(!SalaryStructure::where("employee",$id)->where("status",0)->exists()){
            return back()->with(["status"=>false,"message"=>"Please create salary structure for this employee to view payroll"]);
        }

        $structure = payroll_final::leftJoin("employees","employees.empid","=","payroll_final.employee")
                    ->leftJoin("departments","departments.id","=","payroll_final.department")
                    ->leftJoin("designation","designation.id","=","payroll_final.designation")
                    ->leftJoin("paylevels","paylevels.id","=","employees.payscallvl")
                    ->select("payroll_final.*","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc","employees.empname","employees.emppanno","employees.empdoj","employees.empdob","employees.empgender","employees.maritalstatus","paylevels.paylevel as payscallvl","employees.gpfno","employees.npsno")
                    ->where(["payroll_final.month"=>$modMonth,"payroll_final.employee"=>$id])
                    ->first();
        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $la = loans_advances::where("empid",$structure->employee)->where("status",0)->get();
        $pdf = PDF::loadView('reports.payslip.payslip',["structure"=>$structure,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la,"back"=>"payroll","month"=>$month]);
        //->setPaper(array(0, 0, 720, 1440), 'landscape');
        $employeeName = Employees::where("empid",$structure->employee)->where("status",0)->first()->empname;
        $monthName = date("M-Y",strtotime($month));
        // $file_name='Payslip - '.$month.'.pdf';
        $file_name=$employeeName.'-'.$monthName.'.pdf';
        // return $pdf->download($file_name);
        return $pdf->stream('Payslip.pdf');
        // $response = new Response($pdf->output());
        return view("payroll.view_payroll",["structure"=>$structure,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la,"back"=>"payroll","month"=>$month]);
    }

}

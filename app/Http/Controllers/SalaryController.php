<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\departments;
use App\Models\designations;
use App\Models\SalaryStructure;
use App\Models\Employees;
use App\Models\Centers;
use App\Models\da;
use App\Models\AllowanceCategory;
use App\Models\DeductionCategory;
use App\Models\loans_advances;
use App\Models\paylevels;
use App\Models\payroll;
use Illuminate\Support\Collection;
use Exception;

class SalaryController extends Controller
{
    public function index(){
        $departments = departments::where("status","0")->get();
        $designations = designations::where("status","0")->get();
        $structures = SalaryStructure::leftJoin("departments","departments.id","=","salary_structure.department")
                                    ->leftJoin("employees","employees.empid","=","salary_structure.employee")
                                    ->leftJoin("designation","designation.id","=","salary_structure.designation")
                                    ->select("salary_structure.*","employees.empname","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc")
                                    ->where("salary_structure.status",0)
                                    ->where("salary_structure.pensioner",0)
                                    ->orderBy("salary_structure.id","DESC")
                                    ->get();
        $p_structures = SalaryStructure::leftJoin("departments","departments.id","=","salary_structure.department")
                                    ->leftJoin("employees","employees.empid","=","salary_structure.employee")
                                    ->leftJoin("designation","designation.id","=","salary_structure.designation")
                                    ->select("salary_structure.*","employees.empname","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc")
                                    ->where("salary_structure.status",0)
                                    ->where("salary_structure.pensioner",1)
                                    ->orderBy("salary_structure.id","DESC")
                                    ->get();
        return view("salary-structure.salary_structures",["departments"=>$departments,"designations"=>$designations,"structures"=>$structures,"p_structures"=>$p_structures]);
    }

    public function create(){
       
        $departments = departments::where("status","0")->get();
        $designations = designations::where("status","0")->get();
        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $da = $this->calculateDA();
        return view("salary-structure.add_salary_structure",["departments"=>$departments,"designations"=>$designations,"da"=>$da['da'],"allowances"=>$allowances,"deductions"=>$deductions,"arrear_month"=>$da['arrear_month'],"arrear_da"=>$da['arrear_da']]);
    }

    public function add_pension(){
        $departments = departments::where("status","0")->get();
        $designations = designations::where("status","0")->get();
        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $da = $this->calculateDA();
        return view("salary-structure.add_pension_structure",["departments"=>$departments,"designations"=>$designations,"da"=>$da['da'],"allowances"=>$allowances,"deductions"=>$deductions,"arrear_month"=>$da['arrear_month'],"arrear_da"=>$da['arrear_da']]);
    }

    public function getEmployees(Request $request){
        // $dept = $request->dept;
        // $desg = $request->desg;
        $cat = $request->cat;
        $prnop = $request->from;
        $opt = "<option value=''>Select Employee</option>";
        if($cat){
            // $employees = Employees::where(["status"=>0,"department"=>$dept,"designation"=>$desg])->get();
            $employees = Employees::where(["status"=>0,"category"=>$cat,"prnop"=>$prnop])->get();
            if($employees->count()>0){
                foreach($employees as $employee){
                    $opt.="<option value='".$employee->empid."'>".$employee->empname."-".strtoupper($employee->empid)."</option>";
                }
            }else{
                $opt = "<option value=''>No Employees Found</option>";
                return response()->json(['status' => false,'message' => $opt]);
            }
        }
        return response()->json(['status' => true,'message' => $opt]);
    }
    public function getPensionEmployees(Request $request){
        $dept = $request->dept;
        $desg = $request->desg;
        $opt = "<option value=''>Select Employee</option>";
        if($dept && $desg){
            $employees = Employees::where(["status"=>0,"department"=>$dept,"designation"=>$desg,"prnop"=>"pensioner"])->get();
            if($employees->count()>0){
                foreach($employees as $employee){
                    $opt.="<option value='".$employee->empid."'>".$employee->empname."-".strtoupper($employee->empid)."</option>";
                }
            }else{
                $opt = "<option value=''>No Employees Found</option>";
                return response()->json(['status' => false,'message' => $opt]);
            }
        }
        return response()->json(['status' => true,'message' => $opt]);
    }
    public function getLoanDetails(Request $request){
        $empid = $request->empid;
        $month = date("Y-m");
        $employee_det = Employees::where(['status'=> 0,'empid'=>$empid])->first();
        $paylevel = Paylevels::where("id",$employee_det->payscallvl)->first();
        $hra_perc = Centers::where("id",$employee_det->center)->first();
        if($hra_perc){
            $hra_perc = $hra_perc->hra;
        }else{
            $hra_perc = 0;
        }
        if(!$paylevel){
            $payslab=0;
        }else{
            $payslab = $paylevel->slab;
        }
        if(SalaryStructure::where("employee",$empid)->where("status",0)->exists()){
            $existedSalary = SalaryStructure::where("employee",$empid)->where("status",0)->first();
            $la = loans_advances::where("empid",$empid)->where("status",0)->get();
            $newCollection = new Collection();
            foreach($la as $loan){
                $start_month = new \DateTime(date("Y-m",strtotime($loan->startdt)));
                $e_month = date("Y-m",strtotime($month." +1 months"));
                $end_month = new \DateTime($e_month);
                if($start_month<=$end_month){
                    $interval = $start_month->diff($end_month);
                    $interval = $interval->y * 12 + $interval->m;
                    $tenure_duration = date("Y-m",strtotime($loan->startdt." +".($loan->tenure-1)." months"));
                    if($tenure_duration>=$month){
                        $loan->installment = $interval+1;
                        $loan->tenure = $loan->tenure;
                        $diff = $interval+1;
                        if($loan->adj_instal_no>0 && $diff<=$loan->adj_instal_no){
                            $loan->amt = $loan->adj_instal_amt;
                        }else{
                            $loan->amt = $loan->amt;
                        }
                        $newCollection->push($loan);
                    }else{
                        $loan->installment = 0;
                        $loan->tenure = 0;
                        $loan->amt = 0;
                    }
                }else{
                    $loan->installment = 0;
                    $loan->tenure = 0;
                    $loan->amt = 0;
                }
            }
            $la = $newCollection;
            return response()->json(['status' => true,"existing_salary"=>true,'salary' => $existedSalary,"la"=>$la,"quartes"=>$employee_det->quarters,"nps"=>$employee_det->pf_nps_cat,"emppay"=>$employee_det->emppay,"payslab"=>$payslab,"hra_perc"=>$hra_perc,"department"=>$employee_det->department,"designation"=>$employee_det->designation]);
        }else{
            if($empid){
                $la = loans_advances::where("empid",$empid)->where("status",0)->get();
                $newCollection = new Collection();
                foreach($la as $loan){
                    $start_month = new \DateTime(date("Y-m",strtotime($loan->startdt)));
                    $e_month = date("Y-m",strtotime($month." +1 months"));
                    $end_month = new \DateTime($e_month);
                    if($start_month<=$end_month){
                        $interval = $start_month->diff($end_month);
                        $interval = $interval->y * 12 + $interval->m;
                        $tenure_duration = date("Y-m",strtotime($loan->startdt." +".($loan->tenure-1)." months"));
                        if($tenure_duration>=$month){
                            $loan->installment = $interval+1;
                            $loan->tenure = $loan->tenure;
                            $diff = $interval+1;
                            if($loan->adj_instal_no>0 && $diff<=$loan->adj_instal_no){
                                $loan->amt = $loan->adj_instal_amt;
                            }else{
                                $loan->amt = $loan->amt;
                            }
                            $newCollection->push($loan);
                        }else{
                            $loan->installment = 0;
                            $loan->tenure = 0;
                            $loan->amt = 0;
                        }
                    }else{
                        $loan->installment = 0;
                        $loan->tenure = 0;
                        $loan->amt = 0;
                    }
                }
                $la = $newCollection;
                return response()->json(['status' => true,"existing_salary"=>false,'la' => $la,"quartes"=>$employee_det->quarters,"nps"=>$employee_det->pf_nps_cat,"emppay"=>$employee_det->emppay,"payslab"=>$payslab,"hra_perc"=>$hra_perc,"department"=>$employee_det->department,"designation"=>$employee_det->designation]);
            }else{
                return response()->json(['status' => false,'message' => "No Loans present"]);
            }
        }
    }

    public function store(Request $request){
        $validated = $request->validate([
            'department' => 'required|numeric',
            'designation' => 'required|numeric',
            'category' => 'required',
            'employee' => 'required|numeric',
            'basicsalary' => 'required|numeric',
            'da' => 'required|numeric',
            'hra' => 'required|numeric',
            'gross_salary' => 'required|numeric',
            'slab' => 'required|numeric',
            'transport' => 'required|numeric',
            'misc' => 'nullable|numeric',
            'pf' => 'required|numeric',
            'npse' => 'required|numeric',
            'npser' => 'required|numeric',
            'net_salary' => 'required|numeric',
            'it' => 'nullable|numeric',
            'allowance_type.*' => 'sometimes|required',
            'allowance_amount.*' => 'sometimes|required|numeric',
            'deduction_type.*' => 'sometimes|required',
            'deduction_amount.*' => 'sometimes|required|numeric',
            'la_type.*' => 'sometimes|required',
            'la_amount.*' => 'sometimes|required|numeric',
        ],[
            "department.required"=>"Select Department",
            "department.numeric"=>"Selected Department is not valid",
            "designation.required"=>"Select Designation",
            "designation.numeric"=>"Selected Designation is not valid",
            "category.required"=>"Select Category",
            "basicsalary.required"=>"Basic Salary is required",
            "basicsalary.numeric"=>"Basic salary is not valid",
            "da.required"=>"DA is required",
            "da.numeric"=>"DA is not valid",
            "hra.required"=>"HRA is required",
            "hra.numeric"=>"HRA is not valid",
            "gross_salary.required"=>"Gross Salary is required",
            "gross_salary.numeric"=>"Gross salary is not valid",
            "slab.required"=>"Slab is required",
            "slab.numeric"=>"Slab is not valid",
            "transport.required"=>"Transport is required",
            "transport.numeric"=>"Transport is not valid",
            "misc.numeric"=>"Misc. allowance is not valid",
            "pf.required"=>"PF is required",
            "pf.numeric"=>"PF is not valid",
            "npse.required"=>"NPS-Employee is required",
            "npse.numeric"=>"NPS-Employee is not valid",
            "npser.required"=>"NPS-Employer is required",
            "npser.numeric"=>"NPS-Employer is not valid",
            "net_salary.required"=>"Net Salary is required",
            "net_salary.numeric"=>"Net Salary is not valid",
            "it.numeric"=>"IT deduction amount is not valid",
            "allowance_type.*.required"=>"Allowance type details provided are not valid.",
            "allowance_amount.*.required"=>"Allowance amount details provided are not valid.",
            "deduction_type.*.required"=>"Deduction type details provided are not valid.",
            "deduction_amount.*.required"=>"Deduction amount details provided are not valid.",
            "la_type.*.required"=>"Loan/Advance type details provided are not valid.",
            "la_amount.*.required"=>"Loan/Advance amount details provided are not valid.",
        ]);

        if($validated){
            $updateFlag = false;
            try {

                // if(SalaryStructure::where(["department"=>$request->department,"designation"=>$request->designation,"status"=>0])->exists()){
                //     return back()->with(["status"=>false,"message"=>"Salary structure for this department and designation has been created already"]);
                // }
                $allowances=array();
                $deductions=array();
                $la=array();
                if(isset($request->allowance_type)){
                    if(count($request->allowance_type)>0){
                        $allowance_type = $request->allowance_type;
                        $allowance_amount = $request->allowance_amount;
                        for ($i=0; $i < count($allowance_type); $i++) { 
                            $allowances[$allowance_type[$i]]=$allowance_amount[$i];
                        }
                    }
                }

                if(isset($request->deduction_type)){
                    if(count($request->deduction_type)>0){
                        $deduction_type = $request->deduction_type;
                        $deduction_amount = $request->deduction_amount;
                        for ($i=0; $i < count($deduction_type); $i++) { 
                            $deductions[$deduction_type[$i]]=$deduction_amount[$i];
                        }
                    }
                }
                if(isset($request->la_type)){
                    if(count($request->la_type)>0){
                        $la_type = $request->la_type;
                        $la_amount = $request->la_amount;
                        for ($i=0; $i < count($la_type); $i++) { 
                            $la[$la_type[$i]]=$la_amount[$i];
                        }
                    }
                }
                
                if(SalaryStructure::where("employee",$request->employee)->where("status",0)->exists()){
                    $updateFlag = true;
                    $id = SalaryStructure::where("employee",$request->employee)->where("status",0)->first()->id;
                    $salary = SalaryStructure::find($id);
                }else{
                    $updateFlag = false;
                    $salary = new SalaryStructure;
                }
                $salary->department = $request->department;
                $salary->designation = $request->designation;
                $salary->category = $request->category;
                $salary->employee = $request->employee;
                $salary->basic_salary = $request->basicsalary;
                $salary->da = $request->da;
                $salary->hra = $request->hra;
                $salary->da_perc = $request->da_perc;
                $salary->hra_perc = $request->hra_perc;
                $salary->gross_salary = $request->gross_salary;
                $salary->slab = $request->slab;
                $salary->transport = $request->transport;
                $salary->misc = ($request->misc)?$request->misc:0;
                $salary->allowances = json_encode($allowances);
                $salary->pf = $request->pf;
                $salary->npse = $request->npse;
                $salary->npser = $request->npser;
                $salary->deductions = json_encode($deductions);
                $salary->la = json_encode($la);
                $salary->net_salary = $request->net_salary;
                $salary->it = ($request->it)?$request->it:0;
                $salary->narration = $request->narration;
                $salary->created_by = Auth::user()->id;
                if($salary->save()){
                    if($updateFlag){
                            return redirect()->route('salary-structure')->with(["status"=>true,"from"=>"salary","message"=>"Salary structure updated successfully!"]);
                    }else{
                        return back()->with(["status"=>true,"message"=>"Salary structure created successfully!"]);
                    }
                }else{
                    return back()->with(["status"=>false,"message"=>"Couldn't save data! Try Again"]);
                }
            }catch (Exception $e) {
                return back()->with(["status"=>false,"message"=>"Couldn't save data! Try Again"]);
            }
            
        }
    }

    public function store_pension(Request $request){
        
        $validated = $request->validate([
            // 'department' => 'required|numeric',
            // 'designation' => 'required|numeric',
            'category' => 'required',
            'employee' => 'required|numeric',
            'basicsalary' => 'required|numeric',
            'da' => 'required|numeric',
            'addtl_pension' => 'nullable|numeric',
            'medic_allow' => 'nullable|numeric',
            'misc' => 'nullable|numeric',
            'less_comm' => 'nullable|numeric',
            'gross_salary' => 'required|numeric',
            'misc_rec' => 'nullable|numeric',
            'irg' => 'nullable|numeric',
            'it' => 'nullable|numeric',
            'net_salary' => 'required|numeric',
            'allowance_type.*' => 'sometimes|required',
            'allowance_amount.*' => 'sometimes|required|numeric',
            'deduction_type.*' => 'sometimes|required',
            'deduction_amount.*' => 'sometimes|required|numeric',
            'la_type.*' => 'sometimes|required',
            'la_amount.*' => 'sometimes|required|numeric',
        ],[
            "department.required"=>"Select Department",
            "department.numeric"=>"Selected Department is not valid",
            "designation.required"=>"Select Designation",
            "designation.numeric"=>"Selected Designation is not valid",
            "category.required"=>"Select Category",
            "basicsalary.required"=>"Basic Salary is required",
            "basicsalary.numeric"=>"Basic salary is not valid",
            "da.required"=>"DA is required",
            "da.numeric"=>"DA is not valid",
            "addtl_pension.numeric"=>"Additional Pension is not valid",
            "medic_allow.numeric"=>"Medical Allowance is not valid",
            "less_comm.numeric"=>"Less commutation is not valid",
            "misc.numeric"=>"Misc. allowance is not valid",
            "gross_salary.required"=>"Gross Salary is required",
            "gross_salary.numeric"=>"Gross salary is not valid",
            "misc_rec.numeric"=>"Misc Recovery amount is not valid",
            "irg.numeric"=>"IRG amount is not valid",
            "it.numeric"=>"IT deduction amount is not valid",
            "net_salary.required"=>"Net Salary is required",
            "net_salary.numeric"=>"Net Salary is not valid",
            "allowance_type.*.required"=>"Allowance type details provided are not valid.",
            "allowance_amount.*.required"=>"Allowance amount details provided are not valid.",
            "deduction_type.*.required"=>"Deduction type details provided are not valid.",
            "deduction_amount.*.required"=>"Deduction amount details provided are not valid.",
            "la_type.*.required"=>"Loan/Advance type details provided are not valid.",
            "la_amount.*.required"=>"Loan/Advance amount details provided are not valid.",
        ]);

        if($validated){
            $updateFlag = false;
            try {

                // if(SalaryStructure::where(["department"=>$request->department,"designation"=>$request->designation,"status"=>0])->exists()){
                //     return back()->with(["status"=>false,"message"=>"Salary structure for this department and designation has been created already"]);
                // }
                $allowances=array();
                $deductions=array();
                $la=array();
                if(isset($request->allowance_type)){
                    if(count($request->allowance_type)>0){
                        $allowance_type = $request->allowance_type;
                        $allowance_amount = $request->allowance_amount;
                        for ($i=0; $i < count($allowance_type); $i++) { 
                            $allowances[$allowance_type[$i]]=$allowance_amount[$i];
                        }
                    }
                }

                if(isset($request->deduction_type)){
                    if(count($request->deduction_type)>0){
                        $deduction_type = $request->deduction_type;
                        $deduction_amount = $request->deduction_amount;
                        for ($i=0; $i < count($deduction_type); $i++) { 
                            $deductions[$deduction_type[$i]]=$deduction_amount[$i];
                        }
                    }
                }
                if(isset($request->la_type)){
                    if(count($request->la_type)>0){
                        $la_type = $request->la_type;
                        $la_amount = $request->la_amount;
                        for ($i=0; $i < count($la_type); $i++) { 
                            $la[$la_type[$i]]=$la_amount[$i];
                        }
                    }
                }
                $month = date("Y-m");
                $sel_month = date("Y-m");
                if(isset($request->route)){
                    $route = $request->route;
                    $sel_month = date("Y-m",strtotime($request->month));
                }
                else{
                    $route = null;
                }

                if($sel_month==$month)
                {
                    if(SalaryStructure::where("employee",$request->employee)->where("status",0)->exists()){
                        $updateFlag = true;
                        $id = SalaryStructure::where("employee",$request->employee)->where("status",0)->first()->id;
                        $salary = SalaryStructure::find($id);
                    }else{
                        $updateFlag = false;
                        $salary = new SalaryStructure;
                    }
                    // $salary->department = $request->department;
                    // $salary->designation = $request->designation;
                    $salary->category = $request->category;
                    $salary->employee = $request->employee;
                    $salary->basic_salary = $request->basicsalary;
                    $salary->addtl_pension = ($request->addtl_pension)?$request->addtl_pension:0;
                    $salary->da = $request->da;
                    $salary->hra = 0;
                    $salary->da_perc = $request->da_perc;
                    $salary->hra_perc = 0;
                    $salary->medic_allow = ($request->medic_allow)?$request->medic_allow:0;
                    $salary->less_comm = ($request->less_comm)?$request->less_comm:0;
                    $salary->gross_salary = $request->gross_salary;
                    $salary->slab = 0;
                    $salary->transport = 0;
                    $salary->misc = ($request->misc)?$request->misc:0;
                    $salary->allowances = json_encode($allowances);
                    $salary->pf = 0;
                    $salary->npse = 0;
                    $salary->npser = 0;
                    $salary->deductions = json_encode($deductions);
                    $salary->la = json_encode($la);
                    $salary->net_salary = $request->net_salary;
                    $salary->misc_rec = ($request->misc_rec)?$request->misc_rec:0;
                    $salary->irg = ($request->irg)?$request->irg:0;
                    $salary->it = ($request->it)?$request->it:0;
                    $salary->pensioner = 1;
                    $salary->narration = $request->narration;
                    $salary->created_by = Auth::user()->id;
                }else{
                    if(!payroll::where("month",$sel_month)->where("employee",$request->employee)->exists()){
                        return back()->with(["status"=>true,"message"=>"Sorry! Salary structure not found"]);
                    }

                    $payroll = payroll::where("month",$sel_month)->where("employee",$request->employee)->first();

                    $salary = payroll::find($payroll->id);
                    $salary->emp_category = $request->category;
                    $salary->employee = $request->employee;
                    $salary->basic_salary = $request->basicsalary;
                    $salary->addtl_pension = ($request->addtl_pension)?$request->addtl_pension:0;
                    $salary->da = $request->da;
                    $salary->hra = 0;
                    $salary->da_perc = $request->da_perc;
                    $salary->hra_perc = 0;
                    $salary->medic_allow = ($request->medic_allow)?$request->medic_allow:0;
                    $salary->less_comm = ($request->less_comm)?$request->less_comm:0;
                    $salary->gross_salary = $request->gross_salary;
                    $salary->slab = 0;
                    $salary->transport = 0;
                    $salary->misc = ($request->misc)?$request->misc:0;
                    $salary->allowances = json_encode($allowances);
                    $salary->pf = 0;
                    $salary->npse = 0;
                    $salary->npser = 0;
                    $salary->deductions = json_encode($deductions);
                    $salary->la = json_encode($la);
                    $salary->net_salary = $request->net_salary;
                    $salary->misc_rec = ($request->misc_rec)?$request->misc_rec:0;
                    $salary->irg = ($request->irg)?$request->irg:0;
                    $salary->it = ($request->it)?$request->it:0;
                    
                    if($salary->da_arrear){
                        $da_arrear = array_sum(json_decode($salary->da_arrear));
                    }else{
                        $da_arrear = 0;
                    }
                    $salary->total_salary = $request->net_salary+$da_arrear;
                    $salary->pensioner = 1;
                    $salary->narration = $request->narration;
                    $salary->created_by = Auth::user()->id;
                }
                
                if($salary->save()){
                    if($updateFlag){
                        if($request->route!="null" && $request->route!=null){
                            return redirect($request->route)->with(["status"=>true,"message"=>"Salary structure updated successfully!"]);
                        }else{
                            return redirect()->route('salary-structure')->with(["status"=>true,"from"=>"pension","message"=>"Salary structure updated successfully!"]);
                        }
                    }else{
                        return back()->with(["status"=>true,"from"=>"pension","message"=>"Salary structure created successfully!"]);
                    }
                }else{
                    return back()->with(["status"=>false,"from"=>"pension","message"=>"Couldn't save data! Try Again"]);
                }
            }catch (Exception $e) {
                return back()->with(["status"=>false,"from"=>"pension","message"=>"Couldn't save data! Try Again"]);
            }
            
        }
    }

    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = date("Y-m");
        $model = new SalaryStructure;
        if(isset($request->route)){
            $route = $request->route;
            $sel_month = date("Y-m",strtotime($request->month));
            if($month == $sel_month){
                $model = new SalaryStructure;
            }else{
                $model = new payroll;
            }
            $month = $sel_month;
        }else{
            $route=null;
        }

        if($model::where("id",$id)->exists()){
            $arrear_month = null;
            $arrear_da = null;
            $current_month = date("Y-m");
            $start_month = date("Y-01");
            $departments = departments::where("status","0")->get();
            $designations = designations::where("status","0")->get();
            $da = da::where("status","0")->where("year","<=",$month)->orderBy("year","DESC")->get();
            $latest_da_year = date("Y",strtotime($da->first()->year));
            $current_year = date("Y");
            if($latest_da_year == $current_year){
                $count_of_da = $da->where("year",">=",$start_month)->count();
                if($count_of_da==1){
                    $last_year_da = $da->skip(1)->first();
                    if($last_year_da){
                        if($last_year_da->da != $da->first()->da){
                            $arrear_month = date("m")-1;
                            $arrear_da = (int)$da->first()->da-(int)$last_year_da->da;
                            $da = $da->first()->da;
                        }else{
                            $da = $da->first()->da;
                        }
                    }else{
                        $da = $da->first()->da;
                    }
                }else{
                    $da = $da->first()->da;
                }
            }else{
                $da = $da->first()->da;
            }
            $allowances = AllowanceCategory::where('status', 0)->get();
            $deductions = DeductionCategory::where("status","0")->get();
            $structure = $model::find($id);
            $empdet = Employees::where("empid",$structure->employee)->where("status",0)->first();
            $employees = Employees::where(["status"=>0,"category"=>$empdet->category])->get();
            $la = loans_advances::where("empid",$structure->employee)->where("status",0)->get();
            $newCollection = new Collection();
            foreach($la as $loan){
                $start_month = new \DateTime(date("Y-m",strtotime($loan->startdt)));
                $e_month = date("Y-m",strtotime($month." +1 months"));
                $end_month = new \DateTime($e_month);
                if($start_month<=$end_month){
                    $interval = $start_month->diff($end_month);
                    $interval = $interval->y * 12 + $interval->m;
                    $tenure_duration = date("Y-m",strtotime($loan->startdt." +".($loan->tenure-1)." months"));
                    if($tenure_duration>=$month){
                        $loan->installment = $interval+1;
                        $loan->tenure = $loan->tenure;
                        $diff = $interval+1;
                        if($loan->adj_instal_no>0 && $diff<=$loan->adj_instal_no){
                            $loan->amt = $loan->adj_instal_amt;
                        }else{
                            $loan->amt = $loan->amt;
                        }
                        $newCollection->push($loan);
                    }else{
                        $loan->installment = 0;
                        $loan->tenure = 0;
                        $loan->amt = 0;
                    }
                }else{
                    $loan->installment = 0;
                    $loan->tenure = 0;
                    $loan->amt = 0;
                }
            }
            $la = $newCollection;
            if($structure->category==""){
                $structure->category = $empdet->category;
            }
            if($structure->pensioner==0){
                return view("salary-structure.modify_salary_structure",["structure"=>$structure,"employees"=>$employees,"designations"=>$designations,"departments"=>$departments,"da"=>$da,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la,"arrear_month"=>$arrear_month,"arrear_da"=>$arrear_da,"route"=>$route,"empdet"=>$empdet,"month"=>$month]);
            }else{
                return view("salary-structure.modify_pension_structure",["structure"=>$structure,"employees"=>$employees,"designations"=>$designations,"departments"=>$departments,"da"=>$da,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la,"arrear_month"=>$arrear_month,"arrear_da"=>$arrear_da,"route"=>$route,"empdet"=>$empdet,"month"=>$month]);
            }
        }else{
            return back()->with(["status"=>true,"message"=>"Sorry! Salary structure not found"]);
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $month = date("Y-m");
        $model = new SalaryStructure;
        if(isset($request->route)){
            $route = $request->route;
            $sel_month = date("Y-m",strtotime($request->month));
            if($month == $sel_month){
                $model = new SalaryStructure;
            }else{
                $model = new payroll;
            }
            $month = $sel_month;
        }else{
            $route=null;
        } 
        if(!$model::where("id",$id)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Salary structure not found"]);
        }

        $validated = $request->validate([
            'department' => 'required|numeric',
            'designation' => 'required|numeric',
            'category' => 'required',
            'basicsalary' => 'required|numeric',
            'da' => 'required|numeric',
            'hra' => 'required|numeric',
            'gross_salary' => 'required|numeric',
            'slab' => 'required|numeric',
            'transport' => 'required|numeric',
            'misc' => 'nullable|numeric',
            'pf' => 'required|numeric',
            'npse' => 'required|numeric',
            'npser' => 'required|numeric',
            'net_salary' => 'required|numeric',
            'it' => 'nullable|numeric',
        ],[
            "department.required"=>"Select Department",
            "department.numeric"=>"Selected Department is not valid",
            "designation.required"=>"Select Designation",
            "designation.numeric"=>"Selected Designation is not valid",
            "category.required"=>"Select Category",
            "basicsalary.required"=>"Basic Salary is required",
            "basicsalary.numeric"=>"Basic salary is not valid",
            "da.required"=>"DA is required",
            "da.numeric"=>"DA is not valid",
            "hra.required"=>"HRA is required",
            "hra.numeric"=>"HRA is not valid",
            "gross_salary.required"=>"Gross Salary is required",
            "gross_salary.numeric"=>"Gross salary is not valid",
             "slab.required"=>"Slab is required",
            "slab.numeric"=>"Slab is not valid",
            "transport.required"=>"Transport is required",
            "transport.numeric"=>"Transport is not valid",
            "misc.numeric"=>"Misc. allowance is not valid",
            "pf.required"=>"PF is required",
            "pf.numeric"=>"PF is not valid",
            "npse.required"=>"NPS-Employee is required",
            "npse.numeric"=>"NPS-Employee is not valid",
            "npser.required"=>"NPS-Employer is required",
            "npser.numeric"=>"NPS-Employer is not valid",
            "net_salary.required"=>"Net Salary is required",
            "net_salary.numeric"=>"Net Salary is not valid",
            "it.numeric"=>"IT deduction amount is not valid",
        ]);

        if($validated){
            try {

                // if(SalaryStructure::where(["department"=>$request->department,"designation"=>$request->designation,"status"=>0])->where("id","!=",$id)->exists()){
                //     return back()->with(["status"=>false,"message"=>"Salary structure for this department and designation has been created already"]);
                // }
                $allowances=array();
                $deductions=array();
                $la=array();
                if(isset($request->allowance_type)){
                    if(count($request->allowance_type)>0){
                        $allowance_type = $request->allowance_type;
                        $allowance_amount = $request->allowance_amount;
                        for ($i=0; $i < count($allowance_type); $i++) { 
                            $allowances[$allowance_type[$i]]=$allowance_amount[$i];
                        }
                    }
                }

                if(isset($request->deduction_type)){
                    if(count($request->deduction_type)>0){
                        $deduction_type = $request->deduction_type;
                        $deduction_amount = $request->deduction_amount;
                        for ($i=0; $i < count($deduction_type); $i++) { 
                            $deductions[$deduction_type[$i]]=$deduction_amount[$i];
                        }
                    }
                }
                if(isset($request->la_type)){
                    if(count($request->la_type)>0){
                        $la_type = $request->la_type;
                        $la_amount = $request->la_amount;
                        for ($i=0; $i < count($la_type); $i++) { 
                            $la[$la_type[$i]]=$la_amount[$i];
                        }
                    }
                }
                $salary = $model::find($id);
                $salary->department = $request->department;
                $salary->designation = $request->designation;
                if($model == new SalaryStructure){
                    $salary->category = $request->category;
                }else{
                    $salary->emp_category = $request->category;
                    if($salary->da_arrear){
                        $da_arrear = array_sum(json_decode($salary->da_arrear));
                    }else{
                        $da_arrear = 0;
                    }
                    $salary->total_salary = $request->net_salary+$da_arrear;
                }
                $salary->employee = $request->employee;
                $salary->basic_salary = $request->basicsalary;
                $salary->da = $request->da;
                $salary->hra = $request->hra;
                $salary->da_perc = $request->da_perc;
                $salary->hra_perc = $request->hra_perc;
                $salary->gross_salary = $request->gross_salary;
                $salary->slab = $request->slab;
                $salary->transport = $request->transport;
                $salary->misc = ($request->misc)?$request->misc:0;
                $salary->allowances = json_encode($allowances);
                $salary->pf = $request->pf;
                $salary->npse = $request->npse;
                $salary->npser = $request->npser;
                $salary->deductions = json_encode($deductions);
                $salary->la = json_encode($la);
                $salary->net_salary = $request->net_salary;
                $salary->it = ($request->it)?$request->it:0;
                $salary->narration = $request->narration;
                $salary->created_by = Auth::user()->id;
                if($salary->save()){
                    if($request->route!="null" && $request->route!=null){
                        if($model==new payroll){
                            $this->fix_total_salary($request->employee,$request->month);
                        }
                        return redirect($request->route)->with(["status"=>true,"from"=>"salary","message"=>"Salary structure updated successfully!"]);
                    }else{
                        return redirect()->route('salary-structure')->with(["status"=>true,"from"=>"salary","message"=>"Salary structure updated successfully!"]);
                    }
                }else{
                    return back()->with(["status"=>false,"from"=>"salary","message"=>"Couldn't save data! Try Again"]);
                }
            } catch (Exception $e) {
                return back()->with(["status"=>false,"from"=>"salary","message"=>"Couldn't save data! Try Again"]);
            }
            
        }

    }

    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!SalaryStructure::where("id",$id)->where("status",0)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Salary structure not found"]);
        }

        $salary = SalaryStructure::find($id);
        $salary->status = 1;
        $salary->created_by = Auth::user()->id;
        if($salary->pensioner==1){
            $from = "pension";
        }else{
            $from = "salary";
        }
        if($salary->save()){
            return back()->with(["status"=>true,"from"=>$from,"message"=>"Salary structure deleted successfully!"]);
        }else{
            return back()->with(["status"=>false,"from"=>$from,"message"=>"Couldn't save data! Try Again"]);
        }
        
    }

    public function view(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!SalaryStructure::where("id",$id)->where("status",0)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Salary structure not found"]);
        }

        $structure = SalaryStructure::leftJoin("employees","employees.empid","=","salary_structure.employee")
                    ->leftJoin("departments","departments.id","=","salary_structure.department")
                    ->leftJoin("designation","designation.id","=","salary_structure.designation")
                    ->select("salary_structure.*","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc","employees.empname")
                    ->where(["salary_structure.status"=>0,"salary_structure.id"=>$id])
                    ->first();
        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $la = loans_advances::where("empid",$structure->employee)->where("status",0)->get();

        if($structure->pensioner==1){
            return view("salary-structure.view_pension_structures",["structure"=>$structure,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la]);
        }else{
            return view("salary-structure.view_salary_structures",["structure"=>$structure,"allowances"=>$allowances,"deductions"=>$deductions,"la"=>$la]);
        }
    }

    public function calculateDA(){
        $arrear_month = null;
        $arrear_da = null;
        $current_month = date("Y-m");
        $start_month = date("Y-01");
        $da = da::where("status","0")->where("year","<=",$current_month)->orderBy("year","DESC")->get();
        $latest_da_year = date("Y",strtotime($da->first()->year));
        $current_year = date("Y");
        if($latest_da_year == $current_year){
            $count_of_da = $da->where("year",">=",$start_month)->count();
            if($count_of_da==1){
                $last_year_da = $da->skip(1)->first();
                if($last_year_da){
                    if($last_year_da->da != $da->first()->da){
                        $arrear_month = date("m")-1;
                        $arrear_da = (int)$da->first()->da-(int)$last_year_da->da;
                        $da = $da->first()->da;
                    }else{
                        $da = $da->first()->da;
                    }
                }else{
                    $da = $da->first()->da;
                }
            }else{
                $da = $da->first()->da;
            }
        }else{
            $da = $da->first()->da;
        }

        return [
            "da"=>$da,
            "arrear_month"=>$arrear_month,
            "arrear_da"=>$arrear_da
        ];
    }

    public function consolidated(){
        $structures = SalaryStructure::leftJoin("departments","departments.id","=","salary_structure.department")
                                    ->leftJoin("employees","employees.empid","=","salary_structure.employee")
                                    ->leftJoin("designation","designation.id","=","salary_structure.designation")
                                    ->select("salary_structure.*","employees.empname","departments.department as dept","departments.desg_department as dept_desc","designation.designation as desg","designation.desg_description as desg_desc")
                                    ->where("salary_structure.status",0)
                                    ->where("salary_structure.pensioner",0)
                                    ->orderBy("salary_structure.id","DESC")
                                    ->get();
        $allowances = AllowanceCategory::where('status', 0)->get();
        $deductions = DeductionCategory::where("status","0")->get();
        $la = loans_advances::where("status",0)->get();
        return view("salary-structure.consolidated_salary_structures",["structures"=>$structures,"allowances"=>$allowances,"deductions"=>$deductions,"loans_advances"=>$la]);
    }

    public function fix_total_salary($empid,$month){
        // $empArray = ["11880"];
        // $empid = $request->empid;
        $payrolls = payroll::where("month",$month)->where("employee",$empid)->get();
        foreach($payrolls as $payroll){
            $pay = payroll::find($payroll->id);
            $gross_salary = $pay->gross_salary;
            $deductions = json_decode($pay->deductions);
            $la = json_decode($pay->la);
            $deduction = 0;
            foreach($deductions as $ded){
                $deduction += $ded;
            }
            foreach($la as $l){
                $deduction += $l;
            }
            $deduction =$deduction+$pay->npse+$pay->pf+$pay->it+$pay->nps_da_arrear; 

            $net_salary = $gross_salary-$deduction;

            if($pay->da_arrear){
                $da_arrear = array_sum(json_decode($pay->da_arrear));
                $tda_arrear = $pay->total_tda;
                $da_value = $da_arrear+$tda_arrear;
            }
            else{
                $da_value = 0;
            }

            $total_salary = $net_salary+$da_value;

            // dd($gross_salary,$net_salary,$deduction,$total_salary);
            // if($pay->da_arrear){
            //     $da_arrear = array_sum(json_decode($pay->da_arrear));
            //     $tda_arrear = $pay->total_tda;
            //     $da_value = $da_arrear+$tda_arrear;
            // }else{
            //     $da_arrear = 0;
            //     $tda_arrear = 0;
            //     $da_value = 0;
            // }
            $pay->net_salary = $net_salary;
            $pay->total_salary = $total_salary;
            $pay->save();
        }

       return true;
    }
}

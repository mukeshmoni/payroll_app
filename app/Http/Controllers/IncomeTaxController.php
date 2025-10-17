<?php

namespace App\Http\Controllers;

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
use App\Models\income_tax;
use App\Models\rap;

class IncomeTaxController extends Controller
{

    public $current_month;
    public $current_year_start;
    public $current_year_end;

    public function __construct(){
        $this->current_month=date("Y-m");
        $this->current_year_start=date("Y-01");
        $this->current_year_end=date("Y-12");
    }

    public function index(){
        $departments = departments::leftJoin('employees', 'departments.id', '=', 'employees.department')
                    ->select('departments.department',"departments.id", \DB::raw('COUNT(employees.id) as employee_count'))
                    ->where("departments.status",0)
                    ->groupBy('departments.id', 'departments.department')
                    ->get();
        $employees = Employees::join("designation","designation.id","=","employees.designation")
                    ->select("employees.*","designation.*")
                    ->where("employees.status",0)
                    ->orderBy("employees.empname","ASC")
                    ->get();
        return view("income_tax.income_tax",["departments"=> $departments,"employees"=> $employees]);
    }

    public function generate_dept(Request $request){
        $dept = Crypt::decryptString($request->dept);
        $empid = "";
        $emparray = Employees::where("department", $dept)->where("status",0)->orderBy("empname","ASC")->pluck('empid')->toArray();

        foreach ($emparray as $emp) {
            if(payroll_final::where("employee",$emp)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->exists()){
                $empid = $emp;
                break;
            }
        }
        
        if($empid==""){
            return back()->with(["status"=>false,"message"=>"Please update payroll for this employee to view Income Tax"]);
        }

        $empdetails = $this->getEmployeeDetails($dept, $empid);
        $salaryDetails = $this->getSalaryDetails($dept, $empid);
        $rap = $this->getRAPDetails($empid);
        $da = da::where("status","0")->where("year","<=",$this->current_month)->orderBy("year","DESC")->first();
        $it_deducted = payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->sum("it");
        return view("income_tax.generate",["empDetails"=> $empdetails,"salaryDetails"=>$salaryDetails,"da"=>$da,"it_deducted"=>$it_deducted,"rap"=>$rap]);
    }

    public function generate_emp(Request $request){
        $dept = Crypt::decryptString($request->dept);
        $empid = Crypt::decryptString($request->empid);

        if(!payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->exists()){
            return back()->with(["status"=>false,"message"=>"Please update payroll for the employee to update Income Tax"]);
        }
        // $emparray = Employees::where("department", $dept)->where("status",0)->orderBy("empname","ASC")->pluck('empid')->toArray();

        // $index = array_search($empid, $emparray);

        // if($index==0){
        //     return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
        // }else{
        //     $prev_empid = $index-1;
        //     $empid = $emparray[$prev_empid];
        // }

        $empdetails = $this->getEmployeeDetails($dept, $empid);
        $rap = $this->getRAPDetails($empid);
        $salaryDetails = $this->getSalaryDetails($dept, $empid);
        $da = da::where("status","0")->where("year","<=",$this->current_month)->orderBy("year","DESC")->first();
        $it_deducted = payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->sum("it");

        return view("income_tax.generate",["empDetails"=> $empdetails,"salaryDetails"=>$salaryDetails,"da"=>$da,"it_deducted"=>$it_deducted,"rap"=>$rap]);
    }

    public function generate_prev(Request $request){
        $dept = Crypt::decryptString($request->dept);
        $empid = Crypt::decryptString($request->empid);
        $emparray = Employees::where("department", $dept)->where("status",0)->orderBy("empname","ASC")->pluck('empid')->toArray();

        $index = array_search($empid, $emparray);

        if($index==0){
            return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Next"]);
        }else{
            $prev_empid = $index-1;
            $empid = $emparray[$prev_empid];
        }

        if(!payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->exists()){
            return back()->with(["status"=>false,"message"=>"Please update payroll for employee ".strtoupper($empid)." to view Income Tax"]);
        }

        $empdetails = $this->getEmployeeDetails($dept, $empid);
        $rap = $this->getRAPDetails($empid);
        $salaryDetails = $this->getSalaryDetails($dept, $empid);
        $da = da::where("status","0")->where("year","<=",$this->current_month)->orderBy("year","DESC")->first();
        $it_deducted = payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->sum("it");
        return view("income_tax.generate",["empDetails"=> $empdetails,"salaryDetails"=>$salaryDetails,"da"=>$da,"it_deducted"=>$it_deducted,"rap"=>$rap]);
    }

    public function generate_next(Request $request){
        $dept = Crypt::decryptString($request->dept);
        $empid = Crypt::decryptString($request->empid);
        

        $emparray = Employees::where("department", $dept)->where("status",0)->orderBy("empname","ASC")->pluck('empid')->toArray();

        $index = array_search($empid, $emparray);

        if($index==count($emparray)-1){
            return back()->with(["status"=>false,"message"=>"There is no employee details to view, Click Previous"]);
        }else{
            $next_empid = $index+1;
            $empid = $emparray[$next_empid];
        }

        if(!payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->exists()){
            return back()->with(["status"=>false,"message"=>"Please update payroll for employee ".strtoupper($empid)." to view Income Tax"]);
        }

        $empdetails = $this->getEmployeeDetails($dept, $empid);
        $rap = $this->getRAPDetails($empid);
        $salaryDetails = $this->getSalaryDetails($dept, $empid);
        $da = da::where("status","0")->where("year","<=",$this->current_month)->orderBy("year","DESC")->first();
        $it_deducted = payroll_final::where("employee",$empid)->where("month",">=",$this->current_year_start)->where("month","<=",$this->current_month)->sum("it");
        return view("income_tax.generate",["empDetails"=> $empdetails,"salaryDetails"=>$salaryDetails,"da"=>$da,"it_deducted"=>$it_deducted,"rap"=>$rap]);
    }

    public function view(Request $request){
        $dept = Crypt::decryptString($request->dept);
        $empid = Crypt::decryptString($request->empid);

        if(!income_tax::where("empid",$empid)->where("status",0)->exists()){
            return back()->with(["status"=>false,"message"=>"Please update income tax for the employee to view"]);
        }

        $incomeTax = income_tax::where("empid",$empid)->where("status",0)->orderBy("id","DESC")->first();
        $empdetails = $this->getEmployeeDetails($dept, $empid);

        return view("income_tax.view",["empDetails"=>$empdetails,"incomeTax"=>$incomeTax]);
    }
    public function update_income_tax(Request $request){
        $empid = Crypt::decryptString($request->empid);
        $dept = $request->dept;
        $regime = $request->regime;
        $year = date("Y");
        if(income_tax::where(["empid"=>$empid,"deptid"=>$dept,"year"=>$year,"status"=>0])->exists()){
            income_tax::where(["empid"=>$empid,"deptid"=>$dept,"year"=>$year,"status"=>0])->update(["status"=>1]);
        }
        if($regime=="old"){
            $incomeTax = new income_tax;
            $incomeTax->year = $year;
            $incomeTax->empid = $empid;
            $incomeTax->deptid = $dept;
            $incomeTax->age = $request->age;
            $incomeTax->regime = $request->regime;
            $incomeTax->salary = $request->salary;
            $incomeTax->arrears = $request->arrears;
            $incomeTax->child_edu = $request->child_edu;
            $incomeTax->enc_of_el = $request->enc_of_el;
            $incomeTax->remuneration = $request->remuneration;
            $incomeTax->npser = $request->npser;
            $incomeTax->house_property = $request->house_property;
            $incomeTax->other_income = $request->other_income;
            $incomeTax->gross_income = $request->gross_income;
            $incomeTax->rap_data = $request->rap_data;
            $incomeTax->rap_value = json_encode($request->rap_value);
            $incomeTax->rap_total = $request->rap_total;
            $incomeTax->gross_income_rap = $request->gross_income_rap;
            $incomeTax->license_fee = $request->license_fee;
            $incomeTax->govt_nps = $request->govt_nps;
            $incomeTax->standard_deduction = $request->standard_deduction;
            $incomeTax->hra_received = $request->hra_received;
            $incomeTax->rent_paid = $request->rent_paid;
            $incomeTax->rent_calc = $request->rent_calc;
            $incomeTax->hra_balance = $request->hra_balance;
            $incomeTax->hra_exempted = $request->hra_exempted;
            $incomeTax->prefessional_tax = $request->prefessional_tax;
            $incomeTax->balance_after_pt = $request->balance_after_pt;
            $incomeTax->premia_insurance = $request->premia_insurance;
            $incomeTax->payment_interest = $request->payment_interest;
            $incomeTax->higher_education = $request->higher_education;
            $incomeTax->disability_deduction = $request->disability_deduction;
            $incomeTax->other_deduction = $request->other_deduction;
            $incomeTax->total_deduction_1 = $request->total_deduction_1;
            $incomeTax->deduction_balance_1 = $request->deduction_balance_1;
            $incomeTax->lic_pf = $request->lic_pf;
            $incomeTax->subscription_gpf = $request->subscription_gpf;
            $incomeTax->lic_premium = $request->lic_premium;
            $incomeTax->pli_premium = $request->pli_premium;
            $incomeTax->gslis = $request->gslis;
            $incomeTax->ulip = $request->ulip;
            $incomeTax->nsc = $request->nsc;
            $incomeTax->post_office = $request->post_office;
            $incomeTax->public_pf = $request->public_pf;
            $incomeTax->spl_secu = $request->spl_secu;
            $incomeTax->interest_nsc = $request->interest_nsc;
            $incomeTax->repayment_cost = $request->repayment_cost;
            $incomeTax->tuition_fees = $request->tuition_fees;
            $incomeTax->fixed_deposit = $request->fixed_deposit;
            $incomeTax->total_savings = $request->total_savings;
            $incomeTax->eligible_deduction = $request->eligible_deduction;
            $incomeTax->deduction_balance_2 = $request->deduction_balance_2;
            $incomeTax->nps_add = $request->nps_add;
            $incomeTax->total_amount = $request->total_amount;
            $incomeTax->income_tax_round = $request->income_tax_round;
            $incomeTax->income_tax = $request->income_tax;
            $incomeTax->tax_rebate = $request->tax_rebate;
            $incomeTax->net_income_tax = $request->net_income_tax;
            $incomeTax->health_cess = $request->health_cess;
            $incomeTax->amt_to_be_deducted = $request->amt_to_be_deducted;
            $incomeTax->already_deducted = $request->already_deducted;
            $incomeTax->balance_to_be_deducted = $request->balance_to_be_deducted;
            $incomeTax->nov_month = $request->nov_month;
            $incomeTax->dec_month = $request->dec_month;
            $incomeTax->jan_month = $request->jan_month;
            $incomeTax->feb_month = $request->feb_month;
            $incomeTax->total_month_deduction = $request->total_month_deduction;
            $incomeTax->created_by = Auth::user()->id;
        }else{
            $incomeTax = new income_tax;
            $incomeTax->year = $year;
            $incomeTax->empid = $empid;
            $incomeTax->deptid = $dept;
            $incomeTax->age = $request->age;
            $incomeTax->regime = $request->regime;
            $incomeTax->salary = $request->salary;
            $incomeTax->arrears = $request->arrears;
            $incomeTax->child_edu = $request->child_edu;
            $incomeTax->enc_of_el = $request->enc_of_el;
            $incomeTax->remuneration = $request->remuneration;
            $incomeTax->npser = $request->npser;
            $incomeTax->house_property = $request->house_property;
            $incomeTax->other_income = $request->other_income;
            $incomeTax->gross_income = $request->gross_income;
            $incomeTax->rap_data = $request->rap_data;
            $incomeTax->rap_value = json_encode($request->rap_value);
            $incomeTax->rap_total = $request->rap_total;
            $incomeTax->gross_income_rap = $request->gross_income_rap;
            $incomeTax->license_fee = $request->license_fee;
            $incomeTax->govt_nps = $request->govt_nps;
            $incomeTax->standard_deduction = $request->standard_deduction;
            $incomeTax->total_amount = $request->total_amount;
            $incomeTax->income_tax_round = $request->income_tax_round;
            $incomeTax->income_tax = $request->income_tax;
            $incomeTax->tax_rebate = $request->tax_rebate;
            $incomeTax->net_income_tax = $request->net_income_tax;
            $incomeTax->health_cess = $request->health_cess;
            $incomeTax->amt_to_be_deducted = $request->amt_to_be_deducted;
            $incomeTax->already_deducted = $request->already_deducted;
            $incomeTax->balance_to_be_deducted = $request->balance_to_be_deducted;
            $incomeTax->nov_month = $request->nov_month;
            $incomeTax->dec_month = $request->dec_month;
            $incomeTax->jan_month = $request->jan_month;
            $incomeTax->feb_month = $request->feb_month;
            $incomeTax->total_month_deduction = $request->total_month_deduction;
            $incomeTax->created_by = Auth::user()->id;
        }
        if($incomeTax->save()){
            return back()->with(["status"=>true,"message"=>"Income Tax updated successfully! for employee ".strtoupper($empid).""]);
        }else{
            return back()->with(["status"=>false,"message"=>"Can't update income tax right now!"]);
        }
    }

    public function getEmployeeDetails($dept,$empid){
        $empdetails = Employees::join("designation","designation.id","=","employees.designation")
                            ->join("departments","departments.id","=","employees.department")
                            ->select("employees.*","designation.*","departments.*","departments.id as deptid")
                            ->where("employees.empid",$empid)
                            ->first();
        return $empdetails;
    }
    
    public function getRAPDetails($empid){
        $fromMonth =date("Y-m",strtotime(date("Y-03-01")));
        $toMonth = date("Y-m",strtotime($fromMonth." +1 year -1 month"));

        // Initialize the array to hold the months
        $monthsArray = array();

        // Iterate through each month and add it to the array
        while ($fromMonth <= $toMonth) {
            $monthsArray[] = $fromMonth;
            $fromMonth = date("Y-m",strtotime($fromMonth." +1 month"));
        }

        $allData = array();

        //get DA for all months
        foreach($monthsArray as $month){
            $da = da::where("year","<=",$month)->where("status",0)->orderBy("year","DESC")->first();
            $allData[$month]["da"]=$da->da;
        }

        // get RAP for all months
        foreach($monthsArray as $month){
            $rap = rap::where("from","<=",$month)->where("status",0)->orderBy("from","DESC")->first();
            $allData[$month]["rap"]=$rap->rap_perc;
        }

        // basicpay of the employee
        foreach($monthsArray as $month){
            $payroll = payroll_final::where(["employee"=>$empid,"status"=>0])->where("month","<=",$month)->orderBy("month","DESC")->first();
            $allData[$month]["basic_pay"]=$payroll->basic_salary;
        }

        // add months into the array
        foreach($monthsArray as $month){
            $allData[$month]["month"]=$month;
        }
        // dd($allData);
        //create empty array to store Resdential accomadation data
        $RAarray = array();
        $index = 0;
        foreach ($allData as $key => $data) {
            if(count($RAarray)==0){
                array_push($RAarray,["from"=>$data["month"],"to"=>"","da"=>$data["da"],"rap"=>$data["rap"],"basic_pay"=>$data["basic_pay"]]);
                $index = key(array_slice($RAarray, -1, 1, true));
                continue;
            }

            if($data["da"]==$RAarray[$index]["da"] && $data["rap"]==$RAarray[$index]["rap"]){
                $RAarray[$index]["to"]=$data["month"];
            }else{
                array_push($RAarray,["from"=>$data["month"],"to"=>"","da"=>$data["da"],"rap"=>$data["rap"],"basic_pay"=>$data["basic_pay"]]);
                $index = key(array_slice($RAarray, -1, 1, true));
            }

        }

        // dd($RAarray);

        // $year = date("Y");
        // $rap = rap::select("*",\DB::raw('CONCAT(`year`, "-", `from`) as month'))->where(["year"=>$year,"status"=>0])->orderBy("id","ASC")->get();
        // foreach($rap as $month){
        //     if(payroll_final::where(["employee"=>$empid,"month"=>$month->month,"status"=>0])->exists()){
        //         $payDet = payroll_final::where(["employee"=>$empid,"month"=>$month->month,"status"=>0])->orderBy("id","DESC")->first();
        //         $rap->basicpay = $payDet->basic_salary;
        //         $rap->da = $payDet->da;
        //         $rap->da_perc = $payDet->da_perc;
        //     }else{
        //         $rap->basicpay = 0;
        //         $rap->da = 0;
        //         $rap->da_perc = 0;
        //     }
        // }
        return $RAarray;
    }
    public function getSalaryDetails($dept,$empid){
        $start = date("Y-01");
        $end = date("Y-12");
        $payrollDetails = payroll_final::where("employee", $empid)->where("status",0)->whereBetween("month",[$start,$end])->orderBy("month","DESC")->get();
        
        $total_months = count($payrollDetails);
        $months_pending = 12-$total_months;

        // gross_salary
        $total_salary = $payrollDetails->sum("gross_salary");
        $salaryDetails = $payrollDetails->first();
        $annual_gross = $total_salary + ($salaryDetails->gross_salary*$months_pending);
        $salaryDetails->annual_gross = $annual_gross;

        // hra
        $total_da = $payrollDetails->sum("da");
        $da_lastMonth = $payrollDetails->first()->da;
        $annual_da = $total_da + ($da_lastMonth*$months_pending);
        $salaryDetails->annual_da = $annual_da;

        // hra
        $total_hra = $payrollDetails->sum("hra");
        $hra_lastMonth = $payrollDetails->first()->hra;
        $annual_hra = $total_hra + ($hra_lastMonth*$months_pending);
        $salaryDetails->annual_hra = $annual_hra;
        return $salaryDetails;
    }
}

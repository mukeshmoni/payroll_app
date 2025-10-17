<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\departments;
use App\Models\designations;
use App\Models\Employees;
use App\Models\payroll;
use Illuminate\Support\Facades\Schema;
use App\Models\DeductionCategory;
use App\Models\AllowanceCategory;
use App\Models\loans_advances;
use App\Models\paylevels;
use App\Models\payroll_final;
use PDF;

class ReportsController extends Controller
{
    public function salary_aquitance(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $m_end = date("Y-m-t",strtotime(($month)));
            $category = $request->category;
            $data = payroll::join("designation","designation.id","=","payroll.designation")
                    ->join("employees","employees.empid","=","payroll.employee")
                    // ->select( \DB::raw("select SUM(payroll.basic_salary) as basic salary,SUM(payroll.gross_salary) as gross_salary,SUM(payroll.total_salary) as total_salary, SUM(payroll.net_salary) as net_salary"),"employees.empname","designation.designation as desg")
                    ->select( "payroll.*","employees.empname","employees.payscallvl","designation.designation as desg")
                    ->where("employees.prnop","nop")
                    ->where("payroll.emp_category",$category)
                    ->where("payroll.month",$month)
                    ->where("employees.empdor",">=",$m_end)
                    ->orderBy("employees.order", "ASC")
                    ->get();
            
            $b = 0;
            $g = 0;
            $t = 0;
            $n = 0;
            foreach($data as $payroll){
                $b = $b+$payroll->basic_salary;
                $g = $g+$payroll->gross_salary;
                $t = $t+$payroll->total_salary;
                $n = $n+$payroll->net_salary;
            }
            $sums = [
                "basic_salary"=>$b,
                "gross_salary"=>$g,
                "total_salary"=>$t,
                "net_salary"=>$n,
            ];
        }else{
            $month = date("Y-m");
            $data = [];
            $category="teaching";
            $sums = [];
        }
        
        return view("reports.salary_aquitance",["month"=>$month,"payrolls"=>$data,"category"=>$category,"sums"=>$sums]);
    }

    public function export_salary_aquitance(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $m_end = date("Y-m-t",strtotime(($month)));
            $category = $request->category;
            $data = payroll::join("designation","designation.id","=","payroll.designation")
                    ->join("employees","employees.empid","=","payroll.employee")
                    // ->select( \DB::raw("select SUM(payroll.basic_salary) as basic salary,SUM(payroll.gross_salary) as gross_salary,SUM(payroll.total_salary) as total_salary, SUM(payroll.net_salary) as net_salary"),"employees.empname","designation.designation as desg")
                    ->select( "payroll.*","employees.empname","employees.payscallvl","designation.designation as desg")
                    ->where("employees.prnop","nop")
                    ->where("payroll.emp_category",$category)
                    ->where("month",$month)
                    ->where("employees.empdor",">=",$m_end)
                    ->orderBy("employees.order", "ASC")
                    ->get();
            
            $b = 0;
            $d = 0;
            $g = 0;
            $t = 0;
            $n = 0;
            foreach($data as $payroll){
                $b = $b+$payroll->basic_salary;
                $d = $d+$payroll->da;
                $g = $g+$payroll->gross_salary;
                $t = $t+$payroll->total_salary;
                $n = $n+$payroll->net_salary;
            }
            $sums = [
                "basic_salary"=>$b,
                "da"=>$d,
                "gross_salary"=>$g,
                "total_salary"=>$t,
                "net_salary"=>$n,
            ];
            $pdf = PDF::loadView('reports.view_salary_aquitance',["month"=>$month,"payrolls"=>$data,"category"=>$category,"sums"=>$sums]);
            return $pdf->stream('Salary Aquitance.pdf');
        }else{
            return back()->with(["status"=>false,"message"=>"Select month and staff category"]);
        }
    }

    public function payledger(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $from_month = $this->getLastApril($month);
            $empid = $request->employee;
            $ledger = payroll::leftJoin("loans_advances","payroll.employee","=","loans_advances.empid")
                            ->select( "payroll.*","loans_advances.*")
                            ->where("payroll.employee",$empid)
                            ->whereBetween("payroll.month",[$from_month,$month])
                            ->orderBy("payroll.month","ASC")
                            ->get();
            $emp = Employees::leftJoin("designation","designation.id","=","employees.designation")
                            ->leftJoin("departments","departments.id","=","employees.department")
                            ->select("employees.*","designation.designation as designation_name","departments.department as department_name")
                            ->where(["employees.status"=>0,"employees.empid"=>$empid,"employees.prnop"=>"nop"])
                            ->first();
             $totals = payroll::join("employees","employees.empid","=","payroll.employee")
                            ->selectRaw("SUM(basic_salary) as basic_salary")
                            ->selectRaw("SUM(da) as da")
                            ->selectRaw("SUM(hra) as hra")
                            ->selectRaw("SUM(transport) as transport")
                            ->selectRaw("SUM(misc) as misc")
                            ->selectRaw("SUM(pf) as pf")
                            ->selectRaw("SUM(npse) as npse")
                            ->selectRaw("SUM(nps_da_arrear) as nps_da_arrear")
                            ->selectRaw("SUM(it) as it")
                            ->selectRaw("SUM(net_salary) as net_salary")
                            ->selectRaw("SUM(gross_salary) as gross_salary")
                            ->selectRaw("SUM(total_salary) as total_salary")
                            ->where("employees.status","0")
                            ->where("payroll.employee",$empid)
                            ->whereBetween("payroll.month",[$from_month,$month])
                            ->first();
        }else{
            $month = date("Y-m");
            $ledger = [];
            $totals = [];
            $empid =null;
            $emp = false;
        }
       

            // $all_ded = payroll::join("employees","employees.empid","=","payroll.employee")
            //                 ->select("payroll.allowances","payroll.deductions","payroll.la","payroll.da_arrear")
            //                 ->where("employees.prnop","nop")
            //                 ->where("employees.status","0")
            //                 ->where("payroll.emp_category",$category)
            //                 ->where("payroll.month",$month)
            //                 ->get();
        $deductions = DeductionCategory::where('status',0)->pluck("deduction_name")->toArray();
        $allowances = AllowanceCategory::where('status',0)->pluck("allowance_name")->toArray();
        $columns = array_merge(["month & year","basic_salary","da","hra","transport","misc"],$allowances,["gross_salary"],['pf','nps','it'],$deductions,["total deduction","net_salary"]);
        $employees = Employees::where("status",0)->get();

        foreach($ledger as $led){
            $allowanceIds = array_keys(json_decode($led->allowances, true));
            $allowances_id = AllowanceCategory::whereIn('id', $allowanceIds)->get();
            $formattedAllowances = [];
            foreach ($allowances_id as $allowance) {
                $formattedAllowances[$allowance->allowance_name] = json_decode($led->allowances, true)[$allowance->id];
            }
    
            $deductionIds = array_keys(json_decode($led->deductions, true));
            $deductions_id = DeductionCategory::whereIn('id', $deductionIds)->get();
            $formattedDeductions = [];
            foreach ($deductions_id as $deduction) {
                $formattedDeductions[$deduction->deduction_name] = json_decode($led->deductions, true)[$deduction->id];
            }

            $laIds = array_keys(json_decode($led->la, true));
            $las = loans_advances::whereIn('id', $laIds)->get();
            $formattedLAS = [];
            foreach ($las as $la) {
                $formattedLAS[$la->da_types] = json_decode($led->la, true)[$la->id];
            }
            $all_ded_la = array_merge($formattedAllowances,$formattedDeductions,$formattedLAS);
            $led->all_ded_la = $all_ded_la; 
        }
        // dd($deductions,$ledger);
        return view("reports.payledger.payledger",["month"=>$month,"employees"=>$employees,"empid"=>$empid,"columns"=>$columns,"ledger"=>$ledger,"empDet"=>$emp,"allowances"=>$allowances,"deductions"=>$deductions,"totals"=>$totals]);
    }


    public function export_ledger(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $from_month = $this->getLastApril($month);
            $empid = $request->id;
            $ledger = payroll::leftJoin("loans_advances","payroll.employee","=","loans_advances.empid")
                            ->select( "payroll.*","loans_advances.*")
                            ->where("payroll.employee",$empid)
                            ->whereBetween("payroll.month",[$from_month,$month])
                            ->orderBy("payroll.month","ASC")
                            ->get();
            $emp = Employees::leftJoin("designation","designation.id","=","employees.designation")
                            ->leftJoin("departments","departments.id","=","employees.department")
                            ->select("employees.*","designation.designation as designation_name","departments.department as department_name")
                            ->where(["employees.status"=>0,"employees.empid"=>$empid,"employees.prnop"=>"nop"])
                            ->first();
             $totals = payroll::join("employees","employees.empid","=","payroll.employee")
                            ->selectRaw("SUM(basic_salary) as basic_salary")
                            ->selectRaw("SUM(da) as da")
                            ->selectRaw("SUM(hra) as hra")
                            ->selectRaw("SUM(transport) as transport")
                            ->selectRaw("SUM(misc) as misc")
                            ->selectRaw("SUM(pf) as pf")
                            ->selectRaw("SUM(npse) as npse")
                            ->selectRaw("SUM(nps_da_arrear) as nps_da_arrear")
                            ->selectRaw("SUM(it) as it")
                            ->selectRaw("SUM(net_salary) as net_salary")
                            ->selectRaw("SUM(gross_salary) as gross_salary")
                            ->selectRaw("SUM(total_salary) as total_salary")
                            ->where("employees.status","0")
                            ->where("payroll.employee",$empid)
                            ->whereBetween("payroll.month",[$from_month,$month])
                            ->first();
        }else{
            $month = date("Y-m");
            $ledger = [];
            $totals = [];
            $empid =null;
            $emp = false;
        }
       

            // $all_ded = payroll::join("employees","employees.empid","=","payroll.employee")
            //                 ->select("payroll.allowances","payroll.deductions","payroll.la","payroll.da_arrear")
            //                 ->where("employees.prnop","nop")
            //                 ->where("employees.status","0")
            //                 ->where("payroll.emp_category",$category)
            //                 ->where("payroll.month",$month)
            //                 ->get();
        $deductions = DeductionCategory::where('status',0)->pluck("deduction_name")->toArray();
        $allowances = AllowanceCategory::where('status',0)->pluck("allowance_name")->toArray();
        $columns = array_merge(["month & year","basic_salary","da","hra","transport","misc"],$allowances,["gross_salary"],['pf','nps','it'],$deductions,["total deduction","net_salary"]);
        $employees = Employees::where("status",0)->get();

        foreach($ledger as $led){
            $allowanceIds = array_keys(json_decode($led->allowances, true));
            $allowances_id = AllowanceCategory::whereIn('id', $allowanceIds)->get();
            $formattedAllowances = [];
            foreach ($allowances_id as $allowance) {
                $formattedAllowances[$allowance->allowance_name] = json_decode($led->allowances, true)[$allowance->id];
            }
    
            $deductionIds = array_keys(json_decode($led->deductions, true));
            $deductions_id = DeductionCategory::whereIn('id', $deductionIds)->get();
            $formattedDeductions = [];
            foreach ($deductions_id as $deduction) {
                $formattedDeductions[$deduction->deduction_name] = json_decode($led->deductions, true)[$deduction->id];
            }

            $laIds = array_keys(json_decode($led->la, true));
            $las = loans_advances::whereIn('id', $laIds)->get();
            $formattedLAS = [];
            foreach ($las as $la) {
                $formattedLAS[$la->da_types] = json_decode($led->la, true)[$la->id];
            }
            $all_ded_la = array_merge($formattedAllowances,$formattedDeductions,$formattedLAS);
            $led->all_ded_la = $all_ded_la; 
        }
        // dd($deductions,$ledger);
        $pdf = PDF::loadView('reports.payledger.export_ledger',[
            "month"=>$month,
            "employees"=>$employees,
            "empid"=>$empid,
            "columns"=>$columns,
            "ledger"=>$ledger,
            "empDet"=>$emp,
            "allowances"=>$allowances,
            "deductions"=>$deductions,
            "totals"=>$totals
        ]);
        return $pdf->stream('Pay Ledger.pdf');
    }

    public function export_consolidatedledger(Request $request){
        if($request->pay_month){
            $month = date("Y-m",strtotime($request->pay_month));
            $from_month = $this->getLastApril($month);
            $category = $request->category;
            $ledger = payroll::leftJoin("loans_advances","payroll.employee","=","loans_advances.empid")
                            ->leftJoin("employees","employees.empid","=","payroll.employee")
                            ->select( "payroll.*","loans_advances.*")
                            ->where("payroll.emp_category",$category)
                            ->whereBetween("payroll.month",[$from_month,$month])
                            ->where("payroll.pensioner","0")
                            ->orderBy("payroll.month","ASC")
                            ->orderBy("employees.order","ASC")
                            ->get();
            $ledger = $ledger->groupBy('employee');
            foreach($ledger as $led){
                $empid = $led->first()->employee;
                $emp = Employees::leftJoin("designation","designation.id","=","employees.designation")
                                ->leftJoin("departments","departments.id","=","employees.department")
                                ->select("employees.*","designation.designation as designation_name","departments.department as department_name")
                                ->where(["employees.status"=>0,"employees.empid"=>$empid,"employees.prnop"=>"nop"])
                                ->first();
                 $totals = payroll::join("employees","employees.empid","=","payroll.employee")
                                ->selectRaw("SUM(basic_salary) as basic_salary")
                                ->selectRaw("SUM(da) as da")
                                ->selectRaw("SUM(hra) as hra")
                                ->selectRaw("SUM(transport) as transport")
                                ->selectRaw("SUM(misc) as misc")
                                ->selectRaw("SUM(pf) as pf")
                                ->selectRaw("SUM(npse) as npse")
                                ->selectRaw("SUM(nps_da_arrear) as nps_da_arrear")
                                ->selectRaw("SUM(it) as it")
                                ->selectRaw("SUM(net_salary) as net_salary")
                                ->selectRaw("SUM(gross_salary) as gross_salary")
                                ->selectRaw("SUM(total_salary) as total_salary")
                                ->where("employees.status","0")
                                ->where("payroll.employee",$empid)
                                ->whereBetween("payroll.month",[$from_month,$month])
                                ->first();
                $led->emp = $emp;
                $led->totals = $totals;
            }
        }else{
            $month = date("Y-m");
            $ledger = [];
            $totals = [];
            $empid =null;
            $emp = false;
        }
       

            // $all_ded = payroll::join("employees","employees.empid","=","payroll.employee")
            //                 ->select("payroll.allowances","payroll.deductions","payroll.la","payroll.da_arrear")
            //                 ->where("employees.prnop","nop")
            //                 ->where("employees.status","0")
            //                 ->where("payroll.emp_category",$category)
            //                 ->where("payroll.month",$month)
            //                 ->get();
        $deductions = DeductionCategory::where('status',0)->pluck("deduction_name")->toArray();
        $allowances = AllowanceCategory::where('status',0)->pluck("allowance_name")->toArray();
        $columns = array_merge(["month & year","basic_salary","da","hra","transport","misc"],$allowances,["gross_salary"],['pf','nps','it'],$deductions,["total deduction","net_salary"]);
        $employees = Employees::where("status",0)->get();

        foreach($ledger as $ledg){
            foreach ($ledg as $led) {
                $allowanceIds = array_keys(json_decode($led->allowances, true));
                $allowances_id = AllowanceCategory::whereIn('id', $allowanceIds)->get();
                $formattedAllowances = [];
                foreach ($allowances_id as $allowance) {
                    $formattedAllowances[$allowance->allowance_name] = json_decode($led->allowances, true)[$allowance->id];
                }
        
                $deductionIds = array_keys(json_decode($led->deductions, true));
                $deductions_id = DeductionCategory::whereIn('id', $deductionIds)->get();
                $formattedDeductions = [];
                foreach ($deductions_id as $deduction) {
                    $formattedDeductions[$deduction->deduction_name] = json_decode($led->deductions, true)[$deduction->id];
                }
    
                $laIds = array_keys(json_decode($led->la, true));
                $las = loans_advances::whereIn('id', $laIds)->get();
                $formattedLAS = [];
                foreach ($las as $la) {
                    $formattedLAS[$la->da_types] = json_decode($led->la, true)[$la->id];
                }
                $all_ded_la = array_merge($formattedAllowances,$formattedDeductions,$formattedLAS);
                $led->all_ded_la = $all_ded_la; 
            }
        }

        $pdf = PDF::loadView('reports.payledger.export_consolidated_ledger',[
            "month"=>$month,
            "employees"=>$employees,
            "columns"=>$columns,
            "ledger"=>$ledger,
            "allowances"=>$allowances,
            "deductions"=>$deductions,
        ]);
        return $pdf->stream('Consolidated Pay Ledger.pdf');
    }

    public function getLastApril($month){
        $today = $month;
        $current_year_FY = date("Y-03");
        if($current_year_FY>$today){
            $lastApril = date("Y-m",strtotime($current_year_FY." -1 year"));
        }else{
            $lastApril = $current_year_FY;
        }

        return $lastApril;

    }

    public function da_arrears(){
        return view('reports.da.da_arrears');
    }

    public function get_da_arrear_report(Request $request){
        $year = $request->year;
        $month = $request->month;
        $m_end = date("Y-m-t",strtotime(($month)));
        if($month==1){
            $month = date("Y-m",strtotime($year."-04"));
            $mon = "JANUARY ".date("Y",strtotime($month))." TO MARCH ".date("Y",strtotime($month));
        }else{
            $month = date("Y-m",strtotime($year."-10"));
            $mon = "JULY ".date("Y",strtotime($month))." TO SEPTEMBER ".date("Y",strtotime($month));
        }

        $category = $request->category;
        $data = payroll::join("designation","designation.id","=","payroll.designation")
                    ->join("employees","employees.empid","=","payroll.employee")
                    ->select( "payroll.*","employees.empname","employees.payscallvl","designation.designation as desg")
                    ->whereNotNull("payroll.prev_da")
                    ->where("employees.prnop","nop")
                    ->where("payroll.emp_category",$category)
                    ->where("payroll.month",$month)
                    ->where("employees.empdor",">=",$m_end)
                    ->orderBy("employees.order", "ASC")
                    ->get();
        foreach($data as $emp){
            if($emp->transport!=0){
                if($emp->slab==0){
                    $slab = paylevels::where("id",$emp->payscallvl)->first()->slab;
                }else{
                    $slab = $emp->slab;
                }
            }else{
                $slab = 0;
            }

            $emp->slab = $slab;

            if($emp->total_tda==0){
                $tda_due = round($slab*($emp->da_perc/100));
                $tda_drawn = round($slab*($emp->prev_da/100));
                $tda_arrear = round($tda_due-$tda_drawn);
    
                $emp->tda_due = $tda_due;
                $emp->tda_drawn = $tda_drawn;
                $emp->tda_arrear = $tda_arrear;
            }
        }

        // dd($data);
                    
        if($request->report=="checklist"){
            $pdf = PDF::loadView('reports.da.view_checklist',["data"=>$data,"month"=>$mon,"category"=>$category]);
            return $pdf->stream('DA Arrear Checklist.pdf');
        }elseif($request->report=="aquittance"){
            $pdf = PDF::loadView('reports.da.view_aquittance',["data"=>$data,"month"=>$mon,"category"=>$category]);
            return $pdf->stream('DA Arrear Aquittance.pdf');
        }elseif($request->report=="certificate"){
            $pdf = PDF::loadView('reports.da.view_certificate',["data"=>$data,"month"=>$mon,"category"=>$category]);
            return $pdf->stream('DA Arrear Certificate.pdf');
        }
       
    }
    

    public function salary_certificate(){
        return view('reports.salary.certificate');
    }

    public function get_salary_certificate(Request $request){

        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            // dd($month);
            $category = $request->category;
            $ledger = payroll::join("employees","employees.empid","=","payroll.employee")
                            ->selectRaw("SUM(basic_salary) as basic_salary")
                            ->selectRaw("SUM(da) as da")
                            ->selectRaw("SUM(hra) as hra")
                            ->selectRaw("SUM(transport) as transport")
                            ->selectRaw("SUM(misc) as misc")
                            ->selectRaw("SUM(pf) as pf")
                            ->selectRaw("SUM(npse) as npse")
                            ->selectRaw("SUM(nps_da_arrear) as nps_da_arrear")
                            ->selectRaw("SUM(it) as it")
                            ->selectRaw("SUM(net_salary) as net_salary")
                            ->selectRaw("SUM(gross_salary) as gross_salary")
                            ->selectRaw("SUM(total_salary) as total_salary")
                            ->where("employees.prnop","nop")
                            ->where("employees.status","0")
                            ->where("payroll.emp_category",$category)
                            ->where("payroll.month",$month)
                            ->first();

            $all_ded = payroll::join("employees","employees.empid","=","payroll.employee")
                            ->select("payroll.allowances","payroll.deductions","payroll.la","payroll.da_arrear")
                            ->where("employees.prnop","nop")
                            ->where("employees.status","0")
                            ->where("payroll.emp_category",$category)
                            ->where("payroll.month",$month)
                            ->get();
            // dd($all_ded);
            // $employees = Employees::where("status",0)->get();

            $tot_allowance = [];
            $tot_deduction = [];
            $tot_la = [];
        
            foreach($all_ded as $ad){
                $temp_alwnc = json_decode($ad->allowances);
                foreach ($temp_alwnc as $key => $value) {
                    if (array_key_exists($key, $tot_allowance)) {
                    //    array_push($tot_allowance[$key],$value);
                        $tot_allowance[$key] = $tot_allowance[$key]+$value;
                    } else {
                        $tot_allowance[$key]=$value+0;
                    }
                }
                $temp_ded = json_decode($ad->deductions);
                foreach ($temp_ded as $key => $value) {
                    if (array_key_exists($key, $tot_deduction)) {
                    //    array_push($tot_deduction[$key],$value);
                        $tot_deduction[$key] = $tot_deduction[$key]+$value;
                    } else {
                        $tot_deduction[$key]=$value+0;
                    }
                }

                $temp_la = json_decode($ad->la);
                foreach ($temp_la as $key => $value) {
                    if (array_key_exists($key, $tot_la)) {
                    //    array_push($tot_la[$key],$value);
                        $tot_la[$key] = $tot_la[$key]+$value;
                    } else {
                        $tot_la[$key]=$value+0;
                    }
                }
            }
            // dd($tot_la);
            // foreach($all_ded as $led){
                $allowanceIds = array_keys($tot_allowance);
                $allowances_id = AllowanceCategory::whereIn('id', $allowanceIds)->get();
                $formattedAllowances = [];
                foreach ($allowances_id as $allowance) {
                    $formattedAllowances[$allowance->allowance_name] = $tot_allowance[$allowance->id];
                }
        
                $deductionIds = array_keys($tot_deduction);
                $deductions_id = DeductionCategory::whereIn('id', $deductionIds)->get();
                $formattedDeductions = [];
                foreach ($deductions_id as $deduction) {
                    $formattedDeductions[$deduction->deduction_name] = $tot_deduction[$deduction->id];
                }

                $laIds = array_keys($tot_la);
                $las = loans_advances::whereIn('id', $laIds)->get();
                $formattedLAS = [];
                foreach ($las as $la) {
                    if(array_key_exists($la->da_types,$formattedLAS)){
                        $formattedLAS[$la->da_types] = $formattedLAS[$la->da_types]+$tot_la[$la->id];
                    }else{
                        $formattedLAS[$la->da_types] = $tot_la[$la->id];
                    }
                }
                $formattedDeductions = array_merge($formattedDeductions,$formattedLAS);
                // dd($formattedDeductions);
            // }
            $deductions = DeductionCategory::where('status',0)->pluck("deduction_name")->toArray();
            $allowances = AllowanceCategory::where('status',0)->pluck("allowance_name")->toArray();
            // dd($formattedDeductions,$deductions);
            $pdf = PDF::loadView('reports.salary.view_certificate',[
                "month"=>$month,
                "category"=>$category,
                "ledger"=>$ledger,
                "deductions_list"=>$deductions,
                "allowances_list"=>$allowances,
                "allowances"=>$formattedAllowances,
                "deductions"=>$formattedDeductions,
            ]);
            return $pdf->stream('Salary Certificate.pdf');
        }

    }

    public function nps(){
        return view("reports.nps.nps");
    }
    public function nps_report(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $m_end = date("Y-m-t",strtotime(($month)));
            $category = $request->category;
            $employees = payroll::join("designation","designation.id","=","payroll.designation")
                            ->join("employees","employees.empid","=","payroll.employee")
                            ->select( "payroll.*","employees.empname","employees.payscallvl","designation.designation as desg")
                            ->where("employees.prnop","nop")
                            ->where("employees.pf_nps_cat","nps")
                            ->where("payroll.emp_category",$category)
                            ->where("month",$month)
                            ->where("employees.empdor",">=",$m_end)
                            ->orderBy("employees.order", "ASC")
                            ->get();
            // $employees = payroll::join("employees","employees.empid","=","payroll.employee")
            //                 ->select("payroll.*")
            //                 ->where("employees.prnop","nop")
            //                 ->where("employees.pf_nps_cat","nps")
            //                 ->where("employees.status","0")
            //                 ->where("payroll.emp_category",$category)
            //                 ->where("payroll.month",$month)
            //                 ->get();
            // $employees = Employees::where("status",0)->get();

            // dd($formattedDeductions,$deductions);
            foreach($employees as $emp){
                if($emp->npse==0){
                    $emp->npser = 0;
                }
            }
            
            $pdf = PDF::loadView('reports.nps.nps_report',[
                "month"=>$month,
                "category"=>$category,
                "employees"=>$employees,
            ]);
            return $pdf->stream('NPS Report.pdf');
        }
    }

    public function society(){
        // $la = loans_advances::leftJoin("employees as emp1","emp1.empid","=","loans_advances.empid")
        //                 ->leftJoin("employees as emp2","emp2.empid","=","loans_advances.surety")
        //                 ->select("loans_advances.*","emp1.empname as empname","emp2.empname as surety_name")
        //                 ->where("loans_advances.status","0")
        //                 ->where("loans_advances.da_types","like","%society%")
        //                 ->get();
        $employees = employees::where("status",0)->get();
        return view('reports.society.society',["employees"=>$employees]);
    }

    public function get_society_certificate(Request $request){
        $empid = $request->empid;
        $surety = $request->surety;
        // dd($empid,$surety);
        if($empid && $surety){
            // $la = loans_advances::leftJoin("employees as emp1","emp1.empid","=","loans_advances.empid")
            //                 ->leftJoin("employees as emp2","emp2.empid","=","loans_advances.surety")
            //                 ->select("loans_advances.*","emp1.empname as empname","emp2.empname as surety_name")
            //                 ->where("loans_advances.status","0")
            //                 ->where("loans_advances.id",$id)
            //                 ->first();
            $emp_payslip = payroll::leftJoin("employees","employees.empid","=","payroll.employee")
                            ->leftJoin("departments","departments.id","=","payroll.department")
                            ->leftJoin("designation","designation.id","=","payroll.designation")
                            ->select("payroll.*","departments.department as dept","designation.designation as desg","employees.empname")
                            ->where(["payroll.employee"=>$empid])
                            ->orderBy("payroll.month","DESC")
                            ->first();
            $surety_payslip = payroll::leftJoin("employees","employees.empid","=","payroll.employee")
                            ->leftJoin("departments","departments.id","=","payroll.department")
                            ->leftJoin("designation","designation.id","=","payroll.designation")
                            ->select("payroll.*","departments.department as dept","designation.designation as desg","employees.empname")
                            ->where(["payroll.employee"=>$surety])
                            ->orderBy("payroll.month","DESC")
                            ->first();
            $allowances = AllowanceCategory::where('status', 0)->get();
            $deductions = DeductionCategory::where("status","0")->get();
            $la_emp = loans_advances::where("empid",$empid)->where("status",0)->get();
            $la_surety = loans_advances::where("empid",$surety)->where("status",0)->get();
            $pdf = PDF::loadView('reports.society.society_certificate',[
                "emp_payslip"=>$emp_payslip,
                "surety_payslip"=>$surety_payslip,
                "allowances"=>$allowances,
                "deductions"=>$deductions,
                "la_emp"=>$la_emp,
                "la_surety"=>$la_surety,
            ]);
            return $pdf->stream('Society Certificate.pdf');
        }else{
            return back()->with(["status"=>false,"message"=>"Couldn't load your request! Try Again"]);
        }

    }

    public function get_society_report(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $category = $request->category;
            $sum_recovery = 0;
            $sdet = DeductionCategory::where("deduction_name","LIKE","%society%")->where("status",0)->first();
            $sid = $sdet->id;
            $employees = payroll::join("designation","designation.id","=","payroll.designation")
                            ->join("employees","employees.empid","=","payroll.employee")
                            ->select( "payroll.*","employees.empname","designation.designation as desg_name")
                            ->where("payroll.emp_category",$category)
                            ->where("month",$month)
                            ->orderBy("employees.order", "ASC")
                            ->get()
                            ->map(function ($emp) use ($sid){
                                $ded = json_decode($emp->deductions,true);
                                if(array_key_exists($sid,$ded)){
                                    $emp->recovery = $ded[$sid];
                                    $emp->recovery_id = $sid;
                                    return $emp;
                                }else{
                                    return null;
                                }
                            });
            // foreach ($employees as $emp) {
                // $loans = loans_advances::join("employees","employees.empid","=","loans_advances.empid")
                //                         ->join("designation","designation.id","=","employees.designation")
                //                         ->where("loans_advances.da_types","like","%society%")
                //                         ->select("employees.empname","designation.designation as desg_name","loans_advances.*")
                //                         ->where("loans_advances.status",0)
                //                         ->where("employees.status",0)
                //                         ->where("employees.category",$category)
                //                         ->orderby("employees.order","ASC")
                //                         ->get();
                // foreach($loans as $loan){
                //     $sum_recovery+=$loan->amt;
                //     $start_month = new \DateTime(date("Y-m",strtotime($loan->startdt)));
                //     $end_month = new \DateTime($month);
                //     $interval = $start_month->diff($end_month);
                //     $interval = $interval->y * 12 + $interval->m;
                //     $tenure_duration = date("Y-m",strtotime($loan->startdt." +".$loan->tenure." months"));
                //     if($tenure_duration>=$month){
                //         $loan->installment = $interval+1;
                //         $loan->tenure = $loan->tenure;
                //         $loan->recovery = $loan->amt;
                //     }else{
                //         $loan->installment = 0;
                //         $loan->tenure = 0;
                //         $loan->recovery = 0;
                //     }
                // }
            $pdf = PDF::loadView('reports.society.society_report',[
                "month"=>$month,
                "category"=>$category,
                "loans"=>$employees,
                "sum_recovery"=>$employees->sum("recovery")
            ]);
            return $pdf->stream('Society Report.pdf');
        }
    }

    public function pf(){
        return view("reports.pf.pf");
    }
    public function pf_report(Request $request){
        if($request->month){
            $month = date("Y-m",strtotime($request->month));
            $m_end = date("Y-m-t",strtotime(($month)));
            $category = $request->category;
            $sum_recovery = 0;
            $employees = payroll::join("designation","designation.id","=","payroll.designation")
                            ->join("employees","employees.empid","=","payroll.employee")
                            ->select( "payroll.*","employees.empname","employees.gpfno","employees.payscallvl","designation.designation as desg")
                            ->where("employees.prnop","nop")
                            ->where("employees.pf_nps_cat","pf")
                            ->where("payroll.emp_category",$category)
                            ->where("month",$month)
                            ->where("employees.empdor",">=",$m_end)
                            ->orderBy("employees.order", "ASC")
                            ->get();
            foreach ($employees as $emp) {
                $pf_ded = loans_advances::where("empid",$emp->employee)
                                        ->where("da_types","like","%pf%")
                                        ->where("status",0)
                                        ->first();
                if($pf_ded){
                    $sum_recovery+=$pf_ded->amt;
                    $start_month = new \DateTime(date("Y-m",strtotime($pf_ded->startdt)));
                    $end_month = new \DateTime($month);
                    $interval = $start_month->diff($end_month);
                    $interval = $interval->y * 12 + $interval->m;
                    $tenure_duration = date("Y-m",strtotime($pf_ded->startdt." +".$pf_ded->tenure." months"));
                    if($tenure_duration>=$month){
                        $emp->installment = $interval+1;
                        $emp->tenure = $pf_ded->tenure;
                        $emp->recovery = $pf_ded->amt;
                    }else{
                        $emp->installment = 0;
                        $emp->tenure = 0;
                        $emp->recovery = 0;
                    }
                }else{
                    $emp->installment = 0;
                    $emp->recovery = 0;
                }
            }
            // $employees = payroll::join("employees","employees.empid","=","payroll.employee")
            //                 ->select("payroll.*")
            //                 ->where("employees.prnop","nop")
            //                 ->where("employees.pf_nps_cat","nps")
            //                 ->where("employees.status","0")
            //                 ->where("payroll.emp_category",$category)
            //                 ->where("payroll.month",$month)
            //                 ->get();
            // $employees = Employees::where("status",0)->get();

            // dd($formattedDeductions,$deductions);
            $pdf = PDF::loadView('reports.pf.pf_report',[
                "month"=>$month,
                "category"=>$category,
                "employees"=>$employees,
                "sum_recovery"=>$sum_recovery
            ]);
            return $pdf->stream('PF Report.pdf');
        }
    }

    public function income_tax(){
        return view("reports.income_tax.income_tax");
    }

    public function get_it_report(Request $request){
        $month = date("Y-m",strtotime($request->month));
        $m_end = date("Y-m-t",strtotime(($month)));
        $category = $request->category;

        $curr_march = date("Y-03");
        if($month<$curr_march){
            $from_m = date("Y-03",strtotime("-1 year"));
            $to_m = $month;
        }else{
            $from_m = $curr_march;
            $to_m = $month;
        }
        $employees = payroll::join("designation","designation.id","=","payroll.designation")
                        ->join("employees","employees.empid","=","payroll.employee")
                        ->select( "payroll.*","employees.empname","employees.emppanno","employees.payscallvl","designation.designation as desg")
                        ->where("employees.prnop","nop")
                        ->where("payroll.emp_category",$category)
                        ->where("month",$month)
                        ->where("employees.empdor",">=",$m_end)
                        ->orderBy("employees.order","ASC")
                        ->get();
        foreach($employees as $emp){
            $tot_it = payroll::where('employee',$emp->employee)
                            ->whereBetween("month",[$from_m,$to_m])
                            ->sum('it');
            $emp->tot_it = $tot_it;
        }

        $pdf = PDF::loadView('reports.income_tax.income_tax_report',[
            "month"=>$month,
            "category"=>$category,
            "employees"=>$employees,
        ]);
        return $pdf->stream('Income Tax Report.pdf');
         
    }

    public function get_it_report_quarterly(Request $request){
        $category = $request->category;
        $f_month = date("Y-m",strtotime($request->f_month));
        $t_month = date("Y-m",strtotime($request->t_month));

        $incomeTaxes = payroll_final::leftJoin("employees","employees.empid","=","payroll_final.employee")
                                    ->select(
                                        "payroll_final.month",
                                        "payroll_final.employee",
                                        "employees.empname",
                                        "employees.emppanno",
                                        "payroll_final.created_at as date_of_payment",
                                        "payroll_final.gross_salary",
                                        "payroll_final.net_salary",
                                        "payroll_final.it"
                                    )
                                    ->where("payroll_final.emp_category",$category)
                                    ->whereBetween("payroll_final.month",[$f_month,$t_month])
                                    // ->orderBy("employees.order","ASC")
                                    ->orderBy("payroll_final.month","ASC")
                                    ->get();
        $incomeTaxes = $incomeTaxes->groupBy("month");
        $pdf = PDF::loadView('reports.income_tax.income_tax_quarterly_report',[
            "incomeTaxes"=>$incomeTaxes,
            "category"=>$category,
            "f_month"=>$f_month,
            "t_month"=>$t_month,
        ]);
        return $pdf->stream('Income Tax Quarterly Report.pdf');
    }
}

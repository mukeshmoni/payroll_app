<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use App\Models\payroll;
use App\Models\payroll_final;

class DebuggerController extends Controller
{
    public function fix_total_salary(Request $request){
        // $empArray = ["11880"];
        $empid = $request->empid;
        $payrolls = payroll::where("month","2024-04")->where("employee",$empid)->get();
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

        dd("Payroll has been fixed");
    }

    public function add_pensioner_flag(){
        $employees = Employees::get();
        foreach($employees as $emp){
            if($emp->prnop == "pensioner"){
                $payroll = payroll::where("employee",$emp->empid)->update(["pensioner"=>1]);
                $payroll_final = payroll_final::where("employee",$emp->empid)->update(["pensioner"=>1]);
            }else{
                $payroll = payroll::where("employee",$emp->empid)->update(["pensioner"=>0]);
                $payroll_final = payroll_final::where("employee",$emp->empid)->update(["pensioner"=>0]);
            }
        }

        dd("Pensioner flag updated");
    }

    public function fix_tda_arrear(Request $request){
        // $empArray = ['20850','20160','11540','11530','11960','11250','11085','11440','11990','11400','11720','11750','11510','11740','11570','20140','11630','11710','11790','20860','11800','11760','20700','20750','20320'];
        
        $empArray = [$request->empid];
        foreach($empArray as $emp){
            $payroll = payroll::where("employee",$emp)->where("month","2024-04")->first();
            $employee = Employees::leftJoin('paylevels',"employees.payscallvl","=","paylevels.id")->where("employees.empid",$emp)->select("paylevels.paylevel","paylevels.slab","employees.pf_nps_cat")->first();

            $da_arrear = json_decode($payroll->da_arrear);

           
                if($payroll->transport!=0){
                    if($payroll->slab==0){
                        $slab = $employee->slab;
                    }else{
                        $slab = $payroll->slab;
                    }
                }else{
                    $slab = 0;
                }
           
            $tda_due = round($slab*($payroll->da_perc/100));
            $tda_drawn = round($slab*($payroll->prev_da/100));
            $tda_arrear = round($tda_due-$tda_drawn);

            $tda_due_arr = [];
            $tda_drawn_arr = [];
            $tda_arrear_arr = [];
            $nps_ded = 0;
            if($payroll->da_arrear){
                foreach($da_arrear as $da){
                    array_push($tda_due_arr,$tda_due);
                    array_push($tda_drawn_arr,$tda_drawn);
                    array_push($tda_arrear_arr,$tda_arrear);
                }
                $da_arrear_total = array_sum($da_arrear);
                if($employee->pf_nps_cat=="nps"){
                    $nps_ded = $da_arrear_total*0.10;
                }else{
                    $nps_ded = 0;
                }
                $tda_arrear_total = array_sum($tda_arrear_arr);
            }
            else{
                $da_arrear_total = 0;
                $tda_arrear_total = 0;
            }
            // dd($nps_ded);

            $pay = payroll::find($payroll->id);
            $pay->slab = $slab;
            $pay->nps_da_arrear = $nps_ded;
            $pay->net_salary = $pay->net_salary-$nps_ded;
            $pay->tda_due = $tda_due_arr;
            $pay->tda_drawn = $tda_drawn_arr;
            $pay->tda_arrear = $tda_arrear_arr;
            $pay->total_tda = $tda_arrear_total;
            $pay->total_salary = $pay->net_salary+$da_arrear_total+$tda_arrear_total;
            $pay->save();
            
        }

        $this->fix_payledger($request->empid);
        
        dd("TDA Arrear fixed for ".$request->empid);
    }

    public function fix_payledger($empid){
        // $empArray = ["11880"];
        // $empid = $request->empid;
        $payrolls = payroll::where("month","2024-04")->where("employee",$empid)->get();
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

        dd("Payroll has been fixed");
    }
}

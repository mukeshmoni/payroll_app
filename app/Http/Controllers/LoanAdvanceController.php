<?php

namespace App\Http\Controllers;

use App\Exports\LoanAdvanceList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\loans_advances;
use App\Models\employees;
use App\Models\DeductionCategory;
use App\Models\AllowanceCategory;
use Maatwebsite\Excel\Facades\Excel;

class LoanAdvanceController extends Controller
{
    //Index request - Plain blade
    public function index(){
        $month = date("Y-m");
        $las = loans_advances::leftJoin("employees","employees.empid","=","loans_advances.empid")
                        ->leftJoin("designation","designation.id","=","employees.designation")
                        ->select("loans_advances.*","employees.empname","designation.designation as desg_name")
                        ->where("loans_advances.status","0")
                        ->get();
        foreach($las as $loan){
            $start_month = new \DateTime(date("Y-m",strtotime($loan->startdt)));
            $end_month = new \DateTime($month);
            if($start_month<=$end_month){
                $interval = $start_month->diff($end_month);
                $interval = $interval->y * 12 + $interval->m;
                $tenure_duration = date("Y-m",strtotime($loan->startdt." +".($loan->tenure-1)." months"));
                if($tenure_duration>=$month){
                    if($loan->empid=="20890"){
                        dd($loan->startdt,$loan->tenure,$tenure_duration,$month);
                    }
                    $loan->installment = $interval+1;
                    $loan->tenure = $loan->tenure;
                    $loan->recovery = $loan->amt;
                }else{
                    $loan->installment = $loan->tenure;
                    $loan->tenure = $loan->tenure;
                    $loan->recovery = $loan->amt;
                }
            }else{
                $loan->installment = 0;
                $loan->tenure = $loan->tenure;
                $loan->recovery = $loan->amt;
            }
        }
        return view("loanadvance.loanadvance",["la"=>$las]);
    }

    //Create request - Add blade
    public function create(){
        $la = loans_advances::get()->where('status', '=', '0');      
        $employees = employees::get();
        $deduction = DeductionCategory::get();
        $allowance = AllowanceCategory::get();

        return view("loanadvance.add_loanadvance",array('la' => $la,'employees' => $employees,'deduction' => $deduction,'allowance' => $allowance));
    }

    //Store request
    public function store(Request $request){
        $validated = $request->validate([
            'empid' => 'required',
            'la' => 'required',          
            
        ],[
            'empid.required'=>"Employee Name Must",
            'la.required'=>"Choose Either Loan Advance",
         
        ]);

        if($validated){
        
            //store
            $la = new loans_advances;
            $la->empid = $request->empid;
            $la->surety = $request->surety;
            $la->loans_advances = $request->la;
            if($request->ded!="")
            $la->da_types = $request->ded;
            else
            $la->da_types = "advance";
            //$la->da_types = $request->alw;
            $la->amt = $request->amt;
            $la->startdt = $request->startdt;
            $la->tenure = $request->tenure;
            $la->totamt = $request->tamt;
            $la->adj_instal_no = $request->adj_instal_no;
            $la->adj_instal_amt = $request->adj_instal_amt;
            $la->remark = $request->remark;
            $la->status = "0";
            $la->la_extra1 = "";
            $la->la_extra2 = "";
            if($la->save()){
                return back()->with(["status"=>true,"message"=>"$request->la requests added successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't add your request! Try Again"]);
            }
        }
    }

    //Modify request
    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $la = loans_advances::where("id",$id)->first();
        $employees = employees::get();
        $deduction = DeductionCategory::get();
        $allowance = AllowanceCategory::get();
        if($la){
            //return view("attendance.modify_attendance",["attendance"=>$leaves]);
            return view("loanadvance.modify_loanadvance",array('la' => $la,'employees' => $employees,'deduction' => $deduction,'allowance' => $allowance));
        }else{
            return back()->with(["status"=>false,"message"=>"Couldn't load your request! Try Again"]);
        }
    }
    //Update request
    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $validated = $request->validate([
            'empid' => 'required',
            'la' => 'required',           
        ],[
            'empid.required'=>"Select Employee ID",
            'la.required'=>"Loan Advance is required",           
        ]);

        if($validated){
            $la = loans_advances::find($id);
            $la->empid = $request->empid;
            $la->surety = $request->surety;
            $la->loans_advances = $request->la;
            if($request->ded!="")
            $la->da_types = $request->ded;
            else
            $la->da_types = "advance";
            //$la->da_types = $request->alw;
            $la->amt = $request->amt;
            $la->startdt = $request->startdt;
            $la->tenure = $request->tenure;
            $la->totamt = $request->tamt;
            $la->adj_instal_no = $request->adj_instal_no;
            $la->adj_instal_amt = $request->adj_instal_amt;
            $la->remark = $request->remark;
            $la->status = "0";
            $la->la_extra1 = "";
            $la->la_extra2 = "";
            if($la->save()){
                return redirect()->route("loanadvance")->with(["status"=>true,"message"=>"$request->la Request Updated successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't update your request! Try Again"]);
            }
        }
    }

    //Deleted means update the status 0 => 1
    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $la = loans_advances::find($id);
        $la->status = "1";
        if($la->save()){
            return back()->with(["status"=>true,"message"=>"Deleted successfully!"]);
        }
        else{
            return back()->with(["status"=>false,"message"=>"Couldn't Delete your request! Try Again"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new LoanAdvanceList, 'LoanAdvanceList.xlsx');
    }

}

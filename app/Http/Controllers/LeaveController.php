<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeLeaveList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\attendance;
use App\Models\leaves;
use App\Models\employees;
use Maatwebsite\Excel\Facades\Excel;

class LeaveController extends Controller
{

    public function index(){
        $leaves = leaves::leftJoin("employees","employees.empid","=","leaves.empid")
                        ->select("leaves.*","employees.empname")
                        ->where("leaves.status","0")
                        ->get();
        return view("leave.leaves",["leaves"=>$leaves]);
    }

    public function create(){
        $leaves = leaves::get()->where('status', '=', '0');      

        $employees = employees::get();

        return view("leave.add_leaves",array('leaves' => $leaves,'employees' => $employees));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'empid' => 'required',
            'leavetype' => 'required',
            'startdt' => 'required|date',
            'enddt' => 'required|date|after_or_equal:startdt',
            //'remark' => 'required|regex:/[a-zA-Z0-9\s]+$/',
            //'days' => 'required',
        ],[
            'empid.required'=>"Select Employee ID",
            'leavetype.required'=>"Leave type is required",
            'startdt.required'=>"Invalid Start Date",
            'enddt.required'=>"Invalid End Date",
            'startdt.after_or_equal'=>"Invalid End Date",
            //'remark.required'=>"Enter Remark is invalid",
            //'days.required'=>"Enter Remark is invalid",
        ]);

        if($validated){

            //check user already took leave on same days
            $startdt = $request->startdt;
            $enddt = $request->enddt;
            $existingLeave = leaves::where('empid', $request->empid)
                                ->where(function ($query) use ($startdt, $enddt) {
                                    $query->whereBetween('startdt', [$startdt, $enddt])
                                        ->orWhereBetween('enddt', [$startdt, $enddt]);
                                })
                                ->where("status","0")
                                ->first();

            if ($existingLeave) {
                return back()->with(["status"=>false,"message"=>"Employee already has a leave during the specified date range!"]);
            }

            //store
            $leaves = new leaves;
            $leaves->empid = $request->empid;
            $leaves->leavetype = $request->leavetype;
            $leaves->startdt = $request->startdt;
            $leaves->enddt = $request->enddt;
            if($request->days!="")
            $leaves->days = $request->days;
            else
            $leaves->days = $request->mdays;
            $leaves->remark = $request->remark;
            $leaves->status = "0";
            $leaves->att_extra1 = "";
            $leaves->att_extra2 = "";
            if($leaves->save()){
                return back()->with(["status"=>true,"message"=>"Leave requests added successfully!"]);
            }
        }
    }


   /* public function emp(){
        $employees = employees::get();
        return view("attendance.add_attendance",["employees"=>$employees]);
    }*/

    //Modify
    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $leaves = leaves::where("id",$id)->first();
        $employees = employees::get();
        if($leaves){
            //return view("attendance.modify_attendance",["attendance"=>$leaves]);
            return view("leave.modify_leaves",array('leave' => $leaves,'employees' => $employees));
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $validated = $request->validate([
            'empid' => 'required',
            'leavetype' => 'required',
            'startdt' => 'required|date',
            'enddt' => 'required|date|after_or_equal:startdt',
            //'remark' => 'required|regex:/[a-zA-Z0-9\s]+$/',
            //'days' => 'required',
        ],[
            'empid.required'=>"Select Employee ID",
            'leavetype.required'=>"Leave type is required",
            'startdt.required'=>"Invalid Start Date",
            'enddt.required'=>"Invalid End Date",
            'startdt.after_or_equal'=>"Invalid End Date",
            //'remark.required'=>"Enter Remark is invalid",
            //'days.required'=>"Enter Remark is invalid",
        ]);

        if($validated){
            $leaves = leaves::find($id);
            $leaves->empid = $request->empid;
            $leaves->leavetype = $request->leavetype;
            $leaves->startdt = $request->startdt;
            $leaves->enddt = $request->enddt;
            if($request->days!="")
            $leaves->days = $request->days;
            else
            $leaves->days = $request->mdays;
        
            $leaves->remark = $request->remark;
            $leaves->status = "0";
            $leaves->att_extra1 = "";
            $leaves->att_extra2 = "";
            if($leaves->save()){
                return redirect()->route("leaves")->with(["status"=>true,"message"=>"Leave Request Updated successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't update leave request! Try Again"]);
            }
        }
    }

     //Deleted means update the status 0 => 1
     public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $leaves = leaves::find($id);
        $leaves->status = "1";
        if($leaves->save()){
            return back()->with(["status"=>true,"message"=>"Leave Request Deleted successfully!"]);
        }
        else{
            return back()->with(["status"=>false,"message"=>"Couldn't Delete leave request! Try Again"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new EmployeeLeaveList, 'EmployeeLeaveList.xlsx');
    }
}

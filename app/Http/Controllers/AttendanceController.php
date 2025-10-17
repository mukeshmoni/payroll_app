<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\attendance;
use App\Models\employees;

class AttendanceController extends Controller
{
    //
   

    public function create(){
        $attendance = attendance::get()->where('status', '=', '0');      
        //return view("attendance.add_attendance",["attendance"=>$attendance]);

        $employees = employees::get();
        //return view("attendance.add_attendance",["attendance"=>$employees]);

        return view("attendance.add_attendance",array('attendance' => $attendance,'employees' => $employees));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'empid' => 'required',
            'leavetype' => 'required',
            'startdt' => 'required|date',
            'enddt' => 'required|date',
            //'remark' => 'required|regex:/[a-zA-Z0-9\s]+$/',
            //'days' => 'required',
        ],[
            'empid.required'=>"Enter Remark is invalid",
            'leavetype.required'=>"Enter Remark is invalid",
            'startdt.required'=>"Enter Remark is invalid",
            'enddt.required'=>"Enter Remark is invalid",
            //'remark.required'=>"Enter Remark is invalid",
            //'days.required'=>"Enter Remark is invalid",
        ]);

        if($validated){
            //store
            $attendance = new attendance;
            $attendance->empid = $request->empid;
            $attendance->leavetype = $request->leavetype;
            $attendance->startdt = $request->startdt;
            $attendance->enddt = $request->enddt;
            if($request->days!="")
            $attendance->days = $request->days;
            else
            $attendance->days = $request->mdays;
            $attendance->remark = $request->remark;
            $attendance->status = "0";
            $attendance->att_extra1 = "";
            $attendance->att_extra2 = "";
            if($attendance->save()){
                return back()->with(["status"=>true,"message"=>"Attendance added successfully!"]);
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
        $attendance = attendance::where("id",$id)->first();
        $employees = employees::get();
        if($attendance){
            //return view("attendance.modify_attendance",["attendance"=>$attendance]);
            return view("attendance.modify_attendance",array('attendance' => $attendance,'employees' => $employees));
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $attendance = attendance::find($id);
        $attendance->empid = $request->empid;
        $attendance->leavetype = $request->leavetype;
        $attendance->startdt = $request->startdt;
        $attendance->enddt = $request->enddt;
        if($request->days!="")
        $attendance->days = $request->days;
        else
        $attendance->days = $request->mdays;
        $attendance->remark = $request->remark;
        $attendance->status = "0";
        $attendance->att_extra1 = "";
        $attendance->att_extra2 = "";
        if($attendance->save()){
            return redirect()->route("attendance")->with(["status"=>true,"message"=>"Updated successfully!"]);
        }
    }

     //Deleted means update the status 0 => 1
     public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $attendance = attendance::find($id);
        $attendance->status = "1";
        if($attendance->save()){
            return back()->with(["status"=>true,"message"=>"Selected Employee Attendance Deleted successfully!"]);
        }
    }

}

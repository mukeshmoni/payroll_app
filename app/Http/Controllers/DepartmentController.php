<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\departments;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    //
    public function index(){
        $departments = departments::get();
        return view("departments.departments",["departments"=>$departments]);
    }

    public function create(){
        $departments = departments::get()->where('status', '=', '0');
       // $departments = departments::where('status', '=', '0');
        return view("departments.add_departments",["departments"=>$departments]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'departments' => 'required',
        ]);

        if($validated){
            //store
            $departments = new departments;
            $departments->department = $request->departments;
            $departments->desg_department = $request->desg_description;
            $departments->status = "0";
            if($departments->save()){
                return back()->with(["status"=>true,"message"=>"Departments added successfully!"]);
            }
        }
    }

    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $departments = departments::where("id",$id)->first();
        if($departments){
            return view("departments.modify_departments",["departments"=>$departments]);
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $designation = departments::find($id);
        $designation->department = $request->departments;
        $designation->desg_department = $request->desg_description;
        if($designation->save()){
            return redirect()->route("departments")->with(["status"=>true,"message"=>"Departments Updated successfully!"]);
        }
    }
    //Deleted means update the status 0 => 1
    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $designation = departments::find($id);
        $designation->status = "1";
        if($designation->save()){
            return back()->with(["status"=>true,"message"=>"Departments Deleted successfully!"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new DepartmentList, 'DepartmentList.xlsx');
    }

}

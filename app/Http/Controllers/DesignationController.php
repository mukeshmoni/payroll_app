<?php

namespace App\Http\Controllers;

use App\Exports\DesignationList;
use Illuminate\Http\Request;
use App\Models\designations;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class DesignationController extends Controller
{
    public function index(){
        $designations = designations::get();
        return view("designations.designations",["designations"=>$designations]);
    }

    public function create(){
        $designations = designations::get()->where('status', '=', '0');
       // $designations = designations::where('status', '=', '0');
        return view("designations.add_designations",["designations"=>$designations]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'designation' => 'required',
        ]);

        if($validated){
            //store
            $designation = new designations;
            $designation->designation = $request->designation;
            $designation->desg_description = $request->desg_description;
            $designation->status = "0";
            if($designation->save()){
                return back()->with(["status"=>true,"message"=>"Designation added successfully!"]);
            }
        }
    }

    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $designation = designations::where("id",$id)->first();
        if($designation){
            return view("designations.modify_designations",["designation"=>$designation]);
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $designation = designations::find($id);
        $designation->designation = $request->designation;
        $designation->desg_description = $request->desg_description;
        if($designation->save()){
            return redirect()->route("designations")->with(["status"=>true,"message"=>"Designation Updated successfully!"]);
        }
    }
    //Deleted means update the status 0 => 1
    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $designation = designations::find($id);
        $designation->status = "1";
        if($designation->save()){
            return back()->with(["status"=>true,"message"=>"Designation Deleted successfully!"]);
        }
    }
    
    //Delete raw data
   /* public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $deldesig = designations::where("id",$id)->delete();
        if($deldesig){
            return back()->with(["status"=>true,"message"=>"Designation Deleted successfully!"]);
        }
    } */
    public function export(Request $request){
        return Excel::download(new DesignationList, 'DesignationList.xlsx');
    }
}

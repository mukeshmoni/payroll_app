<?php

namespace App\Http\Controllers;

use App\Exports\DAList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\da;
use App\Models\employees;
use Maatwebsite\Excel\Facades\Excel;

class DAController extends Controller
{   
     //Index request - Plain blade
     public function index(){
        $da = da::get()->where('status', '=', '0');
        return view("da.da",["da"=>$da]);
    }

     //Create request - Add blade
     public function create(){
        $da = da::get()->where('status', '=', '0');
        return view("da.add_da",array('da' => $da));
    }

     //Store request
     public function store(Request $request){
        $validated = $request->validate([
            'da' => 'required',
            'year' => 'required',          
            
        ],[
            'da.required'=>"DA Name Must",
            'year.required'=>"Year is must",
         
        ]);

        if($validated){

              //Given Year already exists or not to be verify
              $year = $request->year;             
              $existyear = da::where('year', $request->year)                                 
                                  ->where("status","0")
                                  ->first();
            $startMonth = date("Y-01",strtotime($year));
            $endMonth = date("Y-12",strtotime($year));
            // dd($startMonth,$endMonth);
            //   $mulyear = explode($year,'-');
            //   $multiyear = da::where('year', 'like', '%' .$mulyear[0]. '%')->where("status","0")
                                        // ->get()->count();
            $multiyear = da::where("year",">=",$startMonth)->where("year","<=",$endMonth)->where("status","0")->count();
            
            if ($existyear) {
                  return back()->with(["status"=>false,"message"=>"Given Year/Month already Exists!"]);
              }
              if ($multiyear >= 2) {
                return back()->with(["status"=>false,"message"=>"DA Lapse Exists! For this year! If you want to add delete exists!"]);
            }
        
            //store
            $da = new da;
            $da->da = $request->da;
            $da->year = $request->year; 
            $da->month = "";           
            $da->remark = $request->remark;
            $da->status = "0";
            $da->da_extra1 = "";
            $da->da_extra2 = "";
            if($da->save()){
                return back()->with(["status"=>true,"message"=>" DA Added Successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't add your request! Try Again"]);
            }
        }
    }  

     //Modify request
     public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $da = da::where("id",$id)->first();
        if($da){
            return view("da.modify_da",array('da' => $da));
        }else{
            return back()->with(["status"=>false,"message"=>"Couldn't load your request! Try Again"]);
        }
    }

    //Update request
    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $validated = $request->validate([
            'da' => 'required',
            'year' => 'required',        
        ],[
            'da.required'=>"DA Name Must",
            'year.required'=>"Year is must",     
        ]);

        if($validated){
            $da = da::find($id);
            $da->da = $request->da;
            $da->year = $request->year;            
            $da->month = "";          
            $da->remark = $request->remark;
            $da->status = "0";
            $da->da_extra1 = "";
            $da->da_extra2 = "";
            if($da->save()){
                return redirect()->route("da.add_da")->with(["status"=>true,"message"=>"Request Updated successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't update your request! Try Again"]);
            }
        }
    }
     //Deleted means update the status 0 => 1
     public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $da = da::find($id);
        $da->status = "1";
        if($da->save()){
            return back()->with(["da.add_da"=>true,"message"=>"Deleted successfully!"]);
        }
        else{
            return back()->with(["da"=>false,"message"=>"Couldn't Delete your request! Try Again"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new DAList, 'DAList.xlsx');
    }

}

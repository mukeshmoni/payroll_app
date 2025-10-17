<?php

namespace App\Http\Controllers;

use App\Exports\DAList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\rap;
use App\Models\employees;
use Maatwebsite\Excel\Facades\Excel;

class RAPController extends Controller
{   
     //Index request - Plain blade
     public function index(){
        $rap = rap::get()->where('status', '=', '0');
        return view("rap.rap",["rap"=>$rap]);
    }

     //Create request - Add blade
     public function create(){
        $rap = rap::get()->where('status', '=', '0');
        return view("rap.add_rap",array('rap' => $rap));
    }

     //Store request
     public function store(Request $request){
        //var_dump($request->rap);
        $validated = $request->validate([
            'rap' => 'required',
            'from' => 'required',          
            
        ],[
            'rap.required'=>"RAP Name Must",
            'from.required'=>"Month Year is must",
         
        ]);

        if($validated){
        $frm1 = explode('-', $request->from);
        $frm  = $frm1[1];
        $year = $frm1[0];  
        $to_m = $frm - 1;
        $to = $year.'-'.$to_m;
        //Getting last record for insert To Month
        $chkrow = rap::get();
        if (!$chkrow->isEmpty()) { 
            //dd("Row is not empty.");
            rap::latest('id')->first()->where("status","0")->where("to"," ")->update(['to'=>$to]);
        }
        
            //store
            $rap = new rap;
            $rap->rap_perc = $request->rap;
            $rap->from = $request->from; 
            $rap->to = "";       
            $rap->year = "";    
            $rap->remark = $request->remark;
            $rap->status = "0";
            $rap->rap_extra1 = "";
            $rap->rap_extra2 = "";
            if($rap->save()){
                return back()->with(["status"=>true,"message"=>" RAP Added Successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't add your request! Try Again"]);
            }
        }
    }  

     //Modify request
     public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        $rap = rap::where("id",$id)->first();
        if($rap){
            return view("rap.modify_rap",array('rap' => $rap));
        }else{
            return back()->with(["status"=>false,"message"=>"Couldn't load your request! Try Again"]);
        }
    }

    //Update request
    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $validated = $request->validate([
            'rap' => 'required',
            'from' => 'required',        
        ],[
            'rap.required'=>"RAP Name Must",
            'from.required'=>"Year is must",     
        ]);

        if($validated){
            $frm1 = explode('-', $request->from);
            $frm  = $frm1[1];
            $year = $frm1[0];  
            $to_m = $frm - 1;
            $to = $year.'-'.$to_m;
        //Getting Previous record for insert To Month
        $chkrow = rap::get();
        if (!$chkrow->isEmpty()) { 
          $previd =   rap::where('id', '<', $id)->where("status","0")->orderBy('id','desc')->pluck('id')->first();
          //dd($previd);
            rap::where("id",$previd)->update(['to'=>$to]);
        }
        $nextid =   rap::where('id', '>', $id)->where("status","0")->orderBy('id','desc')->pluck('id')->first();
        $nextfrm =   rap::where('id', '>', $id)->where("status","0")->orderBy('id','desc')->pluck('from')->first();
        //dd($nextid);
        $next_exp = explode('-',$nextfrm);
        if($nextid)
        $nextfr = $next_exp[1] - 1;
       // dd($nextfr);
            $rap = rap::find($id);
            $rap->rap_perc = $request->rap;
            $rap->from = $request->from;  
            if($nextid) {       
            $rap->to = $year."-".$nextfr;  
            } else{$rap->to = "";}    
            $rap->year = "";      
            $rap->remark = $request->remark;
            $rap->status = "0";
            $rap->rap_extra1 = "";
            $rap->rap_extra2 = "";
            if($rap->save()){
                return redirect()->route("rap.add_rap")->with(["status"=>true,"message"=>"Request Updated successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Couldn't update your request! Try Again"]);
            }
        }
    }
     //Deleted means update the status 0 => 1
     public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        $rap = rap::find($id);
        $rap->status = "1";
        if($rap->save()){
            return back()->with(["rap.add_rap"=>true,"message"=>"Deleted successfully!"]);
        }
        else{
            return back()->with(["rap.add_rap"=>false,"message"=>"Couldn't Delete your request! Try Again"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new DAList, 'DAList.xlsx');
    }

}

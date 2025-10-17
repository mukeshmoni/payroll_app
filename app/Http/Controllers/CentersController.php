<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\centers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CentersExport;

class CentersController extends Controller
{
   public function index(){
      $centers = centers::where("status",0)->orderBy("id","DESC")->get();
      return view("centers.centers",["centers"=>$centers]);
   }
   public function store(Request $request){
      $validated = $request->validate([
         'center' => 'required',
         'hra_perc' => 'required|numeric',
     ]);

     if($validated){
         //store
         $center = new centers;
         $center->centername = $request->center;
         $center->hra = $request->hra_perc;
         $center->created_by = Auth::user()->id;
         if($center->save()){
             return back()->with(["status"=>true,"message"=>"Center added successfully!"]);
         }
     }
   }

   public function modify(Request $request){
      $id = Crypt::decryptString($request->id);
      if(!centers::where("id",$id)->exists()){
         return back()->with(["status"=>false,"message"=>"Center details not found!"]);
      }
      $center = centers::where("id",$id)->first();
      $centers = centers::where("status",0)->orderBy("id","DESC")->get();
      if($center){
         return view("centers.modify_center",["center"=>$center,"centers"=>$centers]);
      }
   }

   public function update(Request $request){
      $id = Crypt::decryptString($request->id);
      $validated = $request->validate([
          'center' => 'required',
          'hra_perc' => 'required|numeric',
      ]);

      if($validated){

          if(!centers::where("id",$id)->exists()){
              return back()->with(["status"=>false,"message"=>"Center details not found!"]);
          }

          //store
          $center =centers::find($id);
          $center->centername = $request->center;
          $center->hra = $request->hra_perc;
          $center->created_by = Auth::user()->id;
          if($center->save()){
              return redirect()->route("centers")->with(["status"=>true,"message"=>"Center updated successfully!"]);
          }
      }
  }

  public function delete(Request $request){
      $id = Crypt::decryptString($request->id);
      if(!centers::where("id",$id)->exists()){
         return back()->with(["status"=>false,"message"=>"Center detail not found!"]);
      }

      //store
      $center =centers::find($id);
      $center->status = 1;
      $center->created_by = Auth::user()->id;
      if($center->save()){
         return redirect()->route("centers")->with(["status"=>true,"message"=>"Center deleted successfully!"]);
      }
   }

   public function export(Request $request){
         return Excel::download(new CentersExport, 'Centers.xlsx');
   }
}

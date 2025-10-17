<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\paylevels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaylevelsList;

class PaylevelsController extends Controller
{
    public function index(){
        $paylevels = paylevels::where("status",0)->orderBy("id","DESC")->get();
        return view("paylevels.paylevels",["paylevels"=>$paylevels]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'paylevel' => 'required',
            'slab' => 'required|numeric',
        ]);

        if($validated){
            //store
            $paylevel = new paylevels;
            $paylevel->paylevel = $request->paylevel;
            $paylevel->slab = $request->slab;
            $paylevel->created_by = Auth::user()->id;
            if($paylevel->save()){
                return back()->with(["status"=>true,"message"=>"Pay Level & Slab added successfully!"]);
            }
        }
    }

    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!paylevels::where("id",$id)->exists()){
            return back()->with(["status"=>false,"message"=>"Pay Level not found!"]);
        }
        $paylevel = paylevels::where("id",$id)->first();
        if($paylevel){
            return view("paylevels.modify_paylevel",["paylevel"=>$paylevel]);
        }
    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        $validated = $request->validate([
            'paylevel' => 'required',
            'slab' => 'required|numeric',
        ]);

        if($validated){

            if(!paylevels::where("id",$id)->exists()){
                return back()->with(["status"=>false,"message"=>"Pay Level not found!"]);
            }

            //store
            $paylevel =paylevels::find($id);
            $paylevel->paylevel = $request->paylevel;
            $paylevel->slab = $request->slab;
            $paylevel->created_by = Auth::user()->id;
            if($paylevel->save()){
                return redirect()->route("paylevels")->with(["status"=>true,"message"=>"Pay Level & Slab updated successfully!"]);
            }
        }
    }

    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!paylevels::where("id",$id)->exists()){
            return back()->with(["status"=>false,"message"=>"Pay Level not found!"]);
        }

        //store
        $paylevel =paylevels::find($id);
        $paylevel->status = 1;
        $paylevel->created_by = Auth::user()->id;
        if($paylevel->save()){
            return redirect()->route("paylevels")->with(["status"=>true,"message"=>"Pay Level & Slab deleted successfully!"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new PaylevelsList, 'PayLevels.xlsx');
    }
}
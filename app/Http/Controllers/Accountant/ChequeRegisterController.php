<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillRegister;
use App\Models\br_vr_assignment;
use App\Models\ChequeRegister;
use App\Models\Employees;
use App\Models\Sequence;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ChequeRegisterController extends Controller
{
    public function index(){
        $entries = ChequeRegister::where("status",0)->orderBy('id',"DESC")->get();
        $entries = $entries->groupBy("cheque_no");
        return view('accounts.cheque-register.register',["entries"=>$entries]);
    }

    public function create(){
        $employees=Employees::where("status",0)->orderBy("empname","ASC")->get();
        return view('accounts.cheque-register.register_entry',["employees"=>$employees]);
    }

    public function fetch(Request $request){
        $br_id = $request->br_id;
        $acc_no = $request->acc_no;
        if(!BillRegister::where('id',$br_id)->exists()){
            return response()->json(['status'=>false,'message'=>'Invalid Bill Register']);
        }
        $data = BillRegister::where('id',$br_id)->first();

        // for acc 180 and 130 the sequence will be same
        if($acc_no == "SBI-888" || $acc_no=="SBI-130"){
            $acc_no = "SBI-888,SBI-130";
        }
        
        if($data->vr_id==0){
            if(br_vr_assignment::where("br_no",$br_id)->exists()){
                $vr_id = br_vr_assignment::where("br_no",$br_id)->first();
                $data->vr_no = $vr_id->vr_no;
            }else{
                $last_vr = br_vr_assignment::where("bank_acc_no",$acc_no)->orderBy("id","DESC")->first();
                if($last_vr){
                    $data->vr_no = $last_vr->vr_no+1;

                    $assignment = new br_vr_assignment;
                    $assignment->br_no = $br_id;
                    $assignment->vr_no = $data->vr_no;
                    $assignment->bank_acc_no = $acc_no;
                    $assignment->sequence = $last_vr->sequence;
                    $assignment->save();
                }else{
                    //get sequence based on account
                    $sequence = Sequence::where('account',$acc_no)->first();

                    //add +1 to the current sequence
                    $vr_no = $sequence->value+1;

                    //add current vr and br to the assignment table
                    $assignment = new br_vr_assignment;
                    $assignment->br_no = $br_id;
                    $assignment->vr_no = $vr_no;
                    $assignment->bank_acc_no = $acc_no;
                    $assignment->sequence = $sequence->value;
                    $assignment->save();

                    $data->vr_no = $vr_no;
                }
            }
            
        }else{
            $data->vr_no = $data->vr_id;
        }
        return response()->json(['status'=>true,'data'=>$data]);
    }

    public function delete(Request $request){
        $br_id = $request->br_id;
        $vr_id = $request->vr_id;
        $acc_no = $request->acc_no;

        if(br_vr_assignment::where([
            'br_no'=>$br_id,
            'vr_no'=>$vr_id,
        ])->exists()){
            $vr_id = br_vr_assignment::where([
                'br_no'=>$br_id,
                'vr_no'=>$vr_id,
            ])->delete();
            if($vr_id){
                return response()->json(['status'=>true,'data'=>"Entry deleted successfully"]);
            }else{
                return response()->json(['status'=>false,'data'=>"Could not delete entry. Please try again"]);
            }
        }else{
            return response()->json(['status'=>false,'data'=>"Entry not found for this BR & VR"]);
        }
    }

    public function store(Request $request){
        $formData = $request->formData;
        try{
            DB::beginTransaction();

            // to update the cheque register delete previous entry and insert new one
            if(ChequeRegister::where("cheque_no",$request->cheque_no)->exists()){
                $del = ChequeRegister::where("cheque_no",$request->cheque_no)->delete();
            }

            foreach($formData as $data){
                if($data){
                    $deductions = $data["deductions"];
                    if(count($deductions)>0){
                        foreach($deductions as $deduction){
                            if($deduction){
                                $cheque_reg = new ChequeRegister;
                                $cheque_reg->cheque_no = $request->cheque_no;
                                $cheque_reg->bank_acc_no = $request->bank_acc_no;
                                $cheque_reg->payment_mode = $request->payment_mode;
                                $cheque_reg->date = $request->cheque_date;
                                $cheque_reg->br_no = $data["id"];
                                $cheque_reg->vr_no = $data["vr_no"];
                                $cheque_reg->particulars = $data["particulars"];
                                $cheque_reg->head_of_acc = $data["head_acc"];
                                $cheque_reg->empid = $deduction["employee"] ?? '';
                                $cheque_reg->deduction_type = $deduction["deduction_type"]??'';
                                $cheque_reg->deduction_perc = $deduction["deduction_perc"]??0;
                                $cheque_reg->cess_perc = $deduction["cess_perc"]??0;
                                $cheque_reg->gross_amount = $data["amount"];
                                $cheque_reg->amount_wo_deduction = $deduction["deduction_tot_amount"]??0;
                                $cheque_reg->deducted_amount = $deduction["deduction_deducted_amount"]??0;
                                $cheque_reg->net_amount = $deduction["deduction_net_amount"]??0;
                                $cheque_reg->total_amount = $request->cheque_amount;
                                if($cheque_reg->save()){
                                    $billRegister = BillRegister::find($data["id"]);
                                    $billRegister->vr_id = $data["vr_no"];
                                    if(!$billRegister->save()){
                                        DB::rollBack();
                                        return response()->json(['status'=>false,'data'=>"Error while saving data".$billRegister]);
                                    }
                                }else{
                                    DB::rollBack();
                                    return response()->json(['status'=>false,'data'=>"Error while saving data! Please check if all the fields are filled"]);
                                }
                            }
                        }
                    }else{
                        $cheque_reg = new ChequeRegister;
                        $cheque_reg->cheque_no = $request->cheque_no;
                        $cheque_reg->bank_acc_no = $request->bank_acc_no;
                        $cheque_reg->payment_mode = $request->payment_mode;
                        $cheque_reg->date = $request->cheque_date;
                        $cheque_reg->br_no = $data["id"];
                        $cheque_reg->vr_no = $data["vr_no"];
                        $cheque_reg->particulars = $data["particulars"];
                        $cheque_reg->head_of_acc = $data["head_acc"];
                        $cheque_reg->gross_amount = $data["amount"];
                        $cheque_reg->total_amount = $request->cheque_amount;
                        if($cheque_reg->save()){
                            $billRegister = BillRegister::where("id",$data["id"])->update(["vr_id"=>$data["vr_no"]]);
                            if(!$billRegister){
                                DB::rollBack();
                                return response()->json(['status'=>false,'data'=>"Error while saving data ".$data['id']." - ".$data["vr_no"]]);
                            }
                        }else{
                            DB::rollBack();
                            return response()->json(['status'=>false,'data'=>"Error while saving data! Please check if all the fields are filled"]);
                        }
                    }
                }
            }
            DB::commit();
            if($request->updateFlag){
                return response()->json(["status"=>true,"data"=>"Entry registered in Cheque Register","redirectUrl"=>route("cheque_register")]);
            }else{
                return response()->json(["status"=>true,"data"=>"Entry registered in Cheque Register","redirectUrl"=>null]);
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status'=>false,'data'=>$e->getMessage()]);
        }
    }

    public function editChequeRegister(Request $request){
        $id = Crypt::decryptString($request->id);
        try{
            if(!ChequeRegister::where("cheque_no",$id)->exists()){
                return back()->with(["status" => false, "message" => "Cheque ".$id." not found in the cheque register!"]);
            }

            $cheque = ChequeRegister::where('cheque_no',$id)->get();
            $chequeDet = $cheque->first();
            $brDet = $cheque->groupBy("br_no");
            $br = [];
            foreach($brDet as $data){
                $br_data = BillRegister::where("id",$data[0]->br_no)->first();
                $br_data->vr_no = $br_data->vr_id;
                array_push($br,$br_data);
            }
            return view('accounts.cheque-register.edit_register_entry',["cheque"=>$chequeDet,"brDet"=>$brDet,"br_data"=>$br]);
            
        }
        catch(\Exception $e){
            return back()->with(["status" => false, "message" => "Error while fetching cheque ".$id." from cheque register!"]);
        }
    }

    public function deleteChequeRegister(Request $request){
        $id = Crypt::decryptString($request->id);
        try{
            if(ChequeRegister::where("cheque_no",$id)->exists()){
                $cheque_reg = ChequeRegister::where("cheque_no",$id)->delete();
                if($cheque_reg){
                    return redirect()->route("cheque_register")->with(["status" => true, "message" => "Entry for ".$id." removed from cheque register!"]);
                }else{
                    return back()->with(["status" => false, "message" => "Error while removing cheque ".$id." from cheque register!"]);
                }
            }else{
                return back()->with(["status" => false, "message" => "Cheque ".$id." not found in the cheque register!"]);
            }
        }
        catch(\Exception $e){
            return back()->with(["status" => false, "message" => "Error while removing cheque ".$id." from cheque register!"]);
        }
    }
}

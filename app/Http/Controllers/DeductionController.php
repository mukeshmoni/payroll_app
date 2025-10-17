<?php

namespace App\Http\Controllers;

use App\Exports\DeductionList;
use App\Models\DeductionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class DeductionController extends Controller
{
    protected $message;
    protected $deductionCategoryModel;
    public function __construct()
    {
        parent::__construct();
        $this->message = '';
        $this->deductionCategoryModel =new DeductionCategory();

    }

    public function deductionCategoryList(Request $request){
        $category = DeductionCategory::where('status',0)->select('id','deduction_name','deduction_type_name','mode','mode_value','frequency','created_at')->get();
        return view("deduction.deduction_category_list",["categories"=>$category, "mode" => DeductionCategory::MODE]);
    }

    public function createDeductionCategory(Request $request){
        return view("deduction.add_deduction_category",["mode"=>DeductionCategory::MODE,"frequency"=>DeductionCategory::FREQUENCY,"tax_mode"=>DeductionCategory::TAXABILITY_MODE]);
    }

    public function addDeductionCategory(Request $request){
        $validated = $request->validate([
            'deduction_name' => 'required',
            'deduction_type_name' => 'required',
            'mode'=>'required',
            'mode_value'=>'required|numeric',
            'tax_amount'=>'nullable|required_if:taxability,1|numeric'
        ]);

        if($validated){
            try{
            $id = isset($request->id)? Crypt::decryptString($request->id):"";
            
            $deductionCategory = $this->deductionCategoryModel->findOrNew($id);
            $deductionCategory->deduction_name = $request->deduction_name;
            $deductionCategory->deduction_type_name = $request->deduction_type_name;
            $deductionCategory->mode = $request->mode;
            $deductionCategory->mode_value = $request->mode_value;
            $deductionCategory->frequency = $request->frequency;
            $deductionCategory->taxability = $request->taxability;
            $deductionCategory->tax_amount = $request->tax_amount;
            $deductionCategory->comments = $request->comments!="" ? $request->comments : null ;
            $deductionCategory->status = "0";

            if (!$deductionCategory->exists) {
                $this->message = "Deduction has been added successfully!";
            } else {
                $this->message = "Deduction has been updated successfully!";
            }
            $deductionCategory->save();
            return redirect()->route("deduction.deduction_category_list")->with(["status" => true, "message" => $this->message]);
        }catch(\Exception $e){
            return back()->with(["status"=>false,"message"=>"Something went wrong!"]);
        }
            
        }
    }

    public function editDeductionCategory(Request $request){
        $id = Crypt::decryptString($request->id);
        $deductionCategory = $this->deductionCategoryModel::where("id",$id)->first();
        if($deductionCategory){
            return view("deduction.modify_deduction_category",["category"=>$deductionCategory,"mode"=>DeductionCategory::MODE,"frequency"=>DeductionCategory::FREQUENCY,"tax_mode"=>DeductionCategory::TAXABILITY_MODE]);
        }
    }

    public function deleteDeductionCategory(Request $request){
        $id = Crypt::decryptString($request->id);
        $deductionType = $this->deductionCategoryModel::find($id);
        $deductionType->status = "1";
        if($deductionType->save()){
            return back()->with(["status"=>true,"message"=>"Deduction has been deleted successfully!"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new DeductionList, 'DeductionList.xlsx');
    }
}

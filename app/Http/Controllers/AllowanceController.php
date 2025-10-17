<?php

namespace App\Http\Controllers;

use App\Exports\AllowanceList;
use App\Models\AllowanceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class AllowanceController extends Controller
{
    protected $message;
    protected $allowanceCategoryModel;
    public function __construct()
    {
        parent::__construct();
        $this->message = '';
        $this->allowanceCategoryModel = new AllowanceCategory();
    }


    public function allowanceCategoryList(Request $request)
    {
        $category = AllowanceCategory::where('status', 0)->select('id', 'allowance_name', 'allowance_type_name', 'mode', 'mode_value', 'frequency', 'created_at')->get();
        return view("allowance.allowance_category_list", ["categories" => $category, "mode" => AllowanceCategory::MODE]);
    }

    public function createAllowanceCategory(Request $request)
    {
        return view("allowance.add_allowance_category", ["mode" => AllowanceCategory::MODE, "frequency" => AllowanceCategory::FREQUENCY, "tax_mode" => AllowanceCategory::TAXABILITY_MODE]);
    }

    public function addAllowanceCategory(Request $request)
    {
        $validated = $request->validate([
            'allowance_name' => 'required|regex:/[a-zA-Z_&()\s]+$/',
            'allowance_type_name' => 'required|regex:/[a-zA-Z_&()\s]+$/',
            'mode' => 'required',
            'mode_value' => 'required|numeric',
            'tax_amount' => 'nullable|required_if:taxability,1|numeric'
        ]);

        if ($validated) {
            try {
                $id = isset($request->id) ? Crypt::decryptString($request->id) : "";

                $allowanceCategory = $this->allowanceCategoryModel->findOrNew($id);
                $allowanceCategory->allowance_name = $request->allowance_name;
                $allowanceCategory->allowance_type_name = $request->allowance_type_name;
                $allowanceCategory->mode = $request->mode;
                $allowanceCategory->mode_value = $request->mode_value;
                $allowanceCategory->frequency = $request->frequency;
                $allowanceCategory->taxability = $request->taxability;
                $allowanceCategory->tax_amount = $request->tax_amount;
                $allowanceCategory->comments = $request->comments != "" ? $request->comments : null;
                $allowanceCategory->status = "0";

                if (!$allowanceCategory->exists) {
                    $this->message = "Allowance has been added successfully!";
                } else {
                    $this->message = "Allowance has been updated successfully!";
                }
                $allowanceCategory->save();
                return redirect()->route("allowance.allowance_category_list")->with(["status" => true, "message" => $this->message]);
            } catch (\Exception $e) {
                return back()->with(["status" => false, "message" => "Something went wrong!"]);
            }
        }
    }

    public function editAllowanceCategory(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $allowanceCategory = $this->allowanceCategoryModel::where("id", $id)->first();
        if ($allowanceCategory) {
            return view("allowance.modify_allowance_category", ["category" => $allowanceCategory, "mode" => AllowanceCategory::MODE, "frequency" => AllowanceCategory::FREQUENCY, "tax_mode" => AllowanceCategory::TAXABILITY_MODE]);
        }
    }

    public function deleteAllowanceCategory(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $allowanceType = $this->allowanceCategoryModel::find($id);
        $allowanceType->status = "1";
        if ($allowanceType->save()) {
            return back()->with(["status" => true, "message" => "Allowance has been deleted successfully!"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new AllowanceList, 'AllowanceList.xlsx');
    }
}

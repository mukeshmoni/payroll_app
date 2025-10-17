<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\BillRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class BillRegisterController extends Controller
{

    protected $message;
    protected $billRegisterModel;
    public function __construct()
    {
        parent::__construct();
        $this->message = '';
        $this->billRegisterModel = new BillRegister();
    }


    public function billList(Request $request)
    {
        $br_list = BillRegister::where('status', 0)->select('id','bill_date', 'particulars', 'amount', 'name_of_clerk', 'created_at','vr_id',DB::raw("CASE
        WHEN vr_id = 0 THEN 'partially completed'
        ELSE 'completed'
    END AS vr_status"),'received_from')->get();
        return view("accounts.bill.bill", ["bill_register" => $br_list]);
    }

    public function createBillRegister(Request $request)
    {
        return view("accounts.bill.add_bill_register");
    }

    public function addBillRegister(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'bill_date' => 'required',
            'particulars' => 'required',
            'amount' => 'required',
            'name_of_clerk' => 'required',
            'received_from' => 'required'
        ]);

        if ($validated) {
            try {
                $id = isset($request->id) ? Crypt::decryptString($request->id) : "";

                $billRegister = $this->billRegisterModel->findOrNew($id);
                $billRegister->bill_date = $request->bill_date;
                $billRegister->particulars = $request->particulars;
                $billRegister->amount = $request->amount;
                $billRegister->name_of_clerk = $request->name_of_clerk;
                $billRegister->received_from = $request->received_from;
                $billRegister->status = "0";
                $billRegister->created_by = Auth::user()->id;

                if (!$billRegister->exists) {
                    $billRegister->vr_id = 0;
                    $this->message = "Bill Register has been added successfully!";
                } else {
                    $this->message = "Bill Register has been updated successfully!";
                }
                $billRegister->save();
                return redirect()->route("br.bill_list")->with(["status" => true, "message" => $this->message]);
            } catch (\Exception $e) {
                return back()->with(["status" => false, "message" => "Something went wrong!"]);
            }
        }
    }

    public function editBillRegister(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $billRegister = $this->billRegisterModel::where("id", $id)->first();
        if ($billRegister) {
            return view("accounts.bill.modify_bill_register", ["bill_register" => $billRegister]);
        }
    }

    public function deleteBillRegister(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $bill = $this->billRegisterModel::find($id);
        $bill->status = "1";
        if ($bill->save()) {
            return back()->with(["status" => true, "message" => "Bill Register has been deleted successfully!"]);
        }
    }
}

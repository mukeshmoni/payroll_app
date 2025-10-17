<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\states;
use App\Models\cities;
use App\Models\designations;
use App\Models\departments;
use App\Models\Employees;
use App\Models\centers;
use App\Models\paylevels;
use App\Models\payroll_final;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Exports\ExportEmployees;
use App\Imports\ImportEmployees;
use App\Models\SalaryStructure;
use Exception;

class EmployeeController extends Controller
{
    public function index(){
        $employees = Employees::leftJoin("designation","designation.id","=","employees.designation")
                                ->leftJoin("departments","departments.id","=","employees.department")
                                ->select("employees.*","designation.designation as desg_name","designation.desg_description as desg_description","departments.department as dept_name")
                                ->where("employees.status","0")
                                ->orderBy("employees.id","DESC")
                                ->get();
        return view("employees.employees",["employees"=>$employees]);
    }

    public function create(){
        $states = states::orderBy("name","ASC")->get();
        $cityData = cities::select('state_id', 'id', 'city')
            ->orderBy('state_id') // Optional: To ensure the results are ordered by state_id
            ->get();

        $cities = [];

        foreach ($cityData as $item) {
            $stateId = "$item->state_id";
            if (!isset($cities[$stateId])) {
                $cities[$stateId] = [];
            }
            $cities[$stateId][] = [$item->id, $item->city];
        }

        $designations = designations::where("status",0)->get();
        $departments = departments::where("status",0)->get();
        $centers = centers::where("status",0)->orderBy("centername","ASC")->get();
        $paylevels = paylevels::where("status",0)->get();
        return view("employees.add_employee",["states"=>$states,"cities"=>$cities,"designations"=>$designations,"departments"=>$departments,"centers"=>$centers,"paylevels"=>$paylevels]);
    }

    public function store(Request $request){
        // $validated = $request->validate([
        //     'empdob'=>'date|after:1940-01-01',
        //     'empcontact'=>'numeric|min_digits:10|max_digits:13',
        //     'emppanno'=>'alpha_num:ascii|max:10',
        //     'empaadhaarno'=>'numeric|digits:12',
        //     'pincode'=>'numeric',
        //     'empid'=>'nullable|alpha_num|unique:employees',
        //     'empdoj'=>'date|after:1940-01-01',
        //     'empaccno'=>'numeric|min_digits:5',
        //     'prevorgname'=>'required_if:prev_exp,yes',
        //     'quartersno'=>'required_if:quarters,yes',
        //     'doccupied'=>'required_if:quarters,yes',
        // ],[
        //     'empname.required'=>"Employee name is invalid",
        //     'empname.regex'=>"Employee name contains special characters",
        //     'fathername.required'=>"Father's name is invalid",
        //     'fathername.regex'=>"Father's name contains special characters",
        //     'mothername.required'=>"Mother's name is invalid",
        //     'mothername.regex'=>"Mother's name contains special characters",
        //     'empdob.required'=> "Invalid Date of Birth",
        //     'empdob.date'=> "Invalid Date of Birth",
        //     'empdob.after'=> "Invalid Date of Birth",
        //     'empgender.required'=>"Gender is required",
        //     'maritalstatus.required'=>"Marital status is required",
        //     'empcontact.required'=>"Provide valid contact number",
        //     'empcontact.numeric'=>"Provide valid contact number",
        //     'empcontact.min_digits'=>"Contact number is invalid",
        //     'empcontact.max_digits'=>"Contact number is invalid",
        //     'emppanno.required'=>"Provide valid PAN number",
        //     'emppanno.alpha_num'=>"PAN number contains special character",
        //     'emppanno.max'=>"PAN number is invalid",
        //     'empaadhaarno.required'=>"Provide valid Aadhaar number",
        //     'empaadhaarno.numeric'=>"Aadhaar should be numeric only",
        //     'empaadhaarno.digits'=>"Aadhaar should be 12 numbers",
        //     'empemail.required'=>"Provide Email address",
        //     'empemail.email'=>"Email address is invalid",
        //     'empaddress.required'=>'Provide employee address',
        //     'empaddress.regex'=>'Address contains invalid character',
        //     'empaddress.max'=>'Address is too long',
        //     'empstate.required'=>'Select State',
        //     'empcity.required'=>'Select City',
        //     'pincode.required'=>'Provide Pincode',
        //     'pincode.numeric'=>'Pincode should be numeric only',
        //     'empid.alpha_num'=>'Provide Employee ID',
        //     'empid.unique'=>'Employee ID already taken',
        //     'empdoj.required'=>'Provide Date of joining',
        //     'empdoj.date'=>'Date of joining is invalid',
        //     'empdoj.after'=>'Date of joining is invalid',
        //     'designation.required'=>'Select Designation',
        //     'department.required'=>'Select Department',
        //     'category.required'=>'Select Category',
        //     'bankname.required'=>'Provide Bankname',
        //     'bankname.regex'=>'Bank name contains invalid characters',
        //     'empaccno.required'=>'Provide Account number',
        //     'empaccno.numeric'=>'Account number should be in number format',
        //     'empaccno.min_digits'=>'Account number is invalid',
        //     'centers.required'=>'Select proper center',
        //     'pf_nps_cat.required'=>'Select valid Category',
        //     'prev_exp.required'=>'Select Previous experience',
        //     'prevorgname.required_if'=>'Provide previous organisation information',
        //     'quarters.required'=>'Select Quarters option',
        //     'quartersno.required_if'=>'Provide Quarters number',
        //     'doccupied.required_if'=>'Provide date of occupied',
        //     'eligiblehra.required'=>'Select Eligibility for HRA',
        //     'handicap.required'=>'Select Handicap or Not',
        //     'prnop.required'=>'Select Pensional or NOP',

        // ]);
        $validated = true;
        if($validated){
            try {
                //if user types employee id keep it as employee id
                if($request->empid){
                    $empid = $request->empid;
                }else{//or else generate employee id
                    $empid = IdGenerator::generate(['table' => 'employees','field'=>'empid', 'length' => 3, 'prefix' =>'1']);
                }

                $employee = new Employees;
                $employee->empname = $request->empname;
                $employee->fathername = $request->fathername;
                $employee->mothername = $request->mothername;
                $employee->empdob = $request->empdob;
                $employee->empgender = $request->empgender;
                $employee->maritalstatus = $request->maritalstatus;
                $employee->empcontact = $request->empcontact;
                $employee->emppanno = $request->emppanno;
                $employee->empaadhaarno = $request->empaadhaarno;
                $employee->empemail = $request->empemail;
                $employee->empaddress = $request->empaddress;
                $employee->empstate = $request->empstate;
                $employee->empcity = $request->empcity;
                $employee->pincode = $request->pincode;
                $employee->empid = $empid;
                $employee->empdoj = $request->empdoj;
                $employee->empdor = $request->empdor;
                $employee->designation = $request->designation;
                $employee->department = $request->department;
                $employee->category = $request->category;
                $employee->bankname = $request->bankname;
                $employee->empaccno = $request->empaccno;
                $employee->center = $request->center;
                $employee->gpfno = $request->gpfno;
                $employee->npsno = $request->npsno;
                $employee->pf_nps_cat = $request->pf_nps_cat;
                $employee->prev_exp = $request->prev_exp;
                $employee->prevorgname = $request->prevorgname;
                $employee->totincomerec = $request->totincomerec;
                $employee->totincometax = $request->totincometax;
                $employee->domedicalexam = $request->domedicalexam;
                $employee->emppay = $request->emppay;
                $employee->emppayscale = $request->emppayscale;
                $employee->payscallvl = $request->payscallvl;
                $employee->quarters = $request->quarters;
                $employee->quartersno = $request->quartersno;
                $employee->doccupied = $request->doccupied;
                $employee->dovacated = $request->dovacated;
                $employee->eligiblehra = $request->eligiblehra;
                $employee->handicap = $request->handicap;
                $employee->prnop = $request->prnop;
                $employee->pen_cat = $request->pen_cat;
                if($employee->save()){
                    if($request->empemail){
                        if(!User::where("email",$request->empemail)->exists()){
                            $user = new User;
                            $user->name = $request->empname;
                            $user->email = $request->empemail;
                            $user->password = Hash::make($request->empcontact);
                            $user->save();
            
                            $user->assignRole('employee');
                        }
                    }
                    return redirect()->route("employees")->with(["status"=>true,"message"=>"Employee added successfully!"]);
                }else{
                    return back()->with(["status"=>false,"message"=>"Can't add employee right now!"]);
                }
            } catch (Exception $e) {
                return back()->with(["status"=>false,"message"=>"Something went wrong!"]);
            }
        }
    }

    public function modify(Request $request){
        $id = Crypt::decryptString($request->id);

        if(Employees::where("id",$id)->where("status",0)->exists()){
            $states = states::orderBy("name","ASC")->get();
            $cityData = cities::select('state_id', 'id', 'city')
                ->orderBy('state_id') // Optional: To ensure the results are ordered by state_id
                ->get();

            $cities = [];

            foreach ($cityData as $item) {
                $stateId = "$item->state_id";
                if (!isset($cities[$stateId])) {
                    $cities[$stateId] = [];
                }
                $cities[$stateId][] = [$item->id, $item->city];
            }

            $designations = designations::where("status",0)->get();
            $departments = departments::where("status",0)->get();
            $centers = centers::where("status",0)->orderBy("centername","ASC")->get();
            $employee = Employees::find($id);
            $paylevels = paylevels::where("status",0)->get();
            return view("employees.modify_employee",["employee"=>$employee,"states"=>$states,"cities"=>$cities,"designations"=>$designations,"departments"=>$departments,"centers"=>$centers,"paylevels"=>$paylevels]);
        }else{
            return back()->with(["status"=>true,"message"=>"Sorry! Employee not found"]);
        }

    }

    public function update(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!Employees::where("id",$id)->where("status",0)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Employee not found"]);
        }

        // $validated = $request->validate([
        //     'empdob'=>'date|after:1940-01-01',
        //     'empcontact'=>'numeric|min_digits:10|max_digits:13',
        //     'emppanno'=>'alpha_num:ascii|max:10',
        //     'empaadhaarno'=>'numeric|digits:12',
        //     'pincode'=>'numeric',
        //     'empdoj'=>'date|after:1940-01-01',
        //     'empaccno'=>'numeric|min_digits:5',
        //     'prevorgname'=>'nullable|required_if:prev_exp,yes',
        //     'quartersno'=>'required_if:quarters,yes',
        //     'doccupied'=>'required_if:quarters,yes',
        // ],[
        //     'empname.required'=>"Employee name is invalid",
        //     'empname.regex'=>"Employee name contains special characters",
        //     'fathername.required'=>"Father's name is invalid",
        //     'fathername.regex'=>"Father's name contains special characters",
        //     'mothername.required'=>"Mother's name is invalid",
        //     'mothername.regex'=>"Mother's name contains special characters",
        //     'empdob.required'=> "Invalid Date of Birth",
        //     'empdob.date'=> "Invalid Date of Birth",
        //     'empdob.after'=> "Invalid Date of Birth",
        //     'empgender.required'=>"Gender is required",
        //     'maritalstatus.required'=>"Marital status is required",
        //     'empcontact.required'=>"Provide valid contact number",
        //     'empcontact.numeric'=>"Provide valid contact number",
        //     'empcontact.min_digits'=>"Contact number is invalid",
        //     'empcontact.max_digits'=>"Contact number is invalid",
        //     'emppanno.required'=>"Provide valid PAN number",
        //     'emppanno.alpha_num'=>"PAN number contains special character",
        //     'emppanno.max'=>"PAN number is invalid",
        //     'empaadhaarno.required'=>"Provide valid Aadhaar number",
        //     'empaadhaarno.numeric'=>"Aadhaar should be numeric only",
        //     'empaadhaarno.digits'=>"Aadhaar should be 12 numbers",
        //     'empemail.required'=>"Provide Email address",
        //     'empemail.email'=>"Email address is invalid",
        //     'empaddress.required'=>'Provide employee address',
        //     'empaddress.regex'=>'Address contains invalid character',
        //     'empaddress.max'=>'Address is too long',
        //     'empstate.required'=>'Select State',
        //     'empcity.required'=>'Select City',
        //     'pincode.required'=>'Provide Pincode',
        //     'pincode.numeric'=>'Pincode should be numeric only',
        //     'empdoj.required'=>'Provide Date of joining',
        //     'empdoj.date'=>'Date of joining is invalid',
        //     'empdoj.after'=>'Date of joining is invalid',
        //     'designation.required'=>'Select Designation',
        //     'department.required'=>'Select Department',
        //     'category.required'=>'Select Category',
        //     'bankname.required'=>'Provide Bankname',
        //     'bankname.regex'=>'Bank name contains invalid characters',
        //     'empaccno.required'=>'Provide Account number',
        //     'empaccno.numeric'=>'Account number should be in number format',
        //     'empaccno.min_digits'=>'Account number is invalid',
        //     'centers.required'=>'Select proper center',
        //     'pf_nps_cat.required'=>'Select valid Category',
        //     'prev_exp.required'=>'Select Previous experience',
        //     'prevorgname.required_if'=>'Provide previous organisation information',
        //     'quarters.required'=>'Select Quarters option',
        //     'quartersno.required_if'=>'Provide Quarters number',
        //     'doccupied.required_if'=>'Provide date of occupied',
        //     'eligiblehra.required'=>'Select Eligibility for HRA',
        //     'handicap.required'=>'Select Handicap or Not',
        //     'prnop.required'=>'Select Pensional or NOP',

        // ]);
        $validated = true;
        if($validated){
            try {
                $employee = Employees::find($id);
                $employee->empname = $request->empname;
                $employee->fathername = $request->fathername;
                $employee->mothername = $request->mothername;
                $employee->empdob = $request->empdob;
                $employee->empgender = $request->empgender;
                $employee->maritalstatus = $request->maritalstatus;
                $employee->empcontact = $request->empcontact;
                $employee->emppanno = $request->emppanno;
                $employee->empaadhaarno = $request->empaadhaarno;
                $employee->empemail = $request->empemail;
                $employee->empaddress = $request->empaddress;
                $employee->empstate = $request->empstate;
                $employee->empcity = $request->empcity;
                $employee->pincode = $request->pincode;
                $employee->empdoj = $request->empdoj;
                $employee->empdor = $request->empdor;
                $employee->designation = $request->designation;
                $employee->department = $request->department;
                $employee->category = $request->category;
                $employee->bankname = $request->bankname;
                $employee->empaccno = $request->empaccno;
                $employee->center = $request->center;
                $employee->gpfno = $request->gpfno;
                $employee->npsno = $request->npsno;
                $employee->pf_nps_cat = $request->pf_nps_cat;
                $employee->prev_exp = $request->prev_exp;
                $employee->prevorgname = $request->prevorgname;
                $employee->totincomerec = $request->totincomerec;
                $employee->totincometax = $request->totincometax;
                $employee->domedicalexam = $request->domedicalexam;
                $employee->emppay = $request->emppay;
                $employee->emppayscale = $request->emppayscale;
                $employee->payscallvl = $request->payscallvl;
                $employee->quarters = $request->quarters;
                $employee->quartersno = $request->quartersno;
                $employee->doccupied = $request->doccupied;
                $employee->dovacated = $request->dovacated;
                $employee->eligiblehra = $request->eligiblehra;
                $employee->handicap = $request->handicap;
                $employee->prnop = $request->prnop;
                $employee->pen_cat = $request->pen_cat;
                if($employee->save()){
                    if($request->empemail){
                        if(!User::where("email",$request->empemail)->exists()){
                            $user = new User;
                            $user->name = $request->empname;
                            $user->email = $request->empemail;
                            $user->password = Hash::make($request->empcontact);
                            $user->save();
            
                            $user->assignRole('employee');
                        }else{
                            $user = User::where("email",$request->empemail)->update([
                                'name'=>$request->empname,
                                'email'=>$request->empemail,
                                'password'=>Hash::make($request->empcontact),
                            ]);
                        }
                    }

                    if($employee->prnop=="pensioner"){
                        if(SalaryStructure::where('employee',$employee->empid)->where("status",0)->exists()){
                            $salary = SalaryStructure::where('employee',$employee->empid)->where("status",0)->update([
                                "pensioner"=>1
                            ]);
                        }
                    }

                    return redirect()->route("employees")->with(["status"=>true,"message"=>"Employee updated successfully!"]);
                }else{
                    return back()->with(["status"=>false,"message"=>"Can't update employee right now!"]);
                }
            } catch (Exception $e) {
                return back()->with(["status"=>false,"message"=>"Something went wrong!"]);
            }
        }

    }

    public function delete(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!Employees::where("id",$id)->where("status",0)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Employee not found"]);
        }

        try {
            $employee = Employees::find($id);
            $employee->status = "1";
            if($employee->save()){
                if(User::where("email",$employee->empemail)->exists()){
                    $user = User::where("email",$employee->empemail)->first();
                    $user->removeRole('employee');
                    $user->delete();
                }
                return redirect()->route("employees")->with(["status"=>true,"message"=>"Employee deleted successfully!"]);
            }else{
                return back()->with(["status"=>false,"message"=>"Can't delete employee right now!"]);
            }
        } catch (Exception $e) {
            return back()->with(["status"=>false,"message"=>"Something went wrong!"]);
        }
    }

    public function view(Request $request){
        $id = Crypt::decryptString($request->id);
        if(!Employees::where("id",$id)->where("status",0)->exists()){
            return back()->with(["status"=>true,"message"=>"Sorry! Employee not found"]);
        }

        try {
            $employee = Employees::leftJoin("designation","designation.id","=","employees.designation")
                                ->leftJoin("departments","departments.id","=","employees.department")
                                ->leftJoin("states","states.id","=","employees.empstate")
                                ->leftJoin("cities","cities.id","=","employees.empcity")
                                ->leftJoin("centers","centers.id","=","employees.center")
                                ->leftJoin("paylevels","paylevels.id","=","employees.payscallvl")
                                ->select("employees.*","employees.id as employee_id","designation.designation as desg_name","designation.desg_description as desg_description","departments.department as dept_name","states.name as state","cities.city as city","centers.centername","paylevels.paylevel as paylevel")
                                ->where("employees.status","0")
                                ->where("employees.id",$id)
                                ->first();
            return view("employees.view_employee",["employee"=>$employee]);
            
        } catch (Exception $e) {
            return back()->with(["status"=>false,"message"=>"Something went wrong!"]);
        }
    }

    public function export(Request $request){
        return Excel::download(new ExportEmployees, 'Employees.xlsx');
    }

    public function import(Request $request){
        $model = new ImportEmployees;
        $result = Excel::import($model,$request->file('employees'));
        // $data = $model->getData();
        // dd($data);
        return back()->with(["status"=>true,"message"=>"Employees updated successfully!"]);
    }

    public function order(){
        return view("employees.order");
    }

    public function order_update(Request $request){
        $empid = $request->empid;
        $order = $request->order;
        $category = $request->category;

        if(!Employees::where("empid",$empid)->where('category',$category)->where("status",0)->exists()){
            return back()->withInput($request->only('category'))->with(["status"=>false,"message"=>"Sorry! Employee not found"]);
        }

        $employee = Employees::where("empid",$empid)->where('category',$category)->where("status",0)->update([
            "order"=>$order
        ]);

        if($employee){
            $employees = Employees::where("order",">=",$order)->where('category',$category)->where("status",0)->orderBy("order","ASC")->get();

            foreach($employees as $emp){
                if($emp->empid!=$empid){
                    if($emp->order){
                        $order = $emp->order+1;
                        $employee = Employees::where("empid",$emp->empid)->where('category',$category)->where("status",0)->update([
                            "order"=>$order
                        ]);
                    }
                }
            }
            return back()->withInput($request->only('category'))->with(["status"=>true,"message"=>"Order Updated"]);
        }



    }

    public function payslip(){
        if(Employees::where("empemail",Auth::user()->email)->exists()){
            $empid = Employees::where("empemail",Auth::user()->email)->first()->empid;
            $payslips = payroll_final::where("employee",$empid)->orderBy("month","ASC")->get();
            return view('employees.payslips',['payslips'=>$payslips]);
        }
    }
}

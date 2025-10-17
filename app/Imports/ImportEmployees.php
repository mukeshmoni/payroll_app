<?php

namespace App\Imports;

use App\Models\Employees;
use App\Models\designations;
use App\Models\departments;
use App\Models\states;
use App\Models\cities;
use App\Models\paylevels;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEmployees implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $data = [];
    private $desgID = null;
    private $deptID = null;
    private $paylevelID = null;
    private $state = null;
    private $city = null;
    public function model(array $row)
    {
        array_push($this->data,$row);
        if(!Employees::where("empemail",$row["employee_email"])->where("status",0)->exists()){

            if($row["employee_id"]){
                $empid = $row["employee_id"];
                // if(Employees::where("empid",$empid)->where("status",0)->exists()){
                //     $empid = IdGenerator::generate(['table' => 'employees','field'=>'empid', 'length' => 3, 'prefix' =>'1']);
                // }
            }else{//or else generate employee id
                $empid = IdGenerator::generate(['table' => 'employees','field'=>'empid', 'length' => 3, 'prefix' =>'1']);
            }
    
            if(!designations::where("designation",$row["designation"])->where("status",0)->exists()){
                $desg = new designations;
                $desg->designation = $row["designation"];
                $desg->status = 0;
                $desg->save();
                $this->desgID = $desg->id;
            }else{
                $this->desgID = designations::where("designation",$row["designation"])->where("status",0)->first()->id;
            }
            if(!departments::where("department",$row["department"])->where("status",0)->exists()){
                $dept = new departments;
                $dept->department = $row["department"];
                $dept->status = 0;
                $dept->save();
                $this->deptID = $dept->id;
            }else{
                $this->deptID = departments::where("department",$row["department"])->where("status",0)->first()->id;
            }
    
            if(!paylevels::where("paylevel",$row["pay_level"])->where("status",0)->exists()){
                $paylevel = new paylevels;
                $paylevel->paylevel = $row["pay_level"];
                $paylevel->slab = 0;
                $paylevel->created_by = Auth::user()->id;
                $paylevel->save();
                $this->paylevelID = $paylevel->id;
            }else{
                $this->paylevelID = paylevels::where("paylevel",$row["pay_level"])->where("status",0)->first()->id;
            }
    
            $states = strtoupper($row["state"]);
            $this->state = states::whereRaw('UPPER(name) = ?', [$states])->first();
            $this->city = cities::where('city','LIKE','%'.$row['city'].'%')->first();
    
            $dob = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["employee_dob"])->format('Y-m-d')));
            $doj = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["employee_doj"])->format('Y-m-d')));
            $dor = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["employee_dor"])->format('Y-m-d')));
            $doccupied = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["date_of_occupied"])->format('Y-m-d')));
            $dovacate = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["date_of_vacated"])->format('Y-m-d')));
            $meddate = date("Y-m-d",strtotime(Date::excelToDateTimeObject($row["medical_examination_date"])->format('Y-m-d')));
            $aadhar = str_replace(' ', '', $row["aadhaar_number"]);

            if(Employees::where("empid",$empid)->where("status",0)->exists()){
                Employees::where("empid",$empid)->where("status",0)->update([
                    "empname" => $row["employee_name"],
                    "fathername" => $row["father_name"],
                    "mothername" => $row["mother_name"],
                    "empdob" => $dob,
                    "empgender" => $row["gender"],
                    "maritalstatus" => $row["marital_status"]?$row["marital_status"]:"Not Provided",
                    "empcontact" => $row["employee_contact"]?str_replace(" ","",$row["employee_contact"]):0000000000,
                    "emppanno" => $row["pan_number"],
                    "empaadhaarno" => $aadhar,
                    "empemail" => $row["employee_email"]?$row["employee_email"]:"Not Provided",
                    "empaddress" => $row["employee_address"],
                    "empstate" => $this->state?$this->state->id:"Not Provided",
                    "empcity" => $this->city?$this->city->id:"Not Provided",
                    "pincode" => str_replace(" ","",$row["pincode"]),
                    "empdoj" => $doj,
                    "empdor" => $dor,
                    "designation" => $this->desgID,
                    "department" => $this->deptID,
                    "category" => $row["employee_category"],
                    "bankname" => $row["bank_name"],
                    "empaccno" => $row["account_number"],
                    "center" => $row["center"],
                    "gpfno" => $row["gpf"],
                    "npsno" => $row["nps"]?$row["nps"]:NULL,
                    "pf_nps_cat" => $row["pf_or_nps"],
                    "prev_exp" => $row["previous_experience"],
                    "prevorgname" => $row["previous_org_name"],
                    "totincomerec" => $row["total_income"],
                    "totincometax" => $row["total_income_tax"],
                    "domedicalexam" => $row["medical_examination_date"],
                    "emppay" => $row["basic_pay"],
                    "emppayscale" => $row["pay_scale"],
                    "payscallvl" => $this->paylevelID,
                    "quarters" => strtolower($row["quarters"])=="false"?"no":"yes",
                    "quartersno" => $row["quarters_no"],
                    "doccupied" => $doccupied,
                    "dovacated" => $dovacate,
                    "eligiblehra" => $row["quarters"]=="false"?"yes":"no",
                    "handicap" => $row["handicap"],
                    "prnop" => $row["pensioner_or_non_pensioner"],
                ]);
            }else{
                return new Employees([
                    "empname" => $row["employee_name"],
                    "fathername" => $row["father_name"],
                    "mothername" => $row["mother_name"],
                    "empdob" => $dob,
                    "empgender" => $row["gender"],
                    "maritalstatus" => $row["marital_status"]?$row["marital_status"]:"Not Provided",
                    "empcontact" => $row["employee_contact"]?str_replace(" ","",$row["employee_contact"]):0000000000,
                    "emppanno" => $row["pan_number"],
                    "empaadhaarno" => $aadhar,
                    "empemail" => $row["employee_email"]?$row["employee_email"]:"Not Provided",
                    "empaddress" => $row["employee_address"],
                    "empstate" => $this->state?$this->state->id:"Not Provided",
                    "empcity" => $this->city?$this->city->id:"Not Provided",
                    "pincode" => str_replace(" ","",$row["pincode"]),
                    "empid" => $empid,
                    "empdoj" => $doj,
                    "empdor" => $dor,
                    "designation" => $this->desgID,
                    "department" => $this->deptID,
                    "category" => $row["employee_category"],
                    "bankname" => $row["bank_name"],
                    "empaccno" => $row["account_number"],
                    "center" => $row["center"],
                    "gpfno" => $row["gpf"],
                    "npsno" => NULL,
                    "pf_nps_cat" => $row["pf_or_nps"],
                    "prev_exp" => $row["previous_experience"],
                    "prevorgname" => $row["previous_org_name"],
                    "totincomerec" => $row["total_income"],
                    "totincometax" => $row["total_income_tax"],
                    "domedicalexam" => $row["medical_examination_date"]?$meddate:NULL,
                    "emppay" => $row["basic_pay"],
                    "emppayscale" => $row["pay_scale"],
                    "payscallvl" => $this->paylevelID,
                    "quarters" => strtolower($row["quarters"])=="false"?"no":"yes",
                    "quartersno" => $row["quarters_no"],
                    "doccupied" => $doccupied,
                    "dovacated" => $dovacate,
                    "eligiblehra" => $row["quarters"]=="false"?"yes":"no",
                    "handicap" => $row["handicap"],
                    "prnop" => $row["pensioner_or_non_pensioner"],
                ]);
            }
        }
    }
    public function getData()
    {
        foreach ($this->data as $data) {
            if($data['employee_id']=="11960"){
                // $states = strtoupper($data["state"]);
                // $stateData = states::whereRaw('UPPER(name) = ?', [$states])->first()->id;
                // $this->city = cities::where('city','LIKE','%'.$row['city'].'%')->first();
                return $data;
            }
        }
    //    return $this->data;
    }
}

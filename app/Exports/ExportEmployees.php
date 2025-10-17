<?php

namespace App\Exports;

use App\Models\Employees;
use App\Models\designations;
use App\Models\departments;
use App\Models\states;
use App\Models\cities;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEmployees implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
    {
        return Employees::leftJoin("designation","designation.id","=","employees.designation")
                        ->leftJoin("departments","departments.id","=","employees.department")
                        ->leftJoin("states","states.id","=","employees.empstate")
                        ->leftJoin("cities","cities.id","=","employees.empcity")
                        ->select([
                                    "employees.empid",
                                    "employees.empname",
                                    "employees.fathername",
                                    "employees.mothername",
                                    "employees.empdob",
                                    "employees.empgender",
                                    "employees.maritalstatus",
                                    "employees.empcontact",
                                    "employees.emppanno",
                                    "employees.empaadhaarno",
                                    "employees.empemail",
                                    "employees.empaddress",
                                    "states.name",
                                    "cities.city",
                                    "employees.pincode",
                                    "employees.empdoj",
                                    "employees.empdor",
                                    "designation.desg_description",
                                    "departments.desg_department",
                                    "employees.bankname",
                                    "employees.empaccno",
                                    "employees.gpfno",
                                    "employees.npsno",
                                    "employees.prev_exp",
                                    "employees.prevorgname",
                                    "employees.totincomerec",
                                    "employees.totincometax",
                                    "employees.domedicalexam",
                                    "employees.emppay",
                                    "employees.emppayscale",
                                    "employees.payscallvl",
                                    "employees.quarters",
                                    "employees.quartersno",
                                    "employees.doccupied",
                                    "employees.dovacated",
                                    "employees.eligiblehra",
                                    "employees.handicap",
                                    "employees.prnop",
                        ])->where("employees.status",0)->get();
    }

    public function headings(): array
    {
        return [
            "Employee ID",
            "Employee Name",
            "Father Name",
            "Mother Name",
            "DOB",
            "Sex",
            "Marital Status",
            "Contact",
            "PAN Number",
            "Aadhaar Number",
            "Email",
            "Address",
            "State",
            "City",
            "Pincode",
            "DOJ",
            "DOR",
            "Designation",
            "Department",
            "Bank Name",
            "Account Number",
            "GPF No",
            "NPS No",
            "Previous Experience",
            "Previous Org. Name",
            "Total Income Received till DOJ",
            "Total Income Recovered till DOJ",
            "Date of Medical Examination",
            "Pay",
            "Pay Scale",
            "Pay Scale Level",
            "Staying in Quarters",
            "Quarters No",
            "Date of Occupied",
            "Date of Vacated",
            "Eligible for HRA",
            "Physically Handicap",
            "Pernsionar or NOP",
        ];
    }
}

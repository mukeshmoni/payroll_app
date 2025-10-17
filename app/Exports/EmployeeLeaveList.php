<?php

namespace App\Exports;

use App\Models\leaves;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeLeaveList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return leaves::leftJoin("employees", "employees.empid", "=", "leaves.empid")
            ->select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT leaves.id) ASC) AS sno'),
                "leaves.empid",
                "employees.empname",
                DB::raw('CASE WHEN leaves.leavetype = "el" THEN "Earned Leave"
            WHEN leaves.leavetype = "ml" THEN "Medical Leave" 
            WHEN leaves.leavetype = "cl" THEN "Casual Leave" 
            ELSE "-" END AS leavetype'),
                DB::raw('DATE_FORMAT(leaves.startdt, "%d-%m-%Y") AS startdt'),
                DB::raw('DATE_FORMAT(leaves.enddt, "%d-%m-%Y") AS enddt'),
                "leaves.days",
                DB::raw('CASE WHEN leaves.remark IS NOT NULL THEN leaves.remark ELSE "-" END AS remark')
            )
            ->where("leaves.status", "0")
            ->orderBy("leaves.id", "ASC")
            ->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Emp ID",
            "Employee Name",
            "Leave Type",
            "Start Date",
            "End Date",
            "Days",
            "Remark"
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\loans_advances;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanAdvanceList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return loans_advances::leftJoin("employees", "employees.empid", "=", "loans_advances.empid")
            ->leftJoin("deduction", "deduction.deduction_type_name", "=", "loans_advances.da_types")
            ->select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT loans_advances.id) ASC) AS sno'),
                "loans_advances.empid",
                "employees.empname",
                "loans_advances.loans_advances",
                "deduction.deduction_name",
                "loans_advances.amt",
                DB::raw('DATE_FORMAT(loans_advances.startdt, "%d-%m-%Y") AS startdt'),
                "loans_advances.tenure",
                "loans_advances.totamt",
                DB::raw('CASE WHEN loans_advances.remark IS NOT NULL THEN loans_advances.remark ELSE "-" END AS remark')
            )
            ->where("loans_advances.status", "0")
            ->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Emp ID",
            "Employee Name",
            "Loan/Advance",
            "Deduction Name",
            "Amount",
            "Start Date",
            "Tenure",
            "Total Amount",
            "Remark"
        ];
    }
}

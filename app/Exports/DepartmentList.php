<?php

namespace App\Exports;

use App\Models\departments;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return departments::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT id) DESC) AS sno'),
            'department',
            DB::raw('CASE WHEN desg_department IS NULL THEN "-" ELSE desg_department END AS desg_department')

        )->where('status', '0')->orderBy("department", "ASC")->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Department Name",
            "Description"
        ];
    }
}

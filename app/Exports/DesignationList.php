<?php

namespace App\Exports;

use App\Models\designations;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesignationList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return designations::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT id) DESC) AS sno'),
            'designation',
            DB::raw('CASE WHEN desg_description IS NULL THEN "-" ELSE desg_description END AS tax_amount')

        )->where('status', '0')->orderBy("designation", "ASC")->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Designation Name",
            "Description"
        ];
    }
}

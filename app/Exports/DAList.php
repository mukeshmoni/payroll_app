<?php

namespace App\Exports;

use App\Models\da;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DAList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return da::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT id) ASC) AS sno'),
            'year',
            DB::raw('CASE WHEN remark IS NOT NULL THEN remark ELSE "-" END AS remark')
        )->where('status', '0')->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Year",
            "Remarks"
        ];
    }
}

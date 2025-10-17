<?php

namespace App\Exports;

use App\Models\AllowanceCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllowanceList implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AllowanceCategory::where('status', 0)->select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT id) DESC) AS sno'),
            'allowance_name',
            'allowance_type_name',
            DB::raw('CASE WHEN mode = 1 THEN "Amount"
            WHEN mode = 2 THEN "Percentage" 
            ELSE "-" END AS mode'),
            'mode_value',
            DB::raw('CASE WHEN frequency = 1 THEN "Monthly"
            WHEN frequency = 2 THEN "Annually"
            ELSE "-" END AS frequency'),
            DB::raw('CASE WHEN taxability = 1 THEN "Yes"
            WHEN taxability = 2 THEN "No"
            ELSE "-" END AS taxability'),
            DB::raw('CASE WHEN tax_amount IS NULL THEN "-" ELSE tax_amount END AS tax_amount'),
            DB::raw('CASE WHEN comments IS NOT NULL THEN comments ELSE "-" END AS comments')

        )->get();
    }

    public function headings(): array
    {
        return [
            "S.No",
            "Allowance Name",
            "Allowance Type Name",
            "Mode",
            "Value",
            "Frequency",
            "Taxability",
            "Tax Amount",
            "Comments"
        ];
    }
}

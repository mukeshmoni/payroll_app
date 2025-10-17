<?php

namespace App\Exports;
use App\Models\centers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CentersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return centers::where("status",0)->select("centername","created_at","updated_at")->get();
    }

    public function headings(): array
    {
        return [
            "Center Name",
            "Created At",
            "Updated At",
        ];
    }
}

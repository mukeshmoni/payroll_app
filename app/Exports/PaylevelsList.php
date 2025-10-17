<?php

namespace App\Exports;
use App\Models\paylevels;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaylevelsList implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return paylevels::where("status",0)->select("paylevel","slab","created_at","updated_at")->get();
    }

    public function headings(): array
    {
        return [
            "Pay Level",
            "Slab Amount",
            "Created At",
            "Updated At",
        ];
    }
}

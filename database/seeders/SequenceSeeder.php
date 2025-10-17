<?php

namespace Database\Seeders;

use App\Models\Sequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class SequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sequence = Sequence::create([
            'sequence'=>1,
            'account'=>'SBI-888,SBI-130',
            'value'=>0,
            'updated_by'=>'',
        ]);
        $sequence = Sequence::create([
            'sequence'=>20001,
            'account'=>'NITTT OWP A/c 10548',
            'value'=>20000,
            'updated_by'=>'',
        ]);
        $sequence = Sequence::create([
            'sequence'=>40001,
            'account'=>'GPF A/c-2193',
            'value'=>40000,
            'updated_by'=>'',
        ]);
    }
}

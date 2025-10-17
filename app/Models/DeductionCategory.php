<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionCategory extends Model
{
    use HasFactory;
    protected $table = "deduction";
    protected $primaryKey = 'id';
    protected $fillable = ['deduction_name','deduction_type_name','mode','mode_value'];

    const MODE =[
        "1"=>"Amount Based",
        "2"=>"Percentage Based"
    ];

    const FREQUENCY = [
        "1"=>"Monthly",
        "2"=>"Yearly"
    ];

    const TAXABILITY_MODE=[
        "1"=>"Yes",
        "2"=>"No"
    ];

}

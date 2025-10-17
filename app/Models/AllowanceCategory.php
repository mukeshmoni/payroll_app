<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceCategory extends Model
{
    use HasFactory;
    protected $table = "allowance";
    protected $primaryKey = 'id';
    protected $fillable = ['allowance_name','allowance_type_name','mode','mode_value'];


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

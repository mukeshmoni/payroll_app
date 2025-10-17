<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillRegister extends Model
{
    use HasFactory;
    protected $table = "bill_register";
    protected $primaryKey = 'id';
    protected $fillable = ['bill_date','particulars','amount','name_of_clerk','received_from'];
}

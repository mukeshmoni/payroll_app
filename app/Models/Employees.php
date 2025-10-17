<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $fillable = ["empname","fathername","mothername","empdob","empgender","maritalstatus","empcontact","emppanno","empaadhaarno","empemail","empaddress","empstate","empcity","pincode","empid","empdoj","empdor","designation","department","category","bankname","empaccno","center","gpfno","npsno","pf_nps_cat","prev_exp","prevorgname","totincomerec","totincometax","domedicalexam","emppay","emppayscale","payscallvl","quarters","quartersno","doccupied","dovacated","eligiblehra","handicap","prnop"];
}

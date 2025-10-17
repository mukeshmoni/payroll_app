<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employees;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(){
        $employees = Employees::where("status",0)->whereNotNull("empemail")->get();
        foreach($employees as $emp){
            if(!User::where("email",$emp->empemail)->exists()){
                $user = new User;
                $user->name = $emp->empname;
                $user->email = $emp->empemail;
                $user->password = Hash::make($emp->empcontact);
                $user->save();

                $user->assignRole('employee');
            }
        }
    }
}

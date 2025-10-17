<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            
            $table->string("empname",50)->nullable()->change();
            $table->string("fathername",50)->nullable()->change();
            $table->string("mothername",50)->nullable()->change();
            $table->date("empdob")->nullable()->change();
            $table->string("empgender",20)->nullable()->change();
            $table->string("maritalstatus",20)->nullable()->change();
            $table->bigInteger("empcontact")->nullable()->change();
            $table->string("emppanno",20)->nullable()->change();
            $table->bigInteger("empaadhaarno")->nullable()->change();
            $table->string("empemail",100)->nullable()->change();
            $table->longText("empaddress")->nullable()->change();
            $table->string("empstate",50)->nullable()->change();
            $table->string("empcity",50)->nullable()->change();
            $table->bigInteger("pincode")->nullable()->change();
            $table->string("empid",20)->nullable()->change();
            $table->date("empdoj")->nullable()->change();
            $table->string("designation",50)->nullable()->change();
            $table->string("department",50)->nullable()->change();
            $table->string("bankname",50)->nullable()->change();
            $table->bigInteger("empaccno")->nullable()->change();
            $table->string("prev_exp",20)->nullable()->change();
            $table->string("quarters",20)->nullable()->change();
            $table->string("eligiblehra",20)->nullable()->change();
            $table->string("handicap",20)->nullable()->change();
            $table->string("prnop",20)->nullable()->change();
            $table->string("center",10)->nullable()->change();
            $table->string("pf_nps_cat",10)->nullable()->change();
            $table->string("category",50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("empname",50);
            $table->string("fathername",50);
            $table->string("mothername",50);
            $table->date("empdob");
            $table->string("empgender",20);
            $table->string("maritalstatus",20);
            $table->bigInteger("empcontact");
            $table->string("emppanno",20);
            $table->bigInteger("empaadhaarno");
            $table->string("empemail",100);
            $table->longText("empaddress");
            $table->string("empstate",50);
            $table->string("empcity",50);
            $table->bigInteger("pincode");
            $table->string("empid",20)->unique();
            $table->date("empdoj");
            $table->date("empdor")->nullable();
            $table->string("designation",50);
            $table->string("department",50);
            $table->string("bankname",50);
            $table->bigInteger("empaccno");
            $table->bigInteger("gpfno")->nullable();
            $table->bigInteger("npsno")->nullable();
            $table->string("prev_exp",20);
            $table->string("prevorgname",50)->nullable();
            $table->bigInteger("totincomerec")->nullable();
            $table->bigInteger("totincometax")->nullable();
            $table->bigInteger("domedicalexam")->nullable();
            $table->bigInteger("emppay")->nullable();
            $table->string("emppayscale",50)->nullable();
            $table->bigInteger("payscallvl")->nullable();
            $table->string("quarters",20);
            $table->string("quartersno",20)->nullable();
            $table->date("doccupied")->nullable();
            $table->date("dovacated")->nullable();
            $table->string("eligiblehra",20);
            $table->string("handicap",20);
            $table->string("prnop",20);
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};

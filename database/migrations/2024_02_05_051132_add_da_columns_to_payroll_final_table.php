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
        Schema::table('payroll_final', function (Blueprint $table) {
            $table->bigInteger("prev_da")->nullable();
            $table->string("da_month",100)->nullable();
            $table->string("da_basic_salary",100)->nullable();
            $table->string("da_due",100)->nullable();
            $table->string("da_drawn",100)->nullable();
            $table->string("da_arrear",100)->nullable();
            $table->bigInteger("total_salary")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_final', function (Blueprint $table) {
            //
        });
    }
};

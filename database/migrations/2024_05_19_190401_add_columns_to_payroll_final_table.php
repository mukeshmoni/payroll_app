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
            $table->bigInteger("slab")->default(0);
            $table->bigInteger("tda_due")->default(0);
            $table->bigInteger("tda_drawn")->default(0);
            $table->bigInteger("tda_arrear")->default(0);
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

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
        Schema::table('payroll', function (Blueprint $table) {
            $table->bigInteger("addtl_pension")->nullable()->default(0);
            $table->bigInteger("medic_allow")->nullable()->default(0);
            $table->bigInteger("less_comm")->nullable()->default(0);
            $table->bigInteger("misc_rec")->nullable()->default(0);
            $table->bigInteger("irg")->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('loans_advances', function (Blueprint $table) {
            $table->bigInteger('adj_instal_no')->default(0)->nullable();
            $table->bigInteger('adj_instal_amt')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans_advances', function (Blueprint $table) {
            //
        });
    }
};

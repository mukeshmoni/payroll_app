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
        Schema::table('salary_structure', function ($table) {
            $table->string('employee',50);
            $table->bigInteger('da_perc');
            $table->bigInteger('hra_perc');
            $table->string('allowances')->nullable();
            $table->string('deductions')->nullable();
            $table->string('la')->nullable();
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

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
        Schema::create('loans_advances', function (Blueprint $table) {
            $table->id();
            $table->string("empid",50);
            $table->string("loans_advances",50);
            $table->string("da_types",50);
            $table->string("amt",50);
            $table->date("startdt");           
            $table->string("tenure",50);
            $table->string("totamt",50);
            $table->string("remark",200)->nullable();
            $table->string("la_extra1",50);
            $table->string("la_extra2",50);
            $table->string("status",50);
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
        Schema::dropIfExists('loans_advances');
    }
};

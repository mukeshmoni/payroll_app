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
        Schema::create('salary_structure', function (Blueprint $table) {
            $table->id();
            $table->string("department",50);
            $table->string("designation",50);
            $table->bigInteger("basic_salary");
            $table->bigInteger("da");
            $table->bigInteger("hra");
            $table->bigInteger("transport");
            $table->bigInteger("misc")->default(0);
            $table->bigInteger("pf");
            $table->bigInteger("npse");
            $table->bigInteger("npser");
            $table->bigInteger("net_salary");
            $table->bigInteger("it")->default(0);
            $table->integer("status")->default(0);
            $table->string("created_by",20);
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
        Schema::dropIfExists('salary_structure');
    }
};

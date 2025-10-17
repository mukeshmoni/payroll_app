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
        Schema::create('da', function (Blueprint $table) {
            $table->id();
            $table->string("da",50);
            $table->string("year",50);
            $table->string("month",50);
            $table->string("remark",200)->nullable();          
            $table->string("da_extra1",50);
            $table->string("da_extra2",50);
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
        Schema::dropIfExists('da');
    }
};

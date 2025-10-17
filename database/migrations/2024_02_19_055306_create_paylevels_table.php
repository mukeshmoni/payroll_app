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
        Schema::create('paylevels', function (Blueprint $table) {
            $table->id();
            $table->string("paylevel",50);
            $table->bigInteger("slab")->default(0);
            $table->bigInteger("status")->default(0);
            $table->string("created_by",10);
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
        Schema::dropIfExists('paylevels');
    }
};

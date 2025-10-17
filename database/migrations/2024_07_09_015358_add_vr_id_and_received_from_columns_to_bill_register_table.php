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
        Schema::table('bill_register', function (Blueprint $table) {
            $table->string("received_from",30)->after("name_of_clerk");
            $table->bigInteger("vr_id")->after("received_from")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_register', function (Blueprint $table) {
            //
        });
    }
};

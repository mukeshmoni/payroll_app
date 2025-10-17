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
        Schema::create('br_vr_assignment', function (Blueprint $table) {
            $table->id();
            $table->string("br_no");
            $table->string("vr_no");
            $table->string("bank_acc_no");
            $table->string("sequence");
            $table->bigInteger("status")->default(0);
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
        Schema::dropIfExists('br_vr_assignment');
    }
};

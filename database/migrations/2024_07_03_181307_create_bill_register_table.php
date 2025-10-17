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
        Schema::create('bill_register', function (Blueprint $table) {
            $table->id();
            $table->date("bill_date");
            $table->text("particulars");
            $table->string("amount",50);
            $table->string("name_of_clerk",10);
            $table->integer("status")->default(0)->comment('0 - Active, 1-Inactive');
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
        Schema::dropIfExists('bill_register');
    }
};

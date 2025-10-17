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
        Schema::table('loans_advances', function ($table) {
            $table->string('tenure',50)->nullable()->change();
            $table->string('totamt',50)->nullable()->change();
            $table->string('la_extra1',50)->nullable()->change();
            $table->string('la_extra2',50)->nullable()->change();
            $table->integer('status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};

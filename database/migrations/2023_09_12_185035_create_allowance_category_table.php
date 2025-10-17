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
        Schema::create('allowance_category', function (Blueprint $table) {
            $table->id();
            $table->string("allowance_name",50);
            $table->unsignedBigInteger('allowance_type_id');
            $table->integer("mode")->comment('0-Amount, 1-Percentage');
            $table->string("mode_value",50);
            $table->integer("frequency")->nullable()->comment('0 - Monthly, 1-Annually');
            $table->integer("taxability")->nullable()->comment('0 - yes, 1-no');
            $table->string("tax_amount",50)->nullable();
            $table->string("comments",150)->nullable();
            $table->integer("status")->default(0)->comment('0 - Active, 1-Inactive');
            $table->timestamps();
            // Define the foreign key constraint
            $table->foreign('allowance_type_id')->references('id')->on('allowance_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowance_category');
    }
};

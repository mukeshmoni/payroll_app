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
        Schema::table('deduction_category', function (Blueprint $table) {
            $table->dropForeign('deduction_category_deduction_type_id_foreign');
        });

        // Rename the 'deduction_type_id' column to 'new_column_name' in deduction_category table
        Schema::table('deduction_category', function (Blueprint $table) {
            $table->renameColumn('deduction_type_id', 'deduction_type_name');
        });

        Schema::table('deduction_category', function (Blueprint $table) {
            $table->string('deduction_type_name')->change();
        });

        // Drop the 'deduction_type' table
        Schema::dropIfExists('deduction_type');
        Schema::dropIfExists('deduction_types');
        Schema::dropIfExists('deduction_categories');
        Schema::rename('deduction_category', 'deduction');

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename the 'deduction_type_name' column back to 'deduction_type_id' in 'deduction_category' table
        Schema::table('deduction_category', function (Blueprint $table) {
            $table->renameColumn('deduction_type_name', 'deduction_type_id');
        });
    
        // Re-add the foreign key constraint on 'deduction_category' table
        Schema::table('deduction_category', function (Blueprint $table) {
            $table->foreign('deduction_type_id')->references('id')->on('deduction_type');
        });
    }
};

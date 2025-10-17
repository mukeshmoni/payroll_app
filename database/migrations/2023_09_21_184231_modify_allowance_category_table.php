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
        Schema::table('allowance_category', function (Blueprint $table) {
            $table->dropForeign('allowance_category_allowance_type_id_foreign');
        });

        // Rename the 'allowance_type_id' column to 'new_column_name' in allowance_category table
        Schema::table('allowance_category', function (Blueprint $table) {
            $table->renameColumn('allowance_type_id', 'allowance_type_name');
        });

        Schema::table('allowance_category', function (Blueprint $table) {
            $table->string('allowance_type_name')->change();
        });

        // Drop the 'allowance_type' table
        Schema::dropIfExists('allowance_type');
        Schema::dropIfExists('allowance_types');
        Schema::dropIfExists('allowance_categories');
        Schema::rename('allowance_category', 'allowance');

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename the 'allowance_type_name' column back to 'allowance_type_id' in 'allowance_category' table
        Schema::table('allowance_category', function (Blueprint $table) {
            $table->renameColumn('allowance_type_name', 'allowance_type_id');
        });
    
        // Re-add the foreign key constraint on 'allowance_category' table
        Schema::table('allowance_category', function (Blueprint $table) {
            $table->foreign('allowance_type_id')->references('id')->on('allowance_type');
        });
    }
};

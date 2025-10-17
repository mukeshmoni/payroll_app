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
        Schema::create('cheque_register', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_no');
            $table->string('bank_acc_no');
            $table->string('payment_mode');
            $table->date('date');
            $table->string('br_no');
            $table->string('vr_no');
            $table->string('particulars');
            $table->string('head_of_acc');
            $table->string('empid')->nullable();
            $table->string('deduction_type')->nullable();
            $table->bigInteger('deduction_perc')->default(0);
            $table->bigInteger('cess_perc')->default(0);
            $table->bigInteger('gross_amount')->default(0);
            $table->bigInteger('amount_wo_deduction')->default(0);
            $table->bigInteger('deducted_amount')->default(0);
            $table->bigInteger('net_amount')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->bigInteger('status')->default(0);
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
        Schema::dropIfExists('cheque_register');
    }
};

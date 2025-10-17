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
        Schema::create('income_tax', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("year");
            $table->string("empid",50);
            $table->string("deptid",10);
            $table->bigInteger("age");
            $table->string("regime",10);
            $table->bigInteger("salary");
            $table->bigInteger("arrears")->nullable()->default(0);
            $table->bigInteger("child_edu")->nullable()->default(0);
            $table->bigInteger("enc_of_el")->nullable()->default(0);
            $table->bigInteger("remuneration")->nullable()->default(0);
            $table->bigInteger("npser")->nullable()->default(0);
            $table->bigInteger("house_property")->nullable()->default(0);
            $table->bigInteger("other_income")->nullable()->default(0);
            $table->bigInteger("gross_income")->nullable()->default(0);
            $table->bigInteger("license_fee")->nullable()->default(0);
            $table->bigInteger("govt_nps")->nullable()->default(0);
            $table->bigInteger("standard_deduction")->nullable()->default(0);
            $table->bigInteger("hra_received")->nullable()->default(0);
            $table->bigInteger("rent_paid")->nullable()->default(0);
            $table->bigInteger("rent_calc")->nullable()->default(0);
            $table->bigInteger("hra_balance")->nullable()->default(0);
            $table->bigInteger("hra_exempted")->nullable()->default(0);
            $table->bigInteger("prefessional_tax")->nullable()->default(0);
            $table->bigInteger("balance_after_pt")->nullable()->default(0);
            $table->bigInteger("premia_insurance")->nullable()->default(0);
            $table->bigInteger("payment_interest")->nullable()->default(0);
            $table->bigInteger("higher_education")->nullable()->default(0);
            $table->bigInteger("disability_deduction")->nullable()->default(0);
            $table->bigInteger("other_deduction")->nullable()->default(0);
            $table->bigInteger("total_deduction_1")->nullable()->default(0);
            $table->bigInteger("deduction_balance_1")->nullable()->default(0);
            $table->bigInteger("lic_pf")->nullable()->default(0);
            $table->bigInteger("subscription_gpf")->nullable()->default(0);
            $table->bigInteger("lic_premium")->nullable()->default(0);
            $table->bigInteger("pli_premium")->nullable()->default(0);
            $table->bigInteger("gslis")->nullable()->default(0);
            $table->bigInteger("ulip")->nullable()->default(0);
            $table->bigInteger("nsc")->nullable()->default(0);
            $table->bigInteger("post_office")->nullable()->default(0);
            $table->bigInteger("public_pf")->nullable()->default(0);
            $table->bigInteger("spl_secu")->nullable()->default(0);
            $table->bigInteger("interest_nsc")->nullable()->default(0);
            $table->bigInteger("repayment_cost")->nullable()->default(0);
            $table->bigInteger("tuition_fees")->nullable()->default(0);
            $table->bigInteger("fixed_deposit")->nullable()->default(0);
            $table->bigInteger("total_savings")->nullable()->default(0);
            $table->bigInteger("eligible_deduction")->nullable()->default(0);
            $table->bigInteger("deduction_balance_2")->nullable()->default(0);
            $table->bigInteger("nps_add")->nullable()->default(0);
            $table->bigInteger("total_amount")->nullable()->default(0);
            $table->bigInteger("income_tax_round")->nullable()->default(0);
            $table->bigInteger("income_tax")->nullable()->default(0);
            $table->bigInteger("tax_rebate")->nullable()->default(0);
            $table->bigInteger("net_income_tax")->nullable()->default(0);
            $table->bigInteger("health_cess")->nullable()->default(0);
            $table->bigInteger("amt_to_be_deducted")->nullable()->default(0);
            $table->bigInteger("already_deducted")->nullable()->default(0);
            $table->bigInteger("balance_to_be_deducted")->nullable()->default(0);
            $table->bigInteger("nov_month")->nullable()->default(0);
            $table->bigInteger("dec_month")->nullable()->default(0);
            $table->bigInteger("jan_month")->nullable()->default(0);
            $table->bigInteger("feb_month")->nullable()->default(0);
            $table->bigInteger("total_month_deduction")->nullable()->default(0);
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
        Schema::dropIfExists('income_tax');
    }
};

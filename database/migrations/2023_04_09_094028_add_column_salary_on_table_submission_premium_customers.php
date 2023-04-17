<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSalaryOnTableSubmissionPremiumCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_premium_customers', function (Blueprint $table) {
            $table->unsignedBigInteger("salary")->nullable()->after("photo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submission_premium_customers', function (Blueprint $table) {
            $table->dropColumn("salary");
        });
    }
}

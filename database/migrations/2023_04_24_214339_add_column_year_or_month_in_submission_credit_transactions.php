<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnYearOrMonthInSubmissionCreditTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_credit_transactions', function (Blueprint $table) {
            $table->string('year_or_month_occupied')->after('long_occupied')->default('Bulan')->comment('Bulan/Tahun');
            $table->string('year_or_month_work')->after('length_of_work')->default('Bulan')->comment('Bulan/Tahun');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submission_credit_transactions', function (Blueprint $table) {
            $table->dropColumn('year_or_month_occupied');
            $table->dropColumn('year_or_month_work');
        });
    }
}

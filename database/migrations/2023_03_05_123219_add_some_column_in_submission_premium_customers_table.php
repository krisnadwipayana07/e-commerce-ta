<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnInSubmissionPremiumCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_premium_customers', function (Blueprint $table) {
            $table->string('ktp_name')->first();
            $table->string('ktp_number')->after('ktp_name');
            $table->text('ktp_address')->after('ktp_number');
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
            $table->dropColumn('ktp_name');
            $table->dropColumn('ktp_number');
            $table->dropColumn('ktp_address');
        });
    }
}

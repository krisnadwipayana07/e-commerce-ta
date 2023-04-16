<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnInTableSubmissionCreditTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_credit_transactions', function (Blueprint $table) {
            $table->string("full_name")->nullable();
            $table->string("nickname")->nullable();
            $table->string("mother_name")->nullable();
            $table->string("post_code")->nullable();
            $table->string("phone")->nullable();
            $table->string("birth_place")->nullable();
            $table->date("birth_date")->nullable();
            $table->string("gender")->nullable();
            $table->string("home_state")->nullable();
            $table->unsignedInteger("long_occupied")->nullable();
            $table->string("education")->nullable();
            $table->string("marital_status")->nullable();
            $table->string("jobs")->nullable();
            $table->string("company_name")->nullable();
            $table->string("company_address")->nullable();
            $table->string("company_phone")->nullable();
            $table->unsignedInteger("length_of_work")->nullable();
            $table->unsignedBigInteger("income_amount")->nullable();
            $table->unsignedBigInteger("extra_income")->nullable();
            $table->unsignedBigInteger("spending")->nullable();
            $table->unsignedBigInteger("residual_income")->nullable();
            $table->string("transportation_type")->nullable();
            $table->string("transportation_brand")->nullable();
            $table->string("year_of_purchase")->nullable();
            $table->string("police_number")->nullable();
            $table->string("transportation_color")->nullable();
            $table->string("bpkb_number")->nullable();
            $table->string("rekening_number")->nullable();
            $table->string("bank")->nullable();
            $table->string("owner_rekening")->nullable();
            $table->string("house_image")->nullable();
            $table->string("transportation_image")->nullable();
            $table->string("rekening_book_image")->nullable();
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->boolean("is_agree")->default(false);
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
            $table->dropColumn("full_name");
            $table->dropColumn("nickname");
            $table->dropColumn("mother_name");
            $table->dropColumn("post_code");
            $table->dropColumn("phone");
            $table->dropColumn("birth_place");
            $table->dropColumn("birth_date");
            $table->dropColumn("gender");
            $table->dropColumn("home_state");
            $table->dropColumn("long_occupied");
            $table->dropColumn("education");
            $table->dropColumn("marital_status");
            $table->dropColumn("jobs");
            $table->dropColumn("company_name");
            $table->dropColumn("company_address");
            $table->dropColumn("company_phone");
            $table->dropColumn("length_of_work");
            $table->dropColumn("income_amount");
            $table->dropColumn("extra_income");
            $table->dropColumn("spending");
            $table->dropColumn("residual_income");
            $table->dropColumn("transportation_type");
            $table->dropColumn("transportation_brand");
            $table->dropColumn("year_of_purchase");
            $table->dropColumn("police_number");
            $table->dropColumn("transportation_color");
            $table->dropColumn("bpkb_number");
            $table->dropColumn("rekening_number");
            $table->dropColumn("bank");
            $table->dropColumn("owner_rekening");
            $table->dropColumn("house_image");
            $table->dropColumn("transportation_image");
            $table->dropColumn("rekening_book_image");
            $table->dropColumn("latitude");
            $table->dropColumn("longitude");
            $table->dropColumn("is_agree");
        });
    }
}

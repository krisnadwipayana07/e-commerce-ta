<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSubmissionCreditTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_credit_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ktp_name');
            $table->string('ktp_number');
            $table->text('ktp_address');
            $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->string('ktp')->nullable();
            $table->string('salary_slip')->nullable();
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('submission_credit_transactions');
    }
}

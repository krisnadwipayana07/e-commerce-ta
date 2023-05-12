<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionDeliveryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_delivery_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Transaction::class)->constrained()->cascadeOnDelete();
            $table->string('product_evidence');
            $table->string('signature_evidence');
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
        Schema::dropIfExists('submission_delivery_payments');
    }
}

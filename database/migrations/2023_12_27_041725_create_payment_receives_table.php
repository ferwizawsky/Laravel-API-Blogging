<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_receives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('va_numbers')->nullable();
            $table->text('signature_key')->nullable();
            $table->text('payment_amounts')->nullable();

            $table->date('settlement_time')->nullable();
            $table->date('expiry_time')->nullable();
            $table->date('transaction_time')->nullable();


            $table->string('transaction_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status_message')->nullable();
            $table->string('status_code')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('order_id')->nullable();
            $table->string('gross_amount')->nullable();
            $table->string('fraud_status')->nullable();
            $table->string('currency')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receives');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount')->default(0); //integer to avoid rounding off
            $table->boolean('tx_type')->default(0); //credit / 1 for debit
            
            $table->date('datestamp')->nullable();

            $table->foreignId('work_id')->nullable();
            $table->foreignId('refund_credit_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->unsignedTinyInteger('status')->default(0); //success /failed /processing /hold
            $table->boolean('refunded')->default(0); //success /failed /processing /hold
            $table->unsignedTinyInteger('mode')->default(1); // upi /cash /banktransfer etc.
            $table->unsignedTinyInteger('type')->default(1); //Payment /Refund
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

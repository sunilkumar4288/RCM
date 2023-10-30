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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('wid')->unique();
            
            $table->unsignedBigInteger('total_amount')->default(0); //integer to avoid rounding off

            $table->string('specification')->nullable();
            $table->string('note')->nullable();

            $table->date('assign_date')->nullable();
            $table->date('end_date')->nullable();

            $table->foreignId('tech_id')->nullable();
            $table->foreignId('scholar_id')->nullable();
            $table->foreignId('query_id')->nullable();

            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->unsignedTinyInteger('lang')->default(1); //english
            $table->enum('status',['Pending For Approval','Processing','Closed','Completed'])->default('Pending For Approval'); //open

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};

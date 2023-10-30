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
       Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('qid')->unique();
            
            $table->string('title')->nullable();
            $table->string('specification')->nullable();
            $table->string('remarks')->nullable();

            $table->date('dod')->nullable();
            $table->string('source')->nullable();
            $table->text('tags')->nullable();          
            
            
            $table->foreignId('support_id')->nullable();
            $table->foreignId('scholar_id')->nullable();
            $table->boolean('quotation_sent')->default(0);
            
            $table->foreignId('area_id')->nullable();
            
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            
            $table->enum('lang', ['English', 'Hindi'])->default('English');  
            $table->enum('status',['Processing','Converted','Closed'])->default('Processing');



            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};

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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('mid')->unique();
            
            $table->string('title')->nullable();
            $table->unsignedBigInteger('amount')->default(0); //integer to avoid rounding off
            $table->string('remark')->nullable();            ;
            $table->string('remark2')->nullable();            ;

            $table->date('assign_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('completed_on')->nullable();

            $table->date('delivery_date')->nullable(); //deadline 

            $table->foreignId('tech_exe_id')->nullable();
            $table->foreignId('work_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->unsignedTinyInteger('mod_type')->default(1); // external /internal 
            $table->unsignedTinyInteger('status')->default(1); //open
            $table->unsignedTinyInteger('lang')->default(1); //english
            $table->unsignedTinyInteger('sort_order')->nullable(); //english
            $table->unsignedTinyInteger('priority')->default(0); //english
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

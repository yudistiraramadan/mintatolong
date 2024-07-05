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
        Schema::create('fundraisings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundraiser_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active');
            $table->boolean('has_finished');
            $table->string('name');
            $table->string('slug');
            $table->string('thumbnail');
            $table->text('about');
            $table->unsignedBigInteger('target_amount');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundraisings');
    }
};

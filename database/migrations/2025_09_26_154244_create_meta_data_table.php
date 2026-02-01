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
        Schema::create('meta_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->year('year');
            $table->enum('visibility', ['private', 'public'])->default('private');
            $table->string('slug')->unique();
            $table->enum('status', ['approve', 'reject', 'process', 'revision'])->default('process');
            $table->string('author_name');
            $table->string('author_nim');
            $table->unsignedBigInteger('study_program_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors')->cascadeOnDelete();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->cascadeOnDelete();

            // Composite index untuk filter tetap
            $table->index(['status', 'visibility']);
            // Composite index untuk pencarian utama
            $table->index(['title', 'author_name', 'year']);
            // Index terpisah untuk fleksibilitas
            $table->index('title');
            $table->index('author_name');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_data');
    }
};

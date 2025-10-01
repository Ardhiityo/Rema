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
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('abstract');
            $table->string('file_path');
            $table->enum('type', ['final_project', 'thesis', 'manual_book']);
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->dateTime('published_at');
            $table->year('year');
            $table->string('slug')->unique();
            $table->enum('status', ['approve', 'reject', 'pending', 'revision']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};

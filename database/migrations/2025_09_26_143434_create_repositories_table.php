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
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->enum('visibility', ['private', 'protected', 'public'])->default('private');
            $table->string('slug')->unique();
            $table->enum('status', ['approve', 'reject', 'pending', 'revision'])->default('pending');
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

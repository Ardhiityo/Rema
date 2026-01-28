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
        Schema::create('meta_data_category', function (Blueprint $table) {
            $table->string('file_path');
            $table->unsignedBigInteger('meta_data_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('meta_data_id')->references('id')->on('meta_data')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->primary(['category_id', 'meta_data_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_data_category');
    }
};

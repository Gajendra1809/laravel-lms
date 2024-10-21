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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('title', 100);
            $table->string('author', 100);
            $table->string('isbn', 17)->unique();
            $table->date('published_date');
            $table->enum('available', ['1', '0'])->default('1')->comment('1: Available, 0: Unavailable')->index();
            $table->foreignId('admin_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 200);
            $table->longText('body');
            $table->enum('status', ['draft','pending','published','rejected'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            
            $table->index(['status', 'published_at'], 'idx_articles_status_published_at');
            $table->index(['user_id', 'status'], 'idx_articles_user_status');
            
        });
    }
    public function down(): void {
        Schema::dropIfExists('articles');
    }
};

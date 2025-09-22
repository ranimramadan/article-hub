<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id','article_id'], 'uniq_like_user_article');
            $table->index('created_at', 'idx_likes_created');
        });
    }
    public function down(): void {
        Schema::dropIfExists('likes');
    }
};

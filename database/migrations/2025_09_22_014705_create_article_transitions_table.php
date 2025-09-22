<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('article_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->enum('from_status', ['draft','pending','published','rejected'])->nullable();
            $table->enum('to_status',   ['draft','pending','published','rejected']);
            $table->foreignId('acted_by')->constrained('users')->cascadeOnDelete();
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->index(['article_id','created_at'], 'idx_transitions_article_created');
            $table->index(['acted_by','created_at'],   'idx_transitions_actor_created');
        });
    }
    public function down(): void {
        Schema::dropIfExists('article_transitions');
    }
};

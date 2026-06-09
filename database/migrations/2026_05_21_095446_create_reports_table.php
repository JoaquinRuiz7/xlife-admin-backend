<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->text('report');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reported_user_id');
            $table->enum('type', ['spam', 'harassment', 'inappropriate', 'copyright', 'misinformation']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['pending', 'in_review', 'resolved', 'dismissed']);
            $table->text('moderator_notes')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('reported_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

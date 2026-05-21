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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('ip_address')->nullable();
            $table->enum('event', [
                'password_reset_requested',
                'password_reset_completed',
                'user_blocked',
                'two_factor_enabled',
                'two_factor_disabled',
                'permission_denied',
                'suspicious_activity',
            ]);
            $table->enum('source', ['admin_panel', 'api', 'auth'])->nullable();
            $table->enum('severity', [
                'info',
                'warning',
                'high',
            ]);
            $table->timestamps();
            $table->index('severity');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};

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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('ip_address');
            $table->enum('event', [
                'failed_login_attempt',
                'ip_block_automatically',
                'password_reset_required',
                'suspicious_activity',
                'new_device_login',
                'account_lockout',
            ]);
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

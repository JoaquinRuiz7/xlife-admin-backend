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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cellphone')->unique()->after('password');
            $table->integer('country_id')
                ->after('cellphone');
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->restrictOnDelete();
            $table->dateTime('last_login_at')
                ->nullable()
                ->after('country_id');
            $table->enum('status', ['active', 'pending', 'suspended', 'disabled'])
                ->default('pending')
                ->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropForeign('users_country_id_foreign'); sqlite does not support this so i leave it commented.
            $table->dropColumn('country_id');
            $table->dropColumn('last_login_at');
            $table->dropUnique(['cellphone']);
            $table->dropColumn('cellphone');
        });
    }
};

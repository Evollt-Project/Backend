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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->integer('role')->default(1);
            $table->string('surname')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('job')->nullable();
            $table->float('balance')->default(0);
            $table->boolean('mail_approve')->default(false);
            $table->string('avatar')->nullable();
            $table->string('telegram')->nullable();
            $table->string('github')->nullable();
            $table->string('vk')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->boolean('privacy')->default(false);
            $table->date('date_of_birth')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('short_content')->nullable();
            $table->text('what_learn_content')->nullable();
            $table->text('about_content')->nullable();
            $table->text('for_who_content')->nullable();
            $table->text('start_content')->nullable();
            $table->text('how_learn_content')->nullable();
            $table->text('what_give_content')->nullable();
            $table->integer('recommended_load')->default(1);
            $table->string('avatar')->nullable();
            $table->boolean('has_certificate')->default(false);
            $table->foreignId('level_id')->default(1)->constrained();
            $table->foreignId('language_id')->default(1)->constrained();
            $table->integer('time')->nullable();
            $table->integer('price')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('certificate_type_id')->default(1)->constrained();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

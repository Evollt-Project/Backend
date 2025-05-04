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
        Schema::create('test_variants', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('test_step_id')->constrained()->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->string('explain');
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_variants');
    }
};

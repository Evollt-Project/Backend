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
        Schema::create('match_task_seconds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('match_task_first_id')->constrained()->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->foreignId('match_task_step_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_task_seconds');
    }
};

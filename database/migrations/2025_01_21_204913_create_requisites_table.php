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
        Schema::create('requisites', function (Blueprint $table) {
            $table->id();
            $table->integer('nalog_status');
            $table->foreignId('user_id')->uniqid()->constrained();
            $table->string('passport');
            $table->string('legal_address');
            $table->date('date_of_birth');
            $table->string('inn');
            $table->string('fio');
            $table->string('bik');
            $table->string('bank');
            $table->string('payment_account');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisites');
    }
};

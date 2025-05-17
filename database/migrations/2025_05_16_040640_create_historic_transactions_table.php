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
        Schema::create('historic_transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type_transaction')->comment('0- Depósito | 1- Transferência | 2- Saque');
            $table->tinyInteger('status')->comment('0- Finalizado | 1- Revertido');
            $table->integer('balance')->comment('Valor em centavos');

            $table->unsignedBigInteger('responsible_user')->comment('usuário responsável por executar transação');
            $table->foreign('responsible_user')->references('id')->on('users');

            $table->unsignedBigInteger('beneficiary_user')->nullable()->comment('usuário beneficiário da transação usuário que recebe a transação)');
            $table->foreign('beneficiary_user')->references('id')->on('users');

            $table->unsignedBigInteger('reverted_user')->nullable()->comment('Usuário responsável por reverter a transação');
            $table->foreign('reverted_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historic_transactions');
    }
};

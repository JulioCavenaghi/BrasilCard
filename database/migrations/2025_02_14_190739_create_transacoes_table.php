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
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_conta', 12);
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->integer('tipo');
            $table->string('transacao_origem');
            $table->string('transacao_destino');
            $table->boolean('transacao_revertida')->default(0);
            $table->timestamps();

            $table->foreign('numero_conta')->references('numero_conta')->on('contas_bancarias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes');
    }
};

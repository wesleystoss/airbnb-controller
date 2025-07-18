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
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['ativa', 'cancelada', 'expirada', 'pendente'])->default('pendente');
            $table->date('data_inicio');
            $table->date('data_expiracao');
            $table->string('payment_id')->nullable(); // ID do pagamento do Mercado Pago
            $table->decimal('valor', 10, 2)->default(39.90);
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['user_id', 'status']);
            $table->index('data_expiracao');
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};

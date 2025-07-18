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
        Schema::table('assinaturas', function (Blueprint $table) {
            $table->integer('tentativas_cobranca')->default(0)->after('valor');
            $table->timestamp('ultima_tentativa_cobranca')->nullable()->after('tentativas_cobranca');
            $table->timestamp('proxima_tentativa_cobranca')->nullable()->after('ultima_tentativa_cobranca');
            $table->enum('status_cobranca', ['sucesso', 'falha', 'pendente'])->default('pendente')->after('proxima_tentativa_cobranca');
            $table->text('motivo_falha')->nullable()->after('status_cobranca');
            
            // Índices para melhor performance
            $table->index('status_cobranca');
            $table->index('proxima_tentativa_cobranca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            $table->dropIndex(['status_cobranca']);
            $table->dropIndex(['proxima_tentativa_cobranca']);
            $table->dropColumn([
                'tentativas_cobranca',
                'ultima_tentativa_cobranca',
                'proxima_tentativa_cobranca',
                'status_cobranca',
                'motivo_falha'
            ]);
        });
    }
};

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
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'funcionario',    // quem trabalha dentro do tenant
                'cliente',        // usuário que consome o serviço (paciente, cliente, aluno, etc.)
                'fornecedor',     // empresas/pessoas que fornecem serviço/produto
                'parceiro',       // parceiro comercial, integrador, canal
                'owner',          // dono/admin do tenant (pode ser o mesmo que o admin)
                'representante',  // representante de venda, vendedor externo
                'autonomo',       // profissional autônomo ligado ao tenant
            ]);
            $table->enum('status', ['ativo', 'inativo', 'pendente'])->default('ativo');
            $table->string('cargo')->nullable();  // ex: Gerente Financeiro
            $table->unique(['user_id', 'tenant_id', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};

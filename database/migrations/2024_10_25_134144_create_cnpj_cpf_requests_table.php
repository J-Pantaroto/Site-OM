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
        Schema::create('cnpj_cpf_requests', function (Blueprint $table) {
            $table->id();
            $table->string('cpf_cnpj',14)->unique();
            $table->json('response'); // Campo para armazenar a resposta da API em formato JSON
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cnpj_cpf_requests');
    }
};

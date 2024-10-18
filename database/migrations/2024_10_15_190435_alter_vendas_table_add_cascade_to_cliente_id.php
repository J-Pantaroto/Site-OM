<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']); // Remove a antiga FK q n dropava em CASCATA
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade'); // Adiciona a FK que ira remover as vendas do cliente em CASCATA
        });
    }
    
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']); // Reverte para a FK original
            $table->foreign('cliente_id')->references('id')->on('users');
        });
    }
    
};

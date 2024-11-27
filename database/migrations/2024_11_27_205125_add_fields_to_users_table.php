<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos de endereço
            $table->unsignedBigInteger('state_id')->nullable()->after('email'); // Estado (relacionado à tabela states)
            $table->unsignedBigInteger('city_id')->nullable()->after('state_id'); // Cidade (relacionado à tabela cities)
            $table->string('address')->nullable()->after('city_id'); // Endereço
            $table->string('house_number')->nullable()->after('address'); // Número da casa
            $table->string('complement')->nullable()->after('house_number'); // Complemento
            $table->string('neighborhood')->nullable()->after('complement'); // Bairro
            $table->string('zip_code', 9)->nullable()->after('neighborhood'); // CEP

            // Índices e chaves estrangeiras
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn([
                'state_id',
                'city_id',
                'address',
                'house_number',
                'complement',
                'neighborhood',
                'zip_code',
            ]);
        });
    }
};


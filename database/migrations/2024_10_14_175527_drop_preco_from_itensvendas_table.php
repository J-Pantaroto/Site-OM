<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPrecoFromItensvendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itensvendas', function (Blueprint $table) {
            $table->dropColumn('preco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itensvendas', function (Blueprint $table) {
            $table->decimal('preco', 8, 2)->nullable(); // Adiciona a coluna novamente se precisar reverter
        });
    }
}

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
        Schema::table('itensvendas', function (Blueprint $table) {
            $table->decimal('preco', 10, 2)->default(0)->after('quantidade');
        });
    }
    
    public function down()
    {
        Schema::table('itensvendas', function (Blueprint $table) {
            $table->dropColumn('preco');
        });
    }
    
};

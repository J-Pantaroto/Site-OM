<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->default(0)->after('data_venda');
        });
    }
    
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
    
};

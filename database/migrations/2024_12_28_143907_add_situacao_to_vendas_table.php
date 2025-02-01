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
            $table->string('A_SITU')->nullable()->after('total');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('A_SITU');
        });
    }
};

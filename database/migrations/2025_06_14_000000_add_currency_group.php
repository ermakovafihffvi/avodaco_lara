<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('currency', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
    }

    public function down()
    {
        Schema::table('currency', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('currency_group_id_foreign');
        });
    }
};
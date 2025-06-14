<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('category_exp', function (Blueprint $table) {
            $table->dropUnique(['str_id']);
        });
        Schema::table('currency', function (Blueprint $table) {
            $table->dropUnique(['str_id']);
        });
        Schema::table('category_savings', function (Blueprint $table) {
            $table->dropUnique(['str_id']);
        });
        Schema::table('saving_source', function (Blueprint $table) {
            $table->dropUnique(['str_id']);
        });
    }

    public function down()
    {
        Schema::table('category_exp', function (Blueprint $table) {
            $table->unique('str_id');
        });
        Schema::table('currency', function (Blueprint $table) {
            $table->unique('str_id');
        });
        Schema::table('category_savings', function (Blueprint $table) {
            $table->unique('str_id');
        });
        Schema::table('saving_source', function (Blueprint $table) {
            $table->unique('str_id');
        });
    }
};
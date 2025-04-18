<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('category_exp', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
        Schema::table('category_savings', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
        Schema::table('income', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
        Schema::table('savings', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
        Schema::table('saving_source', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('user_group');
        });
    }

    public function down(): void
    {
        Schema::table('category_exp', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('category_exp_group_id_foreign');
        });
        Schema::table('category_savings', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('category_savings_group_id_foreign');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('expenses_group_id_foreign');
        });
        Schema::table('income', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('income_group_id_foreign');
        });
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('savings_group_id_foreign');
        });
        Schema::table('saving_source', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('saving_source_group_id_foreign');
        });
    }
};

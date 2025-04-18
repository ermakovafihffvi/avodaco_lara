<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->unsignedBigInteger('current_group_id')->nullable();
            $table->foreign('current_group_id')->references('id')->on('user_group');
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('current_group_id');
            $table->dropForeign('user_current_group_id_foreign');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('current_state_categories', function (Blueprint $table) {
            $table->id();
            $table->string('str_id');
            $table->string('title');
            $table->text('desc');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('currency_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('group_id')->references('id')->on('user_group')->cascadeOnDelete();
            $table->foreign('currency_id')->references('id')->on('currency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('current_state_categories');
    }
};

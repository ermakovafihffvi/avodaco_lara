<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repeatable_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('group_id');
            $table->boolean('is_every_month')->nullable();
            $table->integer('times')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('expense_id')->references('id')->on('expenses');
            $table->foreign('group_id')->references('id')->on('user_group')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repeatable_expenses');
    }
};
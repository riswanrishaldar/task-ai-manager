<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('priority', 20);
    $table->string('status', 20)->default('pending');
    $table->date('due_date')->nullable();
    $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
    $table->text('ai_summary')->nullable();
    $table->string('ai_priority', 20)->nullable();
    $table->timestamps();

    $table->index(['status', 'priority']);
    $table->index('assigned_to');
    $table->index('due_date');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

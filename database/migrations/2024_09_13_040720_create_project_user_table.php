<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->enum('role', ['manager', 'developer', 'tester']);
            $table->integer('contribution_hours')->default(0);
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['project_id', 'user_id']);
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};

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
        Schema::create('hexa_roles', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->foreignId('team_id')->nullable();
            $table->string('created_by_name')->nullable();
            $table->jsonb('access')->nullable();
            $table->jsonb('gates')->nullable();
            $table->jsonb('checkall')->nullable();
            $table->string('guard')->default('web');
            $table->timestamps();
        });

        Schema::create('hexa_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('hexa_roles');
            $table->foreignId('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hexa_roles');
        Schema::dropIfExists('hexa_role_user');
    }
};

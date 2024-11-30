<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Clave única en el campo `email`
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id') // Configuración de clave foránea
            ->constrained('roles') // Asegúrate de que la tabla `roles` existe antes de esta migración
            ->cascadeOnUpdate() // Reemplaza `onUpdate` con este método
            ->cascadeOnDelete(); // Reemplaza `onDelete` con este método
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

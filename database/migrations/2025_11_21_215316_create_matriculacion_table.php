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
        Schema::create('matriculacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_curso');
            $table->text('nro_matricula');
            $table->enum('estado_matriculacion', ['ACTIVO','COMPLETADO','CANCELADO'])->default('ACTIVO');
            $table->timestamps();
            $table->date('fecha_matriculacion')->nullable();
            $table->foreign('id_estudiante')->references('id')->on('estudiante')->onDelete('restrict');
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculacion');
    }
};

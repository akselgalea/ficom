<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('apellidos');
            $table->string('nombres');
            $table->string('email')->unique()->nullable()->default(null);
            $table->enum('genero', ['M', 'F', 'N'])->default('N');
            $table->string('rut')->unique()->nullable()->default(null);
            $table->integer('edad')->nullable()->default(null);
            $table->date('fecha_nacimiento')->nullable()->default(null);
            $table->string('nacionalidad')->nullable()->default(null);
            $table->string('enfermedades')->nullable()->default(null);
            $table->string('persona_emergencia')->nullable()->default(null);
            $table->string('telefono_emergencia')->nullable()->default(null);
            $table->string('dv')->nullable()->default(null);
            $table->boolean('es_nuevo')->default(false);
            $table->enum('prioridad', array('alumno regular', 'prioritario', 'nuevo prioritario'))->default('alumno regular');
            $table->string('email_institucional')->unique()->nullable();
            $table->string('telefono')->default('')->nullable();
            $table->string('direccion')->default('')->nullable();
            $table->json('apoderados')->default(null)->nullable();
            $table->foreignId('curso_id')->nullable()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('beca_id')->nullable()->nullOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
};

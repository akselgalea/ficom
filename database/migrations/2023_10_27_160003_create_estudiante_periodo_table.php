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
        Schema::create('estudiante_periodo', function (Blueprint $table) {
            $table->id();
            $table->year('periodo');
            $table->enum('prioridad', array('alumno regular', 'prioritario', 'nuevo prioritario'))->default('alumno regular');
            $table->foreignId('estudiante_id')->references('id')->on('estudiantes');
            $table->foreignId('curso_id')->references('id')->on('cursos');
            $table->foreignId('beca_id')->nullable()->references('id')->on('becas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiante_periodo');
    }
};

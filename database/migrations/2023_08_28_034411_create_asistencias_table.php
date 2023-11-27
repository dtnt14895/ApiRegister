<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seleccion_id');
            $table->date('dia');
            $table->char('abreviacion', 1);
            $table->foreign('seleccion_id')->references('id')->on('selecciones');
            $table->timestamps();
            $table->unique(['dia', 'seleccion_id']);
        });

        // Agregar restricción de verificación
        DB::statement('ALTER TABLE asistencias ADD CONSTRAINT check_abreviacion CHECK (abreviacion IN ("a", "t", "f"))');
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
      
    }
};

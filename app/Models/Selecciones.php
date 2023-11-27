<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Selecciones extends Model
{
    use HasFactory;

    public function alumno() :belongsTo
    {
        return $this->belongsTo(Alumnos::class, 'alumno_id');
    }

    public function curso() :belongsTo
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cursos extends Model
{
    use HasFactory;

   

    public function docente() :belongsTo
    {
        return $this->belongsTo(Docentes::class, 'docente_id');
    }
    
}

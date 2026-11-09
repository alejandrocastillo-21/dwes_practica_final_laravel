<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Columnas de la tabla "projects" que se pueden rellenar
    // desde el código (con create() y update()).
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'deadline',
        'status',
    ];

    // Relación 1:N (inversa): un proyecto PERTENECE A un usuario.
    // Uso: $project->owner->name  (nombre del propietario)
    // Laravel usa la columna "user_id" de la tabla projects.
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación N:N: un proyecto tiene MUCHOS usuarios participantes.
    // Uso: $project->members  (colección de usuarios)
    // withPivot() permite leer las columnas extra de la tabla "project_user".
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'contribution_hours');
    }
}

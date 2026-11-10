<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    // Regla "update": ¿puede este usuario modificar este proyecto?
    // Laravel rellena los parámetros solo:
    //   $user    -> el usuario con la sesión iniciada
    //   $project -> el proyecto sobre el que se pregunta
    // Devuelve true únicamente si el usuario es el propietario.
    public function update(User $user, Project $project): bool
    {
        return $user->id == $project->user_id;
    }

    // Regla "delete": ¿puede este usuario borrar este proyecto?
    // Por ahora es la misma condición que update.
    public function delete(User $user, Project $project): bool
    {
        return $user->id == $project->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}

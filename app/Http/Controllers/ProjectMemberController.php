<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\User;

class ProjectMemberController extends Controller
{
    // Añade un participante a un proyecto.
    // Ruta: POST /projects/{project}/members  (projects.members.store)
    public function store(Request $request, Project $project)
    {
        // Solo el propietario puede gestionar participantes.
        // Reutilizamos la regla "update" de ProjectPolicy.
        Gate::authorize('update', $project);

        // Validamos los datos del formulario.
        $datos = $request->validate([
            'user_id' => 'required|exists:users,id', // debe existir en la tabla users
            'role' => 'required|max:50',
            'contribution_hours' => 'nullable|integer|min:0',   // opcional, número entero >= 0
        ]);

        // El propietario no puede añadirse como participante.
        if ($datos['user_id'] == $project->user_id) {
            return back()->withErrors(['user_id' => 'El propietario ya forma parte del proyecto.']);
        }

        // Comprobamos que el usuario no esté ya en el proyecto.
        // members() con paréntesis permite hacer consultas sobre la relación.
        if ($project->members()->where('user_id', $datos['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'Este usuario ya participa en el proyecto.']);
        }

        // attach() inserta la fila en la tabla pivote "project_user"
        // con el id del usuario y los datos extra.
        $project->members()->attach($datos['user_id'], [
            'role' => $datos['role'],
            // Si no se indican horas, guardamos 0
            'contribution_hours' => $datos['contribution_hours'] ?? 0,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante añadido correctamente.');
    }

    // Modifica el rol y las horas de un participante.
    // Ruta: PUT /projects/{project}/members/{member}  (projects.members.update)
    public function update(Request $request, Project $project, User $member)
    {
        // Solo el propietario puede modificar participantes.
        Gate::authorize('update', $project);

        // Validamos los dos campos que se pueden modificar.
        $datos = $request->validate([
            'role' => 'required|max:50',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        // Modifica la fila de la tabla pivote "project_user"
        // que une este proyecto con este usuario.
        $project->members()->updateExistingPivot($member->id, [
            'role' => $datos['role'],
            'contribution_hours' => $datos['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante actualizado correctamente.');
    }

    // Quita a un participante del proyecto.
    // Ruta: DELETE /projects/{project}/members/{member}  (projects.members.destroy)
    public function destroy(Project $project, User $member)
    {
        // Solo el propietario puede quitar participantes.
        Gate::authorize('update', $project);

        // detach() borra la fila de la tabla pivote "project_user"
        // que une este proyecto con este usuario.
        // El usuario sigue existiendo en la tabla "users".
        $project->members()->detach($member->id);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante eliminado del proyecto.');
    }
}

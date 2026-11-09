<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Listado de proyectos (paginado).
     */
     public function index()
    {
        // Project::all() hace "SELECT * FROM projects"
        // y devuelve una colección con todos los proyectos.
        $projects = Project::all();

        // view('projects.index') busca el archivo
        // resources/views/projects/index.blade.php
        // El array pasa la variable $projects a la vista.
        return view('projects.index', ['projects' => $projects]);
    }

    // Muestra el detalle de un proyecto.
    // Ruta: GET /projects/{project}  (projects.show)
    public function show(Project $project)
    {
        // Al escribir "Project $project" en el parámetro, Laravel
        // coge el id de la URL (/projects/3), busca ese proyecto
        // en la base de datos y nos lo entrega ya cargado.
        // Si no existe, devuelve un 404 automáticamente.

        // Pasamos el proyecto a la vista resources/views/projects/show.blade.php
        return view('projects.show', ['project' => $project]);
    }

    // Muestra el formulario para crear un proyecto.
// Ruta: GET /projects/create  (projects.create)
public function create()
{
    // No necesita datos: solo devuelve la vista con el formulario vacío.
    return view('projects.create');
}

// Guarda el proyecto nuevo en la base de datos.
// Ruta: POST /projects  (projects.store)
public function store(Request $request)
{
    // $request contiene todos los datos enviados por el formulario.

    // validate() comprueba los datos. Si alguna regla falla, Laravel
    // vuelve automáticamente al formulario con los mensajes de error
    // y sin ejecutar el resto del método.
    $datos = $request->validate([
        'title'       => 'required|max:255',   // obligatorio, máximo 255 caracteres
        'description' => 'nullable',           // opcional
        'deadline'    => 'nullable|date',      // opcional, pero si viene debe ser una fecha
        'status'      => 'required|in:pendiente,en_progreso,completado', // uno de estos tres valores
    ]);

    // Añadimos el propietario: el usuario que tiene la sesión iniciada.
    // Lo ponemos nosotros (y no en el formulario) para que nadie
    // pueda crear proyectos a nombre de otro usuario.
    $datos['user_id'] = $request->user()->id;

    // create() inserta una fila en la tabla "projects".
    // Solo acepta las columnas listadas en $fillable del modelo.
    $project = Project::create($datos);

    // Redirigimos al detalle del proyecto recién creado.
    // with() guarda un mensaje "flash": se muestra una vez y desaparece.
    return redirect()->route('projects.show', $project)
        ->with('success', 'Proyecto creado correctamente.');
}   

    /**
     * Formulario de edición.
     */
    public function edit(Project $project)
    {
        // Consulta ProjectPolicy::update(). Si falla, devuelve un 403.
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $project->update($this->validateProject($request));

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado.');
    }

    public function destroy(Project $project)
    {   
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto eliminado.');
    }

    /**
     * Reglas de validación compartidas por store() y update().
     */
    private function validateProject(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            // El estado debe ser una de las claves definidas en el modelo
            'status' => ['required', Rule::in(array_keys(Project::STATUSES))],
        ]);
    }
}
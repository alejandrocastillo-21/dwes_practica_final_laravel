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
            'title' => 'required|max:255',   // obligatorio, máximo 255 caracteres
            'description' => 'nullable',           // opcional
            'deadline' => 'nullable|date',      // opcional, pero si viene debe ser una fecha
            'status' => 'required|in:pendiente,en_progreso,completado', // uno de estos tres valores
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
    // Muestra el formulario de edición con los datos actuales.
// Ruta: GET /projects/{project}/edit  (projects.edit)
    public function edit(Project $project)
    {
        // Laravel ya ha buscado el proyecto por el id de la URL.
        // Se lo pasamos a la vista para rellenar el formulario.
        return view('projects.edit', ['project' => $project]);
    }

    // Guarda los cambios del proyecto.
    // Ruta: PUT /projects/{project}  (projects.update)
    public function update(Request $request, Project $project)
    {
        // Mismas reglas de validación que en store().
        $datos = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pendiente,en_progreso,completado',
        ]);

        // update() modifica la fila de ESTE proyecto con los datos validados.
        // No tocamos user_id: el propietario no cambia al editar.
        $project->update($datos);

        // Volvemos al detalle con un mensaje de éxito.
        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    // Elimina un proyecto.
    // Ruta: DELETE /projects/{project}  (projects.destroy)
    public function destroy(Project $project)
    {
        // delete() borra la fila de este proyecto en la tabla "projects".
        // Las filas de la tabla pivote "project_user" asociadas a este
        // proyecto se borran solas, gracias al cascadeOnDelete()
        // que pusimos en la migración.
        $project->delete();

        // Tras borrar, el detalle ya no existe, así que volvemos al listado.
        return redirect()->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
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
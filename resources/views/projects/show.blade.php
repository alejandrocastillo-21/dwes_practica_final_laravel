<x-app-layout>
    {{-- Mismo bloque que en el listado: elige color y texto según el estado --}}
    @php
        if ($project->status == 'pendiente') {
            $colorEstado = 'bg-yellow-100 text-yellow-800';
            $textoEstado = 'Pendiente';
        } elseif ($project->status == 'en_progreso') {
            $colorEstado = 'bg-blue-100 text-blue-800';
            $textoEstado = 'En progreso';
        } else {
            $colorEstado = 'bg-green-100 text-green-800';
            $textoEstado = 'Completado';
        }
    @endphp

    {{-- ========== CABECERA ========== --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- Título + etiqueta de estado --}}
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-800">{{ $project->title }}</h2>
                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $colorEstado }}">
                    {{ $textoEstado }}
                </span>
            </div>

            {{-- Botones: cada uno aparece solo si su regla de la Policy lo permite --}}
            <div class="flex items-center gap-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Editar
                    </a>
                @endcan

                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}"
                          onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Eliminar
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Recuadro con TODOS los errores de validación (si los hay) --}}
            @if ($errors->any())
                <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ========== TARJETA: INFORMACIÓN ========== --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <p class="text-gray-700">{{ $project->description ?? 'Sin descripción.' }}</p>

                {{-- Cuadrícula de 3 datos, separada por una línea --}}
                <div class="mt-6 grid gap-4 border-t pt-4 text-sm md:grid-cols-3">
                    <div>
                        <p class="text-gray-500">Propietario</p>
                        <p class="font-medium text-gray-900">{{ $project->owner->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha límite</p>
                        <p class="font-medium text-gray-900">{{ $project->deadline ?? 'Sin fecha' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Participantes</p>
                        {{-- count() cuenta los elementos de la colección members --}}
                        <p class="font-medium text-gray-900">{{ $project->members->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- ========== TARJETA: PARTICIPANTES ========== --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Participantes</h3>

                {{-- overflow-x-auto: si la tabla no cabe, tiene scroll en lugar de romper el diseño --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-500">
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2">Rol</th>
                                <th class="px-3 py-2">Horas</th>
                                @can('update', $project)
                                    <th class="px-3 py-2">Acciones</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($project->members as $member)
                                <tr class="border-b last:border-0">
                                    <td class="px-3 py-3 font-medium text-gray-900">{{ $member->name }}</td>
                                    {{-- pivot da acceso a las columnas de la tabla project_user --}}
                                    <td class="px-3 py-3">
                                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                            {{ $member->pivot->role }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">{{ $member->pivot->contribution_hours }} h</td>

                                    @can('update', $project)
                                        <td class="px-3 py-3">
                                            {{-- Los dos formularios (modificar y quitar) en una misma fila --}}
                                            <div class="flex items-center gap-3">

                                                {{-- Formulario: modificar rol y horas --}}
                                                <form method="POST"
                                                      action="{{ route('projects.members.update', [$project, $member]) }}"
                                                      class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')

                                                    <select name="role" class="rounded-lg border-gray-300 text-sm">
                                                        <option value="Desarrollador" @selected($member->pivot->role == 'Desarrollador')>Desarrollador</option>
                                                        <option value="Diseñador" @selected($member->pivot->role == 'Diseñador')>Diseñador</option>
                                                        <option value="Tester" @selected($member->pivot->role == 'Tester')>Tester</option>
                                                        <option value="Analista" @selected($member->pivot->role == 'Analista')>Analista</option>
                                                    </select>

                                                    <input type="number" name="contribution_hours" min="0"
                                                           value="{{ $member->pivot->contribution_hours }}"
                                                           class="w-20 rounded-lg border-gray-300 text-sm">

                                                    <button type="submit"
                                                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
                                                        Guardar
                                                    </button>
                                                </form>

                                                {{-- Formulario: quitar participante --}}
                                                <form method="POST"
                                                      action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                                      onsubmit="return confirm('¿Quitar a este participante del proyecto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                                        Quitar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-6 text-center text-gray-500">
                                        Aún no hay participantes en este proyecto.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========== TARJETA: AÑADIR PARTICIPANTE (solo propietario) ========== --}}
            @can('update', $project)
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Añadir participante</h3>

                    @if ($users->isNotEmpty())
                        <form method="POST" action="{{ route('projects.members.store', $project) }}"
                              class="grid gap-4 md:grid-cols-4 md:items-end">
                            @csrf

                            {{-- Usuario --}}
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-lg border-gray-300">
                                    <option value="">Selecciona...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                                <select id="role" name="role" class="mt-1 block w-full rounded-lg border-gray-300">
                                    <option value="Desarrollador" @selected(old('role') == 'Desarrollador')>Desarrollador</option>
                                    <option value="Diseñador" @selected(old('role') == 'Diseñador')>Diseñador</option>
                                    <option value="Tester" @selected(old('role') == 'Tester')>Tester</option>
                                    <option value="Analista" @selected(old('role') == 'Analista')>Analista</option>
                                </select>
                            </div>

                            {{-- Horas --}}
                            <div>
                                <label for="contribution_hours" class="block text-sm font-medium text-gray-700">Horas</label>
                                <input type="number" id="contribution_hours" name="contribution_hours" min="0"
                                       value="{{ old('contribution_hours', 0) }}"
                                       class="mt-1 block w-full rounded-lg border-gray-300">
                            </div>

                            <button type="submit"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                                Añadir
                            </button>
                        </form>
                    @else
                        <p class="text-sm text-gray-500">No quedan usuarios disponibles para añadir.</p>
                    @endif
                </div>
            @endcan

            <a href="{{ route('projects.index') }}" class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>
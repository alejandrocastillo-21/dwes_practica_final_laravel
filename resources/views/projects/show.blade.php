<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ $project->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4">
            {{-- session('success') existe solo justo después de crear el proyecto --}}
            @if (session('success'))
                <div class="rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tarjeta con los datos del proyecto --}}
            <div class="rounded bg-white p-6 shadow">
                <p class="text-gray-700">{{ $project->description }}</p>

                <p class="mt-4 text-sm text-gray-600">
                    Propietario: {{ $project->owner->name }}
                </p>
                <p class="text-sm text-gray-600">
                    Estado: {{ $project->status }}
                </p>
                <p class="text-sm text-gray-600">
                    Fecha límite: {{ $project->deadline }}
                </p>
            </div>

            {{-- Tarjeta con la tabla de participantes --}}
            <div class="rounded bg-white p-6 shadow">
                <h3 class="mb-4 text-lg font-semibold">Participantes</h3>

                {{-- Errores de validación al modificar un participante --}}
                @error('role')
                    <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('contribution_hours')
                    <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Rol</th>
                            <th class="py-2">Horas</th>
                            {{-- La columna de acciones solo existe para el propietario --}}
                            @can('update', $project)
                                <th class="py-2">Modificar</th>
                                <th class="py-2">Quitar</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($project->members as $member)
                            <tr class="border-b">
                                <td class="py-2">{{ $member->name }}</td>
                                <td class="py-2">{{ $member->pivot->role }}</td>
                                <td class="py-2">{{ $member->pivot->contribution_hours }}</td>

                                @can('update', $project)
                                    <td class="py-2">
                                        {{-- Un formulario por participante.
                                        Se envía a projects.members.update con el proyecto y el usuario. --}}
                                        <form method="POST" action="{{ route('projects.members.update', [$project, $member]) }}"
                                            class="flex items-center gap-2">
                                            @csrf
                                            {{-- Simulamos el método PUT --}}
                                            @method('PUT')

                                            {{-- Rol: seleccionamos el valor actual de la pivote --}}
                                            <select name="role" class="rounded border-gray-300 text-sm">
                                                <option value="Desarrollador" @selected($member->pivot->role == 'Desarrollador')>
                                                    Desarrollador</option>
                                                <option value="Diseñador" @selected($member->pivot->role == 'Diseñador')>Diseñador
                                                </option>
                                                <option value="Tester" @selected($member->pivot->role == 'Tester')>Tester</option>
                                                <option value="Analista" @selected($member->pivot->role == 'Analista')>Analista
                                                </option>
                                            </select>

                                            {{-- Horas: valor actual de la pivote --}}
                                            <input type="number" name="contribution_hours" min="0"
                                                value="{{ $member->pivot->contribution_hours }}"
                                                class="w-20 rounded border-gray-300 text-sm">

                                            <button type="submit" class="rounded bg-indigo-600 px-3 py-1 text-white">
                                                Guardar
                                            </button>
                                        </form>
                                    </td>

                                    <td class="py-2">
                                        {{-- Formulario que envía DELETE a projects.members.destroy.
                                        Pasamos el proyecto y el usuario, en ese orden. --}}
                                        <form method="POST"
                                            action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                            onsubmit="return confirm('¿Quitar a este participante del proyecto?')">
                                            @csrf
                                            {{-- Simulamos el método DELETE --}}
                                            @method('DELETE')

                                            <button type="submit" class="rounded bg-red-600 px-3 py-1 text-white">
                                                Quitar
                                            </button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500">Aún no hay participantes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('projects.edit', $project) }}" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white">
                Editar proyecto
            </a>
            {{-- Formulario que envía la petición DELETE a projects.destroy --}}
            <form method="POST" action="{{ route('projects.destroy', $project) }}"
                onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
                @csrf
                {{-- Los navegadores solo envían GET/POST: esto lo convierte en DELETE --}}
                @method('DELETE')

                <button type="submit" class="rounded bg-red-600 px-4 py-2 text-sm text-white">
                    Eliminar proyecto
                </button>
            </form>
            {{-- Solo el propietario ve el formulario (usa la Policy, igual que el botón Editar) --}}
            @can('update', $project)
                <div class="rounded bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Añadir participante</h3>

                    {{-- isNotEmpty() es true si hay usuarios disponibles --}}
                    @if ($users->isNotEmpty())
                        {{-- Enviamos a projects.members.store pasando el proyecto --}}
                        <form method="POST" action="{{ route('projects.members.store', $project) }}" class="space-y-4">
                            @csrf

                            {{-- Usuario --}}
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded border-gray-300">
                                    <option value="">Selecciona un usuario</option>
                                    @foreach ($users as $user)
                                        {{-- El valor enviado es el id; el texto visible, el nombre --}}
                                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                                <select id="role" name="role" class="mt-1 block w-full rounded border-gray-300">
                                    <option value="Desarrollador" @selected(old('role') == 'Desarrollador')>Desarrollador</option>
                                    <option value="Diseñador" @selected(old('role') == 'Diseñador')>Diseñador</option>
                                    <option value="Tester" @selected(old('role') == 'Tester')>Tester</option>
                                    <option value="Analista" @selected(old('role') == 'Analista')>Analista</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Horas (opcional) --}}
                            <div>
                                <label for="contribution_hours" class="block text-sm font-medium text-gray-700">Horas
                                    trabajadas</label>
                                <input type="number" id="contribution_hours" name="contribution_hours" min="0"
                                    value="{{ old('contribution_hours', 0) }}"
                                    class="mt-1 block w-full rounded border-gray-300">
                                @error('contribution_hours')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white">
                                Añadir participante
                            </button>
                        </form>
                    @else
                        {{-- Si ya están todos los usuarios dentro, no hay nadie que añadir --}}
                        <p class="text-sm text-gray-500">No quedan usuarios disponibles para añadir.</p>
                    @endif
                </div>
            @endcan
            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>
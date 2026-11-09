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

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Rol</th>
                            <th class="py-2">Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- members es la relación N:N del modelo Project --}}
                        @forelse ($project->members as $member)
                            <tr class="border-b">
                                <td class="py-2">{{ $member->name }}</td>
                                {{-- pivot da acceso a las columnas de project_user --}}
                                <td class="py-2">{{ $member->pivot->role }}</td>
                                <td class="py-2">{{ $member->pivot->contribution_hours }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500">
                                    Aún no hay participantes.
                                </td>
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
            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600">
                ← Volver al listado
            </a>
        </div>
    </div>
</x-app-layout>
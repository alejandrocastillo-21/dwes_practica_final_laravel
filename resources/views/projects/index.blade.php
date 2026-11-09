<x-app-layout>
    {{-- Slot "header": la cabecera que Breeze muestra bajo el menú --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Proyectos</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4">

            {{-- Enlace al formulario de crear (lo haremos en el paso 5) --}}
            <a href="{{ route('projects.create') }}"
               class="mb-6 inline-block rounded bg-indigo-600 px-4 py-2 text-white">
                Nuevo proyecto
            </a>

            {{-- Cuadrícula: 1 columna en móvil, 3 en pantallas grandes --}}
            <div class="grid gap-6 md:grid-cols-3">

                {{-- Recorremos $projects, la variable que envía el controlador --}}
                @forelse ($projects as $project)
                    <div class="rounded bg-white p-6 shadow">
                        <h3 class="text-lg font-semibold">{{ $project->title }}</h3>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ $project->description }}
                        </p>

                        {{-- owner es la relación definida en el modelo Project --}}
                        <p class="mt-4 text-xs text-gray-500">
                            Propietario: {{ $project->owner->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Estado: {{ $project->status }}
                        </p>

                        {{-- route('projects.show', $project) genera /projects/{id} --}}
                        <a href="{{ route('projects.show', $project) }}"
                           class="mt-4 inline-block text-sm text-indigo-600">
                            Ver detalle
                        </a>
                    </div>
                @empty
                    {{-- Se muestra solo si no hay proyectos --}}
                    <p class="text-gray-500">Todavía no hay proyectos.</p>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
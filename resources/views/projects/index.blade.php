<x-app-layout>
    <x-slot name="header">
        {{-- Título a la izquierda y botón a la derecha --}}
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Proyectos</h2>
            <a href="{{ route('projects.create') }}"
               class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($projects as $project)

                    {{-- Según el estado, elegimos el color y el texto de la etiqueta --}}
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

                    {{-- Tarjeta. "hover:" aplica el estilo al pasar el ratón por encima --}}
                    <div class="flex flex-col rounded-xl bg-white p-6 shadow-sm transition hover:shadow-md">

                        {{-- Título y etiqueta de estado --}}
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $project->title }}</h3>
                            <span class="shrink-0 rounded-full px-3 py-1 text-xs font-medium {{ $colorEstado }}">
                                {{ $textoEstado }}
                            </span>
                        </div>

                        {{-- Descripción recortada a 100 caracteres --}}
                        <p class="mt-3 flex-1 text-sm text-gray-600">
                            {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                        </p>

                        {{-- Datos del proyecto --}}
                        <div class="mt-4 space-y-1 border-t pt-4 text-xs text-gray-500">
                            <p>Propietario: <span class="font-medium text-gray-700">{{ $project->owner->name }}</span></p>
                            <p>Fecha límite: <span class="font-medium text-gray-700">{{ $project->deadline ?? 'Sin fecha' }}</span></p>
                            {{-- members_count lo añade withCount() en el controlador --}}
                            <p>Participantes: <span class="font-medium text-gray-700">{{ $project->members_count }}</span></p>
                        </div>

                        <a href="{{ route('projects.show', $project) }}"
                           class="mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Ver detalle →
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500">Todavía no hay proyectos.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
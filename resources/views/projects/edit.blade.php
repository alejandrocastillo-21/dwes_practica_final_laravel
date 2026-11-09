<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Editar proyecto</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4">

            {{-- CAMBIO 1: la acción apunta a projects.update y lleva el proyecto --}}
            <form method="POST" action="{{ route('projects.update', $project) }}"
                  class="space-y-4 rounded bg-white p-6 shadow">

                @csrf

                {{-- CAMBIO 2: los navegadores solo envían GET o POST.
                     Esta directiva hace que Laravel lo trate como PUT. --}}
                @method('PUT')

                {{-- Título --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                    {{-- CAMBIO 3: old('campo', valor_actual).
                         Si hubo error de validación usa lo escrito;
                         si no, usa el valor guardado en el proyecto. --}}
                    <input type="text" id="title" name="title"
                           value="{{ old('title', $project->title) }}"
                           class="mt-1 block w-full rounded border-gray-300">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full rounded border-gray-300">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha límite --}}
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700">Fecha límite</label>
                    {{-- Un input type="date" exige el formato Y-m-d --}}
                    <input type="date" id="deadline" name="deadline"
                           value="{{ old('deadline', $project->deadline) }}"
                           class="mt-1 block w-full rounded border-gray-300">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300">
                        <option value="pendiente" @selected(old('status', $project->status) == 'pendiente')>Pendiente</option>
                        <option value="en_progreso" @selected(old('status', $project->status) == 'en_progreso')>En progreso</option>
                        <option value="completado" @selected(old('status', $project->status) == 'completado')>Completado</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center gap-4">
                    <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white">
                        Guardar cambios
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-600">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
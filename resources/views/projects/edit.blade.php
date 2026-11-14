<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Editar proyecto</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4">

            <div class="rounded-xl bg-white p-8 shadow-sm">

                <h3 class="text-lg font-semibold text-gray-900">Datos del proyecto</h3>
                <p class="mt-1 text-sm text-gray-500">Modifica la información y guarda los cambios.</p>

                {{-- CAMBIO 1: la acción apunta a projects.update y lleva el proyecto --}}
                <form method="POST" action="{{ route('projects.update', $project) }}" class="mt-6 space-y-5">
                    @csrf
                    {{-- CAMBIO 2: los navegadores solo envían GET o POST; esto lo convierte en PUT --}}
                    @method('PUT')

                    {{-- Título --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                        {{-- CAMBIO 3: old('campo', valor_actual).
                             Usa lo escrito si hubo error; si no, el valor guardado --}}
                        <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        {{-- Fecha límite --}}
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700">Fecha límite</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $project->deadline) }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pendiente" @selected(old('status', $project->status) == 'pendiente')>Pendiente</option>
                                <option value="en_progreso" @selected(old('status', $project->status) == 'en_progreso')>En progreso</option>
                                <option value="completado" @selected(old('status', $project->status) == 'completado')>Completado</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-t pt-5">
                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
                            Guardar cambios
                        </button>
                        {{-- CAMBIO 4: Cancelar vuelve al detalle de este proyecto --}}
                        <a href="{{ route('projects.show', $project) }}"
                           class="rounded-lg border border-gray-300 px-5 py-2 font-medium text-gray-700 hover:bg-gray-100">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
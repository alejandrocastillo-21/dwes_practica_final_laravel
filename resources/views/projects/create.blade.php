<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Nuevo proyecto</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4">

            {{-- action: a qué ruta se envían los datos. method: POST --}}
            <form method="POST" action="{{ route('projects.store') }}"
                  class="space-y-4 rounded bg-white p-6 shadow">

                {{-- Token de seguridad obligatorio en todos los formularios POST.
                     Sin él, Laravel rechaza el envío (error 419). --}}
                @csrf

                {{-- Título --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                    {{-- old('title') recupera lo que el usuario escribió si la validación falla --}}
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="mt-1 block w-full rounded border-gray-300">
                    {{-- @error muestra el mensaje de error de ese campo, si lo hay --}}
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full rounded border-gray-300">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha límite --}}
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700">Fecha límite</label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                           class="mt-1 block w-full rounded border-gray-300">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded border-gray-300">
                        {{-- @selected marca la opción elegida antes si hubo un error --}}
                        <option value="pendiente" @selected(old('status') == 'pendiente')>Pendiente</option>
                        <option value="en_progreso" @selected(old('status') == 'en_progreso')>En progreso</option>
                        <option value="completado" @selected(old('status') == 'completado')>Completado</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center gap-4">
                    <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white">
                        Crear proyecto
                    </button>
                    <a href="{{ route('projects.index') }}" class="text-sm text-gray-600">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
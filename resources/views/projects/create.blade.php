<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Nuevo proyecto</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4">

            {{-- Tarjeta que contiene el formulario --}}
            <div class="rounded-xl bg-white p-8 shadow-sm">

                <h3 class="text-lg font-semibold text-gray-900">Datos del proyecto</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Rellena la información básica. Podrás añadir participantes después.
                </p>

                <form method="POST" action="{{ route('projects.store') }}" class="mt-6 space-y-5">
                    {{-- Token de seguridad obligatorio en formularios POST --}}
                    @csrf

                    {{-- Título --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                        {{-- old('title') conserva lo escrito si la validación falla --}}
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha límite y estado, en dos columnas desde pantallas medianas --}}
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700">Fecha límite</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                {{-- @selected deja marcada la opción elegida antes si hubo un error --}}
                                <option value="pendiente" @selected(old('status') == 'pendiente')>Pendiente</option>
                                <option value="en_progreso" @selected(old('status') == 'en_progreso')>En progreso</option>
                                <option value="completado" @selected(old('status') == 'completado')>Completado</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones, separados del formulario por una línea --}}
                    <div class="flex items-center gap-3 border-t pt-5">
                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
                            Crear proyecto
                        </button>
                        <a href="{{ route('projects.index') }}"
                           class="rounded-lg border border-gray-300 px-5 py-2 font-medium text-gray-700 hover:bg-gray-100">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
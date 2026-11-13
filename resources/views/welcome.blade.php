<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    {{-- Carga el CSS (Tailwind) y el JS compilados por Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Fondo gris muy claro para toda la página --}}
<body class="bg-gray-50 text-gray-800 antialiased">

    {{-- ========== BARRA SUPERIOR ========== --}}
    <header class="bg-white shadow-sm">
        {{-- max-w-6xl + mx-auto: limita el ancho y centra el contenido --}}
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">

            {{-- Nombre de la app (viene del APP_NAME del .env) --}}
            <span class="text-xl font-bold text-indigo-600">{{ config('app.name') }}</span>

            <nav class="flex items-center gap-4 text-sm">
                {{-- Solo para usuarios logueados --}}
                @auth
                    <a href="{{ route('projects.index') }}"
                       class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                        Ir a mis proyectos
                    </a>
                @endauth

                {{-- Solo para invitados (no logueados) --}}
                @guest
                    <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-indigo-600">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                        Registrarse
                    </a>
                @endguest
            </nav>
        </div>
    </header>

    {{-- ========== ZONA PRINCIPAL (HERO) ========== --}}
    <section class="bg-gradient-to-b from-indigo-50 to-gray-50">
        <div class="mx-auto max-w-4xl px-6 py-24 text-center">
            {{-- text-4xl en móvil, text-6xl en pantallas medianas o mayores --}}
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 md:text-6xl">
                Organiza tus proyectos <span class="text-indigo-600">en equipo</span>
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600">
                Crea proyectos, invita a tus compañeros, asígnales un rol
                y controla las horas que dedica cada uno. Todo en un solo lugar.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-indigo-600 px-8 py-3 font-semibold text-white shadow hover:bg-indigo-700">
                        Empieza gratis
                    </a>
                    <a href="{{ route('login') }}"
                       class="rounded-lg border border-gray-300 bg-white px-8 py-3 font-semibold text-gray-700 hover:bg-gray-100">
                        Ya tengo cuenta
                    </a>
                @endguest

                @auth
                    <a href="{{ route('projects.index') }}"
                       class="rounded-lg bg-indigo-600 px-8 py-3 font-semibold text-white shadow hover:bg-indigo-700">
                        Ir a mis proyectos
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- ========== TRES TARJETAS ========== --}}
    <section class="mx-auto max-w-6xl px-6 py-16">
        {{-- 1 columna en móvil, 3 columnas desde pantallas medianas --}}
        <div class="grid gap-8 md:grid-cols-3">

            <div class="rounded-xl bg-white p-8 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Proyectos claros</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Define título, descripción, fecha límite y estado para tener
                    siempre a la vista en qué punto está cada proyecto.
                </p>
            </div>

            <div class="rounded-xl bg-white p-8 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Equipo con roles</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Añade usuarios a tus proyectos y asigna a cada uno un rol:
                    desarrollador, diseñador, tester...
                </p>
            </div>

            <div class="rounded-xl bg-white p-8 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Horas controladas</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Anota cuántas horas ha dedicado cada participante y mide
                    la aportación de todo el equipo.
                </p>
            </div>
        </div>
    </section>

    {{-- ========== PIE ========== --}}
    <footer class="py-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name') }}
    </footer>
</body>
</html>

{{-- x-nav-link es un componente de Breeze que pinta un enlace del menú --}}
{{-- :href  -> a dónde lleva (la ruta por su nombre) --}}
{{-- :active -> es true si la ruta actual empieza por "projects." --}}
{{-- routeIs('projects.*') cubre index, show, create y edit, así que el
     enlace queda marcado en todas las pantallas de proyectos --}}
<x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
    Proyectos
</x-nav-link>
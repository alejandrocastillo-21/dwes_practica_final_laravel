<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // 'projects' -> base de las URLs (/projects, /projects/{project}...)
    // ProjectController::class -> controlador que atiende las rutas
    Route::resource('projects', ProjectController::class);

    // Rutas de participantes anidadas dentro de proyectos.
    // only() limita a las rutas que vamos a usar (no necesitamos index, show, etc.)
    Route::resource('projects.members', ProjectMemberController::class)
        ->only(['store', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';

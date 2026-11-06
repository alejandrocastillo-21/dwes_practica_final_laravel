<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------- USUARIOS ----------
        // Contraseña "password" para todos, hasheada con Hash::make
        $password = Hash::make('password');

        $adminId = DB::table('users')->insertGetId([
            'name'              => 'Admin',
            'email'             => 'admin@example.com',
            'password'          => $password,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $anaId = DB::table('users')->insertGetId([
            'name'              => 'Ana López',
            'email'             => 'ana@example.com',
            'password'          => $password,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $luisId = DB::table('users')->insertGetId([
            'name'              => 'Luis Pérez',
            'email'             => 'luis@example.com',
            'password'          => $password,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // ---------- PROYECTOS ----------
        // El propietario del primero es Admin
        $proyecto1 = DB::table('projects')->insertGetId([
            'user_id'     => $adminId,
            'title'       => 'Rediseño de la web corporativa',
            'description' => 'Renovar el diseño y la estructura del sitio web.',
            'deadline'    => '2026-12-15',
            'status'      => 'en_progreso',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // El propietario del segundo es Ana
        $proyecto2 = DB::table('projects')->insertGetId([
            'user_id'     => $anaId,
            'title'       => 'App móvil de reservas',
            'description' => 'Aplicación para reservar mesas en restaurantes.',
            'deadline'    => '2027-02-01',
            'status'      => 'pendiente',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ---------- PARTICIPANTES (tabla pivote) ----------
        DB::table('project_user')->insert([
            ['project_id' => $proyecto1, 'user_id' => $anaId,  'role' => 'Diseñador',     'contribution_hours' => 12],
            ['project_id' => $proyecto1, 'user_id' => $luisId, 'role' => 'Desarrollador', 'contribution_hours' => 30],
            ['project_id' => $proyecto2, 'user_id' => $adminId, 'role' => 'Tester',       'contribution_hours' => 5],
            ['project_id' => $proyecto2, 'user_id' => $luisId, 'role' => 'Desarrollador', 'contribution_hours' => 8],
        ]);
    }
}

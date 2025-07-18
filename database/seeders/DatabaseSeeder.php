<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Hugo Franco', 'email' => 'franco.carreira.hugo@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 1],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Delfim Franco', 'email' => 'franco.carreira.delfim@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Fábio Franco', 'email' => 'franco.carreira.fabio@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Martim Borges', 'email' => 'martim.duarte.borges@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'João Brás', 'email' => 'joao.antunes.bras@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Andrylls Carvalho', 'email' => 'andrylls112@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
            ['pfp' => 'Images/Pfp/pfp_default.png', 'name' => 'Strideboard', 'email' => 'strideboard.web@gmail.com', 'email_verified_at' => now()->toDateTime(), 'password' => Hash::make('123456789'), 'is_admin' => 0],
        ];
        foreach ($users as $user) {
            User::create($user);
        }


        $projects = [
            ['name' => 'Create Project', 'business' => 'Strideboard', 'due_date' => '2025-07-08', 'color' => '#113F59'],
            ['name' => 'Conquer Gaul', 'business' => 'Roman Empire', 'due_date' => '2026-03-21', 'color' => '#ff0000'],
            ['name' => 'Win the Second Punic War', 'business' => 'Carthage', 'due_date' => '2025-12-12', 'color' => '#ffffff'],
        ];
        foreach($projects as $project){
            Project::create($project);
        }


        $projects_users = [
            ['project_id' => 1, 'user_id' => 7, 'user_type' => 2, 'active' => 1],
            ['project_id' => 2, 'user_id' => 1, 'user_type' => 2, 'active' => 1],
            ['project_id' => 2, 'user_id' => 4, 'user_type' => 1, 'active' => 1],
            ['project_id' => 2, 'user_id' => 5, 'user_type' => 1, 'active' => 1],
            ['project_id' => 2, 'user_id' => 6, 'user_type' => 0, 'active' => 1],
            ['project_id' => 3, 'user_id' => 3, 'user_type' => 2, 'active' => 1],
            ['project_id' => 3, 'user_id' => 2, 'user_type' => 1, 'active' => 1],
            ['project_id' => 3, 'user_id' => 1, 'user_type' => 0, 'active' => 1],
        ];
        foreach($projects_users as $project_user){
            ProjectUser::create($project_user);
        }

        $tasks = [];
        for ($i = 0; $i < 18; $i++) {
            $start = Carbon::now()->addDays(rand(0, 30))->setTime(rand(8, 16), rand(0, 59));
            $end = (clone $start)->copy()->addDays(rand(1, 10))->setTime(rand(9, 18), rand(0, 59));
    
            $rand_project = rand(2,3);

            $tasks[] = [
                'project_id' => $rand_project,
                'user_id' => $rand_project == 2 ? [1, 4, 5, 6][rand(0, 3)] : rand(1,3),
                'name' => Str::title(fake()->words(rand(2, 4), true)),
                'description' => fake()->sentence(),
                'start' => $start->toDateTime(),
                'end' => $end->toDateTime(),
                'state' => rand(0, 3),
                'priority' => rand(0, 3),
            ];
        }
        foreach ($tasks as $task) {
            Task::create($task);
        }

        Task::create([
            'project_id' => 1,
            'user_id' => 7,
            'name' => 'Create Task',
            'description' => 'Creating a Task for a project',
            'start' => Carbon::now(),
            'end' => Carbon::now()->addDays(rand(1, 10))->setTime(rand(9, 18), rand(0, 59))->toDateTime(),
            'state' => 0,
            'priority' => 0,
        ]);
    }
}

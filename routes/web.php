<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;

Route::get('/', function () {
    return view('home');
});

//profile edits
Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/uploadImg', [ProfileController::class, 'uploadImg'])->name('profile.uploadImg');
});

//dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/tasks', [DashboardController::class, 'tasks'])->name('dashboard.tasks');
    Route::get('/dashboard/calendar', [DashboardController::class, 'calendar'])->name('dashboard.calendar');
    Route::get('/dashboard/messages', [DashboardController::class, 'messages'])->name('dashboard.messages');
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('dashboard.profile');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');

    //region Project
    Route::get('/dashboard/projects', [ProjectsController::class, 'projects'])->name('dashboard.projects');
    Route::get('/dashboard/projects/create', [ProjectsController::class, 'projectsCreate'])->name('projects.create');
    Route::post('/dashboard/projects/create/add', [ProjectsController::class, 'projectsCreateAdd'])->name('projects.add');
    Route::get('/dashboard/projects/edit/{project_id}', [ProjectsController::class, 'projectsEdit'])->name('projects.edit');
    Route::put('/dashboard/projects/edit/{project_id}/update', [ProjectsController::class, 'projectsUpdate'])->name('projects.update');
    Route::delete('/dashboard/projects/delete/{project_id}', [ProjectsController::class, 'projectsDelete'])->name('projects.delete');
    
    Route::get('/dashboard/projects/overview/{project_id}', [ProjectsController::class, 'projectOverview'])->name('projects.overview');

    Route::post('/dashboard/projects/overview/{project_id}/add-member/{id_user}', [ProjectsController::class, 'addMember'])->name('projects.add-member');
    Route::put('/dashboard/projects/overview/{project_id}/update-member/{id_user}', [ProjectsController::class, 'updateMember'])->name('projects.update-member');
    Route::delete('/dashboard/projects/overview/{project_id}/delete-member/{id_user}', [ProjectsController::class, 'deleteMember'])->name('projects.delete-member');
    //end project

    //region Tasks
    Route::get('/dashboard/projects/overview/{project_id}/create-task', [TasksController::class, 'TasksCreate'])->name('tasks.create');
    Route::post('/dashboard/projects/overview/{project_id}/create-task/add', [TasksController::class, 'TasksCreateAdd'])->name('tasks.add');
    Route::get('/dashboard/projects/overview/{project_id}/edit-task/{task_id}', [TasksController::class, 'TasksEdit'])->name('tasks.edit');
    Route::put('/dashboard/projects/overview/{project_id}/edit-task/{task_id}/update', [TasksController::class, 'TasksUpdate'])->name('tasks.update');
    Route::delete('/dashboard/task/delete/{task_id}', [TasksController::class, 'TasksDelete'])->name('tasks.delete');

    Route::get('/dashboard/tasks/overview/{task_id}', [TasksController::class, 'TaskOverview'])->name('task.overview');
    //end Tasks

    //search routes
    Route::get('/dashboard/projects/create/user-search', [SearchController::class, 'searchUsers'])->name('search.users');
});

require __DIR__ . '/auth.php';

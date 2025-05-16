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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');

    //region Project Routes
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
    
    //region Tasks
    Route::get('/dashboard/projects/overview/{project_id}/create-task', [TasksController::class, 'TasksCreate'])->name('projects.create-task');
    Route::post('/dashboard/projects/overview/{project_id}/create-task/add', [TasksController::class, 'TasksCreateAdd'])->name('projects.create-task.add');
    Route::get('/dashboard/projects/overview/{project_id}/edit-task/{task_id}', [TasksController::class, 'TaskEdit'])->name('projects.edit-task');
    Route::put('/dashboard/projects/overview/{project_id}/edit-task/{task_id}/update', [TasksController::class, 'TasksUpdate'])->name('projects.edit-task.update');
    //end project routes

    //search routes
    Route::get('/dashboard/projects/create/user-search', [SearchController::class, 'searchUsers'])->name('search.users');
});

require __DIR__ . '/auth.php';

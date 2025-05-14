<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProjectsController;

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

    //project routes
    Route::get('/dashboard/projects', [ProjectsController::class, 'projects'])->name('dashboard.projects');
    Route::get('/dashboard/projects/create', [ProjectsController::class, 'projectsCreate'])->name('projects.create');
    Route::post('/dashboard/projects/create/add', [ProjectsController::class, 'projectsCreateAdd'])->name('projects.add');
    Route::get('/dashboard/projects/edit/{id}', [ProjectsController::class, 'projectsEdit'])->name('projects.edit');
    Route::post('/dashboard/projects/edit/{id}/update', [ProjectsController::class, 'projectsUpdate'])->name('projects.update');

    Route::get('/dashboard/projects/overview/{id}', [ProjectsController::class, 'projectOverview'])->name('projects.overview');

    Route::post('/dashboard/projects/overview/{id_project}/add-member/{id_user}', [ProjectsController::class, 'addMember'])->name('projects.add-member');
    Route::post('/dashboard/projects/overview/{id_project}/delete-member/{id_user}', [ProjectsController::class, 'deleteMember'])->name('projects.delete-member');
    Route::post('/dashboard/projects/overview/{id_project}/update-member/{id_user}', [ProjectsController::class, 'updateMember'])->name('projects.update-member');
    
    //tasks
    Route::get('/dashboard/projects/overview/{id_project}/create-task/step-1', [ProjectsController::class, 'TasksCreate'])->name('projects.create-task.step-1');
    Route::post('/dashboard/projects/overview/{id_project}/create-task/add', [ProjectsController::class, 'TasksCreateAdd'])->name('projects.create-task.add');
    Route::get('/dashboard/projects/overview/{id_project}/create-task/step-2/{id_task}', [ProjectsController::class, 'TasksCreateStep2'])->name('projects.create-task.step-2');
    Route::post('/dashboard/projects/overview/{id_project}/create-task/step-2/{id_task}/add/{id_user}', [ProjectsController::class, 'TaskMemberAdd'])->name('projects.create-task.step-2.add');
    //end project routes

    //search routes
    Route::get('/dashboard/projects/create/user-search', [SearchController::class, 'searchUsers'])->name('search.users');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('home');
});

//region Profile
Route::middleware(['auth', 'verified'])->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/uploadImg', [ProfileController::class, 'uploadImg'])->name('profile.uploadImg');
    Route::get('/dashboard/my-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/dashboard/profile/{user_id}', [ProfileController::class, 'overview'])->name('profile.overview');
});
//end Profile

//dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    //end Dashboard

    //region Project
    Route::get('/dashboard/projects', [ProjectsController::class, 'projects'])->name('dashboard.projects');
    Route::get('/dashboard/projects/create', [ProjectsController::class, 'projectCreate'])->name('projects.create');
    Route::post('/dashboard/projects/create/add', [ProjectsController::class, 'projectAdd'])->name('projects.add');
    Route::get('/dashboard/projects/edit/{project_id}', [ProjectsController::class, 'projectEdit'])->name('projects.edit');
    Route::put('/dashboard/projects/edit/{project_id}/update', [ProjectsController::class, 'projectUpdate'])->name('projects.update');
    Route::delete('/dashboard/projects/delete/{project_id}', [ProjectsController::class, 'projectDelete'])->name('projects.delete');

    Route::get('/dashboard/projects/archived', [ProjectsController::class, 'seeArchived'])->name('projects.archived');
    Route::put('/dashboard/projects/archive-toggle/{id}', [ProjectsController::class, 'archiveToggle'])->name('project.archive-toggle');
    
    Route::get('/dashboard/projects/overview/{project_id}', [ProjectsController::class, 'projectOverview'])->name('projects.overview');
    
    Route::post('/dashboard/projects/overview/{project_id}/add-member/{id_user}', [ProjectsController::class, 'addMember'])->name('projects.add-member');
    Route::put('/dashboard/projects/overview/{project_id}/update-member/{id_user}', [ProjectsController::class, 'updateMember'])->name('projects.update-member');
    Route::delete('/dashboard/projects/overview/{project_id}/delete-member/{id_user}', [ProjectsController::class, 'deleteMember'])->name('projects.delete-member');

    Route::post('/dashboard/projects/accept-invite/{id}', [ProjectsController::class, 'acceptInvite'])->name('projects.accept-invite');
    Route::post('/dashboard/projects/reject-invite/{id}', [ProjectsController::class, 'rejectInvite'])->name('projects.reject-invite');
    //end project
    
    //region Tasks
    Route::get('/dashboard/tasks', [TasksController::class, 'tasks'])->name('dashboard.tasks');
    Route::get('/dashboard/projects/overview/{project_id}/create-task', [TasksController::class, 'TaskCreate'])->name('task.create');
    Route::post('/dashboard/projects/overview/{project_id}/create-task/add', [TasksController::class, 'TaskAdd'])->name('task.add');
    Route::put('/dashboard/projects/overview/{project_id}/update-task/{task_id}', [TasksController::class, 'TaskUpdate'])->name('task.update');
    Route::delete('/dashboard/tasks/delete/{task_id}', [TasksController::class, 'TaskDelete'])->name('task.delete');
    Route::get('/dashboard/tasks/overview/{task_id}', [TasksController::class, 'TaskOverview'])->name('task.overview');
    //end Tasks

    //region Calendar
    Route::get('/dashboard/calendar', [CalendarController::class, 'calendar'])->name('dashboard.calendar');
    Route::get('/dashboard/calendar/tasks', [CalendarController::class, 'getTasks']);
    Route::post('/dashboard/calendar/task/update-date/{task_id}', [CalendarController::class, 'updateDate']);
    Route::post('/dashboard/calendar/task/resize-date/{task_id}', [CalendarController::class, 'resizeDate']);
    //end Calendar
    
    //region Inbox
    Route::get('/dashboard/inbox', [InboxController::class, 'inbox'])->name('dashboard.inbox');
    Route::get('/dashboard/inbox/{id}', [InboxController::class, 'open'])->name('inbox.open');
    Route::post('/dashboard/inbox/mark-read/{id}', [InboxController::class, 'markRead'])->name('inbox.mark-read');
    Route::post('/dashboard/inbox/mark-unread/{id}', [InboxController::class, 'markUnread'])->name('inbox.mark-unread');
    Route::delete('/dashboard/inbox/delete/{id}', [InboxController::class, 'delete'])->name('inbox.delete');
    Route::post('/dashboard/inbox/mark-all-read', [InboxController::class, 'markAllRead'])->name('inbox.mark-all-read');
    //end Inbox

    //region Settings
    Route::get('/dashboard/settings', function () {
        return view('pages.settings');
    });
    //end Settigns
});

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * */

//region Admin
Route::middleware(AdminOnly::class)->group(function () {
    Route::get('/admin-panel', function () {
        return view('admin.admin-panel');
    });

    //users
    Route::get('/admin-panel/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin-panel/users/create', [AdminUserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin-panel/users/store', [AdminUserController::class, 'store'])->name('admin.user.store');
    Route::get('/admin-panel/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin-panel/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin-panel/users/delete/{id}', [AdminUserController::class, 'destroy'])->name('admin.user.destroy');

    //projects
    Route::get('/admin-panel/projects', [AdminProjectController::class, 'index'])->name('admin.projects.index');
    Route::get('/admin-panel/projects/create', [AdminProjectController::class, 'create'])->name('admin.project.create');
    Route::post('/admin-panel/projects/store', [AdminProjectController::class, 'store'])->name('admin.project.store');
    Route::get('/admin-panel/projects/edit/{id}', [AdminProjectController::class, 'edit'])->name('admin.project.edit');
    Route::put('/admin-panel/projects/update/{id}', [AdminProjectController::class, 'update'])->name('admin.project.update');
    Route::delete('/admin-panel/projects/delete/{id}', [AdminProjectController::class, 'destroy'])->name('admin.project.destroy');  

    //tasks
    Route::get('/admin-panel/tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
    Route::get('/admin-panel/tasks/create', [AdminTaskController::class, 'create'])->name('admin.task.create');
    Route::post('/admin-panel/tasks/store', [AdminTaskController::class, 'store'])->name('admin.task.store');
    Route::get('/admin-panel/tasks/edit/{id}', [AdminTaskController::class, 'edit'])->name('admin.task.edit');
    Route::put('/admin-panel/tasks/update/{id}', [AdminTaskController::class, 'update'])->name('admin.task.update');
    Route::delete('/admin-panel/tasks/delete/{id}', [AdminTaskController::class, 'destroy'])->name('admin.task.destroy');
});
//end Admin

require __DIR__ . '/auth.php';
@extends('layouts.sidemenu')
@section('title')
    Task Overview
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('projects.overview', $project->id) }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    Task Overview
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/tasks/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    <div id="app">
        <div class="container">
            <div class="box">
                <h2>Task Details</h2>
                <form action="{{ isset($task) ? route('tasks.update', ['project_id' => $project->id, 'task_id' => $task->id]) : route('tasks.add', $project->id)  }}" method="POST">
                    @csrf
                    @if(isset($task))
                        @method('put')
                    @endif

                    <label>Task Name<span class="required">*</span></label><br>
                    <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name', $task->name ?? '') }}"><br>
                    <x-input-error :messages="$errors->get('name')"/>

                    <label>Description</label><br>
                    <textarea name="description" style="margin-bottom: 10px; height: auto;" value="{{ old('description', $task->description) }}" rows="4"></textarea>
                    <x-input-error :messages="$errors->get('description')"/>
                    
                    <div class="space"></div>

                    <label>Start Date<span class="required">*</span></label><br>
                    <input type="date" name="start" style="margin-bottom: 10px" value="{{ old('start', $task->start ?? '') }}"><br>
                    <x-input-error :messages="$errors->get('start')"/>

                    <label>End Date<span class="required">*</span></label><br>
                    <input type="date" name="end" style="margin-bottom: 10px" value="{{ old('end', $task->end ?? '') }}"><br>
                    <x-input-error :messages="$errors->get('end')"/>
                    
                    <div class="space"></div>

                    <label>Status</label><br>
                    <select name="state" id="state" class="state">
                        <option value="0">To Do</option>
                        <option value="1">Stopped</option>
                        <option value="2">In Progress</option>
                        <option value="3">Done</option>
                    </select>
                    <x-input-error :messages="$errors->get('state')"/>

                    <br>
                    <label>Priority</label><br>
                    <select name="priority" id="priority" class="priority">
                        <option value="0">Low</option>
                        <option value="1">Normal</option>
                        <option value="2">High</option>
                        <option value="3">Urgent</option>
                    </select>
                    <x-input-error :messages="$errors->get('priority')"/>

                    <div class="space"></div>
                    
                    <label>Responsible for task<span class="required">*</span></label><br>
                    <div>
                        <div v-if="!selectedUser" >
                            <input type="text" v-model="term" placeholder="Search User" style="margin-bottom: 0px; width: 80%;">
                            <div class="addmember-wrapper" style="position:relative; margin-top: 10px">
                                <table class="addmember">
                                    <tr v-for="user in filteredUsers" :key="user.id">
                                        <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" class="pfp"></td>
                                        <td class="SQL" style="max-width: 200px">@{{ user.name }}</td>
                                        <td class="SQL" style="max-width: 200px">@{{ user.email }}</td>
                                        <td>
                                            <button type="button" @click="selectUser(user)">
                                                <img width="50" height="50" src="{{ asset('Images/Icons/UserAdd.png') }}" alt="plus"/>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>  
                        
                        <div v-if="selectedUser" style="margin-top: 20px" class="selectedUser-wrapper">
                            <table class="selectedUser">
                                <tr>
                                    <td><img :src="'/' + (selectedUser.pfp || 'Images/Pfp/pfp_default.png')" class="pfp"></td>
                                    <td class="SQL" style="max-width: 200px">@{{ selectedUser.name }}</td>
                                    <td class="SQL" style="max-width: 200px">@{{ selectedUser.email }}</td>
                                    <td>
                                        <button type="button" @click="removeUser">
                                            <img width="35" height="35" src="{{ asset('Images/Icons/UserDelete.png') }}" alt="remove"/>
                                        </button>
                                    </td>
                                </tr>
                            </table>

                            <input type="hidden" name="user_id" :value="selectedUser.id">
                        </div>
                    </div>

                    <button type="submit" class="btn_default" style="margin-top: 20px">Save Changes</button>
                </form>
            </div>
            <div class="box">
                <h2 style="text-align: center">AQUI</h2>
            </div>
        </div>
    </div>

    <div id="users" data-users='@json($project->users)'></div>
    <div id="project" data-project='@json($project)'></div>
    @if (isset($task) && $task->user)
        <div id="task-user" data-task-user='@json($task->user)'></div>
    @endif
@endsection

@section('custom_vue')
    <script src="{{ asset('js/search-select.js') }}"></script>
@endsection



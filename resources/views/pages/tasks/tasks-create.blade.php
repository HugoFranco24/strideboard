@extends('layouts.sidemenu')
@section('title')
    {{ isset($task) ? 'Edit' : 'Create' }} Task
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('projects.overview', $project->id) }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    {{ isset($task) ? 'Edit' : 'Create' }} Task
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/tasks/task-create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    <div id="app">
        <div class="box">
            <h2>Task Details</h2>
            <form action="{{ route('tasks.add', $project->id)  }}" method="POST">
                @csrf

                <label>Task Name<span class="required">*</span></label><br>
                <input type="text" name="name" style="margin-bottom: 10px"><br>
                <x-input-error :messages="$errors->get('name')"/>

                <label>Description</label><br>
                <textarea name="description" style="margin-bottom: 10px; height: auto;" rows="4"></textarea>
                <x-input-error :messages="$errors->get('description')"/>

                <br>
                <label>Start Date<span class="required">*</span></label><br>
                <input type="date" name="start" style="margin-bottom: 10px"><br>
                <x-input-error :messages="$errors->get('start')"/>

                <label>End Date<span class="required">*</span></label><br>
                <input type="date" name="end" style="margin-bottom: 10px"><br>
                <x-input-error :messages="$errors->get('end')"/>

                <label>Status</label><br>
                <select name="state" id="state">
                    <option value="0" selected>To Do</option>
                    <option value="1">Stopped</option>
                    <option value="2">In Progress</option>
                    <option value="3">Done</option>
                </select>
                <x-input-error :messages="$errors->get('state')"/>

                <br>
                <label>Priority</label><br>
                <select name="priority" id="priority">
                    <option value="0" selected>Low</option>
                    <option value="1">Normal</option>
                    <option value="2">High</option>
                    <option value="3">Urgent</option>
                </select>
                <x-input-error :messages="$errors->get('priority')"/>

                <br>
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
                <x-input-error :messages="$errors->get('user_id')"/>

                <button type="submit" class="btn_default" style="margin-top: 20px">Create Task</button>
            </form>
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

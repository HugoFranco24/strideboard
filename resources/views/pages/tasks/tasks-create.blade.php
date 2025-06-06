@extends('layouts.main')
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
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    <div id="old-user-id" data-old-user-id="{{ old('user_id') }}"></div>
    <div id="app">
        <div class="box">
            <h2>Task Details</h2>
            <form action="{{ route('task.add', $project->id)  }}" method="POST">
                @csrf

                <label>Task Name<span class="required">*</span></label><br>
                <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name') }}"><br>
                <x-input-error :messages="$errors->get('name')"/>

                <label>Description</label><br>
                <textarea name="description" style="margin-bottom: 10px; height: auto;" rows="4">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')"/>

                <br>
                <label>Start Date<span class="required">*</span></label><br>
                <input type="date" name="start" style="margin-bottom: 10px" value="{{ old('start') }}"><br>
                <x-input-error :messages="$errors->get('start')"/>

                <label>End Date<span class="required">*</span></label><br>
                <input type="date" name="end" style="margin-bottom: 10px" value="{{ old('end') }}"><br>
                <x-input-error :messages="$errors->get('end')"/>

                <label>Status</label><br>
                <select name="state" id="state">
                    <option value="0" {{ old('state') == 0 || old('state') == null ? 'selected' : '' }}>To Do</option>
                    <option value="1" {{ old('state') == 1 ? 'selected' : '' }}>Stopped</option>
                    <option value="2" {{ old('state') == 2 ? 'selected' : '' }}>In Progress</option>
                    <option value="3" {{ old('state') == 3 ? 'selected' : '' }}>Done</option>
                </select>
                <x-input-error :messages="$errors->get('state')"/>

                <br>
                <label>Priority</label><br>
                <select name="priority" id="priority">
                    <option value="0" {{ old('priority') == 0 || old('priority') == null ? 'selected' : '' }}>Low</option>
                    <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Normal</option>
                    <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>High</option>
                    <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Urgent</option>
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
                                            <img width="50" height="50" src="{{ asset('Images/Icons/Actions/UserAdd.png') }}" alt="plus"/>
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
                                <td class="SQL" style="max-width: 200px"><a :href="'/dashboard/profile/' + selectedUser.id" class="username">@{{ selectedUser.name }}</a></td>
                                <td class="SQL" style="max-width: 200px">@{{ selectedUser.email }}</td>
                                <td>
                                    <button type="button" @click="removeUser">
                                        <img width="35" height="35" src="{{ asset('Images/Icons/Actions/UserDelete.png') }}" alt="remove"/>
                                    </button>
                                </td>
                            </tr>
                        </table>

                        <input v-if="selectedUser" type="hidden" name="user_id" :value="selectedUser.id">
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

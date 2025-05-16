@extends('layouts.sidemenu')
@section('title')
    {{ isset($task) ? 'Update' : 'Create' }} Task
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('projects.overview', $project->id) }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    {{ isset($task) ? 'Update' : 'Create' }} Task
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/task-create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    <div id="app">
        <div class="box">
            <h2>Task Details</h2>
            <form action="/dashboard/projects/overview/{{$project->id}}/create-task/add" method="POST">
                @csrf
                @if(isset($task))
                    @method('put')
                @endif

                <label>Task Name</label><br>
                <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name', $task->name ?? '') }}"><br>
                <x-input-error :messages="$errors->get('name')"/>

                <label>Description</label><br>
                <textarea name="description" style="margin-bottom: 10px; height: auto;" value="{{ old('description', ) }}" rows="4"></textarea>
                <x-input-error :messages="$errors->get('description')"/>

                <br>
                <label>Start Date</label><br>
                <input type="date" name="start" style="margin-bottom: 10px" value="{{ old('start', $project->start ?? '') }}"><br>
                <x-input-error :messages="$errors->get('start')"/>

                <label>End Date</label><br>
                <input type="date" name="end" style="margin-bottom: 10px" value="{{ old('end', $project->end ?? '') }}"><br>
                <x-input-error :messages="$errors->get('end')"/>

                <br><br>
                <label>Responsible for task</label><br>
                <div>
                    <div v-if="!selectedUser" >
                        <input type="text" v-model="term" placeholder="Search User" style="margin-bottom: 0px; width: 80%;">
                        <div class="addmember-wrapper" style="position:relative; margin-top: 10px">
                            <table class="addmember">
                                <tr v-for="user in filteredUsers" :key="user.id">
                                    <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" class="pfp"></td>
                                    <td>@{{ user.name }}</td>
                                    <td>@{{ user.email }}</td>
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
                                <td>@{{ selectedUser.name }}</td>
                                <td>@{{ selectedUser.email }}</td>
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

                <button type="submit" class="btn_default" style="margin-top: 20px">{{ isset($task) ? 'Update' : 'Create' }} Task</button>
            </form>
        </div> 
    </div>

    <div id="users" data-users='@json($project->users)'></div>
    <div id="project" data-project='@json($project)'></div>
@endsection

@section('custom_vue')
    <script>
        window.onload = function () {
            const { createApp, ref, onMounted, computed } = Vue;

            createApp({
                setup() {
                    const users = ref([]);
                    const project = ref(null);
                    const term = ref('');
                    const selectedUser = ref(null);

                    onMounted(() => {
                        let el = document.getElementById('users');
                        let rawData = el.dataset.users;

                        try {
                            users.value = JSON.parse(rawData);
                        } catch (e) {
                            console.error('Error parsing users:', e);
                        }

                        el = document.getElementById('project');
                        rawData = el.dataset.project;

                        try {
                            project.value = JSON.parse(rawData);
                        } catch (e) {
                            console.error('Error parsing project:', e);
                        }
                    });

                    const filteredUsers = computed(() => {
                        if (!term.value || selectedUser.value) return [];

                        return users.value.filter(user =>
                            user.name.toLowerCase().includes(term.value.toLowerCase()) ||
                            user.email.toLowerCase().includes(term.value.toLowerCase())
                        ).slice(0, 4);
                    });

                    const selectUser = (user) => {
                        selectedUser.value = user;
                        term.value = '';
                    };

                    const removeUser = () => {
                        selectedUser.value = null;
                    };

                    return {
                        users,
                        term,
                        project,
                        selectedUser,
                        filteredUsers,
                        selectUser,
                        removeUser
                    };
                }
            }).mount('#app');
        }
    </script>
@endsection

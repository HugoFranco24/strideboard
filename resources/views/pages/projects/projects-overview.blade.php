@extends('layouts.sidemenu')
@section('title')
    Project Overview
@endsection

@section('body-title')
    Project Overview
@endsection

@section('css')
    {{ asset("css/dashboard/projects/overview.css") }}
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    <div id="app" v-cloak>
    <div class="container">
        <div class="item">
            <h2>Project Details</h2>
            <p><span>Project Name:</span> {{ $project->name }}</p>
            <p><span>Business:</span> {{ $project->business }}</p>
            <p><span>Due Date:</span> {{ $project->due_date }}</p>
            <p><span>Owner:</span> {{$owner->name}}</p>
            <br>
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/edit/{{ $project->id_project }}"><button type="button" class="btn_default">Edit Project</button></a>
                @endif
                
                @if ($authUserType == 2)
                    <form action="/dashboard/projects/delete/{{ $project->id_project }}">
                        @csrf
                        <button type="submit" class="btn_delete">Delete Project</button>
                    </form>
                @endif

                @if ($authUserType != 2)
                    <form action="/dashboard/projects/overview/{{ $project->id_project }}/delete-member/{{ $user->id }}"
                        method="post"
                        onsubmit="return confirm('Are you sure you want to leave the project?')"
                    >
                        @csrf
                        <button type="submit" class="btn_default">Leave Project</button>
                    </form>
                @endif
            </div>        
        </div>
        <div class="item">
            <input type="hidden" id="users" data-users='@json($user_all)'>
            <input type="hidden" id="project" data-project='@json($project)'>
            <h2>Collaborators</h2>
            <h3>Add Collaborator</h3>
            <div>
                <input type="text" v-model="term" placeholder="Search User" style="margin: 0px; width: 100%;">
                <div class="addmember-wrapper">
                    <table class="addmember">
                        <tr v-for="user in filteredUsers" :key="user.id">                        
                            <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" alt="" class="pfp"></td>
                            <td>@{{ user.name }}</td>
                            <td>@{{ user.email }}</td>
                            <td>
                                <form :action="'/dashboard/projects/overview/'+ project.id_project +'/add-member/'+ user.id" 
                                    class="addmember_form" 
                                    method="post"
                                >
                                    @csrf
                                    <button type="submit">
                                        <img width="50" height="50" src="https://img.icons8.com/ios/50/plus-math--v1.png" alt="plus-math--v1"/>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
                
            </div>

            <h3>Collaborators in Project:</h3>
            <div class="members-wrapper">
                <table class="members">
                    @foreach ($project_users as $pu)
                        <tr>
                            <td><img src="{{ asset($pu->pfp ?? 'Images/Pfp/pfp_default.png') }}" alt="" class="pfp"></td>
                            <td>{{ $pu->name }}</td>
                            <td>{{ $pu->email }}</td>
                            <td>
                                @if ($pu->id != auth()->id())
                                    @if ($authUserType == 1 && $pu->user_type == 1)
                                        <span>Admin</span>
                                    @elseif ($authUserType != 0 && $pu->user_type == 0 || $authUserType == 2 && $pu->user_type == 1 )
                                        <form action="/dashboard/projects/overview/{{ $project->id_project }}/update-member/{{ $pu->id }}" method="POST">
                                            @csrf
                                            <select name="user_type" onchange="this.form.submit()">
                                                <option value="0" {{ $pu->user_type == 0 ? 'selected' : '' }}>Collaborator</option>
                                                <option value="1" {{ $pu->user_type == 1 ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    @else
                                        @if ($pu->user_type == 2)
                                            <span>Owner</span>
                                        @else
                                            <span>{{ $pu->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                        @endif
                                    @endif
                                @else
                                    @if ($pu->user_type == 2)
                                        <span>Owner</span>
                                    @else
                                        <span>{{ $pu->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                    @endif
                                @endif
                            </td>

                            @if ($pu->id != auth()->id())
                                @if ($authUserType == 1 && $pu->user_type == 1)
                                    <td></td>
                                @elseif ($authUserType != 0 && $pu->user_type == 0 || $authUserType == 2 && $pu->user_type == 1 )
                                    <td style="width: 50px">
                                        <form action="/dashboard/projects/overview/{{ $project->id_project }}/delete-member/{{ $pu->id }}" 
                                            method="post"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                        >
                                            @csrf
                                            <button type="submit">
                                                <img style="min-width: 35px; max-width: 35px; height: 35px;" src="https://img.icons8.com/material-rounded/50/cc0000/filled-trash.png" alt="filled-trash"/>
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
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
            const selectedUsers = ref([]);

            onMounted(() => {
                
                let el = document.getElementById('users');
                let rawData = el.dataset.users;

                try {
                    users.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing Blade Data:', e);
                }

                el = document.getElementById('project');
                rawData = el.dataset.project;

                try {
                    project.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing Blade Data:', e);
                }
            });

            const filteredUsers = computed(() => {
                if (!term.value) return [];

                return users.value.filter(user =>
                    user.name.toLowerCase().includes(term.value.toLowerCase()) ||
                    user.email.toLowerCase().includes(term.value.toLowerCase())
                ).filter(user =>
                    !selectedUsers.value.find(u => u.id === user.id)
                );
            });

            const addUser = (user) => {
                selectedUsers.value.push(user);
            };

            const removeUser = (userId) => {
                selectedUsers.value = selectedUsers.value.filter(u => u.id !== userId);
            };

            return {
                users,
                term,
                filteredUsers,
                selectedUsers,
                addUser,
                removeUser,
                project
            };
        }
    }).mount('#app');

    document.getElementById('app').style.display = 'block';
}
</script>
@endsection

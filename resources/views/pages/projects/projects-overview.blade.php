@extends('layouts.sidemenu')
@section('title')
    Project Overview
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('dashboard.projects') }}">
        <img class="icon" width="35" height="35" src="https://img.icons8.com/fluency-systems-filled/48/u-turn-to-left.png" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    Project Overview
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
@endsection

@section('body')
    <div id="app" v-cloak>
    <div class="container">
        <div class="item">
            <h2>Project Details</h2>
            <p><span>Project Name:</span> {{ $project->name }}</p>
            <p><span>Business:</span> {{ $project->business }}</p>
            <p><span>Due Date:</span> {{ $project->due_date }}</p>
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
            @if ($authUserType != 0)
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
            @endif
            <br><br><br><br><br><br><br>
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
        @php
            $done = 0;
            $in_progress = 0;
            $to_do = 0;
            $total = 0;

            foreach($tasks as $t){
                if($t->state == 0){
                    $to_do += 1;
                }elseif($t->state == 1){
                    $in_progress += 1;
                }else{
                    $done += 1;
                }
                $total += 1;
            }
        @endphp
        <div class="item">
            <input type="hidden" value="{{ $done }}" id="done">
            <input type="hidden" value="{{ $in_progress }}" id="in_progress">
            <input type="hidden" value="{{ $to_do }}" id="to_do">
            <h2>Tasks</h2>
            <p><span>Done:</span> {{ $done }}</p>
            <p><span>In Progress:</span> {{ $in_progress }}</p>
            <p><span>To do:</span> {{ $to_do }}</p>
            <p><span>Total:</span> {{ $total }}</p>
            
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/overview/{{$project->id_project}}/create-task/step-1"><button type="button" class="btn_default">Create Task</button></a>
                @endif
                
                <a href=""><button class="btn_default">View All Tasks</button></a>
            </div>  
        </div>
        @if ($total > 0)
            <div class="item">
                <table class="members">
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($tasks as $t)
                        <tr>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->start_date }}</td>
                            <td>{{ $t->end_date }}</td>
                            <td>
                                @if ($t->state == 0)
                                    To Do
                                @elseif ($t->state == 1)
                                    In Progress
                                @else
                                    Done
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="item">
                <div class="chart-container">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>
        @endif
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
                )
                .slice(0, 4);
            });

            return {
                users,
                term,
                filteredUsers,
                selectedUsers,
                project
            };
        }
    }).mount('#app');

    document.getElementById('app').style.display = 'block';

    //Tasks Chart
    const to_do = parseInt(document.getElementById('to_do').value);
    const in_progress = parseInt(document.getElementById('in_progress').value);
    const done = parseInt(document.getElementById('done').value);

    const getCSSVar = (varName) => getComputedStyle(document.documentElement).getPropertyValue(varName).trim(); //BUSCAR TEXT COLOR
    const textColor = getCSSVar('--text-color');

    const taskChart = new Chart('taskChart', {
        type: 'doughnut',
        data: {
            labels: ["To Do", "In Progress", "Done"],
            datasets: [
                {
                    data: [to_do, in_progress, done],
                    backgroundColor: ['#DA291C', '#F3C242', '#008057'],
                    borderColor: 'transparent'
                },
                
            ],
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                }
            }
        }
    });   
}
</script>
@endsection

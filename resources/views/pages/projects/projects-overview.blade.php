@extends('layouts.sidemenu')
@section('title')
    Project Overview
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('dashboard.projects') }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
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
            <p class="SQL"><span>Project Name:</span> {{ $project->name }}</p>
            <p class="SQL"><span>Business:</span> {{ $project->business == '' ? 'Not Defined' : $project->business }}</p>
            <p class="SQL"><span>Due Date:</span> {{ $project->due_date }}</p>
            <div style="display: flex">
                <span>Project Color:   {{$project->color}} -></span>
                <div style="background-color: {{ $project->color }}; width: 40px;"></div>
            </div>
            <br>
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/edit/{{ $project->id }}"><button type="button" class="btn_default">Edit Project</button></a>
                @endif
                
                @if ($authUserType == 2)
                    <form action="/dashboard/projects/delete/{{ $project->id }}"
                        onsubmit="return confirm('Are you sure you want to DELETE the project?')"
                        method="post"
                    >
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn_delete" style="margin-top: 20px;">Delete Project</button>
                    </form>
                @endif

                @if ($authUserType != 2)
                    <form action="/dashboard/projects/overview/{{ $project->id }}/delete-member/{{ $user->id }}"
                        onsubmit="return confirm('Are you sure you want to LEAVE the project?')"
                        method="post"
                    >
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn_default" style="margin-top: 20px;">Leave Project</button>
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
                                <td class="SQL" style="max-width: 200px">@{{ user.name }}</td>
                                <td class="SQL" style="max-width: 200px">@{{ user.email }}</td>
                                <td>
                                    <form :action="'/dashboard/projects/overview/'+ project.id +'/add-member/'+ user.id" 
                                        class="addmember_form"
                                        method="post"
                                    >
                                        @csrf
                                        @method('post')
                                        <button type="submit">
                                            <img width="50" height="50" src="{{ asset('Images/Icons/UserAdd.png') }}" alt="plus-math--v1"/>
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
                    @foreach ($project->users as $pu)
                        <tr>
                            <td><img src="{{ asset($pu->pfp ?? 'Images/Pfp/pfp_default.png') }}" alt="" class="pfp"></td>
                            <td class="SQL" style="max-width: 175px">{{ $pu->name }}</td>
                            <td class="SQL" style="max-width: 175px">{{ $pu->email }}</td>
                            <td>
                                @if ($pu->id != auth()->id())
                                    @if ($authUserType == 1 && $pu->pivot->user_type == 1)
                                        <span>Admin</span>
                                    @elseif ($authUserType != 0 && $pu->pivot->user_type == 0 || $authUserType == 2 && $pu->pivot->user_type == 1 )
                                        <form action="/dashboard/projects/overview/{{ $project->id }}/update-member/{{ $pu->id }}"
                                            method="post"    
                                        >
                                            @csrf
                                            @method('put')
                                            <select name="user_type" onchange="this.form.submit()">
                                                <option value="0" {{ $pu->pivot->user_type == 0 ? 'selected' : '' }}>Collaborator</option>
                                                <option value="1" {{ $pu->pivot->user_type == 1 ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    @else
                                        @if ($pu->pivot->user_type == 2)
                                            <span>Owner</span>
                                        @else
                                            <span>{{ $pu->pivot->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                        @endif
                                    @endif
                                @else
                                    @if ($pu->pivot->user_type == 2)
                                        <span>Owner</span>
                                    @else
                                        <span>{{ $pu->pivot->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                    @endif
                                @endif
                            </td>

                            @if ($pu->id != auth()->id())
                                @if ($authUserType == 1 && $pu->pivot->user_type == 1)
                                    <td></td>
                                @elseif ($authUserType != 0 && $pu->pivot->user_type == 0 || $authUserType == 2 && $pu->pivot->user_type == 1 )
                                    <td style="width: 50px">
                                        <form action="/dashboard/projects/overview/{{ $project->id }}/delete-member/{{ $pu->id }}" 
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                            method="post"
                                        >
                                            @csrf
                                            @method('delete')
                                            <button type="submit">
                                                <img style="min-width: 35px; max-width: 35px; height: 35px;" src="{{ asset('Images/Icons/UserDelete.png') }}" alt="UserDelete"/>
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
            $stoped = 0;
            $to_do = 0;
            $total = 0;

            foreach($project->tasks as $t){
                if($t->state == 0){
                    $to_do += 1;
                }elseif($t->state == 1){
                    $stoped += 1;
                }elseif($t->state == 2){
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
            <input type="hidden" value="{{ $stoped }}" id="stoped">
            <input type="hidden" value="{{ $to_do }}" id="to_do">
            <h2>Tasks</h2>
            <p><span>Total:</span> {{ $total }}</p>
            <p><span>Late: </span> 2</p>
            <p><span>Urgent: </span> 2</p>
            <br><br>
            
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/overview/{{$project->id}}/create-task"><button type="button" class="btn_default">Create Task</button></a>
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
                    @foreach ($project->tasks as $t)
                        <tr>
                            <td class="SQL">{{ $t->name }}</td>
                            <td class="SQL">{{ $t->start }}</td>
                            <td class="SQL">{{ $t->end }}</td>
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
    const stoped = parseInt(document.getElementById('stoped').value);
    const in_progress = parseInt(document.getElementById('in_progress').value);
    const done = parseInt(document.getElementById('done').value);

    const getCSSVar = (varName) => getComputedStyle(document.documentElement).getPropertyValue(varName).trim(); //BUSCAR TEXT COLOR
    const textColor = getCSSVar('--text-color');

    const taskChart = new Chart('taskChart', {
        type: 'doughnut',
        data: {
            labels: ["To Do", "Stoped", "In Progress", "Done"],
            datasets: [
                {
                    data: [to_do, stoped, in_progress, done],
                    backgroundColor: ['#ccc','#da291c', '#f3c242', '#008057'],
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

@extends('layouts.main')
@section('title')
    Task Overview
@endsection

@section('go-back')
    <a class="goBack" href="{{ $url_previous }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    Task Overview
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/tasks/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
@endsection

@section('body')
    @php
        $editable = false;

        if($task->user_id == auth()->id() || $project_user->user_type >= 1){
            $editable = true;
        }
    @endphp
    
    @if($status != null)
        <x-session-status
            :message="$status"
        />
    @endif

    <div id="app">
        <div class="container">
            <div class="box">
                <h2>Task Details</h2>
                <form 
                    action="{{ isset($task) ? route('task.update', ['project_id' => $project->id, 'task_id' => $task->id]) : route('task.add', $project->id)  }}" 
                    method="POST"
                >
                    @csrf
                    @if(isset($task))
                        @method('put')
                    @endif

                    <input type="hidden" name="url_previous" value="{{ $url_previous }}">

                    <label>Task Name<span class="required">*</span></label><br>
                    <input 
                        type="text" 
                        name="name" 
                        style="margin-bottom: 10px" 
                        value="{{ old('name', $task->name ?? '') }}" 
                        {{ $editable ? '' : 'disabled' }}
                    >
                    <br>
                    <x-input-error :messages="$errors->get('name')"/>

                    <label>Description</label><br>
                    <textarea 
                        name="description" 
                        style="margin-bottom: 10px; height: auto;" 
                        value="{{ old('description', $task->description) }}" 
                        rows="4"
                        {{ $editable ? '' : 'disabled' }}
                    ></textarea>
                    <x-input-error :messages="$errors->get('description')"/>
                    
                    <div class="space"></div>

                    <label>Start Date<span class="required">*</span></label><br>
                    <input 
                        type="date" 
                        name="start" 
                        style="margin-bottom: 10px" 
                        value="{{ old('start', $task->start ?? '') }}"
                        {{ $editable ? '' : 'disabled' }}
                    >
                    <br>
                    <x-input-error :messages="$errors->get('start')"/>

                    <label>End Date<span class="required">*</span></label><br>
                    <input 
                        type="date" 
                        name="end" 
                        style="margin-bottom: 10px" 
                        value="{{ old('end', $task->end ?? '') }}"
                        {{ $editable ? '' : 'disabled' }}
                    >
                    <br>
                    <x-input-error :messages="$errors->get('end')"/>
                    
                    <div class="space"></div>

                    <label>Status</label><br>
                    <select name="state" id="state" class="state" {{ $editable ? '' : 'disabled' }}>
                        <option value="0" {{ old('state') == 0 || $task->state == 0 ? 'selected' : '' }}>To Do</option>
                        <option value="1" {{ old('state') == 1 || $task->state == 1 ? 'selected' : '' }}>Stopped</option>
                        <option value="2" {{ old('state') == 2 || $task->state == 2 ? 'selected' : '' }}>In Progress</option>
                        <option value="3" {{ old('state') == 3 || $task->state == 3 ? 'selected' : '' }}>Done</option>
                    </select>
                    <x-input-error :messages="$errors->get('state')"/>

                    <br>
                    <label>Priority</label><br>
                    <select name="priority" id="priority" class="priority" {{ $editable ? '' : 'disabled' }}>
                        <option value="0" {{ old('priority') == 0 || $task->priority == 0 ? 'selected' : '' }}>Low</option>
                        <option value="1" {{ old('priority') == 1 || $task->priority == 1 ? 'selected' : '' }}>Normal</option>
                        <option value="2" {{ old('priority') == 2 || $task->priority == 2 ? 'selected' : '' }}>High</option>
                        <option value="3" {{ old('priority') == 3 || $task->priority == 3 ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <x-input-error :messages="$errors->get('priority')"/>

                    <div class="space"></div>
                    
                    <label>Responsible for task<span class="required">*</span></label><br>
                    <div>
                        <div v-if="!selectedUser">
                            <input type="text" v-model="term" placeholder="Search User" style="margin-bottom: 0px; width: 80%;">
                            <div class="addmember-wrapper" style="position:relative; margin-top: 10px">
                                <table class="addmember">
                                    <tr v-for="user in filteredUsers" :key="user.id">
                                        <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" class="pfp"></td>
                                        <td class="SQL" style="max-width: 200px">@{{ user.name }}</td>
                                        <td class="SQL" style="max-width: 200px">@{{ user.email }}</td>
                                        @if ($editable)
                                            <td>
                                                <button type="button" @click="selectUser(user)">
                                                    <img width="50" height="50" src="{{ asset('Images/Icons/Actions/UserAdd.png') }}" alt="plus"/>
                                                </button>
                                            </td>
                                        @endif
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
                                    @if ($editable)
                                        <td>
                                            <button type="button" @click="removeUser">
                                                <img width="35" height="35" src="{{ asset('Images/Icons/Actions/UserDelete.png') }}" alt="remove"/>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            </table>

                            <input type="hidden" name="user_id" :value="selectedUser.id">
                        </div>
                    </div>

                    @if ($editable)
                        <div class="func">
                            <button type="submit" class="btn_default" style="margin-top: 20px" >Save Changes</button>
                </form>
                            <form 
                                action="{{ route('task.delete', $task->id) }}"
                                method="post"
                            >
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn_delete" style="margin-top: 20px" >Delete Task</button>
                            </form>
                        </div>
                    @endif
            </div>
            <div class="box">
                <h2>History</h2>
                @php
                    $auditCount = 0;
                @endphp
                <div class="audit-wrapper">
                    @foreach ($audits as $a)
                        @php
                            $auditUser = $users->firstWhere('id', $a->user_id);
                            $auditCount += 1
                        @endphp
                        <div class="audit">
                            <div class="Uinfo">
                                <div style="display: flex; align-items: center;">
                                    <img src="{{ asset($auditUser->pfp) ?? asset('Images/Pfp/pfp_default.png') }}" alt="">
                                    @if ($auditUser)
                                        <p><a href="{{ route('profile.overview', $auditUser->id) }}" class="username">{{ $auditUser->name }}</a> <span>{{ $a->event }}</span> this Task on <span>{{ \Carbon\Carbon::parse($a->updated_at)->format('F d, Y \a\t H:i') }}</span></p>                            
                                    @else
                                        <p>Deleted User <span>{{ $a->event }}</span> this Task on <span>{{ \Carbon\Carbon::parse($a->updated_at)->format('F d, Y \a\t H:i') }}</span></p>                          
                                    @endif
                                </div>
                                <div style="display: flex;">
                                    <button onclick="details(this, {{ $auditCount }})">Details</button>
                                </div>
                            </div>
                            <div class="details" id="details{{ $auditCount }}">
                                <p>Changes Made</p>
                                @if ($a->event == 'updated')
                                    <table style="margin-bottom: 50px">
                                        <tr>
                                            <th style="border-right: 1px solid #ddd">Collumn</th>
                                            <th>Old Value</th>
                                        </tr>
                                        @foreach ($a->old_values as $key => $value)
                                            <tr>
                                                @if ($key == 'state')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">Status</td>
                                                    @if ($value == '0')
                                                        <td>To Do</td>
                                                    @elseif ($value == '1')
                                                        <td>Stoped</td>
                                                    @elseif ($value == '2')
                                                        <td>In Progress</td>
                                                    @else
                                                        <td>Done</td>
                                                    @endif
                                                @elseif ($key == 'priority')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">{{$key}}</td>
                                                    @if ($value == '0')
                                                        <td>Low</td>
                                                    @elseif ($value == '1')
                                                        <td>Normal</td>
                                                    @elseif ($value == '2')
                                                        <td>High</td>
                                                    @else
                                                        <td>Urgent</td>
                                                    @endif
                                                @elseif ($key == 'user_id')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">Collaborator</td>
                                                    <td>{{ $project->users->firstWhere('id', $value)?->name }}</td>
                                                @else
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">{{$key}}</td>
                                                    <td>{{$value}}</td>
                                                @endif   
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                                <table>
                                    <tr>
                                        <th style="border-right: 1px solid #ddd">Collumn</th>
                                        <th>New Value</th>
                                    </tr>
                                    @foreach ($a->new_values as $key => $value)
                                        <tr>
                                            @if ($key != 'project_id' && $key != 'id')
                                                @if ($key == 'state')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">Status</td>
                                                    @if ($value == '0')
                                                        <td>To Do</td>
                                                    @elseif ($value == '1')
                                                        <td>Stoped</td>
                                                    @elseif ($value == '2')
                                                        <td>In Progress</td>
                                                    @else
                                                        <td>Done</td>
                                                    @endif
                                                @elseif ($key == 'priority')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">{{$key}}</td>
                                                    @if ($value == '0')
                                                        <td>Low</td>
                                                    @elseif ($value == '1')
                                                        <td>Normal</td>
                                                    @elseif ($value == '2')
                                                        <td>High</td>
                                                    @else
                                                        <td>Urgent</td>
                                                    @endif
                                                @elseif ($key == 'user_id')
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">Collaborator</td>
                                                    <td>{{ $project->users->firstWhere('id', $value)?->name }}</td>
                                                @else
                                                    <td style="border-right: 1px solid #ddd; text-transform: capitalize;">{{$key}}</td>
                                                    <td>{{$value}}</td>
                                                @endif
                                            @endif    
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="users" data-users='@json($project->users->filter(fn($u) => $u->pivot->active))'></div>
    <div id="project" data-project='@json($project)'></div>
    @if (isset($task) && $task->user)
        <div id="task-user" data-task-user='@json($task->user)'></div>
    @endif
@endsection

@section('custom_vue')
    <script src="{{ asset('js/search-select.js') }}"></script>
    <script>
        function details(btn, i){
            var details = document.getElementById('details' + i);

            details.classList.toggle('expanded');

            if (btn.innerText === "Details") {
                btn.innerText = "Close";
            } else {
                btn.innerText = "Details";
            }
        }
    </script>
@endsection



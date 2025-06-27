@extends('layouts.main')
@section('title')
    Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/table.css') }}">
@endsection

@section('custom_links')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/tableSort.js') }}"></script>
@endsection

@section('body-title')
    Dashboard
@endsection

@section('body')
    <div class="Info1">
        <div class="item">
            <h4>Current Projects</h4>
            <h2>{{ $projects->count() }}</h2>
        </div>
        <div class="item">
            <h4>Late Projects</h4>
            <h2>{{ $projects->where('due_date', '<', \Carbon\Carbon::today())->count() }}</h2>
        </div>
        <div class="item">
            <h4>Assigned Tasks</h4>
            <h2>{{ $tasks->count() }}</h2>
        </div>
        <div class="item">
            <h4>Late Tasks</h4>
            <h2>{{ $tasks->where('end', '<', \Carbon\Carbon::today())->count() }}</h2>
        </div>
    </div>

    <div class="project box" style="margin-top: 30px" id="project">
        <div class="details">
            <div>
                <h2>Project:</h2>
                <form action="">
                    <select name="project" id="project" onchange="this.form.submit();">
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" {{ $p->id == $vProject->id ? 'selected' : ''}}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="dashboard/projects/overview/{{ $vProject->id }}">Go to Project</a>
            </div>       
        </div>
        @if ($vProject->tasks->count() != 0)
            <div class="dtable">
                <div class="dtable-wrapper" style="max-height: 400px">
                    <table id="allTasks">
                        <tr>
                            <th onclick="sortTask(0)">Task Name <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(1)">Assigned To <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(2)">Starting Date <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(3)">Ending Date <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(4)"><span style="margin-right: 12px">Status</span> <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(5)"><span style="margin-right: 12px">Priority</span> <span class="sort-arrow"></span></th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                        @foreach ($vProject->tasks as $t)
                            <tr>
                                <td class="SQL">{{ $t->name }}</td>
                                <td class="SQL"><a href="{{ route('profile.overview', $vProject->users->where('id', $t->user_id)->first()?->id) }}" class="username">{{ $vProject->users->where('id', $t->user_id)->first()?->name }}</a></td>
                                <td class="SQL">{{ \Carbon\Carbon::parse($t->start)->format('F d, Y') }}</td>
                                <td class="SQL">{{ \Carbon\Carbon::parse($t->end)->format('F d, Y') }}</td>
                                @if ($t->state == 0)
                                    <td data-state="not_started" class="state"><span style="display: none">0</span>Not Started</td>
                                @elseif ($t->state == 1)
                                    <td data-state="stoped" class="state"><span style="display: none">1</span>Stoped</td>
                                @elseif ($t->state == 2)
                                    <td data-state="in_progress" class="state"><span style="display: none">2</span>In Progress</td>
                                @elseif ($t->state == 3)
                                    <td data-state="done" class="state"><span style="display: none">3</span>Done</td>
                                @endif

                                @if ($t->priority == 0)
                                    <td data-priority="low" class="priority"><span style="display: none">0</span>Low</td>
                                @elseif ($t->priority == 1)
                                    <td data-priority="normal" class="priority"><span style="display: none">1</span>Normal</td>
                                @elseif ($t->priority == 2)
                                    <td data-priority="high" class="priority"><span style="display: none">2</span>High</td>
                                @elseif ($t->priority == 3)
                                    <td data-priority="urgent" class="priority"><span style="display: none">3</span>Urgent</td>
                                @endif
                                
                                @php
                                    $authUserType = $vProject->users->filter(fn(App\Models\User $u) => $u->id == auth()->id())->first()->pivot->user_type;
                                @endphp
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('task.overview',  $t->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                        @if ($authUserType >= 1 || $t->user_id == auth()->id())
                                            <form 
                                                action="{{ route('task.delete', $t->id) }}"
                                                onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                method="post"
                                            >
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="lineSpace"></div>

            <h2 style="text-align: center">Number of Tasks per Collaborator</h2>
            <div class="usersByTasks" style="display: flex; justify-content: center;">
                <canvas id="usersBar" style="width:100%; max-width: 1000px; max-height: 600px;"></canvas>
            </div>
        @else
            <h2 style="margin: 30px 0px 0px">No tasks to display.</h2>
        @endif  
    </div>
@endsection

@section('custom_vue')
    <script>
        const projects = @json($vProject);

        const names = projects.users.map(u => u.name);

        const tasks = projects.users.map(u => {
            return projects.tasks.filter(t => t.user_id === u.id).length;
        });

        setTimeout(() => {
            var textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color').trim();

            const chart = new Chart("usersBar", {
                type: "bar",
                data: {
                    labels: names,
                    datasets: [{
                        label: "Tasks",
                        data: tasks,
                        backgroundColor: "#113F59",
                        maxBarThickness: 100,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            grid: {
                                color: 'hsl(0, 0%, 50%)'
                            },
                            ticks: {
                                color: textColor,
                                tickColor: textColor
                            }
                        },
                        y: {
                            grid: {
                                color: 'hsl(0, 0%, 50%)'
                            },
                            ticks: {
                                stepSize: 1,
                                color: textColor,
                            }
                        }
                    },
                    plugins: {
                        legend: { 
                            display: false 
                        },
                    }
                }
            });

        }, 100);
    </script>
@endsection
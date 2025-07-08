@extends('layouts.main')
@section('title')
    Tasks
@endsection

@section('body-title')
    Tasks
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/table.css') }}">
    <style>
        .box{
            max-height: calc(100dvh - 140px);
        }
    </style>
@endsection

@section('body')
    <div class="box">
        <h2>My Tasks</h2>
        <br>

        <form action="{{ route('dashboard.tasks') }}">
            @csrf
            <div class="filters">
                <div class="filter-item">
                    <label for="filter">Filter by:</label>
                    <select name="filter" id="filter" onchange="form.submit()">
                        <option value="no_filter">No Filter</option>
                        <option value="late" {{ request('filter') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="urgent" {{ request('filter') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="done" {{ request('filter') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterProject">Filter by Project:</label>
                    <select name="project" id="project" onchange="form.submit()">
                        <option value="all">All</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project') == $p->id ? 'selected' : '' }}>
                                {{$p->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if ($my_tasks->isEmpty())
            <p>No tasks found. Try changing the filters, if it's still not showing any tasks, you might have none.</p>
        @else
            <div class="dtable">
                <div class="dtable-wrapper">
                    <table id="allTasks">
                        <tr>
                            <th onclick="sortTask(0)">Task Name <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(1)">Project Name <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(2)">Starting Date <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(3)">Ending Date <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(4)"><span style="margin-right: 12px">Status</span> <span class="sort-arrow"></span></th>
                            <th onclick="sortTask(5)"><span style="margin-right: 12px">Priority</span> <span class="sort-arrow"></span></th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                        @foreach ($my_tasks as $t)
                            <tr>
                                <td class="SQL">{{ $t->name }}</td>
                                <td class="SQL"><a href="{{ route('projects.overview', $t->project_id) }}" class="username">{{ $t->project_name }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($t->start)->format('F d, Y') }}</td>
                                <td>
                                    <div style="display:flex; justify-content: left; align-items: center; gap: 6px">
                                        <span>{{ \Carbon\Carbon::parse($t->end)->format('F d, Y') }}</span>
                                        @if ($t->end < now() && $t->state != 3)
                                            <img class="overdue" src="{{ asset('Images/Icons/Actions/Warning.png') }}" title="Your Task Is Overdue">
                                        @endif  
                                    </div>
                                </td>
                                @if ($t->state == 0)
                                    <td data-state="not_started" class="state"><span style="display: none">0</span>Not Started</td>
                                @elseif ($t->state == 1)
                                    <td data-state="stopped" class="state"><span style="display: none">1</span>Stopped</td>
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

                                <td>
                                    <div class="actions">
                                        <a href="{{ route('task.overview',  $t->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                        <form 
                                            action="{{ route('task.delete', $t->id) }}"
                                            onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                            method="post"
                                        >
                                            @csrf
                                            @method('delete')
                                            <button type="submit"><img class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('custom_vue')
    <script src="{{ asset('js/tableSort.js') }}"></script>
@endsection
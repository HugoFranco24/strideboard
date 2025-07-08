@extends('layouts.main')
@section('title')
    Calendar
@endsection

@section('body-title')
    Calendar
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
    <div class="box">
        <div id="calendar"></div>
    </div>
@endsection

@section('custom_vue')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function checkTextColor(hex) {
            hex = hex.replace('#', '');

            let r = parseInt(hex.substr(0,2),16);
            let g = parseInt(hex.substr(2,2),16);
            let b = parseInt(hex.substr(4,2),16);

            let hexColorCalc = ((r*299)+(g*587)+(b*114))/1000; //formula estranha mas fixe para ver se o bgcolor é light o suficiente para virar preto e vice versa

            return (hexColorCalc >= 128) ? '#1A1A1A' : '#E6E6E6';
        }

        var calendarEl = document.getElementById('calendar');
        var events = [];
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: window.innerWidth < 600 ? '' : 'dayGridMonth,timeGridWeek'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            events: '/dashboard/calendar/tasks',
            editable: true,

            // Event Load
            eventContent: function(info) {
                var eventTitle = info.event.title;
                var textColor = window.innerWidth < 600 ? null : checkTextColor(info.event.backgroundColor);
                var eventElement = document.createElement('div');
                if(window.innerWidth < 600){
                    eventElement.innerHTML = '<a class="calendarMore" href="/dashboard/tasks/overview/' + info.event.id + '"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"/></a> ' + eventTitle;
                }else{
                    if(textColor == '#1A1A1A'){
                    eventElement.innerHTML = '<a class="calendarMore" href="/dashboard/tasks/overview/' + info.event.id + '"><img src="{{ asset('Images/Icons/Actions/More.svg') }}"/></a> ' + eventTitle;
                    }else{
                        eventElement.innerHTML = '<a class="calendarMore" href="/dashboard/tasks/overview/' + info.event.id + '"><img src="{{ asset('Images/Icons/Actions/MoreWhite.svg') }}"/></a> ' + eventTitle;
                    }
                }
                eventElement.style.color = textColor;
                eventElement.style.padding = '2px';    

                return {
                    domNodes: [eventElement]
                };  
            },

            // Drag And Drop
            eventDrop: function(info) {
                var eventId = info.event.id;
                var newStartDate = info.event.start;
                var newEndDate = info.event.end || newStartDate;
                var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'post',
                    url: `/dashboard/calendar/task/update-date/${eventId}`,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        start_date: newStartDateUTC,
                        end_date: newEndDateUTC,
                    },
                });
            },

            // Task Resizing
            eventResize: function(info) {
                var eventId = info.event.id;
                var newEndDate = info.event.end;
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'post',
                    url: `/dashboard/calendar/task/update-date/${eventId}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        end_date: newEndDateUTC
                    },
                });
            },
        });

        calendar.render();

        document.addEventListener('DOMContentLoaded', function () {
            let newView = window.innerWidth < 600 ? 'listWeek' : 'dayGridMonth';
            calendar.changeView(newView);
        });
    </script>
@endsection
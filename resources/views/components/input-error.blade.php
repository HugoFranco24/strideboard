@props(['messages'])

@if ($messages)
    <ul style=
    "
        color: red;
        font-size: 14px;
        list-style-type: none;
        padding: 0;
        margin: 2px 0px;
    "
    >
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

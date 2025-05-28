@props([
    'id' => $id, 
    'name' => $name, 
    'autocomplete' => $autocomplete, 
    'visible' => $visible,
    'invisible' => $invisible
])

<style>
    .password-input {
        position: relative;
        display: inline-flex;
        width: 100%;
    }
    .icons {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
    }
    .icons img {
        width: 26px;
        height: 26px;
        cursor: pointer;
    }
</style>

<div class="password-input">
    <input type="password" name="{{ $name }}" id="{{ $id }}" required autocomplete="{{ $autocomplete }}">
    <div class="icons">
        <img 
            id="{{ $visible }}" 
            src="https://img.icons8.com/material-outlined/96/707070/visible--v1.png" 
            onclick="togglePassword('{{ $id }}', '{{ $visible }}', '{{ $invisible }}')"
        >
        <img 
            id="{{ $invisible }}" 
            src="https://img.icons8.com/material-outlined/96/707070/invisible--v1.png"
            onclick="togglePassword('{{ $id }}', '{{ $visible }}', '{{ $invisible }}')" 
            style="display: none"
        >
    </div>
</div>

<script>
    function togglePassword(inputID, visible, invisible) 
    {
        var visibleIcon = document.getElementById(visible);
        var invisibleIcon = document.getElementById(invisible);

        var input = document.getElementById(inputID);

        if(input.type == "password"){
            input.type = "text";
            visibleIcon.style.display = "none";
            invisibleIcon.style.display = "block";
        }else{
            input.type = "password";
            visibleIcon.style.display = "block";
            invisibleIcon.style.display = "none";
        }
    }
</script>
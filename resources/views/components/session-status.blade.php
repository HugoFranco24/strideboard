@props([
    'message' => $message,
])

<style>
    .sessionStatus{
        height: 56px;
        width: 100%;
        padding: 0px 14px 0px 14px;
        display: flex;
        justify-content: space-between;
        background-color: var(--box-color);
        color: var(--text-color);
        align-items: center;
        font-size: 18px;
        border-radius: 4px;
        margin-bottom: 20px;
        border-left: 16px solid #113f59;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    }
    .sessionStatus button{
        background-color: transparent;
        color: var(--text-color);
        border: none;
        display: block;
        cursor: pointer;
        font-size: 30px;
        height: 100%
    }
</style>

<div class="sessionStatus" id="sessionStatus">
    {{ $message }}
    <button onclick="closeStatus()">&times;</button>
</div>

<script>
    function closeStatus(){
        document.getElementById("sessionStatus").style.display = "none";
    }
</script>
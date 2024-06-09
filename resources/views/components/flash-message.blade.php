@if(session()->has('message'))
    <link rel="stylesheet" href="{{ asset('css/flash-messages/message.css') }}">
    <div class="message_container">
        <div x-data="{show: true}" x-init="setTimeout(() => show = false,3000)" x-show="show" class="message_div">
            <p>{{session('message')}}</p>
        </div>
    </div>
@endif


@if(session()->has('status'))
    <link rel="stylesheet" href="{{ asset('css/flash-messages/status.css') }}">
    <div class="status_container">
        <div x-data="{show: true}" x-init="setTimeout(() => show = false,3000)" x-show="show" class="status_div">
            <p>{{session('status')}}</p>
        </div>
    </div>
@endif
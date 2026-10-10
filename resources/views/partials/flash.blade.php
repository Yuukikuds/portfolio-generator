@if (session('status'))
    <div class="message message-success" role="status">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="message message-error" role="alert">{{ session('error') }}</div>
@endif

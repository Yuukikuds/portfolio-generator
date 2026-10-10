{{-- Profile picture, or the person's initials when there is no picture. Needs $p and $class. --}}
@if (! empty($p->profile_picture))
    <img class="{{ $class }}" src="{{ $p->profile_picture }}" alt="Profile picture of {{ $p->full_name }}">
@else
    <div class="{{ $class }} avatar-fallback" role="img" aria-label="Initials of {{ $p->full_name }}">{{ $tpl->initials($p->full_name) }}</div>
@endif

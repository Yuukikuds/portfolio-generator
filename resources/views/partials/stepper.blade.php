{{-- Progress: $current is 1 (Information), 2 (Template) or 3 (Preview). --}}
@php $steps = ['Information', 'Template', 'Preview']; @endphp
<ol class="stepper" aria-label="Progress">
    @foreach ($steps as $index => $label)
        @php
            $number = $index + 1;
            $state = $number < $current ? 'done' : ($number === $current ? 'current' : 'todo');
        @endphp
        <li class="step step-{{ $state }}" @if ($number === $current) aria-current="step" @endif>
            <span class="step-dot" aria-hidden="true">{!! $number < $current ? '&#10003;' : $number !!}</span>
            <span class="step-label">{{ $label }}</span>
        </li>
    @endforeach
</ol>

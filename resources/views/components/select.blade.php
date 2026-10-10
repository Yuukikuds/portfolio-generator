@props([
    'name',
    'label',
    'options' => [],
    'value' => '',
    'hint' => null,
])
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f-' . trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-');
    $message = $errors->first($key);
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($message ? $id . '-err' : ''));
@endphp
<div class="field">
    <label for="{{ $id }}">{{ $label }}</label>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        aria-invalid="{{ $message ? 'true' : 'false' }}"
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes }}
    >
        @foreach ($options as $option)
            <option value="{{ $option }}" @selected($value === $option)>{{ $option }}</option>
        @endforeach
    </select>
    @if ($hint)<p id="{{ $id }}-hint" class="hint">{{ $hint }}</p>@endif
    @if ($message)<p id="{{ $id }}-err" class="field-error">{{ $message }}</p>@endif
</div>

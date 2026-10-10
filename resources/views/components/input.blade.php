@props([
    'name',
    'label',
    'value' => '',
    'type' => 'text',
    'required' => false,
    'hint' => null,
])
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f-' . trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-');
    $message = $errors->first($key);
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($message ? $id . '-err' : ''));
@endphp
<div class="field">
    <label for="{{ $id }}">{{ $label }}@if ($required)<span class="required" aria-hidden="true"> *</span>@endif</label>
    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @if ($required) aria-required="true" @endif
        aria-invalid="{{ $message ? 'true' : 'false' }}"
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes }}
    >
    @if ($hint)<p id="{{ $id }}-hint" class="hint">{{ $hint }}</p>@endif
    @if ($message)<p id="{{ $id }}-err" class="field-error">{{ $message }}</p>@endif
</div>

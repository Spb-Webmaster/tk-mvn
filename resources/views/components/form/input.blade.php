@props([
    'name',
    'label',
    'type'        => 'text',
    'required'    => false,
    'placeholder' => '',
])

@php $hasError = $errors->has($name); @endphp

<div class="ff app_input_group">
    <label>{{ $label }}@if($required) <span class="req">*</span>@endif</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'app_input_name' . ($hasError ? ' _error' : '')]) }}
        placeholder="{{ $placeholder }}"
        value="{{ old($name) }}"
        @required($required)
    >
    @if($hasError)<div class="app_input_error">{{ $errors->first($name) }}</div>@endif
</div>

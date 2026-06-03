@props([
    'name',
    'label',
    'required'    => false,
    'placeholder' => '',
    'full'        => true,
])

@php $hasError = $errors->has($name); @endphp

<div class="ff{{ $full ? ' fg-full' : '' }} app_input_group">
    <label>{{ $label }}@if($required) <span class="req">*</span>@endif</label>
    <textarea
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'app_input_name' . ($hasError ? ' _error' : '')]) }}
        placeholder="{{ $placeholder }}"
    >{{ old($name) }}</textarea>
    @if($hasError)<div class="app_input_error">{{ $errors->first($name) }}</div>@endif
</div>

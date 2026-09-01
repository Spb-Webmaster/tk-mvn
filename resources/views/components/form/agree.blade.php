@props(['name' => 'agree', 'inputClass' => ''])

<div {{ $attributes->merge(['class' => 'form-agree']) }}>
    <label class="form-agree-label">
        <input type="checkbox" name="{{ $name }}" value="1" class="{{ $inputClass }}" @checked(old($name)) required>
        <span class="form-agree-box" aria-hidden="true">
            <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                <path d="M2 6.2l2.6 2.6L10 3.4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span class="form-agree-text">Я даю согласие на обработку персональных данных и принимаю <a href="{{ route('privacy') }}" target="_blank" rel="noopener">политику конфиденциальности</a></span>
    </label>
    @error($name)
        <span class="form-agree-error">{{ $message }}</span>
    @enderror
</div>

@props([
    'model',
    'field',
    'label' => null,
    'type'  => 'html',
])

@php
    $isAdmin = auth('moonshine')->check();
    $alias   = strtolower(class_basename($model));
    $uid     = 'ie-' . $alias . '-' . $model->id . '-' . $field;
    $label   = $label ?? ucfirst($field);
    $raw     = $model->$field ?? '';
    $current = $type === 'json'
        ? json_encode($raw ?: [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        : $raw;
@endphp

<div class="inline-edit-wrap{{ $isAdmin ? ' inline-edit-wrap--admin' : '' }}"
     @if($isAdmin) data-inline-edit @endif>

    @if($isAdmin)
        <button class="inline-edit-btn"
                type="button"
                data-open="{{ $uid }}"
                title="Редактировать: {{ $label }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
        </button>
    @endif

    <div data-inline-edit-content="{{ $uid }}">{{ $slot }}</div>
</div>

@if($isAdmin)
<div class="inline-edit-modal" id="{{ $uid }}" data-inline-edit-modal>
    <div class="inline-edit-modal__backdrop" data-close></div>
    <div class="inline-edit-modal__box">

        <div class="inline-edit-modal__head">
            <span class="inline-edit-modal__title">{{ $label }}</span>
            @if($type === 'json')
                <span class="inline-edit-modal__hint">JSON · после сохранения страница перезагрузится</span>
            @endif
            <button class="inline-edit-modal__close" type="button" data-close aria-label="Закрыть">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <textarea class="inline-edit-modal__textarea"
                  id="{{ $uid }}-ta"
                  spellcheck="false"
                  autocomplete="off"
                  autocorrect="off"
                  autocapitalize="off"
        >{{ $current }}</textarea>

        <div class="inline-edit-modal__foot">
            <button class="inline-edit-modal__save"
                    type="button"
                    data-save
                    data-model="{{ $alias }}"
                    data-id="{{ $model->id }}"
                    data-field="{{ $field }}"
                    data-ta="{{ $uid }}-ta"
                    data-type="{{ $type }}">
                Сохранить
            </button>
            <button class="inline-edit-modal__cancel"
                    type="button"
                    data-close>
                Отмена
            </button>
        </div>

    </div>
</div>
@endif

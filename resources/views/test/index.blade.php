<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test — t_zoo_item (application_id=46, type=last-actions)</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; padding: 24px; background: #f5f5f5; color: #333; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        .meta { color: #888; margin-bottom: 24px; font-size: 12px; }
        .item { background: #fff; border: 1px solid #e0e0e0; margin-bottom: 20px; padding: 20px 24px; }
        .item-header { display: flex; gap: 16px; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .item-id { font-size: 11px; color: #999; }
        .item-slug { font-weight: 700; color: #095C9A; font-size: 14px; }
        .item-title { font-size: 16px; font-weight: 700; color: #152040; margin-bottom: 12px; }
        .label { font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #888; margin-bottom: 4px; }
        .short-desc { font-size: 13px; color: #444; line-height: 1.6; margin-bottom: 14px; background: #f9f9f9; padding: 10px 12px; border-left: 3px solid #c4963a; }
        .full-desc { font-size: 13px; color: #444; line-height: 1.6; background: #f9f9f9; padding: 10px 12px; border-left: 3px solid #095C9A; }
        .empty { color: #bbb; font-style: italic; }
        details { margin-top: 10px; }
        summary { cursor: pointer; font-size: 11px; color: #888; }
        pre { margin-top: 8px; background: #f3f3f3; padding: 12px; overflow: auto; font-size: 11px; }
    </style>
</head>
<body>

<h1>t_zoo_item — application_id=46, type=last-actions</h1>
<p class="meta">Выводится 5 записей · Фильтр: params → config.primary_category = 1175 · UUID краткого: <code>2f441995…</code> · UUID полного: <code>4c501733…</code></p>

@if($items->isEmpty())
    <p>Нет записей.</p>
@else
    @foreach($items as $item)
    @php
        $elements   = json_decode($item->elements ?? '{}', true) ?? [];
        $shortDesc  = $elements['2f441995-200a-47d2-ae6c-a2e2d26348cc'][0]['value'] ?? null;
        $fullDesc   = $elements['4c501733-fe6b-40e5-a6df-7d6dacb023a3'][0]['value'] ?? null;
    @endphp
    <div class="item">
        <div class="item-header">
            <span class="item-id">#{{ $item->id }}</span>
            <span class="item-slug">{{ $item->alias ?? '—' }}</span>
        </div>

        <div class="item-title">{{ $item->name ?? '—' }}</div>

        <div class="label">Краткое описание (2f441995…)</div>
        @if($shortDesc)
            <div class="short-desc">{!! $shortDesc !!}</div>
        @else
            <div class="short-desc empty">— пусто —</div>
        @endif

        <div class="label">Полное описание (77b66db1…)</div>
        @if($fullDesc)
            <div class="full-desc">{!! $fullDesc !!}</div>
        @else
            <div class="full-desc empty">— пусто —</div>
        @endif

        <details>
            <summary>Все ключи elements</summary>
            <pre>{{ json_encode(array_keys($elements), JSON_UNESCAPED_UNICODE) }}</pre>
        </details>
    </div>
    @endforeach
@endif

</body>
</html>

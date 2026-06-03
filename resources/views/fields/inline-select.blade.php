<div
    x-data="{
        sel: @json($selected).map(String),
        busy: false,
        ok: false,
        save() {
            this.busy = true
            fetch('{{ $saveUrl }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ categories: this.sel })
            })
            .then(() => { this.ok = true; setTimeout(() => this.ok = false, 2000) })
            .catch(() => {})
            .finally(() => this.busy = false)
        }
    }"
    class="flex items-center gap-1"
>
    <select
        multiple
        x-model="sel"
        @change="save"
        :disabled="busy"
        class="text-sm rounded border border-slate-200 px-1 py-0.5"
        style="min-width: 150px"
    >
        @foreach($options as $id => $title)
            <option value="{{ $id }}">{{ $title }}</option>
        @endforeach
    </select>
    <span x-show="busy" class="text-xs text-gray-400">…</span>
    <span x-show="ok" x-cloak class="text-xs text-green-500 font-bold">✓</span>
</div>

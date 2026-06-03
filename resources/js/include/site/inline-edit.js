export function initInlineEdit() {
    if (!document.querySelector('[data-inline-edit]')) return;

    document.addEventListener('click', (e) => {
        const openBtn = e.target.closest('[data-open]');
        if (openBtn && openBtn.closest('[data-inline-edit]')) {
            const modal = document.getElementById(openBtn.dataset.open);
            if (modal) openModal(modal);
            return;
        }

        const closeEl = e.target.closest('[data-close]');
        if (closeEl) {
            const modal = closeEl.closest('[data-inline-edit-modal]');
            if (modal) closeModal(modal);
            return;
        }

        const saveBtn = e.target.closest('[data-save]');
        if (saveBtn) handleSave(saveBtn);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const opened = document.querySelector('[data-inline-edit-modal].is-open');
            if (opened) closeModal(opened);
        }
    });
}

function openModal(modal) {
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    const ta = modal.querySelector('textarea');
    if (ta) setTimeout(() => ta.focus(), 50);
}

function closeModal(modal) {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
}

async function handleSave(btn) {
    const taId = btn.dataset.ta;
    const ta   = document.getElementById(taId);
    if (!ta) return;

    const isJson = btn.dataset.type === 'json';

    const payload = {
        model: btn.dataset.model,
        id:    parseInt(btn.dataset.id, 10),
        field: btn.dataset.field,
        value: ta.value,
    };

    const originalText = btn.textContent;
    btn.disabled    = true;
    btn.textContent = 'Сохранение…';

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const res  = await fetch('/admin-inline-edit', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept':       'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (res.ok && data.success) {
            btn.textContent      = '✓ Сохранено';
            btn.style.background = '#1a8a50';

            if (isJson) {
                // JSON-поля: перезагружаем страницу — контент генерируется на сервере
                setTimeout(() => window.location.reload(), 800);
            } else {
                setTimeout(() => {
                    const uid     = taId.replace(/-ta$/, '');
                    const content = document.querySelector(`[data-inline-edit-content="${uid}"]`);
                    if (content) {
                        const target = content.firstElementChild ?? content;
                        target.innerHTML = ta.value;
                    }
                    const modal = document.getElementById(uid);
                    if (modal) closeModal(modal);
                    btn.disabled         = false;
                    btn.textContent      = 'Сохранить';
                    btn.style.background = '';
                }, 1000);
            }
        } else {
            alert(data.error ?? 'Ошибка при сохранении');
            btn.disabled         = false;
            btn.textContent      = originalText;
            btn.style.background = '';
        }
    } catch {
        alert('Ошибка соединения');
        btn.disabled         = false;
        btn.textContent      = originalText;
        btn.style.background = '';
    }
}

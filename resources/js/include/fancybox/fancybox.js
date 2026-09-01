import { Fancybox } from "@fancyapps/ui/dist/fancybox/";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
import '../mz-modal/mz-modal.js';
import {asyncExecution} from "../form_async/async_execution";
import {scrollCabinetMessages} from "./cabinet_message";
import {mzSelectInit} from "../select/mz-select";
import {validateAgree} from "../site/form-agree";

/** ── MZ-Modal для AJAX-форм ── **/
const _formModalEl = document.createElement('div');
_formModalEl.id = 'mz-form-modal';
_formModalEl.className = 'modal';
_formModalEl.setAttribute('role', 'dialog');
_formModalEl.setAttribute('aria-modal', 'true');
document.body.appendChild(_formModalEl);

const mzModal = window.M.Modal.init(_formModalEl, {
    opacity: 0.72,
    dismissible: true,
    onOpenStart: function() {
        document.documentElement.style.paddingRight = '';
    },
    onCloseEnd: function() {
        document.documentElement.style.paddingRight = '';
        _formModalEl.innerHTML = '';
    },
});


/*Fancybox.bind('[data-fancybox]', {

    zoomEffect: false,
    hideScrollbar: false, // Оставляем скроллбар видимым
    dragToClose: false,
    clickOutside: false,
    preventViewportChange: true, // Добавьте эту опцию, чтобы предотвратить смену положения просмотра
    userSelectableContent: true, // Разрешаем выделять текст внутри модального окна
    touch: false,

});*/

Fancybox.bind('[data-fancybox^="gallery"]', {
    animated: true,
    dragToClose: true,
});

Fancybox.bind('[data-fancybox^="video"]', {
    animated: false,
    dragToClose: false,
    backdropClick: 'close',
    Html: {
        iframe: {
            preload: false,
        },
    },
});

/** получаем csrf **/
const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
const csrf = metaElements.length > 0 ? metaElements[0].content : "";
/** получаем csrf **/


const fancyWindows = Array.from(document.querySelectorAll('.open-fancybox'))

/** открыть open-fancybox **/
for (let fancyWindow of fancyWindows) {
    fancyWindow.addEventListener('click', openModal);
}


function initZaprosModal(container) {
    const form      = container.querySelector('#mzr-form');
    const successEl = container.querySelector('#mzr-success');
    if (!form || !successEl) return;

    const phoneInput = container.querySelector('.imask');
    let phoneMask = null;
    if (phoneInput && window.IMask) {
        phoneMask = new window.IMask(phoneInput, { mask: '+{0}(000)000-00-00' });
    }

    function showError(input, msg) {
        const field = input.closest('.mzr-field');
        field.classList.add('mzr-field--error');
        const err = document.createElement('span');
        err.className = 'mzr-error-msg';
        err.textContent = msg;
        field.appendChild(err);
        input.addEventListener('input', () => {
            field.classList.remove('mzr-field--error');
            field.querySelector('.mzr-error-msg')?.remove();
        }, { once: true });
    }

    function validate() {
        form.querySelectorAll('.mzr-field--error').forEach(f => f.classList.remove('mzr-field--error'));
        form.querySelectorAll('.mzr-error-msg').forEach(e => e.remove());

        let valid = true;
        const nameInput = form.querySelector('#mzr-name');

        if (!nameInput.value.trim()) {
            showError(nameInput, 'Введите имя');
            valid = false;
        }

        const digits = phoneMask
            ? phoneMask.unmaskedValue.replace(/\D/g, '')
            : phoneInput.value.replace(/\D/g, '');
        if (digits.length < 11) {
            showError(phoneInput, 'Введите телефон');
            valid = false;
        }

        if (!validateAgree(form)) {
            valid = false;
        }

        return valid;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!validate()) return;

        const submitBtn = form.querySelector('.mzr-btn-submit');
        const formData  = new FormData(form);
        const data      = {};
        formData.forEach((val, key) => { data[key] = val; });

        submitBtn.disabled = true;

        try {
            const response = await fetch('/send-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrf,
                },
                body: JSON.stringify(data),
            });
            const result = await response.json();
            if (!result.errors) {
                form.style.display = 'none';
                successEl.classList.add('show');
            }
        } catch (err) {
            console.error('Zapros form error:', err);
        } finally {
            submitBtn.disabled = false;
        }
    });
}

async function openModal(e) {
    e.preventDefault();
    try {
        const parentEl     = e.target.closest('.open-fancybox');
        const formTemplate = parentEl.dataset.form;
        const transferData = parentEl.dataset.transfer;
        const template     = { template: formTemplate, author: '@AxeldMaster', data: transferData };

        const response = await fetch('/fancybox-ajax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
            body: JSON.stringify(template),
        });

        if (!response.ok) {
            console.error(`HTTP error: ${response.status}`);
            return;
        }

        const html = await response.text();

        _formModalEl.innerHTML = html;

        mzModal.open(parentEl);

        asyncExecution();
        scrollCabinetMessages();
        mzSelectInit(_formModalEl);
        initZaprosModal(_formModalEl);

    } catch (err) {
        console.error('Ошибка AJAX:', err.message);
    }
}

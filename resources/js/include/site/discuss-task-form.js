import {validateAgree} from './form-agree.js';

export function discussTaskForm() {
    const form = document.getElementById('discuss-task-form');
    if (!form) return;

    const phoneInput = form.querySelector('input[name="phone"]');

    function showError(input, msg) {
        const field = input.closest('.cta-field');
        field.classList.add('cta-field--error');
        const err = document.createElement('span');
        err.className = 'cta-error-msg';
        err.textContent = msg;
        field.appendChild(err);
        input.addEventListener('input', () => {
            field.classList.remove('cta-field--error');
            field.querySelector('.cta-error-msg')?.remove();
        }, { once: true });
    }

    function validate() {
        form.querySelectorAll('.cta-field--error').forEach(f => f.classList.remove('cta-field--error'));
        form.querySelectorAll('.cta-error-msg').forEach(e => e.remove());

        let valid = true;
        const nameInput = form.querySelector('input[name="name"]');

        if (!nameInput.value.trim()) {
            showError(nameInput, 'Введите имя');
            valid = false;
        }

        if (phoneInput.value.replace(/\D/g, '').length < 11) {
            showError(phoneInput, 'Введите телефон');
            valid = false;
        }

        if (!validateAgree(form)) {
            valid = false;
        }

        return valid;
    }

    form.addEventListener('submit', function (e) {
        if (!validate()) {
            e.preventDefault();
        }
    });
}

export function fieldErrors(errors, parentEl) {

    console.log('errors')
    console.log(errors)

    // Получаем список групп полных ввода (.app_input_group)
    const inputGroups = Array.from(parentEl.querySelectorAll('.app_input_group'));

    /** помечаем input` s **/
    // Проходим по каждой группе
    inputGroups.forEach((inputGroup) => {
        const input = inputGroup.querySelector('.app_input_name');
        let errorMessageDiv = inputGroup.querySelector('.app_input_error');

        if (errors.hasOwnProperty(input.name)) {
            input.classList.add('_error');
            if (!errorMessageDiv) {
                errorMessageDiv = document.createElement('div');
                errorMessageDiv.className = 'app_input_error';
                inputGroup.appendChild(errorMessageDiv);
            }
            errorMessageDiv.textContent = errors[input.name][0];
        } else {
            input.classList.remove('_error');
            if (errorMessageDiv) errorMessageDiv.remove();
        }
    });


     /** помечаем select` s **/
        // Теперь проверяем группы select (если нужны)
    const selGroups = Array.from(parentEl.querySelectorAll('.app_select_group'));
    // Используем отдельный класс для select-групп



    selGroups.forEach((selGroup) => {
        const selectedOption = selGroup.querySelector('.selected');
        const errorMessageDivSel = selGroup.querySelector('.app_input_error');
        //console.log(selectedOption.dataset.select.trim())
        if (errors.hasOwnProperty(selectedOption.dataset.select.trim())) { // Триммируем пробелы
            selectedOption.classList.add('_error');
            errorMessageDivSel.textContent = errors[selectedOption.dataset.select.trim()][0];
        } else {
            selectedOption.classList.remove('_error');
            errorMessageDivSel.textContent = '';
        }
    });

}

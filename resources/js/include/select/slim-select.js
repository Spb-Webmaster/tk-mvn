
import SlimSelect from 'slim-select'
import 'slim-select/styles' // optional css import method
import 'slim-select/scss' // optional scss import method

export function slimSelect() {
    new SlimSelect({
        select: '.selectElement'
    })
}

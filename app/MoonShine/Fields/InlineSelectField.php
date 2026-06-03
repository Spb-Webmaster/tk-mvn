<?php

namespace App\MoonShine\Fields;

use Closure;
use Illuminate\Support\HtmlString;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\Preview;

class InlineSelectField extends Preview
{
    protected Closure $optionsResolver;
    protected Closure $saveUrlResolver;

    public function options(Closure $resolver): static
    {
        $this->optionsResolver = $resolver;
        return $this;
    }

    public function saveUrl(Closure $resolver): static
    {
        $this->saveUrlResolver = $resolver;

        return $this->changePreview(fn($value, Field $field) => $this->renderSelect($field));
    }

    private function renderSelect(Field $field): HtmlString
    {
        $item     = $field->getData()?->getOriginal();
        $selected = $item ? $item->{$field->getColumn()}->pluck('id')->all() : [];
        $options  = ($this->optionsResolver)($item);
        $saveUrl  = ($this->saveUrlResolver)($item);

        return new HtmlString(
            view('fields.inline-select', compact('selected', 'options', 'saveUrl'))->render()
        );
    }
}

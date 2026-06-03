# Паттерны проекта

---

## 1. Laravel Page Pattern — от модели до вывода

Полный цикл создания страницы с выводом данных из БД и настроек админки. Пример — страница новостей `/poleznoe/novosti`.

### Структура директорий

```
src/Domain/{Section}/ViewModels/{Model}ViewModel.php
app/Http/Controllers/Pages/{Section}Controller.php
app/Enums/Pages/PageTemplate.php
app/Enums/Resources/TeaserTemplate.php
app/Enums/Resources/FullTemplate.php
resources/views/pages/resources/list.blade.php        ← единый шаблон списка
resources/views/pages/resources/show.blade.php        ← единый шаблон детальной
resources/views/pages/common/pages/templates/{template}.blade.php
resources/views/pages/common/resourses/templates/teaser/{template}.blade.php
resources/views/pages/common/resourses/templates/full/{template}.blade.php
resources/views/components/seo/meta-paginated.blade.php
routes/web.php
routes/breadcrumbs.php
```

---

### Именование маршрутов

URL-адреса — транслитерация (уже проиндексированы, не менять).
Имена маршрутов — только английские слова.

```php
// routes/web.php
Route::get('/poleznoe/novosti',        'news')->name('resources.news');
Route::get('/poleznoe/novosti/{slug}', 'newsShow')->name('resources.news.show');
```

| URL | Имя маршрута |
|-----|-------------|
| `/poleznoe/novosti` | `resources.news` |
| `/poleznoe/novosti/{slug}` | `resources.news.show` |

> **Важно:** маршруты с `{slug}` на одном уровне с конкретными путями (например `/poleznoe/{slug}` и `/poleznoe/novosti`) должны стоять **после** конкретных маршрутов — иначе `{slug}` перехватит их раньше.

---

### Шаг 1 — Модель: scopePublished

`app/Models/News.php`

```php
use Illuminate\Database\Eloquent\Builder;

public function scopePublished(Builder $query): Builder
{
    return $query->where('published', 1)->orderBy('sorting');
}
```

> Вызов в коде: `News::published()->...` — Laravel сам находит `scopePublished` через соглашение `scopeXxx`.

---

### Шаг 2 — ViewModel

`src/Domain/News/ViewModels/NewsViewModel.php`

```php
namespace Domain\News\ViewModels;

use App\Models\News;
use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class NewsViewModel
{
    use Makeable;

    // Данные страницы из админки (MoonShine Settings)
    public function getPageData(): Fluent
    {
        return new Fluent(Setting::getGroup('poleznoe_novosti')->data ?? []);
    }

    // Список записей с пагинацией
    public function getPublished(): LengthAwarePaginator
    {
        return News::published()->paginate(config('site.constants.paginate'));
    }

    // Одна запись по slug для детальной страницы
    public function getBySlug(string $slug): News
    {
        return News::published()->where('slug', $slug)->firstOrFail();
    }
}
```

**Правила:**
- Все запросы к БД — только в ViewModel, никогда в контроллере
- `Makeable` — трейт, добавляет `::make()` вместо `new`
- `Fluent` — доступ через `$page->title`; несуществующий ключ возвращает `null`, не бросает ошибку
- `getPublished()` возвращает `LengthAwarePaginator`, не `Collection` — не ставить `?` перед типом
- Количество записей на странице: `config('site.constants.paginate')`

---

### Шаг 3 — Контроллер (список)

`app/Http/Controllers/Pages/ResourcesController.php`

```php
public function news(): View
{
    $vm    = NewsViewModel::make();
    $items = $vm->getPublished();
    $page  = $vm->getPageData();

    return view('pages.resources.list', [
        'page'            => $page,
        'items'           => $items,
        'pageSuffix'      => $this->pageSuffix($items),
        'template'        => PageTemplate::from($page->page_template ?? PageTemplate::Default->value),
        'teaser_template' => TeaserTemplate::from($page->section_template ?? TeaserTemplate::Default->value),
        'section'         => 'resources.news',
        'route'           => 'resources.news.show',
    ]);
}
```

> `$items` сохраняется в переменную и передаётся — иначе `getPublished()` вызовется дважды и будет два запроса к БД.

> `section` и `route` передаются из контроллера — вьюха `list.blade.php` универсальна для всех моделей.

---

### Шаг 3б — Контроллер (детальная страница)

```php
public function newsShow(string $slug): View
{
    $vm   = NewsViewModel::make();
    $item = $vm->getBySlug($slug);
    $page = $vm->getPageData();

    return view('pages.resources.show', [
        'page'     => $page,
        'item'     => $item,
        'resource' => 'news',
    ]);
}
```

> `$resource` — строка, передаётся в `$item->template->view($resource)` для поиска секционного переопределения шаблона.

---

### Шаг 4 — SEO-компонент с пагинацией

`resources/views/components/seo/meta-paginated.blade.php`

```blade
@props(['page', 'items'])
@php
    $currentPage     = $items->currentPage();
    $suffix          = $currentPage > 1 ? ' — Страница ' . $currentPage : '';
    $baseTitle       = $page->metatitle ?: $page->title ?: '';
    $baseDescription = $page->description ?: '';
    $baseKeywords    = $page->keywords ?: '';
@endphp
<x-seo.meta
    title="{{ $baseTitle ? $baseTitle . $suffix : '' }}"
    description="{{ $baseDescription ? $baseDescription . $suffix : '' }}"
    keywords="{{ $baseKeywords ? $baseKeywords . $suffix : '' }}"
/>
```

**Правила:**
- Суффикс `— Страница N` добавляется только если есть базовое значение
- Если поле пустое — передаётся `''`, тогда `x-seo.meta` подставляет дефолт из конфига
- `metatitle` имеет приоритет над `title` для SEO

---

### Шаг 4б — Три Enum шаблонов

Шаблоны разделены на три класса по назначению. Каждый Enum используется в своём контексте.

---

#### PageTemplate — шаблон блока страницы

`app/Enums/Pages/PageTemplate.php`

```php
enum PageTemplate: string
{
    case Default = 'default';

    public function view(string $section): string
    {
        $specific = "pages.{$section}.pages.templates.{$this->value}";
        $common   = "pages.common.pages.templates.{$this->value}";

        if (view()->exists($specific)) return $specific;

        return $common;
    }
}
```

**Используется:** в MoonShine Pages (поле `page_template`). Контроллер передаёт как `$template`.
**Шаблоны:** `resources/views/pages/common/pages/templates/`
**Вызов во вьюхе:** `@include($template->view($section))`

Приоритет поиска шаблона:
1. Секционный: `pages/{section}/pages/templates/{value}.blade.php`
2. Общий: `pages/common/pages/templates/{value}.blade.php`

---

#### TeaserTemplate — шаблон тизера (одного элемента списка)

`app/Enums/Resources/TeaserTemplate.php`

```php
enum TeaserTemplate: string
{
    case Default = 'default';

    public function view(string $section): string
    {
        $specific = "pages.{$section}.resourses.templates.teaser.{$this->value}";
        $common   = "pages.common.resourses.templates.teaser.{$this->value}";

        if (view()->exists($specific)) return $specific;

        return $common;
    }
}
```

**Используется:** в MoonShine Pages (поле `section_template`). Контроллер передаёт как `$teaser_template`.
**Шаблоны:** `resources/views/pages/common/resourses/templates/teaser/`
**Вызов во вьюхе:** `@include($teaser_template->view($section), ['item' => $item, 'route' => $route])`

Приоритет поиска шаблона:
1. Секционный: `pages/{section}/resourses/templates/teaser/{value}.blade.php`
2. Общий: `pages/common/resourses/templates/teaser/{value}.blade.php`

---

#### FullTemplate — шаблон полной страницы записи

`app/Enums/Resources/FullTemplate.php`

```php
enum FullTemplate: string
{
    case Default = 'default';

    public function view(string $resource): string
    {
        $specific = "pages.resourses.{$resource}.templates.full.{$this->value}";
        $common   = "pages.common.resourses.templates.full.{$this->value}";

        if (view()->exists($specific)) return $specific;

        return $common;
    }
}
```

**Используется:** в MoonShine Resources (поле `template` модели). Берётся из `$item->template`.
**Шаблоны:** `resources/views/pages/common/resourses/templates/full/`
**Вызов во вьюхе:** `@include($item->template->view($resource), ['item' => $item])`

Приоритет поиска шаблона:
1. Секционный: `pages/resourses/{resource}/templates/full/{value}.blade.php`
2. Общий: `pages/common/resourses/templates/full/{value}.blade.php`

> Добавить новый шаблон в любой Enum = создать файл в соответствующей папке + добавить `case` с именем файла. Вьюха не меняется.

---

### Шаг 4в — Шаблон блока страницы (PageTemplate::default)

`resources/views/pages/common/pages/templates/default.blade.php`

```blade
@if($items->currentPage() === 1)
    @if($page->desc)
        <div class="desc">{!! $page->desc !!}</div>
    @endif
@endif
```

**Правила:**
- Получает все переменные из родительской вьюхи через Blade scope inheritance
- Отвечает только за контент уровня страницы (описание, доп. блоки)
- Вывод описания — только на первой странице пагинации

---

### Шаг 4г — Шаблон тизера (TeaserTemplate::default)

`resources/views/pages/common/resourses/templates/teaser/default.blade.php`

```blade
<div class="default">
    <a href="{{ route($route, $item->slug) }}" class="teaser">
        <span class="teaser__title">{{ $item->title }}</span>
    </a>
</div>
```

**Правила:**
- Получает `$item` и `$route` явно из `@include`
- Отвечает за вид одной карточки в списке
- Имя файла = значение `case` в Enum

---

### Шаг 5 — Единый шаблон списка

`resources/views/pages/resources/list.blade.php`

```blade
@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')
    <div class="content_page">
        <div class="block">
            <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
            @if($page->title)
                <h1 class="h1">{{ $page->title }}</h1>
            @endif

            @foreach($items as $item)
                @include($teaser_template->view($section), ['item' => $item, 'route' => $route])
            @endforeach
            {{ $items->withQueryString()->links('pagination::default') }}

            @include($template->view($section))
        </div>
    </div>
@endsection
```

**Правила:**
- Один файл для всех разделов — `news`, `laws`, `diplomas`, `seminar`, `important`, `resources`
- `$teaser_template` — итерация элементов списка
- `$template` — блок уровня страницы (описание и пр.) после пагинации
- Хлебные крошки: `Route::currentRouteName()` — имя маршрута не хардкодится
- `$section` и `$route` приходят из контроллера — вьюха универсальна

---

### Шаг 5б — Единый шаблон детальной страницы

`resources/views/pages/resources/show.blade.php`

```blade
@extends('layouts.layout')
<x-seo.meta
    title="{{ $item->metatitle ?: $item->title }}"
    description="{{ $item->description }}"
    keywords="{{ $item->keywords }}"
/>
@section('content')

    <div class="content_page">
        <div class="block">

            <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName(), $item) }}</div>
            @if($item->title)
                <h1 class="h1">{{ $item->title }}</h1>
            @endif

            @include($item->template->view($resource), ['item' => $item])

        </div>
    </div>

@endsection
```

**Правила:**
- Один файл для всех разделов — вместо отдельных `news-show`, `laws-show` и т.д.
- `$resource` приходит из контроллера — строка (`'news'`, `'laws'`, `'diplomas'` и т.д.)
- SEO использует поля самой записи (`$item->metatitle`, `$item->description`, `$item->keywords`)
- `metatitle` имеет приоритет над `title` для SEO
- Хлебные крошки получают `$item` вторым аргументом

---

### Шаг 6 — Хлебные крошки

`routes/breadcrumbs.php`

```php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});

Breadcrumbs::for('resources.news', function ($trail) {
    $trail->parent('home');
    $trail->push('Новости', route('resources.news'));
});

Breadcrumbs::for('resources.news.show', function ($trail, $item) {
    $trail->parent('resources.news');
    $trail->push($item->title, route('resources.news.show', $item->slug));
});
```

**Правила:**
- Имя для `Breadcrumbs::for(...)` совпадает с именем маршрута в `web.php`
- В вьюхе: `Breadcrumbs::render(Route::currentRouteName())` — имя берётся из текущего маршрута автоматически
- Для детальной страницы `$item` передаётся вторым аргументом: `Breadcrumbs::render(Route::currentRouteName(), $item)`
- Цепочка строится через `$trail->parent(...)` — каждый уровень ссылается на родителя

---

### Итоговая схема потока данных

```
MoonShine Pages (настройки)
  page_template    → PageTemplate enum   → $template         → pages/common/pages/templates/
  section_template → TeaserTemplate enum → $teaser_template  → pages/common/resourses/templates/teaser/

MoonShine Resources (поле записи)
  template         → FullTemplate enum   → $item->template   → pages/common/resourses/templates/full/

pages/resources/list.blade.php  (один файл для всех разделов)
  @foreach → @include($teaser_template->view($section))   ← один тизер
  @include($template->view($section))                     ← блок страницы (desc и пр.)

pages/resources/show.blade.php  (один файл для всех разделов)
  @include($item->template->view($resource))              ← полная страница записи
  $resource = 'news' | 'laws' | 'diplomas' | ...         ← приходит из контроллера
```

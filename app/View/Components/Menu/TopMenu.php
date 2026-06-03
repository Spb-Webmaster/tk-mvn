<?php

namespace App\View\Components\Menu;

use App\Models\Setting;
use App\Models\Training;
use App\Models\TrainingCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopMenu extends Component
{
    public array $navItems;
    public $trainingCategories;
    public $nearestTraining;
    public string $phone;

    public function __construct()
    {
        $training = Setting::getGroup('training')->data ?? [];
        $photo    = Setting::getGroup('photo')->data ?? [];
        $video    = Setting::getGroup('video')->data ?? [];
        $response = Setting::getGroup('response')->data ?? [];

        $items = [
            ['label' => 'Главная',                                          'route' => 'home',           'url' => null, 'pattern' => 'home'],
            ['label' => 'Расписание',                                          'route' => 'schedule',           'url' => null, 'pattern' => 'schedule'],
            ['label' => $training['menu_title'] ?? 'Программы',             'route' => 'training',       'url' => null, 'pattern' => 'training*', 'hasDropdown' => true],
            ['label' => 'О тренере',                                        'route' => 'trainer',        'url' => null, 'pattern' => 'trainer'],
            [
                'label'         => 'О нас',
                'route'         => null,
                'url'           => null,
                'pattern'       => ['last-actions*', 'photo*', 'video*', 'response*'],
                'hasDropdown'   => true,
                'dropdownItems' => [
                    ['label' => 'Новости',                              'href' => route('last-actions'), 'pattern' => 'last-actions*'],
                    ['label' => $photo['menu_title']    ?? 'Фотогалерея', 'href' => route('photo'),        'pattern' => 'photo*'],
                    ['label' => $video['menu_title']    ?? 'Видеообзоры', 'href' => route('video'),        'pattern' => 'video*'],
                    ['label' => $response['menu_title'] ?? 'Отзывы',      'href' => route('response'),     'pattern' => 'response*'],
                ],
            ],
            ['label' => 'Контакты',                                         'route' => route('contact'), 'url' => null, 'pattern' => 'contact'],
        ];

        // Предвычисляем href для каждого пункта, чтобы в шаблоне не вызывать route().
        // resolveHref различает имя маршрута от готового URL — можно передавать оба варианта.
        $this->navItems = array_map(fn($item) => [
            ...$item,
            'href' => $this->resolveHref($item['route'], $item['url']),
        ], $items);

        $this->phone = Setting::getGroup('constants')->data['contact_phone'] ?? '';

        $this->trainingCategories = TrainingCategory::orderBy('sorting')->get();

        $this->nearestTraining = Training::published()
            ->whereNotNull('ev_date_from')
            ->where('ev_date_from', '>=', now()->toDateString())
            ->orderBy('ev_date_from')
            ->with('categories')
            ->first();
    }

    // Если передан готовый URL (начинается с http/https или /) — используем как есть.
    // Если передано имя маршрута — вызываем route(). Это позволяет в navItems
    // использовать и 'home', и route('home'), и 'https://external.com'.
    private function resolveHref(?string $route, ?string $url): string
    {
        if ($url) {
            return $url;
        }

        if (!$route) {
            return '#';
        }

        if (str_starts_with($route, 'http') || str_starts_with($route, '/')) {
            return $route;
        }

        return route($route);
    }

    public function render(): View|Closure|string
    {
        return view('components.menu.top-menu');
    }
}

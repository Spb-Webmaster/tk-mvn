<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\Pages\ConstantsPage;
use App\MoonShine\Pages\Pages\ContactPage;
use App\MoonShine\Pages\Pages\HomePage;
use App\MoonShine\Pages\Pages\MasterPage;
use App\MoonShine\Pages\Pages\NewsPage;
use App\MoonShine\Pages\Pages\PhotoPage;
use App\MoonShine\Pages\Pages\ResponsePage;
use App\MoonShine\Pages\Pages\SchedulePage;
use App\MoonShine\Pages\Pages\TrainerPage;
use App\MoonShine\Pages\Pages\TrainingPage;
use App\MoonShine\Pages\Pages\VideoPage;
use App\MoonShine\Resources\AdminVideo\AdminVideoResource;
use App\MoonShine\Resources\MailLog\MailLogResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\News\NewsResource;
use App\MoonShine\Resources\Photo\PhotoResource;
use App\MoonShine\Resources\PhotoCategory\PhotoCategoryResource;
use App\MoonShine\Resources\Response\ResponseResource;
use App\MoonShine\Resources\ResponseCategory\ResponseCategoryResource;
use App\MoonShine\Resources\Training\TrainingResource;
use App\MoonShine\Resources\TrainingCategory\TrainingCategoryResource;
use App\MoonShine\Resources\TrainingLevel\TrainingLevelResource;
use App\MoonShine\Resources\Video\VideoResource;
use MoonShine\AssetManager\Js;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use MoonShine\MenuManager\MenuDivider;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use YuriZoom\MoonShineMediaManager\Pages\MediaManagerPage;


final class AxeldLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
            new Js('/js/admin/tab-persist.js'),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Пользователи', [
                MenuItem::make(MoonShineUserResource::class, 'Админ', 'user'),
                MenuDivider::make(),
            ])->icon('users'),

            MenuGroup::make(static fn() => __('Категории'), [
                MenuItem::make(HomePage::class, 'Главная', 'home'),
                MenuItem::make(ContactPage::class, 'Контакты', 'phone'),
                MenuItem::make(TrainerPage::class, 'О тренере', 'user'),
                MenuGroup::make('О нас', [
                    MenuItem::make(NewsPage::class, 'Новости', 'newspaper'),
                    MenuItem::make(PhotoPage::class, 'Фотогалерея', 'photo'),
                    MenuItem::make(VideoPage::class, 'Видеообзоры', 'film'),
                    MenuItem::make(ResponsePage::class, 'Отзывы', 'chat-bubble-left-right'),
                ])->icon('user-group'),
                MenuDivider::make(),
                MenuItem::make(SchedulePage::class, 'Расписание', 'book-open'),
                MenuItem::make(TrainingPage::class, 'Обучение', 'academic-cap'),

            ])->icon('rectangle-stack'),

            MenuGroup::make(static fn() => __('Страницы'), [

           MenuGroup::make(static fn() => __('Обучение'), [
               MenuItem::make(TrainingCategoryResource::class, 'Категории', 'tag'),
               MenuItem::make(TrainingLevelResource::class, 'Уровни', 'bars-3'),
               MenuItem::make(TrainingResource::class, 'Страницы', 'document-text'),
               MenuItem::make(MasterPage::class, 'Программа подготовки переговорщиков для бизнеса', 'star'),
           ])->icon('academic-cap'),

               MenuItem::make(NewsResource::class, 'Новости', 'document-text'),

           MenuGroup::make(static fn() => __('Фотографии'), [
               MenuItem::make(PhotoCategoryResource::class, 'Категории', 'tag'),
               MenuItem::make(PhotoResource::class, 'Фотографии', 'photo'),
           ])->icon('photo'),

               MenuItem::make(VideoResource::class, 'Видео', 'film'),

           MenuGroup::make(static fn() => __('Отзывы'), [
               MenuItem::make(ResponseCategoryResource::class, 'Категории', 'tag'),
               MenuItem::make(ResponseResource::class, 'Отзывы', 'chat-bubble-left-right'),
           ])->icon('chat-bubble-left-right'),

            ])->icon('folder-plus'),

            MenuGroup::make(static fn() => __('Настройки'), [
                MenuItem::make(ConstantsPage::class, 'Константы', 'adjustments-vertical'),
                MenuItem::make(AdminVideoResource::class, 'Закрытый канал', 'video-camera'),
                MenuItem::make(MailLogResource::class, 'Исходящие письма', 'envelope'),
                MenuItem::make(MediaManagerPage::class, 'Media', 'film'),
           ])->icon('cog-6-tooth'),

        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    protected function getFooterCopyright(): string
    {
        return \sprintf(
            <<<'HTML'
                &copy; %d Портал
                <a href="/"
                    class="font-semibold text-primary"
                    target="_blank"
                >
                    Мастерская Василия Никольского
                </a>
                HTML,
            now()->year,
        );
    }

    protected function getFooterMenu(): array
    {
        return [
            config('app.url') => 'WebSite',
        ];
    }
}

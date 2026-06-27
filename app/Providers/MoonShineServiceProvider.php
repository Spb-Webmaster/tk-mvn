<?php

declare(strict_types=1);

namespace App\Providers;


use App\MoonShine\Pages\Pages\ConstantsPage;
use App\MoonShine\Pages\Pages\MasterPage;
use App\MoonShine\Pages\Pages\ContactPage;
use App\MoonShine\Pages\Pages\HomePage;
use App\MoonShine\Pages\Pages\NewsPage;
use App\MoonShine\Pages\Pages\PhotoPage;
use App\MoonShine\Pages\Pages\ResponsePage;
use App\MoonShine\Pages\Pages\SchedulePage;
use App\MoonShine\Pages\Pages\TrainerPage;
use App\MoonShine\Pages\Pages\TrainingPage;
use App\MoonShine\Pages\Pages\VideoPage;
use App\MoonShine\Resources\MailLog\MailLogResource;
use App\MoonShine\Resources\Photo\PhotoResource;
use App\MoonShine\Resources\PhotoCategory\PhotoCategoryResource;
use App\MoonShine\Resources\Response\ResponseResource;
use App\MoonShine\Resources\ResponseCategory\ResponseCategoryResource;
use App\MoonShine\Resources\AdminVideo\AdminVideoResource;
use App\MoonShine\Resources\Video\VideoResource;
use Illuminate\Support\ServiceProvider;
use App\MoonShine\Resources\News\NewsResource;
use App\MoonShine\Resources\Training\TrainingResource;
use App\MoonShine\Resources\TrainingCategory\TrainingCategoryResource;
use App\MoonShine\Resources\TrainingLevel\TrainingLevelResource;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                NewsResource::class,
                TrainingResource::class,
                TrainingCategoryResource::class,
                TrainingLevelResource::class,
                PhotoResource::class,
                PhotoCategoryResource::class,
                VideoResource::class,
                AdminVideoResource::class,
                ResponseResource::class,
                ResponseCategoryResource::class,
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                MailLogResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
                HomePage::class,
                ContactPage::class,
                TrainerPage::class,
                NewsPage::class,
                TrainingPage::class,
                PhotoPage::class,
                VideoPage::class,
                ResponsePage::class,
                ConstantsPage::class,
                MasterPage::class,
                SchedulePage::class,
            ])
        ;
    }
}

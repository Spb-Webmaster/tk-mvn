<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Photo;
use App\Models\Response;
use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\Video;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function sitemap(): void
    {
        $sitemap = Sitemap::create();
        $base    = rtrim(config('app.url'), '/');

        // Статические страницы
        $static = [
            ['/', 1.0, 'daily'],
            ['/vasilij-nikolskij', 0.8, 'monthly'],
            ['/contacts', 0.7, 'monthly'],
            ['/schedule', 0.7, 'weekly'],
            ['/trainings', 0.9, 'weekly'],
            ['/last-actions', 0.7, 'weekly'],
            ['/photos', 0.6, 'weekly'],
            ['/video', 0.6, 'weekly'],
            ['/response', 0.6, 'monthly'],
        ];

        foreach ($static as [$path, $priority, $freq]) {
            $sitemap->add(
                Url::create($base . $path)
                    ->setPriority($priority)
                    ->setChangeFrequency($freq)
            );
        }

        // Категории тренингов: /trainings/{slug}
        TrainingCategory::orderBy('sorting')->each(function (TrainingCategory $category) use ($sitemap, $base) {
            $sitemap->add(
                Url::create("{$base}/trainings/{$category->slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            );
        });

        // Тренинги: /trainings/{categorySlug}/{slug}
        Training::where('published', 1)
            ->with('categories')
            ->orderBy('sorting')
            ->each(function (Training $training) use ($sitemap, $base) {
                $category = $training->categories->first();
                if (!$category) {
                    return;
                }
                $sitemap->add(
                    Url::create("{$base}/trainings/{$category->slug}/{$training->slug}")
                        ->setLastModificationDate($training->updated_at)
                        ->setPriority(0.9)
                        ->setChangeFrequency('weekly')
                );
            });

        // Новости: /last-actions/{slug}
        News::where('published', 1)
            ->orderByDesc('created_at')
            ->each(function (News $news) use ($sitemap, $base) {
                $sitemap->add(
                    Url::create("{$base}/last-actions/{$news->slug}")
                        ->setLastModificationDate($news->updated_at)
                        ->setPriority(0.6)
                        ->setChangeFrequency('monthly')
                );
            });

        // Фотографии: /photos/{slug}
        Photo::where('published', 1)
            ->orderBy('sorting')
            ->each(function (Photo $photo) use ($sitemap, $base) {
                $sitemap->add(
                    Url::create("{$base}/photos/{$photo->slug}")
                        ->setLastModificationDate($photo->updated_at)
                        ->setPriority(0.5)
                        ->setChangeFrequency('monthly')
                );
            });

        // Видео: /video/{slug}
        Video::where('published', 1)
            ->orderBy('sorting')
            ->each(function (Video $video) use ($sitemap, $base) {
                $sitemap->add(
                    Url::create("{$base}/video/{$video->slug}")
                        ->setLastModificationDate($video->updated_at)
                        ->setPriority(0.5)
                        ->setChangeFrequency('monthly')
                );
            });

        // Отзывы: /response/{slug}
        Response::where('published', 1)
            ->orderBy('sorting')
            ->each(function (Response $response) use ($sitemap, $base) {
                $sitemap->add(
                    Url::create("{$base}/response/{$response->slug}")
                        ->setLastModificationDate($response->updated_at)
                        ->setPriority(0.5)
                        ->setChangeFrequency('monthly')
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}

<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});

Breadcrumbs::for('contact', function ($trail) {
    $trail->parent('home');
    $trail->push('Контакты', route('contact'));
});

Breadcrumbs::for('schedule', function ($trail) {
    $trail->parent('home');
    $trail->push('Расписание', route('schedule'));
});

Breadcrumbs::for('training', function ($trail) {
    $trail->parent('home');
    $trail->push('Программы', route('training'));
});

Breadcrumbs::for('last-actions', function ($trail) {
    $trail->parent('home');
    $trail->push('Мероприятия', route('last-actions'));
});

Breadcrumbs::for('last-actions.show', function ($trail, $item) {
    $trail->parent('last-actions');
    $trail->push($item->title, route('last-actions.show', $item->slug));
});

Breadcrumbs::for('training.category.show', function ($trail, $category = null) {
    $trail->parent('training');
    if (!$category) {
        $category = \App\Models\TrainingCategory::where('slug', request()->route('slug'))->firstOrFail();
    }
    $trail->push($category->title, route('training.category.show', $category->slug));
});

Breadcrumbs::for('training.show', function ($trail, $item) {
    $category = $item->categories->first();
    $trail->parent('training.category.show', $category);
    $trail->push($item->title, route('training.show', [$category?->slug, $item->slug]));
});

Breadcrumbs::for('trainer', function ($trail) {
    $trail->parent('home');
    $trail->push('О тренере', route('trainer'));
});

Breadcrumbs::for('photo', function ($trail) {
    $trail->parent('home');
    $trail->push('Фотогалерея', route('photo'));
});

Breadcrumbs::for('photo.show', function ($trail, $item) {
    $trail->parent('photo');
    $trail->push($item->title, route('photo.show', $item->slug));
});

Breadcrumbs::for('video', function ($trail) {
    $trail->parent('home');
    $trail->push('Видеообзоры', route('video'));
});

Breadcrumbs::for('video.show', function ($trail, $item) {
    $trail->parent('video');
    $trail->push($item->title, route('video.show', $item->slug));
});

Breadcrumbs::for('admin-video', function ($trail) {
    $trail->parent('home');
    $trail->push('Видео для администратора', route('admin-video'));
});

Breadcrumbs::for('admin-video.show', function ($trail, $item) {
    $trail->parent('admin-video');
    $trail->push($item->title, route('admin-video.show', $item->slug));
});

Breadcrumbs::for('response', function ($trail) {
    $trail->parent('home');
    $trail->push('Отзывы', route('response'));
});

Breadcrumbs::for('response.show', function ($trail, $item) {
    $trail->parent('response');
    $trail->push($item->title, route('response.show', $item->slug));
});

Breadcrumbs::for('privacy', function ($trail) {
    $trail->parent('home');
    $trail->push('Политика обработки персональных данных', route('privacy'));
});

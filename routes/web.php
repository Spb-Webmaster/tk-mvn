<?php

use App\Http\Controllers\Axios\AxiosController;
use App\Http\Controllers\Schedule\ScheduleController;
use App\Models\Photo;
use App\Models\Response;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Dev\NewsImportController;
use App\Http\Controllers\Dev\PhotoImportController;
use App\Http\Controllers\Dev\ResponseImportController;
use App\Http\Controllers\Dev\VideoImportController;
use App\Http\Controllers\FancyBox\FancyBoxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminVideo\AdminVideoController;
use App\Http\Controllers\New\NewController;
use App\Http\Controllers\Pages\ContactController;
use App\Http\Controllers\Pages\ContactFormController;
use App\Http\Controllers\Pages\TrainerController;
use App\Http\Controllers\Pages\TrainingController;
use App\Http\Controllers\Photo\PhotoController;
use App\Http\Controllers\Response\ResponseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\InlineEditController;
use App\Http\Controllers\Training\TrainingRegistrationController;
use App\Http\Controllers\Video\VideoController;
use Illuminate\Support\Facades\Route;

/** Главная **/
Route::get('/', [HomeController::class, 'index'])->name('home');
/** ///Главная **/

/** О тренере **/
Route::get('/vasilij-nikolskij', [TrainerController::class, 'index'])->name('trainer');
/** ///О тренере **/

/** Контакты **/
Route::get('/contacts', [ContactController::class, 'index'])->name('contact');
/** ///Контакты **/

/** Изображения **/
Route::controller(PhotoController::class)->group(function () {
Route::get('/photos', 'list')->name('photo');
Route::get('/photos/{slug}', 'show')->name('photo.show');
});
/** ///Изображения **/

/** Расписание **/
Route::controller(ScheduleController::class)->group(function () {
    Route::get('/schedule', 'index')->name('schedule');
});
/** ///Расписание **/

/** Видео **/
Route::controller(VideoController::class)->group(function () {
    Route::get('/video', 'list')->name('video');
    Route::get('/video/{slug}', 'show')->name('video.show');
});
/** ///Видео **/

/** Отзывы **/
Route::controller(ResponseController::class)->group(function () {
    Route::get('/response', 'list')->name('response');
    Route::get('/response/{slug}', 'show')->name('response.show');
});
/** ///Отзывы **/


/** Регистрация на тренинг **/
Route::post('/trainings/register', [TrainingRegistrationController::class, 'store'])->middleware('throttle:10,10')->name('training.register');
/** ///Регистрация на тренинг **/

/** Форма обратной связи (контакты) **/
Route::post('/zapros', [ContactFormController::class, 'store'])->middleware('throttle:10,10')->name('form.zapros');
/** ///Форма обратной связи **/

/** Обучение **/
Route::controller(TrainingController::class)->group(function () {
    Route::get('/trainings', 'index')->name('training');
    Route::get('/trainings/{slug}', 'indexCategoryShow')->name('training.category.show');
    Route::get('/trainings/{categorySlug}/{slug}', 'show')->name('training.show');
});
/** ///Обучение **/

/** Видео для администратора **/
Route::controller(AdminVideoController::class)->group(function () {
    Route::get('/youtube', 'list')->name('admin-video');
    Route::get('/youtube/{slug}', 'show')->name('admin-video.show');
});
/** ///Видео для администратора **/

/** Новости **/
Route::controller(NewController::class)->group(function () {
    Route::get('/last-actions', 'list')->name('last-actions');
    Route::get('/last-actions/{slug}', 'show')->name('last-actions.show');
});
/** ///Новости **/

/** TestController **/
Route::get('/test', [TestController::class, 'index'])->name('test');
/** ///TestController **/

/** DEV **/
Route::prefix('dev/news')->controller(NewsImportController::class)->group(function () {
/*    Route::get('/preview', 'preview');
    Route::get('/import', 'import');*/
});
Route::prefix('dev/photo')->controller(PhotoImportController::class)->group(function () {
/*    Route::get('/preview', 'preview');
    Route::get('/import', 'import');*/
});
Route::prefix('dev/video')->controller(VideoImportController::class)->group(function () {
/*    Route::get('/preview', 'preview');
    Route::get('/import', 'import');*/
});
Route::prefix('dev/response')->controller(ResponseImportController::class)->group(function () {
/*    Route::get('/preview', 'preview');
    Route::get('/import', 'import');*/
});
/** ///DEV **/

/** FancyBox AJAX **/
Route::controller(FancyBoxController::class)->group(function () {
    Route::post('/fancybox-ajax', 'fancybox');
});
/** ///FancyBox AJAX **/

/** Axios async forms **/
Route::controller(AxiosController::class)->group(function () {
    Route::post('/upload-form-async', 'async');
    Route::post('/call-me-blue', 'callMeBlue');
    Route::post('/send-request', 'sendRequest');
});
/** ///Axios async forms **/

/** Admin AJAX **/
Route::post('/admin-ajax/response/{response}/categories', function (Request $request, Response $response) {
    $response->categories()->sync($request->input('categories', []));
    return response()->json(['ok' => true]);
})->name('response.categories.update');

Route::post('/admin-ajax/photo/{photo}/categories', function (Request $request, Photo $photo) {
    $photo->categories()->sync($request->input('categories', []));
    return response()->json(['ok' => true]);
})->name('photo.categories.update');

Route::post('/admin-ajax/training/{training}/categories', function (Request $request, Training $training) {
    $training->categories()->sync($request->input('categories', []));
    return response()->json(['ok' => true]);
})->name('training.categories.update');
/** ///Admin AJAX **/

/** Inline Edit (фронтенд-редактирование для MoonShine-администраторов) **/
Route::post('/admin-inline-edit', [InlineEditController::class, 'update'])->name('admin.inline-edit');
/** ///Inline Edit **/

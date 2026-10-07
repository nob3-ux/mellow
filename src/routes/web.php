<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// ページ（config/pages.php）
foreach (config('pages.pages') as $name => $page) {
    Route::get($page['path'], [PageController::class, 'show'])->name($name);
}

// 旧サイト（/bb/ 以下）のURLからの転送（検索エンジンの評価を引き継ぐため301）
foreach (config('pages.redirects') as $old => $name) {
    Route::get(trim(config('pages.redirect_prefix').'/'.$old, '/'), fn () => redirect()->route($name, status: 301));
}

Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PageController::class, 'robots']);

Route::get('/api/schedule', ScheduleController::class)->name('schedule.events');

Route::post('/api/forms/{form}', [FormController::class, 'submit'])
    ->whereIn('form', ['reservation', 'inquiry'])
    ->middleware('throttle:10,1')
    ->name('forms.submit');

// 管理画面（空き状況の管理）
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'show'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:20,1');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.availabilities'));
        Route::get('availabilities', [AvailabilityController::class, 'index'])->name('availabilities');
        Route::get('api/availabilities', [AvailabilityController::class, 'month'])->name('availabilities.month');
        Route::put('api/availabilities/{date}', [AvailabilityController::class, 'update'])->name('availabilities.update');
        Route::post('api/availabilities/bulk', [AvailabilityController::class, 'bulk'])->name('availabilities.bulk');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

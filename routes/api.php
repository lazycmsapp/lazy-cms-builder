<?php

use Illuminate\Support\Facades\Route;
use Acme\CmsDashboard\Http\Controllers\Api\V1\CmsApiController;

Route::prefix('api/v1')->middleware(['api'])->group(function() {
    
    // Posts API
    Route::get('/posts', [CmsApiController::class, 'posts']);
    Route::get('/posts/{slug}', [CmsApiController::class, 'singlePost']);

    // Pages API
    Route::get('/pages', function() {
        return (new CmsApiController)->posts(request()->merge(['type' => 'page']));
    });

    // Settings API
    Route::get('/settings', [CmsApiController::class, 'settings']);

    // Menus API
    Route::get('/menus', [CmsApiController::class, 'menus']);
});

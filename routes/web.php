<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ArticleReviewWebController,
    RoleController,
    PermissionController,
    UserController
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::middleware(['auth'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        /*
        |----------------------------------------------------------------------
        | مراجعة المقالات (الحماية داخل ArticleReviewWebController->__construct)
        |----------------------------------------------------------------------
        */
        Route::get('/articles/pending', [ArticleReviewWebController::class, 'pending'])->name('articles.pending');
        Route::get('/articles',         [ArticleReviewWebController::class, 'index'])->name('articles.index'); // اختياري
        Route::post('/articles/{article}/publish', [ArticleReviewWebController::class, 'publish'])->name('articles.publish');
        Route::post('/articles/{article}/reject',  [ArticleReviewWebController::class, 'reject'])->name('articles.reject');

        /*
        |----------------------------------------------------------------------
        | Roles (CRUD بدون show) — الحماية داخل RoleController->__construct
        |----------------------------------------------------------------------
        */
        Route::get   ('/roles',              [RoleController::class, 'index'])->name('roles.index');
        Route::get   ('/roles/create',       [RoleController::class, 'create'])->name('roles.create');
        Route::post  ('/roles',              [RoleController::class, 'store'])->name('roles.store');
        Route::get   ('/roles/{role}/edit',  [RoleController::class, 'edit'])->name('roles.edit');
        Route::match (['put','patch'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}',       [RoleController::class, 'destroy'])->name('roles.destroy');

        /*
        |----------------------------------------------------------------------
        | Permissions (CRUD بدون show) — الحماية داخل PermissionController->__construct
        |----------------------------------------------------------------------
        */
        Route::get   ('/permissions',               [PermissionController::class, 'index'])->name('permissions.index');
        Route::get   ('/permissions/create',        [PermissionController::class, 'create'])->name('permissions.create');
        Route::post  ('/permissions',               [PermissionController::class, 'store'])->name('permissions.store');
        Route::get   ('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::match (['put','patch'], '/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}',  [PermissionController::class, 'destroy'])->name('permissions.destroy');

        /*
        |----------------------------------------------------------------------
        | Users (CRUD كامل) — الحماية داخل UserController->__construct
        |----------------------------------------------------------------------
        */
        Route::get   ('/users',             [UserController::class, 'index'])->name('users.index');
        Route::get   ('/users/create',      [UserController::class, 'create'])->name('users.create');
        Route::post  ('/users',             [UserController::class, 'store'])->name('users.store');
        Route::get   ('/users/{user}',      [UserController::class, 'show'])->name('users.show');
        Route::get   ('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::match (['put','patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    });


require __DIR__.'/auth.php';

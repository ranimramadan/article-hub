<?php
use App\Models\Article;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ArticleReviewWebController,
    RoleController,
    PermissionController,
    UserController
};
use App\Http\Controllers\Web\MyArticlesController;
use App\Http\Controllers\Web\PublicArticlesController;

Route::get('/', function () {
    return view('welcome');
});


// قائمة عامة للمقالات المنشورة مع بحث/فلترة اختيارية
Route::get('/articles', [PublicArticlesController::class, 'index'])->name('articles.index');

// عرض مقال منشور فقط
Route::get('/articles/{article}', [PublicArticlesController::class, 'show'])->name('articles.show');



Route::get('/', function () {
    $articles = \App\Models\Article::published()
        ->latest('published_at')
        ->take(6)
        ->get();

    return view('welcome', compact('articles'));
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



/*
|----------------------------------------------------------------------
| 
|----------------------------------------------------------------------
*/
Route::middleware(['auth','role_or_permission:admin|articles.review'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        // Pending list + (اختياري) جميع المقالات
        Route::get('/articles/pending', [ArticleReviewWebController::class, 'pending'])->name('articles.pending');
        Route::get('/articles',         [ArticleReviewWebController::class, 'index'])->name('articles.index'); // اختياري

        // Actions
        Route::post('/articles/{article}/publish', [ArticleReviewWebController::class, 'publish'])->name('articles.publish');
        Route::post('/articles/{article}/reject',  [ArticleReviewWebController::class, 'reject'])->name('articles.reject');
    });

/*
|----------------------------------------------------------------------
| Roles (Admin أو roles.manage) — CRUD كامل بدون show
|----------------------------------------------------------------------
*/
Route::middleware(['auth','role_or_permission:admin|roles.manage'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get   ('/roles',              [RoleController::class, 'index'])->name('roles.index');
        Route::get   ('/roles/create',       [RoleController::class, 'create'])->name('roles.create');
        Route::post  ('/roles',              [RoleController::class, 'store'])->name('roles.store');
        Route::get   ('/roles/{role}/edit',  [RoleController::class, 'edit'])->name('roles.edit');
        Route::match (['put','patch'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}',       [RoleController::class, 'destroy'])->name('roles.destroy');
    });

/*
|----------------------------------------------------------------------
| Permissions (Admin أو permissions.manage) — CRUD كامل بدون show
|----------------------------------------------------------------------
*/
Route::middleware(['auth','role_or_permission:admin|permissions.manage'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get   ('/permissions',               [PermissionController::class, 'index'])->name('permissions.index');
        Route::get   ('/permissions/create',        [PermissionController::class, 'create'])->name('permissions.create');
        Route::post  ('/permissions',               [PermissionController::class, 'store'])->name('permissions.store');
        Route::get   ('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::match (['put','patch'], '/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}',  [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

/*
|----------------------------------------------------------------------
| Users (Admin أو users.manage) — CRUD كامل (index/create/store/show/edit/update/destroy)
|----------------------------------------------------------------------
*/
Route::middleware(['auth','role_or_permission:admin|users.manage'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get   ('/users',             [UserController::class, 'index'])->name('users.index');
        Route::get   ('/users/create',      [UserController::class, 'create'])->name('users.create');
        Route::post  ('/users',             [UserController::class, 'store'])->name('users.store');
        Route::get   ('/users/{user}',      [UserController::class, 'show'])->name('users.show');
        Route::get   ('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::match (['put','patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    });



Route::middleware(['auth'])
    ->prefix('user')->name('user.')
    ->group(function () {
        Route::get   ('/articles',                 [MyArticlesController::class, 'index'])->name('articles.index');
        Route::get   ('/articles/create',          [MyArticlesController::class, 'create'])->name('articles.create');
        Route::post  ('/articles',                 [MyArticlesController::class, 'store'])->name('articles.store');
        Route::get   ('/articles/{article}/edit',  [MyArticlesController::class, 'edit'])->name('articles.edit');
        Route::match (['put','patch'], '/articles/{article}', [MyArticlesController::class, 'update'])->name('articles.update');
        Route::delete('/articles/{article}',       [MyArticlesController::class, 'destroy'])->name('articles.destroy');

        // طلب النشر + سجل التحوّلات
        Route::post  ('/articles/{article}/submit',      [MyArticlesController::class, 'submit'])->name('articles.submit');
        Route::get   ('/articles/{article}/transitions', [MyArticlesController::class, 'transitions'])->name('articles.transitions');
    });





require __DIR__.'/auth.php';

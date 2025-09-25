<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\ArticlesController;
use App\Http\Controllers\Api\ChatController;


use GuzzleHttp\Client;


Route::get('/ai/ping', fn () => response('ok', 200))
    ->withoutMiddleware(['auth', 'auth:sanctum']);


Route::post('/ai/chat', [ChatController::class, 'chat'])
    ->withoutMiddleware(['auth', 'auth:sanctum']);

Route::get('/ai/ping', fn() => response('ok', 200))->withoutMiddleware(['auth','auth:sanctum']);


Route::get('/ai/debug-openai', function () {
    try {
        $client = new Client(['base_uri' => 'https://api.openai.com/', 'timeout' => 15]);
        $res = $client->get('v1/models', [
            'headers' => [
                'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                'Accept'        => 'application/json',
            ],
        ]);
        return response($res->getBody()->getContents(), 200, ['Content-Type'=>'application/json']);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
})->withoutMiddleware(['auth','auth:sanctum']);


// إصدار التوكن (مقيّد بالمحاولات)
Route::post('auth/token', [AuthTokenController::class, 'store'])->middleware('throttle:auth');

// محمي بـ Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', fn(Request $r) => ['user' => $r->user()]);
    Route::delete('auth/token', [AuthTokenController::class, 'destroy']);
});

// عام: قائمة المنشور + عرض مقال منشور
Route::get('articles', [ArticlesController::class, 'index']);
Route::get('articles/{article}', [ArticlesController::class, 'show']);

// محمي: “مقالاتي” + CRUD + submit + transitions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('my/articles', [ArticlesController::class, 'mine']);
    Route::post('articles', [ArticlesController::class, 'store']);
    Route::put('articles/{article}', [ArticlesController::class, 'update']);
    Route::delete('articles/{article}', [ArticlesController::class, 'destroy']);
    Route::post('articles/{article}/submit', [ArticlesController::class, 'submit']);

    // سجل تغيّر الحالة للمقال
    Route::get('articles/{article}/transitions', [ArticlesController::class, 'transitions']);





   
});

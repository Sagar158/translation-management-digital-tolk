<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslationController;

Route::post('/login', [AuthController::class, 'login']);
Route::controller(TranslationController::class)->prefix('translations')->group(function(){

    Route::middleware('auth:api')->group(function () {
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::get('/', 'index'); // Show Data
        Route::get('/export', 'export'); // To Export Json Language Data
        Route::get('/{id}', 'show');
    });


});

?>

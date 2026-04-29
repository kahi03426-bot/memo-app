<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemoController; // Controllerを読み込む

Route::post('/memos', [MemoController::class, 'store']);
Route::get('/memos', [MemoController::class, 'index']);
Route::delete('/memos/{memo}', [MemoController::class, 'destroy']);
Route::put('/memos/{memo}', [MemoController::class, 'update']);

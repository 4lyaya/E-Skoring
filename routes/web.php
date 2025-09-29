<?php

use App\Http\Controllers\ArcheryScoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArcheryScoreController::class, 'index'])->name('archery.index');
Route::post('/setup', [ArcheryScoreController::class, 'setup'])->name('archery.setup');
Route::post('/calculate', [ArcheryScoreController::class, 'calculate'])->name('archery.calculate');
Route::get('/download/{filename}', [ArcheryScoreController::class, 'download'])->name('archery.download');
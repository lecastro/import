<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/import/center/b2b', function (): string
    {
        return "Seja bem-vindo(a) ao Central Importaçao B2B!";
    }
);

Route::post('/upload/teams', [UploadController::class, 'uploadTeams']);

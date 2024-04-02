<?php

use Illuminate\Support\Facades\Route;
use Bythepixel\NovaTinymceField\Controllers\ImageController;

Route::post('/upload-image/{resource}/{field}', [ImageController::class, 'upload']);

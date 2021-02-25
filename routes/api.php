<?php

use Illuminate\Support\Facades\Route;

Route::post('/upload-image/{resource}/{field}', 'Bythepixel\NovaTinymceField\Controllers\ImageController@upload');

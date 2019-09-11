<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/template-content', 'Novius\NovaVisualComposer\Http\Controllers\TemplateController@templateCrud');
Route::get('/rows-summary', 'Novius\NovaVisualComposer\Http\Controllers\TemplateController@rowsSummary');

Route::post('/image-upload', 'Novius\NovaVisualComposer\Http\Controllers\UploadController@imageUpload');
Route::get('/image-upload', 'Novius\NovaVisualComposer\Http\Controllers\UploadController@imageShow');
Route::delete('/image-upload', 'Novius\NovaVisualComposer\Http\Controllers\UploadController@imageDelete');

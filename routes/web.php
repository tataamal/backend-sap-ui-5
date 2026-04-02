<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/doc');

Route::get('/doc', function () {
    return view('swagger');
});

Route::get('/openapi.json', function () {
    $openapi = json_decode(file_get_contents(resource_path('openapi.json')), true);
    $openapi['servers'][0]['url'] = rtrim(config('app.url'), '/');
    return response()->json($openapi);
});

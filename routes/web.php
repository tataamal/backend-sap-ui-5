<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('swagger');
});

Route::get('/doc', function () {
    return view('swagger');
});

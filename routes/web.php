<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/doc');

Route::get('/doc', function () {
    return view('swagger');
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $laravelVersion = app()->version();
    return response()->json(['laravelVersion' => $laravelVersion]);
});

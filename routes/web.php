<?php

use Illuminate\Support\Facades\Route;

// Load all API routes


Route::get('/', function () {
    return view('welcome');
});

<?php

use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;


Route::get('/{code}', UrlRedirectController::class)->where('code', '[A-Za-z0-9]+');

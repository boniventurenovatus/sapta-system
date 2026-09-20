<?php

use Illuminate\Support\Facades\Route;

Route::get('/language/switch', function() {
    $locale = request()->get('lang', 'en');
    if (in_array($locale, ['en', 'sw'])) {
        session(['locale' => $locale]);
    }
    return response()->json(['success' => true, 'locale' => $locale]);
})->name('language.switch');

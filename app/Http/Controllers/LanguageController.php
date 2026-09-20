<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        // Get the language from URL
        $locale = $request->get('lang', 'en');
        
        // Validate language
        if (!in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }
        
        // Save to session
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}

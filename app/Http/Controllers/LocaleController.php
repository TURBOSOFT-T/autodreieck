<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class LocaleController extends Controller
{
    public function change(Request $request)
    {

        
        $locale = $request->input('locale');

        if (in_array($locale, ['fr', 'en', 'ar','ru','de'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
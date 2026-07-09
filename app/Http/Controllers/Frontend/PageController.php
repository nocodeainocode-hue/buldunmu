<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;


class PageController extends Controller
{
    public function about()
    {
        $settings = SiteSetting::getSettings();
        return view('frontend.pages.about', compact('settings'));
    }
    public function contact()
    {
        $settings = SiteSetting::getSettings();
        return view('frontend.pages.contact', compact('settings'));
    }
    public function privacy()
    {
        $settings = SiteSetting::getSettings();
        return view('frontend.pages.privacy', compact('settings'));
    }
    public function terms()
    {
        $settings = SiteSetting::getSettings();
        return view('frontend.pages.terms', compact('settings'));
    }
}

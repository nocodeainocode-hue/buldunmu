<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;


class PageController extends Controller
{
    protected function getPageContent(string $key): ?string
    {
        $dir = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $pages = $dir->page_contents ?? [];
        return $pages[$key] ?? null;
    }

    public function about()
    {
        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('about');
        return view('frontend.pages.about', compact('settings', 'content'));
    }
    public function contact()
    {
        // Matematik CAPTCHA
        $a = rand(1, 10);
        $b = rand(1, 10);
        session(['captcha_result' => $a + $b]);

        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('contact');
        return view('frontend.pages.contact', compact('settings', 'content', 'a', 'b'));
    }
    public function privacy()
    {
        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('privacy');
        return view('frontend.pages.privacy', compact('settings', 'content'));
    }
    public function terms()
    {
        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('terms');
        return view('frontend.pages.terms', compact('settings', 'content'));
    }
}

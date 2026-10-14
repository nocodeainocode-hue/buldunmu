<?php

namespace App\Http\Controllers\Frontend;

use App\Support\FormGuard;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\PageContent;


class PageController extends Controller
{
    protected function getPageContent(string $key): ?string
    {
        $dir = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $pages = $dir->page_contents ?? [];
        $content = $pages[$key] ?? null;

        return PageContent::isMeaningful($content) ? $content : null;
    }

    public function about()
    {
        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('about');
        return view('frontend.pages.about', compact('settings', 'content'));
    }
    public function contact()
    {
        // İmzalı, tek kullanımlık işlem sorusu (oturuma bağlı değil)
        $challenge = FormGuard::challenge();
        $captchaLabel = $challenge['label'];
        $captchaToken = $challenge['token'];

        $settings = SiteSetting::getSettings();
        $content = $this->getPageContent('contact');
        return view('frontend.pages.contact', compact('settings', 'content', 'captchaLabel', 'captchaToken'));
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

    public function offline()
    {
        $settings = SiteSetting::getSettings();
        return view('frontend.pages.offline', ['settings' => $settings]);
    }
}

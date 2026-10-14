<?php

namespace App\Http\Controllers\Frontend;

use App\Rules\TurkishPhone;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Support\FormGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    private const SUCCESS = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapılacaktır.';

    public function store(Request $request)
    {
        abort_unless(app()->bound('currentDirectory'), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'string', 'max:30', new TurkishPhone],
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha' => 'required|integer',
            'captcha_token' => 'nullable|string',
        ]);

        $guard = FormGuard::verify($validated['captcha_token'] ?? null, $validated['captcha']);
        unset($validated['captcha'], $validated['captcha_token']);

        if ($guard === FormGuard::WRONG) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Güvenlik sorusu yanlış ya da süresi dolmuş. Lütfen sayfayı yenileyip tekrar deneyin.');
        }

        // Bot izlenimi veren gönderimler (çok hızlı, saatlik sınır, aynı metin) sessizce başarılı gösterilir.
        $ip = $request->ip();
        $duplicateKey = 'contact-dup:'.sha1($ip.'|'.mb_strtolower(trim($validated['message'])));

        if ($guard === FormGuard::TOO_FAST
            || RateLimiter::tooManyAttempts('contact-hourly:'.$ip, 10)
            || ! Cache::add($duplicateKey, 1, 3600)) {
            return redirect()->back()->with('success', self::SUCCESS);
        }

        RateLimiter::hit('contact-hourly:'.$ip, 3600);

        $validated['status'] = FormGuard::looksLikeSpam($validated['name'], $validated['message']) ? 'spam' : 'new';
        $validated['directory_id'] = app('currentDirectory')->id;

        ContactMessage::create($validated);

        return redirect()->back()->with('success', self::SUCCESS);
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha' => 'required|integer',
        ]);

        // Matematik CAPTCHA doğrulaması
        $captchaResult = session('captcha_result');
        if ((int) $validated['captcha'] !== $captchaResult) {
            session()->forget('captcha_result');
            return redirect()->back()
                ->withInput()
                ->with('error', 'Güvenlik sorusu yanlış. Lütfen tekrar deneyin.');
        }
        session()->forget('captcha_result');

        $validated['status'] = 'new';

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapılacaktır.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Görünmez "fax_no" alanını dolduran botlar sessizce geri çevrilir (hata mesajı verilmez).
 */
class Honeypot
{
    public const FIELD = 'fax_no';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST') && filled($request->input(self::FIELD))) {
            return $request->expectsJson()
                ? response()->json(['ok' => true])
                : redirect()->back()->with('success', 'Talebiniz alındı.');
        }

        return $next($request);
    }
}

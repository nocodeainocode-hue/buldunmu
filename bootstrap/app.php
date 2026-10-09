<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        // Rehber, rota modelleri (ör. {company:slug}) çözülmeden önce belirlenmeli; aksi halde aynı adresli
        // başka rehber kaydı bulunabilir.
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\SubstituteBindings::class,
            prepend: \App\Http\Middleware\SetCurrentDirectory::class,
        );
        $middleware->alias([
            'honeypot' => \App\Http\Middleware\Honeypot::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SetCurrentDirectory::class,
            \App\Http\Middleware\CaptureAttribution::class,
            \App\Http\Middleware\TrackPageView::class,
            \App\Http\Middleware\CacheHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

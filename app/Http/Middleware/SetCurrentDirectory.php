<?php

namespace App\Http\Middleware;

use App\Models\Directory;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentDirectory
{
    public function handle(Request $request, Closure $next): Response
    {
        $directory = null;

        // Admin panel: session-based tenant switcher
        if ($request->is('admin*') || $request->is('livewire*')) {
            if ($id = session('current_directory_id')) {
                $directory = Directory::find($id);
            }
        }
        // Frontend: domain-based resolution
        else {
            $host = $request->getHost();
            $directory = Directory::where('domain', $host)->first();
        }

        if ($directory) {
            app()->instance('currentDirectory', $directory);
        }

        return $next($request);
    }
}

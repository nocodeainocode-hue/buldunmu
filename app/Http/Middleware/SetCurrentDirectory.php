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
            $host = strtolower($request->getHost());
            $rootHost = str_starts_with($host, 'www.') ? substr($host, 4) : $host;

            $directory = Directory::whereIn('domain', [$host, $rootHost, 'www.'.$rootHost])
                ->where('status', 'active')
                ->where(fn ($query) => $query
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now()))
                ->first();

            if (! $directory && app()->environment('production')) {
                abort(404);
            }
        }

        if ($directory) {
            app()->instance('currentDirectory', $directory);
        }

        return $next($request);
    }
}

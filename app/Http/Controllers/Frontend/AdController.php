<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AdCampaign;
use App\Services\AdServer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    /** Görünür olduğunda tarayıcının çağırdığı 1x1 gösterim işareti. */
    public function impression(Request $request, AdCampaign $ad, AdServer $server)
    {
        if ($ad->isLive() && ! $server->isBot($request->userAgent())) {
            $server->recordImpression($ad, $this->directoryId());
        }

        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'), 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    /** Tıklamayı sayar ve reklamverenin adresine UTM parametreleriyle yönlendirir. */
    public function click(Request $request, AdCampaign $ad, AdServer $server)
    {
        $target = $ad->link_url;

        abort_unless(filter_var($target, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $target), 404);

        if (! $server->isBot($request->userAgent())) {
            $server->recordClick($ad, $this->directoryId());
        }

        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $utm = http_build_query([
            'utm_source' => $directory?->domain ?? 'rehber',
            'utm_medium' => 'banner',
            'utm_campaign' => Str::slug($ad->name) ?: 'reklam',
        ]);

        $target .= (str_contains($target, '?') ? '&' : '?').$utm;

        return redirect()->away($target)->withHeaders(['Cache-Control' => 'no-store, private']);
    }

    private function directoryId(): ?int
    {
        return app()->bound('currentDirectory') ? app('currentDirectory')->id : null;
    }
}

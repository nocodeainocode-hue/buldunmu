<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ClaimInvite;
use App\Support\BotDetector;
use Illuminate\Http\Request;

class ClaimInviteController extends Controller
{
    /** Kısa davet linki: tıklamayı kaydeder ve firmanın sahiplenme sayfasına yönlendirir. */
    public function open(Request $request, string $token)
    {
        abort_unless(app()->bound('currentDirectory') && preg_match('/^[A-Za-z0-9]{6,12}$/', $token), 404);

        $invite = ClaimInvite::with('company')->where('token', $token)->first();

        abort_unless(
            $invite && $invite->company && (int) $invite->directory_id === (int) app('currentDirectory')->id,
            404
        );

        // Bir daha yazılmasını istemeyen firma da bağlantıya erişebilir, yalnızca takip yapılmaz.
        if ($invite->status !== 'opted_out' && ! BotDetector::isBot($request->userAgent())) {
            $invite->forceFill([
                'clicks' => $invite->clicks + 1,
                'first_clicked_at' => $invite->first_clicked_at ?? now(),
                'last_clicked_at' => now(),
                'status' => in_array($invite->status, ['ready', 'sent'], true) ? 'clicked' : $invite->status,
            ])->save();
        }

        $request->session()->put('claim_invite', $invite->token);

        return redirect()->route('companies.claim', [
            'company' => $invite->company->slug,
            'utm_source' => 'davet',
            'utm_medium' => 'whatsapp',
            'utm_campaign' => 'sahiplen',
        ]);
    }

    /** Yönetici: daveti gönderildi işaretler ve hazır mesajla WhatsApp'a gönderir. */
    public function send(int $invite)
    {
        $invite = ClaimInvite::with('directory')->findOrFail($invite);
        $url = $invite->whatsappUrl();

        abort_unless($url, 404);

        if ($invite->status === 'ready') {
            $invite->forceFill(['status' => 'sent', 'sent_at' => now()])->save();
        }

        return redirect()->away($url);
    }
}

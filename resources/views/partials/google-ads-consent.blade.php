@if($adsTagEnabled ?? false)
    @php
        $registrationEvent = session('google_ads_registration');
        $registrationLabel = $adsTracking['registration_label'] ?? '';
        $registrationConversion = is_array($registrationEvent)
            && ($registrationEvent['directory_id'] ?? null) === $directory->id
            && request()->routeIs('owner.dashboard')
            && preg_match('/^[a-zA-Z0-9_-]+$/', $registrationLabel)
            ? ['send_to' => $adsTagId.'/'.$registrationLabel, 'transaction_id' => $registrationEvent['transaction_id']]
            : null;
    @endphp
    <aside id="ads-consent-banner" aria-label="Reklam ölçümü tercihi" hidden style="position:fixed;bottom:16px;left:16px;right:16px;z-index:10000;max-width:600px;padding:20px;background:#fff;color:#17202a;border:1px solid #cbd5e1;border-radius:12px;box-shadow:0 4px 24px #0002;font-size:14px;line-height:1.6;">
        <strong>Reklam ölçümü tercihiniz</strong>
        <p>Hangi reklamların firma kaydına katkı sağladığını ölçmek için Google reklam çerezlerine izin veriyor musunuz? Reddetseniz de ücretsiz kayıt olabilirsiniz. Kişiselleştirilmiş reklamlar kapalıdır. <a href="{{ route('pages.privacy') }}" style="text-decoration:underline;">Gizlilik politikası</a></p>
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
            <button type="button" data-ads-consent="granted" style="padding:8px 16px;border-radius:6px;background:#17202a;color:#fff;">İzin ver</button>
            <button type="button" data-ads-consent="denied" style="padding:8px 16px;border:1px solid #64748b;border-radius:6px;">Reddet</button>
        </div>
    </aside>
    <button type="button" id="ads-consent-settings" style="display:block;margin:12px auto;padding:6px 12px;font-size:12px;text-decoration:underline;">Reklam çerezi tercihleri</button>
    <script id="google-ads-measurement" defer src="{{ asset('js/google-ads-measurement.js') }}" data-registration="{{ json_encode($registrationConversion) }}"></script>
@endif

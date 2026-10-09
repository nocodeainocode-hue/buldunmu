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
    <aside id="ads-consent-banner" aria-label="Reklam ölçümü tercihi" hidden style="position:fixed;bottom:10px;left:10px;right:10px;z-index:10000;max-width:440px;display:flex;flex-wrap:wrap;align-items:center;gap:8px 12px;padding:10px 12px;background:#fff;color:#17202a;border:1px solid #cbd5e1;border-radius:10px;box-shadow:0 2px 14px #0002;font-size:12.5px;line-height:1.45;">
        <span style="flex:1 1 220px;">Reklam ölçümü için Google çerezlerine izin verir misiniz? Reddetseniz de kayıt olabilirsiniz. <a href="{{ route('pages.privacy') }}" style="text-decoration:underline;">Gizlilik</a></span>
        <span style="display:flex;gap:6px;">
            <button type="button" data-ads-consent="granted" style="padding:5px 12px;border-radius:6px;background:#17202a;color:#fff;font-size:12.5px;">İzin ver</button>
            <button type="button" data-ads-consent="denied" style="padding:5px 12px;border:1px solid #64748b;border-radius:6px;font-size:12.5px;">Reddet</button>
        </span>
    </aside>
    <button type="button" id="ads-consent-settings" hidden style="position:fixed;bottom:6px;left:6px;z-index:9999;padding:2px 8px;font-size:11px;color:#64748b;background:#ffffffcc;border-radius:6px;text-decoration:underline;">Çerez tercihleri</button>
    <script id="google-ads-measurement" defer src="{{ asset('js/google-ads-measurement.js') }}" data-registration="{{ json_encode($registrationConversion) }}"></script>
@endif

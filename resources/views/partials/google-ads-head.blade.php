@if($adsTagEnabled)
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        let adsMeasurementAllowed = false;
        try { adsMeasurementAllowed = localStorage.getItem('fr_ads_consent_v1') === 'granted'; } catch (_) {}
        gtag('consent', 'default', {
            ad_storage: adsMeasurementAllowed ? 'granted' : 'denied',
            ad_user_data: adsMeasurementAllowed ? 'granted' : 'denied',
            ad_personalization: 'denied',
            analytics_storage: 'denied'
        });
        gtag('js', new Date());
        gtag('config', {{ Illuminate\Support\Js::from($adsTagId) }}, {
            allow_ad_personalization_signals: false,
            allow_enhanced_conversions: false,
            page_location: {{ Illuminate\Support\Js::from($adsPageLocation) }}
        });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $adsTagId }}"></script>
@endif

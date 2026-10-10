@php
    $company = $owner->company;
    $user = $owner->user;
    $rows = [
        'Firma' => $company?->name,
        'Rehber' => $owner->directory?->name,
        'Kategori' => $company?->category?->name,
        'Şehir' => $company?->city?->name,
        'Adres' => $company?->address,
        'Telefon' => $company?->phone,
        'WhatsApp' => $company?->whatsapp,
        'Firma e-postası' => $company?->email,
        'Web sitesi' => $company?->website,
        'Yetkili' => $user?->name,
        'Hesap e-postası' => $user?->email,
        'Firma durumu' => $company?->status,
        'Kayıt tarihi' => $owner->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i'),
        'Kaynak' => $user?->utm_source ?: ($user?->referrer_host ?: 'Doğrudan'),
        'Kampanya' => $user?->utm_campaign,
        'Ortam' => $user?->utm_medium,
    ];
@endphp
<dl class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm sm:grid-cols-2">
    @foreach($rows as $label => $value)
        @if(filled($value))
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</dt>
                <dd class="mt-0.5 break-words">{{ $value }}</dd>
            </div>
        @endif
    @endforeach
</dl>

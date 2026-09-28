<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yeni Firma Başvurusu</title>
</head>
<body style="margin:0;padding:0;background:#eef1f4;font-family:-apple-system,'Segoe UI',Roboto,Arial,sans-serif;color:#22303a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f4;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border:1px solid #d3dae0;border-radius:6px;overflow:hidden;">

                    {{-- Başlık şeridi --}}
                    <tr>
                        <td style="background:#1c6096;color:#ffffff;padding:20px 26px;">
                            <p style="margin:0;font-size:12px;letter-spacing:.14em;text-transform:uppercase;opacity:.8;">{{ $directory?->name ?? config('app.name') }}</p>
                            <h1 style="margin:6px 0 0;font-size:20px;font-weight:700;">Yeni Firma Başvurusu</h1>
                        </td>
                    </tr>

                    {{-- Özet --}}
                    <tr>
                        <td style="padding:24px 26px 8px;">
                            <p style="margin:0 0 6px;font-size:15px;line-height:1.6;">
                                <strong style="color:#124566;">{{ $listing->company_name }}</strong>
                                {{ $listing->source === 'claim' ? 'sahiplenme' : ($listing->source === 'owner_registration' ? 'sahip kaydı' : 'firma ekleme') }}
                                talebinde bulundu.
                            </p>
                            <p style="margin:0;font-size:13px;color:#5c6b76;">
                                Başvuru tarihi: {{ $listing->created_at?->format('d.m.Y H:i') }}
                            </p>
                        </td>
                    </tr>

                    {{-- Detay tablosu --}}
                    <tr>
                        <td style="padding:8px 26px 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
                                @php
                                    $rows = [
                                        'Firma' => $listing->company_name,
                                        'Yetkili' => $listing->contact_name,
                                        'Telefon' => $listing->phone,
                                        'WhatsApp' => $listing->whatsapp,
                                        'E-posta' => $listing->email,
                                        'Web sitesi' => $listing->website,
                                        'Kategori' => $listing->category?->name ?? $listing->requested_category,
                                        'Şehir / İlçe' => trim(($listing->city?->name ?? '').($listing->district?->name ? ' / '.$listing->district->name : '')),
                                    ];
                                @endphp
                                @foreach($rows as $label => $value)
                                    @if(!empty($value))
                                        <tr>
                                            <td style="padding:9px 12px;border-bottom:1px solid #eef1f4;color:#5c6b76;width:34%;vertical-align:top;font-weight:700;">{{ $label }}</td>
                                            <td style="padding:9px 12px;border-bottom:1px solid #eef1f4;">
                                                @if($label === 'E-posta')
                                                    <a href="mailto:{{ $value }}" style="color:#1c6096;text-decoration:none;">{{ $value }}</a>
                                                @elseif($label === 'Web sitesi')
                                                    <a href="{{ $value }}" target="_blank" rel="noopener" style="color:#1c6096;text-decoration:none;">{{ $value }}</a>
                                                @elseif($label === 'Telefon' || $label === 'WhatsApp')
                                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', (string) $value) }}" style="color:#1c6096;text-decoration:none;">{{ $value }}</a>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>

                            @if(!empty($listing->message))
                                <div style="margin-top:18px;padding:14px 16px;background:#f7f9fb;border-left:4px solid #0b6b52;border-radius:4px;">
                                    <p style="margin:0 0 6px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#5c6b76;">Başvuru Mesajı</p>
                                    <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-line;">{{ $listing->message }}</p>
                                </div>
                            @endif
                        </td>
                    </tr>

                    {{-- Aksiyon --}}
                    <tr>
                        <td style="padding:0 26px 28px;">
                            @if($directory)
                                <a href="{{ rtrim('https://'.$directory->domain, '/').'/admin/listing-requests' }}"
                                   style="display:inline-block;background:#f0872a;color:#27180a;font-weight:700;font-size:14px;padding:12px 22px;border-radius:4px;text-decoration:none;">
                                    Başvuruları Yönet →
                                </a>
                            @endif
                        </td>
                    </tr>

                    {{-- Alt bilgi --}}
                    <tr>
                        <td style="background:#f7f9fb;padding:16px 26px;font-size:12px;color:#5c6b76;border-top:1px solid #eef1f4;">
                            Bu e-posta {{ $directory?->name ?? config('app.name') }} yönetim paneline gelen yeni bir firma başvurusu hakkında otomatik gönderilmiştir.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

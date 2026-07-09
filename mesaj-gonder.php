#!/usr/bin/php
<?php
/**
 * Toplu WhatsApp Mesaj Gönderici
 * Kullanım: php mesaj-gonder.php mesajlar.csv
 * 
 * CSV formatı: telefon,isim,mesaj,gorsel_url
 * gorsel_url boş bırakılabilir
 */

$apiKey = '3cbfbf4ac2e84591bfa8c4c0112443b9';
$wahaUrl = 'http://localhost:3000';
$session = 'session_01kx14x1xzb2krx5bhz30vyxqb';
$sleepMin = 30;
$sleepMax = 90;

$csvFile = $argv[1] ?? 'mesajlar.csv';

if (!file_exists($csvFile)) {
    echo "HATA: $csvFile bulunamadi!\n";
    echo "Format: telefon,isim,mesaj,gorsel_url\n";
    echo "Ornek CSV:\n";
    echo "905551112233,Ahmet,Merhaba Ahmet bey firmanizi ekleyelim mi?,\n";
    echo "905552223344,Mehmet,Sayin Mehmet bey rehberimize bekleriz,https://site.com/resim.jpg\n";
    exit(1);
}

$lines = file($csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$total = count($lines);
$success = 0;
$fail = 0;

echo "═══════════════════════════════════════\n";
echo "  Toplu WhatsApp - $total kisi\n";
echo "═══════════════════════════════════════\n\n";

foreach ($lines as $i => $line) {
    $data = str_getcsv($line);
    $phone = trim($data[0] ?? '');
    $name  = trim($data[1] ?? '');
    $msg   = trim($data[2] ?? '');
    $image = trim($data[3] ?? '');

    if (!$phone || !$msg) continue;

    // Telefon formatı: 9 ile başla, @c.us ekle
    if (!str_starts_with($phone, '9')) $phone = '9' . $phone;
    $chatId = $phone . '@c.us';

    $n = $i + 1;
    echo "[$n/$total] $name ($phone)\n";

    // Varsa önce görsel gönder
    if ($image) {
        $resp = sendRequest("$wahaUrl/api/sendImage", [
            'session' => $session,
            'chatId' => $chatId,
            'caption' => $msg,
            'file' => [
                'mimetype' => 'image/jpeg',
                'url' => $image,
                'filename' => 'resim.jpg'
            ]
        ]);
        $status = $resp['http_code'] ?? 0;
        echo $status >= 200 && $status < 300 ? "  ✅ Gorsel gonderildi\n" : "  ❌ Gorsel hatasi ($status)\n";
    } else {
        // Sadece metin
        $resp = sendRequest("$wahaUrl/api/sendText", [
            'session' => $session,
            'chatId' => $chatId,
            'text' => $msg
        ]);
        $status = $resp['http_code'] ?? 0;
        if ($status >= 200 && $status < 300) {
            echo "  ✅ Gonderildi\n";
            $success++;
        } else {
            echo "  ❌ Hata ($status): " . ($resp['body'] ?? '') . "\n";
            $fail++;
        }
    }

    // Son kişi değilse bekle
    if ($n < $total) {
        $sleep = $sleepMin + rand(0, $sleepMax - $sleepMin);
        echo "  ⏳ {$sleep}s\n";
        sleep($sleep);
    }
}

echo "\n═══════════════════════════════════════\n";
echo "  TAMAMLANDI - Basarili: $success, Basarisiz: $fail\n";
echo "═══════════════════════════════════════\n";

function sendRequest($url, $data) {
    global $apiKey;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-Api-Key: ' . $apiKey
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 30,
    ]);
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['http_code' => $httpCode, 'body' => $body];
}

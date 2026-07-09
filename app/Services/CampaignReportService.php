<?php

namespace App\Services;

use App\Models\Campaign;
use Illuminate\Http\Response;

class CampaignReportService
{
    public static function exportCsv(Campaign $campaign): Response
    {
        $items = $campaign->items()->with(['directory'])->orderBy('scheduled_for')->get();

        $csv = "Sira,Rehber,Domain,Firma Slug,Yayin Tarihi,Anchor Text,Link Tipi,Durum\n";

        foreach ($items as $i => $item) {
            $csv .= implode(',', [
                $i + 1,
                '"' . ($item->directory->name ?? '') . '"',
                '"' . ($item->directory->domain ?? '') . '"',
                '"' . $item->slug . '"',
                $item->published_at?->format('d.m.Y') ?? $item->scheduled_for?->format('d.m.Y') ?? '-',
                '"' . ($item->anchor_text ?? '-') . '"',
                $item->link_type ?? '-',
                $item->status,
            ]) . "\n";
        }

        // Summary
        $total = $items->count();
        $published = $items->where('status', 'published')->count();
        $failed = $items->where('status', 'failed')->count();

        $csv .= "\n";
        $csv .= "Toplam,{$total}\n";
        $csv .= "Yayinlanan,{$published}\n";
        $csv .= "Basarisiz,{$failed}\n";
        $csv .= "Tamamlanma,%," . ($total > 0 ? round($published / $total * 100) : 0) . "\n";

        // UTF-8 BOM for Turkish characters
        $bom = "\xEF\xBB\xBF";

        return response($bom . $csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="kampanya-raporu-' . $campaign->id . '.csv"');
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\Directory;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class AdAttributionReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Reklam Kaynakları';

    protected static ?string $title = 'Reklam Kaynakları ve Kampanya Hunisi';

    protected static string|\UnitEnum|null $navigationGroup = 'Gelir';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.ad-attribution-report';

    public int $days = 30;

    public int|string|null $directoryId = null;

    /** @return array<int, array<string, mixed>> */
    public function getRows(): array
    {
        $source = "COALESCE(NULLIF(users.utm_source, ''), NULLIF(users.referrer_host, ''), 'Doğrudan')";

        $query = DB::table('users')
            ->leftJoin('owner_campaign_events as e', 'e.user_id', '=', 'users.id')
            ->whereNotNull('users.signup_directory_id')
            ->when($this->days > 0, fn ($q) => $q->where('users.created_at', '>=', now()->subDays($this->days)))
            ->when($this->directoryId, fn ($q) => $q->where('users.signup_directory_id', $this->directoryId))
            ->selectRaw("{$source} as source")
            ->selectRaw("COALESCE(NULLIF(users.utm_medium, ''), '-') as medium")
            ->selectRaw("COALESCE(NULLIF(users.utm_campaign, ''), '-') as campaign")
            ->selectRaw('COUNT(DISTINCT users.id) as signups')
            ->selectRaw("COUNT(DISTINCT CASE WHEN e.event = 'popup_shown' THEN users.id END) as popup")
            ->selectRaw("COUNT(DISTINCT CASE WHEN e.event = 'page_view' THEN users.id END) as page_view")
            ->selectRaw("COUNT(DISTINCT CASE WHEN e.event = 'whatsapp_click' THEN users.id END) as whatsapp")
            ->groupByRaw("{$source}, COALESCE(NULLIF(users.utm_medium, ''), '-'), COALESCE(NULLIF(users.utm_campaign, ''), '-')")
            ->orderByDesc('signups');

        return $query->get()->map(fn ($row) => [
            'source' => $row->source,
            'medium' => $row->medium,
            'campaign' => $row->campaign,
            'signups' => (int) $row->signups,
            'popup' => (int) $row->popup,
            'page_view' => (int) $row->page_view,
            'whatsapp' => (int) $row->whatsapp,
            'page_rate' => $row->signups > 0 ? (int) round($row->page_view / $row->signups * 100) : 0,
            'whatsapp_rate' => $row->signups > 0 ? (int) round($row->whatsapp / $row->signups * 100) : 0,
        ])->all();
    }

    /** @return array<int, string> */
    public function getDirectoryOptions(): array
    {
        return Directory::orderBy('name')->pluck('name', 'id')->all();
    }
}

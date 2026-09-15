<x-filament-widgets::widget>
    <section class="admin-command-center">
        <div class="admin-command-center__intro">
            <p class="admin-command-center__eyebrow">Çalışma alanı</p>
            <div class="admin-command-center__heading-row">
                <div>
                    <h2>{{ $directory?->name ?? 'Tüm rehberler' }}</h2>
                    <p>{{ $directory?->domain ?? 'Genel ağ görünümü ve ortak içerik yönetimi' }}</p>
                </div>
                <a class="admin-command-center__switch" href="{{ $directoryUrl }}">
                    Rehber değiştir
                    <x-filament::icon icon="heroicon-m-arrows-right-left" class="h-4 w-4" />
                </a>
            </div>
            <div class="admin-command-center__metrics">
                <div>
                    <strong>{{ $companyCount }}</strong>
                    <span>firma</span>
                </div>
                <div>
                    <strong>{{ $pendingRequests }}</strong>
                    <span>bekleyen talep</span>
                </div>
                <div>
                    <strong>{{ $directory ? 'Seçili' : 'Merkez' }}</strong>
                    <span>çalışma modu</span>
                </div>
            </div>
        </div>

        <div class="admin-command-center__actions">
            @foreach ($actions as $action)
                <a href="{{ $action['url'] }}" class="admin-command-card {{ $loop->first ? 'admin-command-card--primary' : '' }}">
                    <span class="admin-command-card__icon">
                        <x-filament::icon :icon="$action['icon']" class="h-5 w-5" />
                    </span>
                    <span>
                        <strong>{{ $action['label'] }}</strong>
                        <small>{{ $action['description'] }}</small>
                    </span>
                    <x-filament::icon icon="heroicon-m-arrow-up-right" class="admin-command-card__arrow h-4 w-4" />
                </a>
            @endforeach
            @if ($directory?->domain)
                <a href="{{ 'https://' . $directory->domain }}" class="admin-command-card" target="_blank" rel="noopener noreferrer">
                    <span class="admin-command-card__icon">
                        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-5 w-5" />
                    </span>
                    <span>
                        <strong>Siteyi Aç</strong>
                        <small>{{ $directory->domain }}</small>
                    </span>
                    <x-filament::icon icon="heroicon-m-arrow-up-right" class="admin-command-card__arrow h-4 w-4" />
                </a>
            @endif
        </div>
    </section>
</x-filament-widgets::widget>

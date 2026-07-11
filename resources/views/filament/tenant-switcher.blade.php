<div class="flex items-center gap-2 px-2">
    <span class="hidden text-xs font-medium text-gray-500 xl:inline">Çalışılan rehber</span>
    <form method="POST" action="{{ route('filament.admin.tenant.switch') }}">
        @csrf
        <select name="directory_id" onchange="this.form.submit()"
            aria-label="Çalışılan rehberi seç"
            class="max-w-56 cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
            <option value="">Tüm rehberler</option>
            @foreach($directories as $dir)
                <option value="{{ $dir->id }}" {{ ($current && $current->id === $dir->id) ? 'selected' : '' }}>
                    {{ $dir->name }} · {{ $dir->domain }}
                </option>
            @endforeach
        </select>
    </form>
</div>

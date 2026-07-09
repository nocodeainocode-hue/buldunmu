<div class="flex items-center gap-2 px-2">
    <form method="POST" action="{{ route('filament.admin.tenant.switch') }}" class="flex items-center gap-2">
        @csrf
        <select name="directory_id" onchange="this.form.submit()"
            class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-pointer">
            <option value="">-- Tüm Rehberler --</option>
            @foreach($directories as $dir)
                <option value="{{ $dir->id }}" {{ ($current && $current->id === $dir->id) ? 'selected' : '' }}>
                    {{ $dir->name }}
                </option>
            @endforeach
        </select>
    </form>
    @if($current)
        <span class="text-xs bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 px-2 py-0.5 rounded-full">
            {{ $current->name }}
        </span>
    @endif
</div>

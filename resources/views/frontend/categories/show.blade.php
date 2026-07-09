@extends('layouts.app')

@section('title', $category->meta_title ?: $category->name . ' Firmaları')
@section('meta_description', $category->meta_description ?: $category->name . ' kategorisindeki firmaları listeleyin.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ $category->name }}</span>
    </nav>

    <div class="rounded-2xl p-8 mb-8 text-white" style="background: linear-gradient(135deg, var(--hero-gradient-from), var(--hero-gradient-to))">
        <div class="flex items-center gap-4">
            <div class="text-5xl">{{ $category->icon ?? '📁' }}</div>
            <div>
                <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-2 opacity-80">{{ $category->description }}</p>
                @endif
                <p class="mt-2 text-sm opacity-60">{{ $companies->total() }} firma bulundu</p>
            </div>
        </div>
    </div>

    @if($companies->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($companies as $company)
                @include('partials.cards.' . \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null), ['company' => $company, 'premium' => $company->is_premium])
            @endforeach
        </div>
        <div class="mt-8">
            {{ $companies->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="text-5xl mb-4">📭</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bu kategoride henüz firma yok</h3>
            <p class="text-gray-500">İlk firmayı eklemek için <a href="{{ route('listing.create') }}" class="text-indigo-600 hover:underline">tıklayın</a>.</p>
        </div>
    @endif
</div>
@endsection
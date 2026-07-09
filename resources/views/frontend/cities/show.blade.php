@extends('layouts.app')

@section('title', $city->meta_title ?: $city->name . ' Firmaları')
@section('meta_description', $city->meta_description ?: $city->name . ' şehrindeki firmaları listeleyin.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ $city->name }}</span>
    </nav>

    <div class="rounded-2xl p-8 mb-8 text-white" style="background: linear-gradient(135deg, var(--hero_gradient_from), var(--hero_gradient_to))">
        <h1 class="text-3xl font-bold">{{ $city->name }} Firmaları</h1>
        @if($city->plate_code)
            <p class="mt-2 opacity-80">Plaka: {{ $city->plate_code }}</p>
        @endif
        <p class="mt-2 text-sm opacity-70">{{ $companies->total() }} firma bulundu</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-64 shrink-0">
            <form action="{{ route('cities.show', $city->slug) }}" method="GET" class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-24">
                <h3 class="font-semibold text-gray-900 mb-4">Filtreler</h3>
                @if($districts->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">İlçe</label>
                        <select name="district" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                            <option value="">Tümü</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->slug }}" {{ request('district') == $d->slug ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Filtrele
                </button>
            </form>
        </div>
        <div class="flex-1">
            @if($companies->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($companies as $company)
                        @include('components.company-card', ['company' => $company, 'premium' => $company->is_premium])
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $companies->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <div class="text-5xl mb-4">📭</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bu şehirde henüz firma yok</h3>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
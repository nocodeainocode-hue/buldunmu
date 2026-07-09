@extends('layouts.app')

@section('title', $metaTitle ?? 'Firmalar')
@section('meta_description', 'Tüm firmaları listeleyin, kategorilere ve şehirlere göre filtreleyin.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ $metaTitle ?? 'Firmalar' }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-64 shrink-0">
            <form action="{{ url()->current() }}" method="GET" class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-24">
                <h3 class="font-semibold text-gray-900 mb-4">Filtreler</h3>

                <!-- Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arama</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma ara..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="">Tümü</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Şehir</label>
                    <select name="city" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="">Tümü</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct->slug }}" {{ request('city') == $ct->slug ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Filtrele
                </button>
                @if(request()->anyFilled(['q', 'category', 'city']))
                    <a href="{{ url()->current() }}" class="block text-center mt-2 text-sm text-gray-500 hover:text-indigo-600">Filtreleri Temizle</a>
                @endif
            </form>
        </div>

        <!-- Company List -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ $metaTitle ?? 'Tüm Firmalar' }}</h1>
                <span class="text-sm text-gray-500">{{ $companies->total() }} firma bulundu</span>
            </div>

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
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Firma Bulunamadı</h3>
                    <p class="text-gray-500">Aramanızla eşleşen firma bulunamadı. Lütfen farklı kriterlerle tekrar deneyin.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'İletişim')
@section('meta_description', $settings->meta_description ?? 'Bizimle iletişime geçin.')
@section('canonical', route('pages.contact'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::staticPage(
    'İletişim',
    $settings->meta_description ?? 'Bizimle iletişime geçin.',
    route('pages.contact'),
    'İletişim'
)])
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-breadcrumb :items="[['label' => 'İletişim']]" />
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 mt-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">İletişim</h1>
        <div class="prose max-w-none text-gray-700">
            @if($content)
                {!! $content !!}
            @else
                <p>Bizimle iletişime geçmek için aşağıdaki formu kullanabilirsiniz.</p>
            @endif
        </div>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="space-y-4 mt-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adınız *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konu</label>
                <select name="subject" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 bg-white">
                    <option value="">Seçiniz...</option>
                    <option value="Üyelik Paketleri" @selected(old('subject', request('subject') === 'uyelik' ? 'Üyelik Paketleri' : ''))>Üyelik Paketleri</option>
                    <option value="Reklam ve Sponsorluk" @selected(old('subject') === 'Reklam ve Sponsorluk')>Reklam ve Sponsorluk</option>
                    <option value="Firma Ekleme" @selected(old('subject') === 'Firma Ekleme')>Firma Ekleme</option>
                    <option value="Diğer" @selected(old('subject') === 'Diğer')>Diğer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mesajınız *</label>
                <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">{{ old('message') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $a }} + {{ $b }} = ? *</label>
                <input type="text" name="captcha" required class="w-32 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200" placeholder="?">
                <p class="text-xs text-gray-400 mt-1">Spam koruması için lütfen işlemi cevaplayın.</p>
            </div>
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">Gönder</button>
        </form>
    </div>
</div>
@endsection

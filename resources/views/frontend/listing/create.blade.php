@extends('layouts.app')

@section('title', 'Firma Ekle')
@section('meta_description', 'Firmanızı ücretsiz rehbere ekleyin.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Firma Ekle</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Firma Ekleme Talebi</h1>
        <p class="text-gray-500 mb-8">Firmanızı ücretsiz olarak rehbere ekleyin. Talebiniz incelendikten sonra yayınlanacaktır.</p>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('listing.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Firma Adı *</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 @error('company_name') border-red-300 @enderror">
                    @error('company_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yetkili Adı</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Web Sitesi</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="">Seçiniz</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Şehir</label>
                    <select name="city_id" id="city_select" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="">Seçiniz</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct->id }}" {{ old('city_id') == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İlçe</label>
                    <select name="district_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="">Seçiniz</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mesaj</label>
                    <textarea name="message" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">{{ old('message') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Talebi Gönder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
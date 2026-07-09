@extends('layouts.app')

@section('title', 'İletişim')
@section('meta_description', 'Bizimle iletişime geçin.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">İletişim</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">İletişim</h1>
        <div class="prose max-w-none text-gray-700">
            @if(contact === 'contact')
                <p class="mb-6">Bizimle iletişime geçmek için aşağıdaki formu doldurabilir veya iletişim bilgilerimizi kullanabilirsiniz.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4 mb-10">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adınız *</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konu</label>
                        <input type="text" name="subject" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mesajınız *</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200"></textarea>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">Gönder</button>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @if( ?? false)
                        <div class="p-4 bg-gray-50 rounded-xl text-center">
                            <div class="text-2xl mb-2">📞</div>
                            <p class="font-medium text-gray-900">{{  }}</p>
                        </div>
                    @endif
                    @if( ?? false)
                        <div class="p-4 bg-gray-50 rounded-xl text-center">
                            <div class="text-2xl mb-2">✉️</div>
                            <p class="font-medium text-gray-900">{{  }}</p>
                        </div>
                    @endif
                    @if( ?? false)
                        <div class="p-4 bg-gray-50 rounded-xl text-center">
                            <div class="text-2xl mb-2">📍</div>
                            <p class="font-medium text-gray-900">{{  }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p>Bu sayfanın içeriği yakında eklenecektir.</p>
            @endif
        </div>
    </div>
</div>
@endsection
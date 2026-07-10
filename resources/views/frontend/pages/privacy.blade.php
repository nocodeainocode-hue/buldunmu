@extends('layouts.app')
@section('title', 'Gizlilik Politikası')
@section('meta_description', $settings->meta_description ?? 'Gizlilik politikamız.')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Gizlilik Politikası</span>
    </nav>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Gizlilik Politikası</h1>
        <div class="prose max-w-none text-gray-700">
            @if($content)
                {!! $content !!}
            @else
                <p>Bu gizlilik politikası, {{ $settings->site_name ?? 'sitemiz' }} tarafından toplanan bilgilerin nasıl kullanılacağını açıklar.</p>
                <p>Kişisel verileriniz üçüncü şahıslarla paylaşılmaz. Sadece hizmet kalitesini artırmak amacıyla kullanılır.</p>
            @endif
        </div>
    </div>
</div>
@endsection

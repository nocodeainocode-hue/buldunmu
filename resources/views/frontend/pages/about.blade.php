@extends('layouts.app')
@section('title', 'Hakkımızda')
@section('meta_description', $settings->meta_description ?? 'Firma rehberimiz hakkında bilgi edinin.')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Ana Sayfa</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">Hakkımızda</span>
    </nav>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Hakkımızda</h1>
        <div class="prose max-w-none text-gray-700">
            @if($content)
                {!! $content !!}
            @else
                <p>{{ $settings->site_name ?? 'Firma Rehberi' }}, Türkiye genelinde güvenilir işletmeleri kategori, şehir ve firma adına göre aramanızı sağlayan kapsamlı bir firma rehberidir.</p>
                <p>Amacımız, kullanıcıların ihtiyaç duydukları işletmelere en hızlı ve doğru şekilde ulaşmasını sağlamaktır.</p>
            @endif
        </div>
    </div>
</div>
@endsection

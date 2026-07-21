@extends('layouts.app')
@section('title', 'Kullanım Şartları')
@section('meta_description', $settings->meta_description ?? 'Kullanım şartlarımız.')
@section('canonical', route('pages.terms'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::staticPage(
    'Kullanım Şartları',
    $settings->meta_description ?? 'Kullanım şartlarımız.',
    route('pages.terms'),
    'Kullanım Şartları'
)])
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-breadcrumb :items="[['label' => 'Kullanım Şartları']]" />
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 mt-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Kullanım Şartları</h1>
        <div class="prose max-w-none text-gray-700">
            @if($content)
                {!! $content !!}
            @else
                <p>{{ $settings->site_name ?? 'Sitemiz' }} kullanım şartları aşağıda belirtilmiştir. Siteyi kullanarak bu şartları kabul etmiş sayılırsınız.</p>
                <p>Sitede yer alan firma bilgileri düzenli olarak güncellenmekte olup, bilgilerin doğruluğu konusunda garanti verilmemektedir.</p>
            @endif
        </div>
    </div>
</div>
@endsection

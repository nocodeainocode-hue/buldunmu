@extends('layouts.app')
@section('title', 'Gizlilik Politikası')
@section('meta_description', $settings->meta_description ?? 'Gizlilik politikamız.')
@section('canonical', route('pages.privacy'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::staticPage(
    'Gizlilik Politikası',
    $settings->meta_description ?? 'Gizlilik politikamız.',
    route('pages.privacy'),
    'Gizlilik Politikası'
)])
@endpush

@section('content')
@if((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'departure-board')
    @include('frontend.departures.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin panoda nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'design-catalog')
    @include('frontend.catalog.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'signal-station')
    @include('frontend.signal.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin istasyonda nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
    @elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'ilan-board')
        @include('frontend.ilan.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin borsada nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'elegant')
    @include('frontend.elegant.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin bu zarif rehberde nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'mobile-app')
    @include('frontend.mobileapp.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Uygulamada hangi verilerin nasıl işlendiği.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' uygulaması üzerinden toplanan bilgilerin kullanımını açıklar.', 'Kişisel veriler üçüncü kişilerle paylaşılmaz; yalnızca hizmeti iyileştirmek için kullanılır.']])
@elseif(\App\View\Helpers\ThemeHelper::isPhoneShell(app()->bound('currentDirectory') ? app('currentDirectory') : null))
    @include('frontend.phone.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Cep ekranında hangi verilerin nasıl işlendiği.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' üzerinden toplanan bilgilerin kullanımını açıklar.', 'Kişisel veriler üçüncü kişilerle paylaşılmaz; yalnızca rehber hizmetini iyileştirmek için kullanılır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'cinematic-atlas')
    @include('frontend.cinema.info', ['pageTitle' => 'Gizlilik Politikası', 'pageDescription' => 'Verilerinizin nasıl işlendiğine ilişkin bilgiler.', 'fallbackParagraphs' => ['Bu sayfa, '.($settings->site_name ?? 'sitemiz').' tarafından toplanan bilgilerin nasıl kullanıldığını açıklar.', 'Kişisel verileriniz üçüncü kişilerle paylaşılmaz; hizmet kalitesini artırmak için kullanılır.']])
@else
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-breadcrumb :items="[['label' => 'Gizlilik Politikası']]" />
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 mt-6">
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
@endif
@endsection

@extends('layouts.app')
@section('title', 'Hakkımızda')
@section('meta_description', $settings->meta_description ?? 'Firma rehberimiz hakkında bilgi edinin.')
@section('canonical', route('pages.about'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::staticPage(
    'Hakkımızda',
    $settings->meta_description ?? 'Firma rehberimiz hakkında bilgi edinin.',
    route('pages.about'),
    'Hakkımızda'
)])
@endpush

@section('content')
@if((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'departure-board')
    @include('frontend.departures.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu peronun arkasındaki ekip ve servis hikâyesi.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki işletmeleri tek bir kalkış panosunda toplayır.', 'Amacımız, aradığınız işletmeye üç hamlede ulaşmanızı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'board-v2')
    @include('frontend.board.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu panonun arkasındaki ekip ve hikâye.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' mahalledeki işletmeleri tek panoda toplar.', 'Amacımız, aradığınız işletmeye birkaç dokunuşta ulaşmanızı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'design-catalog')
    @include('frontend.catalog.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu kataloğun arkasındaki ekip ve hikâye.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki işletmeleri tek bir katalogda toplar.', 'Amacımız, aradığınız işletmeye birkaç dokunuşta ulaşmanızı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'signal-station')
    @include('frontend.signal.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu istasyonun arkasındaki ekip ve hizmet hikâyesi.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki işletmeleri tek bir sinyal istasyonunda toplar.', 'Amacımız, aradığınız işletmeye birkaç dokunuşta ulaşmanızı sağlamaktır.']])
    @elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'ilan-board')
        @include('frontend.ilan.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu borsanın arkasındaki ekip ve hizmet hikâyesi.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki işletmeleri tek bir ilan borsasında toplar.', 'Amacımız, aradığınız işletmeye birkaç tıklamada ulaşmanızı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'elegant')
    @include('frontend.elegant.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu seçkin rehberin arkasındaki ekip ve hizmet hikâyesi.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki seçkin işletmeleri tek bir zarif rehberde toplar.', 'Amacımız, aradığınız işletmeye sofistike bir deneyimle ulaşmanızı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'mobile-app')
    @include('frontend.mobileapp.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu uygulamanın arkasındaki ekip ve hikâye.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' işletmeleri pratik bir mobil uygulama deneyiminde toplar.', 'Amacımız, aradığın işletmeye tek dokunuşta ulaşmanı sağlamaktır.']])
@elseif(\App\View\Helpers\ThemeHelper::isPhoneShell(app()->bound('currentDirectory') ? app('currentDirectory') : null))
    @include('frontend.phone.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Bu rehberin arkasındaki ekip ve hikâye.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' şehrin işletmelerini tek bir cep ekranında toplar.', 'Amacımız, aradığın işletmeye iki dokunuşta ulaşmanı sağlamaktır.']])
@elseif((app()->bound('currentDirectory') ? app('currentDirectory')?->template : null) === 'cinematic-atlas')
    @include('frontend.cinema.info', ['pageTitle' => 'Hakkımızda', 'pageDescription' => 'Rehberin arkasındaki hikâyeyi keşfedin.', 'fallbackParagraphs' => [($settings->site_name ?? 'Firma Rehberi').' Türkiye genelindeki işletmeleri bir araya getirir.', 'Amacımız, ihtiyaç duyduğunuz işletmelere hızlıca ulaşmanıza yardımcı olmaktır.']])
@else
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-breadcrumb :items="[['label' => 'Hakkımızda']]" />
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 mt-6">
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
@endif
@endsection

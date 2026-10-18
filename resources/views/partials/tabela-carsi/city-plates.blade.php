<ul class="tc-cities{{ !empty($tcInline) ? ' tc-cities--inline' : '' }}" role="list">
    @foreach($cities as $tcCity)
        @php $tcCityN = (int) ($tcCity->companies_count ?? 0); @endphp
        <li>
            <a class="tc-cplate{{ $tcCity->slug === 'diger-iller' ? ' is-zinc' : '' }}" href="{{ route('cities.show', $tcCity->slug) }}">
                <strong>{{ $tcCity->name }}</strong>
                @if($tcCityN > 0)<small>{{ number_format($tcCityN, 0, ',', '.') }} esnaf</small>@endif
            </a>
        </li>
    @endforeach
</ul>

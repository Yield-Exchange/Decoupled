{{-- @if(!empty($show_filter_page_1)) --}}
    <depositor-marketplace buy_url="{{ route('market.store.offer') }}"
                           marketplace_url="{{ route('market.get.offer') }}"
                           banks="{{ json_encode($banks) }}" products="{{ json_encode($products) }}"
                           market_place_filters="{{ json_encode($market_place_filters) }}">
    </depositor-marketplace>
{{-- @else --}}
    {{-- <marketplace-filter-page1 marketplace_url="{{ route('market.get.offer') }}"
                              products="{{ json_encode($products) }}">
    </marketplace-filter-page1>
@endif --}}
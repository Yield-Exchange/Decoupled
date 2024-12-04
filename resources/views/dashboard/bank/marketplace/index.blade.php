<m-default-page products="{{ json_encode($products) }}" organization="{{ json_encode($organization) }}"
    store_route="{{ json_encode(route('market.store.offer')) }}"
    home_route="{{ json_encode(route('market-place-offer.index')) }}"
    featured_offer_route="{{ json_encode(route('market.offer.featured')) }}" accepted_market_place_offer_url="{{ route('accepted_market_place_offer') }}"
    get_data_route="{{ json_encode(url('market-place-offer')) }}" auth_user="{{ json_encode(auth()->user()) }}">
</m-default-page>

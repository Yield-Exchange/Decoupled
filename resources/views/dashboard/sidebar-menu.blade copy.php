<ul class="nav nav-sidebar" data-nav-type="accordion">
    @foreach($menu as $item)
        @if(!$item['permission'] || $user->userCan($item['permission']))
        <li class="nav-item @if( arrayHasData($item['submenu'])) menu-open @endif @if( arrayHasData($item['submenu']) && highlightNavigation($item['url_name']) || checkStartsWith(request()->path(),$item['url_name']) ) menu-is-open @endif">
            <a href="{{ $item['url'] }}" class="nav-link nav-link-main {{ highlightNavigation($item['url_name']) ? 'active': '' }}">
                <img src="{{ $item['icon'] }}"><span>{{ $item['name'] }}</span>
                @if(isset($item['is_beta']) && $item['is_beta'])
                    <div class="beta-menu-wrapper"><img src="{{ asset('assets/dashboard/images/beta.svg') }}" /></div>
                @endif
            </a>
            @if( arrayHasData($item['submenu']) )
                <ul class="nav nav-treeview">
                @foreach($item['submenu'] as $submenu)
                    <li class="nav-item">
                        <a href="{{ $submenu['url'] }}" class="nav-link">
                            <span class=" @if (highlightNavigation($submenu['url_name']))  sub-active @endif">{{ $submenu['name'] }}</span>
                        </a>
                    </li>
                @endforeach
                </ul>
            @endif
        </li>
        @endif
    @endforeach
</ul>
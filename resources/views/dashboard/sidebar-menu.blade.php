<ul class="nav nav-sidebar" data-nav-type="accordion">
    @foreach ($menu as $item)
        @if (!$item['permission'] || $user->userCan($item['permission']))
            <li
                class="nav-item @if (arrayHasData($item['submenu'])) menu-open @endif @if (
                    (arrayHasData($item['submenu']) && highlightNavigation($item['url_name'])) ||
                        checkStartsWith(request()->path(), $item['url_name']) ||
                        (array_key_exists('routes', $item) && in_array(getPathPart(0), $item['routes']))) menu-is-open @endif">

                <a href="{{ $item['url'] }}"
                    class="nav-link nav-link-main {{ highlightNavigation($item['url_name']) ? 'active' : '' }}">
                    @if ($item['show_icon'])
                        <img src="{{ $item['icon'] }}">
                    @endif
                    <span>{{ $item['name'] }}</span>
                    @if (isset($item['is_beta']) && $item['is_beta'])
                        <div class="beta-menu-wrapper"><img src="{{ asset('assets/dashboard/images/beta.svg') }}" /></div>
                    @endif
                </a>

                @if (arrayHasData($item['submenu']))
                    <ul class="nav nav-treeview">
                        @foreach ($item['submenu'] as $submenu)
                            @if (!$submenu['permission'] || $user->userCan($submenu['permission']))
                                @if (arrayHasData($submenu['submenu']))
                                    <li
                                        class="nav-item @if (arrayHasData($submenu['submenu'])) menu-open @endif @if (
                                            (arrayHasData($submenu['submenu']) && highlightNavigation($submenu['url_name'])) ||
                                                checkStartsWith(request()->path(), $submenu['url_name']) ||
                                                (array_key_exists('routes', $submenu) && in_array(getPathPart(0), $submenu['routes']))) menu-is-open @endif">

                                        <a href="{{ $submenu['url'] }}"
                                            class="nav-link nav-link-main {{ highlightNavigation($submenu['url_name']) ? 'active' : '' }}">
                                            @if ($item['show_icon'])
                                                <img src="{{ $submenu['icon'] }}">
                                            @endif
                                            <span>{{ $submenu['name'] }}</span>
                                            @if (isset($submenu['is_beta']) && $submenu['is_beta'])
                                                <div class="beta-menu-wrapper"><img
                                                        src="{{ asset('assets/dashboard/images/beta.svg') }}" /></div>
                                            @endif
                                        </a>
                                        {{-- {{ request()->path() }} --}}
                                        @if (isset($submenu['routes']) && in_array(request()->path(), $submenu['routes']))
                                            @if (arrayHasData($submenu['submenu']))
                                                <ul class="nav nav-treeview">
                                                    @foreach ($submenu['submenu'] as $lastsubmenu)
                                                        <li class="nav-item">
                                                            <a href="{{ $lastsubmenu['url'] }}" class="nav-link">
                                                                <span
                                                                    class="@if (highlightNavigation($lastsubmenu['url_name'])) sub-active @endif ml-4">
                                                                    {{ $lastsubmenu['name'] }}
                                                                </span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a href="{{ $submenu['url'] }}" class="nav-link">
                                            <span class=" @if (highlightNavigation($submenu['url_name'])) sub-active @endif">
                                                @if ($submenu['show_icon'])
                                                    @if ($submenu['icon'])
                                                        <img src="{{ $submenu['icon'] }}">
                                                    @endif
                                                @endif
                                                {{ $submenu['name'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endforeach
</ul>

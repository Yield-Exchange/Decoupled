<li class="dropdown dropdown-user krishna" style="margin-top: 10px;">
    <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle no-page-exit-alert"
       data-toggle="dropdown">
        <img src="{{ asset('assets/dashboard/icons/pepicons-pop_dots-y.svg') }}" />
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-navbar">
        @if(!is_admin_route(request()) && !$user->is_super_admin)
{{--            @if ($organization->is_non_partnered_fi != 1)--}}
                <a href="javascript:void()" class="dropdown-item no-page-exit-alert">
                    <i class="icon-volume-mute"></i> Email Notifications &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ getUserPreference('mute_notification') == 0 ? "checked" : "" }} data-toggle="toggle" class="pull-right update_notification_preference"></a>
{{--            @endif--}}
        @endif
        {{-- @if(can_switch_to_organizations($user))
            <a href="javascript:void()" data-toggle="modal" data-target="#switch-organization-modal" class="dropdown-item no-page-exit-alert"><i class="icon-reload-alt"></i>Switch Organization</a>
        @endif --}}
        @foreach($menu as $item)
            @if(!$item['permission'] || $user->userCan($item['permission']))
                <a href="{{ $item['url'] }}" class="dropdown-item"><img src="{{ $item['icon'] }}"> {{ $item['name'] }}</a>
            @endif
        @endforeach
        @if( in_array(getEnvironmentNameEmailTag(),['UAT','DEV','LOCALHOST']) && $user->organization && $user->organization->type=='DEPOSITOR')
            <a href="{{ url('/post-request?demo_setup=1') }}" class="dropdown-item no-page-exit-alert"><i class="icon-cog"></i>Demo Setup</a>
        @endif
        <div class="dropdown-divider"></div>
        <a href="{{ url('logout') }}" class="dropdown-item no-page-exit-alert"><i class="icon-switch2"></i>Logout</a>
    </div>
</li>
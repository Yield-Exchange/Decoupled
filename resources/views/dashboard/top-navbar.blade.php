<!-- Main navbar -->
<div style="border:0; width:100% !important;" class="dashboard-navbar navbar navbar-expand-md navbar-dark">

    <div class="collapse navbar-collapse justify-content-between" id="navbar-mobile"
        style="background-color:white;border:1px white solid;padding-right: 1rem !important">
        <div class="ml-3">
            @include('dashboard.switch-timezone')
        </div>
        {{-- <span class="ml-md-3 mr-md-auto"></span> --}}

        {!! adminLoginAsClientBack() !!}

        <ul class="navbar-nav">
            @if (!is_admin_route(request()) && !$user_data->is_super_admin)
                {{-- @if ($user->userCan('depositor/pending-deposits/page-access'))
                    <li class="nav-item dropdown" style="margin-top:5px">
                        <a href="{{ $organization->type == 'DEPOSITOR' ? url('/pending-deposits') : url('/bank-pending-deposits') }}"
                            class="navbar-nav-link nav-notifications" id="nav-chats">
                            <img src="{{ asset('assets/dashboard/icons/message.svg') }}" />
                            <span class="d-md-none ml-2">Messages</span>
                            <chats-count :chats="chats">
                            </chats-count>
                        </a>
                    </li>
                @endif --}}
                @if ($user->userCan('universal/notifications/page-access'))
                    <li class="nav-item dropdown" style="margin-top: 5px">
                        <a href="{{ route('user.notifications') }}" class="navbar-nav-link nav-notifications"
                            id="nav-notify">
                            <img src="{{ asset('assets/dashboard/icons/notification.svg') }}" />
                            <span class="d-md-none ml-2">Notifications</span>
                            <notify-count :notifications="notifications">
                            </notify-count>
                        </a>
                    </li>
                @endif
            @endif
            @if (!empty($organization->logo))
                <li class="nav-item" style="margin-top: 8px;">

                    <img src="{{ url('image/' . $organization->logo) }}" class="rounded-circle mr-2" height="45"
                        alt="" />

                </li>
            @else
                <li class="nav-item" style="margin-top: 18px;">
                    <span
                        class="i-initial-inverse"><span>{{ !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}</span></span>
                </li>
            @endif


            <li class="nav-item" style="margin-top: 7px; padding-left: 15px">
                <div
                    style="text-align: center; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; line-height: 22px; letter-spacing: 0.40px; word-wrap: break-word">
                    {{ !empty($organization->name) ? $organization->name : 'Yield Exchange' }}</div>
                <div
                    style="text-align: center; color: #4200FF; font-size: 11px; font-family: Montserrat; font-weight: 700; line-height: 22px; letter-spacing: 0.28px; word-wrap: break-word">
                    {{ !empty($organization->type)
                        ? ($organization->type == 'BANK'
                            ? $organization->type
                            : 'INVESTOR')
                        : ($user_data->is_super_admin
                            ? 'System Admin'
                            : 'N/A') }}
                </div>
            </li>
            @php (new \App\View\Navigation\NavBarActionBar())->render() @endphp

            {{-- @php(new \App\View\Navigation\NavBarActionBar())->render() @endphp --}}
        </ul>
    </div>
</div>
<!-- /main navbar -->

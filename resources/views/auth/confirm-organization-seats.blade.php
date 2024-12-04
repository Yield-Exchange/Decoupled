@extends('dashboard.master')
@section('page_title')
    Add Users
@stop
@section('styles')

@endsection
@section('page_content')
    <!-- Main charts -->
    <div class="row" id="VueApp">
        <div class="col-xl-12">
            <!-- Traffic sources -->

            @php
                $provinces = json_encode(provinces());
                $timezones = json_encode(timezonesList());
                $roles = json_encode(App\Role::whereNotIn('roles.name',unfetchableRoles())->get());
                $currentRoute = json_encode(url('first-login/confirm-organization-seats'));
                $createRoute = json_encode(route('users.create'));
            @endphp

            <div class="row">
                <div class="col-md-12 col-lg-2"></div>
                <div class="col-md-12 col-lg-8">
                        <sign-up-step3
                                organization="{{ $organization }}"
                                submit-route="{{ route('complete-registration-organization') }}"
                                auth-user="{{ $auth_user }}"
                                users="{{ $users }}"
                                request-seat-route="{{ route('request-organization-seats') }}"
                                organization_seat_rate="{{ $organization_seat_rate }}"
                                provinces="{{ $provinces }}" timezones="{{ $timezones }}"
                                roles="{{ $roles }}"  createroute="{{ $createRoute }}"
                                listroute="{{ $currentRoute }}"
                        >
                        </sign-up-step3>
                </div>

                <div class="col-md-12 col-lg-2"></div>
            </div>

            <!-- /support tickets -->
        </div>
    </div>

    <!-- /main charts -->
@endsection
@section('scripts')

@endsection

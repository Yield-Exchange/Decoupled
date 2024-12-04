{{-- <div id=""> --}}
<div class="w-100">
    <login-form action="{{ $action }}" is-admin="{{ is_admin_route($request) }}"
        login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
        register-route="{{ url('/request-an-account') }}" skip_robot="{{ can_skip_robot_check_and_otp() }}"
        {{-- otp --}}
        resend-pin-route="{{ is_admin_route($request) ? route('admin.resend-otp') : url('resend-otp') }}"
        verify-otp-route="{{ is_admin_route($request) ? route('admin.verify-otp') : url('verify-otp') }}"
        dashboard-route="{{ is_admin_route($request) ? route('admin.home') : url('/launchpad') }}"
        user-id="{{ $hashed_user_id }}" {{-- reset --}}
        reset-route="{{ is_admin_route($request) ? route('admin.reset-password') : url('reset-password') }}"
        {{-- chnage password --}}
        password-change-route="{{ is_admin_route($request) ? route('admin.reset-password-final-step') : url('reset-password-final-step') }}"
        reset-code="{{ $code }}">

    </login-form>
    {{-- </div> --}}
</div>

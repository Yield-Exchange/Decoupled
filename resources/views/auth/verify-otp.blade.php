<div id="row">
    <div class="col-md-12 row">
        <verify-otp
                is-admin="{{is_admin_route($request)}}"
                login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
                resend-pin-route="{{ (is_admin_route($request)  ? route('admin.resend-otp')  : url('resend-otp')) }}"
                verify-otp-route="{{ is_admin_route($request) ? route('admin.verify-otp') : url('verify-otp') }}"
                dashboard-route="{{ is_admin_route($request) ? route('admin.home') : url('/dashboard') }}"
                user-id="{{ $hashed_user_id }}"
        >
        </verify-otp>
    </div>
</div>

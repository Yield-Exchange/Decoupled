<div id="row">
    <div class="col-md-12 row">
        <reset-password
                is-admin="{{is_admin_route($request)}}"
                login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
                login-as="{{ is_admin_route($request) ? "Admin": $loginAs }}"
                reset-route="{{ is_admin_route($request) ? route('admin.reset-password') : url('reset-password') }}"
                recaptcha-key="{{ config('app.RECAPTCHA_KEY') }}"
                skip_robot="{{ can_skip_robot_check_and_otp() }}"
        ></reset-password>
    </div>
</div>

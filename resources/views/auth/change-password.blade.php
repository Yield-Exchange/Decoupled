<div id="row">
    <div class="col-md-12 row">
        <change-password
                is-admin="{{is_admin_route($request)}}"
                login-route="{{ is_admin_route($request) ? route('admin.login') : url('login') }}"
                password-change-route="{{ is_admin_route($request) ? route('admin.reset-password-final-step') : url('reset-password-final-step') }}"
                reset-code="{{ $code }}"
        >
        </change-password>
    </div>
</div>
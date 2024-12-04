<link rel="stylesheet" href="{{ asset('assets/css/passwordmeter/custom.css?v=1.0') }}" />
<style type="text/css">
    .swal-modal .swal-text {
        text-align: center;
    }
</style>
<form action="{{ is_admin_route($request) ? route('admin.reset-password-final-step') : url('reset-password-final-step') }}" id="form-change-password" method="post">
    <span class="form-text text-muted">Enter the new password. </span>
    <div class="form-group form-group-feedback form-group-feedback-left">
        <input type="hidden" name="code" value="{{ $code }}" class="form-control"/>
        @csrf
    </div>

    <div class="form-group" id="complexity">
        <input id="lgd_out_pg_pass" name="pass" class="password form-control" type="password" onkeyup="checkThisPassword(this.value);" placeholder="New password" value="" />
        <span id="complexity-span" class="pmshow">No Password</span>
    </div>

    <div class="generate_button chars_holder row col-md-12">
        <div class="char_type col-md-3" id="special_count">
            <span class="char_type_text" style="padding-left: 15px;">Symbols</span>
        </div>

        <div class="char_type col-md-3" id="digits_count">
            <span class="char_type_text" style="padding-left: 15px;">Numbers</span>
        </div>

        <div class="char_type col-md-3" id="upper_count">
            <span class="char_type_text" style="padding-left: 15px;">Upper case</span>
        </div>
        <div class="char_type col-md-3" id="lower_count">
            <span class="char_type_text" style="padding-left: 15px;">Lower case</span>
        </div>
        <div class="breaker">&nbsp;</div>
    </div>

    <div class="form-group form-group-feedback form-group-feedback-right" style="margin-top: 15px">
        <input type="password" name="cpass" class="form-control pass_confirm" id="conf_pass" placeholder="Confirm New Password" required/>
        <div class="form-control-feedback">
            <i style="margin-top:10px;" class="icon-lock2 text-muted"></i>
        </div>
        <span id="pass_error_confirm" style="color:red"></span>
    </div>

    <div class="row">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)"> Show Password
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a style="color: #4587FD;" href="{{ is_admin_route($request) ? route('admin.login') : url('login') }}" class="btn btn-link">Sign In Instead</a>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <button type="submit" disabled class="btn custom-primary round btn change_pwd">Submit</button>
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script>
        $('#form-change-password').on('submit', function (e) {
            e.preventDefault();

            $(".change_pwd").attr('disabled', true).html('Submitting...');
            $this = $('#form-change-password');
            $loader = $(".proloader");

            makeApiCall($this.attr('action'), $('#form-change-password').serialize(), function(response) {
                if(response.success) {
                    $(".alert").hide();
                    swal(response.message_title,response.message).then(function () {
                        $loader.show();
                        window.location.href = "{{ is_admin_route($request) ? route('admin.login') : url('login') }}";
                    });
                }else{
                    $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(response.message).show();
                }
                $(".change_pwd").attr('disabled', false).html('Submit');
                $this.trigger("reset");
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                let response = xhr?.responseJSON;
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(apiCallServerErrorMessage(xhr,"Unable to change password, try again later")).show();
                $(".change_pwd").attr('disabled', false).html('Submit');
            });

        });
    </script>
    <script src="{{ asset('assets/js/passwordmeter/zxcvbn.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/passwordmeter/index.js?v=1.0') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/reset-password.js?v=1.0') }}" type="text/javascript"></script>
@endsection
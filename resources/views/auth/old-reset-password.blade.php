<div class="row m-3">
    @if (is_admin_route($request))
        <h5 class="mb-0">Password Reset</h5>
    @else
    <ul class="nav nav-tabs">
        @if($loginAs!="fi")
        <li class="nav-item">
            <a class="nav-link {{ $loginAs != "fi" ? 'active' : '' }} userType" data="Depositor" href="javascript:void();">Wholesale Investor</a>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link {{ $loginAs == "fi" ? 'active' : '' }} userType" data="Bank" href="javascript:void();">Financial Institution</a>
        </li>
        @endif
    </ul>
    @endif
</div>
<form action="{{ is_admin_route($request) ? route('admin.reset-password') : url('reset-password') }}" method="post" id="reset_password_form">
    <span class="form-text text-muted">Enter your registered email address and we'll send you the link to reset the password. </span>
    <input type="hidden" name="loginAs" value="{{ $loginAs=="fi" ? "Bank" : (is_admin_route($request) ? "Admin": "Depositor") }}" />
    <br>
    <div class="form-group form-group-feedback form-group-feedback-left">
        <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required />
        <div class="form-control-feedback">
            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
            @csrf
        </div>
    </div>

    <div class="form-group">
        <label id="recpatchareq" style="color:black;display:none">Please verify that you are not a robot</label>
        <div class="g-recaptcha" data-sitekey="{{ config('app.RECAPTCHA_KEY') }}" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
        <input class="form-control d-none recaptchar" name="recaptcha" data-recaptcha="true" data-error="Please complete the Captcha"/>
        <br/>
    </div>

    <div class="row">
        <div class="col-md-6">
            <a style="color: #4587FD;" href="{{ is_admin_route($request) ? route('admin.login') : url('login') }}" class="btn btn-link">Sign In Instead</a>
        </div>
        <div class="col-md-6">
            <div class="form-group d-flex align-items-center">
                <button type="submit" class="btn btn-primary forget_pwd">Reset password</button>
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $('#reset_password_form').on('submit', function (e) {
            e.preventDefault();

            if (jQuery(".recaptchar").val() == "") {
                jQuery("#recpatchareq").show();
                swal("","Please verify that you are not a robot","info");
                return false;
            } else {
                jQuery("#recpatchareq").hide();
            }

            $(".forget_pwd").attr('disabled', true).html('Please wait...');
            $this = $('#reset_password_form');
            $loader = $(".proloader");

            makeApiCall($this.attr('action'), $('#reset_password_form').serialize(), function(response) {
                if(response.success) {
                    $(".alert").hide();
                    swal(response.message_title,response?.message).then(function () {
                        $loader.show();
                        window.location.href = "{{ is_admin_route($request) ? route('admin.login') : url('login') }}";
                    });
                }else{
                    $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(response.message).show();
                }
                $(".forget_pwd").attr('disabled', false).html('Reset password');
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
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(apiCallServerErrorMessage(xhr,"Unable to reset password, try again later")).show();
                $(".forget_pwd").attr('disabled', false).html('Reset password');
            });

        });
    </script>
    <script>
        @if (!is_admin_route($request))
        $(document).ready(function () {
            $(document).on('click','.userType',function (e) {
                e.preventDefault();
                $('.userType').removeClass('active');
                $(this).addClass('active');
                $('input[name="loginAs"]').val($(this).attr('data'));
            });
        });
        @endif

        function checkRecaptcha() {

            if (jQuery('.recaptchar').val() == "") {
                jQuery("#recpatchareq").show();
            } else {
                jQuery("#recpatchareq").hide();
            }

        }

        $(function () {

            checkRecaptcha();
            window.verifyRecaptchaCallback = function (response) {
                $('.recaptchar').val(response).trigger('change');
            };

            window.expiredRecaptchaCallback = function () {
                $('.recaptchar').val("").trigger('change');
            };

        });

    </script>
@endsection
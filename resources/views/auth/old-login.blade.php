<div class="row m-3">
    @if (is_admin_route($request))
        <h5 class="mb-0">Login to your account</h5>
    @else
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $loginAs != "fi" ? 'active' : '' }} userType" data="Depositor" href="javascript:void();">Wholesale Investor</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $loginAs == "fi" ? 'active' : '' }} userType" data="Bank" href="javascript:void();">Financial Institution</a>
        </li>
    </ul>
    @endif
</div>
<form action="{{ is_admin_route($request) ? route('admin.login') : url('login') }}" method="post" id="loginForm">
    <div class="form-group form-group-feedback form-group-feedback-right">
        <input type="hidden" name="loginAs" value="{{ $loginAs=="fi" ? "Bank" : (is_admin_route($request) ? "Admin" :"Depositor") }}" />
        <input type="email" name="email" class="form-control" placeholder="Email" required/>
        <div class="form-control-feedback">
            <i style="margin-top:10px;" class="icon-user text-muted"></i>
        </div>
    </div>

    <div class="form-group form-group-feedback form-group-feedback-right">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required/>
        @csrf
        <div class="form-control-feedback">
            <i style="margin-top:10px;" class="icon-lock2 text-muted"></i>
        </div>
    </div>

    <div class="row">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)" /> Show Password
            </label>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right" style="margin-bottom: 20px">
            <a href="{{ is_admin_route($request) ? route('admin.login') : url('login') }}?action=resetPassword&loginAs={{$loginAs}}" class="ml-auto forgot-pass">Forgot password?</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        @if (!is_admin_route($request))
        <div class="col-md-4">
            <div class="form-group d-flex align-items-center">
                <a href="{{ url('sign-up') }}"  class="btn custom-primary round">Sign up</a>
            </div>
        </div>
        @endif
        <div class="col-md-4">
            <div class="form-group d-flex align-items-center">
                <button type="submit" class="btn custom-primary round signInBtn">Sign in</button>
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script>
        $('#loginForm').on('submit', function (e) {
            e.preventDefault();

            $(".signInBtn").attr('disabled', true).html('Signing in...');
            $this = $('#loginForm');
            $loader = $(".proloader");

            makeApiCall($this.attr('action'), $('#loginForm').serialize(), function(response) {
                if(response.success) {
                    $(".alert").hide();
                    $loader.show();
                    // swal(response.message).then(function () {
                        window.location.href = "{{ is_admin_route($request) ? route('admin.login') : url('login') }}?action=verifyOtp";
                    // });
                }else{
                    $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(response.message).show();
                }
                $(".signInBtn").attr('disabled', false).html('Sign in');
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
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(apiCallServerErrorMessage(xhr,"Unable to sign in, try again later")).show();
                $(".signInBtn").attr('disabled', false).html('Sign in');
            });

        });
    </script>
    <script>
        function myFunction(thi) {
            let x = document.getElementById("password");
            if (thi.checked) {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        @if (!is_admin_route($request))
        $(document).ready(function () {
            $(document).on('click','.userType',function (e) {
                e.preventDefault();
                $('.userType').removeClass('active');
                $(this).addClass('active');
                $('input[name="loginAs"]').val($(this).attr('data'));
                let as = $(this).attr('data')=="Bank" ? "fi" : "inv";
                $(".forgot-pass").attr("href","{{ url('login') }}?action=resetPassword&loginAs="+as);
            });
        });
        @endif
    </script>
@endsection
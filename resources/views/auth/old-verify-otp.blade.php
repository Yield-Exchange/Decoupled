<form action="{{ is_admin_route($request) ? route('admin.verify-otp') : url('verify-otp') }}" method="post" id="verifyOtpForm">
    <div class="form-group">
        <input type="email" class="form-control" name="email" value="{{ $user ? $user->email : '' }}" readonly/>
        <input type="hidden" name="user_id" value="{{ $hashed_user_id }}" />
    </div>
    <div class="form-group">
        <input type="password" name="pin" class="form-control" placeholder="Enter Login Pin" required=""/>
    </div>
    @csrf
    <div class="row">
        <div class="col-md-4">
            <a href="{{ is_admin_route($request) ? route('admin.login') : url('login') }}" class="ml-auto">Back to Login</a>
        </div>
        <div class="col-md-4">
            <div class="form-group d-flex align-items-center">
                <a href="javascript:void();" class="btn btn-outline-primary resendPin">Resend PIN</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group d-flex align-items-center">
                <button type="submit" class="btn btn-primary verifyOtpBtn">Sign in</button>
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script>
        $('#verifyOtpForm').on('submit', function (e) {
            e.preventDefault();

            $(".verifyOtpBtn").attr('disabled', true).html('Verifying...');
            $this = $('#verifyOtpForm');
            $loader = $(".proloader");

            makeApiCall($this.attr('action'), $('#verifyOtpForm').serialize(), function(response) {
                if(response.success) {
                    // swal(response.message).then(function () {
                    $loader.show();
                    window.location.href = "{{ is_admin_route($request) ? route('admin.home') : url('/dashboard') }}";
                    // });
                }else{
                    $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(response.message).show();
                }
                $(".verifyOtpBtn").attr('disabled', false).html('Sign in');
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
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(apiCallServerErrorMessage(xhr,"Unable to verify login pin, try again later")).show();
                $(".verifyOtpBtn").attr('disabled', false).html('Sign in');
            });

        });

        $('.resendPin').on('click', function (e) {
            e.preventDefault();

            $this=$(this);
            $this.attr('disabled', true).html('Please wait...');
            $loader = $(".proloader");
            makeApiCall( '@php echo (is_admin_route($request)  ? route('admin.resend-otp')  : url('resend-otp')) @endphp', {
                'user_id': $('input[name="user_id"]').val(),
                '_token': '@php echo csrf_token() @endphp'
            }, function(response) {
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(response.message).show();
                $this.attr('disabled', false).html('Resend PIN');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();

                        return;
                    });
                }

                let response = xhr?.responseJSON;
                $(".alert").removeClassStartingWith('alert-').addClass(response?.alert_class ? response?.alert_class : "alert-info").html(apiCallServerErrorMessage(xhr,"Unable to resend the pin, try again later")).show();
                $this.attr('disabled', false).html('Resend PIN');
            });

        });
    </script>
@endsection
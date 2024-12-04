@extends('home.master')
@section('page_title')
    Sign Up
@stop
@section('styles')
    <style>
        .form-check-label > .form-input-styled{
            margin-right: 5px;
        }
        #banner{
            padding-top: 80px!important;
        }
        @media screen and (max-width: 578px) {
            #login_img{
                display: none;
            }
        }
        .accountTypeBtn{
            font-size: 14px;
            padding: 1%;
            color:#4587FD;background-color:white;border-color:#4587FD;
        }
        .form-group > input,.form-group > select, .form-group > .form-check{
            font-size: 14px;
        }
        #deposit_insurance,#short_term_credit{
            display: none;
        }
        .tooltip-inner{
            background: transparent!important;
        }
        .tooltip-inner > img{
            width: 400px!important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/passwordmeter/custom.css?v=1.3') }}" />
@stop
@section('page_content')
<div id="banner" style="background:white!important;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <form action="{{ url('sign-up') }}" method="post" id="signup-form">
                    @csrf
                    <br />
                    <span>You are registering as</span>
                    <br>
                    <div class="form-group">
                        <label class="btn btn-primary accountTypeBtn">
                            <input type="radio" name="userType" value="Depositor" checked class="btn btn-primary register_as"> GIC Investor
                        </label>
                        <label class="btn btn-primary accountTypeBtn">
                            <input style="color:black;background-color:grey" type="radio" name="userType" value="Bank" class="btn btn-primary register_as" required> Financial Institution
                        </label>
                    </div>

                    <div class="form-group select_institution">
                        <select name="institution_s" class="form-control select2 institution_s">
                            <option value="">Select Institution</option>
                            @php
                            $fis = getFIs();
                            @endphp
                            @foreach($fis as $fi)
                            <option value="{{ $fi->name }}">{{ $fi->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control input_institution" placeholder="Institution" name="institution">
                        <div class="form-control-feedback">
                            @csrf
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Address Line 1" name="address" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Address Line 2" name="address2">
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="City" name="city" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="province" class="form-control select2" required>
                            <option value="">Select Province/Territory</option>
                            @php
                                $provinces = provinces();
                            @endphp
                            @foreach($provinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Postal Code" name="postal" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-check text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="time_zone" class="form-control" required>
                            <option value="">Select Your Timezone</option>
                            @php
                                $timezones = timezonesList();
                            @endphp
                            @foreach($timezones as $key => $timezone)
                                <option value="{{ $key }}">{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" name="admin_name" class="form-control" placeholder="Your name" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" name="telephone" class="form-control" placeholder="Your Telephone" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group" id="complexity">
                        <input id="lgd_out_pg_pass" name="pass" class="password form-control" type="password" onkeyup="checkThisPassword(this.value);" placeholder="Your password" value="" />
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
                        <div class="char_type col-md-3 " id="lower_count">
                            <span class="char_type_text" style="padding-left: 15px;">Lower case</span>
                        </div>
                        <div class="breaker">&nbsp;</div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left" style="margin-top: 15px">
                        <input type="password" id="myInput1" name="cpass" class="form-control pass_confirm" placeholder="Confirm password" data-toggle-title="Show Password" required/>
                        <div class="form-control-feedback">
                            <i style="margin-top:10px;" class="icon-user-lock text-muted"></i>
                        </div>
                        <span id="pass_error_confirm" style="color:red"></span>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-input-styled" onclick="myFunction(this)"> Show Password
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label id="recpatchareq" style="color:black;display:none">Please verify that you are not a robot</label>
                        <div class="g-recaptcha" data-sitekey="{{ config('app.RECAPTCHA_KEY') }}" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                        <input class="form-control d-none" name="recaptcha" data-recaptcha="true" required data-error="Please complete the Captcha"/>
                        <br/>
                    </div>
                    <button type="submit" name="signup" disabled class="btn btn-primary mmy_btn btn-block signup_btn">Register</button>
                </form>
                <!-- /page content -->
            </div>
            <div class="col-md-8">
                <img style="height:100%;width: 105%" id="login_img" src="{{ asset('assets/images/back.jpg') }}">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="{{ asset('assets/js/passwordmeter/zxcvbn.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/passwordmeter/index.js?v=1.0') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/signup.js?v=1.2.1') }}" type="text/javascript"></script>

<script>
    $('a[data-toggle="tooltip"]').tooltip({
        sanitize: false,
        animated: 'fade',
        placement: 'bottom',
        html: true,
        viewport: '#signup-form'
    });
</script>
@endsection
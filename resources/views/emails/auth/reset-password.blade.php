@extends('emails.new-master')
@section('page-content')

    <div style="padding: 0 5%;margin-right:auto; margin-left:auto" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/mdi_security-lock-outline.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Account Security
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="marign-top: 20px; margin-bottom:20px;color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center"
            align="center">
            Reset Your Password
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{asset('assets/emails/reset-password.png')}}" class="cover-image" alt=""
                style="max-height:400px">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action"
                style="color:#5063F4; font-family:Montserrat; font-size:24px; font-style:normal; font-weight:700; line-height:normal">
                Reset your password by clicking the link below. </p>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">For increased account security, this password link will expire in 30 minutes after it was
                sent.</p>
        </div>


        @if ($show_login || $show_register || count($other_buttons) > 0)
            <div id="buttons-div" style="width: 100%">

                @if ($show_login && strtolower(trim($user_type)) != 'admin')
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/') }}/login?loginAs={{ strtolower(trim($user_type)) == 'bank' ? 'fi' : 'inv' }}"
                            class="view-button"
                            style="align-items:center; background:#5063F4; border-radius:20px; color:white; cursor:pointer; display:flex; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:400; justify-content:center; line-height:20px; margin:0 auto; paddin-left:15% !important; text-decoration:none; padding:10px 30px; text-transform:capitalize; width:250px"
                            width="250">
                            Login
                        </a>
                    </div>
                @elseif ($show_login && strtolower(trim($user_type)) == 'admin')
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/yie-admin/login') }}" class="view-button"
                            style="align-items:center; background:#5063F4; border-radius:20px; color:white; cursor:pointer; display:flex; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:400; justify-content:center; line-height:20px; margin:0 auto; paddin-left:15% !important; text-decoration:none; padding:10px 30px; text-transform:capitalize; width:250px"
                            width="250">
                            Login
                        </a>
                    </div>
                    {{-- <a href="%7B%7B%20url('/yie-admin/login')%20%7D%7D" class="btn btn-outline-info btn-lg" id="button2">Login</a> --}}
                @endif

                @if ($show_register)
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('request-an-account') }}" class="view-button"
                            style="align-items:center; background:#5063F4; border-radius:20px; color:white; cursor:pointer; display:flex; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:400; justify-content:center; line-height:20px; margin:0 auto; paddin-left:15% !important; text-decoration:none; padding:10px 30px; text-transform:capitalize; width:250px"
                            width="250">
                            Register
                        </a>
                    </div>
                    {{-- <a href="%7B%7B%20url('/')%20%7D%7D/signup" class="btn btn-info btn-lg" id="button1">Register</a> --}}
                @endif

                @if (count($other_buttons) > 0)
                    @php
                        $count_n = count($other_buttons);
                        $i = 1;
                    @endphp

                    @foreach ($other_buttons as $button)
                        @if ($count_n > 1 && $i == 1)
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" style="border-bottom:3px solid white; border-top:none">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" bgcolor="#ffffff"
                                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                                <a href="{{ $button['link'] }}" 
                                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                                    {{ $button['linkName'] }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @else
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" style="border-bottom:3px solid white; border-top:none">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" bgcolor="#ffffff"
                                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                                <a href="{{ $button['link'] }}"
                                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                                    {{ $button['linkName'] }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @endif

                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endif
                <div>
        @endif





        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login"
                style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:0 auto; paddin-left:15% text-decoration:none; text-align:center"
                align="center">Discover a world of exclusive GIC's waiting for you <a
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection
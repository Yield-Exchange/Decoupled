@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <g clip-path="url(#clip0_5556_8582)">
                        <path
                            d="M12.3135 6.60225C12.3135 9.83976 10.0735 12.8673 7.06348 13.6023C4.05348 12.8673 1.81348 9.83976 1.81348 6.60225V3.10225L7.06348 0.768921L12.3135 3.10225V6.60225ZM7.06348 12.4356C9.25098 11.8523 11.1468 9.25059 11.1468 6.73059V3.86059L7.06348 2.04059L2.98014 3.86059V6.73059C2.98014 9.25059 4.87598 11.8523 7.06348 12.4356ZM8.69681 6.60225V5.72725C8.69681 4.91059 7.88014 4.26892 7.06348 4.26892C6.24681 4.26892 5.43014 4.91059 5.43014 5.72725V6.60225C5.08014 6.60225 4.73014 6.95225 4.73014 7.30225V9.34392C4.73014 9.75225 5.08014 10.1023 5.43014 10.1023H8.63848C9.04681 10.1023 9.39681 9.75226 9.39681 9.40226V7.36059C9.39681 6.95225 9.04681 6.60225 8.69681 6.60225ZM7.93848 6.60225H6.18848V5.72725C6.18848 5.26059 6.59681 4.96892 7.06348 4.96892C7.53014 4.96892 7.93848 5.26059 7.93848 5.72725V6.60225Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_5556_8582">
                            <rect width="14" height="14" fill="white" transform="translate(0.0634766 0.185547)" />
                        </clipPath>
                    </defs>
                </svg>
                <p class="m-0 p-0">Account Security</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Reset Your Password
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/reset-password.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action"> Reset your password by clicking the link below. </p>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">We've received a request to change your password.</p>
        </div>


        @if ($show_login || $show_register || count($other_buttons) > 0)
            <div id="buttons-div">

                @if ($show_login && strtolower(trim($user_type)) != 'admin')
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/') }}/login?loginAs={{ strtolower(trim($user_type)) == 'bank' ? 'fi' : 'inv' }}"
                            class="view-button">
                            Login
                        </a>
                    </div>
                @elseif ($show_login && strtolower(trim($user_type)) == 'admin')
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/yie-admin/login') }}" class="view-button">
                            Login
                        </a>
                    </div>
                    {{-- <a href="{{ url('/yie-admin/login') }}" class="btn btn-outline-info btn-lg" id="button2">Login</a> --}}
                @endif

                @if ($show_register)
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/') }}/signup" class="view-button">
                            Register
                        </a>
                    </div>
                    {{-- <a href="{{ url('/') }}/signup" class="btn btn-info btn-lg" id="button1">Register</a> --}}
                @endif

                @if (count($other_buttons) > 0)
                    @php
                        $count_n = count($other_buttons);
                        $i = 1;
                    @endphp

                    @foreach ($other_buttons as $button)
                        @if ($count_n > 1 && $i == 1)
                            <div class="d-flex justify-content-center" style="
                            display: flex;
                            justify-content: center;
                        ">
                                <a href="{{ $button['link'] }}" class="view-button">
                                    {{ $button['linkName'] }}
                                </a>
                            </div>
                            {{-- <a href="{{ $button['link'] }}" style="background:white; color:blue;"
                                class="btn btn-info btn-lg" id="button1">{{ $button['linkName'] }}</a> --}}
                        @else
                            <div class="d-flex justify-content-center" style="
                            display: flex;
                            justify-content: center;
                        ">
                                <a href="{{ $button['link'] }}" class="view-button">
                                    {{ $button['linkName'] }}
                                </a>
                            </div>
                            {{-- <a href="{{ $button['link'] }}" class="btn btn-info btn-lg"
                                id="button1">{{ $button['linkName'] }}</a> --}}
                        @endif

                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endif
                <div>
        @endif

  



        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login">Discover a world of exclusive GIC's waiting for you <a
                    href="https://app.yieldexchange.ca/request-an-account"><span>Sign Up </span></a> Or
                <a href="https://app.yieldexchange.ca/login"> <span> Log In</span></a>
            </p>
        </div>
    </div>
@endsection

<style>
    .m-0{
        margin: 0;
    }
    .p-0{
        padding: 0;
    }
    .my-2{
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .my-4{
      margin-top: 1rem;
      margin-bottom: 1rem;
    }
    .w-100{
        width: 100%;
    }
    .justify-content-center {
        text-align: center;
    }
    .d-flex{
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .cover-image {
        max-height: 400px
    }

    .campaign-status-text {
        color: #5063F4;
        text-align: center;
        font-family: Montserrat;
        font-size: 38px;
        font-style: normal;
        font-weight: 700;
        line-height: 42px;
        /* 110.526% */
    }

    .window-button {
        border-radius: 85.91px;
        background: #5063F4;
        display: flex;
        height: 22.371px;
        padding: 3.436px 10.309px;
        justify-content: center;
        align-items: center;
        gap: 6.873px;
    }

    .window-button p {
        color: #FFF;
        /* Yield Exchange Text Styles/Tooltips */
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 14px;
        /* 127.273% */
    }

    .action-message {
        color: var(--yield-exchange-pallette-yield-exchange-black, var(--yield-exchange-colors-darks, #252525));
        text-align: center;
        font-family: Montserrat;
        font-size: 25px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
    }

    .action-message span,
    .suggest-action {
        color: #5063F4;
        font-family: Montserrat;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .action-suggested {
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        line-height: normal;
        background: #F4F5F6;
        padding: 10px;
        margin: 12px
    }

    .action-suggested span {
        color: #5063F4;
        font-weight: 700;
    }

    .discover-gic {
        background: #EFF2FE;
        align-items: center
    }

    .gic-item {
        color: #252525;
        text-align: center;
        font-family: Montserrat;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .gic-item span {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));

        font-size: 39px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .action-suggested p {
        color: #252525;
        font-weight: 300;
    }

    .view-button {
        color: white;
        cursor: pointer;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        display: flex;
        padding: 10px 30px;
        justify-content: center;
        align-items: center;
        border-radius: 20px;
        background: #5063F4;
        width: 250px;
        /* 125% */
        text-transform: capitalize;
        margin: 1rem
    }

    .view-button-outline {
        color: #5063F4;
        cursor: pointer;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        display: flex;
        padding: 10px 30px;
        justify-content: center;
        align-items: center;
        border-radius: 20px;
        background: #fff;
        width: 250px;
        border: 2px solid #5063F4;

        /* 125% */
        text-transform: capitalize;
        margin: 1rem
    }

    .view-button-outline:hover {
        color: #5063F4;
        text-decoration: none
    }

    .view-button:hover {
        color: white;
        cursor: pointer;
        text-decoration: none
    }

    .discover-login {
        margin: 1rem;
        color: #252525;
        text-align: center;

        /* Yield Exchange Text Styles/Body Text */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
    }

    .discover-login span {
        color: #5063F4;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
        text-decoration-line: underline;
    }
</style>

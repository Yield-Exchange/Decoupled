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
            Password Changed
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/password-changed.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action">Your password was changed on {{ getUTCEmailDateNow() }}.</p>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">If this was you, then you can safely ignore this email.If this wasn't, your account
                has been compromised. Here is what you can do:t.</p>
        </div>
        <div class="w-100 d-flex justify-content-start action-suggested align-items-center">
            <div class="d-flex align-items-center ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="34" viewBox="0 0 33 34" fill="none">
                    <path
                        d="M12.7875 27.871H5.5C3.9875 27.871 2.75 26.6335 2.75 25.121V8.62097C2.75 7.10847 3.9875 5.87097 5.5 5.87097H13.75L16.5 8.62097H27.5C29.0125 8.62097 30.25 9.85847 30.25 11.371V20.446C28.325 19.071 25.9875 18.246 23.375 18.246C18.5625 18.246 14.3 21.1335 12.5125 25.5335L12.1 26.496L12.5125 27.4585C12.65 27.596 12.65 27.7335 12.7875 27.871ZM31.625 26.496C30.3875 29.6585 27.0875 31.996 23.375 31.996C19.6625 31.996 16.3625 29.6585 15.125 26.496C16.3625 23.3335 19.6625 20.996 23.375 20.996C27.0875 20.996 30.3875 23.3335 31.625 26.496ZM26.8125 26.496C26.8125 24.571 25.3 23.0585 23.375 23.0585C21.45 23.0585 19.9375 24.571 19.9375 26.496C19.9375 28.421 21.45 29.9335 23.375 29.9335C25.3 29.9335 26.8125 28.421 26.8125 26.496ZM23.375 25.121C22.55 25.121 22 25.671 22 26.496C22 27.321 22.55 27.871 23.375 27.871C24.2 27.871 24.75 27.321 24.75 26.496C24.75 25.671 24.2 25.121 23.375 25.121Z"
                        fill="#5063F4" />
                </svg>
                <span>&nbsp Reset Your Password &nbsp</span>
            </div>
            <p class="m-0 p-0">and take control of your account.</p>
        </div>
        <div class="w-100 d-flex justify-content-start action-suggested align-items-center">
            <div class="d-flex align-items-center ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="34" viewBox="0 0 33 34" fill="none">
                    <path
                        d="M8.9375 24.4335L19.25 19.621L24.0625 9.30847L13.75 14.121L8.9375 24.4335ZM16.5 18.246C16.1104 18.246 15.7836 18.114 15.5196 17.85C15.2556 17.586 15.1241 17.2596 15.125 16.871C15.125 16.4814 15.257 16.1546 15.521 15.8906C15.785 15.6266 16.1113 15.4951 16.5 15.496C16.8896 15.496 17.2164 15.628 17.4804 15.892C17.7444 16.156 17.8759 16.4823 17.875 16.871C17.875 17.2606 17.743 17.5873 17.479 17.8513C17.215 18.1153 16.8887 18.2469 16.5 18.246ZM16.5 30.621C14.5979 30.621 12.8104 30.2598 11.1375 29.5375C9.46458 28.8151 8.00938 27.8357 6.77188 26.5991C5.53438 25.3616 4.55492 23.9064 3.8335 22.2335C3.11208 20.5606 2.75092 18.7731 2.75 16.871C2.75 14.9689 3.11117 13.1814 3.8335 11.5085C4.55583 9.83556 5.53529 8.38035 6.77188 7.14285C8.00938 5.90535 9.46458 4.92589 11.1375 4.20447C12.8104 3.48306 14.5979 3.12189 16.5 3.12097C18.4021 3.12097 20.1896 3.48214 21.8625 4.20447C23.5354 4.92681 24.9906 5.90626 26.2281 7.14285C27.4656 8.38035 28.4455 9.83556 29.1679 11.5085C29.8902 13.1814 30.2509 14.9689 30.25 16.871C30.25 18.7731 29.8888 20.5606 29.1665 22.2335C28.4442 23.9064 27.4647 25.3616 26.2281 26.5991C24.9906 27.8366 23.5354 28.8165 21.8625 29.5388C20.1896 30.2612 18.4021 30.6219 16.5 30.621Z"
                        fill="#5063F4" />
                </svg>
                <span>&nbsp Email us of use our chat: &nbsp</span>
            </div>
            <p class="m-0 p-0">to help you log back into the system.</p>
        </div>


        <div class="d-flex justify-content-center" style="
        display: flex;
        justify-content: center;
    ">>
            <a href='{{ url('login') }}?action=resetPassword' class="view-button">
                Reset Password
            </a>
        </div>



        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login">Discover a world of exclusive GIC's waiting for you <a
                    href="{{url('request-an-account')}}"><span>Sign Up </span></a> Or
                <a href="{{ url('login')}}"> <span> Log In</span></a>
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

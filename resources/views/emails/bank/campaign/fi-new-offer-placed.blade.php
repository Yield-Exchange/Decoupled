@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.20207 4.3562C3.34108 4.24767 3.52234 4.20923 3.69344 4.252L5.98436 4.82473C6.23932 4.88847 6.41818 5.11756 6.41818 5.38036V7.67129C6.41818 7.84765 6.33693 8.01418 6.19792 8.12272C6.05891 8.23125 5.87764 8.26969 5.70655 8.22692L3.41562 7.65419C3.16066 7.59045 2.9818 7.36136 2.9818 7.09856V4.80763C2.9818 4.63127 3.06306 4.46474 3.20207 4.3562ZM4.12726 5.54117V6.65138L5.27272 6.93775V5.82754L4.12726 5.54117Z"
                        fill="white" />
                    <path
                        d="M3.69344 9.40671C3.38657 9.32999 3.07561 9.51656 2.9989 9.82343C2.92218 10.1303 3.10875 10.4413 3.41562 10.518L5.70654 11.0907C6.01341 11.1674 6.32436 10.9808 6.40108 10.674C6.4778 10.3671 6.29122 10.0562 5.98436 9.97944L3.69344 9.40671Z"
                        fill="white" />
                    <path
                        d="M12.1284 6.95978C12.2051 7.26664 12.0185 7.5776 11.7117 7.65432L9.42074 8.22705C9.11387 8.30376 8.80292 8.11719 8.7262 7.81032C8.64948 7.50346 8.83606 7.1925 9.14292 7.11579L11.4338 6.54306C11.7407 6.46634 12.0517 6.65291 12.1284 6.95978Z"
                        fill="white" />
                    <path
                        d="M11.7117 5.36339C12.0185 5.28668 12.2051 4.97572 12.1284 4.66886C12.0517 4.36199 11.7407 4.17542 11.4338 4.25213L9.14292 4.82487C8.83606 4.90158 8.64948 5.21254 8.7262 5.5194C8.80292 5.82627 9.11387 6.01284 9.42074 5.93612L11.7117 5.36339Z"
                        fill="white" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M1.26361 3.48348C1.26361 2.33253 2.37311 1.50703 3.47552 1.83775L7.56364 3.06419L11.6518 1.83775C12.7542 1.50703 13.8637 2.33253 13.8637 3.48348V10.8281C13.8637 11.5869 13.366 12.2558 12.6392 12.4738L7.72822 13.9471C7.62087 13.9793 7.50642 13.9793 7.39907 13.9471L2.48808 12.4738C1.76132 12.2558 1.26361 11.5869 1.26361 10.8281V3.48348ZM6.99091 4.08826L3.14637 2.9349C2.7789 2.82466 2.40907 3.09983 2.40907 3.48348V10.8281C2.40907 11.081 2.57497 11.304 2.81723 11.3767L6.99091 12.6288V4.08826ZM8.13638 12.6288L12.3101 11.3767C12.5523 11.304 12.7182 11.081 12.7182 10.8281V3.48348C12.7182 3.09983 12.3484 2.82466 11.9809 2.9349L8.13638 4.08826V12.6288Z"
                        fill="white" />
                </svg>
                <p class="m-0 p-0">GIC Deposit</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            New Offer
        </div>
        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/fi-new-offer-placed.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">Exciting news! <span> 1st Choice Savings</span>
                has placed an offer</p>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action">Here's what you can do to next...</p>
        </div>
        <div class="w-100 d-flex justify-content-start action-suggested align-items-center">
            <div class="d-flex align-items-center ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="34" viewBox="0 0 33 34" fill="none">
                    <path
                        d="M15.4688 26.9819C15.4688 26.7084 15.5774 26.4461 15.7708 26.2527C15.9642 26.0593 16.2265 25.9507 16.5 25.9507H24.75C24.8412 25.9507 24.9286 25.9145 24.9931 25.85C25.0575 25.7855 25.0938 25.6981 25.0938 25.6069V9.10693C25.0938 9.01577 25.0575 8.92833 24.9931 8.86387C24.9286 8.7994 24.8412 8.76318 24.75 8.76318H16.5C16.2265 8.76318 15.9642 8.65453 15.7708 8.46114C15.5774 8.26774 15.4688 8.00544 15.4688 7.73193C15.4688 7.45843 15.5774 7.19613 15.7708 7.00273C15.9642 6.80933 16.2265 6.70068 16.5 6.70068H24.75C26.0782 6.70068 27.1562 7.77868 27.1562 9.10693V25.6069C27.1562 26.2451 26.9027 26.8571 26.4515 27.3084C26.0002 27.7597 25.3882 28.0132 24.75 28.0132H16.5C16.2265 28.0132 15.9642 27.9045 15.7708 27.7111C15.5774 27.5177 15.4688 27.2554 15.4688 26.9819Z"
                        fill="#5063F4" />
                    <path
                        d="M4.8125 18.8901C4.8125 19.2548 4.95737 19.6045 5.21523 19.8624C5.47309 20.1202 5.82283 20.2651 6.1875 20.2651H12.8645C12.8961 20.7546 12.936 21.2413 12.9827 21.7308L13.024 22.1502C13.0394 22.3083 13.0926 22.4603 13.1793 22.5934C13.2659 22.7264 13.3834 22.8367 13.5217 22.9147C13.66 22.9927 13.8151 23.0361 13.9738 23.0414C14.1325 23.0467 14.2902 23.0136 14.4334 22.945C16.9464 21.7402 19.2214 20.0917 21.1489 18.0788L21.1901 18.0362C21.3671 17.8516 21.4659 17.6058 21.4659 17.3501C21.4659 17.0944 21.3671 16.8485 21.1901 16.664L21.1489 16.6213C19.2214 14.6084 16.9464 12.9599 14.4334 11.7552C14.2902 11.6866 14.1325 11.6535 13.9738 11.6587C13.8151 11.664 13.66 11.7075 13.5217 11.7855C13.3834 11.8635 13.2659 11.9737 13.1793 12.1068C13.0926 12.2399 13.0394 12.3919 13.024 12.55L12.9827 12.9693C12.936 13.4575 12.8961 13.9456 12.8645 14.4351H6.1875C5.82283 14.4351 5.47309 14.5799 5.21523 14.8378C4.95737 15.0957 4.8125 15.4454 4.8125 15.8101V18.8901Z"
                        fill="#5063F4" />
                </svg>
                <span>&nbsp Sign In &nbsp
            </div>
            <p class="m-0 p-0">and review the offer through the <b style="font-weight: 700"> 'Review offers' </b> page.</p>
        </div>
        <div class="w-100 d-flex justify-content-start action-suggested align-items-center">
            <div class="d-flex align-items-center ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="34" viewBox="0 0 33 34" fill="none">
                    <path
                        d="M9.90001 5.80713C8.58719 5.80713 7.32814 6.32865 6.39983 7.25695C5.47153 8.18526 4.95001 9.44431 4.95001 10.7571V20.6571C4.95001 21.9699 5.47153 23.229 6.39983 24.1573C7.32814 25.0856 8.58719 25.6071 9.90001 25.6071H19.8C21.1128 25.6071 22.3719 25.0856 23.3002 24.1573C24.2285 23.229 24.75 21.9699 24.75 20.6571V10.7571C24.75 9.44431 24.2285 8.18526 23.3002 7.25695C22.3719 6.32865 21.1128 5.80713 19.8 5.80713H9.90001ZM20.3445 11.7867C20.5087 11.931 20.6089 12.1346 20.6231 12.3527C20.6373 12.5707 20.5644 12.7856 20.4204 12.95L14.6454 19.55C14.5711 19.6351 14.4801 19.7041 14.378 19.7526C14.276 19.8011 14.165 19.8281 14.0521 19.832C13.9392 19.8359 13.8267 19.8165 13.7215 19.7751C13.6164 19.7336 13.5209 19.6711 13.4409 19.5912L10.1409 16.2912C10.0642 16.2145 10.0034 16.1235 9.96185 16.0232C9.92034 15.923 9.89897 15.8156 9.89897 15.7071C9.89897 15.5987 9.92034 15.4912 9.96185 15.391C10.0034 15.2908 10.0642 15.1997 10.1409 15.123C10.2176 15.0463 10.3087 14.9855 10.4089 14.944C10.5091 14.9025 10.6165 14.8811 10.725 14.8811C10.8335 14.8811 10.9409 14.9025 11.0411 14.944C11.1413 14.9855 11.2324 15.0463 11.3091 15.123L13.9854 17.7993L19.1796 11.8643C19.3237 11.6999 19.5271 11.5994 19.7452 11.5848C19.9633 11.5703 20.1799 11.6429 20.3445 11.7867ZM9.49246 27.2571C10.4 28.2702 11.715 28.9071 13.1819 28.9071H20.6069C22.5761 28.9071 24.4647 28.1249 25.8571 26.7324C27.2496 25.3399 28.0319 23.4514 28.0319 21.4821V14.0571C28.0319 12.5985 27.3999 11.2851 26.3967 10.3809V19.0071C26.3967 19.1721 26.3901 19.3371 26.3802 19.5005V21.4805C26.3802 22.2389 26.2308 22.9898 25.9406 23.6905C25.6504 24.3911 25.225 25.0278 24.6888 25.564C24.1525 26.1003 23.5159 26.5257 22.8152 26.8159C22.1146 27.1061 21.3636 27.2555 20.6052 27.2555H18.2391L18.1467 27.2571H9.49246Z"
                        fill="#5063F4" />
                </svg>
                <span>&nbsp Select an offer &nbsp</span>
            </div>
            <p class="m-0 p-0">after 2024-01-10 06:57 pm Central</p>
        </div>

        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                View Offer
            </a>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login">Discover a world of exclusive GIC’s waiting for you <a
                    href="https://app.yieldexchange.ca/request-an-account"><span>Sign Up </span></a> Or
                <a href="https://app.yieldexchange.ca/login"> <span> Log In</span></a>
            </p>
        </div>
    </div>
@endsection

<style>
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
        background: #44E0AA;
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

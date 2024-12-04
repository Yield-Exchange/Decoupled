@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="15" viewBox="0 0 14 15" fill="none">
                    <g clip-path="url(#clip0_3552_41667)">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.70213 3.87049C2.84114 3.76195 3.0224 3.72351 3.1935 3.76629L5.48442 4.33902C5.73938 4.40276 5.91825 4.63184 5.91825 4.89465V7.18557C5.91825 7.36193 5.83699 7.52846 5.69798 7.637C5.55897 7.74553 5.37771 7.78397 5.20661 7.7412L2.91569 7.16847C2.66073 7.10473 2.48186 6.87565 2.48186 6.61284V4.32192C2.48186 4.14555 2.56312 3.97902 2.70213 3.87049ZM3.62732 5.05546V6.16566L4.77278 6.45203V5.34182L3.62732 5.05546Z"
                            fill="white" />
                        <path
                            d="M3.1935 8.92099C2.88663 8.84427 2.57568 9.03085 2.49896 9.33771C2.42224 9.64458 2.60882 9.95553 2.91568 10.0323L5.2066 10.605C5.51347 10.6817 5.82442 10.4951 5.90114 10.1883C5.97786 9.88139 5.79128 9.57044 5.48442 9.49372L3.1935 8.92099Z"
                            fill="white" />
                        <path
                            d="M11.6284 6.47406C11.7052 6.78093 11.5186 7.09188 11.2117 7.1686L8.9208 7.74133C8.61393 7.81805 8.30298 7.63147 8.22626 7.32461C8.14955 7.01774 8.33612 6.70678 8.64298 6.63007L10.9339 6.05734C11.2408 5.98062 11.5517 6.16719 11.6284 6.47406Z"
                            fill="white" />
                        <path
                            d="M11.2117 4.87768C11.5186 4.80096 11.7052 4.49001 11.6284 4.18314C11.5517 3.87627 11.2408 3.6897 10.9339 3.76642L8.64298 4.33915C8.33612 4.41586 8.14955 4.72682 8.22626 5.03368C8.30298 5.34055 8.61393 5.52712 8.9208 5.45041L11.2117 4.87768Z"
                            fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.763672 2.99776C0.763672 1.84681 1.87317 1.02131 2.97558 1.35203L7.06371 2.57847L11.1518 1.35203C12.2542 1.02131 13.3637 1.84681 13.3637 2.99776V10.3424C13.3637 11.1012 12.866 11.7701 12.1393 11.9881L7.22828 13.4614C7.12093 13.4936 7.00648 13.4936 6.89913 13.4614L1.98814 11.9881C1.26138 11.7701 0.763672 11.1012 0.763672 10.3424V2.99776ZM6.49098 3.60255L2.64644 2.44918C2.27897 2.33894 1.90913 2.61411 1.90913 2.99776V10.3424C1.90913 10.5953 2.07503 10.8183 2.31729 10.891L6.49098 12.1431V3.60255ZM7.63644 12.1431L11.8101 10.891C12.0524 10.8183 12.2183 10.5953 12.2183 10.3424V2.99776C12.2183 2.61411 11.8484 2.33894 11.481 2.44918L7.63644 3.60255V12.1431Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_3552_41667">
                            <rect width="13.7455" height="13.7455" fill="white"
                                transform="translate(0.190918 0.312744)" />
                        </clipPath>
                    </defs>
                </svg>
                <p class="m-0 p-0">Investment Guide</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Discover Our Top GIC Listings
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/discover-gics.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">These products exclusively selected for you offer attractive terms and competitive
                rates to help you make the most of your investments.</p>
        </div>

        @foreach ($gics as $gic_item)
            <div class="w-100 d-flex justify-content-start align-items-center discover-gic my-2">
                <div class="ml-4 gic-item p-0 d-flex align-items-center"> <span> {{ $gic_item->rate }}% &nbsp</span>
                    @if (hasLockoutPeriod($gic_item->description))
                        {{ $gic_item->lockout_period . ' Days ' . $gic_item->description }}
                    @else
                        {{ $gic_item->description }}
                    @endif GIC from &nbsp
                    {{ $gic_item->fi_name }}
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                View All Listings
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

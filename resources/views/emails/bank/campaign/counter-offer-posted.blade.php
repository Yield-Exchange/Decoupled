@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="15" viewBox="0 0 14 15" fill="none">
                    <g clip-path="url(#clip0_3591_49706)">
                        <path
                            d="M5.84437 1.50273L5.65638 1.38483C5.28846 1.15925 4.82923 1.02819 4.43446 1.15119C4.01014 1.28332 3.67176 1.61015 3.49452 2.10107L3.24208 1.97109C2.80702 1.74711 2.57928 1.74362 2.39049 1.82849C2.23983 1.89589 2.08273 2.0581 1.90467 2.29497L4.11756 4.00593C4.464 4.11872 4.97157 4.11604 5.47914 3.98444C5.98939 3.85285 6.49696 3.59504 6.86756 3.2486L7.70277 1.18342C6.96693 0.601997 6.29554 0.938765 5.84437 1.50273ZM7.2704 3.55744C6.81385 4.01936 6.13441 4.33088 5.60536 4.47053C5.42005 4.51887 5.23475 4.55378 5.05482 4.57258C4.95545 4.7364 4.8722 4.9029 4.80775 5.0694C4.93666 5.72468 5.17836 6.26179 5.5409 6.70222L5.15418 7.02448C4.93128 6.75593 4.73255 6.46589 4.58216 6.14362C4.55799 6.76667 4.69764 7.4112 4.92591 8.08259L4.45057 8.24372C4.03163 7.01911 3.88929 5.76496 4.47743 4.58332C4.2787 4.56721 4.09071 4.52961 3.91615 4.46784L3.90809 4.46516C3.79261 4.57526 3.68251 4.69074 3.57777 4.81159C2.72994 5.7945 2.26293 7.10236 2.50731 8.12018C2.6381 8.66535 2.87254 9.18098 3.20985 9.62141C3.70399 9.61066 4.17128 9.62141 4.59559 9.78254C4.79701 9.76105 5.00379 9.74763 5.21864 9.74763C5.98402 9.74763 6.67689 9.88996 7.20057 10.137C7.40467 10.2337 7.58998 10.3492 7.73768 10.4808C7.73768 10.3384 7.75917 10.1854 7.80213 10.0726C7.56312 9.84699 7.41273 9.57307 7.41273 9.26154C7.41273 8.82917 7.70008 8.47199 8.11634 8.21418C8.11634 8.09064 8.14051 7.97248 8.18348 7.85969C7.91224 7.62604 7.73768 7.33601 7.73768 6.99763C7.73768 6.78547 7.80751 6.59211 7.92567 6.41755C7.80751 6.24567 7.73768 6.05231 7.73768 5.84016C7.73768 5.73542 7.75379 5.63874 7.78334 5.54475C7.52284 5.31379 7.35902 5.02912 7.35902 4.70148C7.35902 4.37653 7.52015 4.09455 7.77796 3.86628C7.61952 3.74274 7.45033 3.63801 7.2704 3.55744ZM10.2621 3.61921C9.56385 3.61921 8.93275 3.76691 8.50038 3.98444C8.06532 4.20197 7.86122 4.47053 7.86122 4.70148C7.86122 4.93513 8.06532 5.20368 8.50038 5.42121C8.93275 5.63874 9.56385 5.78645 10.2621 5.78645C10.5656 5.78645 10.8529 5.7569 11.1188 5.70856V5.25202C11.4921 5.19563 11.8009 5.09357 11.9996 4.96198V5.43464C12.3353 5.33796 12.6576 4.98078 12.6657 4.70148C12.6657 4.47053 12.4616 4.20197 12.0265 3.98444C11.5914 3.76691 10.963 3.61921 10.2621 3.61921ZM12.8536 5.43732C12.765 5.53132 12.6603 5.61994 12.5421 5.70051L12.5475 6.47932C12.8859 6.2779 13.0443 6.04426 13.0443 5.84016C13.0443 5.71125 12.9826 5.5716 12.8536 5.43732ZM8.23988 5.8509C8.24793 6.08186 8.45204 6.34235 8.87904 6.5572C9.3141 6.77741 9.94251 6.92243 10.6434 6.92243C11.0812 6.92243 11.4921 6.86335 11.8439 6.76667L11.8627 6.0362C11.4008 6.19733 10.8529 6.28596 10.2621 6.28596C9.49671 6.28596 8.80116 6.13288 8.27479 5.8697C8.26136 5.86433 8.25062 5.85627 8.23988 5.8509ZM8.29359 6.78547C8.25599 6.85798 8.23988 6.93049 8.23988 6.99763C8.23988 7.23127 8.44398 7.49982 8.87904 7.71735C9.3141 7.93488 9.94251 8.0799 10.6434 8.0799C10.9979 8.0799 11.3336 8.04499 11.6371 7.97785V7.33332C11.3256 7.3924 10.9926 7.42194 10.6434 7.42194C9.87538 7.42194 9.17982 7.27155 8.65345 7.00568C8.52186 6.94123 8.40101 6.86604 8.29359 6.78547ZM12.9906 6.78547C12.8268 6.90095 12.6845 6.98689 12.518 7.05939V7.65827C12.8751 7.45148 13.0443 7.20978 13.0443 6.99763C13.0443 6.93049 13.0282 6.85798 12.9906 6.78547ZM13.192 7.77375C13.1383 7.82746 13.0792 7.87849 13.0148 7.92951V8.79963C13.2914 8.61164 13.4257 8.40485 13.4257 8.21686C13.4257 8.07453 13.3478 7.92145 13.192 7.77375ZM8.62391 8.14973C8.62123 8.1739 8.61854 8.19538 8.61854 8.21686C8.61854 8.45051 8.82264 8.71638 9.2577 8.93391C9.69276 9.15412 10.3239 9.29914 11.0221 9.29914C11.4223 9.29914 11.8036 9.2508 12.1339 9.17023V8.36726C11.6962 8.5069 11.1886 8.5821 10.6434 8.5821C9.87538 8.5821 9.17982 8.42902 8.65345 8.16584C8.64271 8.16047 8.63465 8.1551 8.62391 8.14973ZM8.25868 8.72443C8.02503 8.89899 7.91493 9.08967 7.91493 9.26154C7.91493 9.49518 8.11903 9.76105 8.55409 9.98127C8.98915 10.1988 9.61756 10.3438 10.3185 10.3438C10.5736 10.3438 10.8207 10.325 11.0516 10.2901V9.80134H11.0221C10.254 9.80134 9.55848 9.64826 9.03211 9.38239C8.69642 9.21589 8.41712 8.99299 8.25868 8.72443ZM12.6227 9.54621C12.4105 9.62141 12.1796 9.68317 11.9325 9.72346V10.0484C11.9835 10.0269 12.0346 10.0054 12.0829 9.98127C12.3461 9.84699 12.5233 9.6966 12.6227 9.54621ZM12.8698 10.035C12.7677 10.137 12.6495 10.2283 12.518 10.3116V11.0797C12.8751 10.8756 13.0443 10.6339 13.0443 10.4217C13.0443 10.2982 12.9879 10.1639 12.8698 10.035ZM3.50526 10.1102C3.23133 10.1129 2.93592 10.1478 2.62977 10.2203C2.0172 10.3626 1.49056 10.6231 1.14681 10.9105C0.803062 11.1951 0.668785 11.4852 0.709068 11.692C0.749351 11.8987 0.961509 12.0894 1.37508 12.1995C1.78731 12.307 2.3639 12.3123 2.97621 12.17C3.22328 12.1109 3.45423 12.0357 3.66639 11.9471V11.3912C4.05848 11.2166 4.35121 11.0071 4.4828 10.803V11.4583C4.80775 11.179 4.93397 10.8997 4.89637 10.6956C4.85878 10.4888 4.64393 10.2982 4.23036 10.1907C4.02357 10.137 3.7765 10.1075 3.50526 10.1102ZM5.24012 10.2498C5.31263 10.3546 5.36366 10.4727 5.39051 10.6043C5.42005 10.7654 5.40663 10.9212 5.35829 11.0743C5.62416 11.1441 5.86585 11.2381 6.07533 11.3563C6.19349 11.4207 6.3036 11.4986 6.40028 11.5792C6.64198 11.5228 6.84608 11.4422 6.9911 11.3455V11.8987C7.42347 11.6946 7.6222 11.4449 7.6222 11.2462C7.6222 11.0447 7.42347 10.795 6.98841 10.5909C6.55604 10.3895 5.93299 10.2525 5.24012 10.2498ZM8.24256 10.3841C8.23988 10.3948 8.23988 10.4083 8.23988 10.4217C8.23988 10.6553 8.44398 10.9212 8.87904 11.1414C9.3141 11.359 9.94251 11.504 10.6434 11.504C10.9979 11.504 11.3336 11.4664 11.6371 11.4019V10.6795C11.2396 10.7869 10.7911 10.846 10.3185 10.846C9.55043 10.846 8.85487 10.6929 8.3285 10.4298C8.29896 10.4136 8.26942 10.3975 8.24256 10.3841ZM8.31776 11.3858C8.26405 11.4744 8.23988 11.5604 8.23988 11.6436C8.23988 11.8773 8.44398 12.1431 8.87904 12.3633C9.3141 12.5809 9.94251 12.7259 10.6434 12.7259C10.9979 12.7259 11.3336 12.6883 11.6371 12.6238V11.9149C11.3256 11.9739 10.9926 12.0062 10.6434 12.0062C9.87538 12.0062 9.17982 11.8531 8.65345 11.5872C8.5326 11.5281 8.41981 11.461 8.31776 11.3858ZM12.9664 11.3858C12.8107 11.4905 12.6737 11.5738 12.518 11.6409V12.3016C12.8751 12.0975 13.0443 11.8558 13.0443 11.6436C13.0443 11.5604 13.0202 11.4744 12.9664 11.3858ZM5.10853 11.5308C5.01454 11.649 4.90443 11.7618 4.78089 11.8638C4.36195 12.2156 3.77113 12.5003 3.089 12.6588C2.84569 12.7152 2.60399 12.7501 2.37196 12.7689C2.4579 12.9112 2.60909 13.0508 2.83038 13.1744C3.19642 13.3812 3.73353 13.5208 4.32972 13.5208C4.60096 13.5208 4.85877 13.4913 5.0951 13.4403V12.8817C5.46571 12.8253 5.77723 12.7232 5.97596 12.5916V13.0858C6.24452 12.8978 6.37342 12.6856 6.37342 12.4842C6.37342 12.2506 6.19886 12.0008 5.83094 11.794C5.63221 11.6839 5.38514 11.5926 5.10853 11.5308Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_3591_49706">
                            <rect width="13.75" height="13.75" fill="white" transform="translate(0.188477 0.310547)" />
                        </clipPath>
                    </defs>
                </svg>
                <p class="m-0 p-0">Counter Offer</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Counter Offer Request Posted
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/counter-offer-posted.svg') }}" class="cover-image" alt="">
        </div>

        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">You have posted a new offer to BC Municipality</p>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center discover-gic my-2">
            <div class="ml-4 gic-item p-0 d-flex align-items-center"> <span> New Rate &nbsp</span> 5.75% </div>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center discover-gic my-2">
            <div class="ml-4 gic-item p-0 d-flex align-items-center"> <span> Offer Expiry &nbsp</span> 2023-10-26 </div>
        </div>


        <div class="w-100 d-flex justify-content-center">
            <p class="timezone">*Counter offer expiry timezone America/Winnipeg</p>
        </div>

        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                View Offers
            </a>
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

        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .timezone {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
        text-align: center;

        /* Yield Exchange Text Styles/Body Text */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
        margin: 10px;
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

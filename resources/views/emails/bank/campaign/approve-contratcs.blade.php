@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <g clip-path="url(#clip0_5238_64547)">
                        <path
                            d="M7.5636 4.17773C7.5636 4.74754 7.33725 5.294 6.93434 5.69691C6.53143 6.09982 5.98496 6.32617 5.41516 6.32617C4.84536 6.32617 4.2989 6.09982 3.89599 5.69691C3.49308 5.294 3.26672 4.74754 3.26672 4.17773C3.26672 3.60793 3.49308 3.06147 3.89599 2.65856C4.2989 2.25565 4.84536 2.0293 5.41516 2.0293C5.98496 2.0293 6.53143 2.25565 6.93434 2.65856C7.33725 3.06147 7.5636 3.60793 7.5636 4.17773ZM10.5714 5.4668C10.1122 5.46645 9.65535 5.53304 9.21532 5.66445C9.01744 5.41056 8.89484 5.1062 8.86149 4.78603C8.82813 4.46587 8.88535 4.14277 9.02663 3.85353C9.16792 3.5643 9.38759 3.32055 9.66062 3.15006C9.93366 2.97956 10.2491 2.88916 10.571 2.88916C10.8929 2.88916 11.2083 2.97956 11.4813 3.15006C11.7544 3.32055 11.974 3.5643 12.1153 3.85353C12.2566 4.14277 12.3138 4.46587 12.2805 4.78603C12.2471 5.1062 12.1245 5.41056 11.9266 5.66445C11.4869 5.53312 11.0304 5.46654 10.5714 5.4668ZM3.26672 7.18555H6.92508C6.22529 8.03151 5.84318 9.09547 5.84485 10.1934C5.84485 10.4804 5.87063 10.7605 5.91961 11.033C5.7518 11.0463 5.58351 11.0529 5.41516 11.0527C1.97766 11.0527 1.97766 8.53906 1.97766 8.53906V8.47461C1.97766 8.13273 2.11347 7.80485 2.35522 7.5631C2.59696 7.32136 2.92484 7.18555 3.26672 7.18555ZM14.4386 10.1934C14.4386 11.219 14.0312 12.2026 13.3059 12.9279C12.5807 13.6531 11.5971 14.0605 10.5714 14.0605C9.54577 14.0605 8.56214 13.6531 7.8369 12.9279C7.11166 12.2026 6.70422 11.219 6.70422 10.1934C6.70422 9.16772 7.11166 8.18408 7.8369 7.45885C8.56214 6.73361 9.54577 6.32617 10.5714 6.32617C11.5971 6.32617 12.5807 6.73361 13.3059 7.45885C14.0312 8.18408 14.4386 9.16772 14.4386 10.1934ZM11.0011 8.47461C11.0011 8.36065 10.9558 8.25136 10.8752 8.17077C10.7947 8.09019 10.6854 8.04492 10.5714 8.04492C10.4575 8.04492 10.3482 8.09019 10.2676 8.17077C10.187 8.25136 10.1417 8.36065 10.1417 8.47461V9.76367H8.85266C8.7387 9.76367 8.62941 9.80894 8.54883 9.88952C8.46824 9.97011 8.42297 10.0794 8.42297 10.1934C8.42297 10.3073 8.46824 10.4166 8.54883 10.4972C8.62941 10.5778 8.7387 10.623 8.85266 10.623H10.1417V11.9121C10.1417 12.0261 10.187 12.1354 10.2676 12.2159C10.3482 12.2965 10.4575 12.3418 10.5714 12.3418C10.6854 12.3418 10.7947 12.2965 10.8752 12.2159C10.9558 12.1354 11.0011 12.0261 11.0011 11.9121V10.623H12.2902C12.4041 10.623 12.5134 10.5778 12.594 10.4972C12.6746 10.4166 12.7198 10.3073 12.7198 10.1934C12.7198 10.0794 12.6746 9.97011 12.594 9.88952C12.5134 9.80894 12.4041 9.76367 12.2902 9.76367H11.0011V8.47461Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_5238_64547">
                            <rect width="13.75" height="13.75" fill="white" transform="translate(0.688599 0.310547)" />
                        </clipPath>
                    </defs>
                </svg>
                <p class="m-0 p-0">Campaign Status</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Pending Deposits
        </div>
        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/approve-contracts.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">Please review the following deposits for approval </p>
        </div>
        {{-- <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action">Here's what you can do to next...</p>
        </div> --}}
        <div class="w-100  ">
            <table class="custom-table w-100 table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Deposit ID</th>
                        <th>Offered Amount</th>
                        <th>Date of Deposit</th>
                        <th>Depositor</th>
                        <th>Institution</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>12343</td>
                        <td>CAD 2,000,000</td>
                        <td>2022-10-12</td>
                        <td>Ferni Muni</td>
                        <td>Synergy</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                Approve Contracts
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

    .custom-table {
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        line-height: normal;
        /* background: #F4F5F6; */
        padding: 10px;
        margin: 12px
    }

    .custom-table thead {
        color: #5063F4;
        font-weight: 700;
        padding: 1rem
    }

    .custom-table thead {
        background-color: #EFF2FE;
    }

    .custom-table thead th {
        padding: 0.6rem
    }

    .custom-table tbody {
        margin-top: 30px;
        background-color: #F4F5F6;
    }

    .action-suggested p {
        color: #252525;
        font-weight: 300;
    }

    tr td,
    tr th {
        border-top: none !important;
        border-bottom: 3px solid white !important;
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

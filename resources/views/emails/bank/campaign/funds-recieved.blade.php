@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.20213 3.87037C3.34114 3.76183 3.5224 3.72339 3.6935 3.76616L5.98442 4.33889C6.23938 4.40263 6.41825 4.63172 6.41825 4.89452V7.18545C6.41825 7.36181 6.33699 7.52834 6.19798 7.63688C6.05897 7.74541 5.87771 7.78385 5.70661 7.74108L3.41569 7.16835C3.16073 7.10461 2.98186 6.87552 2.98186 6.61272V4.32179C2.98186 4.14543 3.06312 3.9789 3.20213 3.87037ZM4.12732 5.05533V6.16554L5.27278 6.45191V5.3417L4.12732 5.05533Z"
                        fill="white" />
                    <path
                        d="M3.6935 8.92087C3.38663 8.84415 3.07568 9.03072 2.99896 9.33759C2.92224 9.64446 3.10882 9.95541 3.41568 10.0321L5.7066 10.6049C6.01347 10.6816 6.32442 10.495 6.40114 10.1881C6.47786 9.88127 6.29128 9.57031 5.98442 9.4936L3.6935 8.92087Z"
                        fill="white" />
                    <path
                        d="M12.1284 6.47394C12.2052 6.78081 12.0186 7.09176 11.7117 7.16848L9.4208 7.74121C9.11393 7.81792 8.80298 7.63135 8.72626 7.32448C8.64955 7.01762 8.83612 6.70666 9.14298 6.62995L11.4339 6.05722C11.7408 5.9805 12.0517 6.16707 12.1284 6.47394Z"
                        fill="white" />
                    <path
                        d="M11.7117 4.87755C12.0186 4.80084 12.2052 4.48988 12.1284 4.18302C12.0517 3.87615 11.7408 3.68958 11.4339 3.76629L9.14298 4.33903C8.83612 4.41574 8.64955 4.7267 8.72626 5.03356C8.80298 5.34043 9.11393 5.527 9.4208 5.45028L11.7117 4.87755Z"
                        fill="white" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M1.26367 2.99764C1.26367 1.84669 2.37317 1.02119 3.47558 1.35191L7.56371 2.57835L11.6518 1.35191C12.7542 1.02119 13.8637 1.84669 13.8637 2.99764V10.3423C13.8637 11.101 13.366 11.77 12.6393 11.988L7.72828 13.4613C7.62093 13.4935 7.50648 13.4935 7.39913 13.4613L2.48814 11.988C1.76138 11.77 1.26367 11.101 1.26367 10.3423V2.99764ZM6.99098 3.60242L3.14644 2.44906C2.77897 2.33882 2.40913 2.61399 2.40913 2.99764V10.3423C2.40913 10.5952 2.57503 10.8182 2.81729 10.8908L6.99098 12.1429V3.60242ZM8.13644 12.1429L12.3101 10.8908C12.5524 10.8182 12.7183 10.5952 12.7183 10.3423V2.99764C12.7183 2.61399 12.3484 2.33882 11.981 2.44906L8.13644 3.60242V12.1429Z"
                        fill="white" />
                </svg>
                <p class="m-0 p-0">GIC Deposit</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Funds Received
        </div>

        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/funds-recieved.svg') }}" class="cover-image" alt="">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message">The funds for the following deposit have been provided successfully and therefore
                marked as
                complete</p>
        </div>

        <div class="w-100  ">
            <table class="custom-table w-100 table table-hover">
                <thead>
                    <tr>
                        <th>Deposit ID</th>
                        <th>Depositor</th>
                        <th>Deposited Amount</th>
                        <th>Date of Deposit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>7865467</td>
                        <td>Mike Kariuki</td>
                        <td>CAD 9,000,000</td>
                        <td>2023-10-16</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                View All Deposits
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
        color: #252525;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
    }

    tr td,
    tr th {
        border-top: none !important;
        border-bottom: 3px solid white !important;
    }
</style>

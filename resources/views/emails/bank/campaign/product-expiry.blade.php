@extends('emails.new-master')
@section('page-content')
    <div>
        <div class="w-100 d-flex justify-content-center my-4">
            <div class="window-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <g clip-path="url(#clip0_3566_39456)">
                        <path
                            d="M6.34437 1.50249L6.15638 1.38459C5.78846 1.159 5.32923 1.02795 4.93446 1.15095C4.51014 1.28308 4.17176 1.60991 3.99452 2.10083L3.74208 1.97085C3.30702 1.74687 3.07928 1.74338 2.89049 1.82824C2.73983 1.89565 2.58273 2.05786 2.40467 2.29472L4.61756 4.00568C4.964 4.11848 5.47157 4.11579 5.97914 3.9842C6.48939 3.85261 6.99696 3.59479 7.36756 3.24836L8.20277 1.18317C7.46693 0.601753 6.79554 0.938521 6.34437 1.50249ZM7.7704 3.5572C7.31385 4.01911 6.63441 4.33063 6.10536 4.47028C5.92005 4.51862 5.73475 4.55354 5.55482 4.57233C5.45545 4.73615 5.3722 4.90266 5.30775 5.06916C5.43666 5.72443 5.67836 6.26154 6.0409 6.70197L5.65418 7.02424C5.43128 6.75568 5.23255 6.46564 5.08216 6.14338C5.05799 6.76643 5.19764 7.41096 5.42591 8.08234L4.95057 8.24348C4.53163 7.01887 4.38929 5.76472 4.97743 4.58308C4.7787 4.56696 4.59071 4.52937 4.41615 4.4676L4.40809 4.46491C4.29261 4.57502 4.18251 4.6905 4.07777 4.81135C3.22994 5.79426 2.76293 7.10212 3.00731 8.11994C3.1381 8.66511 3.37254 9.18073 3.70985 9.62116C4.20399 9.61042 4.67128 9.62116 5.09559 9.78229C5.29701 9.76081 5.50379 9.74738 5.71864 9.74738C6.48402 9.74738 7.17689 9.88972 7.70057 10.1368C7.90467 10.2335 8.08998 10.3489 8.23768 10.4805C8.23768 10.3382 8.25917 10.1851 8.30213 10.0723C8.06312 9.84675 7.91273 9.57282 7.91273 9.2613C7.91273 8.82893 8.20008 8.47175 8.61634 8.21393C8.61634 8.0904 8.64051 7.97224 8.68348 7.85944C8.41224 7.6258 8.23768 7.33576 8.23768 6.99738C8.23768 6.78522 8.30751 6.59187 8.42567 6.4173C8.30751 6.24543 8.23768 6.05207 8.23768 5.83991C8.23768 5.73518 8.25379 5.6385 8.28334 5.5445C8.02284 5.31354 7.85902 5.02888 7.85902 4.70124C7.85902 4.37629 8.02015 4.09431 8.27796 3.86603C8.11952 3.7425 7.95033 3.63776 7.7704 3.5572ZM10.7621 3.61896C10.0639 3.61896 9.43275 3.76667 9.00038 3.9842C8.56532 4.20173 8.36122 4.47028 8.36122 4.70124C8.36122 4.93488 8.56532 5.20344 9.00038 5.42097C9.43275 5.6385 10.0639 5.7862 10.7621 5.7862C11.0656 5.7862 11.3529 5.75666 11.6188 5.70832V5.25178C11.9921 5.19538 12.3009 5.09333 12.4996 4.96174V5.43439C12.8353 5.33772 13.1576 4.98054 13.1657 4.70124C13.1657 4.47028 12.9616 4.20173 12.5265 3.9842C12.0914 3.76667 11.463 3.61896 10.7621 3.61896ZM13.3536 5.43708C13.265 5.53107 13.1603 5.6197 13.0421 5.70026L13.0475 6.47907C13.3859 6.27766 13.5443 6.04401 13.5443 5.83991C13.5443 5.71101 13.4826 5.57136 13.3536 5.43708ZM8.73988 5.85065C8.74793 6.08161 8.95204 6.34211 9.37904 6.55695C9.8141 6.77717 10.4425 6.92219 11.1434 6.92219C11.5812 6.92219 11.9921 6.86311 12.3439 6.76643L12.3627 6.03596C11.9008 6.19709 11.3529 6.28571 10.7621 6.28571C9.99671 6.28571 9.30116 6.13264 8.77479 5.86945C8.76136 5.86408 8.75062 5.85603 8.73988 5.85065ZM8.79359 6.78522C8.75599 6.85773 8.73988 6.93024 8.73988 6.99738C8.73988 7.23103 8.94398 7.49958 9.37904 7.71711C9.8141 7.93464 10.4425 8.07966 11.1434 8.07966C11.4979 8.07966 11.8336 8.04475 12.1371 7.97761V7.33308C11.8256 7.39216 11.4926 7.4217 11.1434 7.4217C10.3754 7.4217 9.67982 7.27131 9.15345 7.00544C9.02186 6.94099 8.90101 6.86579 8.79359 6.78522ZM13.4906 6.78522C13.3268 6.9007 13.1845 6.98664 13.018 7.05915V7.65803C13.3751 7.45124 13.5443 7.20954 13.5443 6.99738C13.5443 6.93024 13.5282 6.85773 13.4906 6.78522ZM13.692 7.77351C13.6383 7.82722 13.5792 7.87824 13.5148 7.92927V8.79938C13.7914 8.6114 13.9257 8.40461 13.9257 8.21662C13.9257 8.07429 13.8478 7.92121 13.692 7.77351ZM9.12391 8.14948C9.12123 8.17365 9.11854 8.19514 9.11854 8.21662C9.11854 8.45026 9.32264 8.71613 9.7577 8.93366C10.1928 9.15388 10.8239 9.2989 11.5221 9.2989C11.9223 9.2989 12.3036 9.25056 12.6339 9.16999V8.36701C12.1962 8.50666 11.6886 8.58185 11.1434 8.58185C10.3754 8.58185 9.67982 8.42878 9.15345 8.1656C9.14271 8.16022 9.13465 8.15485 9.12391 8.14948ZM8.75868 8.72419C8.52503 8.89875 8.41493 9.08942 8.41493 9.2613C8.41493 9.49494 8.61903 9.76081 9.05409 9.98102C9.48915 10.1986 10.1176 10.3436 10.8185 10.3436C11.0736 10.3436 11.3207 10.3248 11.5516 10.2899V9.80109H11.5221C10.754 9.80109 10.0585 9.64802 9.53211 9.38215C9.19642 9.21564 8.91712 8.99274 8.75868 8.72419ZM13.1227 9.54597C12.9105 9.62116 12.6796 9.68293 12.4325 9.72321V10.0482C12.4835 10.0267 12.5346 10.0052 12.5829 9.98102C12.8461 9.84675 13.0233 9.69636 13.1227 9.54597ZM13.3698 10.0347C13.2677 10.1368 13.1495 10.2281 13.018 10.3113V11.0794C13.3751 10.8753 13.5443 10.6336 13.5443 10.4215C13.5443 10.2979 13.4879 10.1636 13.3698 10.0347ZM4.00526 10.1099C3.73133 10.1126 3.43592 10.1475 3.12977 10.22C2.5172 10.3624 1.99056 10.6229 1.64681 10.9102C1.30306 11.1949 1.16878 11.4849 1.20907 11.6917C1.24935 11.8985 1.46151 12.0892 1.87508 12.1993C2.28731 12.3067 2.8639 12.3121 3.47621 12.1697C3.72328 12.1107 3.95423 12.0355 4.16639 11.9468V11.3909C4.55848 11.2164 4.85121 11.0069 4.9828 10.8028V11.4581C5.30775 11.1788 5.43397 10.8995 5.39637 10.6954C5.35878 10.4886 5.14393 10.2979 4.73036 10.1905C4.52357 10.1368 4.2765 10.1072 4.00526 10.1099ZM5.74012 10.2496C5.81263 10.3543 5.86366 10.4725 5.89051 10.6041C5.92005 10.7652 5.90663 10.921 5.85829 11.074C6.12416 11.1439 6.36585 11.2379 6.57533 11.356C6.69349 11.4205 6.8036 11.4984 6.90028 11.5789C7.14198 11.5225 7.34608 11.442 7.4911 11.3453V11.8985C7.92347 11.6944 8.1222 11.4446 8.1222 11.2459C8.1222 11.0445 7.92347 10.7947 7.48841 10.5906C7.05604 10.3892 6.43299 10.2523 5.74012 10.2496ZM8.74256 10.3839C8.73988 10.3946 8.73988 10.408 8.73988 10.4215C8.73988 10.6551 8.94398 10.921 9.37904 11.1412C9.8141 11.3587 10.4425 11.5037 11.1434 11.5037C11.4979 11.5037 11.8336 11.4661 12.1371 11.4017V10.6793C11.7396 10.7867 11.2911 10.8458 10.8185 10.8458C10.0504 10.8458 9.35487 10.6927 8.8285 10.4295C8.79896 10.4134 8.76942 10.3973 8.74256 10.3839ZM8.81776 11.3856C8.76405 11.4742 8.73988 11.5601 8.73988 11.6434C8.73988 11.877 8.94398 12.1429 9.37904 12.3631C9.8141 12.5806 10.4425 12.7257 11.1434 12.7257C11.4979 12.7257 11.8336 12.6881 12.1371 12.6236V11.9146C11.8256 11.9737 11.4926 12.0059 11.1434 12.0059C10.3754 12.0059 9.67982 11.8529 9.15345 11.587C9.0326 11.5279 8.91981 11.4608 8.81776 11.3856ZM13.4664 11.3856C13.3107 11.4903 13.1737 11.5736 13.018 11.6407V12.3013C13.3751 12.0972 13.5443 11.8555 13.5443 11.6434C13.5443 11.5601 13.5202 11.4742 13.4664 11.3856ZM5.60853 11.5306C5.51454 11.6487 5.40443 11.7615 5.28089 11.8636C4.86195 12.2154 4.27113 12.5001 3.589 12.6585C3.34569 12.7149 3.10399 12.7498 2.87196 12.7686C2.9579 12.911 3.10909 13.0506 3.33038 13.1741C3.69642 13.3809 4.23353 13.5206 4.82972 13.5206C5.10096 13.5206 5.35877 13.491 5.5951 13.44V12.8814C5.96571 12.825 6.27723 12.723 6.47596 12.5914V13.0855C6.74452 12.8975 6.87342 12.6854 6.87342 12.484C6.87342 12.2503 6.69886 12.0006 6.33094 11.7938C6.13221 11.6837 5.88514 11.5924 5.60853 11.5306Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_3566_39456">
                            <rect width="13.75" height="13.75" fill="white" transform="translate(0.688477 0.310547)" />
                        </clipPath>
                    </defs>
                </svg>
                <p class="m-0 p-0">Campaign Status</p>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text ">
            Your 10 Day Cashable is off the charts!
        </div>
        <div class="w-100 d-flex justify-content-center">
            <img src="{{ asset('assets/emails/product-off-charts.svg') }}" class="cover-image" alt="">
        </div>
        {{-- <div class="w-100 d-flex justify-content-center">
            <p class="action-message">Your <span>10 Day Cashable </span> is about to expire. <br>What do you want to do?</p>
        </div> --}}
        {{-- <div class="w-100 d-flex justify-content-center">
            <p class="suggest-action">Here's what you can do to next...</p>
        </div> --}}
        <div class="w-100  ">
            <table class="custom-table w-100 table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Deposits Placed</th>
                        <th>Order Limit</th>
                        <th>% Raised</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10 Day Cashable</td>
                        <td>CAD 9,000,000</td>
                        <td>CAD 12,000,000</td>
                        <td>75%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login">We have prepared a product report for you to see how good the product is doing
            </p>
        </div>
        <div class="d-flex justify-content-center">
            <a href="" class="view-button">
                View Report
            </a>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="discover-login">Discover a world of exclusive GIC's waiting for you <a
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

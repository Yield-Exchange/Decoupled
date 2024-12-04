<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .check_chat_badge {
        display: none;
    }

    .sidebar-items {
        list-style: decimal !important;
    }

    .sidebar-items li,
    .sidebar-items li a {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6);

        /* Yield Exchange Text Styles/Table Body */
        font-family: Montserrat !important;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        cursor: pointer;
    }

    .sidebar-items li {
        padding: 20px;
        /* padding: 0; */
    }

    .signup-sidebar {
        background-image: url('/assets/signup/signup-sidebar-bg.svg');
    }
</style>
<!-- Main sidebar -->
<div class="sidebar signup-sidebar sidebar-main sidebar-expand-md">
    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <div class="mt-3 d-flex justify-content-center w-100">
            <img class="mt-3" src="{{ asset('assets/signup/yie-Logo.svg') }}" alt="" srcset="">
        </div>

        <!-- Main navigation -->
        <div class="mt-5">
            <ol class="sidebar-items" style="list-style-type: decimal !important">
                <li> <a href="/proceed-to-reg"> Getting Started</a></li>
                <li>Depositor Details</li>
                <li>K.Y.C</li>
                <li>User Details</li>
                <li>Account Password</li>
                <li>Terms and conditions</li>
                <li>Document Upload</li>
                <li>Account Verification</li>
                <li>Success</li>
            </ol>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->

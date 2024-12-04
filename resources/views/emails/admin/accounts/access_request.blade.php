@extends('emails.new-master')

@section('page-content')
<div style="padding: 0 5%; margin-right:auto; margin-left:auto" class="responsive">
    <div style="width: 100%; text-align: center; margin-top: 20px;">
        <div
            style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
            <img src="{{ asset('assets/emails/security-lock.png') }}" alt=""
                style="vertical-align: middle; margin-right:8px">
            <span
                style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                Access Request
            </span>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
        style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px;text-align:center"
        width="100%">
        Feature Access Request      
    </div>
    <div style="width: 100%; text-align: center;">
        <img src="{{ asset('assets/emails/secure-data-cuate.png') }}" class="cover-image" alt=""
            style="max-height: 400px; display: block; margin: 0 auto;">
    </div>
    <div style="width: 100%; display: table; margin: 0 auto;">
        <p class="unique-key-text"
            style="color: var(--yield-exchange-pallette-yield-exchange-black, var(--yield-exchange-colors-darks, #252525)); text-align: center; font-family: Montserrat; font-size: 25px; font-style: normal; font-weight: 300; line-height: normal;">
            {{$access_request->organization->name}} has requested access to <strong>{{$access_request->permissionDetails->name}} </strong> permission.Log in to edit their permissions
        </p>
    </div>
    <div>
        <p style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
            align="center">
            <a href="{{ url('/yie-admin?goto=users/users_onboard') }}"
                style="color:white; text-decoration-line:none;width: 70px; padding:10px; height: 20px; padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; display: inline-flex">
                Login
            </a>
        </p>
    </div>
</div>
@endsection
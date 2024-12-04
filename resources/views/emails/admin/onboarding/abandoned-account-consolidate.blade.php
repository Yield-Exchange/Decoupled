@extends('emails.new-master')
@section('page-content')
<div style="padding:0 5%; margin-right:auto; margin-left:auto;" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 4rem;">
            <div
                style="display: inline-flex; align-items: center; background: #44E0AA; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/fluent_people-add-16-filled.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    Registration Status
                </span>
            </div>
        </div>


        <div style="color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px; text-align:center ;width:100%;"
            align="center">
            Abandoned Registration
        </div>
        <div style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/emails/pending-accounts.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div class="w-100 d-flex justify-content-center">
            <p class="action-message"
                style="font-family:Montserrat; font-size:25px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">Kindly review the following abandoned accounts within the next 24 hours to ensure a swift onboarding progression</p>
        </div>
        
        

        <div class="w-100  " style="width:100%" width="100%">
            <table class="border-collapse :collapse;margin:0 auto !important; custom-table w-100 table table-hover"
                style="width: 100%; margin:0 auto !important; border-collapse :collapse; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:12px; padding:10px">
                <thead style="color:#5063F4; font-weight:700; padding:1rem; background-color:#EFF2FE"
                    bgcolor="#EFF2FE">
                    <tr>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Organization Name
                        </th>
                        <th style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                            align="start">Stage</th>
                        
                    </tr>
                </thead>
                <tbody
                    style="background-color:#F4F5F6; color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin-top:30px"
                    bgcolor="#F4F5F6">
                    {{-- {{json_encode($organization_data)}} --}}

                   @foreach ( $organization_data as $datum )
                        <tr>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $datum['name'] }}</td>
                            <td style="padding:0.6rem; text-align:start; border-bottom:3px solid white; border-top:none"
                                align="start">{{ $datum['stage']}}</td>
                            
                        </tr>   
                   @endforeach

                </tbody>
            </table>
        </div>





        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff" style="border-radius: 20px; padding: 10px;">
                                <a href="{{ url('/yie-admin/users/users_onboard') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 10px 30px; display: inline-block; width: 250px;">
                                    Review Accounts
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
@endsection

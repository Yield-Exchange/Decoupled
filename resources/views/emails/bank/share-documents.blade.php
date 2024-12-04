@extends('emails.new-master')
@section('page-content')
    <div style="padding: 0 5%;margin-right:auto;margin-left:auto" class="responsive">
        <div style="width: 100%; text-align: center; margin-top: 20px;">
            <div
                style="display: inline-flex; align-items: center; background: #5063F4; border-radius: 85.91px; padding: 4.436px 10.309px; text-transform: capitalize;">
                <img src="{{ asset('assets/emails/book-v2.png') }}" alt=""
                    style="vertical-align: middle; margin-right:8px">
                <span
                    style="color: #FFF; font-family: Montserrat; font-size: 14px; font-style: normal; font-weight: 400; line-height: 14px;">
                    GIC Purchase
                </span>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center my-2 campaign-status-text "
            style="width:100%; color:#5063F4; font-family:Montserrat; font-size:38px; font-style:normal; font-weight:700; line-height:42px;text-align:center"
            width="100%">
            Review Documents
        </div>

        <div style="width:80%; margin-left: auto; margin-right:auto;">
            <img src="{{ asset('assets/emails/Profile-data-cuate.png') }}" class="cover-image" alt=""
                style="max-height: 400px; display: block; margin: 0 auto;">
        </div>
        <div class="w-100 d-flex justify-content-center" style="width:80%; margin-left: auto; margin-right:auto;"
            width="100%">

            <p class="action-message"
                style="font-family:Montserrat; font-size:24px; font-style:normal; font-weight:300; line-height:normal; text-align:center"
                align="center">
                <span style="font-weight: 700; color:#5063F4;">{{ $name }}</span> would like to share the following
                documents with you to facilitate their GIC Purchase
            </p>
        </div>

        <div class="" style="width:550px; margin-left: auto; margin-right:auto;">
            <table class="custom-table w-100 table table-hover"
                style=" width:100%; font-family:Montserrat; font-size:16px; font-style:normal; line-height:normal; margin:5px auto; padding:10px"
                width="100%">
                {{-- @php @endphp --}}
                @foreach ($documents as $document)
                    {{-- @foreach ($chunkedDocuments as $chunk) --}}
                    <tr style="box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.15); background: #EFF2FE; margin:30px">
                        <td
                            style="padding: 15px 10px; align-items: center;text-align:start !important; color: #000; font-family: Montserrat; font-size: 16px; font-style: normal; font-weight: 500; line-height: 18px; margin:10px; text-transform: capitalize;">
                            <img src="{{ asset('assets/emails/mdi_attachment-vertical.png') }}" style="margin-right: 10px;">
                            <a href="{{ url(str_replace('\\', '', $document->file_name)) }}"
                                style="text-decoration: none; color: #000;">{{ $document->document_type }}</a>
                        </td>
                        {{-- @endforeach --}}
                    </tr>
                @endforeach
            </table>
        </div>



        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="border-bottom:3px solid white; border-top:none">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center" bgcolor="#ffffff"
                                style="border-bottom:3px solid white; border-top:none; border-radius:20px; padding:10px">
                                <a href="{{ url('active-deposits') }}"
                                    style="color: #ffffff; font-family: Montserrat, sans-serif; font-size: 16px; font-weight: 400; text-decoration: none; background-color: #5063F4; border-radius: 20px; padding: 6px 30px; display: inline-block; width: 250px;">
                                    View Documents
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="w-100 d-flex justify-content-center" style="width:100%" width="100%">
            <p class="discover-login"
                style="color:#252525; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; margin:1rem; text-align:center"
                align="center">Discover a world of exclusive GIC’s waiting for you <a
                    href="{{ url('request-an-account')}}"><span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">Sign
                        Up </span></a> Or
                <a href="{{url('login')}}"> <span
                        style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:300; line-height:normal; text-decoration-line:underline">
                        Log In</span></a>
            </p>
        </div>
    </div>
@endsection

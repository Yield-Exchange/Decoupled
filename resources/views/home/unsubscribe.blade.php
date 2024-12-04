<!DOCTYPE html>
<html lang="en"
    style="box-sizing: border-box;font-family: sans-serif;line-height: 1.15;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: transparent;">

<head style="box-sizing: border-box;">
    <meta charset="UTF-8" style="box-sizing: border-box;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" style="box-sizing: border-box;">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" style="box-sizing: border-box;">


    <link rel="preconnect" href="https://fonts.googleapis.com" style="box-sizing: border-box;">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin style="box-sizing: border-box;">

    <title style="box-sizing: border-box;"></title>
    <style>
        @media only screen and (min-width: 768px) {
           .responsive {
               max-width: 550px;
           }
        
       }
       @media only screen and (max-width: 767px) {
           .responsive {
               /* padding: 0 10%; */
               max-width: 550px;
               /* margin-left:auto;margin-right:auto;padding 0 5% */
           }
        }
        @media only screen and (min-width: 768px) {
           .responsive {
               max-width: 550px;
               /* margin-left:auto;margin-right:auto;padding 0 5% */
           }
       }
       @media only screen and (min-width: 1024px) {
           .responsive {
               max-width: 650px;
               /* margin-left:auto;margin-right:auto;padding 0 5% */
           }
       }
   
       @media only screen and (min-width: 1536px) {
           .responsive {
               max-width: 800px;
               /* margin-left:auto;margin-right:auto;padding 0 5% */
           }
       }
       .a6S{display:none !important;}
       .a5q{
        display: none !important;
       }
   </style>
</head>

<body>
    <p>
        @yield('styles')


    </p>
    <div class="container my-4"
        style="font-family: Montserrat !important; box-sizing: border-box;width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;margin-top: 1.5rem!important;margin-bottom: 1.5rem!important;min-width: 992px!important;">
        <div class="w-100 d-flex justify-content-center"
            style="box-sizing: border-box; display: flex !important; -ms-flex-pack: center !important; justify-content: center !important; width: 100% !important;">
            <img src="{{ asset('assets/emails/yie-logo.png') }}" alt="" class="mx-auto d-block"
                style="box-sizing: border-box; vertical-align: middle; border-style: none; page-break-inside: avoid;margin:auto;height: 49.486px;">
        </div>

        <div class="w-100 d-flex justify-content-center" 
            style="box-sizing: border-box; display: table; width: 100%; margin-bottom: .5rem;">
            <div style="padding: 0 5%; margin-right:auto; margin-left:auto; width:40%">
                <div style="display: flex; justify-content:center">
                    <img src="{{asset('assets/emails/Documents-cuate.png')}}" alt="email documents cuate"/>
                </div>
                <div style="display:flex; justify-content:center; flex-direction:column">
                    <p>We are sad to see you go. Kindly select what emails you would want to unsubscribe from on the email address <a href="mailto:{{App\CustomEncoder::urlValueDecrypt($user_email)}}">{{App\CustomEncoder::urlValueDecrypt($user_email)}}</a></p>
                
                    <form  method="POST" action="{{ url('/unsubscribe/emails') }}">
                        @csrf 
                        <input type="hidden" name="user_email" value="{{$user_email}}">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="email" value="{{$email}}">
                        <div>
                            <input
                                type="radio"
                                id="no-ctive-campaign"
                                name="marketing_email"
                                value="0" />
                            <label for="no-ctive-campaign">{{ucwords(str_replace('-', ' ', $email))}} Marketing Email</label>
                        </div>
                        
                        <div style="margin-top:20px ">
                            <input
                                type="radio"
                                id="all-campaign"
                                name="marketing_email"
                                value="1"
                                checked />
                            <label for="all-campaign">All Yield Exchange Marketing Emails</label>
                        </div>
                        
                        <div class="d-flex;" style="display:flex; justify-content: right; margin: 20px">
                            <a href="{{url('/')}}" type="button"  style="text-decoration:none; border-radius: 32px;border: 2px solid #9CA1AA; color: #9CA1AA;font-family: Montserrat; font-size: 16px;font-style: normal; font-weight: 400;line-height: 20px;text-transform: capitalize; padding: 10px 30px; cursor: pointer;">Cancel</a>
                            <button type="submit" style="margin-left: 20px; border-radius: 20px;background: #5063F4; color: #FFF;font-family: Montserrat; font-size: 16px;font-style: normal;font-weight: 400; line-height: 20px; text-transform: capitalize; padding:10px 30px;  cursor: pointer; border-style:none">Unsubscribe</button>
                        </div>
                    </form>
                </div>

                <div class="footer" style="margin-top:40px;box-sizing: border-box; width:100%">
                    <div style="display:flex; justify-content:space-between; margin:0 auto">
                        <div style="display: inline-block; margin-right: 20px;">
                            © {{ Date('Y') }} 
                        </div>
                        <div style="display: inline-block;">
                            <a href="https://yieldexchange.ca/" class="links"
                                style="color:#5063F4; font-family:Montserrat; font-size:16px; font-style:normal; font-weight:700; line-height:normal; text-decoration-line:underline; background-color:transparent; text-decoration:underline">
                                Yield
                                Exchange</a>
                        </div>
                    </div>
                </div>
                
        </div>
        </div>

        <script style="box-sizing: border-box;"></script>
        <script>
            function updateActionAndSubmit() {
                var selectedValue = document.querySelector('input[name="marketing_email"]:checked').value;
                var actionURL = "{{ url('/unsubscribe/'.$email.'/' . $user_id . '/' . $email) }}";
                actionURL += "?from=2&marketing_email=" + selectedValue;
                document.getElementById("unsubscribeForm").action = actionURL;
                document.getElementById("unsubscribeForm").submit();
            }
        </script>
    </div>
</body>

</html>
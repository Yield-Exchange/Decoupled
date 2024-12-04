<bank-campaign :products="{{ json_encode($products) }}" :depositors="{{ json_encode($depositors) }}" :userloggedin="{{ $userLoggedIn }}"
               :timezone="{{ json_encode($timezone) }}" :provinces="{{ $provinces }}" :unformattedusertimezone="{{json_encode($unformattedusertimezone)}}" :formattedtimezone="{{json_encode($formattedtimezone)}}"></bank-campaign>

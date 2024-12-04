@if( $user->userCan('universal/organization-setting/page-access') )
    <form method="post" class="timezone_switcher_form" action="#">
        <div class="input-group" style="opacity: 0.80; background: #F8FAFF; border-radius: 9px; border: 0.50px rgba(37, 37, 37, 0.20) solid;">
              <span class="input-prepend-icon">
                <img src="{{ asset('assets/dashboard/icons/timezone.svg') }}" style="margin-top: 10px" />
              </span>
            <select name="timezone" class="form-control switch_timezone" required style="border: none;background: transparent!important;">
                <option value="">Select Timezone</option>
                @php
                    $timezones = timezonesList();
                    $my_timezone = $user->timezone;
                @endphp
                @foreach($timezones as $key => $timezone)
                    <option {{  strcmp($my_timezone, $key) == 0 ? "selected" : "" }} value="{{ $key }}">{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
    </form>
@endif
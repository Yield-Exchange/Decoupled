<div class="row" style="margin-bottom: 20px">
    @php
        $depositor = $offer->invited->depositRequest->customUser;
        $organization = $offer->invited->depositRequest->organization;
    @endphp

    <div class="col-md-5" style="height: 100%">
        <div class='card p-3 ml-1 mb-0'>
            <div class="row">
                <div class="col-12">
                    <h5 style="color:#2CADF5;font-weight:bold;text-transform:uppercase;margin-right: 50px;">Investor Information</h5>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;"  class="invisible">Name</p></div>
                        <div class="col-md-9">
                            <a {{$organization->demographicData ? 'href=https://'.$organization->demographicData->website.' target="_blank"' : '' }} ><span style="font-weight:bold;color:darkblue;text-transform:capitalize;"> {{ $organization->name }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;" class="invisible">Location</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $organization->demographicData ? $organization->demographicData->city. ', ' .$organization->demographicData->province  : ''  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;" class="invisible">Industry</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $organization->naics_code_id ? $organization->naics_code : ''  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Name</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $depositor->name }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Job Title</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ optional($depositor->demographicData)->job_title  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Email</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;">{{ $depositor->email }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Telephone</p></div>
                        <div class="col-md-9"><span style="font-weight:bold">{{ formatPhone(optional($depositor->demographicData)->phone) }}</span></div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Description</p></div>
                        <div class="col-md-9">
                            <span style="font-weight:bold">
                                {{ $organization->demographicData ? substr($organization->demographicData->description, 0, 40) : ''  }}
                            </span>
                            @if (strlen($organization->demographicData->description) > 40)
                                @include('dashboard.summary-model', ['organization' => $organization])
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div style="position: absolute;top:20%;left:4%;">
                @if ( !empty($organization->logo) )
                    <img src="{{ url('image/'.$organization->logo) }}" width="80" height="80" alt=""  style="border-radius: 50%;"/>
                @else
                    <div class="i-initial-inverse-big" style="width: 80px;height: 80px;color:#fff;line-height: 75px; font-size: 50px;text-transform:uppercase;">
                        {{ !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('dashboard.offer_summary')
</div>

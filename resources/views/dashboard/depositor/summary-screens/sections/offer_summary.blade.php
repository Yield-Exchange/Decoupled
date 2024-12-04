<div class="row" style="margin-bottom: 20px">
    @php
        $bank = $offer->invited->bank;
        $organization= $bank;
        $counterOffer = $offer->counterOffers->where('status','!=','COUNTERED')->first();
        $offerBeforeCounter = $offer->offerBeforeCounter();
        if($counterOffer && in_array($counterOffer->status,['CLOSED_ON_COUNTERED'])){
            $offerBeforeCounter = $counterOffer;
        }

        if( $counterOffer && !in_array($counterOffer->status,['PENDING','DECLINED'])){
            $counterOffer=null;
        }
    @endphp

    <div class="col-md-5">
        <div class='card p-3 ml-1' style="height: 100%;">
            <div class="row">
                <div class="col-12">
                    <h5 style="color:#2CADF5;font-weight:bold;text-transform:uppercase;margin-right: 50px;">Financial Institution</h5>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;" class="invisible">Name</p></div>
                        <div class="col-md-9">
                            <a {{$organization->demographicData ? 'href=https://'.$organization->demographicData->website.' target="_blank"' : '' }} ><span style="font-weight:bold;color:darkblue;text-transform:capitalize;">{{ $organization->name }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;"  class="invisible">Industry</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ $organization->fi_type_id ? $organization->fi_type : $organization->type  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;"  class="invisible">Insurance</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ !empty($organization->depositCreditRating->insuranceRating) ? $organization->depositCreditRating->insuranceRating->description : ""}}</span></div>
                    </div>
                </div>
                {{-- <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Credit Rating</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ !empty($organization->depositCreditRating->creditRating) ? $organization->depositCreditRating->creditRating->description : ''  }}</span></div>
                    </div>
                </div> --}}
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Name</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ optional($offer->customUser)->name }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Job Title</p></div>
                        <div class="col-md-9"><span style="font-weight:bold;text-transform:capitalize;">{{ optional($offer->customUser->demographicData)->job_title  }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Email</p></div>
                        <div class="col-md-9"><span style="font-weight:bold">{{ optional($offer->customUser)->email }}</span></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3"><p style="font-weight:bold;">Telephone</p></div>
                        <div class="col-md-9"><span style="font-weight:bold">{{  optional($offer->customUser->demographicData)->phone  }}</span></div>
                    </div>
                </div>
            </div>

            <div style="position: absolute;top:23%;left:4%;">
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
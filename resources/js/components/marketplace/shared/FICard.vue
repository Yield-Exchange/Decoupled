<template>
    <div>

        <div class="box p-3 m-3 h-17em" :style="market_offer.is_featured == 1 ? 'border-color: lightgreen;' : ''">
            <div v-if="market_offer" style="width: 15em;">
                <OfferTopCard :data="market_offer" />
            </div>

            <!-- Empty Featured Offer -->
            <div v-if="notFeatured || !market_offer" style="width: 15em;">
                <h5 class="font-w-b text-black mb-5 mx-2">No Featured GIC offer selected</h5>
                <p class="card-text my-1 text-gray mt-5 mx-2">Select a GIC offer to promote to all depositor
                </p>
            </div>

            <!-- Empty Offer -->
            <div v-if="noOffer" style="width: 13em;">
                <h5 class="font-w-b text-black mb-5 mx-2">No GIC Offer Created</h5>
                <p class="card-text my-1 text-gray mt-5 mx-2">Create a GIC offer above to share with our
                    depositors
                </p>
            </div>
        </div>

    </div>
</template>

<style>
.text-black {
    color: #525252 !important;
}

.text-gray {
    color: #707070;
}

.font-w-b {
    font-weight: 900;
}

.font-s-1 {
    font-size: .8em;
}

.image {
    width: 60px;
    border-radius: 100%;
    margin: 2px;
}

.rate {
    font-size: 2.5em;
    vertical-align: middle;
    margin: 2px;
}

.w-25-f {
    width: 21em;
}

.disable-btn {
    background: rgba(255, 255, 255, 0);
    border-radius: 15px;
    backdrop-filter: blur(2.5px);
    -webkit-backdrop-filter: blur(2.5px);
    border: 1px solid rgba(0, 0, 0, 0.125);
    height: 42px;
}

.box {
    border: 1px solid #dddddd;
    border-radius: 10px;
    margin: 0px 10px;
}

.h-17em {
    height: 17em !important;
}

.border-1-green {
    border: 2px solid #44E0AA !important;
}
</style>


<script>
import OrganizationAvatar from "../../shared/OrganizationAvatar";
import OfferTopCard from "./OfferTopCard";
export default {
    components: {
        OfferTopCard,
        OrganizationAvatar,
    },
    props: ["isNotFeatured", "isNotOffer", "market_offer", "organization"],

    data() {
        return {
            "expiredDate": ""
        }
    },
    mounted() {

    },
    computed: {
        notFeatured() {
            return (this.isNotFeatured == 'true') ? true : false;
        },

        noOffer() {
            return (this.isNotOffer == 'true') ? true : false;
        },

        parseOrganization() {
            return (this.organization) ? JSON.parse(this.organization) : "";
        },
    },

    methods: {

    },

}

</script>
